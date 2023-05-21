<?php

namespace Packages\CashCompetitions\Http\Requests\Frontend;

use App\Http\Requests\Frontend\JoinCompetition as ParentJoinCompetition;
use Packages\CashCompetitions\Rules\BalanceIsSufficientToJoin;

class JoinCompetition extends ParentJoinCompetition
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules['*'][] = new BalanceIsSufficientToJoin(
            $this->competition, $this->user()
        );

        return $rules;
    }
}
