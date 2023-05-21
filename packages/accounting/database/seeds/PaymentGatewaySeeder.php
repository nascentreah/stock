<?php

use Illuminate\Database\Seeder;
use Packages\Accounting\Models\PaymentGateway;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentGateway = new PaymentGateway();

        $paymentGateway->firstOrCreate(
            ['id' => 1], ['code' => 'paypal']
        );

        $paymentGateway->firstOrCreate(
            ['id' => 2], ['code' => 'stripe']
        );

        $paymentGateway->firstOrCreate(
            ['id' => 3], ['code' => 'coinpayments']
        );
    }
}
