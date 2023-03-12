<?php

namespace YoKartPaymentGateways\CashFree\Http\Controllers;

use App\Http\Controllers\PackageYokartController;
use YoKartTax\SystemTaxManagement\Models\SystemTax;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Package;
use App\Models\UserAddress;
use DB;

class CashFreeController extends PackageYokartController
{
    const CASHFREE_SANDBOX_URL = "https://test.cashfree.com";
    const CASHFREE_PRODUCTION_URL = "https://api.cashfree.com";

    public static function getApiBaseUrl()
    {
        $record = Package::getPackageBySlug('CashFree');
        if ($record['pkg_environment'] == Package::PKG_ENVIRONMENT_SANDBOX) {
            return self::CASHFREE_SANDBOX_URL;
        }
        return self::CASHFREE_PRODUCTION_URL;
    }

    public static function makePayment($request, $orderId, $amount = 0)
    {
        try {
            $opUrl = self::getApiBaseUrl()."/api/v1/order/create";
            $userAddress = UserAddress::getAddressByid($request->input('address-id'));
            if (empty($userAddress->addr_phone)) {
                return ['status' => false, 'message' =>  __('phone number is required') ];
            }
            $order = Order::select('order_user_id', 'order_id', 'order_net_amount', 'order_currency_code')->with(['user' => function ($query) {
                $query->select('user_id', DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'), 'user_email', 'user_phone');
            }])->where('order_id', $orderId)->first();


            if ($amount == 0) {
                $amount = $order->order_net_amount;
            }
            $cf_request = [];
            $record = Package::getRecordBySlug('CashFree');
            $cf_request["appId"] = $record['CASHFREE_APPID'];
            $cf_request["secretKey"] = $record['CASHFREE_SECRET_KEY'];
            $cf_request["orderId"] = $orderId;
            $cf_request["orderAmount"] = $amount;
            $cf_request["orderNote"] = "Yokart Payment";
            $cf_request["customerPhone"] = $userAddress->addr_phone;
            $cf_request["customerName"] = isset($order->user->user_name) ? $order->user->user_name : '';
            $cf_request["customerEmail"] = isset($order->user->user_email) ? $order->user->user_email : "";
            $cf_request["returnUrl"] =  route('cashFreePaymentSuceess');
            $cf_request["notifyUrl"] =  route('cashFreePaymentSuceess');
            $request_string = "";
            foreach ($cf_request as $key=>$value) {
                $request_string .= $key.'='.rawurlencode($value).'&';
            }
            $jsonResponse = self::curlRequest($opUrl, $cf_request, $request_string);
            if ($jsonResponse['status'] == true) {
                if ($jsonResponse['data']->status == "OK") {
                    return ['status'=> false, 'method'=>'redirect', 'url'=> $jsonResponse['data']->paymentLink ];
                } else {
                    return ['status' => false, 'message' => $jsonResponse['data']->reason];
                }
            } else {
                return ['status' => false, 'message' => $jsonResponse['error']];
            }
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->message];
        }
    }

    public static function paymentRefund($transactionId, $amount)
    {
        $data = [];
        $opUrl = self::getApiBaseUrl()."/api/v1/order/refund";
        $cf_request = [];
        $record = Package::getRecordBySlug('CashFree');
        $cf_request["appId"] = $record['CASHFREE_APPID'];
        $cf_request["secretKey"] = $record['CASHFREE_SECRET_KEY'];
        $cf_request["referenceId"] = $transactionId;
        $cf_request["refundAmount"] = $amount;
        $cf_request["refundNote"] = "Yokart Refund";
        $request_string = "";
        foreach ($cf_request as $key=>$value) {
            $request_string .= $key.'='.rawurlencode($value).'&';
        }
        $jsonResponse = self::curlRequest($opUrl, $cf_request, $request_string);
        if ($jsonResponse['status'] == true) {
            $data['id'] = $jsonResponse['data']->refundId;
            $data['status'] = true;
            return ['status' => true, 'data' => $data];
        } else {
            return ['status' => false, 'message' => $jsonResponse['error']];
        }
    }

    public static function curlRequest($opUrl, $cf_request, $request_string)
    {
        $timeout = 10;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$opUrl?");
        curl_setopt($ch, CURLOPT_POST, count($cf_request));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $curl_result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err) {
            return ['status'=> false, 'error' => $err];
        }
        return ['status'=> true, 'data' => json_decode($curl_result)];
    }
}
