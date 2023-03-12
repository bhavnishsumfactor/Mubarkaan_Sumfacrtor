<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\YokartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Cart;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use App\Models\Product;
use App\Models\CouponHistory;
use App\Models\DiscountCoupon;
use App\Helpers\Cart\CartCondition;
use App\Models\UserRewardPoint;
use App\Models\Configuration;
use App\Models\StoreTiming;
use App\Models\UserTempTokenRequest;
use App\Models\SmsTemplate;
use App\Helpers\EmailHelper;
use App\Helpers\PaymentGatewayHelper;
use App\Jobs\SendNotification;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use DB;
use Str;
use Auth;
use App\Models\UserAddress;
use App\Models\StoreAddress;
use App\Models\Package;
use App\Models\UserCard;
use App\Models\ShippingRate;

class CheckoutController extends YokartController
{
    public function getStep1Data(Request $request, $type)
    {
        $storeAddress = StoreAddress::getRecords([], false);
        $defaultAddressId = '';
        if (!empty($storeAddress) && count($storeAddress)) {
            $firstAddress = $storeAddress->first();
            $defaultAddressId = $firstAddress->saddr_id;
        }
        $dayOfTheWeek = Carbon::parse(date('Y-m-d'))->dayOfWeek;
        foreach ($storeAddress as $storeAddr) {
           
            $timeSlots = [];
            if (count($storeAddr['timings']) > 0) {
                $timeSlots = $storeAddr['timings']->pluck('st_day')->toArray();
            }
            $nextDate = Arr::last($timeSlots, function ($value, $key) use ($dayOfTheWeek) {
                return $value >= $dayOfTheWeek;
            });
            if (empty($nextDate) && count($timeSlots) > 0) {
                $nextDate = Arr::first($timeSlots);
            }
            $defaultDate = date('Y-m-d');
            if ($nextDate) {
                $defaultDate = Carbon::parse(date('Y-m-d'))->next(StoreTiming::DAYS[$nextDate])->format('Y-m-d');
            }
            $storeAddr->default_date =  $defaultDate;
            $storeAddr->display_date =  getConvertedDateTime($defaultDate, false);
            $storeAddr->avail_weeks =  $timeSlots;
            $dayOfTheWeek = Carbon::parse($defaultDate)->dayOfWeek;
            $time_slots = StoreTiming::getTimings($storeAddr->saddr_id, $dayOfTheWeek,$defaultDate);
            $storeAddr->slots =  $time_slots;
        }
        
        $response['pickup_locations'] = $storeAddress;
        $response['default_id'] = $defaultAddressId;
        
        return apiresponse(config('constants.SUCCESS'), '', array_merge($response, paymentSummaryForApp()));
    }

    public function getShippingData(Request $request)
    {
        $address = UserAddress::getAddressByid($request->input('addr_id'))->toArray();
        if (empty($address)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_SOMETHING_WENT_WRONG"));
        }
        
        $response = [];
        $countryId = $address['addr_country_id'];
        $stateId = $address['addr_state_id'];
        $shippings =  Cart::shippingCalculate($countryId, $stateId);
        $cartCollection = Cart::session($this->cartSession)->getContent();
            
        Cart::taxCalculate($countryId, $stateId);
        $finalShipping = [];
        if (count($shippings) > 0) {
            foreach ($shippings as $key => $shipping) {
                $productIds = explode(',', $key);
                $tempShippingArr = [];
                $products = [];
                foreach ($productIds as $productId) {
                    $attributes = $cartCollection[$productId]['attributes']->getItems();
                    $subRecordId = 0;
                    if ($cartCollection[$productId]->product_id !== $productId) {
                        $subRecordId = getImageByVariantCode($cartCollection[$productId]->product_id, $productId);
                    }
                    $images = getProductImages($productId, $subRecordId);
                  
                    
                    $products[] = [
                        'variant_display_title' => isset($attributes['styles']) ? implode('|', $attributes['styles']) : '',
                        'prod_name' => $cartCollection[$productId]['name'],
                        'prod_price' => (string) $cartCollection[$productId]['price'],
                        'prod_image' => !empty($images->first()) ? url('yokart/image/' . $images->first()->afile_id . '?t=' . strtotime($images->first()->afile_updated_at)) : noImage('../no_image.jpg')
                    ];
                }
                $tempShippingArr['key'] = (string) $key;
                $tempShippingArr['products'] = $products;

                $tempShippingArr['shipping_options'] = [];
                if (array_key_exists('error', end($shipping))) {
                    $tempShippingArr['is_shippable'] = "0";
                } else {
                    $tempShippingArr['is_shippable'] = "1";
                    foreach ($shipping as $shipKey => $shipVal) {
                        $tempShippingArr['order_level_ship'] = $shipVal['order_level_ship'];
                        $tempShippingArr['shipping_options'][] = [
                            'srate_id' => (string) $shipKey,
                            'name' => $shipVal['name'],
                            'price' => (string) $shipVal['price']
                        ];
                    }
                }
                $finalShipping[] = $tempShippingArr;
            }
        }
        $response['shipping_info'] = $finalShipping;
        $response['address'] = $address;
        $response['shipping_count'] = count($shippings) ? count($shippings) : 0;
        return apiresponse(config('constants.SUCCESS'), "", array_merge($response, paymentSummaryForApp()));
    }

