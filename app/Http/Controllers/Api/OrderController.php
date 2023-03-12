<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\YokartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use Carbon\Carbon;
use Str;
use App\Models\Product;
use App\Models\TaxGroupType;
use App\Models\OrderProductAdditionInfo;
use App\Models\OrderProductCharge;
use App\Models\OrderProductTaxLog;
use App\Models\ProductStockHold;
use App\Models\OrderProduct;
use App\Models\Order;
use App\Models\OrderMessage;
use App\Models\Admin\Admin;
use App\Models\Admin\AdminRole;
use App\Models\User;
use App\Models\OrderReturnRequest;
use App\Models\DiscountCoupon;
use App\Models\CouponHistory;
use App\Models\NotificationTemplate;
use App\Models\Country;
use App\Models\State;
use App\Models\Reason;
use App\Models\UserRewardPoint;
use App\Models\UserRewardPointBreakup;
use App\Models\Transaction;
use App\Models\OrderAddress;
use App\Models\SmsTemplate;
use App\Models\OrderReturnAmount;
use App\Models\OrderReturnBankInfo;
use App\Events\SystemNotification;
use App\Models\Notification;
use App\Models\ProductOptionVarient;
use App\Models\StoreAddress;
use App\Models\Package;
use App\Helpers\EmailHelper;
use App\Helpers\PaymentGatewayHelper;
use App\Jobs\SendNotification;
use App\Models\DigitalFileRecord;
use Cart;
use Crypt;

class OrderController extends YokartController
{
    public function placeOrder(Request $request)
    {
        $userId  = Auth::guard('api')->user()->user_id;
        $pickupAvailable = count(Cart::getPickups('name'));
        $paymentGateway = $request->input('payment_gateway');
        $shippingAddressId = $request->input('shipping_address_id');
        $billingAddressId = $request->input('billing_address_id');
        
        if (empty($paymentGateway)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_PLEASE_SELECT_PAYMENT_METHOD"));
        }
        
        if ($pickupAvailable == 0 && Cart::isDigitalProduct() != 1) {
            if (empty($shippingAddressId)) {
                return apiresponse(config('constants.ERROR'), appLang("NOTI_PLEASE_SELECT_SHIPPING_ADDRESS"));
            } elseif (empty($billingAddressId)) {
                return apiresponse(config('constants.ERROR'), appLang("NOTI_PLEASE_SELECT_BILLING_ADDRESS"));
            }
        } else {
            if (empty($billingAddressId)) {
                return apiresponse(config('constants.ERROR'), appLang("NOTI_PLEASE_SELECT_BILLING_ADDRESS"));
            }
        }
        if ($paymentGateway == 'cod' && isset($request['cod']) && $request['cod'] != 1) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_VERIFY_COD"));
        }
        $cartCollection = Cart::session($this->cartSession)->getContent();
        $price = Cart::session($this->cartSession)->getTotal();
        $subTotal = Cart::session($this->cartSession)->getSubTotal();
        
        $totalTax = 0;
        if ($tax = Cart::session($this->cartSession)->getCondition('tax')) {
            $totalTax = $tax->getValue();
        }
        $couponCode = '';
        $couponValue = 0;
        $couponType = '';
        $historyId = 0;
        if ($coupon = Cart::session($this->cartSession)->getCondition('coupon')) {
            $historyId = $coupon->getId();
            $couponCode = $coupon->getCode();
            $records = DiscountCoupon::getRecordByCouponCode($couponCode, $userId);
            $couponValue = $coupon->getValue();
            $couponType = $records->discountcpn_in_percent;
        }
        $totalShipping = 0;
        if ($shipping = Cart::session($this->cartSession)->getCondition('shipping')) {
            $totalShipping = $shipping->getValue();
        }
        $totalRedeemedPoints = 0;
        $totalRedeemedPointsAmount = 0;
        if ($rewardpoints = Cart::session($this->cartSession)->getCondition('rewardpoints')) {
            $totalRedeemedPointsAmount = $rewardpoints->getValue();
            $totalRedeemedPoints = $rewardpoints->getPoints();
        }
        $giftAmount = 0;
        if ($gift = Cart::session($this->cartSession)->getCondition('gift')) {
            $giftAmount = $gift->getValue();
        }

        $shippingMethods = json_encode(Cart::getShippingAttributes());
        $currency = getSystemCurrency();
        $shipType = Order::ORDER_SHIPPING;
        $timeLineMessage = Order::SHIPPING_STATUS[Order::SHIPPING_STATUS_IN_PROGRESS];
        if (count(Cart::getPickups('name')) > 0) {
            $shipType = Order::ORDER_PICKUP;
            $timeLineMessage = Order::PICKUP_STATUS[Order::SHIPPING_STATUS_IN_PROGRESS];
        }
        if (Cart::isDigitalProduct() == true) {
            $timeLineMessage = Order::DIGITAL_STATUS[Order::SHIPPING_STATUS[Order::SHIPPING_STATUS_IN_PROGRESS]];
        }
        $paymentSatus = Order::PAYMENT_PENDING;
        if ($paymentGateway == "rewards" && $price == 0) {
            $paymentSatus = Order::PAYMENT_PAID;
        }
        $orderDate = now();
        $insertId = Order::create([
            'order_user_id' => $userId,
            'order_payment_status' => $paymentSatus,
            'order_shipping_status' => Order::SHIPPING_STATUS_IN_PROGRESS,
            'order_status' => Order::ORDER_STATUS_OPEN,
            'order_payment_method' => $paymentGateway,
            'order_net_amount' => $price,
            'order_amount_charged' => 0,
            'order_shipping_type' =>  $shipType,
            'order_tax_charged' => $totalTax,
            'order_discount_coupon_code' => $couponCode,
            'order_discount_type' => $couponType,
            'order_discount_amount' => abs($couponValue),
            'order_currency_code' => $currency->currency_code,
            'order_currency_symbol' => $currency->currency_symbol,
            'order_currency_value' => $currency->currency_value,
            'order_shipping_value' => $totalShipping,
            'order_date_added' => $orderDate,
            'order_gift_amount' => $giftAmount,
            'order_reward_points' => $totalRedeemedPoints,
            'order_reward_amount' => abs($totalRedeemedPointsAmount),
            'order_shipping_methods' => $shippingMethods,
            'order_notes' => $request->input('order_notes'),
            'order_cart_data' => serialize($cartCollection),
            'order_from' => Order::ORDER_FROM_APP
        ])->id;


        /* $data = NotificationTemplate::getBySlug('order_created_by_user');
        $message = str_replace('{orderNumber}', $insertId, $data->ntpl_body);
        $message = str_replace('{orderAmount}', formatPriceByCurrencyCode($price, $currency->currency_code), $message);
        event(new SystemNotification([
            'type' => Notification::ORDER_CREATED_BY_USER,
            'record_id' => $insertId,
            'from_id' => $userId,
            'message' => $message
        ])); */

        $this->savedComment($insertId, OrderMessage::MSG_ORDER_TIMELLINE_TYPE, $timeLineMessage, 0, $userId);

        $this->saveProducts($insertId, $cartCollection, $totalRedeemedPoints);

        if ($totalRedeemedPoints != 0) {
            UserRewardPoint::redeemRewardPoints($userId, $insertId, $totalRedeemedPoints);
        }


        if ($historyId != 0) {
            $this->updateCoupon($historyId, $insertId, $userId);
        }

        if ($pickupAvailable > 0 || Cart::isDigitalProduct()) {
            OrderAddress::saveAddressFromUserAddress($insertId, $billingAddressId, OrderAddress::BILLING_ADDRESS_TYPE);
        } else {
            OrderAddress::saveAddressFromUserAddress($insertId, $shippingAddressId, OrderAddress::SHIPPING_ADDRESS_TYPE);
            OrderAddress::saveAddressFromUserAddress($insertId, $billingAddressId, OrderAddress::BILLING_ADDRESS_TYPE);
        }

        $order = Order::getRecordById($insertId, $userId);
     //   $this->orderConfirmationNotification($order);
        $this->newOrderEmailToAdmin($order);
        
        ProductStockHold::where('pshold_session_id', $this->cartSession)->delete();
        $orderStatus = [];
        $orderStatus['data']['id'] = '';
        $encryptedOrderId = Crypt::encryptString($insertId . config("app.salt") . $userId);
       
        if ($paymentGateway != 'cod' && $paymentGateway != 'rewards') {
            $orderStatus = $this->orderPayment($request, $insertId, $price, $currency->currency_code);

            $chargePrice = 0;
            if (empty($orderStatus['status'])) {
                if (isset($orderStatus['method']) &&  $orderStatus['method'] == "redirect") {
                    return apiresponse(config('constants.SUCCESS'), '', ['payment_info' => $orderStatus]);
                }
                Cart::session($this->cartSession)->clear();
                $this->paymentFailedSendPaymentLink($order);
                return apiresponse(config('constants.ERROR'), $orderStatus['message'], ['url' => route('successError', $encryptedOrderId)]);
            }
            $chargePrice = $orderStatus['data']['amount'];
            
            if (round($chargePrice) != round($price)) {
                $this->paymentFailedSendPaymentLink($order);
                return apiresponse(config('constants.ERROR'), appLang("NOTI_PAYMENT_FAILED"), ['url' => route('successError', $encryptedOrderId)]);
            }
            $this->notifyPaymentRecived($insertId, $userId, $chargePrice, $currency->currency_code);
            $this->orderUpdate($insertId, Order::PAYMENT_PAID, $chargePrice);
        }
        $this->orderTransaction($insertId, $price, $userId, $paymentGateway, $orderStatus);


        if ($paymentGateway == 'reward_points') {
            $this->orderUpdate($insertId, Order::PAYMENT_PAID, 0);
        }
        $this->updateProductInventory($insertId);

