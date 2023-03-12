<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderProductAdditionInfo;
use Cart;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\YokartController;
use App\Helpers\PaymentGatewayHelper;
use App\Models\UserAddress;
use App\Models\OrderAddress;
use App\Models\Transaction;
use App\Models\Country;
use App\Models\State;
use App\Models\TaxGroupType;
use App\Models\StoreAddress;
use App\Models\OrderProductTaxLog;
use App\Models\DiscountCoupon;
use App\Models\CouponHistory;
use App\Models\ProductOptionVarient;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\SmsTemplate;
use App\Helpers\EmailHelper;
use App\Models\OrderProductCharge;
use App\Jobs\SendNotification;
use App\Models\ProductStockHold;
use App\Models\Configuration;
use App\Models\UserRewardPoint;
use App\Models\DigitalFileRecord;
use App\Models\AttachedFile;
use App\Models\UserRewardPointBreakup;
use App\Models\OrderMessage;
use App\Models\Reason;
use App\Models\OrderInvoice;
use App\Models\Admin\AdminRole;
use App\Models\Admin\Admin;
use App\Models\OrderReturnBankInfo;
use App\Models\OrderReturnRequest;
use App\Models\OrderReturnAmount;
use App\Models\OrderAdditionInfo;
use App\Models\Package;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use App\Helpers\TrackingApiHelper;
use App\Models\UserTempTokenRequest;
use ZipArchive;
use File;
use PDF;
use Str;
use Redirect;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\NotificationTemplate;
use App\Events\SystemNotification;
use App\Models\Notification;
use Inertia\Inertia;

