<?php

namespace Packages\Accounting\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Packages\Accounting\Models\User;
use Packages\Accounting\Models\Account;
use Packages\Accounting\Models\AccountTransaction;
use Packages\Accounting\Models\PaymentMethod;
use Packages\Accounting\Models\WithdrawalMethod;
use Illuminate\Http\Request;
use Packages\Accounting\Services\AccountService;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        if ($user->id != $request->user()->id)
            abort(404);

        if (!$user->account) {
            AccountService::create($user);
            $user->load('account');
        }

        $paymentMethods = PaymentMethod::where('status', PaymentMethod::STATUS_ACTIVE)->get();
        $withdrawalMethods = WithdrawalMethod::where('status', WithdrawalMethod::STATUS_ACTIVE)->get();

        $transactions = $user
            ->account
            ->transactions()
            ->orderBy('id', 'desc')
            ->paginate($this->rowsPerPage);

        return view('accounting::pages.frontend.account.show', [
            'account'               => $user->account,
            'payment_methods'       => $paymentMethods,
            'withdrawal_methods'    => $withdrawalMethods,
            'transactions'          => $transactions
        ]);
    }
}
