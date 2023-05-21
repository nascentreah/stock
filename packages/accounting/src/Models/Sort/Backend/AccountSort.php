<?php

namespace Packages\Accounting\Models\Sort\Backend;

use App\Models\Sort\Sort;

class AccountSort extends Sort
{
    protected $sortableColumns = [
        'user'              => 'user_name',
        'account'           => 'code',
        'balance'           => 'balance',
        'status'            => 'status',
        'created'           => 'created_at',
        'updated'           => 'updated_at',
    ];

    protected $defaultSort = 'created';
}