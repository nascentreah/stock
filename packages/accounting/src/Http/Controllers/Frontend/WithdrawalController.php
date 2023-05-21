<?php

namespace Packages\Accounting\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Packages\Accounting\Models\User;
use Packages\Accounting\Http\Requests\Frontend\StoreWithdrawal;
use Packages\Accounting\Models\Sort\Frontend\WithdrawalSort;
use Packages\Accounting\Models\Withdrawal;
use Packages\Accounting\Models\WithdrawalMethod;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    /**
     * Deposits listing
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, User $user)
    {
        $sort = new WithdrawalSort($request);

        $withdrawals = Withdrawal::select(
                'withdrawals.*',
                'withdrawal_methods.code AS withdrawal_method_code',
                'acc_currencies.code AS account_currency_code'
            )
            ->join('accounts', 'withdrawals.account_id', '=', 'accounts.id')
            ->join('withdrawal_methods', 'withdrawals.withdrawal_method_id', '=', 'withdrawal_methods.id')
            ->join('currencies AS acc_currencies', 'accounts.currency_id', '=', 'acc_currencies.id')
            ->where('accounts.user_id', $user->id) // only current user withdrawals
            ->orderBy($sort->getSortColumn(), $sort->getOrder())
            ->paginate($this->rowsPerPage);

        return view('accounting::pages.frontend.withdrawals.index', [
            'withdrawals'   => $withdrawals,
            'sort'          => $sort->getSort(),
            'order'         => $sort->getOrder(),
        ]);
    }

    /**
     * Display withdrawal form
     *
     * @param Request $request
     * @param Account $account
     * @param PaymentMethod $withdrawalMethod
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, User $user, WithdrawalMethod $withdrawalMethod) {
        if ($user->id != $request->user()->id)
            abort(404);

        // check that method is enabled for withdrawals
        if ($withdrawalMethod->status != WithdrawalMethod::STATUS_ACTIVE)
            abort(404);

        return view('accounting::pages.frontend.withdrawals.create', [
            'account'               => $user->account,
            'withdrawal_method'     => $withdrawalMethod,
        ]);
    }

    /**
     * Handle withdrawals form submission
     *
     * @param StoreWithdrawal $request
     * @param User $user
     * @param WithdrawalMethod $withdrawalMethod
     */
    public function store(StoreWithdrawal $request, User $user, WithdrawalMethod $withdrawalMethod) {
        if ($user->id != $request->user()->id)
            abort(404);

        // check that method is enabled for withdrawals
        if ($withdrawalMethod->status != WithdrawalMethod::STATUS_ACTIVE)
            abort(404);

        $withdrawal = new Withdrawal();
        $withdrawal->account()->associate($user->account);
        $withdrawal->withdrawalMethod()->associate($withdrawalMethod);
        $withdrawal->status = Withdrawal::STATUS_CREATED;
        $withdrawal->amount = $request->amount;
        $withdrawal->details = serialize($request->details);
        $withdrawal->save();

        return redirect()
            ->route('frontend.withdrawals.index', [$user])
            ->with('success', __('accounting::text.withdrawal_created'));
    }
}