    public function shippingUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipping' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        
        $shipping = $request->input("shipping");
        $shipping_attributes = $shipping_val = [];
        foreach ($shipping as $key => $srateId) {
            $srate = explode('-',$srateId);
            $srateId = trim($srate[0]);
            if(count($srate) == 2){
                $srateId = trim($srate[1]);
            }
            $shippingRate = ShippingRate::where('srate_id', $srateId)->first();
            $shipping_attributes[$key][$shippingRate->srate_name] = $shippingRate->srate_cost;
            $shipping_val[] = $shippingRate->srate_cost;
        }

        $shippingAttributes = Cart::getShippingAttributes();
        unset($shippingAttributes['shipping']);
        $updatedAttributes = $shippingAttributes + ['shipping' => $shipping_attributes];
        Cart::shippingUpdate(array_sum($shipping_val), $updatedAttributes);
        
        return apiresponse(config('constants.SUCCESS'), "", ['cart_info' => $this->getCartSummary(false)]);
    }

    public function saveCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required|numeric|digits_between:13,19',
            'name' => 'required|string|max:191',
            'exp_month' => 'required|digits:2',
            'exp_year' => 'required|digits:4',
            'cvv' => 'required|numeric|digits_between:3,4'
        ]);
        $validator->setAttributeNames([
            'number' => 'card number',
            'exp_month' => 'expiry month',
            'exp_year' => 'expiry year',
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $package = Package::select('pkg_slug')->where('pkg_type', Package::PKG_TYPE['paymentGateways'])->where('pkg_publish', config('constants.YES'))->where('pkg_card_type', config('constants.YES'))->first();
        if (!isset($package->pkg_slug) || empty($package->pkg_slug)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_SOMETHING_WENT_WRONG"));
        }
        $payment = new PaymentGatewayHelper($package->pkg_slug);
        $response = $payment->saveCard($request->toArray(), Auth::guard('api')->user()->user_id);
        $cardData = UserCard::prepareCardData($package->pkg_slug, $response["message"]);
        if ($response['status']) {
            return apiresponse(config('constants.SUCCESS'), appLang("NOTI_CARD_ADDED"), ['card' => $cardData]);
        } else {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_SOMETHING_WENT_WRONG"));
        }
    }

    public function getPaymentStepData(Request $request)
    {
        $selectedShippings = '';
        if (count(Cart::getPickups('name')) > 0) {
            $pickupAddressId = $request->input('pickup_address_id');
            $selectedDate = $request->input('selected_date');
            $stFrom = $request->input('st_from');
            $stTo = $request->input('st_to');
            if (empty($pickupAddressId)) {
                return apiresponse(config('constants.ERROR'), appLang("NOTI_PLEASE_SELECT_PICKUP_ADDRESS"));
            }
            if (empty($selectedDate)) {
                return apiresponse(config('constants.ERROR'), appLang("NOTI_PLEASE_SELECT_PICKUP_DATE"));
            }
            if (empty($stFrom) || empty($stTo)) {
                return apiresponse(config('constants.ERROR'), appLang("NOTI_PLEASE_SELECT_PICKUP_TIMING"));
            }
            $pickupSlot = $stFrom . " - " . $stTo;
            $this->setPickupAddress($pickupAddressId, $selectedDate, $pickupSlot);
        } else {
            if (Cart::shippingErrors() == true) {
                return apiresponse(config('constants.ERROR'), appLang('NOTI_SHIPPING_NOT_AVAILABLE_FOR_PRODUCT'));
            }
            // if (Cart::totalShippingProducts() != $request['shippings']) {
            //     return jsonresponse(false, __('NOTI_PLEASE_SELECT_SHIPPING'));
            // }
            // if ($request->input('shipping')) {
            //     $selectedShippings = implode(',', array_keys($request->input('shipping')));
            // }
        }

        $response = [];
        $userId  = Auth::guard('api')->user()->user_id;
        $paymentGatewayRecords=[];
        $paymentGateways = Package::getActivePaymentGateways('paymentGateways');
        $selectedPaypal = false;
        $i = 0;
        foreach($paymentGateways as $pg){
            // if(($pg->pkg_slug == "Paypal" || $pg->pkg_slug == "PaypalExpress") && $selectedPaypal ){
            //     continue;
            // }
            if($pg->pkg_slug == "PaypalExpress"){
                continue;
            }
            $paymentGatewayRecords[$i++] = ['pkg_card_type'=>$pg->pkg_card_type,
            'pkg_slug'=>$pg->pkg_slug,
            'credit_card'  => $pg->pkg_card_type ? UserCard::getCardsForApp($userId) : null];
            if(($pg->pkg_slug == "Paypal" || $pg->pkg_slug == "PaypalExpress") && !$selectedPaypal ){
                $selectedPaypal = true;
            }
        }
        if(getConfigValueByName('COD_ENABLE')){
            $paymentGatewayRecords[$i++] = ['pkg_card_type'=>0,'pkg_slug'=>'cod'];
        }
        $response['reward_points'] = UserRewardPoint::calculateRewardPoints(Cart::session($this->cartSession)->getSubTotal(), $userId);
        $response['payment_gateways'] = $paymentGatewayRecords;
        
        $userAddresses = UserAddress::getAddressesForApp($userId, "", false);
        $response['addresses'] = $userAddresses;
        if ($defaultAddress = $userAddresses->where('addr_billing_default', config("constants.YES"))->first()) {
            $response['default_billing_address'] = $defaultAddress->addr_id;
        }
        return apiresponse(config('constants.SUCCESS'), '', array_merge($response, paymentSummaryForApp()));
    }

    private function setPickupAddress($addressId, $selectedDate, $pickupSlot)
    {
        $address = StoreAddress::getRecordById($addressId);

        $pickupAddress = $address->saddr_name . ' ' . $address->saddr_address . ' , ' . $address->saddr_postal_code . ' ' . $address->saddr_city . ' ' . $address->state_name . ' ' . $address->country_name;
        $shippingAttributes = Cart::getShippingAttributes();
        if (!empty($shippingAttributes['pick_ups'])) {
            unset($shippingAttributes['pick_ups']);
        }
        $pickup['pick_ups'] = ['address_id' => $addressId, 'address' => $pickupAddress, 'pickup_date' => $selectedDate, 'pickup_time' => $pickupSlot];
       
        $updatedAttr = $shippingAttributes +  $pickup;
      
        Cart::shippingUpdate(0, $updatedAttr);
    }

    public function getPickupDays(Request $request, $saddr_id)
    {
        $days = StoreTiming::getStoreWeekDays($saddr_id);
        $default_date = "";
        if (count($days) > 0) {
            $en = Carbon::now();
            $currentDay = $en->format('N');
            $nextDate = $days->min();
            if ($currentDay < $days->max()) {
                $nextDate =  $days->max();
            }
            $configuration = Configuration::getKeyValues(['ADMIN_TIMEZONE', 'PICKUP_AVAILABLE']);
            $nextDay = StoreTiming::DAYS[$nextDate];
            $pickupAvailableAfter = ($configuration['PICKUP_AVAILABLE'] == '' ? 0 : $configuration['PICKUP_AVAILABLE']);
            $default_date = Carbon::now()->setTimezone($configuration['ADMIN_TIMEZONE'])->addHours($pickupAvailableAfter)->format('Y-m-d');
        }
        return apiresponse(config('constants.SUCCESS'), '', compact('days', 'default_date'));
    }

    public function getpickupTimeSlots(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'saddr_id' => 'required',
            'date' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $date = $request->input('date');
        $dayOfTheWeek = Carbon::parse($date)->dayOfWeek;
        $timeSlots = StoreTiming::getTimings($request->input('saddr_id'), $dayOfTheWeek,$date);
        $records['default_date'] = $date;
        $records['display_date'] = getConvertedDateTime($date, false);
        $records['time_slots'] = $timeSlots;
        return apiresponse(config('constants.SUCCESS'), '', $records);
    }

    public function getCartSummary($json = true)
    {
        $cartConditions = Cart::session($this->cartSession)->getConditions();
        $pickup  = count(Cart::getPickups('name'));

        $couponAmount = 0;
        if ($coupon = Cart::session($this->cartSession)->getCondition('coupon')) {
            $couponAmount = abs($coupon->getValue());
        }
        $response = [];

        $digitalProducts = Cart::isDigitalProduct();
        $response['digital_products'] = $digitalProducts;
        $response['sub_total'] = displayPrice(Cart::getSubTotal());
        $response['total'] = displayPrice(Cart::getTotal());
        $response['reward_points_earned'] = 0;
        $response['conditions'] = [];

        $displayRewardPointOnEarn = getConfigValueByName('REWARD_POINTS_PURCHASE_POINTS_DISPLAY_EARNED');
        $earnRewardPoints = UserRewardPoint::calculateEarnRewardPoints(Cart::session($this->cartSession)->getSubTotal(), $couponAmount);
        if ($earnRewardPoints != 0 && $displayRewardPointOnEarn) {
            $response['reward_points_earned'] = $earnRewardPoints;
        }

        foreach ($cartConditions as $key => $condition) {
            if ($condition->getType() == 'shipping' && ($digitalProducts == 1 || $pickup != 0)) {
                continue;
            }
            $tempArr = [];
            if (!empty($condition->getType() == 'coupon')) {
                $tempArr['type'] = 'coupon';
                $tempArr['code'] = $cartConditions['coupon']->getCode();
                $tempArr['value'] = '-' . displayPrice(abs($condition->getValue()));
            } elseif (!empty($condition->getType() == 'rewardpoints')) {
                $tempArr['type'] = 'rewardpoints';
                $tempArr['value'] = '-' . displayPrice(abs($condition->getValue()));
            } else {
                $tempArr['type'] = $condition->getType();
                if ($condition->getValue() == 0) {
                    $tempArr['value'] = displayPrice('00.00');
                } else {
                    $tempArr['value'] = displayPrice($condition->getValue());
                }
            }
            $response['conditions'][] = $tempArr;
        }
        if ($json === true) {
            return apiresponse(config('constants.SUCCESS'), '', ['cart_info' => $response]);
        }
        return $response;
    }

    public function applyRewardPoints(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'points' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $config = Configuration::getRecordsByPrefix('REWARD_POINTS');
        $pointWorth = ($config['REWARD_POINTS_SPENDING_POINTS_AMOUNT'] / $config['REWARD_POINTS_SPENDING_POINTS_EARNINGS']);
        $points = $request->input('points');
        $subTotal = Cart::session($this->cartSession)->getSubTotal();
        $totalPoints = UserRewardPoint::calculateRewardPoints($subTotal, Auth::guard('api')->user()->user_id);
        if ($subTotal < $totalPoints['minOrderTotal']) {
            return apiresponse(config('constants.ERROR'), appLang('NOTI_INSUFFICIENT_SUBTOTAL_FOR_REDEEM'));
        } elseif ($totalPoints['totalPoints'] < $points) {
            return apiresponse(config('constants.ERROR'), appLang('NOTI_INSUFFICIENT'));
        } elseif ($totalPoints['minUsePoints'] > $points) {
            return apiresponse(config('constants.ERROR'), appLang('NOTI_PLEASE_ENTER_GREATER_OR_EQUAL') .' '. $totalPoints['minUsePoints'] . ' '. appLang('NOTI_POINTS'));
        } elseif ($totalPoints['maxUsablePoints'] < $points) {
            return apiresponse(config('constants.ERROR'), appLang('NOTI_PLEASE_ENTER_LESS_THAN_EQUAL') .' '. $totalPoints['maxUsablePoints'] . ' '. appLang('NOTI_POINTS'));
        } elseif (($points * $pointWorth) > Cart::session($this->cartSession)->getTotal()) {
            return apiresponse(config('constants.ERROR'), appLang('NOTI_APPLIED_REWARD_POINT_EXCEED_THE_ORDER'));
        }

        $totalAmount = $pointWorth * $points;
        Cart::updateRewardPoints($totalAmount, $points);
        return apiresponse(config('constants.SUCCESS'), '', paymentSummaryForApp());
    }
    public function removeRewardPoints(Request $request)
    {
        Cart::session($this->cartSession)->removeConditionsByType('rewardpoints');
        return apiresponse(config('constants.SUCCESS'), '', paymentSummaryForApp());
    }

    public function addOrUpdateAddress(Request $request)
    {
        $lastId = $request->input('addr_id');
        if (!empty($lastId)) {
            $userAddress = UserAddress::where('addr_id', $lastId)->first();
            if (! Auth::guard('api')->user()->can('update', $userAddress)) {
                return apiresponse(config('constants.ERROR'), appLang("NOTI_YOU_DO_NOT_OWN_THIS_RECORD"));
            }
        }
        $validator = Validator::make($request->all(), [
            'addr_first_name' => 'required',
            'addr_last_name' => 'required',
            'addr_title' => 'required',
            'addr_address1' => 'required',
            'addr_city' => 'required',
            'addr_country_id' => 'required',
            'addr_state_id' => 'required',
            'addr_postal_code' => 'required',
            'addr_phone_country_code' => 'required',
            'addr_phone' => 'required'
        ]);
        $validator->setAttributeNames([
            'addr_first_name' => 'first name',
            'addr_last_name' => 'last name',
            'addr_title' => 'title',
            'addr_address1' => 'address1',
            'addr_city' => 'city name',
            'addr_country_id' => 'country name',
            'addr_state_id' => 'state name',
            'addr_postal_code' => 'postal code',
            'addr_phone_country_code' => 'phone country',
            'addr_phone' => 'phone number'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        
        $records = $request->except('addr_phone_country_code', 'addr_id');
        $records['addr_user_id'] = Auth::guard('api')->user()->user_id;
        $records['addr_phone_country_id'] = Country::getCountryIdByCountryCode($request->input('addr_phone_country_code'));
                
        if (!empty($lastId)) {
            UserAddress::where('addr_id', $lastId)->update($records);
            $message = appLang("NOTI_ADDRESS_UPDATED");
        } else {
            $lastId = UserAddress::create($records)->addr_id;
            $message = appLang("NOTI_ADDRESS_ADDED");
        }
        $response = [];
        $response['address'] = UserAddress::getAddressForApp(Auth::guard('api')->user()->user_id, $lastId);
        return apiresponse(config('constants.SUCCESS'), $message, $response);
    }

    
    public function requestCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'addr_id' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $address = UserAddress::getRecordById($request->input('addr_id'))->toArray();
        if (empty($address['addr_user_id'])) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_SOMETHING_WENT_WRONG"));
        }
        $userId = $address['addr_user_id'];
        $user = User::getUserById($userId);
        DB::beginTransaction();
        try {
            $otp = generateNumericOTP(6);
            UserTempTokenRequest::where('uttr_user_id', $userId)->where('uttr_type', UserTempTokenRequest::ORDER_OTP_REQUEST)->delete();
            UserTempTokenRequest::create([
                'uttr_type' => UserTempTokenRequest::ORDER_OTP_REQUEST,
                'uttr_user_id' => $userId,
                'uttr_code' => $otp,
                'uttr_expiry' => Carbon::now()->addMinutes(config('app.expiredToken'))
            ]);
            $this->sendOtpEmailAndSms($address, $otp);
            DB::commit();
            $message = '';
            $message .= !empty($address['addr_phone']) ? str_repeat('*', strlen($address['addr_phone']) - 4) . substr($address['addr_phone'], -4) : '';
            $message .= (!empty($address['addr_phone']) && !empty($address['addr_email'])) ? " and " : '';
            $message .= $address['addr_email'];
            return apiresponse(config('constants.SUCCESS'), appLang("NOTI_SUCCESSFULLY_SENT"), ['text' => appLang("NOTI_ENTER_OTP_AT")." ". $message]);
        } catch (Exception $e) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_SOMETHING_WENT_WRONG"));
            DB::rollBack();
        }
    }

    public function resendCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'addr_id' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $address = UserAddress::getRecordById($request->input('addr_id'))->toArray();
        if (empty($address['addr_user_id'])) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_SOMETHING_WENT_WRONG"));
        }
        $userId = $address['addr_user_id'];
        $user = User::getUserById($userId);
        DB::beginTransaction();
        try {
            $otp = generateNumericOTP(6);
            UserTempTokenRequest::where('uttr_user_id', $userId)->where('uttr_type', UserTempTokenRequest::ORDER_OTP_REQUEST)
                ->update([
                    'uttr_code' => $otp,
                    'uttr_expiry' => Carbon::now()->addMinutes(config('app.expiredToken'))
                ]);
            $this->sendOtpEmailAndSms($address, $otp);
            DB::commit();
            return apiresponse(config('constants.SUCCESS'), appLang("NOTI_SUCCESSFULLY_SENT"));
        } catch (Exception $e) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_SOMETHING_WENT_WRONG"));
            DB::rollBack();
        }
    }

    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'addr_id' => 'required',
            'code' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $address = UserAddress::getRecordById($request->input('addr_id'))->toArray();
        if (empty($address['addr_user_id'])) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_SOMETHING_WENT_WRONG"));
        }
        if (
            UserTempTokenRequest::where('uttr_user_id', $address['addr_user_id'])->where('uttr_type', UserTempTokenRequest::ORDER_OTP_REQUEST)
            ->where('uttr_code', $request->input('code'))->where('uttr_expiry', '>=', Carbon::now())->count() > 0
        ) {
            return apiresponse(config('constants.SUCCESS'), appLang("NOTI_CODE_VERIFY_SUCCESSFULLY"));
        } else {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_VERIFICATION_CODE_INCORRECT"));
        }
    }
    
    private function sendOtpEmailAndSms($user, $otp)
    {
        $notificationData = [];
        $sendSms = false;

        $currency = getSystemCurrency();
        $totalPrice = formatPriceByCurrencyCode(Cart::session($this->cartSession)->getTotal(), $currency->currency_code);
        $userName = $user['addr_first_name'] . ' ' . $user['addr_last_name'];
        $emaildata = EmailHelper::getEmailData('cod_verification');
        $subject = str_replace('{websiteName}', env('APP_NAME'), $emaildata['body']->etpl_subject);
        $priority = $emaildata['body']->etpl_priority;
        $emaildata['body'] = str_replace('{name}', $userName, $emaildata['body']->etpl_body);
        $emaildata['body'] = str_replace('{otp}', $otp, $emaildata['body']);
        $emaildata['body'] = str_replace('{orderAmount}', $totalPrice, $emaildata['body']);
        $emaildata['body'] = str_replace('{websiteName}', env('APP_NAME'), $emaildata['body']);
        $emaildata['body'] = str_replace('{contactUsEmail}', (getConfigValueByName('BUSINESS_EMAIL') ?? ''), $emaildata['body']);
        $emaildata['to'] = $user['addr_email'];
        $emaildata['subject'] = $subject;
        $notificationData['email'] = $emaildata;
        sendPushNotification($user['addr_user_id'], 'cod_verification', ['verification_code' => $otp]);
        if (!empty($user['addr_phone_country_id']) && !empty($user['addr_phone'])) {
            $country = Country::select('country_phonecode')->where('country_id', $user['addr_phone_country_id'])->first();
            $smsdata = SmsTemplate::getSMSTemplate('cod_verification');
            $priority = $smsdata['body']->stpl_priority;
            $smsdata['body'] = str_replace('{name}', $userName, $smsdata['body']->stpl_body);
            $smsdata['body'] = str_replace('{verificationCode}', $otp, $smsdata['body']);
            $smsdata['body'] = str_replace('{websiteName}', env('APP_NAME'), $smsdata['body']);
            $notificationData['sms'] = [
                'message' => $smsdata['body'],
                'recipients' => array('+' . $country->country_phonecode . $user['addr_phone'])
            ];
            $sendSms = true;
        }
        dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);
    }
        
    private function codAvailable()
    {
        $productIds = Cart::session($this->cartSession)->getContent()->getKeys();
        $products = [];
        if (!empty($productIds)) {
            $products = Product::getDataByIds($productIds)->pluck('prod_cod_available', 'prod_id')->toArray();
        }
        if (empty($products) || in_array(0, $products)) {
            return false;
        }
        if (getConfigValueByName('COD_ENABLE') == 0) {
            return false;
        }
        return true;
    }
    public function reviewOrder(Request $request)
    {
        $cartCollection = Cart::session($this->cartSession)->getContent();
        $cartRecords = [];
        foreach ($cartCollection as $key => $collection) {
            $attributes = $collection['attributes']->getItems();
            $giftApplied = false;
            $variant = '';
            if (isset($attributes['gift'])) {
                $giftApplied = true;
            }
            if (isset($attributes['styles'])) {
                $variant = implode('|', $attributes['styles']);
            }
            $cartRecords['products'][] = [
                'name' => $collection['name'],
                'id' => (string) $collection['id'],
                'product_id' => $collection['product_id'],
                'price' =>  (string) $collection['price'],
                'quantity' => (int) $collection['quantity'],
                'gift_applied' => $giftApplied,
                'variant_display_title' => $variant,
                'image' => url('yokart/app/product/image/' . $collection['product_id'] . '/' . ($collection['product_id'] == $key ? 0 : $key) . '/' . getProdSize('medium')),
            ];
        }
        $userId  = Auth::guard('api')->user()->user_id;
        $cartRecords['ship_addr'] =  new \stdClass();
        $cartRecords['billing_addr'] = new \stdClass();
        $cartRecords['pickup_addr'] = new \stdClass();
        if ($request->input('shiiping_address_id')) {
            $cartRecords['ship_addr'] = UserAddress::getAddressForApp($userId, $request->input('shiiping_address_id'))->toArray();
        }
        if ($request->input('billing_address_id')) {
            $cartRecords['billing_addr'] = UserAddress::getAddressForApp($userId, $request->input('billing_address_id'))->toArray();
        }
        $shippAttr = Cart::getShippingAttributes();
       
        if (!empty($shippAttr['pick_ups'])) {
            $cartRecords['pickup_addr'] = StoreAddress::getRecordById($shippAttr['pick_ups']['address_id'])->toArray();
            $cartRecords['pickup_addr']['date'] = $shippAttr['pick_ups']['pickup_date'];
            $cartRecords['pickup_addr']['time'] = $shippAttr['pick_ups']['pickup_time'];
        }
        $results = array_merge($cartRecords, paymentSummaryForApp());
        return apiresponse(config('constants.SUCCESS'), '', $results);
    }
    
    public function pickupRecordsById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'saddr_id' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $dayOfTheWeek = Carbon::parse(date('Y-m-d'))->dayOfWeek;
        $storeAddress = StoreAddress::getRecordByIdForApp($request->input('saddr_id'));
        $timeSlots = [];
        if (count($storeAddress['timings']) > 0) {
            $timeSlots = $storeAddress['timings']->pluck('st_day')->toArray();
        }
        $nextDate = Arr::last($timeSlots, function ($value, $key) use ($dayOfTheWeek) {
            return $value >= $dayOfTheWeek;
        });
        if (empty($nextDate) && count($timeSlots) > 0) {
            $nextDate = Arr::first($timeSlots);
        }
        $defaultDate = date('Y-m-d');
        if ($nextDate) {
            $defaultDate = Carbon::parse(date('Y-m-d'))->next(StoreTiming::DAYS[$nextDate])->format('Y-m-d');
        }
        $response['default_date'] = getConvertedDateTime($defaultDate, false);
        $response['pickup_locations'] = $storeAddress;
        return apiresponse(config('constants.SUCCESS'), '', array_merge($response, paymentSummaryForApp()));
    }
    // public function calShippingTax(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'country_id' => 'required',
    //         'state_id' => 'required'
    //     ]);
    //     $validator->setAttributeNames([
    //         'country_id' => 'country',
    //         'state_id' => 'state'
    //     ]);
    //     if ($validator->fails()) {
    //         return apiresponse(config('constants.ERROR'), $validator->errors()->first());
    //     }
    //     $response = [];
    //     $countryId = $request->input('country_id');
    //     $stateId = $request->input('state_id');
    //     Cart::taxCalculate($countryId, $stateId);
    //     $response['cart_info'] = $this->getCartSummary(false);
    //     $response['total'] =  displayPrice(Cart::getTotal());
    //     return apiresponse(config('constants.SUCCESS'), "", $response);
    // }
}
