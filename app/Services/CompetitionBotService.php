<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Competition;
use App\Models\User;
use Carbon\Carbon;

class CompetitionBotService
{
    public function run()
    {
        // loop through open competitions
        Competition::where('status', Competition::STATUS_IN_PROGRESS)->get()->each(function($competition, $i) {
            $allowedAssets = $competition->assetsIds();

            // choose a random asset
            $topAssets = Asset::where('status', Asset::STATUS_ACTIVE)
                ->where('price','>',0)
                // choose from top N assets
                ->when($allowedAssets->count() == 0, function($query) {
                    return $query->orderBy('market_cap','desc')->limit(config('settings.bots.top_assets_limit'));
                })
                // choose from allowed assets only
                ->when($allowedAssets->count() > 0, function($query) use($allowedAssets) {
                    return $query->whereIn('id', $allowedAssets->toArray());
                })
                ->get();

            // loop through bots, which joined given competition
            $competition->participants()->inRandomOrder()->where('role', User::ROLE_BOT)->get()->each(function($bot) use($competition, $topAssets) {
                $tradeService = new TradeService($competition, $bot);
                // radnomly determine number of trades to open
                $tradesToOpenCount = mt_rand(config('settings.bots.min_trades_to_open'), config('settings.bots.max_trades_to_open'));

                for ($i = 0; $i < $tradesToOpenCount; $i++) {
                    // re-load open trades from the 2nd iteration onwards
                    if ($i > 0)
                        $tradeService->loadOpenTrades();

                    $asset = $topAssets->random(); // randomly choose asset
                    $direction = mt_rand(0, 1); // randomly choose buy or sell
                    $maxVolume = $tradeService->freeMargin() * $competition->leverage / ($asset->price * $competition->lot_size);

                    /*print sprintf('Margin: %f, Leverage: %f, Price: %f, Lot: %f, Max Vol: %f',
                        $tradeService->totalMargin(),
                        $competition->leverage,
                        $asset->price,
                        $competition->lot_size,
                        $maxVolume
                    ) . "\n";*/

                    if ($maxVolume < $competition->volume_min)
                        continue;

                    $volume = mt_rand($competition->volume_min * 100, min($competition->volume_max, $maxVolume) * 100) / 100; // randomly choose volume

                    if ($tradeService->margin($asset, $volume) > 99999999)
                        continue;

                    // open trade
                    $tradeService->open($asset, $direction, $volume);
                }

                // calc the earliest date according to min trade life time
                $earliestDate = Carbon::now()->subSeconds(config('settings.bots.min_trade_life_time'));
                // radnomly determine number of trades to close
                $tradesToCloseCount = mt_rand(config('settings.bots.min_trades_to_close'), config('settings.bots.max_trades_to_close'));
                $competition->openTrades()->where('user_id', $bot->id)->where('created_at','<',$earliestDate)->limit($tradesToCloseCount)->get()->each(function($trade) use($tradeService) {
                    $tradeService->close($trade);
                });
            });
        });
    }
}
