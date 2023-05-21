<?php

namespace App\Services;

use App\Models\Currency;
use App\Services\API\CurrencyDataApi;
use Illuminate\Support\Facades\Log;

class CurrencyDataUpdateService extends Service
{
    private $api;

    public function __construct()
    {
        $this->api = new CurrencyDataApi(config('settings.openexchangerates_api_key'));
    }

    public function run()
    {
        // get quotes from API
        $quotes = $this->api->latest();
        $baseCurrency = config('settings.currency');
        $baseCurrencyRate = isset($quotes->$baseCurrency) ? $quotes->$baseCurrency : 1;

        // loop through currencies and update its rate
        foreach (Currency::cursor() as $currency) {
            if (isset($quotes->{$currency->code})) {
                $currency->rate = $quotes->{$currency->code} / $baseCurrencyRate;
                $currency->save();
            }
        }

        // GBX (pence) rate is always constant in relation to GBP (pound)
        if ($baseCurrency == 'GBP') {
            Currency::where('code', 'GBX')
                ->where('rate', '!=', 100)
                ->update([
                    'rate' => 100
                ]);
        // otherwise GBX rate needs to be calculated
        } elseif (isset($quotes->GBP)) {
            Currency::where('code', 'GBX')
                ->update([
                    'rate' => $quotes->GBP * 100 / $baseCurrencyRate
                ]);
        }
    }
}