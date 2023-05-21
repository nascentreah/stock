<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Market;
use App\Services\API\StockMarketLiveDataApi;
use Illuminate\Support\Carbon;

class StockMarketDataUpdateService extends Service
{
    private $api;

    public function __construct()
    {
        $this->api = new StockMarketLiveDataApi();
    }

    public function run($force = FALSE)
    {
        // empty collection
        $symbols = collect();

        // iterate through all markets
        Market::all()->each(function($market) use(&$symbols, $force) {
            // check that it's a working day in the market timezone and the market is open
            // (allow extra 30 minutes to receive updates after market close)
            if ($force || Market::isOpen($market->trading_start, $market->trading_end, $market->timezone_name, 0, 60)) {
                // in this case add all market symbols to collection
                $symbols = $symbols->merge($market->assets()->get()->pluck('symbol_ext')->all());
            }
        });

        // split assets symbols into chunks and retrieve quotes from the API for each chunk
        foreach (array_chunk($symbols->all(), config('settings.api_assets_chunk_size')) as $chunk) {
            $quotes = collect((array) $this->api->quotes($chunk));

            // update DB for each retrieved quote
            $quotes->each(function($quote, $i) {
                Asset::where('symbol_ext', $quote->symbol)->update([
                    // specify default value, so it doesn't break
                    'price'         => isset($quote->regularMarketPrice) ? floatval($quote->regularMarketPrice) : 0,
                    'change_abs'    => isset($quote->regularMarketChange) ? floatval($quote->regularMarketChange) : 0,
                    'change_pct'    => isset($quote->regularMarketChangePercent) ? floatval($quote->regularMarketChangePercent) : 0,
                    'volume'        => isset($quote->regularMarketVolume) ? floatval($quote->regularMarketVolume) : 0,
                    'supply'        => isset($quote->sharesOutstanding) ? floatval($quote->sharesOutstanding) : 0,
                    'market_cap'    => isset($quote->marketCap) ? (floatval($quote->marketCap) < 999000000000 ? floatval($quote->marketCap) : floatval($quote->regularMarketPrice) *floatval($quote->sharesOutstanding)) : 0,
                ]);
            });
        }
    }
}