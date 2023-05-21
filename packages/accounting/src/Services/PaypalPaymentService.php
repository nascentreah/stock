<?php

namespace Packages\Accounting\Services;

use Illuminate\Support\Facades\Log;
use PayPal\CoreComponentTypes\BasicAmountType;
use PayPal\EBLBaseComponents\PaymentDetailsItemType;
use PayPal\EBLBaseComponents\PaymentDetailsType;
use PayPal\EBLBaseComponents\SetExpressCheckoutRequestDetailsType;
use PayPal\PayPalAPI\SetExpressCheckoutReq;
use PayPal\PayPalAPI\SetExpressCheckoutRequestType;
use PayPal\Service\PayPalAPIInterfaceServiceService;
use PayPal\EBLBaseComponents\DoExpressCheckoutPaymentRequestDetailsType;
use PayPal\PayPalAPI\DoExpressCheckoutPaymentReq;
use PayPal\PayPalAPI\DoExpressCheckoutPaymentRequestType;
use PayPal\PayPalAPI\GetExpressCheckoutDetailsReq;
use PayPal\PayPalAPI\GetExpressCheckoutDetailsRequestType;

/**
 * Class PaypalDepositService
 *
 * PayPal API (express checkout) implementation
 * Flow: supply amount and currency -> create token -> redirect to PayPal payment page -> authorize payment -> redirect to sucess URL
 */
class PaypalPaymentService implements PaymentService
{

    private $env = array(
        'sandbox' => 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=%s',
        'live' => 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=%s'
    );
    private $config;
    private $response;
    private $amount;

    /**
     * PaypalPayment constructor.
     */
    public function __construct($config)
    {
        $this->config = [
            'mode'              => $config['mode'],
            'acct1.UserName'    => $config['user'],
            'acct1.Password'    => $config['password'],
            'acct1.Signature'   => $config['signature']
        ];
    }

    /**
     * @param $amount
     * @param $currency
     * @param array $paymentDetails
     */
    public function initializePayment($amount, $currency, array $paymentDetails = NULL)
    {
        $this->amount = round($amount, 2);
        $this->currency = $currency;

        $orderTotal = new BasicAmountType();
        $orderTotal->currencyID = $currency;
        $orderTotal->value = $this->amount;

        $taxTotal = new BasicAmountType();
        $taxTotal->currencyID = $currency;
        $taxTotal->value = '0.0';

        $itemDetails = new PaymentDetailsItemType();
        $itemDetails->Name = $paymentDetails['description'];
        $itemDetails->Amount = $this->amount;
        $itemDetails->Quantity = 1;
        $itemDetails->ItemCategory = 'Digital';

        $PaymentDetails = new PaymentDetailsType();
        $PaymentDetails->PaymentDetailsItem[0] = $itemDetails;
        $PaymentDetails->OrderTotal = $orderTotal;
        $PaymentDetails->PaymentAction = 'Sale';
        $PaymentDetails->ItemTotal = $orderTotal;
        $PaymentDetails->TaxTotal = $taxTotal;

        $setECReqDetails = new SetExpressCheckoutRequestDetailsType();
        $setECReqDetails->PaymentDetails[0] = $PaymentDetails;
        $setECReqDetails->CancelURL = $paymentDetails['returnUrl'];
        $setECReqDetails->ReturnURL = $paymentDetails['returnUrl'];

        // You do not require the buyer's shipping address be a confirmed address.
        $setECReqDetails->ReqConfirmShipping = 0;
        // PayPal does not display shipping address fields whatsoever.
        $setECReqDetails->NoShipping = 1;

        $setECReqType = new SetExpressCheckoutRequestType();
        $setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;

        $setECReq = new SetExpressCheckoutReq();
        $setECReq->SetExpressCheckoutRequest = $setECReqType;

        $paypalService = new PayPalAPIInterfaceServiceService($this->config);

        /*
          Example success response
          [Token] => EC-27T69808C98856806
          [Timestamp] => 2017-02-13T11:15:01Z
          [Ack] => Success
          [CorrelationID] => faa7421fa6281
          [Errors] =>
          [Version] => 106.0
          [Build] => 30029726
         */
        $this->response['SetExpressCheckout'] = $paypalService->SetExpressCheckout($setECReq);

        Log::info($this->response);

        // check response status and throw an error if response is not successful
        if (!isset($this->response['SetExpressCheckout']->Ack) ||
            $this->response['SetExpressCheckout']->Ack == 'Failure' ||
            !isset($this->response['SetExpressCheckout']->Token) ||
            is_null($this->response['SetExpressCheckout']->Token)) {

            if (isset($this->response['SetExpressCheckout']->Errors)) {
                throw new \Exception($this->response['SetExpressCheckout']->Errors[0]->ErrorCode . ': '. $this->response['SetExpressCheckout']->Errors[0]->LongMessage);
            } else {
                throw new \Exception('PayPal express checkout payment failed. Please try again later or contact website support.');
            }
        }
    }

