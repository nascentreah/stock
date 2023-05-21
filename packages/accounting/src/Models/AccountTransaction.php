<?php

namespace Packages\Accounting\Models;

use App\Models\Formatters\Formatter;
use Packages\Accounting\Models\Fields\EnumAccountTransactionType;
use Illuminate\Database\Eloquent\Model;

class AccountTransaction extends Model implements EnumAccountTransactionType
{
    use Formatter;

    protected $formats = [
        'amount' => 'decimal',
        'balance' => 'decimal',
    ];

    public function account() {
        return $this->belongsTo(Account::class);
    }

    public function transactionable() {
        return $this->morphTo();
    }
}