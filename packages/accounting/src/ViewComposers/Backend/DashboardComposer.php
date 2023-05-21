<?php

namespace Packages\Accounting\ViewComposers\Backend;

use Illuminate\View\View;
use Packages\Accounting\Models\Account;
use Packages\Accounting\Models\Deposit;

class DashboardComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // get variables passed to the main app view
        $variables = $view->getData();

        $accountsCount = Account::count();
        $positiveBalanceAccountsCount = Account::where('balance', '>', 0)->count();
        $zeroBalanceAccountsCount = $accountsCount - $positiveBalanceAccountsCount;

        $depositsCount = Deposit::count();
        $completedDepositsCount = Deposit::where('status', Deposit::STATUS_COMPLETED)->count();
        $pendingDepositsCount = $depositsCount - $completedDepositsCount;

        $variables['accounts_count']                    = $accountsCount;
        $variables['positive_balance_accounts_count']   = $positiveBalanceAccountsCount;
        $variables['zero_balance_accounts_count']       = $zeroBalanceAccountsCount;

        $variables['deposits_count']                    = $depositsCount;
        $variables['completed_deposits_count']          = $completedDepositsCount;
        $variables['pending_deposits_count']            = $pendingDepositsCount;

        $view->with($variables);
    }
}