    /**
     * Should be bound to successUrl
     *
     * @param array $paymentDetails
     */
    public function processPayment(array $paymentDetails)
    {
        // if payerID is not supplied it usually means that cancel link was clicked on the PayPal page
        if (!$paymentDetails['payer_id']) {
            $this->response['token'] = $paymentDetails['token'];
            return;
        }

        $getExpressCheckoutDetailsRequest = new GetExpressCheckoutDetailsRequestType($paymentDetails['token']);
        $getExpressCheckoutReq = new GetExpressCheckoutDetailsReq();
        $getExpressCheckoutReq->GetExpressCheckoutDetailsRequest = $getExpressCheckoutDetailsRequest;

        $paypalService = new PayPalAPIInterfaceServiceService($this->config);
        $getECResponse = $paypalService->GetExpressCheckoutDetails($getExpressCheckoutReq);

        $orderTotal = new BasicAmountType();
        $orderTotal->currencyID = $getECResponse->GetExpressCheckoutDetailsResponseDetails->PaymentDetails[0]->OrderTotal->currencyID;
        $orderTotal->value = $getECResponse->GetExpressCheckoutDetailsResponseDetails->PaymentDetails[0]->OrderTotal->value;

        $itemDetails = new PaymentDetailsItemType();
        $itemDetails->Name = $getECResponse->GetExpressCheckoutDetailsResponseDetails->PaymentDetails[0]->PaymentDetailsItem[0]->Name;
        $itemDetails->Amount = $getECResponse->GetExpressCheckoutDetailsResponseDetails->PaymentDetails[0]->PaymentDetailsItem[0]->Amount;
        $itemDetails->Quantity = $getECResponse->GetExpressCheckoutDetailsResponseDetails->PaymentDetails[0]->PaymentDetailsItem[0]->Quantity;
        $itemDetails->ItemCategory = $getECResponse->GetExpressCheckoutDetailsResponseDetails->PaymentDetails[0]->PaymentDetailsItem[0]->ItemCategory;

        $PaymentDetails = new PaymentDetailsType();
        $PaymentDetails->PaymentDetailsItem[0] = $itemDetails;
        $PaymentDetails->OrderTotal = $orderTotal;
        $PaymentDetails->PaymentAction = 'Sale';
        $PaymentDetails->ItemTotal = $orderTotal;

        $DoECRequestDetails = new DoExpressCheckoutPaymentRequestDetailsType();
        $DoECRequestDetails->PayerID = $paymentDetails['payer_id'];
        $DoECRequestDetails->Token = $paymentDetails['token'];
        $DoECRequestDetails->PaymentDetails[0] = $PaymentDetails;

        $DoECRequest = new DoExpressCheckoutPaymentRequestType();
        $DoECRequest->DoExpressCheckoutPaymentRequestDetails = $DoECRequestDetails;

        $DoECReq = new DoExpressCheckoutPaymentReq();
        $DoECReq->DoExpressCheckoutPaymentRequest = $DoECRequest;

        $paypalService = new PayPalAPIInterfaceServiceService($this->config);
        $this->response['DoExpressCheckoutPayment'] = $paypalService->DoExpressCheckoutPayment($DoECReq);

        Log::info($this->response);

        /*
         * PayPal\PayPalAPI\DoExpressCheckoutPaymentResponseType Object
          (
              [DoExpressCheckoutPaymentResponseDetails] => PayPal\EBLBaseComponents\DoExpressCheckoutPaymentResponseDetailsType Object
                  (
                      [Token] => EC-6F7584801U0719045
                      [PaymentInfo] => Array
                          (
                              [0] => PayPal\EBLBaseComponents\PaymentInfoType Object
                                  (
                                      [TransactionID] => 0UH54230P0689344P
                                      [EbayTransactionID] =>
                                      [ParentTransactionID] =>
                                      [ReceiptID] =>
                                      [TransactionType] => cart
                                      [PaymentType] => instant
                                      [RefundSourceCodeType] =>
                                      [ExpectedeCheckClearDate] =>
                                      [PaymentDate] => 2017-02-13T12:10:07Z
                                      [GrossAmount] => PayPal\CoreComponentTypes\BasicAmountType Object
                                          (
                                              [currencyID] => USD
                                              [value] => 6.00
                                          )

                                      [FeeAmount] => PayPal\CoreComponentTypes\BasicAmountType Object
                                          (
                                              [currencyID] => USD
                                              [value] => 0.35
                                          )

                                      [SettleAmount] =>
                                      [TaxAmount] => PayPal\CoreComponentTypes\BasicAmountType Object
                                          (
                                              [currencyID] => USD
                                              [value] => 0.00
                                          )

                                      [ExchangeRate] =>
                                      [PaymentStatus] => Completed
                                      [PendingReason] => none
                                      [ReasonCode] => none
                                      [HoldDecision] =>
                                      [ShippingMethod] =>
                                      [ProtectionEligibility] => Ineligible
                                      [ProtectionEligibilityType] => None
                                      [ReceiptReferenceNumber] =>
                                      [POSTransactionType] =>
                                      [ShipAmount] =>
                                      [ShipHandleAmount] =>
                                      [ShipDiscount] =>
                                      [InsuranceAmount] =>
                                      [Subject] =>
                                      [StoreID] =>
                                      [TerminalID] =>
                                      [SellerDetails] => PayPal\EBLBaseComponents\SellerDetailsType Object
                                          (
                                              [SellerId] =>
                                              [SellerUserName] =>
                                              [SellerRegistrationDate] =>
                                              [PayPalAccountID] => dev.paypal.business@webthegap.com
                                              [SecureMerchantAccountID] => QZ5HTCESVPUL8
                                          )

                                      [PaymentRequestID] =>
                                      [FMFDetails] =>
                                      [EnhancedPaymentInfo] =>
                                      [PaymentError] =>
                                      [InstrumentDetails] =>
                                      [OfferDetails] =>
                                      [BinEligibility] =>
                                  )

                          )

                      [BillingAgreementID] =>
                      [RedirectRequired] =>
                      [Note] =>
                      [SuccessPageRedirectRequested] => false
                      [UserSelectedOptions] =>
                      [CoupledPaymentInfo] =>
                  )

              [FMFDetails] =>
              [Timestamp] => 2017-02-13T12:10:07Z
              [Ack] => Success
              [CorrelationID] => 9973042f255e2
              [Errors] =>
              [Version] => 106.0
              [Build] => 30029726
          )
         */
    }

