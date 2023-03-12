<?php

namespace App\Helpers;

use App\Helpers\YokartHelper;
use Cart;

class PaymentGatewayHelper extends YokartHelper
{
    /*protected $activePackage;*/
    protected $paymentMethod;
    protected $type;

    public function __construct($type)
    {
        $this->type = $type;
        $this->paymentMethod = 'YoKartPaymentGateways\\'.$type.'\Http\Controllers\\'.$type.'Controller';
    }

    public function orderPayment($request, $orderId, $amount = 0, $redirect = 0)
    {
        if ($this->type == "Paypal" || $this->type == "CashFree") {
            return $this->paymentMethod::makePayment($request, $orderId, $amount, $redirect);
        }
        return $this->paymentMethod::makePayment($request, $orderId, $amount);
    }
    public function createCustomer($userId)
    {
        return $this->paymentMethod::createCustomer($userId);
    }
    public function orderPaymentByCartId($cardId, $orderId)
    {
        return $this->paymentMethod::makePaymentByCardId($cardId, $orderId);
    }

    public function orderPaymentByCardToken($token, $orderId,$isSave)
    {
        return $this->paymentMethod::makePaymentByCardToken($token, $orderId,$isSave);
    }


    public function orderRefund($transactionId, $amount, $currencyCode)
    {
        return $this->paymentMethod::paymentRefund($transactionId, $amount, $currencyCode);
    }
    public function saveCard($request, $userId)
    {
        return $this->paymentMethod::saveCard($request, $userId);
    }
    public function saveCardByToken($token, $customerId)
    {
        return $this->paymentMethod::saveCardInformation($token, $customerId);
    }
    public function getCards($userId)
    {
        return $this->paymentMethod::getAllCards($userId);
    }
    public function deleteCard($cardId, $userId)
    {
        return $this->paymentMethod::deleteCard($cardId, $userId);
    }
    public function getDefaultCard($userId)
    {
        return $this->paymentMethod::defaultCard($userId);
    }
    public function updateDefaultCard($userId, $cardId)
    {
        return $this->paymentMethod::updateDefaultCard($userId, $cardId);
    }
    public function completePurchase($request, $order)
    {
        return $this->paymentMethod::completePurchase($request, $order);
    }
}
