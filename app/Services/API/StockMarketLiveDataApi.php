<?php

namespace App\Services\API;

class StockMarketLiveDataApi extends API
{

    public function __construct()
    {
        $this->baseUri = sprintf('https://query%d.finance.yahoo.com/v7/finance/quote', rand(1,2));
        parent::__construct();
    }

    public function quotes($symbols)
    {
        $response = $this->getJson($this->queryParams($symbols, [
            'regularMarketPrice',
            'regularMarketChange',
            'regularMarketChangePercent',
            'regularMarketVolume',
            'sharesOutstanding',
            'marketCap'
        ]));

        return isset($response->quoteResponse->result) ? $response->quoteResponse->result : [];
    }

    /**
     * Format request params as HTTP query string
     *
     * @param $symbols
     * @param $fields
     * @return string
     */
    private function queryParams($symbols, $fields)
    {
        return '?' . urldecode(http_build_query([
            'formatted' => 'false',
            'symbols' => is_array($symbols) ? implode(',', $symbols) : $symbols,
            'fields' => is_array($fields) ? implode(',', $fields) : $fields
        ]));
    }
}