<?php

namespace Packages\Accounting\Models\Fields;

interface EnumAccountTransactionType {
    const TYPE_DEPOSIT              = 1;
    const TYPE_WITHDRAWAL           = 2;
    const TYPE_COMPETITION_FEE      = 3;
    const TYPE_COMPETITION_REWARD   = 4;
}