<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\YokartController;
use App\Models\Product;
use App\Models\ProductStockHold;
use App\Models\Country;
use App\Models\UserSavedProduct;
use App\Models\StoreAddress;
use App\Models\ProductOption;
use App\Models\CouponHistory;
use App\Models\UserRewardPoint;
use App\Models\DiscountCoupon;
use App\Models\DiscountCouponRecord;
use App\Models\UserCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Cart;
use Str;
use Auth;

class CartController extends YokartController
{
    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prod_id' => 'required',
            'qty' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $productId = $request->input('prod_id');

        $variantCode = $request->input('pov_code');
        if (empty($variantCode) || $variantCode == "null") {
            $variantCode = 0;
        }

        $fields =  'prod_id, prod_name, prod_price, pov_default, prod_self_pickup, prod_stock_out_sellable, prod_min_order_qty, pov_code, prod_max_order_qty, ' . Product::getPrice();
        
        $product = Product::productById($productId, $fields, [], $variantCode, true);

        if (empty($product)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_PRODUCT_NO_LONGER_AVAILABLE"));
        }
        $qty = ($product->prod_min_order_qty > 0) ? $product->prod_min_order_qty : 1;

        if ($request->input('qty')) {
            $qty = $request->input('qty');
        }

        if ($qty < $product->prod_min_order_qty) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_MIN_PURCHASE_QTY_SHOULD_BE") . ' ' . $qty);
        }
        $varientsResults = [];
        $code =  $product->prod_id;
        if (!empty($variantCode)) {
            $varientsResults['styles'] = ProductOption::optionsByCode($product->prod_id, $variantCode);
            if (substr($variantCode, -1) != "|") {
                $variantCode = $variantCode . '|';
            }
            $varientsResults['code'] = $variantCode;
            $code =  $variantCode;
        }
      
        $stock = productStockVerify($product, $qty, $variantCode);

        if ($stock == false) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_PRODUCT_OUT_OF_STOCK"));
        }
        $cartItem = Cart::session($this->cartSession)->getContent()->byKey($code);
        if (count($cartItem) > 0) {
            if ($qty + $cartItem['quantity'] > $product->prod_max_order_qty) {
                return apiresponse(config('constants.ERROR'), appLang("NOTI_MAX_PURCHASE_QTY_SHOULD_BE") . ' ' . $product->prod_max_order_qty);
            }
        }
        $shipType = "shipping";
        if ($product->prod_self_pickup == 2) {
            $shipType = "pickup";
        }
        ProductStockHold::storeProductOnHold($product->prod_id, $qty, true, $variantCode, $this->cartSession);

        Cart::session($this->cartSession)->add($product->prod_id, $product->prod_name, priceFormat($product->finalprice), $qty, $code, $shipType, $varientsResults);
        $currentItem = [
            'content_name' => $product->prod_name,
            'currency' => currencyCode(),
            'value' => $product->finalprice
        ];
        $countryCode = Str::upper(getDefaultCountryCode());
        $countryData = Country::getStatesByCountryCode($countryCode, ['country_id', 'state_id'])->first();
        
        Cart::session($this->cartSession)->taxUpdate($countryData->country_id, $countryData->state_id);
        $response = array_merge(['currentItem' => $currentItem], paymentSummaryForApp());
        UserCart::updateMobileDeviceType($this->cartSession);
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_ITEM_ADDED_TO_CART"), $response);
    }

    public function list(Request $request, $type = "")
    {
        if (!empty($type)) {
            return $this->updateCartType($request, $type);
        }
        $cartCollection = Cart::session($this->cartSession)->getContent();
        $records = $this->cartProductListing($request, $cartCollection);
        $savedProducts = $this->savedProductListing(UserSavedProduct::SAVED_PRODUCTS);
        $records['saved_products'] = $savedProducts;
        $records['cart_collection_count'] = $cartCollection->count();
        $records['is_all_digital'] = Cart::isDigitalProduct(true);
        $response = array_merge($records, paymentSummaryForApp());
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }

    public function saveForLater(Request $request)
    {
        $prodId  = $request->input('prod_id');
        $code  = !empty($request->input('pov_code') && $request->input('pov_code') != "null") ? $request->input('pov_code') : '';
      
        $userId  = Auth::guard('api')->user()->user_id;
        $records = ['usp_prod_id' => $prodId, 'usp_user_id' => $userId, 'usp_pov_code' => $code];
        $record = UserSavedProduct::where($records)->first();
        $recordId = 0;
        if (!empty($record)) {
            if ($record->usp_temp == UserSavedProduct::TEMP_SAVED_PRODUCTS) {
                UserSavedProduct::where($records)->update(['usp_temp' => UserSavedProduct::SAVED_PRODUCTS]);
            }
            $recordId  = $record->usp_id;
        } else {
            $recordId  =  UserSavedProduct::create($records)->usp_id;
        }
        $cartId = !empty($code) ? $code : $prodId;
        $this->deleteCartItem($request, $cartId);
        Cart::taxUpdate();
        
        $savedProducts = $this->savedProductListing(UserSavedProduct::SAVED_PRODUCTS, $recordId);
    
        $updatedSummary = array_merge(['saved_products' => $savedProducts], paymentSummaryForApp());
        return apiresponse(config('constants.SUCCESS'), appLang('NOTI_PRODUCT_SAVED_FOR_LATER'), $updatedSummary);
    }

    public function moveToBag(Request $request)
    {
        $shipType = ($request->input('ship_type') ? $request->input('ship_type') : 'shipping');
        $product = Product::getSavedProductById($request->input('usp_id'));
        if (empty($product)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_PRODUCT_NO_LONGER_AVAILABLE"));
        }
        
        $cartUpdate = $this->productCartUpdated($product, $shipType);
        
        if ($cartUpdate['status'] == false) {
            return apiresponse(config('constants.ERROR'), $cartUpdate['message']);
        }

        UserSavedProduct::where('usp_id', $request->input('usp_id'))->delete();
        Cart::taxUpdate();
        $savedProducts = $this->savedProductListing(UserSavedProduct::SAVED_PRODUCTS);
        $code = $product->pov_code ? $product->pov_code : $product->prod_id;

        $cartItem = Cart::session($this->cartSession)->getContent()->byKey($code);
 
        $cartItem['product'] = $product;
        $updatedSummary = array_merge([
            'cart_collection' => [$cartItem]
        ], paymentSummaryForApp());
      
        return apiresponse(config('constants.SUCCESS'), appLang('NOTI_PRODUCT_MOVED_TO_BAG'), $updatedSummary);
    }

    private function productCartUpdated($product, $shipType, $validateShipping = true)
    {
       $code = $product->pov_code ? $product->pov_code : $product->prod_id;
        $cartItem = Cart::session($this->cartSession)->getContent()->byKey($code);
        $qty = ($product->prod_min_order_qty > 0) ? $product->prod_min_order_qty : 1;
        $cartQty = $qty;
        if (!empty($cartItem)) {
            $qty = $qty + $cartItem['quantity'];
        }
        if ($qty > $product->prod_max_order_qty) {
            return ['status' => false, 'message' => appLang("NOTI_MAXIMUM_PURCHASE_QUANTITY") . ' ' . $product->prod_max_order_qty];
        }
        $stock = productStockVerify($product, $qty, $product->pov_code);
       
        if ($stock == false) {
            return ['status' => false, 'message' => appLang("NOTI_PRODUCT_OUT_OF_STOCK")];
        }
        
        if ($product->prod_self_pickup != 3  && $validateShipping == true) {
            if ($shipType == 'shipping' && !in_array($product->prod_self_pickup, [0, 1])) {
                return ['status' => false, 'message' => appLang("NOTI_PRODUCT_NOT_AVAILABLE_FOR_SHIPPING")];
            }
            if ($shipType == 'pickup' && $product->prod_self_pickup != 2) {
                return ['status' => false, 'message' => appLang("NOTI_PRODUCT_NOT_AVAILABLE_FOR_PICKUP")];
            }
        }

        $code =  $product->prod_id;
        $varientsResults = [];

        if (!empty($product->pov_code)) {
            $varientsResults['styles'] = ProductOption::optionsByCode($product->prod_id, $product->pov_code);
            $code =  $product->pov_code;
            if (substr($code, -1) != "|") {
                $code = $code . '|';
            }
            $varientsResults['code'] = $code;
        }
    
        ProductStockHold::storeProductOnHold($product->prod_id, $cartQty, true, $product->pov_code, $this->cartSession);
        Cart::session($this->cartSession)->add($product->prod_id, $product->prod_name, $product->finalprice, $cartQty, $code, $shipType, $varientsResults);
        return ['status' => true, 'message' => appLang("NOTI_PRODUCT_ADDED_SUCCESSFULLY")];
    }

    public function getCoupons(Request $request)
    {
        $userId = 0;
        if (Auth::guard('api')->check()) {
            $userId = Auth::guard('api')->user()->user_id;
        }

        $coupons = DiscountCoupon::getActiveRecords($userId);
        $cartCollection = Cart::session($this->cartSession)->getContent();
        foreach ($coupons as $key => $coupon) {
            if ($coupon->couponConditions->count() > 0) {
                $validCoupon = false;
                if($coupon)
                    $validCoupon = isValidCoupon($coupon, $cartCollection, $userId);
                if ($validCoupon == false) {
                    unset($coupons[$key]);
                }
            }
            $coupon->is_exist = isExpiredCoupon($coupon);
        }
        
        return apiresponse(config('constants.SUCCESS'), '', ['coupons' => array_values($coupons->toArray())]);
    }

    public function updateGiftItem(Request $request, $validate = true, $cartId = 0, $update = false, $oldPrice = 0)
    {
        if (empty($cartId)) {
            $cartId = $request->input('id');
        }
        $cartObj =  Cart::session($this->cartSession);
        $attributes = $cartObj->getContent()->get($cartId)['attributes']->getItems();

        if (isset($attributes['message'])) {
            unset($attributes['message']);
        }
     
        $quantity = $cartObj->getContent()->get($cartId)['quantity'];

        $cartGiftPrice = ($cartObj->getCondition('gift')) ? $cartObj->getCondition('gift')->getValue() : 0;
        $totalProduct = ($cartObj->getCondition('gift')) ? $cartObj->getCondition('gift')->getTotalProducts() : 0;

        $giftPrice = getConfigValueByName('PRODUCT_GIFT_WRAP_PRICE');


        if (isset($attributes['gift']) && $update == true) {
            
            $giftArray = ['gift' => $attributes['gift']];
            $cartGiftPrice = ($cartGiftPrice - $oldPrice) + $giftPrice * $quantity;
            $totalProduct = $totalProduct;
        } elseif (isset($attributes['gift'])) {
               unset($attributes['gift']);
            $giftArray = [];
            $cartGiftPrice = $cartGiftPrice - $giftPrice * $quantity;
            $totalProduct = $totalProduct - 1;
        } else {
            if ($validate) {
                $validator = Validator::make($request->all(), [
                    'id' => 'required',
                    'from' => 'required',
                    'to' => 'required',
                    'message' => 'required'
                ]);
                if ($validator->fails()) {
                    return apiresponse(config('constants.ERROR'), $validator->errors()->first());
                }
            }
          
            $messages = $request->except('cartId');
            $giftArray = ['gift' => ['message' => $messages]];
            $cartGiftPrice = $cartGiftPrice + $giftPrice * $quantity;
            $totalProduct = $totalProduct + 1;
        }
        $newArray = array_merge($attributes, $giftArray);
        $cartObj->update(
            $cartId,
            array('attributes' => $newArray)
        );

        if ($totalProduct != 0) {
            if (count($giftArray) == 0) {
                $giftmessages = Cart::session($this->cartSession)->getCondition('gift');
                $giftArray =  $giftmessages->getAttributes();
            }
            Cart::giftWrapUpdate($cartGiftPrice, $totalProduct, $giftArray);
        } else {
            Cart::removeConditionsByType('gift');
        }
      //  print_r((Cart::session($this->cartSession)->getCondition('gift')));
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_GIFT_WRAP_UPDATED"), paymentSummaryForApp());
    }

    public function giftItemMessages()
    {
        $giftmessages = Cart::session($this->cartSession)->getCondition('gift');
        $message = [];
        if ($giftmessages && isset($giftmessages->getAttributes()['gift'])) {
            $message =  $giftmessages->getAttributes()['gift']['message'];
        }
        return apiresponse(config('constants.SUCCESS'), '', compact('message'));
    }

    private function updateCartType(Request $request, $type)
    {
        $cartCollection = Cart::session($this->cartSession)->getContent();

        foreach ($cartCollection as $key => $collection) {
            Cart::session($this->cartSession)->update(
                $key,
                array('shipType' => $type)
            );
        }
        $shippingType = Product::PICKUP_ONLY;
        if ($type == 'shipping') {
            $shippingType = Product::SHIPPING_ONLY;
        }
        $savedProducts = $this->savedProductListing(UserSavedProduct::TEMP_SAVED_PRODUCTS, $shippingType);

        foreach ($savedProducts as $product) {
            $this->productCartUpdated($product, $type, false);
            UserSavedProduct::where('usp_id', $product->usp_id)->delete();
        }
        Cart::taxUpdate();
        if (!empty(Cart::session($this->cartSession)->getCondition('coupon'))) {
            if ($coupon = Cart::session($this->cartSession)->getCondition('coupon')) {
                CouponHistory::where('couponhistory_id', $coupon->getId())->delete();
                Cart::removeConditionsByType('coupon');
                $this->applyCoupon($request, $coupon->getCode());
            }
        }
        $cartCollection = Cart::session($this->cartSession)->getContent();
       
        $records = $this->cartProductListing($request, $cartCollection);
        $savedProducts = $this->savedProductListing(UserSavedProduct::SAVED_PRODUCTS);
        $records['saved_products'] = $savedProducts;
        $records['cart_collection_count'] = $cartCollection->count();
        $response = array_merge($records, paymentSummaryForApp());

        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_CART_UPDATED"), $response);
    }

    public function updateCart(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'qty' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $cartId = $request->input('id');
        $qty = $request->input('qty');

        $cartData = Cart::session($this->cartSession)->getContent()->get($cartId);
       
        $prodId = $cartData['product_id'];
        $variantCode = 0;
        if (trim($prodId) !== trim($cartId)) {
            $variantCode = $cartId;
        }

        $fields = 'prod_id, prod_stock_out_sellable, prod_min_order_qty, prod_max_order_qty, ' . Product::getPrice();
        $product = Product::productById($prodId, $fields, [], $variantCode);

        $cartQty = $cartData['quantity'];
        $attributes  = $cartData['attributes']->getItems();
        if ($qty > $product->prod_max_order_qty) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_MAX_PURCHASE_QTY_SHOULD_BE") . ' ' . $cartQty, ['quantity' => $cartQty]);
        }
        if ($qty < $product->prod_min_order_qty) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_MIN_PURCHASE_QTY_SHOULD_BE") . ' ' . $cartQty, ['quantity' => $cartQty]);
        }
        $stock = productStockVerify($product, $qty, $variantCode);
        if ($stock == false) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_PRODUCT_OUT_OF_STOCK"), ['quantity' => $cartQty]);
        }
        Cart::session($this->cartSession)->update(
            $cartId,
            array(
                'quantity' => array(
                    'relative' => false,
                    'value' => $qty
                )
            )
        );
        ProductStockHold::storeProductOnHold($prodId, $qty, false, $variantCode, $this->cartSession);
        if (!empty(Cart::session($this->cartSession)->getCondition('coupon'))) {
            if ($coupon = Cart::session($this->cartSession)->getCondition('coupon')->getCode()) {
                $this->applyCoupon($request, $coupon);
            }
        }
        
        if (isset($attributes['gift'])) {
            $giftPrice = getConfigValueByName('PRODUCT_GIFT_WRAP_PRICE');
            $this->updateGiftItem($request, false, $cartId, true, $giftPrice * $cartQty);
        }
        Cart::taxUpdate();
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_PRODUCT_QUANTITY_SUCCESSFULLY"), paymentSummaryForApp());
    }

    public function removeFromCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $deleted = $this->deleteCartItem($request, $request->input('id'));
        if ($deleted === false) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_ITEM_NOT_IN_CART"));
        }
        Cart::taxUpdate();
        if (!empty(Cart::session($this->cartSession)->getCondition('coupon'))) {
            if ($coupon = Cart::session($this->cartSession)->getCondition('coupon')) {
                CouponHistory::where('couponhistory_id', $coupon->getId())->delete();
                Cart::removeConditionsByType('coupon');
                $this->applyCoupon($request, $coupon->getCode());
            }
        }
        $response = paymentSummaryForApp();
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_ITEM_REMOVED_FROM_CART"), $response);
    }

    public function applyCoupon(Request $request, $coupon = "")
    {
        if (empty($coupon)) {
            $validator = Validator::make($request->all(), [
                'coupon_code' => 'required'
            ]);
            if ($validator->fails()) {
                return apiresponse(config('constants.ERROR'), $validator->errors()->first());
            }
            $couponCode = $request->input('coupon_code');
        } else {
            $couponCode = $coupon;
        }
        $userId = 0;
        if (Auth::guard('api')->check()) {
            $userId = Auth::guard('api')->user()->user_id;
        }
        $historyId = "";
        $record = DiscountCoupon::getRecordByCouponCode($couponCode, $userId);
        $couponExisting = Cart::session($this->cartSession)->getCondition('coupon');
        if ($couponExisting) {
            $historyId = $couponExisting->getId();
        }
        if (empty($record)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_COUPON_INVALID"));
        }
        $subtotal = Cart::session($this->cartSession)->getSubTotal();
        if ($subtotal < $record->discountcpn_minorderamt) {
            if ($couponExisting) {
                Cart::applyCoupon($couponCode, 0, $historyId, [], true);
            }
            return apiresponse(config('constants.ERROR'), appLang("NOTI_MINIMUM_ORDER_AMOUNT_SHOULD_BE"). displayPrice($record->discountcpn_minorderamt));
        }

        if (empty(CouponHistory::CouponHistoryValidate($record, $userId))) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_COUPON_EXPIRED"));
        }

        

        if (count($record->couponConditions) == 0) {
            $amount = $this->calculateCouponAmount($record);
            if (empty($historyId)) {
                $historyId = $this->saveCoupon($record->discountcpn_id, $amount);
            }
            Cart::applyCoupon($couponCode, $amount, $historyId);
            Cart::taxUpdate();
            return apiresponse(config('constants.SUCCESS'), appLang("NOTI_COUPON_APPLIED_SUCCESSFULLY"), paymentSummaryForApp());
        }

        $cartCollection = Cart::session($this->cartSession)->getContent();
        $products = [];
        if (!empty($cartCollection)) {
            foreach ($cartCollection as $key => $collection) {
                $fields =  'prod_id, brand_id,cat_id,' . Product::getPrice();
                $code = $key;
                if ($collection->product_id ===  $key) {
                    $code = 0;
                }
                $products[$key] = Product::productById($collection->product_id, $fields, [], $code);
            }
        }

        $discountTypes = DiscountCouponRecord::DISCOUNT_RECORD_TYPE;
        $attributes = [];
        $amount = $record->discountcpn_amount;

        $couponExist = [];

        foreach ($products as $productKey => $product) {
            if (DiscountCoupon::COUPON_PERCENT_TYPE == $record->discountcpn_in_percent) {
                $taxDiscount = getConfigValueByName('IS_DISCOUNT_ON_TAX');
                $price = $cartCollection[$productKey]['price'];

                if ($taxDiscount == 1) {
                    $price = $product->finalprice;
                }
                $amount = ($price * $cartCollection[$productKey]['quantity']) * $record->discountcpn_amount / 100;
            }

            foreach ($discountTypes as $key => $discountType) {
                $conditionobj = $record->couponConditions->where('dcr_type', $key);

                switch ($discountType) {

                    case 'users':

                        if ($conditionobj->count() > 0) {
                            if ($this->signed_in  && $conditionobj->where('dcr_record_id', $userId)->count() > 0) {
                                $couponExist["users"][] = true;
                            } else {
                                $couponExist["users"][] = false;
                            }
                        }

                        break;
                    case 'products':
                        if ($conditionobj->count() > 0) {
                            $records = $conditionobj->where('dcr_record_id', $product['prod_id'])->pluck('dcr_subrecord_id')->toArray();

                            if (count($records) > 0) {
                                $code = $productKey;
                                if ($code != 0 && substr($code, -1) != "|") {
                                    $code = $code . '|';
                                }

                                if (in_array(0, $records) || in_array($code, $records)) {
                                    $couponExist["products"][] = true;
                                    $attributes[$productKey] = $amount;
                                } else {
                                    $couponExist["products"][] = false;
                                }
                            }
                        }


                        break;
                    case 'brands':
                        if ($conditionobj->count() > 0) {
                            if ($conditionobj->where('dcr_record_id', $product['brand_id'])->count() > 0) {
                                $couponExist["brands"][] = true;
                                $attributes[$productKey] = $amount;
                            } else {
                                $couponExist["brands"][] = false;
                            }
                        }

                        break;
                    case 'categories':
                        if ($conditionobj->count() > 0) {
                            if ($conditionobj->where('dcr_record_id', $product['cat_id'])->count() > 0) {
                                $couponExist["categories"][] = true;
                                $attributes[$productKey] = $amount;
                            } else {
                                $couponExist["categories"][] = false;
                            }
                        }

                        break;
                }
            }
        }
        if ($couponExist  > 0) {
            if ($record->discountcpn_operator == DiscountCoupon::AND_CONDITION) {
                $condition = [];
                foreach ($couponExist as $key => $coupon) {
                    if (in_array(true, $coupon)) {
                        $condition[] = true;
                    }
                }
                if (count($condition) != count($couponExist)) {
                    return apiresponse(config('constants.ERROR'), appLang("NOTI_COUPON_INVALID"));
                }
            }
            if (array_key_exists("users", $couponExist)) {
                $amount = $this->calculateCouponAmount($record);
                if (empty($historyId)) {
                    $historyId = $this->saveCoupon($record->discountcpn_id, $amount);
                }
                Cart::applyCoupon($couponCode, $amount, $historyId);
                Cart::taxUpdate();
                return apiresponse(config('constants.SUCCESS'), appLang("NOTI_COUPON_APPLIED_SUCCESSFULLY"), paymentSummaryForApp());
            }
        }
        if (count($attributes) == 0) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_COUPON_INVALID"));
        }
        $totalAmount = array_sum($attributes);

        $couponAmount = 0;
        if ($totalAmount  > $record->discountcpn_maxamt_limit && $record->discountcpn_in_percent == DiscountCoupon::COUPON_PERCENT_TYPE) {
            $totalAmount = $record->discountcpn_maxamt_limit;
            $couponAmount = $totalAmount / count($attributes);
        }
        if ($totalAmount  > $record->discountcpn_amount && $record->discountcpn_in_percent == DiscountCoupon::COUPON_FLAT_TYPE) {
            $totalAmount = $record->discountcpn_amount;
            $couponAmount = $totalAmount / count($attributes);
        }
        $this->updateCartAttributes($attributes, $couponCode, $couponAmount);
        if (empty($historyId)) {
            $historyId = $this->saveCoupon($record->discountcpn_id, $amount);
        }
        Cart::applyCoupon($couponCode, $totalAmount, $historyId, $attributes);
        Cart::taxUpdate();
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_COUPON_APPLIED_SUCCESSFULLY"), paymentSummaryForApp());
    }

    public function updateCartAttributes($attributes, $couponCode, $couponAmount)
    {
        foreach ($attributes as $key => $value) {
            $attributes = Cart::session($this->cartSession)->getContent()->get($key)['attributes']->getItems();

            $couponArray = array('coupon' => array(
                'name' => $couponCode,
                'type' => 'coupon',
                'value' => ($couponAmount != 0) ? $couponAmount : $value,
            ));
            $newArray = array_merge($attributes, $couponArray);
            Cart::session($this->cartSession)->update(
                $key,
                array('attributes' => $newArray)
            );
        }
    }

    public function saveCoupon($couponId, $amount)
    {
        $userId = 0;
        if (Auth::guard('api')->check()) {
            $userId = Auth::guard('api')->user()->user_id;
        }

        $records = [
            'couponhistory_coupon_id' => $couponId,
            'couponhistory_user_id' => $userId,
            'couponhistory_amount' => $amount,
            'couponhistory_added_on' => Carbon::now(),
        ];
        if ($coupon = Cart::session($this->cartSession)->getCondition('coupon')) {
            $historyId = $coupon->getId();
            CouponHistory::where('couponhistory_id', $historyId)->update($records);
            return  $historyId;
        }

        return CouponHistory::create($records)->couponhistory_id;
    }

    public function calculateCouponAmount($record)
    {
        $amount = $record->discountcpn_amount;
        if (DiscountCoupon::COUPON_PERCENT_TYPE == $record->discountcpn_in_percent) {
            $taxDiscount = getConfigValueByName('IS_DISCOUNT_ON_TAX');
            $totalTax = 0;
            if ($taxDiscount == 1) {
                $totalTax = Cart::session($this->cartSession)->getCondition('tax')->getValue();
            }
            $amount = (Cart::session($this->cartSession)->getSubTotal() + $totalTax) * $record->discountcpn_amount / 100;
        }
        if ($amount > $record->discountcpn_maxamt_limit && DiscountCoupon::COUPON_PERCENT_TYPE == $record->discountcpn_in_percent) {
            $amount = $record->discountcpn_maxamt_limit;
        } elseif (DiscountCoupon::COUPON_FLAT_TYPE == $record->discountcpn_in_percent) {
            $amount = $record->discountcpn_amount;
        }
        return $amount;
    }
    public function removeCoupon(Request $request)
    {
        $couponApplied = Cart::session($this->cartSession)->getCondition('coupon');
        if (empty($couponApplied)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_NO_COUPON_APPLIED"));
        }
        $cartCollection = Cart::session($this->cartSession)->getContent();
        $products = [];
        if (!empty($cartCollection)) {
            foreach ($cartCollection as $key => $collection) {
                $attributes = $collection['attributes']->getItems();
                if (isset($attributes['coupon'])) {
                    unset($attributes['coupon']);
                }
                Cart::session($this->cartSession)->update(
                    $key,
                    array('attributes' => $attributes)
                );
            }
        }
        foreach ($products as $productKey =>  $value) {
            $attributes = Cart::session($this->cartSession)->getContent()->get($productKey)['attributes']->getItems();
            if (isset($attributes['coupon'])) {
                unset($attributes['coupon']);
            }
            Cart::session($this->cartSession)->update(
                $value,
                array('attributes' => $attributes)
            );
        }

        if ($historyId = Cart::session($this->cartSession)->getCondition('coupon')->getId()) {
            CouponHistory::where('couponhistory_id', $historyId)->delete();
        }
        Cart::session($this->cartSession)->removeConditionsByType('coupon');
        Cart::taxUpdate();
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_COUPON_REMOVED"), paymentSummaryForApp());
    }

    /*public function updateCartPageSummary()
    {
        $cart_conditions = Cart::session($this->cartSession)->getConditions();

        $pickup  = count(Cart::getPickups('name'));
        $couponAmount = 0;
        if ($coupon = Cart::session($this->cartSession)->getCondition('coupon')) {
            $couponAmount = abs($coupon->getValue());
        }
        $out_of_stock = Cart::isOutOfStockProducts();
        $display_reward_point_on_earn = getConfigValueByName('REWARD_POINTS_PURCHASE_POINTS_DISPLAY_EARNED');
        $earn_reward_points = UserRewardPoint::calculateEarnRewardPoints(Cart::session($this->cartSession)->getSubTotal(), $couponAmount);
        $digital_products  = Cart::isDigitalProduct();

        $data['cart_info'] = compact(
            'cart_conditions',
            'out_of_stock',
            'pickup',
            'digital_products',
            'display_reward_point_on_earn',
            'earn_reward_points'
        );
        $data['cart_items'] = Cart::cartItemsCount();
        return $data;
    }*/

    private function cartProductListing(Request $request, $cartCollection)
    {
        $products = [];
        $shippingType = [];
        $cartData = [];
        $selectedShipping = '';
        if (count($cartCollection) > 0) {
            $cartFields =  'prod_min_order_qty, prod_type, prod_stock_out_sellable, prod_name,prod_max_order_qty, prod_self_pickup, pov_code, pov_id, prod_id, pc_gift_wrap_available, REPLACE(pov_display_title, "_","|") as variant_display_title, prod_updated_on, ' . Product::getPrice();
            foreach ($cartCollection as $key => $collection) {
                $selectedShipping = $collection->shipType;
                $code = $key;
                if ($collection->product_id ===  $key) {
                    $code = 0;
                }
                $productRecord = Product::productById($collection->product_id, $cartFields, [], $code);
                if(!$productRecord){
                     continue;
                 }
                $productRecord->image = url('yokart/app/product/image/' . $productRecord->prod_id . '/' . $productRecord->pov_id . '?t=' . strtotime($productRecord->prod_updated_on));
            
                if ($selectedShipping == 'shipping' && $productRecord->prod_self_pickup == 2) {
                    $this->deleteCartItem($request, $key);
                    $this->savedTempProducts($collection->product_id, $code);
                    continue;
                } elseif ($selectedShipping == 'pickup' && $productRecord->prod_self_pickup != 2 && $productRecord->prod_self_pickup != 3) {
                    $this->deleteCartItem($request, $key);
                    $this->savedTempProducts($collection->product_id, $code);
                    continue;
                }

                $stock = productStockVerify($productRecord, $collection['quantity'], $collection['id']);
                $collection['out_of_stock'] = "0";
                if ($stock === false) {
                    $collection['out_of_stock'] = "1";
                }
                $collection['gift_applied'] = false;
                $attributes = $collection->attributes->getItems();
              
                if (isset($attributes['gift'])) {
                    $collection['gift_applied'] = true;
                }
                $collection['ship_type'] = $collection['shipType'];
                unset($collection['shipType']);
                $collection['id'] = (string) $collection['id'];
                $collection['quantity'] = (int) $collection['quantity'];
                $collection['products'] = $productRecord;
                $cartData[] = $collection;
                $shippingType[$key] = $productRecord->prod_self_pickup;
            }
        }
   
        $giftEnable = getConfigValueByName('PRODUCT_GIFT_WRAP_ENABLE');
        $total = priceFormat(Cart::session($this->cartSession)->getTotal());
        $subTotal = priceFormat(Cart::session($this->cartSession)->getSubTotal());
        $tempSavedProducts = $this->savedProductListing(UserSavedProduct::TEMP_SAVED_PRODUCTS);

        return [
            'shipping_type' => $shippingType,
            'selected_shipping' => $selectedShipping,
            'cart_collection' => $cartData,
            'unavailable_prods' => $tempSavedProducts,
            'gift_enable' => $giftEnable,
            'total' => $total,
            'sub_total' => $subTotal
        ];
    }
    private function savedTempProducts($prodId, $code)
    {
        $userId = 0;
        if (Auth::guard('api')->check()) {
            $userId = Auth::guard('api')->user()->user_id;
        }
        $records = ['usp_prod_id' => $prodId, 'usp_user_id' => $userId,  'usp_temp' => 1, 'usp_session_id' => $this->cartSession];
        if ($code != 0) {
            $records['usp_pov_code'] =  $code;
        }
        UserSavedProduct::create($records);
    }
    private function savedProductListing($type, $recordId = 0)
    {
        $userId = 0;
        if (Auth::guard('api')->check()) {
            $userId = Auth::guard('api')->user()->user_id;
        }
        $sessionId = $this->cartSession;
        $savedProducts = Product::getSavedProductByUserId($userId, $type, $sessionId, 0, $recordId);

	    foreach($savedProducts as $productRecord){
            $productRecord->prod_image = url('yokart/app/product/image/' . $productRecord->prod_id . '/' . $productRecord->pov_id . '?t=' . strtotime($productRecord->prod_updated_on));
            unset($productRecord->pov_id,$productRecord->prod_updated_on);
        }

        return $savedProducts;
    }

    private function deleteCartItem(Request $request, $cartId)
    {
        $cartObj =  Cart::session($this->cartSession);
        if (empty($cartObj->getContent()->get($cartId))) {
            return false;
        }
        $attributes = $cartObj->getContent()->get($cartId)['attributes']->getItems();
        if (isset($attributes['gift'])) {
            $this->updateGiftItem($request, false, $cartId);
        }
        $cartObj->remove($cartId);
        ProductStockHold::where('pshold_prod_id', $cartId)->where('pshold_session_id', $this->cartSession)->delete();
        return true;
    }
    public function removeProducts(Request $request)
    {
        $temp = $request->input('temp_all');
        if ($temp == 0) {
            UserSavedProduct::where('usp_id', $request->input('usp_id'))->delete();
        } else {
            UserSavedProduct::where('usp_temp', UserSavedProduct::TEMP_SAVED_PRODUCTS)->delete();
        }
        Cart::taxUpdate();
        return apiresponse(config('constants.SUCCESS'), '', paymentSummaryForApp());
    }
    
}
