<?php

namespace Packages\Accounting\Models;

use App\Models\Currency;
use App\Models\Formatters\Formatter;
use App\Models\User;
use Packages\Accounting\Models\Fields\EnumAccountStatus;
use Illuminate\Database\Eloquent\Model;

class Account extends Model implements EnumAccountStatus
{
    use Formatter;

    protected $formats = [
        'balance' => 'decimal',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function transactions()
    {
        return $this->hasMany(AccountTransaction::class);
    }
}
