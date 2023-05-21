<?php

namespace Packages\Accounting\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Packages\Accounting\Http\Requests\Frontend\StoreAccount;
use Packages\Accounting\Models\Account;
use Packages\Accounting\Models\Sort\Backend\AccountSort;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort = new AccountSort($request);

        $accounts = Account::select(
                'accounts.*',
                'users.id AS user_id',
                'users.name AS user_name',
                'currencies.code AS currency_code'
            )
            ->join('users', 'accounts.user_id', '=', 'users.id')
            ->join('currencies', 'accounts.currency_id', '=', 'currencies.id')
            ->orderBy($sort->getSortColumn(), $sort->getOrder())
            ->paginate($this->rowsPerPage);

        return view('accounting::pages.backend.accounts.index', [
            'accounts'  => $accounts,
            'sort'      => $sort->getSort(),
            'order'     => $sort->getOrder(),
        ]);
    }
}
