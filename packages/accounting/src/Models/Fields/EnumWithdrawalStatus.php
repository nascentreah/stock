<?php

namespace Packages\Accounting\Models\Fields;

interface EnumWithdrawalStatus {
    const STATUS_CREATED     = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_COMPLETED   = 2;
    const STATUS_REJECTED    = 3;
    const STATUS_CANCELLED   = 4;
}