<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $currencies = (array) json_decode(file_get_contents(base_path() . '/database/seeds/data/currencies.json'));

        foreach ($currencies as $currency) {
            Currency::firstOrCreate(
                [
                    'code' => $currency->code
                ],
                [
                    'name'          => $currency->name,
                    'symbol_native' => $currency->symbol_native,
                    'rate'          => 0,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                    'fraction'      => isset($currency->fraction) ? $currency->fraction : 1
                ]
            );
        }
    }
}
