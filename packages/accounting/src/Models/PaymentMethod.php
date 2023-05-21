<?php

namespace Packages\Accounting\Models;

use Packages\Accounting\Models\Fields\EnumPaymentMethodStatus;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model implements EnumPaymentMethodStatus
{
    public function paymentGateway() {
        return $this->belongsTo(PaymentGateway::class);
    }

    public function currencies() {
        return $this->hasMany(PaymentMethodCurrency::class);
    }
}