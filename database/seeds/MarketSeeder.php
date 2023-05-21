<?php

use Illuminate\Database\Seeder;
use App\Models\Market;

class MarketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // get all symbols from the JSON file
        $data = json_decode(file_get_contents(base_path() . '/database/seeds/data/markets.json'));

        foreach ($data as $item) {
            Market::firstOrCreate(
                [
                    'code' => $item->code
                ],
                [
                    'name'              => $item->name,
                    'trading_start'     => $item->trading_start,
                    'trading_end'       => $item->trading_end,
                    'timezone_code'     => $item->timezone_code,
                    'timezone_name'     => $item->timezone_name,
                    'url'               => $item->url,
                    'country_code'      => $item->country_code,
                    'status'            => Market::STATUS_ACTIVE
                ]
            );
        }
    }
}
