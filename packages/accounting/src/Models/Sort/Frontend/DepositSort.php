<?php

namespace Packages\Accounting\Models\Sort\Frontend;

use App\Models\Sort\Sort;

class DepositSort extends Sort
{
    protected $sortableColumns = [
        'payment_method'    => 'payment_method_code',
        'amount'            => 'amount',
        'status'            => 'status',
        'created'           => 'created_at',
        'updated'           => 'updated_at'
    ];

    protected $defaultSort = 'created';
}