class OrderController extends YokartController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth')->only('index', 'orderDetail', 'digitalProducts', 'downloadItems', 'saveComments', 'cancelOrder', 'returnItems');
    }

    public function validateCardDetails($data)
    {
        switch ($data['payment-gateway']) {
            case 'Stripe':
            case 'AuthorizeDotNet':
            case 'Paypal':
                $this->cartValidation($data)->validate();
                break;
        }
    }
    protected function cartValidation(array $data)
    {
        $validator = Validator::make($data, [
            'number' => ['required'],
            'name' => ['required', 'string', 'max:191'],
            'exp_month' => ['required', 'min:2', 'max:2'],
            'exp_year' => ['required', 'min:4', 'max:4'],
            'cvv' => ['required'],
        ]);
        return $validator->setAttributeNames([
            'number' => 'card number',
            'name' => 'card name',
            'exp_month' => 'expired month',
            'exp_year' => 'expired year',
            'cvv' => 'cvv',
        ]);
    }



    public function store(Request $request)
    {
        $pickupAvailable = count(Cart::getPickups('name'));
        if (empty($request['payment-gateway'])) {
            return jsonresponse(false, __('NOTI_PLEASE_SELECT_PAYMENET_METHOD'));
        }
        if (empty($request['billing-address']) && empty($request['addr_id']) && $pickupAvailable == 0 && Cart::isDigitalProduct() != 1) {
            UserAddress::addressValidate($request->all())->validate();
        }
        if ($request['selectedCards'] == 'false' && $request['redirect'] == 0) {
            $this->validateCardDetails($request->all());
        }
        if ($request['payment-gateway'] == 'cod' && isset($request['cod']) && $request['cod'] != 1) {
            return jsonresponse(false, __('Please verify COD!!'));
        }
        $cartCollection = Cart::session($this->cartSession)->getContent();
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
            $userId = 0;
            if ($this->signed_in) {
                $userId = $this->user->user_id;
            }
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
        $price = Cart::session($this->cartSession)->getTotal();
        $address = UserAddress::getRecordById($request->input('address-id'))->toArray();

        $userId = $address['addr_user_id'];

        $subTotal = Cart::session($this->cartSession)->getSubTotal();

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
        if ($request['payment-gateway'] == "rewards" && $price == 0) {
            $paymentSatus = Order::PAYMENT_PAID;
        }
        
        $insertId = Order::create([
            'order_user_id' => $userId,
            'order_payment_status' => $paymentSatus,
            'order_shipping_status' => Order::SHIPPING_STATUS_IN_PROGRESS,
            'order_status' => Order::ORDER_STATUS_OPEN,
            'order_payment_method' => $request['payment-gateway'],
            'order_net_amount' => $price,
            'order_amount_charged' => 0,
            'order_shipping_type' =>  $shipType,
            'order_tax_charged' => $totalTax,
            'order_discount_coupon_code' => $couponCode,
            'order_discount_type' => $couponType,
            'order_discount_amount' => abs($couponValue),
            'order_currency_code' => $currency->currency_code, //currencyCode(),
            'order_currency_symbol' => $currency->currency_symbol, //currencySymbol(),
            'order_currency_value' => $currency->currency_value, //currencyValue(),
            'order_shipping_value' => $totalShipping,
            'order_gift_amount' => $giftAmount,
            'order_reward_points' => $totalRedeemedPoints,
            'order_reward_amount' => abs($totalRedeemedPointsAmount),
            'order_shipping_methods' => $shippingMethods,
            'order_notes' => $request->input('order_notes'),
            'order_cart_data' => serialize($cartCollection),
        ])->id;


        $data = NotificationTemplate::getBySlug('order_created_by_user');
        $message = str_replace('{orderNumber}', $insertId, $data->ntpl_body);
        $message = str_replace('{orderAmount}', formatPriceByCurrencyCode($price, $currency->currency_code), $message);
        event(new SystemNotification([
            'type' => Notification::ORDER_CREATED_BY_USER,
            'record_id' => $insertId,
            'from_id' => $userId,
            'message' => $message
        ]));

        $this->savedComment($insertId, OrderMessage::MSG_ORDER_TIMELLINE_TYPE, $timeLineMessage, 0, $userId);

        $this->saveProducts($insertId, $cartCollection, $totalRedeemedPoints);

        if ($totalRedeemedPoints != 0) {
            UserRewardPoint::redeemRewardPoints($userId, $insertId, $totalRedeemedPoints);
        }


        if ($historyId != 0) {
            $this->updateCoupon($historyId, $insertId, $userId);
        }

        if ($pickupAvailable > 0 || Cart::isDigitalProduct()) {
            $this->saveAddress($insertId, $address, OrderAddress::BILLING_ADDRESS_TYPE);
        } else {
            $this->saveAddress($insertId, $address, OrderAddress::SHIPPING_ADDRESS_TYPE);
            if (empty($request['billing-address'])) {
                if (!empty($request['edited_addr_id'])) {
                    $request['addr_id'] =  $request['edited_addr_id'];
                }
                $stateId = $countryId = '';
                if (!empty($request['addr_phone_country_code'])) {
                    $country = Country::getCountries(['country_id'], ['country_code' => Str::upper($request['addr_phone_country_code'])])->first();
                    $request['addr_phone_country_id'] = !empty($country->country_id) ? $country->country_id : null;
                }

                if (!empty($request['addr_id'])) {
                    if (!empty($request['edited_addr_id'])) {
                        UserAddress::where('addr_id', $request['edited_addr_id'])->update(
                            $request->only(['addr_email', 'addr_first_name', 'addr_last_name', 'addr_address1', 'addr_phone_country_id', 'addr_phone', 'addr_address2', 'addr_city', 'addr_postal_code', 'addr_country_id', 'addr_state_id'])
                        );
                    }
                    $existingAddress = UserAddress::getAddressByid($request['addr_id']);
                    $address = $existingAddress->only(['addr_email', 'addr_first_name', 'addr_last_name', 'addr_address1', 'addr_phone_country_id', 'addr_phone', 'addr_address2', 'addr_city', 'addr_postal_code']);
                    $stateId = $existingAddress['addr_state_id'];
                    $countryId = $existingAddress['addr_country_id'];
                } else {
                    $address = $request->only(['addr_email', 'addr_first_name', 'addr_last_name', 'addr_address1', 'addr_phone_country_id', 'addr_phone', 'addr_address2', 'addr_city', 'addr_postal_code']);
                    $stateId = $request['addr_state_id'];
                    $countryId = $request['addr_country_id'];
                }
                $state = State::getRecordById($stateId);
                $country = Country::getRecordById($countryId);
                $address['state_name'] = $state->state_name;
                $address['country_name'] = $country->country_name;
                $address['country_code'] = $country->country_code;
            }
            $this->saveAddress($insertId, $address, OrderAddress::BILLING_ADDRESS_TYPE);
        }

        $order = Order::getRecordById($insertId, $userId);
        $this->orderConfirmationNotification($order);
        $this->newOrderEmailToAdmin($order);
        
        ProductStockHold::where('pshold_session_id', $this->cartSession)->delete();
        $orderStatus = [];
        $orderStatus['data']['id'] = '';
        $encryptedOrderId = Crypt::encryptString($insertId . config("app.salt") . $userId);
       
        if ($request->input('payment-gateway') != 'cod' && $request->input('payment-gateway') != 'rewards') {
            $orderStatus = $this->orderPayment($request, $insertId);
            $chargePrice = 0;
            if (empty($orderStatus['status'])) {
                if (isset($orderStatus['method']) &&  $orderStatus['method'] == "redirect") {
                    Cart::session($this->cartSession)->clear();
                    return jsonresponse(false, '', $orderStatus);
                } else {
                    Cart::session($this->cartSession)->clear();
                    $this->paymentFailedSendPaymentLink($order);
                    return jsonresponse(false, $orderStatus['message'], ['url' => route('successError', $encryptedOrderId)]);
                }
            }

            $chargePrice = $orderStatus['data']['amount'];
            
            if (round($chargePrice) != round($price)) {
                $this->paymentFailedSendPaymentLink($order);
                return jsonresponse(false, __('Payment failed'), ['url' => route('successError', $encryptedOrderId)]);
            }

            /* trigger event for system notification*/
            $data = NotificationTemplate::getBySlug('order_payment_received');
            $message = str_replace('{orderNumber}', $insertId, $data->ntpl_body);
            $message = str_replace('{orderAmount}', formatPriceByCurrencyCode($chargePrice, $currency->currency_code), $message);
            $message = str_replace('{paymentMethod}', $request['payment-gateway'], $message);
            event(new SystemNotification([
                'type' => Notification::ORDER_PAYMENT,
                'record_id' => $insertId,
                'from_id' => $userId,
                'message' => $message
            ]));

            $this->orderUpdate($insertId, Order::PAYMENT_PAID, $chargePrice);
        }
        $this->orderTransaction($insertId, $price, $userId, $request['payment-gateway'], $orderStatus);


        if ($request->input('payment-gateway') == 'reward_points') {
            $this->orderUpdate($insertId, Order::PAYMENT_PAID, 0);
        }
        $this->updateProductInventory($insertId);
        $canEarnOnRedeemed = (bool) getConfigValueByName('REWARD_POINTS_EARN_ON_REDEEMED_ORDER');
        $rewardActiveImmediately = (bool) getConfigValueByName('REWARD_POINTS_PURCHASE_POINTS_ACTIVATED_IMMEDIATELY');

        if (($canEarnOnRedeemed == false &&  $totalRedeemedPoints == 0) || $canEarnOnRedeemed == true) {
            if ($rewardActiveImmediately == true) {
                $this->saveRewardPoints($subTotal - abs($couponValue), $subTotal, $userId, $insertId);
            } else {
                Order::where('order_id', $insertId)->update(['order_pending_rewards' => true]);
            }
        }
        Cart::session($this->cartSession)->clear();
        $sysCurrency = \App\Models\Currency::getSystemDefault();

        return jsonresponse(true, __('NOTI_ORDER_CREATED_SUCCESSFULLY'), [
            'orderId' => $encryptedOrderId,
            'currency' => $sysCurrency->currency_code,
            'value' => $price
        ]);
    }



    public function saveRewardPoints($netPrice, $price, $userId, $orderId)
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
                    'urp_comments' => __('LBL_EARN_REWARD_POINT').' #' . $orderId,
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

    public function updateCoupon($historyId, $orderId, $userId)
    {
        CouponHistory::where('couponhistory_id', $historyId)->update(['couponhistory_order_id' => $orderId, 'couponhistory_user_id' => $userId]);
    }
    public function orderTransaction($orderId, $price, $userId, $gateWayType, $orderStatus, $comment = 'Order Placed', $txnType = "", $orrequestId = 0)
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
    public function orderPayment($request, $orderId, $amount = 0)
    {
        $payment = new PaymentGatewayHelper($request['payment-gateway']);
        if (isset($request['selectedCards']) && $request['selectedCards'] == "true" && $request['payment-gateway'] != "PaypalExpress" && $request['payment-gateway'] != "CashFree") {
            return $payment->orderPaymentByCartId($request['card-id'], $orderId);
        }
        if (empty($request['redirect'])) {
            $request['redirect'] = false;
        }
        return $payment->orderPayment($request, $orderId, $amount, $request['redirect']);
    }
    public function updateProductInventory($orderId)
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
    public function orderUpdate($orderId, $orderStatus, $chargePrice = 0)
    {
        Order::where('order_id', $orderId)->update([
            'order_payment_status' => $orderStatus,
            'order_amount_charged' => $chargePrice,
        ]);
    }
    public function saveProducts($orderId, $cartCollection, $totalRewardPoint)
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
            $pickaddress = StoreAddress::getRecordById($shippingAttributes['pick_ups']['address-id']);
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
            $this->saveAddress($orderId, $address, OrderAddress::SHIPPING_PICKUP_TYPE);
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
    public function saveRewardPointsByProduct($price, $orderProductId)
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
    public function saveAddress($orderId, $address, $type)
    {
        OrderAddress::create([
            'oaddr_order_id' => $orderId,
            'oaddr_name' => $address['addr_first_name'] . ' ' . $address['addr_last_name'],
            'oaddr_type' => $type,
            'oaddr_email' => $address['addr_email'],
            'oaddr_address1' => $address['addr_address1'],
            'oaddr_address2' => $address['addr_address2'],
            'oaddr_city' => $address['addr_city'],
            'oaddr_state' => $address['state_name'],
            'oaddr_country' => $address['country_name'],
            'oaddr_country_code' => $address['country_code'],
            'oaddr_postal_code' => $address['addr_postal_code'],
            'oaddr_phone_country_id' => !empty($address['addr_phone_country_id']) ? $address['addr_phone_country_id'] : null,
            'oaddr_phone' => $address['addr_phone'],
        ]);
    }
    public function success(Request $request, $id)
    {

      
          
        try {
            $decrypted = Crypt::decryptString($id);
        } catch (DecryptException $e) {
            toastr()->error(__('Invalid request!'));
            return redirect()->route('home');
        }
        $decrypted = explode(config("app.salt"), $decrypted);
        if (empty($decrypted) || count($decrypted) != 2) {
            toastr()->error(__('Invalid request!'));
            return redirect()->route('home');
        }
        $ipAddress = \Request::ip();
        $iphone = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");
        $ipod = strpos($_SERVER['HTTP_USER_AGENT'], "iPod");
            if ($iphone || $ipod) {
                //ios code;
        } elseif ($android) {
            echo "<script type='text/javascript'> window.android.updatePaymentSuccess('" . $decrypted[0] . "');</script>";
        }
    
        $order = Order::getRecordById($decrypted[0], $decrypted[1]);
        if (!empty($order)) {
            $result['order'] = $order;
            $result['billAddress'] = $order->address->where('oaddr_type', OrderAddress::BILLING_ADDRESS_TYPE)->first();
            $result['paymentTypes'] = Order::PAYMENT_STATUS;
            $result['addressTypes'] = OrderAddress::ADDRESS_TYPES;
            $result['shippingTypes'] = Order::SHIPPING_STATUS;
            $config = getConfigValues(['BUSINESS_PHONE_NUMBER', 'BUSINESS_PHONE_COUNTRY_CODE']);
            $country = Country::getCountries(['country_phonecode'], ['country_id' => $config['BUSINESS_PHONE_COUNTRY_CODE']])->first();
            $result['phone'] = (!empty($country->country_phonecode) ? '+' . $country->country_phonecode : '') . " " . $config['BUSINESS_PHONE_NUMBER'];
            return view('themes.' . config('theme') . '.success', $result);
        } else {
            toastr()->error(__('NOTI_ORDER_NOT_FOUND'));
            return redirect()->route('userOrders');
        }
    }

    public function paypalSucess(Request $request, $id)
    {
        $order = Order::getOrderById($id);
        $payment = new PaymentGatewayHelper('Paypal');
        $responce = $payment->completePurchase($request, $order);
       
        $orderStatus['status'] = "success";
    
        $orderStatus['data'] = $responce;
        $orderStatus['data']['id'] = $responce['PAYMENTINFO_0_TRANSACTIONID'];

        $this->orderUpdate($id, Order::PAYMENT_PAID, $order->order_net_amount);
        $orderStatus = $this->orderTransaction($id, $order->order_net_amount, $order->order_user_id, 'Paypal', $orderStatus);

        $this->updateProductInventory($id);
        $subTotal = calCulateOrderSubtotal($order);


        $canEarnOnRedeemed = (bool) getConfigValueByName('REWARD_POINTS_EARN_ON_REDEEMED_ORDER');
        $rewardActiveImmediately = (bool) getConfigValueByName('REWARD_POINTS_PURCHASE_POINTS_ACTIVATED_IMMEDIATELY');

        if (($canEarnOnRedeemed == false &&  $order->order_reward_amount == 0) || $canEarnOnRedeemed == true) {
            if ($rewardActiveImmediately == true) {
                $this->saveRewardPoints($subTotal - $order->order_discount_amount, $subTotal, $order->order_user_id, $order->order_id);
            } else {
                Order::where('order_id', $insertId)->update(['order_pending_rewards' => true]);
            }
        }


        /** trigger event for system notification */
        $data = NotificationTemplate::getBySlug('order_payment_received');
        $message = str_replace('{orderNumber}', $id, $data->ntpl_body);
        $message = str_replace('{orderAmount}', formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code), $message);
        $message = str_replace('{paymentMethod}', 'Paypal Express', $message);
        event(new SystemNotification([
            'type' => Notification::ORDER_PAYMENT,
            'record_id' => $id,
            'from_id' => $order->order_user_id,
            'message' => $message
        ]));

        $encryptedOrderId = Crypt::encryptString($id . config("app.salt") . $order->order_user_id);
        return redirect()->route('successPage', ['orderId' => $encryptedOrderId]);
    }

    public function index(Request $request, $id = '', $type = '')
    {
        $records = [
            'id' => $id,
            'shippingTypes' => array_reverse(Order::SHIPPING_STATUS),
            'shippedStatus' => Order::SHIPPING_STATUS,
            'pickedStatus' => Order::PICKUP_STATUS,
            'digitalStatus' => array_reverse(Order::DIGITAL_STATUS),
            'pickupStatus' => array_reverse(Order::PICKUP_STATUS),
            'returnStatus' => OrderReturnRequest::REQUEST_STATUS,
            'addressTypes' => OrderAddress::ADDRESS_TYPES,
            'stateName' => '',
            'countryName' => '',
            'type' =>  $type,
            'shopUrl' => route('productListing')
        ];

        $config = getConfigValues(['BUSINESS_STATE', 'BUSINESS_COUNTRY']);
        if (!empty($config["BUSINESS_STATE"])) {
            $state = State::getRecordById($config["BUSINESS_STATE"]);
            $records["stateName"] = $state->state_name;
        }
        if (!empty($config["BUSINESS_COUNTRY"])) {
            $country = Country::getRecordById($config["BUSINESS_COUNTRY"]);
            $records["countryName"] = $country->country_name;
        }
        $trackShippingPackage = Package::getActivePackage('trackShipping');
        if (isset($trackShippingPackage->pkg_slug) && !empty($trackShippingPackage->pkg_slug)) {
            $records["trackingEnabled"] = 1;
        } else {
            $records["trackingEnabled"] = 0;
        }

        return Inertia::render('Orders/Index', $records);
    }
    public function getOrders(Request $request, $type)
    {
        $id = $request->input('id');
        if ($type == "cancellation" || $type == "return") {
            $orders = OrderProduct::getReturnRecordsByUserId($this->user->user_id, $type, $id, $request->input('total'));
        } else {
            $orders = Order::getRecordsByUserId($this->user->user_id, $type, $id, $request->input('total'));
        }
        return jsonresponse(true, null, $orders);
    }
    public function saveNotes(Request $request, $id)
    {
        Order::where('order_id', $id)->update(['order_notes' => $request->input('notes')]);
        return jsonresponse(true, __('NOTI_NOTES_UPDATED_SUCCESSFULLY'));
    }
    public function cancelOrder($id)
    {
        $order = Order::getRecordById($id, $this->user->user_id);
        $reasonType = Reason::CANCELLATION;
        if ($order->order_shipping_status == Order::SHIPPING_STATUS_DELIVERED) {
            $reasonType = Reason::RETURN;
        }
        $bankDetails = OrderReturnBankInfo::where('orbinfo_order_id', $id)->first();

        $retrunReasons = Reason::where(['reason_type' => $reasonType, 'reason_publish' =>  config('constants.YES')])->get();

        $records = [
            'order' => $order,
            'paymentTypes' => Order::PAYMENT_STATUS,
            'returnStatus' => OrderReturnRequest::REQUEST_STATUS,
            'addressTypes' => OrderAddress::ADDRESS_TYPES,
            'reasonType' => $reasonType,
            'reasonTypes' => Reason::TYPE,
            'retrunReasons' => $retrunReasons,
            'bankDetails' => $bankDetails,
            'requestStatus' => OrderReturnRequest::REQUEST_STATUS,
            'productDiscountCount' => $order->products->whereNotNull('opainfo_discount_amount')->count()
        ];
        return Inertia::render('Orders/CreateRequest', $records);
    }
    public function digitalProducts(Request $request, $orderId = '', $productId = '')
    {
        $fileTypes = DigitalFileRecord::FILE_TYPES;
        $shopUrl = route('productListing');
        return Inertia::render('Orders/Download', ['fileTypes' => $fileTypes, 'orderId' => $orderId, 'productId' => $productId, 'shopUrl' => $shopUrl]);
    }


    public function getDigitalOrders(Request $request)
    {
        $orderId = $request->input('order-id');
        $productId = $request->input('product-id');
        $opId = '';
        if (!empty($orderId) && !empty($productId)) {
            $record = OrderProduct::where(['op_order_id' => $orderId, 'op_product_id' => $productId])->first();
            if ($record) {
                $opId = $record->op_id;
            }
        }
        $fileTypes = DigitalFileRecord::FILE_TYPES;
        $products = OrderProduct::digitalOrderByUserId($this->user->user_id, [], $opId, $request->input('filters'), $request->input('total'));

        foreach ($products as $product) {
            $url = route('userOrders', $product->order_id);
            if ($product->order_status == Order::ORDER_STATUS_COMPLETED) {
                $url = route('userOrders', [$product->order_id, 'history']);
            }
            $imageInfo = $this->getAttachedInfo($product, $fileTypes);
            $product->totalSize = $imageInfo['total_size'];
            $product->attachments = $imageInfo['total_attachments'];
            $product->expired = \Carbon\Carbon::parse($product->op_expired_on)->isPast();
            $product->maxTypeImage =  $imageInfo['attachmentType'];
            $product->orderPageUrl =  $url;
        }
        return jsonresponse(true, null, $products);
    }
    private function getAttachedInfo($product, $fileTypes)
    {
        $size = [];
        $type = [];
        $attachments = [];
        $x = 0;
        foreach ($product->digitalData->where('dfr_subrecord_id', $product->op_product_id) as $file) {
            if ($file->dfr_url == '' && !empty($file->afile_physical_path)) {
                $size[$x] = getFileSize($file->afile_physical_path);
                $type[$x] = $file->dfr_file_type;
            }
            $attachments[$x] = $file->dfr_file_type;
            $x++;
        }
        if (count($size) > 0) {
            $maxTypeImage = strtolower($fileTypes[$type[max(array_keys($size))]]);
            unset($attachments[max(array_keys($size))]);
        } else {
            $maxTypeImage = 'link';
        }
        return ['total_size' => array_sum($size), 'total_attachments' => array_values($attachments),  'attachmentType' => $maxTypeImage];
    }
    public function downloadFiles(Request $request)
    {
        $orderId = $request->input('order-id');
        $productId = $request->input('product-id');
        $condistions = ['op_order_id' => $orderId, 'op_product_id' => $productId];
        if (!empty($orderId) && !empty($productId)) {
            $record = OrderProduct::where($condistions)->first();
            if ($record) {
                $opId = $record->op_id;
            }
        }
        $totalDownloads = OrderProductAdditionInfo::select('opainfo_max_download_limit', 'opainfo_downloads')->where('opainfo_op_id', $opId)->first();
        if ($totalDownloads->opainfo_max_download_limit == $totalDownloads->opainfo_downloads) {
            return jsonresponse(false, 'Download limit Reached');
        }
        OrderProductAdditionInfo::where('opainfo_op_id', $opId)->increment('opainfo_downloads');
        $fileTypes = DigitalFileRecord::FILE_TYPES;
        $product = OrderProduct::digitalOrderByUserId($this->user->user_id, $condistions);
        $imageInfo = $this->getAttachedInfo($product, $fileTypes);
        $product->totalSize = $imageInfo['total_size'];
        $product->attachments = $imageInfo['total_attachments'];
        $product->expired = \Carbon\Carbon::parse($product->op_expired_on)->isPast();
        $product->maxTypeImage =  $imageInfo['attachmentType'];
        $url = route('downloadItems', [$orderId, $productId]);
        return jsonresponse(true, null, ['product' => $product, 'url' => $url]);
    }

    public function downloadItemsByApp(Request $request, $orderId, $productId){
        if (!$request->has('t')) {
            return ['status' => __("NOTI_FILE_NOT_EXISTS")];
        }
        $tempToken = $request->input('t');
        if(UserTempTokenRequest::where('uttr_type', UserTempTokenRequest::DOWNLOAD_REQUEST)->where('uttr_code', $tempToken)->where('uttr_expiry', '>=', Carbon::now())->count() > 0){
            UserTempTokenRequest::where('uttr_code', $tempToken)->where('uttr_type', UserTempTokenRequest::DOWNLOAD_REQUEST)->delete();
            return $this->downloadItems($orderId,$productId);
        }else{
            return ['status' => __("NOTI_FILE_NOT_EXISTS")];
        }
    }
    
    public function downloadItems($orderId, $productId)
    {
        $product = OrderProduct::select('op_product_name', 'op_pov_id', 'op_id', 'op_qty')->where(['op_order_id' => $orderId, 'op_product_id' => $productId])->first();
        
        $records = DigitalFileRecord::getRecordsByOrderId($orderId, $productId, $product->op_pov_id);
        $digitalRecords = DigitalFileRecord::with('attachment:afile_physical_path,afile_id')->where('dfr_record_id', $product->op_id)->where('dfr_record_type', DigitalFileRecord::ORDERED_PRODUCT_RECORD_TYPE)->get();
    
        $zipFileName = $product->op_product_name . '.zip';
        $txtFiles = [];
        $zip = new ZipArchive;
        if ($zip->open(public_path() . '/storage/' . $zipFileName, ZipArchive::CREATE) === true) {
            
            for ($x = 0; $x < $product->op_qty; $x++) {
                $number = '';
                if ($product->op_qty != 1) {
                    $number = $x + 1;
                }
                foreach ($records as $record) {
                    if ($record->dfr_url == '' && !empty($record->attachment)) {
                        $ext = pathinfo(storage_path($record->attachment->afile_physical_path), PATHINFO_EXTENSION);
                        $zip->addFile(public_path('storage/' . $record->attachment->afile_physical_path), $record->dfr_name . '' . $number . '.' . $ext);
                    } elseif ($record->dfr_url != '') {
                        $filename = $record->dfr_name . '' . $number . '.txt';
                        $destinationPath = public_path('storage/' . $filename);
                        $txtFiles[] = $destinationPath;
                        File::put($destinationPath, $record->dfr_url);
                        $zip->addFile($destinationPath, $filename);
                    }
                }
                if (count($digitalRecords) > 0 && isset($digitalRecords[$x])) {
                    $ext = pathinfo(storage_path($digitalRecords[$x]->attachment->afile_physical_path), PATHINFO_EXTENSION);
                    $zip->addFile(public_path('storage/' . $digitalRecords[$x]->attachment->afile_physical_path), $digitalRecords[$x]->dfr_name . '' . $number . '.' . $ext);
                }
            }
            $zip->close();
            if (count($txtFiles) > 0) {
                File::delete($txtFiles);
            }
        }
        $headers = array(
            'Content-Type' => 'application/octet-stream',
        );
        
        $filetopath = public_path() . '/storage/' . $zipFileName;
        if (file_exists($filetopath)) {
            return response()->download($filetopath, $zipFileName, $headers)->deleteFileAfterSend(true); die;
        }
        return ['status' => __("NOTI_FILE_NOT_EXISTS")];
    }



    public function saveComments(Request $request)
    {
        $this->validate($request, [
            'comment' => 'required',
        ]);
        $type = OrderMessage::MSG_ORDER_TIMELLINE_TYPE;
        if ($request->input('type') == "cancellation" || $request->input('type') == "return") {
            $type = OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE;
        }
        $comment = $request->input('comment');
        $this->savedComment($request->input('record-id'), $type, $comment);

        /* trigger event for system notification*/
        $data = NotificationTemplate::getBySlug('new_comment_on_order');
        $notifyType = Notification::ORDER_COMMENT;
        $orderId = $request->input('record-id');
        if ($request->input('type') == "cancellation" || $request->input('type') == "return") {
            $returnData = OrderReturnRequest::select('orrequest_order_id', 'orrequest_type')->where('orrequest_id', $request->input('record-id'))->first();
            $notifyType = Notification::ORDER_RETURN_COMMENT;
            $data = NotificationTemplate::getBySlug('new_comment_on_order_return_request');
            if ($returnData->orrequest_type == OrderReturnRequest::CANCELLATION) {
                $notifyType = Notification::ORDER_CANCELLATION_COMMENT;
                $data = NotificationTemplate::getBySlug('new_comment_on_order_cancellation_request');
            }
            $orderId = $returnData->orrequest_order_id;
        }
        $message = str_replace('{orderNumber}', $orderId, $data->ntpl_body);
        $message = str_replace('{userName}', Str::title($this->user->user_fname . ' ' . $this->user->user_lname), $message);

        event(new SystemNotification([
            'type' => $notifyType,
            'record_id' => $orderId,
            'record_subid' => !empty($request->input('type')) ? $request->input('record-id') : 0,
            'from_id' => $this->user->user_id,
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
        return jsonresponse(true, __('NOTI_COMMENT_SUCCESSFULLY_SENT'));
    }

    private function replaceCommentTags($content, $comment, $adminName, $orderNumber)
    {
        $content = str_replace('{buyerName}', $this->user->user_fname . ' ' . $this->user->user_lname, $content);
        $content = str_replace('{adminName}', $adminName, $content);
        $content = str_replace('{commentText}', $comment, $content);
        $content = str_replace('{link}', url('') . '/admin#/order/' . $orderNumber, $content);
        $content = str_replace('{orderNumber}', $orderNumber, $content);
        $content = str_replace('{websiteName}', (getConfigValueByName('BUSINESS_NAME') ?? ''), $content);
        return $content;
    }

    private function savedComment($recordId, $type, $comment, $subrecordId = 0, $userId = 0)
    {
        if ($userId == 0 && $this->user) {
            $userId = $this->user->user_id;
        }
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
    public function loadComments(Request $request)
    {
        $recordId = $request->input('record-id');
        $type = [OrderMessage::MSG_ORDER_TIMELLINE_TYPE, OrderMessage::MSG_ORDER_ACTION_TYPE];
        if ($request->input('type') == "cancellation" || $request->input('type') == "return") {
            $type = OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE;
        }

        /* if ($request->input('type') == "history") {
            $requestIds =  OrderReturnRequest::where('orrequest_order_id', $recordId)->pluck('orrequest_id')->toArray();
            $messages = OrderMessage::getOrderHistory($recordId, $requestIds, $type, OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE);
        } else {
            $messages = OrderMessage::getRecords($recordId, $type);
        }*/
        $messages = OrderMessage::getRecords($recordId, $type);


        return jsonresponse(true, null, $messages);
    }
    public function returnItems(Request $request)
    {
        $orderId = $request->input('order-id');
        $type = $request->input('type');
        $order = Order::getRecordById($orderId, $this->user->user_id);
      
        $gatewayType = $order->paymentslug;
        $transactionId = "";
        if ($order->transaction) {
            $transactionId = $order->transaction->txn_gateway_transaction_id;
            if (!in_array($order->transaction->txn_gateway_type, ['cash', 'card'])) {
                $gatewayType = $order->transaction->txn_gateway_type;
            }
        }

        $bankInfo = json_decode($request->input('bank'), true);
        if (!empty($bankInfo) && count($bankInfo) > 0) {
            $this->submitBankInformation($bankInfo, $orderId);
        }
        $approvalReq = getConfigValueByName('APPROVAL_ON_CANCELLATION');
        $items = json_decode($request->input('items'), true);
        $refundAmount = [];
        $reqIds = [];
        $x = 1;
        if($items){
            
            foreach ($items as $item) {
                if (!$item['selectedQty'] || $item['selectedQty'] == 0) {
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
                }else{
                    $cancellationReason = "";
                }
                $requestExist = OrderReturnRequest::where(['orrequest_op_id' => $item['op_id'], 'orrequest_order_id' => $orderId])->count();
                if ($requestExist != 0) {
                    return Redirect::route('cancelOrder', $orderId);
                }
                $orrequestId = OrderReturnRequest::create([
                    'orrequest_user_id' => $this->user->user_id,
                    'orrequest_op_id' => $item['op_id'],
                    'orrequest_order_id' => $orderId,
                    'orrequest_qty' => $item['selectedQty'],
                    'orrequest_type' => $type,
                    'orrequest_date' => Carbon::now(),
                    'orrequest_order_status' => isOrderPaid($order->payment_status) ? 1 : 0,
                    'orrequest_status' => $refundStatus,
                    'orrequest_txn_gateway_transaction_id' =>  $transactionId,
                    'orrequest_reason' => $item['reason'],
                    'orrequest_comment' =>  $request->input('comment'),
                ])->orrequest_id;

                if ($refundStatus ==  OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED) {
                    $this->updateReturnInventory($product, $item['selectedQty'], $orrequestId);
                }
                $shipping = 0;
                if ($x == count($order->products) && $request->input('shipping') != 0) {
                    $shipping = $request->input('shipping');
                }
                $reqIds[] = $orrequestId;
                $additionInfoMessage =  '';
                if ($request->input('comment')) {
                    $additionInfoMessage =  '<p> <span class="text-bold">Addition Information:</span> ' . $request->input('comment') . '</p>';
                }
                $refundAmount = $this->calculateYKRefundableAmount($shipping, $order, $product, $item, $orrequestId, $refundStatus, $cancellationReason, $additionInfoMessage, $type);
                

                if ($type == OrderReturnRequest::CANCELLATION && $transactionId != "" && $refundAmount != 0  && $approvalReq == 0) {
                    $orderReturnStatus = $this->paymentRefund($gatewayType, $transactionId, $refundAmount, $order->order_currency_code);
                    if ($orderReturnStatus['status'] != 1) {
                        return Redirect::route('cancelOrder', $orderId);
                    }
                
                    $this->orderTransaction($orderId, $refundAmount, $this->user->user_id, $gatewayType, $orderReturnStatus, 'order Refund', Transaction::CREDIT_TYPE, $orrequestId);
                    $transMessage = '';
                    if ($orderReturnStatus['data']['id']) {
                        $transMessage = '<p><span class="text-bold">' . __('LBL_TRANSACTION_DETAILS') . ': </span>' . $orderReturnStatus['data']['id'] . '</p>';
                    }

                    $commentId =  $this->savedComment($orrequestId, OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE, 'Refund processed');
                    $this->savedComment($orrequestId, OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE, '<p><span class="text-bold">' . __('LBL_REASON_ONLY') . ':</span>' . $item['reason'] . '</p>' . $additionInfoMessage . '' . $transMessage, $commentId);

                    $orderCommentId = $this->savedComment($orderId, OrderMessage::MSG_ORDER_TIMELLINE_TYPE, __('LBL_REFUND_REQUEST_PROCESSED_FOR_REQUEST_ID') . ' #' . $orrequestId);


                    $orderMessage = "Canceled " . $product->op_product_name . " ". __('LBL_WITH_REQUEST_ID') . " #" . $orrequestId . '<p><span class="text-bold">' .  __('LBL_REASON_ONLY') . ': </span> ' . $item['reason'] . '</p>' . $additionInfoMessage . '' . $transMessage;
                    $this->savedComment($orderId, OrderMessage::MSG_ORDER_TIMELLINE_TYPE, $orderMessage, $orderCommentId);
                } else {
                    $commentId = $this->savedComment($orrequestId, OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE, OrderReturnRequest::TYPE[$type] . ' request initiated');

                    $this->savedComment($orrequestId, OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE, '<p><span class="text-bold">' . __('LBL_REASON_ONLY') .': </span>' . $item['reason'] . ' </p>' . $additionInfoMessage, $commentId);
                    $orderMessage = $product->op_product_name . " " . __('LBL_WITH_REQUEST_ID') . " #" . $orrequestId . '<p><span class="text-bold"> ' . __('LBL_REASON_ONLY') .': </span>' . $item['reason'] . '</p>' . $additionInfoMessage;

                    $orderCommentId = $this->savedComment($orderId, OrderMessage::MSG_ORDER_TIMELLINE_TYPE, OrderReturnRequest::TYPE[$type] . ' ' . __('LBL_REQUESTED_FOR_REQUEST_ID') . ' #' . $orrequestId);

                    $this->savedComment($orderId, OrderMessage::MSG_ORDER_TIMELLINE_TYPE, $orderMessage, $orderCommentId);
                }
                $x++;
            }
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
            $message = str_replace('{userName}', Str::title($this->user->user_fname . ' ' . $this->user->user_lname), $message);
            event(new SystemNotification([
                'type' => $notifyType,
                'record_id' => $orderId,
                'from_id' => $this->user->user_id,
                'message' => $message
            ]));
        }
        $requestId = Crypt::encryptString(implode('-', $reqIds) . config("app.salt") . $orderId);
        return Redirect::route('returnSummary', $requestId);
    }
    private function submitBankInformation($bankInfo, $orderId)
    {
        $bankInfo['orbinfo_order_id'] = $orderId;
        $bankInfo['orbinfo_user_id'] = $this->user->user_id;

        $exist = OrderReturnBankInfo::where('orbinfo_order_id', $orderId)->count();
        if ($exist == 0) {
            OrderReturnBankInfo::create($bankInfo);
        } else {
            OrderReturnBankInfo::where('orbinfo_order_id', $orderId)->update($bankInfo);
        }
    }

    private function calculateYKRefundableAmount($shippingAmount, $order, $product, $item, $requestId, $refundStatus, $cancellationReason = '', $additionInfoMessage = '', $type)
    {
        $tax = 0;
        if (!empty($product) && !empty($product->tax)) {
            $tax = $product->tax->opc_value *  $item['selectedQty'];
        }
        $discountAmount = 0;
        if (!empty($order->order_discount_amount)) {
            $discountAmount =  $product->opainfo_discount_amount  * $item['selectedQty'];
        }
        $refundableAmount = (($product->op_product_price * $item['selectedQty'] + $tax + $shippingAmount) - $discountAmount);
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
            $giftPrice =  $product->opainfo_gift_amount * $item['selectedQty'];

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
        // if ($order->order_reward_amount != 0) {
        //     $rewardPrice =  ((($order->order_reward_amount * $product->opainfo_reward_rate) / 100) / $product->op_qty) * $item['selectedQty'];
        //     // $refundableAmount = $rewardPrice;
        //     // if ($order->order_shipping_status != 1 && $giftPrice != 0) {
        //     //     $rewardAmount =  $refundableGift  + $giftPrice;
        //     //     $refundableAmount =  $refundableAmount - $giftPrice;
        //     // } else {
        //     //     $rewardAmount = ($refundableAmount - $rewardPrice > 0) ? $refundableAmount - $rewardPrice : 0;
        //     // }
        // }

        if ($order->order_reward_amount != 0) {
            $rewardPrice =  ((($order->order_net_amount * $product->opainfo_reward_rate) / 100) / $product->op_qty) * $item['selectedQty'];
            $refundableAmount = $rewardPrice;
            if ($order->order_shipping_status != 1 && $giftPrice != 0) {
                $rewardAmount =  $refundableGift  + $giftPrice;
                $refundableAmount =  $refundableAmount - $giftPrice;
            } else {
                $rewardAmount = ($refundableAmount - $rewardPrice > 0) ? $refundableAmount - $rewardPrice : 0;
            }
        }

        $refundAmount['oramount_reward_price'] = round($rewardPrice, 2);
        OrderReturnAmount::create($refundAmount);
        /** Start Send Cancellation Request Email to Buyer */
        $approvalOnCancellation = Configuration::getValue('APPROVAL_ON_CANCELLATION');
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
        $replacementData['subTotal'] = $product->op_product_price * $item['selectedQty'];
        $data['subject'] = str_replace("{orderNumber}", $order->order_id, $data['body']->etpl_subject);
        if ($type == OrderReturnRequest::CANCELLATION) {
            $replacementData['requestType'] = "Cancellation";
        } else {
            $replacementData['requestType'] = "Return";
        }
        $data['subject'] = str_replace("{type}", $replacementData['requestType'], $data['subject']);
        $data['subject'] = str_replace("{returnId}", $order->order_id.'-'. $requestId, $data['subject']);
        $data['body'] = $this->replacementCancellation($data['body']->etpl_body, $order, $replacementData, $item, '', $requestId);
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

                $adminEmaildata['body'] = $this->replacementCancellation($adminEmaildata['body']->etpl_body, $order, $replacementData, $item, $admin['admin_name'], $requestId);
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

    private function updateReturnInventory($product, $qty, $requestId)
    {
        if (!empty($product->rewards)) {
            $rewardAmount = $product->rewards->opc_value;
            if ($product->op_qty != $qty) {
                $rewardAmount = round($rewardAmount / $qty);
            }
            UserRewardPoint::redeemRewardPoints($this->user->user_id, $product->op_order_id, $rewardAmount, UserRewardPoint::ORDER_REQUEST_REFUND_POINT, $requestId);
        }
        $productId = $product->op_product_id;
        $varientCode = $product->op_pov_code;
        Product::where('prod_id', $productId)->decrement('prod_sold_count', $qty);
        if ($varientCode != "0") {
            ProductOptionVarient::where('pov_code', $varientCode)->increment('pov_quantity', $qty);
        } else {
            Product::where('prod_id', $product)->increment('prod_stock', $qty);
        }
    }
    public function paymentRefund($paymentMethod, $transactionId, $amount,$currencyCode)
    {
        $payment = new PaymentGatewayHelper($paymentMethod);
        return $payment->orderRefund($transactionId, $amount,$currencyCode);
    }
    public function returnSummary($id)
    {
        try {
            $decrypted = Crypt::decryptString($id);
        } catch (DecryptException $e) {
            toastr()->error(__('Invalid request!'));
            return redirect()->route('home');
        }
        $decrypted = explode(config("app.salt"), $decrypted);


        if (empty($decrypted)) {
            toastr()->error(__('Invalid request!'));
            return redirect()->route('home');
        }
        $orderId = end($decrypted);
        $records = OrderProduct::getReturnSummary(explode('-', head($decrypted)), $orderId);

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

        return Inertia::render('Orders/RequestSummary', ['records' => $records, 'stateName' => $stateName, 'countryName' => $countryName, 'reasonTypes' => $reasonTypes, 'orderId' => $orderId, 'comment' => $comment, 'phonecode' => $phonecode]);
    }
    public function orderConfirmation(Request $request)
    {
        $inFo = [
            'oainfo_order_id' => $request->input('order-id'),
            'oainfo_received_confirmation' => 1
        ];
        $exist = OrderAdditionInfo::where('oainfo_order_id', $request->input('order-id'))->count();
        if ($exist != 0) {
            OrderAdditionInfo::where('oainfo_order_id', $request->input('order-id'))->update($inFo);
        } else {
            OrderAdditionInfo::create($inFo);
        }

        return jsonresponse(true, __('LBL_CONFIRMATION_SUBMITED_SUCCESSFULLY'));
    }

    public function buyAgain(Request $request, $orderId)
    {
        $order = Order::select('order_cart_data')->where('order_id', $orderId)->first();
        if (!empty($order)) {
            $cartData = unserialize($order->order_cart_data);

            $products = [];
            $qty = 0;

            foreach ($cartData as $key => $cart) {
                $code = $key;
                if ($cart['product_id'] ===  $key) {
                    $code = 0;
                }


                $productRecord = Product::productById($cart['product_id'], 'prod_published,prod_id,prod_stock_out_sellable,prod_min_order_qty,prod_max_order_qty, ' . Product::getPrice(), [], $code, true);
                if(!$productRecord || $productRecord->prod_published != 1){
                    toastr()->error(__('NOTI_PRODUCT_NOT_AVAILABLE_OR_OUT_OF_STOCK'));
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
                        toastr()->error(__('NOTI_PRODUCT_NOT_AVAILABLE_OR_OUT_OF_STOCK'));
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
                        toastr()->error(__('NOTI_PRODUCT_NOT_AVAILABLE_OR_OUT_OF_STOCK'));
                        continue;
                    }
                    Cart::session($this->cartSession)->add($cart['product_id'], $cart['name'], $cart['price'], $qty, $key, $cart['shipType'], $cart['attributes']);
                }
            }
            if (count($products) == 0) {
                toastr()->error(__('NOTI_PRODUCT_NOT_AVAILABLE_OR_OUT_OF_STOCK'));
            } elseif (count($products)  != count($cartData)) {
                toastr()->error(__('NOTI_SOME_PRODUCT_NOT_AVAILABLE_OR_OUT_OF_STOCK'));
            }

            return redirect('/cart');
        }
    }
    public function makePayment(Request $request, $paymentGateway,$orderId)
    {
        $data = $request->except('_token');
        $data['payment-gateway'] = $paymentGateway;

        $this->validateCardDetails($data);
        $amount = $request->input('amount');

        $orderStatus = $this->orderPayment($data, $orderId, $amount);
        $chargePrice = 0;
        if (empty($orderStatus['status'])) {
            return jsonresponse(false, $orderStatus['message']);
        }
        $order = Order::select('order_user_id', 'order_net_amount')->where('order_id', $orderId)->first();

        $chargePrice = $orderStatus['data']['amount'];
        if (round($chargePrice) != round($amount)) {
            return jsonresponse(false, __('NOTI_PAYMENT_FAILED'));
        }

        $this->orderUpdate($orderId, Order::PAYMENT_PAID, $chargePrice);
        $this->orderTransaction($orderId, $amount, $order->order_user_id, $paymentGateway, $orderStatus);

        $order = Order::getOrderById($orderId);

        $this->updateProductInventory($orderId);



        $subTotal = calCulateOrderSubtotal($order);


        $canEarnOnRedeemed = (bool) getConfigValueByName('REWARD_POINTS_EARN_ON_REDEEMED_ORDER');
        $rewardActiveImmediately = (bool) getConfigValueByName('REWARD_POINTS_PURCHASE_POINTS_ACTIVATED_IMMEDIATELY');

        if (($canEarnOnRedeemed == false &&  $order->order_reward_amount == 0) || $canEarnOnRedeemed == true) {
            if ($rewardActiveImmediately == true) {
                $this->saveRewardPoints($subTotal - $order->order_discount_amount, $subTotal, $order->order_user_id, $order->order_id);
            } else {
                Order::where('order_id', $insertId)->update(['order_pending_rewards' => true]);
            }
        }

        $sysCurrency = \App\Models\Currency::getSystemDefault();

        /** trigger event for system notification */
        $data = NotificationTemplate::getBySlug('order_payment_received');
        $message = str_replace('{orderNumber}', $orderId, $data->ntpl_body);
        $message = str_replace('{orderAmount}', formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code), $message);
        $message = str_replace('{paymentMethod}', $paymentGateway, $message);
        event(new SystemNotification([
            'type' => Notification::ORDER_PAYMENT,
            'record_id' => $orderId,
            'from_id' => $order->order_user_id,
            'message' => $message
        ]));
        $encryptedOrderId = Crypt::encryptString($orderId . config("app.salt") . $order->order_user_id);

        return jsonresponse(true, __('NOTI_ORDER_CREATED_SUCCESSFULLY'), [
            'orderId' => $encryptedOrderId,
            'currency' => $sysCurrency->currency_code,
            'value' => $amount
        ]);
    }
    public function getbankDetails($orderId)
    {
        $bankDetails = OrderReturnBankInfo::where('orbinfo_order_id', $orderId)->first();

        $html = view('themes.' . config('theme') . '.dashboard.orders.bankForm', compact('bankDetails'))->render();
        return jsonresponse(true, null, $html);
    }


    public function cashFreeSucess(Request $request)
    {
        $orderDetails = Order::getOrderById($request->input('orderId'));
        if ($request->input('txStatus') == "SUCCESS") {
            if (round($request->input('orderAmount')) != round($orderDetails->order_net_amount)) {
                $this->paymentFailedSendPaymentLink($orderDetails);
                // payment failed create payment failure page
            }
            $this->orderUpdate($request->input('orderId'), Order::PAYMENT_PAID, $orderDetails->order_net_amount);
            $data['data'] = $request->all();
            $data['data']['id'] = $request->input('referenceId');
            $data['data']['status'] = true;
            $orderStatus = $this->orderTransaction($request->input('orderId'), $request->input('orderAmount'), $orderDetails->order_user_id, 'CashFree', $data);
            $this->updateProductInventory($request->input('orderId'));

            /** trigger event for system notification */
            $data = NotificationTemplate::getBySlug('order_payment_received');
            $message = str_replace('{orderNumber}', $request->input('orderId'), $data->ntpl_body);
            $message = str_replace('{orderAmount}', formatPriceByCurrencyCode($orderDetails->order_net_amount, $orderDetails->order_currency_code), $message);
            $message = str_replace('{paymentMethod}', 'CashFree', $message);
            event(new SystemNotification([
                'type' => Notification::ORDER_PAYMENT,
                'record_id' => $request->input('orderId'),
                'from_id' => $orderDetails->order_user_id,
                'message' => $message
            ]));

            $subTotal = calCulateOrderSubtotal($orderDetails);
            $canEarnOnRedeemed = (bool) getConfigValueByName('REWARD_POINTS_EARN_ON_REDEEMED_ORDER');
            $rewardActiveImmediately = (bool) getConfigValueByName('REWARD_POINTS_PURCHASE_POINTS_ACTIVATED_IMMEDIATELY');

            if (($canEarnOnRedeemed == false &&  $orderDetails->order_reward_amount == 0) || $canEarnOnRedeemed == true) {
                if ($rewardActiveImmediately == true) {
                    $this->saveRewardPoints($subTotal - $orderDetails->order_discount_amount, $subTotal, $orderDetails->order_user_id, $orderDetails->order_id);
                } else {
                    Order::where('order_id', $insertId)->update(['order_pending_rewards' => true]);
                }
            }

            $encryptedOrderId = Crypt::encryptString($request->input('orderId') . config("app.salt") . $orderDetails->order_user_id);
            return redirect()->route('successPage', ['orderId' => $encryptedOrderId]);
        }
    }
    public function downloadInvoice($orderId)
    {

        /*   $invoiceExist =  OrderInvoice::where('oinv_order_id', $orderId)->first();


        $invoiceDetails = Configuration::getKeyValues(['TAX_INVOICE_ALPHABET_NUMBER', 'TAX_INVOICE_NUMERIC_NUMBER']);
        $newInvoiceNumber = $invoiceDetails['TAX_INVOICE_ALPHABET_NUMBER'] . '-' . $invoiceDetails['TAX_INVOICE_NUMERIC_NUMBER'];
        $lastInvoice = OrderInvoice::select('oinv_number')->where('oinv_number', 'LIKE', '%' . $invoiceDetails['TAX_INVOICE_ALPHABET_NUMBER'] . '%')->orderBy('oinv_created_at', 'DESC')->first();
        if (!empty($lastInvoice)) {
            $explodeNumber = array_filter(explode('-', $lastInvoice->oinv_number));
            if (isset($explodeNumber[0]) && isset($explodeNumber[1])) {
                $newInvoiceNumber = $explodeNumber[0] . '-' . ($explodeNumber[1] + 1);
            }
        }
        $currentTime = Carbon::now();


        $records = [
            'order' => Order::getRecordById($orderId),
            'addressTypes' => OrderAddress::ADDRESS_TYPES,
            'taxDetails' => Configuration::getRecordsByPrefix('TAX'),
            'businessDetails' => Configuration::getRecordsByPrefix('BUSINESS'),
            'invoiceNumber' => $invoiceExist->oinv_number,
            'invoiceDate' => $currentTime,
            'taxInfo' => OrderProductTaxLog::where('optl_order_id', $orderId)->get()->toArray(),
        ];
        if (!empty($records["businessDetails"]["BUSINESS_COUNTRY"])) {
            $country = Country::getRecordById($records["businessDetails"]["BUSINESS_COUNTRY"]);
            $records["businessDetails"]["BUSINESS_COUNTRY"] = $country->country_name;
        }
        if (!empty($records["businessDetails"]["BUSINESS_STATE"])) {
            $state = State::getRecordById($records["businessDetails"]["BUSINESS_STATE"]);
            $records["businessDetails"]["BUSINESS_STATE"] = $state->state_name;
        }
        if (isset($_GET['view'])) {
            return  View('pdf.order-invoice', $records);
        }
        $html = View('pdf.order-invoice', $records)->render();

        $pdf = PDF::loadHTML($html);
        return   $pdf->setPaper('a2')->stream();
        */
        $invoiceDetails = OrderInvoice::where('oinv_order_id', $orderId)->first();
        $pdf = PDF::loadHTML($invoiceDetails->oinv_content);
        return $pdf->setPaper('a2')->download('invoice-' . $invoiceDetails->oinv_created_at . '.pdf');
    }

    public function orderConfirmationNotification($order)
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

    public function paymentFailedSendPaymentLink($order)
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

    public function getPaymentLink($orderId)
    {
        $paymentGateways = Package::getActivePaymentGateways('paymentGateways')->where('pkg_card_type', 1)->first();
        if (empty($paymentGateways)) {
            return false;
        }
        $encrypted =  Crypt::encryptString(config('app.salt') . '' . $orderId . '-' . $paymentGateways->pkg_slug);
        return url('/payment/' . $encrypted);
    }

    public function newOrderEmailToAdmin($order)
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

    public function replacementTags($type, $order, $products, $shipAddress, $billAddress, $pickAddress, $adminName = '', $buyerName = '')
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
    public function paymentLink(Request $request)
    {
        //$paymentGateways = Package::getActivePaymentGateways('paymentGateways')->where('pkg_card_type', 1)->first();
        $paymentGateways = Package::getActivePaymentGateways('paymentGateways')->first();
        if (empty($paymentGateways)) {
            return jsonresponse(false, __('NOTI_PAYMENT_GATEWAY_NOT_ACTIVATED'));
        }
        $orderId = $request->input('order_id');

        $paymentGateway = '-' . $paymentGateways->pkg_slug;
        $encrypted =  Crypt::encryptString(config('app.salt') . '' . $orderId . '' . $paymentGateway);
        $url = url('/payment/' . $encrypted);
        return jsonresponse(true, '', ['url' => $url]);
    }

    public function getTrackingInformation(Request $request)
    {
        $trackShippingPackage = Package::getActivePackage('trackShipping');
        if (isset($trackShippingPackage->pkg_slug) && !empty($trackShippingPackage->pkg_slug)) {
            $trackingApi = new TrackingApiHelper($trackShippingPackage->pkg_slug);
            $data = $trackingApi->getTrackingInfo($request->input('tracking_number'), $request->input('courier_name'), $request->input('order_id'));
            return jsonresponse(true, '', $data);
        }
        return jsonresponse(true, '', []);
    }

    private function replacementCancellation($type, $order, $replacementData, $item, $adminName = "", $requestId = 0)
    {
        $variants = json_decode($item['op_product_variants'], true);
        if (!empty($variants["styles"])) {
            $variants = " (" . implode(" | ", $variants["styles"]) . ") ";
        } else {
            $variants = "";
        }
        $type = str_replace('{adminUser}', $adminName, $type);
        $type = str_replace('{userName}', $this->user->user_fname . ' ' . $this->user->user_lname, $type);
        $type = str_replace('{orderId}', $order->order_id, $type);
        $type = str_replace('{productName}', $item['op_product_name'] . $variants . ' X ' . $item['op_qty'], $type);
        $type = str_replace('{productImage}', productImageUrl($item['op_product_id'], $item['op_pov_code']), $type);
        $type = str_replace('{productUrl}', getRewriteUrl("products", $item['op_product_id']), $type);
        $type = str_replace('{productPrice}', formatPriceByCurrencyCode($item['op_product_price'], $order->order_currency_code), $type);
        $type = str_replace('{cancellationReason}', $replacementData['cancellationReason'], $type);
        $type = str_replace('{requestType}', $replacementData['requestType'], $type);
        $type = str_replace('{cancellationAdditionInformation}', $replacementData['additionInfoMessage'], $type);
        $type = str_replace('{subtotal}', formatPriceByCurrencyCode($replacementData['subTotal'], $order->order_currency_code), $type);
        $type = str_replace('{tax}', formatPriceByCurrencyCode($replacementData['tax'], $order->order_currency_code), $type);
        $type = str_replace('{shipping}', formatPriceByCurrencyCode($replacementData['shippingAmount'], $order->order_currency_code), $type);
        $type = str_replace('{totalRefundAmount}', formatPriceByCurrencyCode($replacementData['refundableAmount'], $order->order_currency_code), $type);
        $type = str_replace('{reviewRequestUrl}', url("/admin#/order/return/" . $order->order_id . "/". $requestId), $type);
        $type = str_replace('{cancellationRequestId}', $order->order_id . "-". $requestId, $type);
        $type = str_replace('{returnRequestId}', $order->order_id . "-". $requestId, $type);
        $type = str_replace('{websiteName}', env('APP_NAME'), $type);
        $type = str_replace('{RequestId}', $requestId, $type);
        $type = str_replace('{requestDeclinedComment}', '', $type);

        $configurations = Configuration::getRecordsByPrefix('BUSINESS');
        $countryName = '';
        if (isset($configurations['BUSINESS_COUNTRY']) && !empty($configurations['BUSINESS_COUNTRY'])) {
            $country = Country::getRecordById($configurations['BUSINESS_COUNTRY']);
            $countryName = $country->country_name;
        }
        $stateName = '';
        if (isset($configurations['BUSINESS_STATE']) && !empty($configurations['BUSINESS_STATE'])) {
            $state = State::getRecordById($configurations['BUSINESS_STATE']);
            $stateName = $state->state_name;
        }
        $type = str_replace('{adminAddress1}', ($configurations['BUSINESS_ADDRESS1'] ?? ''), $type);
        $type = str_replace('{adminAddress2}', ($configurations['BUSINESS_ADDRESS2'] ?? ''), $type);
        $type = str_replace('{adminAddressState}', $stateName, $type);
        $type = str_replace('{adminAddressCountry}', $countryName, $type);
        $type = str_replace('{adminAddressPincode}', ($configurations['BUSINESS_PINCODE'] ?? ''), $type);
        return $type;
    }
}
