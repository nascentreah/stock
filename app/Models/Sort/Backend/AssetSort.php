<?php

namespace App\Models\Sort\Backend;

use App\Models\Sort\Sort;

class AssetSort extends Sort
{
    protected $sortableColumns = [
        'id'                => 'id',
        'symbol'            => 'symbol',
        'name'              => 'name',
        'market'            => 'market_code',
        'price'             => 'price',
        'change_abs'        => 'change_abs',
        'change_pct'        => 'change_pct',
        'status'            => 'status',
    ];

    protected $defaultSort = 'symbol';

    protected $defaultOrder = 'asc';
}