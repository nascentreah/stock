<?php

namespace Packages\CashCompetitions\Rules;

use App\Models\Competition;
use Packages\Accounting\Models\User;
use Illuminate\Contracts\Validation\Rule;

class BalanceIsSufficientToJoin implements Rule
{
    private $competition;
    private $user;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Competition $competition, User $user)
    {
        $this->competition = $competition;
        $this->user = $user;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute = NULL, $value = NULL)
    {
        return $this->competition->fee == 0 || // it's a free to join competition
            $this->user->account && // user account might not exist (for new users), so check that it exists first
            $this->user->account->currency_id = $this->competition->currency_id && // check that user account currency equals competition currency
            $this->user->account->balance > $this->competition->fee; // check that user balance is sufficient to deduct the fee
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('cash-competitions::text.balance_not_sufficient');
    }
}
