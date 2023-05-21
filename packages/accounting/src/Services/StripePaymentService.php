<?php

namespace Packages\Accounting\Services;

use Illuminate\Support\Facades\Log;
use Stripe\Charge;
use Stripe\Source;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Error\SignatureVerification;

/**
 * Stripe API implementation
 *
 * Class StripePayment
 */
class StripePaymentService implements PaymentService
{
    private $publicKey;
    private $secretKey;
    private $webhookSecret;
    private $response = array();
    private $redirectUrl;
    private $zeroDecimalCurrencies = [
        'BIF','CLP','DJF','GNF','JPY','KMF','KRW','MGA','PYG','RWF','UGX','VND','VUV','XAF','XOF','XPF'
    ];

    /**
     * StripePayment constructor.
     */
    public function __construct($config)
    {
        $this->publicKey = $config['public_key'];
        $this->secretKey = $config['secret_key'];
        $this->webhookSecret = $config['webhook_secret'];

        Stripe::setApiKey($this->secretKey);
    }

    /**
     * @param $quantity
     * @param $price
     * @param $currency
     * @param array $paymentDetails
     */
    public function initializePayment($amount, $currency, array $paymentDetails = NULL)
    {
        // for card payments source is already created on the frontend
        if ($paymentDetails['method'] == 'card') {
            // retrieve card source
            if ($this->response['source'] = Source::retrieve($paymentDetails['source_id'])) {

//                Log::info(gettype($this->response['source']->amount) . ',' . gettype($this->stripeAmount($amount, $currency)));
                Log::info($this->response['source']->amount . '|' . $this->stripeAmount($amount, $currency) . ' ' . $this->currency() . '|' . $currency);
//                Log::info(intval($this->response['source']->amount) . '|' . $this->stripeAmount($amount, $currency) . ' ' . $this->currency() . '|' . $currency);

                if ($this->response['source']->amount != $this->stripeAmount($amount, $currency) || $this->currency() != $currency) {
                    throw new \Exception(__('accounting::text.error_amount_currency'));
                }

                // redirect to local page in case payment can be captured right away
                $this->redirectUrl = $paymentDetails['returnUrl'] . '?source=' . $paymentDetails['source_id'] . '&client_secret=' . $this->response['source']->client_secret;
                $verificationRequired = isset($this->response['source']->card->three_d_secure) && in_array($this->response['source']->card->three_d_secure, ['required', 'recommended', 'optional']) ? TRUE : FALSE;

                // If 3D secure protection enabled
                if ($verificationRequired) {
                    $paymentDetails['three_d_secure'] = [
                        'card' => $this->response['source']->id,
                    ];
                    // create 3D source
                    $this->response['source'] = $this->createSource($amount, $currency, $paymentDetails);
                    // redirect to 3D secure verification page
                    if ($this->response['source']->status != 'failed'
                        && $this->response['source']->redirect->status != 'succeeded'
                        && !$this->response['source']->three_d_secure->authenticated
                        && $this->response['source']->redirect->url != ''
                    ) {
                        $this->redirectUrl = $this->response['source']->redirect->url;
                    }
                }
            }
        // for other payment nethods source needs to be created
        } else {
            $this->response['source'] = $this->createSource($amount, $currency, $paymentDetails);
            // redirect to complete payment
            if ($this->response['source']->status != 'failed'
                && $this->response['source']->redirect->status != 'succeeded'
                && $this->response['source']->redirect->url != ''
            ) {
                $this->redirectUrl = $this->response['source']->redirect->url;
            }
        }

        Log::info($this->response);
    }


    /**
     * Create Stripe source object
     * @param $amount
     * @param $currency
     * @param $paymentDetails
     * @return \Stripe\Source
     */
    private function createSource($amount, $currency, $paymentDetails)
    {
        // check Zero-decimal currencies
        // https://stripe.com/docs/currencies#zero-decimal
        $amount = $this->stripeAmount($amount, $currency);

        $sourceOptions = [
            'amount' => $amount,
            'currency' => $currency,
            'type' => $paymentDetails['method'],
            'owner' => $paymentDetails['owner'],
            'redirect' => [
                'return_url' => $paymentDetails['returnUrl'],
            ]
        ];

        if ($paymentDetails['method'] == 'card' && isset($paymentDetails['three_d_secure'])) {
            $sourceOptions['type'] = 'three_d_secure';
            $sourceOptions['three_d_secure'] = $paymentDetails['three_d_secure'];
        } elseif ($paymentDetails['method'] == 'sofort') {
            $sourceOptions['sofort'] = [
                'country' => $paymentDetails['country'],
                'statement_descriptor' => $paymentDetails['description'],
            ];
        }

        return Source::create($sourceOptions);
    }

    /**
     * Should be bound to successUrl
     *
     * @param array $paymentDetails
     */
    public function processPayment(array $paymentDetails)
    {
        if ($this->response['source'] = Source::retrieve($paymentDetails['source_id'])) {
            if ($this->response['source']->amount > 0
                && $paymentDetails['client_secret'] == $this->response['source']->client_secret
                && $this->response['source']->status == 'chargeable'
            ) {
                $this->response['charge'] = Charge::create([
                    'amount' => $this->response['source']->amount,
                    'currency' => $this->response['source']->currency,
                    'source' => $paymentDetails['source_id']
                ]);
            }
        }

        Log::info($this->response);
    }

    public function amount()
    {
        $amount = isset($this->response['charge']) ?
            $this->response['charge']->amount :
            (isset($this->response['source']) ?
                $this->response['source']->amount :
                0);

        return in_array($this->currency(), $this->zeroDecimalCurrencies) ? $amount : $amount / 100;
    }

    private function stripeAmount($amount, $currency)
    {
        return in_array($currency, $this->zeroDecimalCurrencies) ? intval(round($amount, 0)) : intval(round($amount * 100, 0));
    }

    public function currency()
    {
        return isset($this->response['charge']) ?
            strtoupper($this->response['charge']->currency) :
            (isset($this->response['source']) ?
                strtoupper($this->response['source']->currency) :
                NULL);
    }

    public function redirectToGateway()
    {
        header('Location: ' . $this->redirectUrl);
        exit;
    }

    public function paymentId()
    {
        return isset($this->response['source']) ? $this->response['source']->id : NULL;
    }

    public function completed()
    {
        return isset($this->response['charge']) &&
        $this->response['charge']->paid == 1 &&
        $this->response['charge']->status == 'succeeded' ? TRUE : FALSE;
    }

    public function pending()
    {
        return isset($this->response['charge']) && !$this->response['charge']->paid && $this->response['charge']->status == 'pending' ? TRUE : FALSE;
    }

    public function validSignature($content, $header) {
        try {
            $event = Webhook::constructEvent(
                $content, $header, $this->webhookSecret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            return FALSE;
        } catch(SignatureVerification $e) {
            // Invalid signature
            return FALSE;
        }

        return TRUE;
    }
}