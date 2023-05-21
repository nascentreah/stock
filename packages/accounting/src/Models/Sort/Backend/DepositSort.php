<?php

namespace Packages\Accounting\Models\Sort\Backend;

use App\Models\Sort\Sort;

class DepositSort extends Sort
{
    protected $sortableColumns = [
        'user'              => 'user_name',
        'payment_method'    => 'payment_method_code',
        'payment_id'        => 'external_id',
        'amount'            => 'amount',
        'status'            => 'status',
        'created'           => 'created_at',
        'updated'           => 'updated_at'
    ];

    protected $defaultSort = 'created';
}