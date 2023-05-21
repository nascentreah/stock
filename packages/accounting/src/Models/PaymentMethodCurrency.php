<?php

namespace Packages\Accounting\Models;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodCurrency extends Model
{
    public function currency() {
        return $this->belongsTo(Currency::class);
    }
}
