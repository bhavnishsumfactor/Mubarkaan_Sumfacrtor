<?php

namespace App\Http\Controllers;

use App\Http\Controllers\YokartController;
use Illuminate\Http\Request;
use Cart;
use Illuminate\Support\Facades\Validator;
use App\Models\Country;
use App\Models\State;
use App\Models\UserAddress;
use App\Models\User;
use App\Models\Product;
use App\Models\CouponHistory;
use App\Models\DiscountCoupon;
use App\Helpers\Cart\CartCondition;
use App\Models\UserRewardPoint;
use App\Models\Configuration;
use App\Models\Package;
use App\Models\UserCard;
use App\Models\StoreAddress;
use App\Models\StoreTiming;
use App\Models\UserTempTokenRequest;
use App\Models\SmsTemplate;
use App\Helpers\EmailHelper;
use App\Jobs\SendNotification;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use DB;
use Str;

class CheckoutController extends YokartController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $defaultState = '';
        $savedaddress = [];
        $defaultAddressId = '';
        if ($this->signed_in) {
            $savedaddress = UserAddress::getRecordByUserId($this->user->user_id);
            if ($defaultAddress = $savedaddress->where('addr_shipping_default', 1)->first()) {
                $defaultAddressId = $defaultAddress->addr_id;
            }
        }


        $countryCode = Str::upper(getDefaultCountryCode());
        $stateObj = Country::getStatesByCountryCode($countryCode, ['state_name', 'state_id']);
        $defaultState = $stateObj->first()->state_name;
        $states = $stateObj->pluck('state_name', 'state_id');

        $cartCollection = Cart::session($this->cartSession)->getContent();
        if ($cartCollection->count() == 0) {
            return redirect()->route('home');
        }
        $products = [];

        if (!empty($cartCollection)) {
            foreach ($cartCollection as $key => $collection) {
                $fields =  'prod_min_order_qty, prod_stock_out_sellable, prod_max_order_qty, pov_code, prod_id,' . Product::getPrice();
                $code = $key;
                if ($collection->product_id ===  $key) {
                    $code = 0;
                }

                $products[$key] = Product::productById($collection->product_id, $fields, [], $code);
            }
        }
        Cart::shippingUpdate(0, []);
        Cart::session($this->cartSession)->removeConditionsByType('rewardpoints');

        $result['cartCollection'] = $cartCollection;
        $result['products'] = $products;

        $result['countries'] = Country::all();
        $result['countryCode'] = $countryCode;
        $result['savedaddress'] = $savedaddress;
        $result['states'] = $states;
        $result['pickup'] = count(Cart::getPickups('name'));
        $result['defaultState'] = $defaultState;
        $result['isDigitalProduct'] = Cart::isDigitalProduct();
        $result['defaultAddressId'] = $defaultAddressId;


        $smsPackageCheck = Package::getPublishedPackages('sms');
        $result['smsActivePackage'] = $smsPackageCheck->count();
        return view('themes.' . config('theme') . '.checkout', $result);
    }
    
    public function getFirstStep()
    {
        $defaultState = '';
        $savedaddress = [];
        $defaultAddressId = '';
        if ($this->signed_in) {
            $savedaddress = UserAddress::getRecordByUserId($this->user->user_id);
            if ($defaultAddress = $savedaddress->where('addr_shipping_default', 1)->first()) {
                $defaultAddressId = $defaultAddress->addr_id;
            }
        }

        $countryCode = Str::upper(getDefaultCountryCode());
        $stateObj = Country::getStatesByCountryCode($countryCode, ['state_name', 'state_id']);
        $defaultState = $stateObj->first()->state_name;
        $states = $stateObj->pluck('state_name', 'state_id');

        $result['countries'] = Country::all();
        $result['countryCode'] = $countryCode;
        $result['savedaddress'] = $savedaddress;
        $result['states'] = $states;
        $result['pickup'] = count(Cart::getPickups('name'));
        $result['defaultState'] = $defaultState;
        $result['isDigitalProduct'] = Cart::isDigitalProduct();
        $result['defaultAddressId'] = $defaultAddressId;

        $cartCollection = Cart::session($this->cartSession)->getContent();
        $products = [];

        if (!empty($cartCollection)) {
            foreach ($cartCollection as $key => $collection) {
                $fields =  'prod_min_order_qty, prod_stock_out_sellable, prod_max_order_qty, pov_code, prod_id,' . Product::getPrice();
                $code = $key;
                if ($collection->product_id ===  $key) {
                    $code = 0;
                }
                $products[$key] = Product::productById($collection->product_id, $fields, [], $code);
            }
        }

        $outOFStock = [];
        $qty = '0';
        foreach ($products as $productId => $product) {
            if (isset($cartCollection[$productId])) {
                $qty = $cartCollection[$productId]['quantity'];
            }
            $outOFStock[$productId] = productStockVerify($product, $qty, $product->pov_code);
        }
        $result['outOFStock'] = $outOFStock;
        $hide = false;

        $smsPackageCheck = Package::getPublishedPackages('sms');
        $result['smsActivePackage'] = $smsPackageCheck->count();
        $result['hide'] = $hide;
        $data['html'] = view('themes.' . config('theme') . '.partials.checkoutFirstStep', $result)->render();
        return jsonresponse(true, null, $data);
    }

    public function calShippingTax(Request $request)
    {
        $countryId = $request->input('country-id');
        $stateId = $request->input('state-id');
        Cart::taxCalculate($countryId, $stateId);
        $result['cartInfo'] = $this->cartSummary();
        $result['total'] =  displayPrice(Cart::getTotal());
        return $result;
    }

    public function getAddressForm(Request $request, $addressId = 0)
    {
        if ($addressId != 0) {
            $userAddress = UserAddress::getAddressByid($addressId);
            $userAddress->states = State::where('state_country_id', $userAddress->addr_country_id)->pluck('state_name', 'state_id');

            return jsonresponse(true, '', $userAddress);
        }
    }
    public function getBillingAddress(Request $request)
    {
        $savedaddress = [];
        $defaultAddressId = '';
        if ($this->signed_in) {
            $savedaddress = UserAddress::getRecordByUserId($this->user->user_id);
            if ($defaultAddress = $savedaddress->where('addr_billing_default', 1)->first()) {
                $defaultAddressId = $defaultAddress->addr_id;
            }
        }
        $result['savedaddress'] = $savedaddress;
        $result['defaultAddressId'] = $defaultAddressId;

        $countryCode = Str::upper(getDefaultCountryCode());
        $stateObj = Country::getStatesByCountryCode($countryCode, ['state_name', 'state_id', 'state_country_id']);

        $result['countries'] = Country::all();
        $result['countryCode'] = $countryCode;
        $result['states'] = $stateObj->pluck('state_name', 'state_id');
        $result['defaultState'] = $stateObj->first()->state_name;
        $states = $stateObj->first();
        $result['digital'] = false;
        if (Cart::isDigitalProduct(false)) {
            $result['digital'] = true;
            Cart::taxCalculate($states->state_country_id, $states->state_id, true);
        }

        $records['cartInfo'] = $this->cartSummary();
        $records['defaultAddressId'] = $defaultAddressId;
        $html = view('themes.' . config('theme') . '.partials.billingAddress', $result)->render();
        $records['html'] = $html;
        return jsonresponse(true, '', $records);
    }

    public function updateTaxAmount(Request $request)
    {
        if ($request['address-id']) {
            $addressId = UserAddress::getAddressByid($request['address-id']);
            $countryId = $addressId->addr_country_id;
            $stateId = $addressId->addr_state_id;
        } else {
            $countryId = $request['country-id'];
            $stateId = $request['state-id'];
        }
        if (Cart::isDigitalProduct(false)) {
            Cart::taxCalculate($countryId, $stateId, true);
        }
        $records['cartInfo'] = $this->cartSummary();
        return jsonresponse(true, '', $records);
    }
    public function saveAddress(Request $request)
    {
        $records = $request->except(['shipping', 'addr_phone_country_code', 'addr_phone_mask', 'edited_addr_id', 'addressForm', 'selectedAddress']);
        if ($request['selectedAddress'] == "false" && $request['addressForm'] == "false") {
            return jsonresponse(false, __('Please select address!!'));
        }
        if ($request['selectedAddress'] == "true") {
            $insertedId = $request['addr_id'];
        } else {
            if (!empty($request['edited_addr_id'])) {
                $records['addr_id'] =  $request['edited_addr_id'];
            }
            $this->addressValidate($request->all())->validate();

            $country = Country::select('country_id')->where('country_code', strtoupper($request->input('addr_phone_country_code')))->first();
            $request['addr_phone_country_id'] = $records['addr_phone_country_id'] = $country->country_id;


            if ($this->signed_in) {
                $userId = $this->user->user_id;
            } else {
                $userId = User::generateUserId(Arr::only($records, ['addr_first_name', 'addr_last_name', 'addr_email', 'addr_phone', 'addr_phone_country_id']))->user_id;
            }
            $records['addr_user_id'] = $userId;
            if (!empty($records['addr_id']) && $request['selectedAddress'] == "true") {
                $insertedId = $records['addr_id'];
                UserAddress::where('addr_id', $insertedId)->update($records);
            } elseif (!empty($request['edited_addr_id']) && $request['selectedAddress'] == "false") {
                unset($records['addr_id']);
                $insertedId = $request['edited_addr_id'];
                UserAddress::where('addr_id', $insertedId)->update($records);
            } else {
                unset($records['addr_id']);
                $insertedId = UserAddress::create($records)->addr_id;
            }
        }
        if (isset($records['addr_id']) && !empty($records['addr_id'])) {
            $userAddress = UserAddress::getAddressByid($records['addr_id']);
            $records['addr_country_id'] =  $userAddress['addr_country_id'];
            $records['addr_state_id'] = $userAddress['addr_state_id'];
        }
        if ($coupon = Cart::session($this->cartSession)->getCondition('coupon')) {
            $historyId = $coupon->getId();
            $couponRecord = DiscountCoupon::where('discountcpn_code', $coupon->getCode())->first();
            if (empty(CouponHistory::CouponHistoryValidate($couponRecord, $this->user, $historyId))) {
                return jsonresponse(false, __('NOTI_COUPON_EXPIRED'));
            }
        }
        if (!empty($request['addr_billing_default'])) {
            UserAddress::where('addr_user_id', $userId)->where(['addr_billing_default' => 0]);
        }
        Cart::shippingUpdate(0, []);

        return $this->shippingSummary($records['addr_country_id'], $records['addr_state_id'], $insertedId);
    }

    public function shippingSummary($countryId, $stateId, $addressId)
    {
        $records['address'] = UserAddress::getAddressByid($addressId);
        $records['pickups'] = Cart::getPickups('name');
        $records['shippings'] = [];
        $pickupAddress = [];

        if (count($records['pickups']) > 0) {
            $pickupAddress = StoreAddress::getRecords([], false);
        } else {
            $records['shippings'] =  Cart::shippingCalculate($countryId, $stateId);
            $records['shippingAttributes'] = Cart::getShippingAttributes();
            $records['cartCollection'] = Cart::session($this->cartSession)->getContent();
        }
        Cart::taxCalculate($countryId, $stateId);
        $html['cartInfo'] = $this->cartSummary();
        $records['isDigitalProduct'] = Cart::isDigitalProduct();
        $records['pickupAddress'] = $pickupAddress;
        $html['shippingInfo'] = view('themes.' . config('theme') . '.partials.shippingSummary', $records)->render();
        $html['shippingCount'] = count($records['shippings']) ? count($records['shippings']) : 0;
        $html['total'] =  displayPrice(Cart::getTotal());
        $html['addressId'] =  $addressId;
        return jsonresponse(true, '', $html);
    }
    public function paymentSection(Request $request)
    {
        $selectedShippings = '';
        if (count(Cart::getPickups('name')) > 0) {
            if (empty($request->input('shipping-address')) && $request->input('shipping-address') == "") {
                return jsonresponse(false, __('NOTI_PLEASE_SELECT_SHIPPING_ADDRESS'));
            }
            if (empty($request->input('selectedDate')) && $request->input('selectedDate') == "") {
                return jsonresponse(false, __('NOTI_PLEASE_SELECT_PICKUP_DATE'));
            }
            if (empty($request->input('pickupSlot')) && $request->input('pickupSlot') == "") {
                return jsonresponse(false, __('NOTI_PLEASE_SELECT_SHIPPING_TIMING'));
            }
            $pickupRequest = $request->only('shipping-address', 'selectedDate', 'pickupSlot');
            $this->setPickupAddress($pickupRequest);
        } else {
            if (Cart::shippingErrors() == true) {
                return jsonresponse(false, __('NOTI_SHIPPING_NOT_AVAILABLE_FOR_PRODUCT'));
            }

            if (Cart::totalShippingProducts() != $request['shippings']) {
                return jsonresponse(false, __('NOTI_PLEASE_SELECT_SHIPPING'));
            }
            if ($request->input('shipping')) {
                $selectedShippings = implode(',', array_keys($request->input('shipping')));
            }
        }

        $points = [];
        $userCards = [];
        if ($this->signed_in) {
            $userId = $this->user->user_id;
            $points = UserRewardPoint::calculateRewardPoints(Cart::session($this->cartSession)->getSubTotal(), $userId);
            $userCards = UserCard::getCardsListing($userId);
        }
        
        $paymentGateways = Package::getActivePaymentGateways('paymentGateways');
        $result['creditCard'] = $paymentGateways->where('pkg_card_type', config('constants.YES'))->first();
    //    $result['creditCard'] = $paymentGateways->first();
        $result['paypalExpress'] = $paymentGateways->where('pkg_slug', 'PaypalExpress')->first();
        $result['paypal'] = $paymentGateways->where('pkg_slug', 'Paypal')->first();
        $result['cashFree'] = $paymentGateways->where('pkg_slug', 'CashFree')->first();
        $result['countryCode'] = '';
        $result['selectedShippings'] = $selectedShippings;
        $result['addressId'] = $request->input('address');
        $result['rewardPoints'] = $points;
        $result['userCards'] = $userCards;
        $result['states'] = [];
        $result['rewardApplied'] = (Cart::session($this->cartSession)->getCondition('rewardpoints')) ? 1 : 0;
        $minimumOrderTotal = getConfigValueByName('REWARD_POINTS_SPENDING_POINTS_MINIMUM_ORDER_TOTAL');
    
        $result['minimumOrderTotal'] = ($minimumOrderTotal) ? $minimumOrderTotal : 0;
        $result['countries'] = Country::all();
        $result['shippingSummary'] =  Cart::getShippingAttributes();
        $result['cartCollection'] = Cart::session($this->cartSession)->getContent();
        $result['address'] = UserAddress::getAddressByid($request->input('address'));
        $result['codAvailable'] = $this->codAvailable();
        $result['totalPrice'] = Cart::session($this->cartSession)->getTotal();
        $result['subTotal'] = Cart::session($this->cartSession)->getSubTotal();
        $result['isDigitalProduct'] = Cart::isDigitalProduct();
       
        $couponAmount = 0;
        if ($coupon = Cart::session($this->cartSession)->getCondition('coupon')) {
            $couponAmount = abs($coupon->getValue());
        }
        $result['orderSubtotalExcludingDiscount'] = ($result['subTotal'] - $couponAmount);
        
        $savedaddress = [];
        $defaultAddressId = '';
        if ($this->signed_in) {
            $savedaddress = UserAddress::getRecordByUserId($this->user->user_id);
            if ($defaultAddress = $savedaddress->where('addr_billing_default', 1)->first()) {
                $defaultAddressId = $defaultAddress->addr_id;
            }
        } 
        $result['savedaddress'] =  $savedaddress;
        $result['defaultAddressId'] =  $defaultAddressId;
       // print_r($result);die;
        $html['html'] = view('themes.' . config('theme') . '.partials.checkoutPaymentSection', $result)->render();
        $html['totalPrice'] = $result['totalPrice'];
        $html['total'] =  displayPrice(Cart::getTotal());

        return jsonresponse(true, null, $html);
    }
    
    private function setPickupAddress($pickupData)
    {
        $addressId = $pickupData['shipping-address'];
        $selectedDate = $pickupData['selectedDate'];
        $pickupSlot = $pickupData['pickupSlot'];

        $address = StoreAddress::getRecordById($addressId);

        $pickupAddress = $address->saddr_name . ' ' . $address->saddr_address . ' , ' . $address->saddr_postal_code . ' ' . $address->saddr_city . ' ' . $address->state_name . ' ' . $address->country_name;
        $shippingType = [];
        $shippingAttributes = Cart::getShippingAttributes();
        if (!empty($shippingAttributes['pick_ups'])) {
            unset($shippingAttributes['pick_ups']);
        }
        $pickup['pick_ups'] = ['address-id' => $addressId, 'address' => $pickupAddress, 'pickup_date' => $selectedDate, 'pickup_time' => $pickupSlot];
        $updatedAttributes = $shippingAttributes +  $pickup;

        Cart::shippingUpdate(0, $updatedAttributes);
    }
    public function codAvailable()
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
    public function pickupTimeSlots(Request $request)
    {
        $dayOfTheWeek = Carbon::parse($request->input('date'))->dayOfWeek;
        $pickupSlots = StoreTiming::getTimings($request->input('addrerss'), $dayOfTheWeek,$request->input('date'));
        $html = view('themes.' . config('theme') . '.partials.pickupSlots', compact('pickupSlots'))->render();
        return jsonresponse(true, null, ['timeSlots' => $html]);
    }
    public function applyRewardPoints(Request $request)
    {
        $request->validate([
            'points' => 'required|integer'
        ]);
        $config = Configuration::getRecordsByPrefix('REWARD_POINTS');
        $pointWorth = ($config['REWARD_POINTS_SPENDING_POINTS_AMOUNT'] / $config['REWARD_POINTS_SPENDING_POINTS_EARNINGS']);
        $points = $request->input('points');
        $subTotal = Cart::session($this->cartSession)->getSubTotal();
        $totalPoints = UserRewardPoint::calculateRewardPoints($subTotal, $this->user->user_id);
        if ($subTotal < $totalPoints['minOrderTotal']) {
            return jsonresponse(false, __('NOTI_INSUFFICIENT_SUBTOTAL_FOR_REDEEM'));
        } elseif ($totalPoints['totalPoints'] < $points) {
            return jsonresponse(false, __('NOTI_INSUFFICIENT'));
        } elseif ($totalPoints['minUsePoints'] > $points) {
            return jsonresponse(false, __('NOTI_PLEASE_ENTER_GREATER_OR_EQUAL') .' '. $totalPoints['minUsePoints'] . ' '. __('NOTI_POINTS'));
        } elseif ($totalPoints['maxUsablePoints'] < $points) {
            return jsonresponse(false, __('NOTI_PLEASE_ENTER_LESS_THAN_EQUAL') .' '. $totalPoints['maxUsablePoints'] . ' '. __('NOTI_POINTS'));
        } elseif (($points * $pointWorth) > Cart::session($this->cartSession)->getTotal()) {
            return jsonresponse(false, __('NOTI_APPLIED_REWARD_POINT_EXCEED_THE_ORDER'));
        }

        $totalAmount = $pointWorth * $points;
        Cart::updateRewardPoints($totalAmount, $points);

        return jsonresponse(true, '', ['cartInfo' => $this->cartSummary(), 'total' => Cart::getTotal(), 'canEarn' => $config['REWARD_POINTS_EARN_ON_REDEEMED_ORDER']]);
    }
    public function removeRewardPoints(Request $request)
    {
        Cart::session($this->cartSession)->removeConditionsByType('rewardpoints');
        return jsonresponse(true, '', ['cartInfo' => $this->cartSummary()]);
    }
    protected function addressValidate(array $data)
    {
        $validator = Validator::make($data, [
            'addr_email' => ['required', 'email'],
            'addr_first_name' => ['required', 'string', 'max:191'],
            'addr_last_name' => ['required', 'string', 'max:191'],
            'addr_title' => ['required', 'string', 'max:191'],
            // 'addr_apartment' => ['required', 'string', 'max:191'],
            'addr_address1' => ['required'],
            'addr_city' => ['required'],
            'addr_country_id' => ['required', 'integer'],
            'addr_state_id' => ['required', 'integer'],
            'addr_postal_code' => ['required'],
            'addr_phone' => ['required'],
        ]);

        return $validator->setAttributeNames([
            'addr_email' => 'email address',
            'addr_first_name' => 'first name',
            'addr_last_name' => 'last name',
            'addr_title' => 'title',
            //'addr_apartment' => 'apartment',
            'addr_address1' => 'address',
            'addr_city' => 'city',
            'addr_country_id' => 'country',
            'addr_state_id' => 'state',
            'addr_postal_code' => 'pin code',
            'addr_phone' => 'phone',
        ]);
    }

    public function getStates(Request $request)
    {
        $records = State::where('state_country_id', $request->input('country-id'))->pluck('state_name', 'state_id');
        return jsonresponse(true, null, $records);
    }
    public function cartSummary()
    {
        $cartConditions = Cart::session($this->cartSession)->getConditions();
        $pickup  = count(Cart::getPickups('name'));


        $couponAmount = 0;
        if ($coupon = Cart::session($this->cartSession)->getCondition('coupon')) {
            $couponAmount = abs($coupon->getValue());
        }
        $cartPage = false;
        $displayRewardPointOnEarn = getConfigValueByName('REWARD_POINTS_PURCHASE_POINTS_DISPLAY_EARNED');
        $earnRewardPoints = UserRewardPoint::calculateEarnRewardPoints(Cart::session($this->cartSession)->getSubTotal(), $couponAmount);
        $digitalProducts  = Cart::isDigitalProduct();
        
        return view('themes.' . config('theme') . '.partials.cartSummary', compact('cartConditions', 'pickup', 'displayRewardPointOnEarn', 'earnRewardPoints', 'digitalProducts', 'cartPage'))->render();
    }

    public function pickupDays(Request $request, $id)
    {
        $days = StoreTiming::getStoreWeekDays($id);
        $defaultDate = false;
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
            $defaultDate = Carbon::now()->setTimezone($configuration['ADMIN_TIMEZONE'])->addHours($pickupAvailableAfter)->format('Y-m-d');
        }
        return jsonresponse(true, null, compact('days', 'defaultDate'));
    }



    public function shippingUpdate(Request $request)
    {
        $shippingAttributes = Cart::getShippingAttributes();
        unset($shippingAttributes['shipping']);
        $updatedAttributes = $shippingAttributes +  ['shipping' => $request->input('shipping-attributes')];
        Cart::shippingUpdate(array_sum($request->input('shipping-val')), $updatedAttributes);
        return $this->cartSummary();
    }

    public function requestOtp(Request $request)
    {
        $address = UserAddress::getRecordById($request->input('address-id'))->toArray();
        if (!empty($address['addr_user_id'])) {
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
                return jsonresponse(true, __('NOTI_SUCCESSFULLY_SENT'), "Enter OTP sent at " . $message);
            } catch (Exception $e) {
                return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
                DB::rollBack();
            }
        }
        return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
    }

    public function resendOtpRequest(Request $request)
    {
        $address = UserAddress::getRecordById($request->input('address-id'))->toArray();
        if (!empty($address['addr_user_id'])) {
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
                return jsonresponse(true, __('NOTI_SUCCESSFULLY_SENT'));
            } catch (Exception $e) {
                return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
                DB::rollBack();
            }
        }
    }

    public function verifyOtpRequest(Request $request)
    {
        $address = UserAddress::getRecordById($request->input('address-id'))->toArray();
        if (!empty($address['addr_user_id']) && $request->input('otp') != '') {
            if (
                UserTempTokenRequest::where('uttr_user_id', $address['addr_user_id'])->where('uttr_type', UserTempTokenRequest::ORDER_OTP_REQUEST)
                ->where('uttr_code', $request->input('otp'))->where('uttr_expiry', '>=', Carbon::now())->count() > 0
            ) {
                return jsonresponse(true, __('NOTI_OTP_VERIFY_SUCCESSFULLY'));
            } else {
                return jsonresponse(false, __('NOTI_VERIFICATION_CODE_INCORRECT'));
            }
        } else {
            return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
        }
    }

    public function addNewCard(Request $request)
    {
        $addCard = view('themes.' . config('theme') . '.checkout.addCardPopup')->render();
        return jsonresponse(true, '', $addCard);
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
}
