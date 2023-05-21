<?php

namespace Packages\Accounting\Models\Fields;

interface EnumDepositStatus {
    const STATUS_PENDING     = 0;
    const STATUS_COMPLETED   = 1;
    const STATUS_CANCELLED   = 2;
}