<?php

return [
    'version' => '1.2.1',
    'currency' => env('ACCOUNT_CURRENCY', 'USD'),
    'paypal' => [
        'mode' => env('PAYPAL_MODE', 'sandbox'),
        'user' => env('PAYPAL_USER'),
        'password' => env('PAYPAL_PASSWORD'),
        'signature' => env('PAYPAL_SIGNATURE'),
    ],
    'stripe' => [
        'public_key' => env('STRIPE_PUBLIC_KEY'),
        'secret_key' => env('STRIPE_SECRET_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],
    'coinpayments' => [
        'merchant_id' => env('COINPAYMENTS_MERCHANT_ID'),
        'public_key' => env('COINPAYMENTS_PUBLIC_KEY'),
        'private_key' => env('COINPAYMENTS_PRIVATE_KEY'),
        'secret_key' => env('COINPAYMENTS_SECRET_KEY'),
    ],
    'withdrawal' => [
        'cryptocurrencies' => env('WITHDRAWAL_CRYPTOCURRENCIES', 'BTC,ETH,LTC,XRP'),
    ],
];