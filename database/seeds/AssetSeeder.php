<?php

use Illuminate\Database\Seeder;
use App\Models\Asset;
use App\Models\Currency;
use App\Models\Market;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = [];
        $markets = [];

        // get all symbols from the JSON file
        $data = json_decode(file_get_contents(base_path() . '/database/seeds/data/assets.json'));

        foreach ($data as $item) {
            // retrieve currency by code
            if (in_array($item->currency, $currencies)) {
                $currencyId = $currencies[$item->currency];
            } else {
                $currencyId = Currency::where('code', $item->currency)->value('id');
                $currencies[$item->currency] = $currencyId;
            }

            // retrieve market by code
            if (in_array($item->market, $markets)) {
                $marketId = $markets[$item->market];
            } else {
                $marketId = Market::where('code', $item->market)->value('id');
                $markets[$item->market] = $marketId;
            }

            Asset::firstOrCreate(
//            Asset::updateOrCreate(
                [
                    'symbol_ext' => isset($item->symbol_ext) ? $item->symbol_ext : $item->symbol
                ],
                [
                    'market_id'     => $marketId,
                    'currency_id'   => $currencyId,
                    'symbol'        => $item->symbol,
                    'name'          => $item->name,
                    'logo'          => isset($item->logo) ? $item->logo : NULL,
                    'status'        => Asset::STATUS_ACTIVE
                ]
            );
        }
    }
}