    public function paymentId()
    {
        return isset($this->response['SetExpressCheckout']->Token) ?
            $this->response['SetExpressCheckout']->Token :
            (isset($this->response['DoExpressCheckoutPayment']->DoExpressCheckoutPaymentResponseDetails->Token) ?
                $this->response['DoExpressCheckoutPayment']->DoExpressCheckoutPaymentResponseDetails->Token :
                (isset($this->response['token']) ?
                    $this->response['token'] :
                    NULL));
    }

    public function amount() {
        return isset($this->response['DoExpressCheckoutPayment']->DoExpressCheckoutPaymentResponseDetails->PaymentInfo[0]->GrossAmount->value) ?
            floatval($this->response['DoExpressCheckoutPayment']->DoExpressCheckoutPaymentResponseDetails->PaymentInfo[0]->GrossAmount->value) :
            $this->amount;
    }

    public function currency() {
        return isset($this->response['DoExpressCheckoutPayment']->DoExpressCheckoutPaymentResponseDetails->PaymentInfo[0]->GrossAmount->currencyID) ?
            floatval($this->response['DoExpressCheckoutPayment']->DoExpressCheckoutPaymentResponseDetails->PaymentInfo[0]->GrossAmount->currencyID) :
            $this->currency;
    }

    /**
     * Redirect to payment gateway to complete payment
     */
    public function redirectToGateway()
    {
        if (isset($this->response['SetExpressCheckout']->Token)) {
            header('Location: ' . sprintf($this->env[$this->config['mode']], $this->response['SetExpressCheckout']->Token));
            exit;
        }
    }

    public function completed()
    {
        return isset($this->response['DoExpressCheckoutPayment']->Ack) && $this->response['DoExpressCheckoutPayment']->Ack == 'Success';
    }

    public function pending()
    {
        return FALSE;
    }
}