<?php

namespace App\Services;

use App\Models\Competition;
use App\Notifications\MailMarginCall;
use Illuminate\Support\Facades\Log;

class MarginCallService
{
    public function run() {
        $openCompetitions = Competition::where('status', Competition::STATUS_IN_PROGRESS)->get();

        foreach ($openCompetitions as $openCompetition) {
            foreach ($openCompetition->participants()->get() as $participant) {
                $tradeService = new TradeService($openCompetition, $participant);

                // check margin level only if user has open trades
                $openTrades = $tradeService->openTrades();
                if (!$openTrades->isEmpty()) {
                    $marginLevel = $tradeService->marginLevel();

                    // if margin level is not sufficient perform a margin call
                    if ($marginLevel < $openCompetition->min_margin_level) {
                        // get the most losing trade
                        $mostLosingTrade = $openTrades->sortBy('unrealizedPnl')->first();
                        Log::debug(sprintf('Competition %s, User %d, Margin level: %.2f (min %.2f), Close trade: %d', $openCompetition->id, $participant->id, $marginLevel, $openCompetition->min_margin_level, $mostLosingTrade->id));
                        // close trade
                        $tradeService->close($mostLosingTrade);
                        // notify user if it's not a bot
                        if (!$participant->bot())
                            $participant->notify(new MailMarginCall($openCompetition, $mostLosingTrade));
                    }
                }
            }
        }
    }
}