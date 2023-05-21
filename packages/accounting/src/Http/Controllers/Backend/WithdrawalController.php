<?php

namespace Packages\Accounting\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Packages\Accounting\Http\Requests\Backend\UpdateWithdrawal;
use Packages\Accounting\Models\AccountTransaction;
use Packages\Accounting\Models\Sort\Backend\WithdrawalSort;
use Packages\Accounting\Models\Withdrawal;
use Packages\Accounting\Rules\WithdrawalIsEditable;
use Packages\Accounting\Services\AccountService;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    /**
     * Deposits listing
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $sort = new WithdrawalSort($request);

        $withdrawals = Withdrawal::select(
                'withdrawals.*',
                'users.id AS user_id',
                'users.name AS user_name',
                'accounts.code AS account_code',
                'withdrawal_methods.code AS withdrawal_method_code',
                'acc_currencies.code AS account_currency_code'
            )
            ->join('accounts', 'withdrawals.account_id', '=', 'accounts.id')
            ->join('users', 'accounts.user_id', '=', 'users.id')
            ->join('withdrawal_methods', 'withdrawals.withdrawal_method_id', '=', 'withdrawal_methods.id')
            ->join('currencies AS acc_currencies', 'accounts.currency_id', '=', 'acc_currencies.id')
            ->orderBy($sort->getSortColumn(), $sort->getOrder())
            ->paginate($this->rowsPerPage);

        return view('accounting::pages.backend.withdrawals.index', [
            'withdrawals'   => $withdrawals,
            'sort'          => $sort->getSort(),
            'order'         => $sort->getOrder(),
        ]);
    }

    public function edit(Request $request, Withdrawal $withdrawal) {
        $view = view('accounting::pages.backend.withdrawals.edit', [
            'withdrawal'    => $withdrawal,
            'editable'      => (new WithdrawalIsEditable($withdrawal))->passes()
        ]);

        // warn user if withdrawal can not be edited
        if (!$view->editable)
            $request->session()->flash('warning', __('accounting::text.withdrawal_edit_warning'));

        return $view;
    }

    public function update(UpdateWithdrawal $request, Withdrawal $withdrawal) {

        $withdrawal->amount = $request->amount;
        $withdrawal->status = $request->status;
        $withdrawal->comments = $request->comments;

        // if withdrawal is completed decrement user account balance
        if ($withdrawal->status == Withdrawal::STATUS_COMPLETED) {
            $accountService = new AccountService($withdrawal->account);
            $accountService->decrementBalance(
                $withdrawal,
                $request->amount,
                AccountTransaction::TYPE_WITHDRAWAL
            );
        }

        $withdrawal->save();

        return redirect()
            ->route('backend.withdrawals.index')
            ->with('success', __('accounting::text.withdrawal_saved'));
    }

}
