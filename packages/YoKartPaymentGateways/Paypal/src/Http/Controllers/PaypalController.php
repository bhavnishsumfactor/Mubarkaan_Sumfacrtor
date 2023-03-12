<?php

namespace YoKartPaymentGateways\Paypal\Http\Controllers;

use App\Http\Controllers\PackageYokartController;
use YoKartTax\SystemTaxManagement\Models\SystemTax;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; // this is for the validation
use Srmklive\PayPal\Services\ExpressCheckout;
use App\Models\Order;
use App\Models\Package;
use App\Models\OrderAddress;
use Omnipay\Common\CreditCard;
use Omnipay\Omnipay;

class PaypalController extends PackageYokartController
{
    public static function makePayment($request, $orderId, $orderAmount, $redirect)
    {
        $order = Order::with(['address' => function ($sql) {
            $sql->where('oaddr_type', OrderAddress::BILLING_ADDRESS_TYPE);
        }])->select('order_id', 'order_net_amount', 'order_currency_code')->where('order_id', $orderId)->first();
    
      
        if ($redirect == 1) {
            $package = Package::getRecordBySlug('PaypalExpress');
            return static::expressCheckout($package, $order);
        } else {
            $package = Package::getRecordBySlug('Paypal');
            return static::cardPayment($request, $package, $order);
        }
    }

    public static function paymentRefund($transactionId, $amount, $currencyCode)
    {
        $mode = static::checkMode();
        $package = Package::getRecordBySlug('PaypalExpress');
        $gateway = Omnipay::create('PayPal_Pro');
        $gateway->setUsername($package['PAYPAL_EXPRESS_USERNAME']);
        $gateway->setPassword($package['PAYPAL_EXPRESS_PASSWORD']);
        $gateway->setSignature($package['PAYPAL_EXPRESS_SIGNATURE']);
        $gateway->setTestMode($mode);
        try {
            $transaction = $gateway->refund([
                'amount'    => $amount,
                'currency'  => $currencyCode
            ]);
            $transaction->setTransactionReference($transactionId);
            $response = $transaction->send();
            if ($response->isSuccessful()) {
                $data = $response->getData();
                $data['id'] = $data['REFUNDTRANSACTIONID'];
                return ['status' => true, 'data' => $data];
            }
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
    
    private static function expressCheckout($package, $order)
    {
        $gateway = Omnipay::create('PayPal_Express');
        $gateway->setUsername($package['PAYPAL_EXPRESS_USERNAME']);
        $gateway->setPassword($package['PAYPAL_EXPRESS_PASSWORD']);
        $gateway->setSignature($package['PAYPAL_EXPRESS_SIGNATURE']);
        $mode = static::checkMode();
        $gateway->setTestMode($mode);
        $parameters = [
            'amount' => $order->order_net_amount,
            'transactionId' => $order->order_id,
            'currency' => $order->order_currency_code,
            'cancelUrl' => '',
            'returnUrl' => route('paypalPaymentSuceess', ['id' => $order->order_id]),
        ];
        $response = $gateway->purchase($parameters)->send();
        return ['status'=> false, 'method'=>'redirect', 'url'=> $response->getRedirectUrl() ];
    }
      
    private static function cardPayment($request, $package, $order)
    {
        $mode = static::checkMode();
        $gateway = Omnipay::create('PayPal_Rest');
       
        $gateway->initialize([
            'clientId' => $package['PAYPAL_CLIENTID'],
            'secret'   => $package['PAYPAL_SECRET'],
            'testMode' => $mode
        ]);
        try {
            $card = new CreditCard([
                'firstName' => $request['name'],
                'lastName' => 'User',
                'number' => $request['number'],
                'expiryMonth' => $request['exp_month'],
                'expiryYear' => $request['exp_year'],
                'cvv' => $request['cvv'],
                'billingAddress1' => $order['address'][0]['oaddr_address1'],
                'billingCountry' => $order['address'][0]['oaddr_country_code'],
                'billingCity' => $order['address'][0]['oaddr_city'],
                'billingPostcode' => $order['address'][0]['oaddr_postal_code'],
                'billingState'      => $order['address'][0]['oaddr_state'],
            ]); 
            $transaction = $gateway->purchase([
                'amount'        => $order->order_net_amount,
                'currency'      => $order->order_currency_code,
                'description'   => 'yokart Payment',
                'card'          => $card,
            ])->send();
            $data = $transaction->getData();            
            if ($transaction->isSuccessful()) {
                $data['id'] = $transaction->getTransactionReference();
                $data['amount'] = $data['transactions'][0]['amount']['total'] ?? $order->order_net_amount;
                return ['status'=>true, 'data' => $data];
            } else {
                // Payment failed
                return ['status'=>false, 'message' => $transaction->getMessage()];
            }
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    private static function checkMode()
    {
        $packageMode = Package::select('pkg_environment')->where('pkg_slug', 'Paypal')->where('pkg_type', Package::PKG_TYPE['paymentGateways'])->first();
        $mode = $packageMode->pkg_environment == 1 ? true : false;
        return $mode;
    }
    public static function completePurchase($request, $order)
    {
        $package = Package::getRecordBySlug('PaypalExpress');
        $gateway = Omnipay::create('PayPal_Express');
        $gateway->setUsername($package['PAYPAL_EXPRESS_USERNAME']);
        $gateway->setPassword($package['PAYPAL_EXPRESS_PASSWORD']);
        $gateway->setSignature($package['PAYPAL_EXPRESS_SIGNATURE']);
        $gateway->setTestMode(static::checkMode());
        $response = $gateway->completePurchase(array(
            'amount' =>  $order->order_net_amount,
            'currency' =>  'USD',
            'token' => $request->get('token'),
            'payerid' => $request->get('PayerID'),
        ))->send();
        return $paypalResponse = $response->getData();
    }

    public static function getAllCards($userId)
    {
        return [];
    }
}
