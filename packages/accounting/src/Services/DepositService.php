<?php

namespace Packages\Accounting\Services;

use Packages\Accounting\Models\AccountTransaction;
use Packages\Accounting\Models\Deposit;

class DepositService
{
    private $deposit;

    public function __construct($externalId)
    {
        $this->deposit = Deposit::where('external_id', $externalId)->firstOrFail();

        return $this;
    }

    /**
     * Mark deposit as completed, increase account balance accordingly
     */
    public function complete() {
        if ($this->deposit->status == Deposit::STATUS_PENDING) {
            // update deposit model
            $this->deposit->status = Deposit::STATUS_COMPLETED;
            $this->deposit->save();
            // change account balance
            $account = new AccountService($this->deposit->account);
            $account->incrementBalance(
                $this->deposit,
                $this->deposit->amount,
                AccountTransaction::TYPE_DEPOSIT
            );
        }
    }

    /**
     * Mark deposit as cancelled
     */
    public function cancel() {
        if ($this->deposit->status == Deposit::STATUS_PENDING) {
            // update deposit model
            $this->deposit->status = Deposit::STATUS_CANCELLED;
            $this->deposit->save();
        }
    }
}