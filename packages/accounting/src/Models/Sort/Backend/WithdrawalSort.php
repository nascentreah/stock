<?php

namespace Packages\Accounting\Models\Sort\Backend;

use App\Models\Sort\Sort;

class WithdrawalSort extends Sort
{
    protected $sortableColumns = [
        'user'                  => 'user_name',
        'account'               => 'account_code',
        'withdrawal_method'     => 'withdrawal_method_code',
        'amount'                => 'amount',
        'status'                => 'status',
        'created'               => 'created_at',
        'updated'               => 'updated_at'
    ];

    protected $defaultSort = 'created';
}