<?php

namespace Packages\Accounting\Models;

use App\Models\Currency;
use App\Models\Formatters\Formatter;
use Packages\Accounting\Models\Fields\EnumDepositStatus;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model implements EnumDepositStatus
{
    use Formatter;

    protected $formats = [
        'payment_amount' => 'variableDecimal',
        'amount' => 'decimal',
    ];

    public function account() {
        return $this->belongsTo(Account::class);
    }

    public function paymentMethod() {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function transactions() {
        return $this->morphOne(AccountTransaction::class, 'transactionable');
    }
}
