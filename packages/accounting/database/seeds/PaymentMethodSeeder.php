<?php

use Illuminate\Database\Seeder;
use App\Models\Currency;
use Packages\Accounting\Models\PaymentMethod;
use Packages\Accounting\Models\PaymentMethodCurrency;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethod = new PaymentMethod();
        $paymentMethodCurrency = new PaymentMethodCurrency();

        $currencies = Currency::get();

        $paymentMethod->firstOrCreate(
            ['id' => 1], ['payment_gateway_id' => 1, 'code' => 'paypal', 'status' => PaymentMethod::STATUS_ACTIVE]
        );

        $paymentMethod->firstOrCreate(
            ['id' => 2], ['payment_gateway_id' => 2, 'code' => 'card', 'status' => PaymentMethod::STATUS_ACTIVE]
        );

        $paymentMethod->firstOrCreate(
            ['id' => 3], ['payment_gateway_id' => 2, 'code' => 'ideal', 'status' => PaymentMethod::STATUS_ACTIVE]
        );
        $paymentMethodCurrency->firstOrCreate(
            ['payment_method_id' => 3, 'currency_id' => $currencies->where('code','EUR')->first()->id]
        );

        $paymentMethod->firstOrCreate(
            ['id' => 4], ['payment_gateway_id' => 2, 'code' => 'bancontact', 'status' => PaymentMethod::STATUS_ACTIVE]
        );
        $paymentMethodCurrency->firstOrCreate(
            ['payment_method_id' => 4, 'currency_id' => $currencies->where('code','EUR')->first()->id]
        );

        $paymentMethod->firstOrCreate(
            ['id' => 5], ['payment_gateway_id' => 2, 'code' => 'giropay', 'status' => PaymentMethod::STATUS_ACTIVE]
        );
        $paymentMethodCurrency->firstOrCreate(
            ['payment_method_id' => 5, 'currency_id' => $currencies->where('code','EUR')->first()->id]
        );

        $paymentMethod->firstOrCreate(
            ['id' => 6], ['payment_gateway_id' => 2, 'code' => 'sofort', 'status' => PaymentMethod::STATUS_ACTIVE]
        );
        $paymentMethodCurrency->firstOrCreate(
            ['payment_method_id' => 6, 'currency_id' => $currencies->where('code','EUR')->first()->id]
        );

        $paymentMethod->firstOrCreate(
            ['id' => 7], ['payment_gateway_id' => 2, 'code' => 'eps', 'status' => PaymentMethod::STATUS_ACTIVE]
        );
        $paymentMethodCurrency->firstOrCreate(
            ['payment_method_id' => 7, 'currency_id' => $currencies->where('code','EUR')->first()->id]
        );

        $paymentMethod->firstOrCreate(
            ['id' => 8], ['payment_gateway_id' => 2, 'code' => 'multibanco', 'status' => PaymentMethod::STATUS_ACTIVE]
        );
        $paymentMethodCurrency->firstOrCreate(
            ['payment_method_id' => 8, 'currency_id' => $currencies->where('code','EUR')->first()->id]
        );

        $paymentMethod->firstOrCreate(
            ['id' => 9], ['payment_gateway_id' => 2, 'code' => 'p24', 'status' => PaymentMethod::STATUS_ACTIVE]
        );
        $paymentMethodCurrency->firstOrCreate(
            ['payment_method_id' => 9, 'currency_id' => $currencies->where('code','EUR')->first()->id]
        );
        $paymentMethodCurrency->firstOrCreate(
            ['payment_method_id' => 9, 'currency_id' => $currencies->where('code','PLN')->first()->id]
        );

        $paymentMethod->firstOrCreate(
            ['id' => 10], ['payment_gateway_id' => 2, 'code' => 'alipay', 'status' => PaymentMethod::STATUS_ACTIVE]
        );
        $paymentMethodCurrency->firstOrCreate(
            ['payment_method_id' => 10, 'currency_id' => $currencies->where('code','AUD')->first()->id]
        );
        $paymentMethodCurrency->firstOrCreate(
            ['payment_method_id' => 10, 'currency_id' => $currencies->where('code','CAD')->first()->id]
        );
        $paymentMethodCurrency->firstOrCreate(
            ['payment_method_id' => 10, 'currency_id' => $currencies->where('code','EUR')->first()->id]
        );
        $paymentMethodCurrency->firstOrCreate(
            ['payment_method_id' => 10, 'currency_id' => $currencies->where('code','GBP')->first()->id]
        );
        $paymentMethodCurrency->firstOrCreate(
            ['payment_method_id' => 10, 'currency_id' => $currencies->where('code','HKD')->first()->id]
        );
        $paymentMethodCurrency->firstOrCreate(
            ['payment_method_id' => 10, 'currency_id' => $currencies->where('code','JPY')->first()->id]
        );
        $paymentMethodCurrency->firstOrCreate(
            ['payment_method_id' => 10, 'currency_id' => $currencies->where('code','NZD')->first()->id]
        );
        $paymentMethodCurrency->firstOrCreate(
            ['payment_method_id' => 10, 'currency_id' => $currencies->where('code','SGD')->first()->id]
        );
        $paymentMethodCurrency->firstOrCreate(
            ['payment_method_id' => 10, 'currency_id' => $currencies->where('code','USD')->first()->id]
        );

        $paymentMethod->firstOrCreate(
            ['id' => 11], ['payment_gateway_id' => 3, 'code' => 'coinpayments', 'status' => PaymentMethod::STATUS_ACTIVE]
        );
    }
}
