<?php

namespace Packages\Accounting\Services;

interface PaymentService
{
    public function initializePayment($amount, $currency, array $paymentDetails = NULL);

    public function redirectToGateway();

    public function processPayment(array $paymentDetails);

    public function amount();

    public function currency();

    public function paymentId();

    public function completed();

    public function pending();
}