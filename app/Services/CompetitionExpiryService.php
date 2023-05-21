<?php

namespace App\Services;

use App\Events\AfterCompetitionClosed;
use App\Models\Competition;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CompetitionExpiryService
{
    private $competitionModel;

    public function __construct(Competition $competition)
    {
        // save Competition model as a property and use it later in the controller methods,
        // so that other packages can bind their own implementations via IoC
        // If used directly (e.g. $c = Competition::where(...)->get()) bindings will not work and overridden model will not be used.
        $this->competitionModel = $competition;
    }

    public function run()
    {
        $competitions = $this->competitionModel::where('status', Competition::STATUS_IN_PROGRESS)
            ->where('end_time', '<', Carbon::now())
            ->get();

        foreach ($competitions as $competition) {
            Log::info(sprintf('Closing competition %d %s', $competition->id, $competition->title));

            // change competition status first, so no more trades can be made
            $competition->status = $this->competitionModel::STATUS_COMPLETED;
            $competition->save();

            // close all open trades
            foreach ($competition->participants()->get() as $participant) {
                $tradeService = new TradeService($competition, $participant);
                $tradeService->closeAll();
            }

            // update participants standings in competition (important to retrieve them again from database)
            $competition->participants()
                // skip participants who didn't make any trades, they will be ranked last
                ->whereColumn([
                    ['start_balance', '!=', 'current_balance'],
                    ['competition_participants.updated_at', '>', 'competition_participants.created_at']
                ])
                ->orderBy('current_balance', 'desc')
                ->orderBy('created_at', 'asc')
                ->get()
                ->each(function ($participant, $i) {
                    $participant->data->place = $i + 1;
                    $participant->data->save();
                    // award points to winners
                    if (in_array($participant->data->place, [1,2,3])) {
                        $userPointService = new UserPointService();
                        $userPointService->add(
                            $participant,
                            constant('\App\Models\UserPoint::TYPE_COMPETITION_PLACE' . $participant->data->place),
                            config('settings.points_type_competition_place' . $participant->data->place)
                        );
                    }
            });

            // trigger CompetitionClosed event
            event(new AfterCompetitionClosed($competition));
        }
    }
}