        $this->updateRewards($order);
        Cart::session($this->cartSession)->clear();
        $sysCurrency = \App\Models\Currency::getSystemDefault();

        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_ORDER_CREATED_SUCCESSFULLY"), [
            'order_id' => $insertId,
            'currency' => $sysCurrency->currency_code,
            'order_date' => getConvertedDateTime($orderDate),
            'value' => $price
        ]);
    }
    
    private function notifyPaymentRecived($orderId, $userId, $amount, $currencyCode)
    {
        /** trigger event for system notification */
        $data = NotificationTemplate::getBySlug('order_payment_received');
        $message = str_replace('{orderNumber}', $orderId, $data->ntpl_body);
        $message = str_replace('{orderAmount}', formatPriceByCurrencyCode($amount, $currencyCode), $message);
        $message = str_replace('{paymentMethod}', 'CashFree', $message);
        event(new SystemNotification([
            'type' => Notification::ORDER_PAYMENT,
            'record_id' => $orderId,
            'from_id' => $userId,
            'message' => $message
        ]));
    }
    public function cashFreeSucess($order, $paymentResponse)
    {
        $orderId = $order->order_id;
        $encryptedOrderId = Crypt::encryptString($orderId . config("app.salt") . $order->order_user_id);

        if ($paymentResponse['txStatus'] == "SUCCESS") {
            
            
            $this->orderUpdate($orderId, Order::PAYMENT_PAID, $order->order_net_amount);
            $data['data'] = $paymentResponse;
            $data['data']['id'] = $paymentResponse['referenceId'];
            $data['data']['status'] = true;
            $orderStatus = $this->orderTransaction($orderId, $paymentResponse['orderAmount'], $order->order_user_id, 'CashFree', $data);
            if (round($paymentResponse['orderAmount']) != round($order->order_net_amount)) {
                $this->paymentFailedSendPaymentLink($order);
                return apiresponse(config('constants.ERROR'), $orderStatus['message'], ['url' => route('successError', $encryptedOrderId)]);
            }
            $this->updateProductInventory($orderId);
            $this->notifyPaymentRecived($orderId, $order->order_user_id, $order->order_net_amount, $order->order_currency_code);
            $this->updateRewards($order);
            return apiresponse(config('constants.SUCCESS'), '', ['url' => route('successError', $encryptedOrderId)]);
        }
    }

    public function paypalSucess($order, $paymentResponse)
    {
        $orderId = $order->order_id;
        $encryptedOrderId = Crypt::encryptString($orderId . config("app.salt") . $order->order_user_id);

        $this->orderUpdate($orderId, Order::PAYMENT_PAID, $order->order_net_amount);
        $data['data'] = $paymentResponse;
        $data['data']['id'] = $paymentResponse['transaction_id'];
        $data['data']['status'] = true;
        $orderStatus = $this->orderTransaction($orderId, $paymentResponse['orderAmount'], $order->order_user_id, 'PayPal', $data);
        if (round($paymentResponse['orderAmount']) != round($order->order_net_amount)) {
            $this->paymentFailedSendPaymentLink($order);
            return apiresponse(config('constants.ERROR'), $orderStatus['message'], ['url' => route('successError', $encryptedOrderId)]);
        }
        $this->updateProductInventory($orderId);
        $this->notifyPaymentRecived($orderId, $order->order_user_id, $order->order_net_amount, $order->order_currency_code);
        $this->updateRewards($order);
        return apiresponse(config('constants.SUCCESS'), '', ['url' => route('successError', $encryptedOrderId)]);    
    }
    
    private function updateRewards($order)
    {
        $subTotal = calCulateOrderSubtotal($order);
        $canEarnOnRedeemed = (bool) getConfigValueByName('REWARD_POINTS_EARN_ON_REDEEMED_ORDER');
        $rewardActiveImmediately = (bool) getConfigValueByName('REWARD_POINTS_PURCHASE_POINTS_ACTIVATED_IMMEDIATELY');

        if (($canEarnOnRedeemed == false &&  $order->order_reward_amount == 0) || $canEarnOnRedeemed == true) {
            if ($rewardActiveImmediately == true) {
                $this->saveRewardPoints($subTotal - $order->order_discount_amount, $subTotal, $order->order_user_id, $order->order_id);
            } else {
                Order::where('order_id', $order->order_user_id)->update(['order_pending_rewards' => true]);
            }
        }
    }

    public function updatePaymentDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required',
            'type' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
     
        switch ($request->input('type')) {
            case 'CashFree':
                $paymentResponse = json_decode($request->input('data'), true);
                $orderId = $paymentResponse['orderId'];
                $order = Order::getOrderById($orderId);
                $this->cashFreeSucess($order, $paymentResponse);
                break;

            case 'Paypal':
                $orderId = $request->input('order_id');
                $paymentResponse = ['transaction_id'=>$request->input('capture_id'),'orderAmount' => $request->input('order_amount'),'order_id'=>$orderId,'paypal_order_id'=>$request->input('paypal_order_id'), 'response'=>$request->input('data')];
                $order = Order::getOrderById($orderId);
                $this->paypalSucess($order, $paymentResponse);
                break;    
        }
        Cart::session($this->cartSession)->clear();
        $sysCurrency = \App\Models\Currency::getSystemDefault();
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_ORDER_CREATED_SUCCESSFULLY"), [
            'order_id' => (int) $orderId,
            'currency' =>  $order->order_currency_code,
            'value' => $order->order_net_amount,
            'order_date' => getConvertedDateTime($order->order_date_added)
        ]);
    }


    public function orders(Request $request, $page = 1)
    {
        $response = [];
        $orderId = $request->input('order_id');
        $type = $request->input('type');
        $orders = Order::getOrdersForApp(Auth::guard('api')->user()->user_id, $type, $page, $orderId);
        
        foreach ($orders as $orderkey => $order) {
            $order->paymentUrl = "";
            if ($order->payment_status == 1 &&  $order->order_status != 4 && ($order->order_payment_method != 'rewards' || $order->order_payment_method != 'cod')) {
                $order->paymentUrl = $this->getPaymentLink($order->order_id);
            }

            foreach ($orders[$orderkey]->products as $productkey => $product) {
                $product->review = true;
                $variantNames = "";
                if (!empty($product->op_product_variants->styles)) {
                    $variantNames = \Arr::flatten($product->op_product_variants->styles);
                    $variantNames = implode(" | ", $variantNames);
                }
                $orders[$orderkey]->products[$productkey]->op_product_variants =  $variantNames;

                if (($product->cancel_request === null || ($product->cancel_request != '' && $product->cancel_request->orrequest_status == 0))
                && ($order->order_shipping_status == 1 ||  $order->order_shipping_status == 2)) {
                    $product->review = false;
                }
            }
            $order->type = (in_array($order->order_shipping_status, [1, 2]) ? 0 : 1);
            $order->showRequest = $this->isRequestExist($order);
            $orders[$orderkey]->order_date = getConvertedDateTime($order->order_date_added);
            unset($orders[$orderkey]->order_date_added);
            unset($orders[$orderkey]->order_date_updated);
            $statusVal = "0";
            $statusText = "";
            $statusColor = Order::STATUS_COLORS[$order->order_shipping_status];
            if ($order->digital_products > 0 && $order->digital_products == count($order->products)) {
                $statusVal = "2";
                $statusText = Order::DIGITAL_STATUS[Order::SHIPPING_STATUS[$order->order_shipping_status]];
            } elseif ($order->order_shipping_type == Order::ORDER_SHIPPING) {
                $statusVal = "0";
                $statusText = Order::SHIPPING_STATUS[$order->order_shipping_status];
            } elseif ($order->order_shipping_type == Order::ORDER_PICKUP) {
                $statusVal = "1";
                $statusText = Order::PICKUP_STATUS[$order->order_shipping_status];
            }
            $orders[$orderkey]->order_status_value = $statusVal;
            $orders[$orderkey]->order_status_text = $statusText;
            $orders[$orderkey]->order_status_color = $statusColor;

            $type = [OrderMessage::MSG_ORDER_TIMELLINE_TYPE, OrderMessage::MSG_ORDER_ACTION_TYPE];
            $orderComments = OrderMessage::getRecordsForApp($order->order_id, $type, 1, false);
            foreach ($orderComments as $key => $comment) {
                $orderComments[$key]->omsg_created_at = getConvertedDateTime($comment->omsg_created_at);
            }
            $orders[$orderkey]->order_comments = $orderComments->toArray();
            $paymentDetail = [];
            $paymentDetail['payment_gateway'] = (string) $order->order_payment_method;
            $paymentDetail['payment_status'] = (string) $order->payment_status;
            $paymentDetail['transaction_id'] = !empty($order->transaction->txn_gateway_transaction_id) ? (string) $order->transaction->txn_gateway_transaction_id : '';
            $paymentDetail['name'] = !empty($order->transaction->txn_gateway_response->data->source->name) ? (string) $order->transaction->txn_gateway_response->data->source->name : '';
            $paymentDetail['card_number'] = !empty($order->transaction->txn_gateway_response->data->source->last4) ? (string) $order->transaction->txn_gateway_response->data->source->last4 : '';
            $paymentDetail['expiry_year'] = !empty($order->transaction->txn_gateway_response->data->source->exp_year) ? (string) $order->transaction->txn_gateway_response->data->source->exp_year : '';
            $paymentDetail['expiry_month'] = !empty($order->transaction->txn_gateway_response->data->source->exp_month) ? (string) $order->transaction->txn_gateway_response->data->source->exp_month : '';
            $paymentDetail['card_type'] = !empty($order->transaction->txn_gateway_response->data->source->brand) ? (string) $order->transaction->txn_gateway_response->data->source->brand : '';
            $orders[$orderkey]->payment_detail = $paymentDetail;

            $show_bank_form = 0;
            if (
                ($order->order_payment_method == 'cod' && $order->payment_status == 2 && $order->transaction) ||
                ($order->transaction && in_array($order->transaction->txn_gateway_type, ['cash', 'card']))
            ) {
                $show_bank_form = 1;
            }
            $orders[$orderkey]->show_bank_form = $show_bank_form;

            $shippingPrice = 0;
            $taxPrice = 0;
            $discountPrice = 0;
            $rewardPrice = 0;
            $giftPrice = 0;

            if (!empty($order->order_shipping_value)) {
                $shippingPrice = $order->order_shipping_value;
            }

            if (!empty($order->order_tax_charged)) {
                $taxPrice = $order->order_tax_charged;
            }

            if (!empty($order->order_discount_amount)) {
                $discountPrice = $order->order_discount_amount;
            }
            if (!empty($order->order_reward_amount)) {
                $rewardPrice = $order->order_reward_amount;
            }
            if (!empty($order->order_gift_amount)) {
                $giftPrice =  $order->order_gift_amount;
            }

            $subTotal = $order->order_net_amount - $taxPrice - $shippingPrice - $giftPrice + $discountPrice + $rewardPrice;

            $priceDetail = [];
            $priceDetail[] = [
                'key' => 'subtotal',
                'value' => (string) $subTotal
            ];
            $priceDetail[] = [
                'key' => 'tax',
                'value' => (string) $taxPrice
            ];
            $priceDetail[] = [
                'key' => 'shipping',
                'value' => (string) $shippingPrice
            ];
            $priceDetail[] = [
                'key' => 'discount',
                'value' => (string) $discountPrice
            ];
            $priceDetail[] = [
                'key' => 'reward',
                'value' => (string) $rewardPrice
            ];
            $priceDetail[] = [
                'key' => 'gift',
                'value' => (string) $giftPrice
            ];
            $priceDetail[] = [
                'key' => 'total',
                'value' => (string) $order->order_net_amount
            ];

            $orders[$orderkey]->price_detail = $priceDetail;

            $shippingMethods = json_decode($order->order_shipping_methods, true);
            foreach ($orders[$orderkey]->address as $addrKey => $address) {
                $pickupDate = "";
                $pickupTime = "";
                
                if (isset($shippingMethods['pick_ups']) && !empty($shippingMethods['pick_ups']) && $address->oaddr_type === 3) {
                    $pickupDate = $shippingMethods['pick_ups']['pickup_date'] ?? "";
                    $pickupTime = $shippingMethods['pick_ups']['pickup_time'] ?? "";
                }
                $orders[$orderkey]->address[$addrKey]->pickup_date = $pickupDate;
                $orders[$orderkey]->address[$addrKey]->pickup_time = $pickupTime;
            }
            
            unset($orders[$orderkey]->transaction);
        }
        
        $orders = $orders->toArray();
        $response['total'] = $orders['total'];
        $response['pages'] = ceil($orders['total'] / $orders['per_page']);
        $response['orders'] = $orders['data'];
        
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }
    private function isRequestExist($order)
    {
        $digital = true;
        if ($order->digital_products == count($order->products) &&
          in_array($order->order_shipping_status, [1, 2]) == false
        ) {
            $digital = false;
        }
        if ($order->order_shipping_status != 3 &&
          $order->order_status != 4 &&
          $order->order_status != 3 &&
          count($order->products) != $order->return_products_count &&
          $digital == true
        ) {
            return true;
        }
            return false;
    }
    public function getInvoice(Request $request, $order_id)
    {
        $response = [];
        $response['invoice_url'] = url('order/download-pdf/' . $order_id);
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }

    public function returns(Request $request, $page = 1)
    {
        $response = [];
        $orderId = $request->input('order_id');
        $type = $request->input('type');
        $orders = OrderProduct::getReturnRecordsForApp(Auth::guard('api')->user()->user_id, $type, $page, $orderId);
        foreach ($orders as $orderkey => $order) {
            $variantNames = "";
            if (!empty($order->op_product_variants->styles)) {
                $variantNames = \Arr::flatten($order->op_product_variants->styles);
                $variantNames = implode(" | ", $variantNames);
            }
            $orders[$orderkey]->op_product_variants =  $variantNames;
            $orders[$orderkey]->orrequest_date = getConvertedDateTime($order->orrequest_date);
            $address = [];
            
            $config = getConfigValues(['BUSINESS_NAME', 'BUSINESS_ADDRESS1', 'BUSINESS_ADDRESS2', 'BUSINESS_CITY', 'BUSINESS_STATE', 'BUSINESS_COUNTRY', 'BUSINESS_PINCODE', 'BUSINESS_PHONE_COUNTRY_CODE','BUSINESS_PHONE_NUMBER']);
            $stateName = $countryName = "";
            if (!empty($config["BUSINESS_STATE"])) {
                $state = State::getRecordById($config["BUSINESS_STATE"]);
                $stateName = $state->state_name;
            }
            if (!empty($config["BUSINESS_COUNTRY"])) {
                $country = Country::getRecordById($config["BUSINESS_COUNTRY"]);
                $countryName = $country->country_name;
                $countryCode = $country->country_code;
            }
            $country = Country::getCountries(['country_phonecode'], ['country_id' => $config['BUSINESS_PHONE_COUNTRY_CODE']])->first();
            $address[] = [
                'oaddr_type' => 4,
                'oaddr_name' => $config['BUSINESS_NAME'],
                'oaddr_address1' => $config['BUSINESS_ADDRESS1'],
                'oaddr_address2' => $config['BUSINESS_ADDRESS2'],
                'oaddr_city' => $config['BUSINESS_CITY'],
                'oaddr_state' => $stateName,
                'oaddr_country' => $countryName,
                'oaddr_country_code' => $countryCode,
                'oaddr_postal_code' => $config['BUSINESS_PINCODE'],
                'oaddr_phone_country_id' => $config['BUSINESS_PHONE_COUNTRY_CODE'],
                'oaddr_phone_country_code' => (!empty($country->country_phonecode) ? '+' . $country->country_phonecode : ''),
                'oaddr_phone' => $config['BUSINESS_PHONE_NUMBER'],
            ];
            
            $orders[$orderkey]->address = $address;

            $type = OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE;
            $orderComments = OrderMessage::getRecordsForApp($order->orrequest_id, $type, 1, false);
            foreach ($orderComments as $key => $comment) {
                $orderComments[$key]->omsg_created_at = getConvertedDateTime($comment->omsg_created_at);
            }
            $orders[$orderkey]->order_comments = $orderComments->toArray();

            $shippingPrice = 0;
            $taxPrice = 0;
            $discountPrice = 0;
            $rewardPrice = 0;
            $giftPrice = 0;

            if (!empty($order->oramount_shipping)) {
                $shippingPrice = $order->oramount_shipping;
            }

            if (!empty($order->oramount_tax)) {
                $taxPrice = $order->oramount_tax;
            }

            if (!empty($order->oramount_discount)) {
                $discountPrice = $order->oramount_discount;
            }
            if (!empty($order->oramount_reward_price)) {
                $rewardPrice = $order->oramount_reward_price;
            }
            if (!empty($order->oramount_giftwrap_price)) {
                $giftPrice =  $order->oramount_giftwrap_price;
            }

            // $subTotal = $order->order_net_amount - $taxPrice - $shippingPrice - $giftPrice + $discountPrice + $rewardPrice;
            $subTotal = $order->op_product_price * $order->orrequest_qty;
            $total = $subTotal + $taxPrice + $shippingPrice + $giftPrice - $rewardPrice - $discountPrice;

            $priceDetail = [];
            $priceDetail[] = [
                'key' => 'subtotal',
                'value' => (string) $subTotal
            ];
            $priceDetail[] = [
                'key' => 'tax',
                'value' => (string) $taxPrice
            ];
            $priceDetail[] = [
                'key' => 'shipping',
                'value' => (string) $shippingPrice
            ];
            $priceDetail[] = [
                'key' => 'discount',
                'value' => (string) $discountPrice
            ];
            $priceDetail[] = [
                'key' => 'reward',
                'value' => (string) $rewardPrice
            ];
            $priceDetail[] = [
                'key' => 'gift',
                'value' => (string) $giftPrice
            ];
            $priceDetail[] = [
                'key' => 'total',
                'value' => (string) $total
            ];

            $orders[$orderkey]->price_detail = $priceDetail;
            $orders[$orderkey]->refunded_amount = $total;
        }
        $orders = $orders->toArray();
        $response['total'] = $orders['total'];
        $response['pages'] = ceil($orders['total'] / $orders['per_page']);
        $response['orders'] = $orders['data'];
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }
    public function loadOrderComments(Request $request, $orderId, $orderType, $page)
    {
      
        $defaultStatus =  array_reverse(Order::SHIPPING_STATUS);
        if ($orderType == 1) {
            $defaultStatus =  array_reverse(Order::PICKUP_STATUS);
        } elseif ($orderType == 2) {
            $defaultStatus =  array_reverse(array_values(Order::DIGITAL_STATUS));
        }
   
        $response = [];
        $type = [OrderMessage::MSG_ORDER_TIMELLINE_TYPE, OrderMessage::MSG_ORDER_ACTION_TYPE];
        $comments = OrderMessage::getRecordsForApp($orderId, $type, $page);
        foreach ($comments as $key => $comment) {
            $comments[$key]->omsg_created_at = getConvertedDateTime($comment->omsg_created_at);
        }
        $comments = $comments->toArray();
        $response['total'] = $comments['total'];
        $response['default_status'] = $defaultStatus;
        $response['pages'] = ceil($comments['total'] / $comments['per_page']);
        $response['comments'] = $comments['data'];
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }
    
    public function loadReturnComments(Request $request, $orrequestId, $page)
    {
        $response = [];
        $type = OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE;
        $comments = OrderMessage::getRecordsForApp($orrequestId, $type, $page);
        foreach ($comments as $key => $comment) {
            $comments[$key]->omsg_created_at = getConvertedDateTime($comment->omsg_created_at);
        }
        $comments = $comments->toArray();
        $response['total'] = $comments['total'];
        $response['pages'] = ceil($comments['total'] / $comments['per_page']);
        $response['comments'] = $comments['data'];
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }
    
    public function saveOrderComment(Request $request, $orderId)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $response = [];
        $type = OrderMessage::MSG_ORDER_TIMELLINE_TYPE;
        $comment = $request->input('comment');
        $userId = Auth::guard('api')->user()->user_id;
        $fromType = OrderMessage::MSG_FROM_USER;
        $cmtId = OrderMessage::createComment($orderId, $comment, $type, 0, $userId, $fromType);
        $response["comment"] = OrderMessage::getRecordForApp($cmtId);
        $this->commentNotification($orderId, $comment);
        return apiresponse(config('constants.SUCCESS'), appLang('NOTI_COMMENT_SUCCESSFULLY_SENT'), $response);
    }
    public function saveReturnComment(Request $request, $orrequestId)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $response = [];
        $type = OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE;
        $comment = $request->input('comment');
        $userId = Auth::guard('api')->user()->user_id;
        $fromType = OrderMessage::MSG_FROM_USER;
        $cmtId = OrderMessage::createComment($orrequestId, $comment, $type, 0, $userId, $fromType);
        $response["comment"] = OrderMessage::getRecordForApp($cmtId);
        $this->commentNotification($orrequestId, $comment, "return");
        return apiresponse(config('constants.SUCCESS'), appLang('NOTI_COMMENT_SUCCESSFULLY_SENT'), $response);
    }

    public function getReturnData(Request $request)
    {
        $requestData = $request->all();
        $orderId = $requestData["order_id"];
        $userId = Auth::guard('api')->user()->user_id;
        $order = Order::getRecordById($orderId, $userId);
        if (empty($order)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_ORDER_NOT_FOUND"));
        }
        $opIds = !empty($requestData["op_id"]) ? json_decode($requestData["op_id"], true) : [];
        $qty = !empty($requestData["qty"]) ? json_decode($requestData["qty"], true) : [];
        if (count($opIds) == 0 || count($qty) == 0 || count($opIds) != count($qty)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_INVALID_REQUEST"));
        }
        $opIdsOrdered = implode(',', $opIds);
        $orderProducts = OrderProduct::whereIn('op_id', $opIds)->where('op_order_id', $orderId)->orderByRaw("FIELD(op_id, $opIdsOrdered)")->get();
        $refundSumary = $this->refundSumary($orderProducts, $qty, $order);
        return apiresponse(config('constants.SUCCESS'), appLang('NOTI_COMMENT_SUCCESSFULLY_SENT'), $refundSumary);
    }

    private function refundSumary($orderProducts, $qty, $order)
    {
        
        $tax = 0;
        $subTotal = 0;
        $totalTax = 0;
        $shipping = $order->order_shipping_value;
        $discount = 0;
        $maxQty = 0;
        $selectedQty = 0;
        $reward = 0;
        $gift = 0;
        $rewardPrice = 0;
        $discountAmount = 0;
        foreach($order->products as $prd){
            $maxQty += $prd->op_qty;
        }
        foreach ($orderProducts as $key => $orderProduct) {
            $rewardPrice = 0;
            $selected_qty = $qty[$key];
            if ($selected_qty != 0) {
                $subTotal += $orderProduct->op_product_price * $selected_qty;
                if (!empty($orderProduct->tax) && $orderProduct->tax->opc_type == "tax") {
                    $totalTax = floatval($orderProduct->tax->opc_value) * $selected_qty;
                }
                $tax += $totalTax;
                if ($order->order_discount_amount != 0) {
                    $discountAmount = $orderProduct->additionInfo->opainfo_discount_amount;
                }
                if ($order->order_reward_amount != 0) {
                    $rewardPrice = (($order->order_net_amount * $orderProduct->additionInfo->opainfo_reward_rate) / 100) / $orderProduct->op_qty;
                }
                if ($order->order_gift_amount != 0 && !empty($orderProduct->additionInfo->opainfo_gift_amount)) {
                    $gift += floatval($orderProduct->additionInfo->opainfo_gift_amount * $selected_qty);
                }
                $reward += floatval($rewardPrice * $selected_qty);
                $discount += floatval($discountAmount * $selected_qty);

            //    echo $order->order_net_amount." -- ".$order->order_reward_amount." -- ".$orderProduct->additionInfo->opainfo_reward_rate;
            }
        //    $maxQty += floatval($orderProduct->op_qty);
            $selectedQty += floatval($selected_qty);
        }
       
        if ($maxQty != $selectedQty || (!in_array($order->order_shipping_status, [1, 2]) && getConfigValueByName('RETURN_SHIPPING_ENABLE') == 0)) {
            $shipping = 0;
        }
        $total = floatval($subTotal) + floatval($tax) + floatval($shipping) - round($discount, 2) ;
    
        if ($order->order_shipping_status == 1) {
            $total = floatval($total) + floatval($gift);
        }
        if ($total < 0) {
            $total = 0;
        }
  
        if ($order->order_reward_amount != 0) {
            if ($order->order_shipping_status != 1 && $gift != 0) {
                $total = floatval($reward) - $gift;
                $reward = floatval($total) + $gift;
            } else {
                $ttl = $total;
                $total = $reward;
                $reward = $ttl - $reward > 0 ? floatval($ttl - $reward) : 0;
            }
        }
        $total_refund = $order->payment_status == 2 ? $total : 0;

        $priceDetail = [];
        $priceDetail[] = [
            'key' => 'subtotal',
            'value' => (string) round($subTotal, 2)
        ];
        $priceDetail[] = [
            'key' => 'tax',
            'value' => (string) round($tax, 2)
        ];
        $priceDetail[] = [
            'key' => 'shipping',
            'value' => (string) round($shipping, 2)
        ];
        $priceDetail[] = [
            'key' => 'discount',
            'value' => (string) round($discount, 2)
        ];
        $priceDetail[] = [
            'key' => 'reward',
            'value' => (string) round($reward, 2)
        ];
        $priceDetail[] = [
            'key' => 'total',
            'value' => (string) round($total, 2)
        ];
        $priceDetail[] = [
            'key' => 'total_refund',
            'value' => (string) round($total_refund, 2)
        ];

        

        return ['price_detail' => $priceDetail, 'total' => (string) round($total_refund,2)];
    }

    public function returnItems(Request $request)
    {
        $requestData = $request->all();
      
        $items = $requestData["items"];
        $orderId = $requestData["order_id"];
        $userId = Auth::guard('api')->user()->user_id;
        $order = Order::getRecordById($orderId, $userId);
        if (empty($order)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_ORDER_NOT_FOUND"));
        }

        $type = Reason::CANCELLATION;
        if ($order->order_shipping_status == Order::SHIPPING_STATUS_DELIVERED) {
            $type = Reason::RETURN;
        }
      
        $gatewayType = $order->paymentslug;
        $transactionId = "";
        if ($order->transaction) {
            $transactionId = $order->transaction->txn_gateway_transaction_id;
            if (!in_array($order->transaction->txn_gateway_type, ['cash', 'card'])) {
                $gatewayType = $order->transaction->txn_gateway_type;
            }
        }

        $bankInfo = !empty($requestData["bank"]) ? $requestData["bank"] : "";
        if (!empty($bankInfo) && count($bankInfo) > 0 && array_filter($bankInfo)) {
            OrderReturnBankInfo::submitBankInformation($bankInfo, $orderId, $userId);
        }
        $approvalReq = getConfigValueByName('APPROVAL_ON_CANCELLATION');
        $refundAmount = [];
        $reqIds = [];
        $x = 1;

        $shipping = $order->order_shipping_value;
        $maxQty = OrderProduct::where('op_order_id', $orderId)->sum('op_qty');
        $selectedQty = array_reduce($items, function ($accumulator, $item) {
            return $accumulator + $item["selected_qty"];
        });
        if (
            ($maxQty != $selectedQty)
            || (in_array($order->order_shipping_status, [1, 2]) !== true
            && getConfigValueByName('RETURN_SHIPPING_ENABLE') === 0)
        ) {
            $shipping = 0;
        }
        
        foreach ($items as $item) {
            if ($item['selected_qty'] == 0) {
                continue;
            }

            $refundStatus = OrderReturnRequest::RETURN_REQUEST_STATUS_PENDING;
            $product = $order->products->where('op_id', $item['op_id'])->first();
           
            if ($type == OrderReturnRequest::CANCELLATION && $transactionId != "" && $approvalReq == 0) {
                $refundStatus =  OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED;
            }
            if ($type == OrderReturnRequest::CANCELLATION && $gatewayType == "cod" && $approvalReq == 0) {
                $refundStatus =  OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED;
            }

            if ($item['reason'] != "other") {
                $item['reason'] = Reason::where('reason_id', $item['reason'])->first()->reason_title;
                $cancellationReason = $item['reason'];
            }
            $requestExist = OrderReturnRequest::where(['orrequest_op_id' => $item['op_id'], 'orrequest_order_id' => $orderId])->count();
            if ($requestExist != 0) {
                return apiresponse(config('constants.ERROR'), appLang("NOTI_RETURN_ALREADY_REQUESTED_FOR_THIS_PRODUCT"));
            }
            $orrequestId = OrderReturnRequest::create([
                'orrequest_user_id' => $userId,
                'orrequest_op_id' => $item['op_id'],
                'orrequest_order_id' => $orderId,
                'orrequest_qty' => $item['selected_qty'],
                'orrequest_type' => $type,
                'orrequest_date' => Carbon::now(),
                'orrequest_order_status' => isOrderPaid($order->payment_status) ? 1 : 0,
                'orrequest_status' => $refundStatus,
                'orrequest_txn_gateway_transaction_id' =>  $transactionId,
                'orrequest_reason' => $item['reason'],
                'orrequest_comment' =>  $requestData["comment"],
            ])->orrequest_id;

            if ($refundStatus ==  OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED) {
                Product::updateReturnInventory($product, $item['selected_qty'], $orrequestId, $userId);
            }
            $shipping = 0;
            if ($x == count($order->products) && $shipping != 0) {
                $shipping = $shipping;
            }
            $reqIds[] = $orrequestId;
            $additionInfoMessage =  '';
            if ($requestData["comment"]) {
                $additionInfoMessage =  '<p> <span class="text-bold">Addition Information:</span> ' . $requestData["comment"] . '</p>';
            }
            $refundAmount = $this->calculateYKRefundableAmount($shipping, $order, $product, $item, $orrequestId, $refundStatus, $cancellationReason, $additionInfoMessage, $type);
            

            $fromType = OrderMessage::MSG_FROM_USER;
            if ($type == OrderReturnRequest::CANCELLATION && $transactionId != "" && $refundAmount != 0  && $approvalReq == 0) {
                $payment = new PaymentGatewayHelper($gatewayType);
                $orderReturnStatus = $payment->orderRefund($transactionId, $refundAmount, $order->order_currency_code);
                if ($orderReturnStatus['status'] != 1) {
                    return apiresponse(config('constants.ERROR'), $orderReturnStatus['message']);
                }
               

                Transaction::orderTransaction($orderId, $refundAmount, $userId, $gatewayType, $orderReturnStatus, 'order Refund', Transaction::CREDIT_TYPE, $orrequestId);
                $transMessage = '';
                if ($orderReturnStatus['data']['id']) {
                    $transMessage = '<p><span class="text-bold">' . appLang('LBL_TRANSACTION_DETAILS') . ': </span>' . $orderReturnStatus['data']['id'] . '</p>';
                }
                $commentId =  OrderMessage::createComment($orrequestId, 'Refund processed', OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE, 0, $userId, $fromType);
                OrderMessage::createComment($orrequestId, '<p><span class="text-bold">' . appLang('LBL_REASON_ONLY') . ':</span>' . $item['reason'] . '</p>' . $additionInfoMessage . '' . $transMessage, OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE, $commentId, $userId, $fromType);

                $orderCommentId = OrderMessage::createComment($orderId, appLang('LBL_REFUND_REQUEST_PROCESSED_FOR_REQUEST_ID') . ' #' . $orrequestId, OrderMessage::MSG_ORDER_TIMELLINE_TYPE, 0, $userId, $fromType);


                $orderMessage = "Canceled " . $product->op_product_name . " ". appLang('LBL_WITH_REQUEST_ID') . " #" . $orrequestId . '<p><span class="text-bold">' .  appLang('LBL_REASON_ONLY') . ': </span> ' . $item['reason'] . '</p>' . $additionInfoMessage . '' . $transMessage;
                OrderMessage::createComment($orderId, $orderMessage, OrderMessage::MSG_ORDER_TIMELLINE_TYPE, $orderCommentId, $userId, $fromType);
            } else {
                $commentId = OrderMessage::createComment($orrequestId, OrderReturnRequest::TYPE[$type] . ' '. appLang('LBL_REQUEST_INITIATED'), OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE, 0, $userId, $fromType);

                OrderMessage::createComment($orrequestId, '<p><span class="text-bold">' . appLang('LBL_REASON_ONLY') .': </span>' . $item['reason'] . ' </p>' . $additionInfoMessage, OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE, $commentId, $userId, $fromType);
                $orderMessage = $product->op_product_name . " " . appLang('LBL_WITH_REQUEST_ID') . " #" . $orrequestId . '<p><span class="text-bold"> ' . appLang('LBL_REASON_ONLY') .': </span>' . $item['reason'] . '</p>' . $additionInfoMessage;

                $orderCommentId = OrderMessage::createComment($orderId, OrderReturnRequest::TYPE[$type] . ' ' . appLang('LBL_REQUESTED_FOR_REQUEST_ID') . ' #' . $orrequestId, OrderMessage::MSG_ORDER_TIMELLINE_TYPE, 0, $userId, $fromType);

                OrderMessage::createComment($orderId, $orderMessage, OrderMessage::MSG_ORDER_TIMELLINE_TYPE, $orderCommentId, $userId, $fromType);
            }
            $x++;
        }
        $orderStatus  = Order::ORDER_STATUS_PARTIAL_RETURNED;
        $oldRequests = OrderReturnRequest::where('orrequest_order_id', $orderId)->pluck('orrequest_qty', 'orrequest_op_id')->toArray();
        if ($order->products->count() == count($oldRequests)) {
            $orderedProducts = $order->products->pluck('op_qty', 'op_id')->toArray();
            if ($orderedProducts == $oldRequests) {
                $orderStatus  = ($approvalReq == 0) ? Order::ORDER_STATUS_COMPLETED : Order::ORDER_STATUS_RETURNED;
            };
        }

        Order::where('order_id', $orderId)->update(['order_status' => $orderStatus]);


        /* trigger event for system notification */
        $data = '';
        if ($type == OrderReturnRequest::RETURN && $orderStatus  == Order::ORDER_STATUS_PARTIAL_RETURNED) {
            $notifyType = Notification::ORDER_PARTIAL_RETURN;
            $data = NotificationTemplate::getBySlug('user_requested_partial_return');
        } elseif ($type == OrderReturnRequest::RETURN && $orderStatus  == Order::ORDER_STATUS_COMPLETED) {
            $notifyType = Notification::ORDER_RETURN;
            $data = NotificationTemplate::getBySlug('user_requested_return');
        } elseif ($type == OrderReturnRequest::CANCELLATION && $orderStatus  == Order::ORDER_STATUS_PARTIAL_RETURNED) {
            $notifyType = Notification::ORDER_PARTIAL_CANCELLATION;
            $data = NotificationTemplate::getBySlug('user_requested_partial_cancellation');
        } elseif ($type == OrderReturnRequest::CANCELLATION && $orderStatus  == Order::ORDER_STATUS_COMPLETED) {
            $notifyType = Notification::ORDER_CANCELLATION;
            $data = NotificationTemplate::getBySlug('user_requested_cancellation');
        }
        if ($data) {
            $message = str_replace('{orderNumber}', $orderId, $data->ntpl_body);
            $message = str_replace('{orderAmount}', formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code), $message);
            $message = str_replace('{userName}', Str::title(Auth::guard('api')->user()->user_fname . ' ' . Auth::guard('api')->user()->user_lname), $message);
            event(new SystemNotification([
                'type' => $notifyType,
                'record_id' => $orderId,
                'from_id' => $userId,
                'message' => $message
            ]));
        }

        $response = [];
        $response["request_ids"] = $reqIds;
        $response["order_id"] = $orderId;
        $response["return_summary"] = $this->returnSummary($reqIds, $orderId);

        return apiresponse(config('constants.SUCCESS'), appLang('NOTI_RETURN_REQUESTED_SUBMITTED'), $response);
    }

    public function getReturnSummary(Request $request)
    {
        $reqIds = $request->input('request_ids');
        $orderId = $request->input('order_id');
        $response = [];
        $response["request_ids"] = $reqIds;
        $response["return_summary"] = $this->returnSummary($reqIds, $orderId);

        return apiresponse(config('constants.SUCCESS'), '', $response);
    }

    private function returnSummary($reqIds, $orderId)
    {
        $records = OrderProduct::getReturnSummary($reqIds, $orderId);

        $reasonTypes = Reason::TYPE;
        $phonecode = '';
        if (!empty($countryId = getConfigValueByName('BUSINESS_PHONE_COUNTRY_CODE'))) {
            $phonecode = Country::getRecordById($countryId)->country_phonecode;
        }
        $comment = '';
        if (!empty($records)) {
            $comment = $records->first()->orrequest_comment;
        }
        $stateName = $countryName = "";
        $config = getConfigValues(['BUSINESS_STATE', 'BUSINESS_COUNTRY']);
        if (!empty($config["BUSINESS_STATE"])) {
            $state = State::getRecordById($config["BUSINESS_STATE"]);
            $stateName = $state->state_name;
        }
        if (!empty($config["BUSINESS_COUNTRY"])) {
            $country = Country::getRecordById($config["BUSINESS_COUNTRY"]);
            $countryName = $country->country_name;
        }

        return ['records' => $records, 'stateName' => $stateName, 'countryName' => $countryName, 'reasonTypes' => $reasonTypes, 'orderId' => $orderId, 'comment' => $comment, 'phonecode' => $phonecode];
    }

    private function calculateYKRefundableAmount($shippingAmount, $order, $product, $item, $requestId, $refundStatus, $cancellationReason = '', $additionInfoMessage = '', $type)
    {
        $tax = 0;
        if (!empty($product) && !empty($product->tax)) {
            $tax = $product->tax->opc_value *  $item['selected_qty'];
        }
        $discountAmount = 0;
        if (!empty($order->order_discount_amount) && !empty($product->opainfo_discount_amount)) {
            $discountAmount =  $product->opainfo_discount_amount  * $item['selected_qty'];
        }
        $refundableAmount = (($product->op_product_price * $item['selected_qty'] + $tax + $shippingAmount) - $discountAmount);
        $refundAmount = [
            'oramount_orrequest_id' => $requestId,
            'oramount_op_id' => $item['op_id'],
            'oramount_tax' => $tax,
            'oramount_shipping' => $shippingAmount,
            'oramount_discount' => $discountAmount,
            'oramount_giftwrap_price' => 0,

            'oramount_payment_status' => ($refundStatus == OrderReturnRequest::RETURN_REQUEST_STATUS_PENDING) ? OrderReturnAmount::PENDING : OrderReturnAmount::PAID,
        ];
        $giftPrice = 0;
        if (!empty($order->order_gift_amount) && !empty($product->opainfo_gift_amount)) {
            $giftPrice =  $product->opainfo_gift_amount * $item['selected_qty'];

            if ($order->order_shipping_status == Order::SHIPPING_STATUS_IN_PROGRESS) {
                $refundableAmount = $refundableAmount + $giftPrice;
                $refundAmount['oramount_giftwrap_price']  = $giftPrice;
            } else {
                $refundAmount['oramount_giftwrap_price']  = '-' . $giftPrice;
            }
        }
        $refundableGift = $refundableAmount;
        $rewardAmount = 0;
        $rewardPrice = 0;
        if ($order->order_reward_amount != 0) {
            $rewardPrice =  ((($order->order_net_amount * $product->opainfo_reward_rate) / 100) / $product->op_qty) * $item['selected_qty'];
            $refundableAmount = $rewardPrice;
            if ($order->order_shipping_status != 1 && $giftPrice != 0) {
                $rewardAmount =  $refundableGift  + $giftPrice;
                $refundableAmount =  $refundableAmount - $giftPrice;
            } else {
                $rewardAmount = ($refundableAmount - $rewardPrice > 0) ? $refundableAmount - $rewardPrice : 0;
            }
        }

        $refundAmount['oramount_reward_price'] = round($rewardAmount, 2);
        OrderReturnAmount::create($refundAmount);
        /** Start Send Cancellation Request Email to Buyer */
        $approvalOnCancellation = getConfigValueByName('APPROVAL_ON_CANCELLATION');
        $notificationData = [];
        $sendSms = false;
        if ($type == OrderReturnRequest::CANCELLATION) {
            if ($approvalOnCancellation == 1) {
                $requestTemplate = 'cancellation_request_buyer';
            } else {
                $requestTemplate = 'return_approved';
            }
        } else {
            if ($approvalOnCancellation == 1) {
                $requestTemplate = 'return_request_buyer';
            } else {
                $requestTemplate = 'return_approved';
            }
        }
        
        $data = EmailHelper::getEmailData($requestTemplate);
        $priority = $data['body']->etpl_priority;
        $replacementData['tax'] = $tax;
        $replacementData['refundableAmount'] = $refundableAmount;
        $replacementData['cancellationReason'] = $cancellationReason;
        $replacementData['additionInfoMessage'] = $additionInfoMessage;
        $replacementData['shippingAmount']  = $shippingAmount;
        $replacementData['subTotal'] = $product->op_product_price * $item['selected_qty'];
        $data['subject'] = str_replace("{orderNumber}", $order->order_id, $data['body']->etpl_subject);
        if ($type == OrderReturnRequest::CANCELLATION) {
            $replacementData['requestType'] = "Cancellation";
        } else {
            $replacementData['requestType'] = "Return";
        }
        $data['subject'] = str_replace("{type}", $replacementData['requestType'], $data['subject']);
        $data['subject'] = str_replace("{returnId}", $order->order_id.'-'. $requestId, $data['subject']);
        $data['body'] = OrderReturnRequest::replacementCancellation($data['body']->etpl_body, $order, $replacementData, $item, '', $requestId, Auth::guard('api')->user());
        $billAddress = $order->address->where('oaddr_type', OrderAddress::BILLING_ADDRESS_TYPE)->first();
        $data['to'] = $billAddress->oaddr_email;
        $notificationData['email'] = $data;
        
        if (!empty($billAddress->oaddr_phone) && !empty($billAddress->oaddr_phone_country_id)) {
            $smsdata = SmsTemplate::getSMSTemplate($requestTemplate);
            $country = Country::select('country_phonecode')->where('country_id', $billAddress->oaddr_phone_country_id)->first();

            $smsdata['body'] = str_replace('{name}', $billAddress->oaddr_name, $smsdata['body']->stpl_body);
            $smsdata['body'] = str_replace('{requestId}', $order->order_id.'-'. $requestId, $smsdata['body']);
            $notificationData['sms'] = [
                'message' => $smsdata['body'],
                'recipients' => array('+' . $country->country_phonecode . $billAddress->oaddr_phone)
            ];
            $sendSms = true;
        }

        dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);
        /** End Send Cancellation Request Email to Buyer */

        /** Start Admin Send Email */
        $admins = Admin::getAdminsByModule(AdminRole::ORDERS);
        $notificationData = [];
        $sendSms = false;
        if (!empty($admins)) {
            $template = 'return_request_admin';
            if ($type == OrderReturnRequest::CANCELLATION) {
                $template =  'cancellation_request_admin';
            }
            foreach ($admins as $key => $admin) {
                $adminEmaildata = EmailHelper::getEmailData($template);
                $priority = $adminEmaildata['body']->etpl_priority;
                $adminEmaildata['subject'] = str_replace('{cancellationRequestId}', $order->order_id. '-'. $requestId, $adminEmaildata['body']->etpl_subject);
                $adminEmaildata['subject'] = str_replace('{orderNumber}', $order->order_id, $adminEmaildata['subject']);
                $adminEmaildata['subject'] = str_replace('{returnId}', $order->order_id. '-'. $requestId, $adminEmaildata['subject']);
                $adminEmaildata['subject'] = str_replace('{buyerName}', $billAddress->oaddr_name, $adminEmaildata['subject']);

                $adminEmaildata['body'] = OrderReturnRequest::replacementCancellation($adminEmaildata['body']->etpl_body, $order, $replacementData, $item, $admin['admin_name'], $requestId, Auth::guard('api')->user());
                $adminEmaildata['to'] = $admin['admin_email'];
                $notificationData['email'] = $adminEmaildata;
                if (!empty($admin['admin_phone_country_id']) && !empty($admin['admin_phone'])) {
                    $smsdata = SmsTemplate::getSMSTemplate($template);
                    $country = Country::select('country_phonecode')->where('country_id', $admin['admin_phone_country_id'])->first();
                    $smsdata['body'] = str_replace('{requestId}', $order->order_id. '-'. $requestId, $smsdata['body']->stpl_body);
                    $smsdata['body'] = str_replace('{buyerName}', $billAddress->oaddr_name, $smsdata['body']);
                    $smsdata['body'] = str_replace('{adminName}', $admin['admin_name'], $smsdata['body']);
                    $notificationData['sms'] = [
                        'message' => $smsdata['body'],
                        'recipients' => array('+' . $country->country_phonecode . $admin['admin_phone'])
                    ];
                    $sendSms = true;
                }
                dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);
            }
        }
        /** End Admin Send Email */



        return priceFormat($refundableAmount, false);
    }

    private function commentNotification($recordId, $comment, $type = "")
    {
        /* trigger event for system notification*/
        $data = NotificationTemplate::getBySlug('new_comment_on_order');
        $notifyType = Notification::ORDER_COMMENT;
        $orderId = $recordId;
        if ($type == "return") {
            $returnData = OrderReturnRequest::select('orrequest_order_id', 'orrequest_type')->where('orrequest_id', $recordId)->first();
            $notifyType = Notification::ORDER_RETURN_COMMENT;
            $data = NotificationTemplate::getBySlug('new_comment_on_order_return_request');
            if ($returnData->orrequest_type == OrderReturnRequest::CANCELLATION) {
                $notifyType = Notification::ORDER_CANCELLATION_COMMENT;
                $data = NotificationTemplate::getBySlug('new_comment_on_order_cancellation_request');
            }
            $orderId = $returnData->orrequest_order_id;
        }
        $message = str_replace('{orderNumber}', $orderId, $data->ntpl_body);
        $message = str_replace('{userName}', Str::title(Auth::guard('api')->user()->user_fname . ' ' . Auth::guard('api')->user()->user_lname), $message);

        event(new SystemNotification([
            'type' => $notifyType,
            'record_id' => $orderId,
            'record_subid' => !empty($type) ? $recordId : 0,
            'from_id' => Auth::guard('api')->user()->user_id,
            'message' => $message
        ]));
        /*send email code*/
        $admins = Admin::getAdminsByModule(AdminRole::ORDERS);
        if (!empty($admins)) {
            $order = Order::select('order_user_id')->where('order_id', $orderId)->first();
            $user = User::select('user_fname', 'user_lname')->where('user_id', $order->order_user_id)->first();
            foreach ($admins as $key => $admin) {
                $notificationData = [];
                $sendSms = false;

                $data = EmailHelper::getEmailData('comment_by_buyer_on_order');
                $priority = $data['body']->etpl_priority;
                $data['subject'] = $this->replaceCommentTags($data['body']->etpl_subject, $comment, $admin['admin_name'], $orderId);
                $data['body'] = $this->replaceCommentTags($data['body']->etpl_body, $comment, $admin['admin_name'], $orderId);
                $data['to'] = $admin['admin_email'];
                $notificationData['email'] = $data;

                $country = Country::select('country_phonecode')->where('country_id', $admin['admin_phone_country_id'])->first();
                if (!empty($country->country_phonecode) && !empty($admin['admin_phone'])) {
                    $data = SmsTemplate::getSMSTemplate('comment_by_buyer_on_order');
                    $priority = $data['body']->stpl_priority;
                    $data['body'] = str_replace('{name}', $admin['admin_name'], $data['body']->stpl_body);
                    $data['body'] = str_replace('{orderNumber}', $orderId, $data['body']);
                    $notificationData['sms'] = [
                        'message' => $data['body'],
                        'recipients' => array('+' . $country->country_phonecode . $admin['admin_phone'])
                    ];
                    $sendSms = true;
                }

                dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);
            }
        }
    }
    private function replaceCommentTags($content, $comment, $adminName, $orderNumber)
    {
        $content = str_replace('{buyerName}', Auth::guard('api')->user()->user_fname . ' ' . Auth::guard('api')->user()->user_lname, $content);
        $content = str_replace('{adminName}', $adminName, $content);
        $content = str_replace('{commentText}', $comment, $content);
        $content = str_replace('{link}', url('') . '/admin#/order/' . $orderNumber, $content);
        $content = str_replace('{orderNumber}', $orderNumber, $content);
        $content = str_replace('{websiteName}', (getConfigValueByName('BUSINESS_NAME') ?? ''), $content);
        return $content;
    }

    private function orderPayment($request, $orderId, $amount, $currency)
    {
        $payment = new PaymentGatewayHelper($request['payment_gateway']);
        if (!empty($request['card_id']) && !in_array($request['payment_gateway'], ["PaypalExpress", "CashFree"])) {
            return $payment->orderPaymentByCartId($request['card_id'], $orderId);
        }
        if (!empty($request['card_token']) && !in_array($request['payment_gateway'], ["PaypalExpress", "CashFree"])) {
            return $payment->orderPaymentByCardToken($request['card_token'], $orderId,$request['card_is_save']);
        }
        $records = ['payment_gateway' => $request['payment_gateway'], 'order_id' => $orderId, 'amount' => (string)$amount, 'method' => 'redirect', 'currency' => $currency];
        switch ($request['payment_gateway']) {
            case 'CashFree':
                $record = Package::getRecordBySlug('CashFree');
                $records["app_id"] = $record['CASHFREE_APPID'];
                $records["secret_key"] = $record['CASHFREE_SECRET_KEY'];
                break;
            case 'Paypal':
                $record = Package::getRecordBySlug('Paypal');
                $records["client_id"] = $record['PAYPAL_CLIENTID'];
                $records["username"] = $record['PAYPAL_USERNAME'];
                $records["secret"] = $record['PAYPAL_SECRET'];
                break;
        }
        return $records;
    }

    private function saveRewardPoints($netPrice, $price, $userId, $orderId)
    {
        if (UserRewardPoint::where('urp_order_id', $orderId)->count() == 0) {
            $config = getConfigValues(['REWARD_POINTS_ENABLE', 'REWARD_POINTS_PURCHASE_POINTS_MINIMUM_ORDER_TOTAL', 'REWARD_POINTS_PURCHASE_POINTS_AMOUNT', 'REWARD_POINTS_ROUNDING_MODE', 'REWARD_POINTS_PURCHASE_POINTS_EARNINGS', 'REWARD_POINTS_PURCHASE_POINTS_VALIDITY']);

            if ($config['REWARD_POINTS_ENABLE'] == 1 && $config['REWARD_POINTS_PURCHASE_POINTS_MINIMUM_ORDER_TOTAL'] <= $price) {
                $totalRewardPoint = $netPrice /  $config['REWARD_POINTS_PURCHASE_POINTS_AMOUNT'];

                if (($config['REWARD_POINTS_ROUNDING_MODE']) == 1) {
                    if (strpos($totalRewardPoint, ".") !== false) {
                        $totalRewardPoint =  ceil($totalRewardPoint);
                    }
                } else {
                    $totalRewardPoint =  floor($totalRewardPoint);
                }
                $totalRewardPoint =   $totalRewardPoint *  $config['REWARD_POINTS_PURCHASE_POINTS_EARNINGS'];

                $insertId = UserRewardPoint::create([
                    'urp_user_id' => $userId,
                    'urp_referral_user_id' => 0,
                    'urp_comments' => appLang('LBL_EARN_REWARD_POINT') . ' #' . $orderId,
                    'urp_type' => UserRewardPoint::ORDER_EARNED_POINT,
                    'urp_points' => $totalRewardPoint,
                    'urp_order_id' => $orderId,
                    'urp_date_added' => Carbon::now(),
                    'urp_date_expiry' => Carbon::now()->addDays($config['REWARD_POINTS_PURCHASE_POINTS_VALIDITY']),
                ])->urp_id;
                UserRewardPointBreakup::create([
                    'urpbreakup_urp_id' => $insertId,
                    'urpbreakup_referral_user_id' => 0,
                    'urpbreakup_used_order_id' => 0,
                    'urpbreakup_points' => $totalRewardPoint,
                    'urpbreakup_used' => 0,
                    'urp_date_added' => Carbon::now(),
                    'urpbreakup_expiry' => Carbon::now()->addDays($config['REWARD_POINTS_PURCHASE_POINTS_VALIDITY']),
                ]);

                $user = User::getUserById($userId);
                $data = EmailHelper::getEmailData('rewards_earned_on_order');
                $priority = $data['body']->etpl_priority;
                $data['subject'] = str_replace('{rewardPoints}', $totalRewardPoint, $data['body']->etpl_subject);
                $data['subject'] = str_replace('{orderNumber}', $orderId, $data['subject']);
                $data['body'] = str_replace('{name}', $user->user_name, $data['body']->etpl_body);
                $data['body'] = str_replace('{rewardPoints}', $totalRewardPoint, $data['body']);
                $data['body'] = str_replace('{orderNumber}', $orderId, $data['body']);
                $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
                $data['to'] = $user->user_email;
                sendPushNotification($userId, 'rewards_earned_on_order', ['reward_points' => $totalRewardPoint,'order_id' => $orderId]);


                dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);
            }
        }
    }

    private function updateProductInventory($orderId)
    {
        $orderProducts = OrderProduct::select("op_qty", "op_product_id", "op_pov_code")->where('op_order_id', $orderId)->get();
        foreach ($orderProducts as $orderProduct) {
            $qty = $orderProduct->op_qty;

            if (!empty($orderProduct->op_pov_code)) {
                $varientCode = $orderProduct->op_pov_code;
                if (substr($varientCode, -1) != "|") {
                    $varientCode = $varientCode . '|';
                }
                $varient = ProductOptionVarient::select('pov_quantity')->where('pov_code', $varientCode)->first();
                if ($varient->pov_quantity < $qty) {
                    $qty = 0;
                }
                $varient->where('pov_code', $varientCode)->decrement('pov_quantity', $qty);
            } else {
                $product = Product::select('prod_stock')->where('prod_id', $orderProduct->op_product_id)->first();
                if ($product->prod_stock < $qty) {
                    $qty = 0;
                }
                $product->where('prod_id', $orderProduct->op_product_id)->decrement('prod_stock', $qty);
            }

            Product::where('prod_id', $orderProduct->op_product_id)->update(['prod_sold_count' => $qty]);
            Product::checkIfProductLowStock($orderProduct->op_product_id);
        }
    }

    private function orderUpdate($orderId, $orderStatus, $chargePrice = 0)
    {
        Order::where('order_id', $orderId)->update([
            'order_payment_status' => $orderStatus,
            'order_amount_charged' => $chargePrice,
        ]);
    }

    private function orderTransaction($orderId, $price, $userId, $gateWayType, $orderStatus, $comment = 'Order Placed', $txnType = "", $orrequestId = 0)
    {
        if ($txnType == "") {
            $txnType = Transaction::DEBIT_TYPE;
        }
        Transaction::create([
            'txn_user_id' => $userId,
            'txn_date' => Carbon::now(),
            'txn_amount' => $price,
            'txn_comments' => $comment . '  #' . $orderId,
            'txn_status' => Transaction::STATUS_COMPLETED,
            'txn_order_id' => $orderId,
            'txn_gateway_type' => $gateWayType,
            'txn_gateway_transaction_id' => $orderStatus['data']['id'],
            'txn_gateway_response' => json_encode($orderStatus),
            'txn_orrequest_id' => $orrequestId,
            'txn_type' => $txnType
        ]);
    }

    private function paymentFailedSendPaymentLink($order)
    {
        $paymentUrl = $this->getPaymentLink($order->order_id);
        if ($paymentUrl) {
            $buyerName = $order->user_fname . ' ' . $order->user_lname;
            $shipAddress = $order->address->where('oaddr_type', OrderAddress::SHIPPING_ADDRESS_TYPE)->first();
            $billAddress = $order->address->where('oaddr_type', OrderAddress::BILLING_ADDRESS_TYPE)->first();
            $pickAddress = $order->address->where('oaddr_type', OrderAddress::SHIPPING_PICKUP_TYPE)->first();
            $products = $order->products;

            $notificationData = [];
            $sendSms = false;

            $data = EmailHelper::getEmailData('payment_failed_new_link_for_buyer');
            $priority = $data['body']->etpl_priority;
            $data['subject'] = $this->replacementTags($data['body']->etpl_subject, $order, $products, $shipAddress, $billAddress, $pickAddress);
            $data['body'] = $this->replacementTags($data['body']->etpl_body, $order, $products, $shipAddress, $billAddress, $pickAddress);
            $data['body'] = str_replace('{paymentLink}', $paymentUrl, $data['body']);
            $data['to'] =  ($billAddress->oaddr_email) ?? '';
            $notificationData['email'] = $data;

            $country = Country::select('country_phonecode')->where('country_id', $billAddress->oaddr_phone_country_id)->first();
            if (!empty($country->country_phonecode) && !empty($billAddress->oaddr_phone_country_id)) {
                $data = SmsTemplate::getSMSTemplate('payment_failed_new_link_for_buyer');
                $priority = $data['body']->stpl_priority;
                $data['body'] = str_replace('{name}', $buyerName, $data['body']->stpl_body);
                $data['body'] = str_replace('{number}', $order->order_id, $data['body']);
                $notificationData['sms'] = [
                    'message' => $data['body'],
                    'recipients' => array('+' . $country->country_phonecode . $billAddress->oaddr_phone)
                ];
                $sendSms = true;
            }

            dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);
        }
    }

    private function getPaymentLink($orderId)
    {
        $paymentGateways = Package::getActivePaymentGateways('paymentGateways')->where('pkg_card_type', 1)->first();
        if (empty($paymentGateways)) {
            return "";
        }
        $encrypted =  Crypt::encryptString(config('app.salt') . '' . $orderId . '-' . $paymentGateways->pkg_slug);
        return url('/payment/' . $encrypted);
    }

    private function newOrderEmailToAdmin($order)
    {
        $admins = Admin::getAdminsByModule(AdminRole::ORDERS);
        if (!empty($admins)) {
            $buyerName = $order->user_fname . ' ' . $order->user_lname;
            $products = $order->products;
            $shipAddress = $order->address->where('oaddr_type', OrderAddress::SHIPPING_ADDRESS_TYPE)->first();
            $billAddress = $order->address->where('oaddr_type', OrderAddress::BILLING_ADDRESS_TYPE)->first();
            $pickAddress = $order->address->where('oaddr_type', OrderAddress::SHIPPING_PICKUP_TYPE)->first();
            foreach ($admins as $key => $admin) {
                $notificationData = [];
                $sendSms = false;

                $data = EmailHelper::getEmailData('new_order_notification_to_admin');
                $priority = $data['body']->etpl_priority;
                $data['subject'] = $this->replacementTags($data['body']->etpl_subject, $order, $products, $shipAddress, $billAddress, $pickAddress, $admin['admin_name'], $buyerName);
                $data['body'] = $this->replacementTags($data['body']->etpl_body, $order, $products, $shipAddress, $billAddress, $pickAddress, $admin['admin_name'], $buyerName);
                $data['to'] =  $admin['admin_email'];
                $notificationData['email'] = $data;

                $country = Country::select('country_phonecode')->where('country_id', $admin['admin_phone_country_id'])->first();
                if (!empty($country->country_phonecode) && !empty($admin['admin_phone'])) {
                    $data = SmsTemplate::getSMSTemplate('new_order_notification_to_admin');
                    $priority = $data['body']->stpl_priority;
                    $data['body'] = str_replace('{name}', $admin['admin_name'], $data['body']->stpl_body);
                    $data['body'] = str_replace('{buyerName}', $buyerName, $data['body']);
                    $data['body'] = str_replace('{number}', $order->order_id, $data['body']);
                    $data['body'] = str_replace('{amount}', formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code), $data['body']);
                    $notificationData['sms'] = [
                        'message' => $data['body'],
                        'recipients' => array('+' . $country->country_phonecode . $admin['admin_phone'])
                    ];
                    $sendSms = true;
                }

                dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);
            }
        }
    }

    private function replacementTags($type, $order, $products, $shipAddress, $billAddress, $pickAddress, $adminName = '', $buyerName = '')
    {
        $orderProducts = view('emails.order-products', compact('products'))->render();
        
        $billingAddress = '';
        if (!empty($billAddress)) {
            $country = Country::getCountries(['country_phonecode'], ['country_id' => $billAddress->oaddr_phone_country_id])->first();
            $phoneCode = !empty($country->country_phonecode) ? '+' . $country->country_phonecode . ' ' : '';

            $billingAddress = '<strong>' . $billAddress->oaddr_name . '</strong> <br>' . $billAddress->oaddr_address1 . ', ' . $billAddress->oaddr_address2 . '<br>' . $billAddress->oaddr_city . ',' . $billAddress->oaddr_state . '<br>' .  $billAddress->oaddr_country . '<br>' . $phoneCode . $billAddress->oaddr_phone;
        }
        $shippingAddress = '';
        if (!empty($shipAddress)) {
            $country = Country::getCountries(['country_phonecode'], ['country_id' => $shipAddress->oaddr_phone_country_id])->first();
            $phoneCode = !empty($country->country_phonecode) ? '+' . $country->country_phonecode . ' ' : '';

            $shippingAddress = '<strong>' . $shipAddress->oaddr_name . '</strong> <br>' . $shipAddress->oaddr_address1 . ', ' . $shipAddress->oaddr_address2 . '<br>' . $shipAddress->oaddr_city . ',' . $shipAddress->oaddr_state . '<br>' .  $shipAddress->oaddr_country . '<br>' . $phoneCode . $shipAddress->oaddr_phone;
        }
        $pickupAddress = '';
        if (!empty($pickAddress)) {
            $country = Country::getCountries(['country_phonecode'], ['country_id' => $pickAddress->oaddr_phone_country_id])->first();
            $phoneCode = !empty($country->country_phonecode) ? '+' . $country->country_phonecode . ' ' : '';

            $pickupAddress = '<strong>' . $pickAddress->oaddr_name . '</strong> <br>' . $pickAddress->oaddr_address1 . ', ' . $pickAddress->oaddr_address2 . '<br>' . $pickAddress->oaddr_city . ',' . $pickAddress->oaddr_state . '<br>' .  $pickAddress->oaddr_country . '<br>' . $phoneCode . $pickAddress->oaddr_phone;
        }

        $orderType = '';
        if ($order->order_shipping_type == Order::ORDER_SHIPPING) {
            $orderType = 'shipping';
        } elseif ($order->order_shipping_type == Order::ORDER_PICKUP) {
            $orderType = 'pickup';
        }
        
        if (isset($order->products) && isset($order->digital_products) && count($order->products) == $order->digital_products) {
            $orderType = 'digital';
        }

        $shippingPrice = 0;
        $taxPrice = 0;
        $discountPrice = 0;
        $rewardPrice = 0;
        $giftPrice = 0;

        if (!empty($order->order_shipping_value)) {
            $shippingPrice = $order->order_shipping_value;
        }

        if (!empty($order->order_tax_charged)) {
            $taxPrice = $order->order_tax_charged;
        }

        if (!empty($order->order_discount_amount)) {
            $discountPrice = $order->order_discount_amount;
        }
        if (!empty($order->order_reward_amount)) {
            $rewardPrice = $order->order_reward_amount;
        }
        if (!empty($order->order_gift_amount)) {
            $giftPrice =  $order->order_gift_amount;
        }

        $subTotal = $order->order_net_amount - $taxPrice - $shippingPrice - $giftPrice + $discountPrice + $rewardPrice;

        if (!empty($pickAddress)) {
            $shippingData = json_decode($order->order_shipping_methods, true);

            if (isset($shippingData['pick_ups'])) {
                $type = str_replace('{pickupDetails}', $shippingData['pick_ups']['pickup_date'] . ' ' . $shippingData['pick_ups']['pickup_time'], $type);
            }
            $type = str_replace('{pickupAddress}', $pickupAddress, $type);
        }
        $type = str_replace('{number}', $order->order_id, $type);
        $type = str_replace('{orderNumber}', $order->order_id, $type);
        $type = str_replace('{websiteName}', env('APP_NAME'), $type);
        $type = str_replace('{amount}', $order->currency_symbol . '' .  $order->order_net_amount, $type);
        $type = str_replace('{date}', getConvertedDateTime($order->order_date_added), $type);
        $type = str_replace('{paymentMethod}', $order->order_payment_method, $type);
        $type = str_replace('{orderProductListing}', $orderProducts, $type);
        $type = str_replace('{subtotal}', $order->currency_symbol . '' . $subTotal, $type);
        $type = str_replace('{tax}', $order->currency_symbol . '' . $taxPrice, $type);
        $type = str_replace('{shipping}', $order->currency_symbol . '' . $shippingPrice, $type);

        $discountCoupon = !empty($order->order_discount_coupon_code) ? '<br>(' . $order->order_discount_coupon_code . ')' : '';
        $type = str_replace('{discount}', '-' . $order->currency_symbol . '' . $discountPrice . $discountCoupon, $type);


        $type = str_replace('{giftWrapPrice}', $order->currency_symbol . '' . $giftPrice, $type);
        $type = str_replace('{rewardPrice}', '-' . $order->currency_symbol . '' . $rewardPrice, $type);
        $type = str_replace('{total}', $order->currency_symbol . '' . $order->order_net_amount, $type);
        $shippingMethods = json_decode($order->order_shipping_methods, true);
        if (isset($shippingMethods['pick_ups']) && !empty($shippingMethods['pick_ups'])) {
            $pickupDateTime = $shippingMethods['pick_ups']['pickup_date'].' '. $shippingMethods['pick_ups']['pickup_time'];
        } else {
            $pickupDateTime = '';
        }
        $orderAddresses = view('emails.order-addresses', compact('shippingAddress', 'billingAddress', 'pickupAddress', 'orderType', 'pickupDateTime'))->render();

        $type = str_replace('{orderAddresses}', $orderAddresses, $type);
        // $type = str_replace('{shippingAddress}', $shippingAddress, $type);
        // $type = str_replace('{billingAddress}', $billingAddress, $type);
        if ($adminName != '') {
            $type = str_replace('{adminName}', $adminName, $type);
        }
        if ($buyerName != '') {
            $type = str_replace('{buyerName}', $buyerName, $type);
        }
        return $type;
    }

    private function orderConfirmationNotification($order)
    {
        $notificationData = [];
        $sendSms = false;
        $orderType = "pickup_order_confirmation";

        if ($order->order_shipping_type == Order::ORDER_PICKUP) {
            $data = EmailHelper::getEmailData('pickup_order_confirmation');
            $smsdata = SmsTemplate::getSMSTemplate('pickup_order_confirmation');
        } else {
            if (count($order->products) == $order->digital_products) {
                $data = EmailHelper::getEmailData('order_confirmation_digital');
            } else {
                $data = EmailHelper::getEmailData('order_confirmation');
            }
            $smsdata = SmsTemplate::getSMSTemplate('order_confirmation');
            $orderType = "order_confirmation";
        }

        $shipAddress = $order->address->where('oaddr_type', OrderAddress::SHIPPING_ADDRESS_TYPE)->first();
        $billAddress = $order->address->where('oaddr_type', OrderAddress::BILLING_ADDRESS_TYPE)->first();
        $pickAddress = $order->address->where('oaddr_type', OrderAddress::SHIPPING_PICKUP_TYPE)->first();
        $products = $order->products;
        $priority = $data['body']->etpl_priority;
        $data['subject'] = $this->replacementTags($data['body']->etpl_subject, $order, $products, $shipAddress, $billAddress, $pickAddress);
        $data['body'] = $this->replacementTags($data['body']->etpl_body, $order, $products, $shipAddress, $billAddress, $pickAddress);
        $data['to'] =  ($billAddress->oaddr_email) ?? '';
        $notificationData['email'] = $data;
        
        if (!empty($billAddress->oaddr_phone) && !empty($billAddress->oaddr_phone_country_id)) {
            $country = Country::select('country_phonecode')->where('country_id', $billAddress->oaddr_phone_country_id)->first();
            $smsdata['body'] = str_replace('{name}', $billAddress->oaddr_name, $smsdata['body']->stpl_body);
            $smsdata['body'] = str_replace('{number}', $order->order_id, $smsdata['body']);
            $smsdata['body'] = str_replace('{websiteName}', env('APP_NAME'), $smsdata['body']);
            $smsdata['body'] = str_replace('{amount}', formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code), $smsdata['body']);
            $notificationData['sms'] = [
                'message' => $smsdata['body'],
                'recipients' => array('+' . $country->country_phonecode . $billAddress->oaddr_phone)
            ];
            $sendSms = true;
        }
        sendPushNotification($order->order_user_id, $orderType, ['order_number' => $order->order_id, 'order_amount' => formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code)]);
        dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);
    }

    private function updateCoupon($historyId, $orderId, $userId)
    {
        CouponHistory::where('couponhistory_id', $historyId)->update(['couponhistory_order_id' => $orderId, 'couponhistory_user_id' => $userId]);
    }

    private function saveProducts($orderId, $cartCollection, $totalRewardPoint)
    {
        $giftPrice = getConfigValueByName('PRODUCT_GIFT_WRAP_PRICE');
        $x = 1;
        $totalRewards = 0;

        foreach ($cartCollection as $cartKey => $cart) {
            $variants = $cart->attributes->getItems();

            $code = 0;
            if ($cart->product_id !==  $cart->id) {
                $code = $cart->id;
            }
            $cartFields = 'prod_type, cat_tax_code, prod_taxcat_id, pov_id, prod_cost, pc_max_download_times,pc_return_age, pc_upload_additional_files, pc_weight, pc_download_validity_in_days, coalesce(`pov_sku`, `pc_sku`) as sku, ' . Product::getPrice();
            $product = Product::productById($cart->product_id, $cartFields, [], $code);
            $expiredDate = 'null';
            if ($product->prod_type == Product::DIGITAL_PRODUCT) {
                $expiredDate = Carbon::now()->addDays($product->pc_download_validity_in_days);
                DigitalFileRecord::saveAttachedFiles($cart->product_id, $orderId, $product->pov_id);
            }
            $isPickup = 0;
            if ($cart->shipType == 'pickup') {
                $isPickup = 1;
            }

            if ($giftmessages = Cart::session($this->cartSession)->getCondition('gift')) {
                $message =  $giftmessages->getAttributes();
                if (isset($variants['gift']) && isset($message['gift'])) {
                    $variants['gift'] = $message['gift'];
                }
            }


            $productPrice = priceFormat($cart->price, false);
            $orderProductid = OrderProduct::create([
                'op_order_id' => $orderId,
                'op_qty' => $cart->quantity,
                'op_product_id' => $cart->product_id,
                'op_pov_code' =>  $code,
                'op_prod_cost' =>  $product->prod_cost,
                'op_is_pickup' => $isPickup,
                'op_pov_id' => $product->pov_id,
                'op_product_name' => $cart->name,
                'op_product_price' => $productPrice,
                'op_product_variants' => json_encode($variants),
                'op_product_type' => $product->prod_type,
                'op_expired_on' => $expiredDate,
                'op_return_age' => $product->pc_return_age,
                'op_product_sku' => $product->sku,
                'op_product_width' => null,
                'op_product_height' => null,
                'op_product_weight' => $product->pc_weight,
            ])->op_id;
            $tax = 0;
            $productTotalTax = 0;
            $productTotalPrice = $productPrice * $cart->quantity;
            $productTotalShipping = 0;
            $productTotalDiscount = 0;
            if (!empty($cart['tax'])) {
                $tax  = $cart['tax'] / $cart->quantity;
                OrderProductCharge::create([
                    'opc_op_id' => $orderProductid,
                    'opc_type' => 'tax',
                    'opc_value' =>  $tax,
                ]);
                $productTotalTax = $cart['tax'];
            }

            $taxInfo = TaxGroupType::getRecordByGroupId($product->prod_taxcat_id);
            $additionInfo = [
                'opainfo_op_id' => $orderProductid,
                'opainfo_cat_tax_code' => $product->cat_tax_code,
                'opainfo_tgtype_rate' => ($taxInfo->tgtype_rate) ? $taxInfo->tgtype_rate : '',
            ];
            if ($product->prod_type == Product::DIGITAL_PRODUCT) {
                $additionInfo['opainfo_max_download_limit'] = $product->pc_max_download_times;
                $additionInfo['opainfo_upload_additional_files'] = $product->pc_upload_additional_files;
                $additionInfo['opainfo_downloads'] = 0;
            }
            $shippingAttributes = Cart::getShippingAttributes();
            if (isset($shippingAttributes['shipping'])) {
                $shippingAmount = $this->calculateShipping($shippingAttributes['shipping'], ($code != 0 ? $code : $cart->product_id));
                $productTotalShipping = $shippingAmount;
                $additionInfo['opainfo_shipping_amount'] = priceFormat($shippingAmount / $cart->quantity, false);
            }
            if ($coupon = Cart::session($this->cartSession)->getCondition('coupon')) {
                $counpnAttributes =  $coupon->getAttributes();

                if (isset($counpnAttributes[$cartKey])) {
                    $additionInfo['opainfo_discount_amount'] = $counpnAttributes[$cartKey] / $cart->quantity;
                    $productTotalDiscount =  $counpnAttributes[$cartKey];
                } elseif (count($counpnAttributes) == 0) {
                    $totalTax = 0;
                    if ($totalTax = Cart::session($this->cartSession)->getCondition('tax')) {
                        $totalTax = $totalTax->getValue();
                    }
                    $netPrice = Cart::session($this->cartSession)->getSubTotal();

                    $discountAmount = $productPrice * (abs($coupon->getValue()) * 100 /  $netPrice) / 100;
                    $productTotalDiscount = $discountAmount * $cart->quantity;
                    $additionInfo['opainfo_discount_amount'] = priceFormat($discountAmount, false);
                }
            }

            if ($totalRewardPoint != 0) {
                $rewards = (($productTotalPrice - $productTotalDiscount) + $productTotalTax + $productTotalShipping) / ($totalRewardPoint + Cart::session($this->cartSession)->getTotal()) * 100;

                if (count($cartCollection) == $x) {
                    $rewards = 100 - round($totalRewards, 2);
                }

                $additionInfo['opainfo_reward_rate'] = priceFormat($rewards, false);

                $totalRewards += $rewards;
            }

            if (isset($cart->attributes->getItems()['gift'])) {
                $additionInfo['opainfo_gift_amount'] = $giftPrice;
            }
            OrderProductAdditionInfo::create($additionInfo);
            $this->saveRewardPointsByProduct((($productPrice *  $cart->quantity) - $productTotalDiscount), $orderProductid);

            $this->saveTaxLogs($orderId, $orderProductid, $taxInfo, $productPrice, $tax, $cart->quantity);
            $x++;
        }
        $shippingAttributes = Cart::getShippingAttributes();
        if (!empty($shippingAttributes['pick_ups'])) {
            $pickaddress = StoreAddress::getRecordById($shippingAttributes['pick_ups']['address_id']);
            $address = [
                'addr_first_name' => $pickaddress->saddr_name,
                'addr_last_name' => '',
                'addr_email' => '',
                'addr_address1' => $pickaddress->saddr_address,
                'addr_address2' => '',
                'addr_city' => $pickaddress->saddr_city,
                'addr_postal_code' => $pickaddress->saddr_postal_code,
                'addr_phone_country_id' => $pickaddress->saddr_phone_country_id,
                'addr_phone' => $pickaddress->saddr_phone,
                'country_name' => $pickaddress->country_name,
                'state_name' => $pickaddress->state_name,
                'country_code' => $pickaddress->country_code,
            ];
            OrderAddress::saveAddress($orderId, $address, OrderAddress::SHIPPING_PICKUP_TYPE);
        }
    }

    private function saveTaxLogs($orderId, $orderProductId, $taxInfo, $productPrice, $taxAmount, $qty)
    {
        if (count($taxInfo->combinedData) > 0) {
            foreach ($taxInfo->combinedData as $combinedData) {
                if ($taxAmount != 0 && $combinedData->tgc_rate != "") {
                    $taxAmount = round($productPrice * $combinedData->tgc_rate / 100, 2);
                }
                $taxAmount = $taxAmount * $qty;

                OrderProductTaxLog::create([
                    'optl_order_id' => $orderId,
                    'optl_op_id' => $orderProductId,
                    'optl_key' =>  $combinedData->tgc_name,
                    'optl_value' => $taxAmount,
                ]);
            }
        } else {
            if ($taxAmount != 0) {
                $taxAmount = round($productPrice * $taxInfo->tgtype_rate / 100, 2);
            }
            $taxAmount = $taxAmount * $qty;

            OrderProductTaxLog::create([
                'optl_order_id' => $orderId,
                'optl_op_id' => $orderProductId,
                'optl_key' => $taxInfo->tgtype_name,
                'optl_value' => $taxAmount,
            ]);
        }
    }

    private function saveRewardPointsByProduct($price, $orderProductId)
    {
        $netPrice = Cart::session($this->cartSession)->getSubTotal();
        $config = getConfigValues(['REWARD_POINTS_ENABLE', 'REWARD_POINTS_PURCHASE_POINTS_MINIMUM_ORDER_TOTAL', 'REWARD_POINTS_PURCHASE_POINTS_AMOUNT', 'REWARD_POINTS_ROUNDING_MODE', 'REWARD_POINTS_PURCHASE_POINTS_EARNINGS']);
        if ($config['REWARD_POINTS_ENABLE'] == 1 && $config['REWARD_POINTS_PURCHASE_POINTS_MINIMUM_ORDER_TOTAL'] <= $netPrice) {
            $totalRewardPoint = $price /  $config['REWARD_POINTS_PURCHASE_POINTS_AMOUNT'];

            if (($config['REWARD_POINTS_ROUNDING_MODE']) == 1) {
                if (strpos($totalRewardPoint, ".") !== false) {
                    $totalRewardPoint =  ceil($totalRewardPoint);
                }
            } else {
                $totalRewardPoint =  floor($totalRewardPoint);
            }
            $totalRewardPoint =   $totalRewardPoint *  $config['REWARD_POINTS_PURCHASE_POINTS_EARNINGS'];
            OrderProductCharge::create([
                'opc_op_id' => $orderProductId,
                'opc_type' => 'rewardpoints',
                'opc_value' => $totalRewardPoint,
            ]);
        }
    }

    private function calculateShipping($shippings, $productId)
    {
        foreach ($shippings as $key => $ship) {
            $explode = explode(',', $key);
            if (count($explode) > 1) {
                if (in_array($productId, $explode)) {
                    return last($ship) / count($explode);
                }
            } elseif ($key == $productId) {
                return last($ship);
            }
        }
        return 0;
    }

    private function savedComment($recordId, $type, $comment, $subrecordId = 0, $userId = 0)
    {
        return OrderMessage::create([
            'omsg_type' => $type,
            'omsg_record_id' => $recordId,
            'omsg_subrecord_id' => $subrecordId,
            'omsg_from_type' => OrderMessage::MSG_FROM_USER,
            'omsg_from_id' => $userId,
            'omsg_comment' => $comment,
            'omsg_created_at' => Carbon::now(),
        ])->omsg_id;
    }
    public function buyAgain(Request $request)
    {
        $status = config('constants.SUCCESS');
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $order = Order::select('order_cart_data')->where('order_id', $request->input('order_id'))->first();
        $message = '';
        if (!empty($order)) {
            $cartData = unserialize($order->order_cart_data);
  
            $products = [];
            $qty = 0;

            foreach ($cartData as $key => $cart) {
                $code = $key;
                if ($cart['product_id'] ===  $key) {
                    $code = 0;
                }


                $productRecord = Product::productById($cart['product_id'], 'prod_id,prod_stock_out_sellable,prod_min_order_qty,prod_max_order_qty, ' . Product::getPrice(), [], $code, true);
                if(!$productRecord){
                    continue;
                }
                $maxQty = $productRecord->prod_max_order_qty;
           
                if ($maxQty > $productRecord->totalstock) {
                    $maxQty = $productRecord->totalstock;
                }
                
               
                if (!empty($productRecord)) {
                    if (isset($cart['attributes']['gift'])) {
                        unset($cart['attributes']['gift']);
                        unset($cart['attributes']['message']);
                    }
                    $itemCode = $code ? $code : $cart['product_id'];
                    $qty = $cart['quantity'];
                 
                    $cartItem = Cart::session($this->cartSession)->getContent()->byKey($itemCode);
                    
                    $stock = true;
                    if (!empty($cartItem)) {
                        $qty = $qty + $cartItem['quantity'];
                        $stock = productStockVerify($productRecord, $qty, $code);
                    }
                    if ($stock == false) {
                        $status = config('constants.ERROR');
                        $message = appLang('NOTI_PRODUCT_NOT_AVAILABLE_OR_OUT_OF_STOCK');
                        continue;
                    }
                    
                    if ($qty > $maxQty) {
                        $qty = $maxQty;
                    }
                    $minStock = productStockVerify($productRecord, $qty, $code);
                   
                    if (isset($cart['attributes']['styles']) && is_array($cart['attributes']['styles']) == false) {
                        $cart['attributes']['styles'] = $cart['attributes']['styles']->toArray();
                    }

                    $products[] = $cart['product_id'];
                
                    if ($qty == 0) {
                        $status = config('constants.ERROR');
                        $message = appLang('NOTI_PRODUCT_NOT_AVAILABLE_OR_OUT_OF_STOCK');
                        continue;
                    }
                    Cart::session($this->cartSession)->add($cart['product_id'], $cart['name'], $cart['price'], $qty, $key, $cart['shipType'], $cart['attributes']);
                }
            }
           
            if (count($products) == 0) {
                $status = config('constants.ERROR');
                $message = appLang('NOTI_PRODUCT_NOT_AVAILABLE_OR_OUT_OF_STOCK');
            } elseif (count($products)  != count($cartData)) {
                $status = config('constants.ERROR');
                $message = appLang('NOTI_PRODUCT_NOT_AVAILABLE_OR_OUT_OF_STOCK');
            }
           
            return apiresponse($status, $message);
        }
    }
}
