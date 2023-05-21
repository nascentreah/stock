<?php

namespace Packages\Accounting\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Packages\Accounting\Models\User;
use Packages\Accounting\Http\Requests\Frontend\StoreDeposit;
use Packages\Accounting\Models\Account;
use Packages\Accounting\Models\Deposit;
use Packages\Accounting\Models\PaymentMethod;
use Packages\Accounting\Models\Sort\Frontend\DepositSort;
use Packages\Accounting\Services\CoinpaymentsPaymentService;
use Packages\Accounting\Services\DepositService;
use Packages\Accounting\Services\PaymentMethodService;
use Packages\Accounting\Services\StripePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepositController extends Controller
{
    /**
     * Deposits listing
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, User $user)
    {
        $sort = new DepositSort($request);

        $deposits = Deposit::select(
                'deposits.*',
                'accounts.id AS account_id',
                'payment_methods.code AS payment_method_code',
                'acc_currencies.code AS account_currency_code'
            )
            ->join('accounts', 'deposits.account_id', '=', 'accounts.id')
            ->join('payment_methods', 'deposits.payment_method_id', '=', 'payment_methods.id')
            ->join('currencies AS acc_currencies', 'accounts.currency_id', '=', 'acc_currencies.id')
            ->where('accounts.user_id', $user->id) // only current user deposits
            ->orderBy($sort->getSortColumn(), $sort->getOrder())
            ->paginate($this->rowsPerPage);

        return view('accounting::pages.frontend.deposits.index', [
            'deposits'      => $deposits,
            'sort'          => $sort->getSort(),
            'order'         => $sort->getOrder(),
        ]);
    }

    /**
     * Display deposit form
     *
     * @param Request $request
     * @param Account $account
     * @param PaymentMethod $paymentMethod
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, User $user, PaymentMethod $paymentMethod) {
        if ($user->id != $request->user()->id)
            abort(404);

        // check that method is enabled for deposits
        if ($paymentMethod->status != PaymentMethod::STATUS_ACTIVE)
            abort(404);

        // check that payment gateway keys are provided
        if ($paymentMethod->paymentGateway->code == 'paypal' && (!config('accounting.paypal.user') || !config('accounting.paypal.password') || !config('accounting.paypal.signature'))
            ||
            $paymentMethod->paymentGateway->code == 'stripe' && (!config('accounting.stripe.public_key') || !config('accounting.stripe.public_key'))
            ||
            $paymentMethod->paymentGateway->code == 'coinpayments' && (!config('accounting.coinpayments.public_key') || !config('accounting.coinpayments.private_key'))) {
            // display error
            $request->session()->flash('error', __('accounting::text.gateway_not_configured', ['gateway' => ucfirst($paymentMethod->paymentGateway->code)]));
        }

        // get allowed currencies for selected payment method
        $paymentMethodService = new PaymentMethodService($paymentMethod);
        $paymentMethodCurrencies = $paymentMethodService->acceptedCurrencies();

        return view('accounting::pages.frontend.deposits.create', [
            'account'                   => $user->account,
            'payment_method'            => $paymentMethod,
            'payment_method_currencies' => $paymentMethodCurrencies,
        ]);
    }

    /**
     * Handle deposit form submission
     *
     * @param StoreDeposit $request
     * @param Account $account
     * @param PaymentMethod $paymentMethod
     * @return $this
     */
    public function store(StoreDeposit $request, User $user, PaymentMethod $paymentMethod) {
        $paymentServiceClass = '\Packages\Accounting\Services\\' . ucfirst($paymentMethod->paymentGateway->code) . 'PaymentService';

        if ($user->id != $request->user()->id)
            abort(404);

        // check that method is enabled for deposits and deposit service is implemented
        if ($paymentMethod->status != PaymentMethod::STATUS_ACTIVE || !class_exists($paymentServiceClass))
            abort(404);

        // dynamically instantiate payment service class
        $paymentService = new $paymentServiceClass(config('accounting.' . $paymentMethod->paymentGateway->code));

        $account = $user->account;
        $accountCurrency = $account->currency->code;

        $paymentDetails = [
            'returnUrl' => route('frontend.deposits.complete', [$user, $paymentMethod]),
            'ipnUrl' => route('webhook.deposits.event'),
            'description' => __('accounting::text.deposit_description', ['website' => __('app.app_name')]),
        ];

        // Fill extra payment details depending on gateway and method
        if ($paymentMethod->paymentGateway->code == 'stripe') {
            $paymentDetails['method'] = $paymentMethod->code;
            $paymentDetails['owner'] = [
                'name' => $user->name,
                'email' => $user->email
            ];
            // source for card payments is created on the frontend and passed to the backend
            if ($paymentMethod->code == 'card') {
                $paymentDetails['source_id'] = $request->source_id;
            } elseif ($paymentMethod->code == 'sofort') {
                $paymentDetails['country'] = $request->country;
            }
        } elseif ($paymentMethod->paymentGateway->code == 'coinpayments') {
            $paymentDetails['userName'] = $user->name;
            $paymentDetails['userEmail'] = $user->email;
            $paymentDetails['accountCurrency'] = $accountCurrency;
        }

        // by default deposit amount equals payment amount
        $paymentAmount = $request->amount;
        $paymentFxRate = 1;

        // if deposit currency differs from payment currency
        if ($request->currency != $accountCurrency) {
            if ($paymentMethod->paymentGateway->code != 'coinpayments') {
                // retrieve payment currency object
                $paymentCurrency = Currency::where('code', $request->currency)->firstOrFail();
                $paymentFxRate = $paymentCurrency->rate;
                // calculate required payment amount in payment currency to pass it to a gateway
                $paymentAmount = $request->amount * $paymentFxRate;
            }
        }

        // init payment
        try {
            $paymentService->initializePayment(
                $paymentAmount,
                $request->currency,
                $paymentDetails
            );
        // catch any exceptions
        } catch (\Exception $exception) {
            return back()->withInput()->withErrors(
                ['method' => $exception->getMessage()]
            );
        }

        // for coinpayments FX rate is known only after payment is initialized
        if ($paymentMethod->paymentGateway->code == 'coinpayments') {
            $paymentFxRate = $paymentService->amount() / $request->amount;
        }

        // save deposit record to the DB
        $deposit = new Deposit();
        $deposit->account()->associate($account);
        $deposit->paymentMethod()->associate($paymentMethod);
        $deposit->payment_amount = $paymentService->amount();
        $deposit->payment_currency_code = $paymentService->currency();
        $deposit->payment_fx_rate = $paymentFxRate;
        $deposit->amount = $request->currency == $accountCurrency ? $paymentService->amount() : $request->amount;
        $deposit->status = Deposit::STATUS_PENDING;
        $deposit->external_id = $paymentService->paymentId();

        // if deposit is saved successfully in the local DB redirect to a payment gateway to complete the payment
        if ($deposit->save())
            $paymentService->redirectToGateway();
    }

    /**
     * Handle callback request by payment gateway
     *
     * @param Request $request
     * @param Account $account
     * @param PaymentMethod $paymentMethod
     * @return \Illuminate\Http\RedirectResponse
     */
    public function complete(Request $request, User $user, PaymentMethod $paymentMethod) {
        Log::info($request->all());

        $paymentServiceClass = '\Packages\Accounting\Services\\' . ucfirst($paymentMethod->paymentGateway->code) . 'PaymentService';

        // check that method is enabled for deposits and deposit service is implemented
        if ($paymentMethod->status != PaymentMethod::STATUS_ACTIVE ||
            !class_exists($paymentServiceClass))
            abort(404);

        // dynamically instantiate deposit service class
        $paymentService = new $paymentServiceClass(config('accounting.' . $paymentMethod->paymentGateway->code));

        // Fill payment details depending on gateway
        if ($paymentMethod->paymentGateway->code == 'paypal') {
            $paymentDetails = [
                'token'     => $request->token,
                'payer_id'  => $request->PayerID
            ];
        } elseif ($paymentMethod->paymentGateway->code == 'stripe') {
            $paymentDetails = [
                'source_id'      => $request->source,
                'client_secret'  => $request->client_secret
            ];
        }

        // process payment
        try {
            $paymentService->processPayment($paymentDetails);
            // catch any exceptions
        } catch (\Exception $exception) {
            return back()->withInput()->withErrors(
                ['method' => $exception->getMessage()]
            );
        }

        // look up the deposit record in the local DB
        $depositService = new DepositService($paymentService->paymentId());

        // payment processed successfully
        if ($paymentService->completed()) {
            $depositService->complete();
            return redirect()->route('frontend.deposits.index', [$user])->with('success', __('accounting::text.deposit_completed'));
        // payment is processed, but pending clearing
        } elseif ($paymentService->pending()) {
            // don't update deposit in the local DB as it's already in the pending state
            return redirect()->route('frontend.deposits.index', [$user])->with('success', __('accounting::text.deposit_pending'));
        // cancel deposit (for PayPal it happens when user clicks on cancel button on the gateway page)
        } else {
            $depositService->cancel();
            return redirect()->route('frontend.deposits.index', [$user])->with('success', __('accounting::text.deposit_cancelled'));
        }
    }

    /**
     * Handle async events (webhooks setup is required)
     *
     * @param Request $request
     */
    public function event(Request $request) {
        $payload = $request->getContent();
        Log::info($payload);

        // Stripe IPN
        if ($request->header('STRIPE_SIGNATURE')) {
            $paymentService = new StripePaymentService(config('accounting.stripe'));
            // verify stripe signature to ensure the request is authentic
            if ($paymentService->validSignature($payload, $request->header('STRIPE_SIGNATURE'))) {
                $event = json_decode($payload);
                // check that it's an event object
                if (is_object($event) && isset($event->type)) {
                    // charge.succeeded (payment completed)
                    if ($event->type == 'charge.succeeded' && $event->data->object->status == 'succeeded') {
                        $source = $event->data->object->source;
                        $depositService = new DepositService($source->id);
                        // if deposit is still in pending status (it will be automatically set to completed for synchronous payment methods)
                        $depositService->complete();
                    }
                }

                return response()->make('OK', 200);
            }
        // coinpayments.net IPN
        } elseif ($request->header('HMAC')) {
            if ($request->ipn_mode == 'hmac' && $request->merchant == config('accounting.coinpayments.merchant_id')) {
                $paymentService = new CoinpaymentsPaymentService(config('accounting.coinpayments'));
                // verify stripe signature to ensure the request is authentic
                if ($paymentService->validSignature($payload, $request->header('HMAC'))) {
                    /*Payments will post with a 'status' field, here are the currently defined values:
                    -2 = PayPal Refund or Reversal
                    -1 = Cancelled / Timed Out
                    0 = Waiting for buyer funds
                    1 = We have confirmed coin reception from the buyer
                    2 = Queued for nightly payout (if you have the Payout Mode for this coin set to Nightly)
                    3 = PayPal Pending (eChecks or other types of holds)
                    100 = Payment Complete. We have sent your coins to your payment address or 3rd party payment system reports the payment complete
                    For future-proofing your IPN handler you can use the following rules:
                    <0 = Failures/Errors
                    0-99 = Payment is Pending in some way
                    >=100 = Payment completed successfully*/
                    if ($request->status >= 100 || $request->status == 2) {
                        $depositService = new DepositService($request->txn_id);
                        $depositService->complete();
                    }

                    return response()->make('OK', 200);
                }
            }
        }

        // report an error
        return response()->make('ERROR', 400);
    }
}
