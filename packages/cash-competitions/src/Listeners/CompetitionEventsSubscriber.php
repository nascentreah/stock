<?php

namespace Packages\CashCompetitions\Listeners;

use App\Events\AfterCompetitionClosed;
use App\Events\BeforeUserJoinedCompetition;
use Packages\Accounting\Models\User;
use Packages\Accounting\Models\AccountTransaction;
use Packages\Accounting\Services\AccountService;

class CompetitionEventsSubscriber
{
    public function beforeUserJoinedCompetition(BeforeUserJoinedCompetition $event) {
        $competition = $event->competition();

        // charge entrance fee
        if ($competition->fee) {
            $user = $event->user();
            (new AccountService($user->account))
                ->decrementBalance(
                    $competition,
                    $competition->fee,
                    AccountTransaction::TYPE_COMPETITION_FEE
                );
        }
    }

    public function afterCompetitionClosed(AfterCompetitionClosed $event) {
        $competition = $event->competition();

        $participants = $competition->participants()->get();

        // loop through defined payouts
        foreach ($competition->payouts as $i => $payout) {
            $place = $i + 1;
            // find competition participant, who should be rewarded according to their place
            $participant = $participants->where('data.place', $place)->first();
            // if it's found
            if ($participant) {
                // find user explicitly, $participant->user->account will not work in this case
                $user = User::find($participant->id);
                $reward = $payout['type'] == 'percentage' ? $payout['amount'] * $competition->total_fees_paid / 100 : (float) $payout['amount'];

                $accountService = new AccountService($user->account);
                // increment balance depending on reward type
                $accountService->incrementBalance(
                    $competition,
                    $reward,
                    AccountTransaction::TYPE_COMPETITION_REWARD
                );
            }
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            BeforeUserJoinedCompetition::class,
            'Packages\CashCompetitions\Listeners\CompetitionEventsSubscriber@beforeUserJoinedCompetition'
        );

        $events->listen(
            AfterCompetitionClosed::class,
            'Packages\CashCompetitions\Listeners\CompetitionEventsSubscriber@afterCompetitionClosed'
        );
    }
}