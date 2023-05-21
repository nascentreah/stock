<?php

namespace Packages\CashCompetitions\Models;

use App\Models\Competition as ParentCompetition;
use Packages\Accounting\Models\AccountTransaction;

class Competition extends ParentCompetition
{
    public function getTotalFeesPaidAttribute() {
        return abs($this->transactions()->where('type', AccountTransaction::TYPE_COMPETITION_FEE)->sum('amount'));
    }

    public function getTotalRewardPaidAttribute() {
        return abs($this->transactions()->where('type', AccountTransaction::TYPE_COMPETITION_REWARD)->sum('amount'));
    }

    public function transactions() {
        return $this->morphMany(AccountTransaction::class, 'transactionable');
    }
}