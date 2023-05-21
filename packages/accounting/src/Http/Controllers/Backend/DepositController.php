<?php

namespace Packages\Accounting\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Packages\Accounting\Models\Deposit;
use Packages\Accounting\Models\Sort\Backend\DepositSort;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    /**
     * Deposits listing
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $sort = new DepositSort($request);

        $deposits = Deposit::select(
                'deposits.*',
                'users.id AS user_id',
                'users.name AS user_name',
                'payment_methods.code AS payment_method_code',
                'acc_currencies.code AS account_currency_code'
            )
            ->join('accounts', 'deposits.account_id', '=', 'accounts.id')
            ->join('users', 'accounts.user_id', '=', 'users.id')
            ->join('payment_methods', 'deposits.payment_method_id', '=', 'payment_methods.id')
            ->join('currencies AS acc_currencies', 'accounts.currency_id', '=', 'acc_currencies.id')
            ->orderBy($sort->getSortColumn(), $sort->getOrder())
            ->paginate($this->rowsPerPage);

        return view('accounting::pages.backend.deposits.index', [
            'deposits'      => $deposits,
            'sort'          => $sort->getSort(),
            'order'         => $sort->getOrder(),
        ]);
    }
}
