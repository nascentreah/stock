<?php

namespace Packages\Accounting\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use CoinpaymentsAPI;

/**
 * CoinpaymentsPayment.net API implementation
 *
 * Class CoinpaymentsPaymentService
 */
class CoinpaymentsPaymentService implements PaymentService
{
    private $currency;
    private $api;
    private $response;
    private $config;

    /**
     * CoinpaymentsPaymentService constructor.
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->api = new CoinpaymentsAPI($config['private_key'], $config['public_key'], 'json');
    }

    /**
     * @param $quantity
     * @param $price
     * @param $currency
     * @param array $paymentDetails
     */
    public function initializePayment($amount, $currency, array $paymentDetails = NULL)
    {
        $this->currency = $currency;

        $this->response = $this->api->CreateComplexTransaction(
            $amount,
            $paymentDetails['accountCurrency'],
            $currency,
            $paymentDetails['userEmail'],
            '', // forward to address
            $paymentDetails['userName'],
            $paymentDetails['description'],
            '', // item number
            '', // invoice
            '', // custom
            $paymentDetails['ipnUrl']
        );

        Log::info($this->response);
    }

    private function requestSuccessful() {
        return $this->response && isset($this->response['error']) && $this->response['error'] == 'ok' && isset($this->response['result']);
    }

    /**
     * Should be bound to successUrl
     *
     * @param array $paymentDetails
     */
    public function processPayment(array $paymentDetails)
    {
        //
    }

    public function acceptedCurrencies() {
        $currencies = Cache::get('coinpayments-accepted-currencies');

        if (!$currencies || empty($currencies)) {
            $this->response = $this->api->GetShortRatesWithAccepted();

            if ($this->requestSuccessful()) {
                $currencies = array_keys(array_filter($this->response['result'], function ($value) {
                    return $value['accepted'] == 1 && $value['status'] == 'online';
                }));

                if (!empty($currencies)) {
                    Cache::put('coinpayments-accepted-currencies', $currencies, 1440/* 24 hours */);
                }
            }
        }

        return $currencies;
    }

    public function amount()
    {
        return $this->requestSuccessful() ? $this->response['result']['amount'] : NULL;
    }

    public function currency()
    {
        return $this->currency;
    }


    public function redirectToGateway()
    {
        if ($this->requestSuccessful()) {
            header('Location: ' . $this->response['result']['status_url']);
            exit;
        }
    }

    public function paymentId()
    {
        return $this->requestSuccessful() ? $this->response['result']['txn_id'] : NULL;
    }

    public function completed()
    {
        //
    }

    public function pending()
    {
        //
    }

    public function validSignature($content, $header) {
        $hmac = hash_hmac('sha512', $content, $this->config['secret_key']);
        return hash_equals($hmac, $header);
    }
}