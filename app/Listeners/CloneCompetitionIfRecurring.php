<?php

namespace App\Listeners;

use App\Events\AfterCompetitionClosed;
use App\Models\Competition;
use App\Services\CompetitionService;

class CloneCompetitionIfRecurring
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param AfterCompetitionClosed $event
     */
    public function handle(AfterCompetitionClosed $event) {
        $competition = $event->competition();

        // re-create competition if recurring flag is set
        if ($competition->recurring) {
            $competitionService = new CompetitionService($competition);
            $competitionService->clone();
        }
    }
}