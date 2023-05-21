<?php

use Illuminate\Database\Seeder;
use Packages\Accounting\Models\WithdrawalMethod;

class WithdrawalMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $withdrawalMethod = new WithdrawalMethod();

        $methods = [
            'paypal', 'wire', 'crypto'
        ];

        foreach ($methods as $i => $method) {
            $withdrawalMethod->firstOrCreate(
                ['id' => ++$i], ['code' => $method, 'status' => WithdrawalMethod::STATUS_ACTIVE]
            );
        }
    }
}
