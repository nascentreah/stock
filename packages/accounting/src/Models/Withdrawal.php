<?php

namespace Packages\Accounting\Models;

use App\Models\Formatters\Formatter;
use Packages\Accounting\Models\Fields\EnumWithdrawalStatus;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model implements EnumWithdrawalStatus
{
    use Formatter;

    protected $formats = [
        'amount' => 'decimal',
    ];

    public function account() {
        return $this->belongsTo(Account::class);
    }

    public function withdrawalMethod() {
        return $this->belongsTo(WithdrawalMethod::class);
    }

    public function getPaymentDetailsAttribute() {
        return $this->details ? unserialize($this->details) : [];
    }

    public function transactions() {
        return $this->morphOne(AccountTransaction::class, 'transactionable');
    }
}
