<?php

namespace YoKartPaymentGateways\TwoCheckout\Http\Controllers;

use App\Http\Controllers\PackageYokartController;
use YoKartTax\SystemTaxManagement\Models\SystemTax;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; // this is for the validation
use App\Models\Order;
use App\Models\UserCard;
use App\Models\User;
use TwoCheckout;
use DB;
use App\Models\Package;

class TwoCheckoutController extends PackageYokartController
{
    CONST API_URL = "https://api.2checkout.com/rest/6.0/orders/";

    public static function makePayment($request, $orderId, $amount = 0)
    {
        $orderInfo = Order::getRecordById($orderId);
        $orderAddress = $orderInfo['address'][0];
        if ($amount == 0) {
            $amount = $orderInfo['order_net_amount'];
        }
        if ($amount > 0) {            
            $order_actual_paid = number_format(round($amount, 2), 2, ".", "");
            $firstName = '';
            $lastName = '';
            if(isset($orderAddress['oaddr_name'])){
                $addrNameArr = explode(" ",$orderAddress['oaddr_name']);
                $firstName = $addrNameArr[0];
                $lastName = isset($addrNameArr[1])?$addrNameArr[1]:$addrNameArr[0];
            }
            $params = array(
                "Country" => $orderAddress['oaddr_country_code'],
                "currency" => $orderInfo["order_currency_code"],
                "CustomerReference"=> "GFDFE",
                "ExternalCustomerReference"=> "IOUER",
                "ExternalReference"=> "REST_API_AVANGTE",
                "Language"=> "en",
                "Source"=> "testAPI.com",
                "BillingDetails" => array(
                    "FirstName" => $firstName,
                    "LastName" => $lastName,
                    "Address1" => $orderAddress['oaddr_address1'] . ' ' . $orderInfo['oaddr_address2'],
                    "City" => $orderAddress['oaddr_city'],
                    "state" => $orderAddress['oaddr_state'],
                    "Zip" => $orderAddress['oaddr_postal_code'],
                    "CountryCode" => $orderAddress['oaddr_country_code'],
                    "Email" => $orderAddress['oaddr_email'],
                    "Phone" => $orderAddress['oaddr_phone']
                ),
                "Items"=> array(
                    array(
                        "Name"=>"Dynamic product",
                        "Description"=>"Test description",
                        "Quantity"=>1,
                        "IsDynamic"=>true,
                        "Tangible"=>false,
                        "PurchaseType"=>"PRODUCT",
                        "Price"=> array(
                            "Amount"=> $order_actual_paid,
                            "Type"=> "CUSTOM"
                        )
                    )
                ),
                "PaymentDetails"=> array(
                    "Currency"=> $orderInfo["order_currency_code"],
                    "CustomerIP"=> $_SERVER['REMOTE_ADDR'],
                    "PaymentMethod"=> array(
                        "EesToken"=> $request['token'],
                        "Vendor3DSReturnURL"=> url(''),
                        "Vendor3DSCancelURL"=> url('')
                    ),
                    "Type" => "EES_TOKEN_PAYMENT"
                )            
            );
            try{

                $response = self::curlRequest('POST', '', $params); 
                $error = (array)$response->Errors;
                $error = array_values($error);
                $message = __('NOTI_INVALID_REQUEST');

                if( isset($response->Errors) ){

                    $errorArr = array_values((array)$response->Errors);
                    $message = $errorArr[0];
                    return ['status' => false, 'message' => $message];
                       
                }else if( isset($response->Status) && $response->Status == 'AUTHRECEIVED'){

                    $data = array();       
                    $data['amount'] = $order_actual_paid;
                    $data['id'] = $response->RefNo;
                    $data['description'] = "";                   
                    return ['status' => true, 'data' => $data];

                }else{

                    return ['status' => false, 'message' => $message];
                }
            }catch(Exception $e){

                return ['status' => false, 'message' => $e->message];
            }
           
        } else {
            return ['status' => false, 'message' => __('NOTI_INVALID_REQUEST')];
        }
    }   
    public static function paymentRefund($transactionId, $amount)
    {
        $params = array("amount" => $amount, "reason"=>"CUSTOM_REASON", "comment"=>"");
        try {
            $data = array();
            $response = self::curlRequest('DELETE', $transactionId.'/', $params); 
            echo "<pre>";print_r($response);die;
            if( !$response || isset($response->errorCode)){
                return ['status' => false, 'message' => __('Invalid payment gateway configurations, Please contact seller.')];
            }
            if( isset($response->refundTransactionId) ){

                $data['id'] = $response->refundTransactionId;
                $data['amount'] = $amount;
                return ['status' => true, 'data' => $data];

            }else{
                $message = __('NOTI_INVALID_REQUEST');
                $error = $response->message[0]->errorName;
                switch($error){
                    case 'INVOICE_ALREADY_FULLY_REFUNDED':
                        $message = $response->message[0]->description;
                    case 'UNAUTHORIZED_REFUND_TRANSACTION':
                        $message = $response->message[0]->description;
                    default:
                        $message = __('NOTI_INVALID_REQUEST');                    
                }
                return ['status' => false, 'message' => $message];
            }

        }catch(Exception $e){

            return ['status' => false, 'message' => $e->message];
        }    
    }
    
    public static function curlRequest($method, $endUrl = '', $params = array())
    {
        $record = Package::getRecordBySlug('TwoCheckout');
        $url = self::API_URL;
        if( !empty($endUrl) ){
            $url .= $endUrl;
        }
        $curl = curl_init($url);
        $params = json_encode($params);
        $gmDate = gmdate('Y-m-d H:i:s');
        $string = strlen($record['MERCHANT_CODE']) . $record['MERCHANT_CODE'] . strlen($gmDate) . $gmDate;
        $hash = hash_hmac('md5', $string, $record['SECRET_CODE']);
        $header = array("content-type:application/json", "Accept:application/json", "content-length:" . strlen($params), 'X-Avangate-Authentication: code="'.$record['MERCHANT_CODE'].'" date="'.$gmDate.'" hash="'.$hash.'"');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_USERAGENT, "2Checkout PHP/0.1.0%s");
        if(!empty($params)){
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        }  
        $response = curl_exec($curl);
        return json_decode($response);
    }
}
