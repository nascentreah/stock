<?php

namespace App\Services\API;

use Illuminate\Support\Facades\Cache;

class StockMarketHistoricalDataApi extends API
{
    private $symbol;

    // Valid intervals: [1m, 2m, 5m, 15m, 30m, 60m, 90m, 1h, 1d, 5d, 1wk, 1mo, 3mo]
    // validRanges: [1d, 5d, 1mo, 3mo, 6mo, 1y, 2y, 5y, 10y, ytd, max]
    // range => interval
    private $ranges = [
        '1d'    => '5m',
        '5d'    => '1h',
        '1mo'   => '1d',
        '3mo'   => '1d',
        '6mo'   => '1d',
        '1y'    => '1wk',
        '2y'    => '1wk',
        '5y'    => '1mo',
        '10y'   => '1mo',
        'ytd'   => '1d'
    ];

    // cache time for each range in minutes
    private $cacheTimes = [
        '1d'    => 5,
        '5d'    => 30,
        '1mo'   => 60,
        '3mo'   => 60,
        '6mo'   => 60,
        '1y'    => 120,
        '2y'    => 120,
        '5y'    => 180,
        '10y'   => 180,
        'ytd'   => 60
    ];

    public function __construct($symbol)
    {
        $this->symbol = $symbol;
        $this->baseUri = sprintf('https://query%d.finance.yahoo.com/v8/finance/chart/%s', rand(1,2), $symbol);
        parent::__construct();
    }

    /**
     * Get historical data
     *
     * @param $range
     * @param $interval
     * @return array
     */
    public function history($range)
    {
        $range = $this->range($range);

        return Cache::remember('history-' . $this->symbol . '-' . $range, $this->cacheTime($range), function() use($range) {
            $response = $this->getJson($this->queryParams($range));

            if (isset($response->chart->result[0])) {
                $data = $response->chart->result[0];

                return [
                    'date' => $data->timestamp,
                    'open' => $data->indicators->quote[0]->close ?? [],
                    'high' => $data->indicators->quote[0]->high ?? [],
                    'low' => $data->indicators->quote[0]->low ?? [],
                    'close' => $data->indicators->quote[0]->close ?? [],
                    'adj_close' => $data->indicators->adjclose[0]->adjclose ?? [],
                    'volume' => $data->indicators->quote[0]->close ?? [],
                ];
            }

            return [];
        });
    }

    /**
     * Format request params as HTTP query string
     *
     * @param $range
     * @param $interval
     * @return string
     */
    private function queryParams($range)
    {
        return '?' . urldecode(http_build_query([
            'includePrePost'    => 'false',
            'range'             => $range,
            'interval'          => $this->interval($range)
        ]));
    }

    private function cacheTime($range)
    {
        return in_array($range, $this->cacheTimes) ? $this->cacheTimes[$range] : 60;
    }

    private function range($range)
    {
        return in_array($range, array_keys($this->ranges)) ? $range : '1mo';
    }

    private function interval($range)
    {
        return in_array($range, array_keys($this->ranges)) ? $this->ranges[$range] : '1d';
    }
}