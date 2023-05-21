<?php

namespace Packages\Accounting\Services;

use App\Models\Currency;
use Packages\Accounting\Models\Account;
use App\Models\User;
use Packages\Accounting\Models\AccountTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AccountService
{
    private $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * Increment account balance
     *
     * @param Model $transactionable
     * @param float $amount
     * @param int $type
     */
    public function incrementBalance(Model $transactionable, float $amount, int $type)
    {
        $this->changeBalance($transactionable, $amount, $type);
    }

    /**
     * Decrement account balance
     *
     * @param Model $transactionable
     * @param float $amount
     * @param int $type
     */
    public function decrementBalance(Model $transactionable, float $amount, int $type)
    {
        $this->changeBalance($transactionable, -$amount, $type);
    }

    /**
     * Change user account balance
     *
     * @param float $amount
     * @param int $type
     * @param string|NULL $descriptionType
     * @param array $descriptionParameters
     */
    private function changeBalance(Model $transactionable, float $amount, int $type)
    {
        if ($amount != 0) {
            // all in one DB transaction to ensure consistency
            DB::transaction(function () use ($transactionable, $amount, $type) {
                // update user balance
                if ($amount > 0)
                    $this->account->increment('balance', $amount);
                else
                    $this->account->decrement('balance', abs($amount));

                // create account transaction
                $this->createTransaction($transactionable, $amount, $type);
            });
        }
    }

    /**
     * Create account transaction
     *
     * @param float $amount
     * @param int $type
     * @param string|NULL $descriptionType - should be language string key from resources/lang/en/text.php
     * @param array $descriptionParameters
     */
    private function createTransaction(Model $transactionable, float $amount, int $type)
    {
        $transaction = new AccountTransaction();
        $transaction->account()->associate($this->account);
        $transaction->type = $type;
        $transaction->amount = $amount;
        $transaction->balance = $this->account->balance;

        $transactionable->transactions()->save($transaction);
    }

    /**
     * Create a new user account
     *
     * @param User $user
     * @return Account
     */
    public static function create(User $user)
    {
        $currency = Currency::where('code', config('accounting.currency'))->firstOrFail();

        $account = new Account();
        $account->user()->associate($user);
        $account->currency()->associate($currency);
        $account->status = Account::STATUS_ACTIVE;
        $account->code = $user->id . '-' . str_random(15);
        $account->save();

        return $account;
    }
}