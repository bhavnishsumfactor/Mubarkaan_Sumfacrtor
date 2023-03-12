<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductOptionVarient;
use App\Models\ProductOption;
use App\Http\Controllers\YokartController;
use Illuminate\Http\Request;
use App\Helpers\TaxHelper;
use App\Models\DiscountCoupon;
use App\Models\CouponHistory;
use App\Models\Country;
use App\Models\DiscountCouponRecord;
use Cart;
use Session;
use Str;
use Carbon\Carbon;
use App\Models\ProductStockHold;
use App\Models\UserRewardPoint;
use App\Models\StoreAddress;
use App\Models\UserSavedProduct;
use App\Helpers\Cart\CartCondition;
use Illuminate\Support\Arr;

class CartController extends YokartController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addToCart(Request $request)
    {
        $fields =  'prod_id, prod_name, prod_price, pov_default, prod_self_pickup, prod_stock_out_sellable, prod_min_order_qty, pov_code, prod_max_order_qty, ' . Product::getPrice();
        $variantCode = $request->input('optionCode');
        
        if (empty($variantCode) || $variantCode == "null") {
            $variantCode = 0;
        }
       
        $productId = $request->input('prodId');
    

        $product = Product::productById($productId, $fields, [], $variantCode, true);

        if (empty($product)) {
            return jsonresponse(false, __('NOTI_PRODUCT_NO_LONGER_AVAILABLE'));
        }
        $qty = ($product->prod_min_order_qty > 0) ? $product->prod_min_order_qty : 1;

        if ($request->input('qty')) {
            $qty = $request->input('qty');
        }

        if ($qty < $product->prod_min_order_qty) {
            return jsonresponse(false, __('NOTI_MINIMUM_PURCHASE_QUANTITY') . ' ' . $qty);
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
        $cartItem = Cart::session($this->cartSession)->getContent()->byKey($code);
        $cartQty = $qty;
        if (!empty($cartItem)) {
            $cartQty = $cartQty + $cartItem['quantity'];
            if ($cartQty > $product->prod_max_order_qty) {
                return jsonresponse(false, __('NOTI_MAXIMUM_PURCHASE_QUANTITY') . ' ' . $product->prod_max_order_qty);
            }
        }
        $stock = productStockVerify($product, $cartQty, $variantCode);
        if ($stock == false) {
            return jsonresponse(false, __('NOTI_PRODUCT_OUT_OF_STOCK'));
        }
        $shipType = "shipping";
        if ($product->prod_self_pickup == 2) {
            $shipType = "pickup";
        }
        ProductStockHold::storeProductOnHold($product->prod_id, $qty, true, $variantCode);

        Cart::session($this->cartSession)->add($product->prod_id, $product->prod_name, priceFormat($product->finalprice), $qty, $code, $shipType, $varientsResults);
        $currentItem = [
            'content_name' => $product->prod_name,
            'currency' => currencyCode(),
            'value' => $product->finalprice
        ];
        $countryCode = Str::upper(getDefaultCountryCode());
        $countryData = Country::getStatesByCountryCode($countryCode, ['country_id', 'state_id'])->first();
        Cart::taxUpdate($countryData->country_id, $countryData->state_id);
        return jsonresponse(true, __('Added to cart'), ['cartItems' => Cart::cartItemsCount(), 'currentItem' => $currentItem]);
    }
    public function updateSavedProduct(Request $request)
    {
        $shipType = ($request->input('ship-type') ? $request->input('ship-type') : '');
        $isValidateShipping = false;
        if($shipType !== ""){
            $isValidateShipping = true;
        }
        $product = Product::getSavedProductById($request->input('id'));
        $cartUpdate = $this->productCartUpdated($product, $shipType, $isValidateShipping);
        if ($cartUpdate['status'] == false) {
            return jsonresponse(false, $cartUpdate['message']);
        }


        UserSavedProduct::where('usp_id', $request->input('id'))->delete();
        Cart::taxUpdate();
        $savedProducts = $this->savedProductListing(UserSavedProduct::SAVED_PRODUCTS);
        $cartProducts = $this->cartProductListing($request);
        $cartProductListing = view('themes.' . config('theme') . '.cart.cartItems', $cartProducts)->render();
        $saveProductListing = view('themes.' . config('theme') . '.cart.savedItems', compact('savedProducts'))->render();
        $shippingType = $cartProducts['shippingType'];
        $shipping = validateShippigType($shippingType, 'shipping');
        $pickup = validateShippigType($shippingType, 'pickup');
        $updatedSummary = array_merge(['pickup' => $pickup, 'shipping' => $shipping, 'savedProducts' => $saveProductListing, 'cartProducts' => $cartProductListing], $this->updateCartPageSummary(true));
        return jsonresponse(true, __('NOTI_ITEM_REMOVED_SUCCESSFULLY'), $updatedSummary);
    }
    private function productCartUpdated($product, $shipType,  $validateShipping = true)
    {
        $code = $product->pov_code ? $product->pov_code : $product->prod_id;
        $cartItem = Cart::session($this->cartSession)->getContent()->byKey($code);

          
        $qty = ($product->prod_min_order_qty > 0) ? $product->prod_min_order_qty : 1;
        $cartQty = $qty;
        if (!empty($cartItem)) {
            $qty = $qty + $cartItem['quantity'];
        }
        if ($qty > $product->prod_max_order_qty) {
            return ['status' => false, 'message' => __('NOTI_MAXIMUM_PURCHASE_QUANTITY').' '. $product->prod_max_order_qty];
        }
        $stock = productStockVerify($product, $qty, $product->pov_code);
     
       
        if ($stock == false) {
            return ['status' => false, 'message' => __('NOTI_PRODUCT_OUT_OF_STOCK')];
        }
        
        if ($product->prod_self_pickup != 3  && $validateShipping == true) {
            if ($shipType == 'shipping' && !in_array($product->prod_self_pickup, [0, 1])) {
                return ['status' => false, 'message' => __('NOTI_PRODUCT_NOT_AVAILABLE_FOR_SHIPPING')];
            }
            if ($shipType == 'pickup' && $product->prod_self_pickup != 2) {
                return ['status' => false, 'message' => __('NOTI_PRODUCT_NOT_AVAILABLE_FOR_PICKUP')];
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
    
        ProductStockHold::storeProductOnHold($product->prod_id, $cartQty, true, $product->pov_code);
        Cart::session($this->cartSession)->add($product->prod_id, $product->prod_name, $product->finalprice, $cartQty, $code, $shipType, $varientsResults);
        return ['status' => true, 'message' => __('NOTI_PRODUCT_ADDED_SUCCESSFULLY')];
    }
    public function index(Request $request)
    {
        $cartCollection = Cart::session($this->cartSession)->getContent();
        $records = $this->cartProductListing($request);
        $buyTogetherProducts = [];
        if (count($records['productIds']) > 0) {
            $fields = 'prod_id, prod_name,  prod_price, pov_default,
            prod_stock_out_sellable, prod_min_order_qty, prod_cod_available, pov_code,brand_id,cat_id, prod_max_order_qty,' . Product::getPrice();
            $buyTogetherProducts = Product::getBuyTogetherProductIds($fields, $records['productIds'], 10);
        }
        $savedProducts = $this->savedProductListing(UserSavedProduct::SAVED_PRODUCTS);
        $records['buyTogetherProducts'] = $buyTogetherProducts;
        $records['savedProducts'] = $savedProducts;
        $records['cartCollectionCount'] = $cartCollection->count();
        $records['pickupAddress'] = StoreAddress::getRecords([], false);
        return view('themes.' . config('theme') . '.cart.index', $records);
    }
    private function cartProductListing(Request $request)
    {
        $cartCollection = Cart::session($this->cartSession)->getContent();
        $products = [];
        $productIds = [];
        $shippingType = [];
        $cartData = [];
        $selectedShipping = '';
        if (count($cartCollection) > 0) {
            $cartFields =  'prod_min_order_qty, prod_stock_out_sellable, prod_name,prod_max_order_qty, prod_self_pickup, pov_code, prod_id, pc_gift_wrap_available, ' . Product::getPrice();

            foreach ($cartCollection as $key => $collection) {
                $selectedShipping = $collection->shipType;
                $code = $key;
                if ($collection->product_id ===  $key) {
                    $code = 0;
                }
                $productRecord = Product::productById($collection->product_id, $cartFields, [], $code);
                
                if(!empty($productRecord)){
                    if ($selectedShipping == 'shipping' && $productRecord->prod_self_pickup == 2) {
                        $this->deleteCartItem($request, $key);
                        $this->savedTempProducts($collection->product_id, $code);
                        continue;
                    } elseif ($selectedShipping == 'pickup' && $productRecord->prod_self_pickup != 2 && $productRecord->prod_self_pickup != 3) {
                        $this->deleteCartItem($request, $key);
                        $this->savedTempProducts($collection->product_id, $code);
                        continue;
                    }
                    $cartData[$key] = $collection;
                    $shippingType[$key] = $productRecord->prod_self_pickup;
                    $products[$key] = $productRecord;
                    $productIds[] = $collection->product_id;
                }else{
                    $this->deleteCartItem($request,$key);
                }    
            }
        }
        $giftEnable = getConfigValueByName('PRODUCT_GIFT_WRAP_ENABLE');
        return ['products' => $products, 'productIds' => $productIds, 'shippingType' => $shippingType, 'selectedShipping' => $selectedShipping, 'cartCollection' => $cartData, 'giftEnable' => $giftEnable];
    }
    private function savedTempProducts($prodId, $code)
    {
        $userId = 0;
        if ($this->signed_in) {
            $userId = $this->user->user_id;
        }
        $records = ['usp_prod_id' => $prodId, 'usp_user_id' => $userId,  'usp_temp' => 1, 'usp_session_id' => $this->cartSession];
        if ($code != 0) {
            $records['usp_pov_code'] =  $code;
        }
        UserSavedProduct::create($records);
    }
    private function savedProductListing($type)
    {
        $userId = 0;
        if ($this->signed_in) {
            $userId = $this->user->user_id;
        }
        $sessionId = $this->cartSession;
        $savedProducts = Product::getSavedProductByUserId($userId, $type, $sessionId);

        return $savedProducts;
    }
    public function updateShipping(Request $request, $type)
    {
        $cartCollection = Cart::session($this->cartSession)->getContent();

        foreach ($cartCollection as $key => $collection) {
            Cart::session($this->cartSession)->update(
                $key,
                array('shipType' => $type)
            );
        }
        $shiipingType = Product::PICKUP_ONLY;
        if ($type == 'shipping') {
            $shiipingType = Product::SHIPPING_ONLY;
        }
        $savedProducts = $this->savedProductListing(UserSavedProduct::TEMP_SAVED_PRODUCTS, $shiipingType);

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
        $cartProducts = $this->cartProductListing($request);

        $cartProductListing = view('themes.' . config('theme') . '.cart.cartItems', $cartProducts)->render();
        $tempSavedProducts = $this->savedProductListing(UserSavedProduct::TEMP_SAVED_PRODUCTS);


        $shippingIds = [];
        if ($tempSavedProducts->count() > 0) {
            $shippingIds = array_merge(Arr::pluck($cartProducts['products'], 'prod_self_pickup'), $tempSavedProducts->pluck('prod_self_pickup')->toArray());
        }
        $tempSavedProductsHtml = view('themes.' . config('theme') . '.cart.tempSavedItems', ['tempSavedProducts' => $tempSavedProducts, 'selectedShipping' => $type, 'shippingIds' => $shippingIds])->render();
        $shippingType = $cartProducts['shippingType'];
        $shipping = validateShippigType($shippingType, 'shipping');
        $pickup = validateShippigType($shippingType, 'pickup');
        $updatedSummary = array_merge(['pickup' => $pickup, 'shipping' => $shipping, 'tempProducts' => $tempSavedProductsHtml, 'cartProducts' => $cartProductListing], $this->updateCartPageSummary(true));
        return jsonresponse(true, __('Cart updated successfully'), $updatedSummary);
    }
    public function savedCartItems(Request $request)
    {
        if (!$this->signed_in) {
            return jsonresponse(false, '', loginPopup());
        }
        $prodId  = $request->input('id');
        $code  = $request->input('code');
        $userId  = $this->user->user_id;
        $records = ['usp_prod_id' => $prodId, 'usp_user_id' => $userId, 'usp_pov_code' => $code];
        $record = UserSavedProduct::where($records);
        if ($record->count() != 0) {
            if ($record->first()->usp_temp == UserSavedProduct::TEMP_SAVED_PRODUCTS) {
                UserSavedProduct::where($records)->update(['usp_temp' => UserSavedProduct::SAVED_PRODUCTS]);
            }
        } else {
            UserSavedProduct::create($records);
        }
        Cart::taxUpdate();
        $savedProducts = $this->savedProductListing(UserSavedProduct::SAVED_PRODUCTS);
        $saveProductListing = view('themes.' . config('theme') . '.cart.savedItems', compact('savedProducts'))->render();
        $updatedSummary = array_merge(['savedProducts' => $saveProductListing], $this->updateCartPageSummary(true));
        return jsonresponse(true, __('NOTI_RECORD_UPDATED_SUCCESSFULLY'), $updatedSummary);
    }

    public function update(Request $request)
    {
        $cartId = $request->input('cartId');
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
            return jsonresponse(false, __('NOTI_MAXIMUM_PURCHASE_QUANTITY') .  $cartQty, $cartQty);
        }
        if ($qty < $product->prod_min_order_qty) {
            return jsonresponse(false, __('NOTI_MINIMUM_PURCHASE_QUANTITY') . $cartQty, $cartQty);
        }
        $stock = productStockVerify($product, $qty, $variantCode);
        if ($stock == false) {
            return jsonresponse(false, __('NOTI_PRODUCT_OUT_OF_STOCK'), $cartQty);
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
        ProductStockHold::storeProductOnHold($prodId, $qty, false);
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
        return jsonresponse(true, __('NOTI_PRODUCT_QUANTITY_SUCCESSFULLY'), $this->updateCartPageSummary(true));
    }
    public function saveCoupon($couponId, $amount)
    {
        $userId = 0;
        if ($this->signed_in) {
            $userId = $this->user->user_id;
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
    public function getCoupons(Request $request)
    {
        $userId = 0;
        if ($this->signed_in) {
            $userId = $this->user->user_id;
        }
        $pageType = $request->input('page-type');
        $coupons = DiscountCoupon::getActiveRecords($userId);
        $cartCollection = Cart::session($this->cartSession)->getContent();
        $subtotal = Cart::session($this->cartSession)->getSubTotal();
        $html = view('themes.' . config('theme') . '.partials.couponForm', compact('coupons', 'cartCollection', 'subtotal', 'pageType'))->render();
        return jsonresponse(true, '', $html);
    }
    public function applyCoupon(Request $request, $coupon = "")
    {
        if (empty($coupon)) {
            $validatedData = $request->validate([
                'coupon-code' => 'required',
            ]);
            $couponCode = $request->input('coupon-code');
        } else {
            $couponCode = $coupon;
        }
        $userId = 0;
        if ($this->user) {
            $userId = $this->user->user_id;
        }
        $pageType = '';
        if (isset($request['page-type'])) {
            $pageType = $request->input('page-type');
        }
        $historyId = "";
        $record = DiscountCoupon::getRecordByCouponCode($couponCode, $userId);
        $couponExisting = Cart::session($this->cartSession)->getCondition('coupon');
        if ($couponExisting) {
            $historyId = $couponExisting->getId();
        }
        if (empty($record)) {
            return jsonresponse(false, __('Coupon Invalid!'));
        }
        $subtotal = Cart::session($this->cartSession)->getSubTotal();
        if ($subtotal < $record->discountcpn_minorderamt) {
            if ($couponExisting) {
                Cart::applyCoupon($couponCode, 0, $historyId, [], true);
            }
            return jsonresponse(false, __('NOTI_MINIMUM_ORDER_AMOUNT_SHOULD_BE'). displayPrice($record->discountcpn_minorderamt));
        }

        if (empty(CouponHistory::CouponHistoryValidate($record, $userId))) {
            return jsonresponse(false, __('NOTI_COUPON_EXPIRED'));
        }

        

        if (count($record->couponConditions) == 0) {
            $amount = $this->calculateCouponAmount($record);
            if (empty($historyId)) {
                $historyId = $this->saveCoupon($record->discountcpn_id, $amount);
            }
            Cart::applyCoupon($couponCode, $amount, $historyId);
            Cart::taxUpdate();
            return jsonresponse(true, __('NOTI_COUPON_APPLIED_SUCCESSFULLY'), $this->updateCartPageSummary($pageType));
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
                    return jsonresponse(false, __('Coupon Invalid!'));
                }
            }
            if (array_key_exists("users", $couponExist)) {
                $amount = $this->calculateCouponAmount($record);
                if (empty($historyId)) {
                    $historyId = $this->saveCoupon($record->discountcpn_id, $amount);
                }
                Cart::applyCoupon($couponCode, $amount, $historyId);
                Cart::taxUpdate();
                return jsonresponse(true, __('NOTI_COUPON_APPLIED_SUCCESSFULLY'), $this->updateCartPageSummary($pageType));
            }
        }
        if (count($attributes) == 0) {
            return jsonresponse(false, __('Coupon Invalid!'));
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
        return jsonresponse(true, __('NOTI_COUPON_APPLIED_SUCCESSFULLY'), $this->updateCartPageSummary($pageType));
    }
    public function updateCartPageSummary($pageType = '')
    {
        $cartConditions = Cart::session($this->cartSession)->getConditions();

        $cartPage = $pageType ? true :  false;
        $pickup  = count(Cart::getPickups('name'));
        $couponAmount = 0;
        if ($coupon = Cart::session($this->cartSession)->getCondition('coupon')) {
            $couponAmount = abs($coupon->getValue());
        }
        $outOfStock = Cart::isOutOfStockProducts();
        $displayRewardPointOnEarn = getConfigValueByName('REWARD_POINTS_PURCHASE_POINTS_DISPLAY_EARNED');
        $earnRewardPoints = UserRewardPoint::calculateEarnRewardPoints(Cart::session($this->cartSession)->getSubTotal(), $couponAmount);
        $digitalProducts  = Cart::isDigitalProduct();
       
        $data['cartInfo'] = view('themes.' . config('theme') . '.partials.cartSummary', compact('cartConditions', 'cartPage', 'outOfStock', 'pickup', 'digitalProducts', 'displayRewardPointOnEarn', 'earnRewardPoints'))->render();
        $data['cartItems'] = Cart::cartItemsCount();
        return $data;
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
    public function updateGiftItem(Request $request, $validate, $cartId = 0, $update = false, $oldPrice = 0)
    {
        if ($validate == "true") {
            $request->validate([
                'from' => 'required',
                'to' => 'required',
                'message' => 'required',
            ]);
        }

        if (empty($cartId)) {
            $cartId = $request->input('id');
        }
        $cartObj =  Cart::session($this->cartSession);
        $attributes = $cartObj->getContent()->get($cartId)['attributes']->getItems();

        $quantity = $cartObj->getContent()->get($cartId)['quantity'];

        $cartGiftPrice = ($cartObj->getCondition('gift')) ? $cartObj->getCondition('gift')->getValue() : 0;
        $totalProduct = ($cartObj->getCondition('gift')) ? $cartObj->getCondition('gift')->getTotalProducts() : 0;

        $giftPrice = getConfigValueByName('PRODUCT_GIFT_WRAP_PRICE');


        if (isset($attributes['gift']) && $update == true) {
            $giftArray = $attributes['gift'];
            $cartGiftPrice = ($cartGiftPrice - $oldPrice) + $giftPrice * $quantity;
            $totalProduct = $totalProduct;
        } elseif (isset($attributes['gift'])) {
            unset($attributes['gift']);
            $giftArray = [];
            $cartGiftPrice = $cartGiftPrice - $giftPrice * $quantity;
            $totalProduct = $totalProduct - 1;
        } else {
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
        return jsonresponse(true, __('NOTI_GIFT_WRAP_UPDATED'), $this->updateCartPageSummary(true));
    }


    public function giftItemMessages($id)
    {
        $giftmessages = Cart::session($this->cartSession)->getCondition('gift');
        $message = [];
        if ($giftmessages && isset($giftmessages->getAttributes()['gift'])) {
            $message =  $giftmessages->getAttributes()['gift']['message'];
        }
        return view('themes.' . config('theme') . '.partials.giftForm', compact('message', 'id'))->render();
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
        return jsonresponse(true, __('NOTI_COUPON_APPLIED_SUCCESSFULLY'), $this->updateCartPageSummary($request['page-type']));
    }
    public function removeItem(Request $request)
    {
        $this->deleteCartItem($request, $request->input('id'));
        Cart::taxUpdate();
        $cartProducts = $this->cartProductListing($request);
        $shippingType = $cartProducts['shippingType'];
        $shipping = validateShippigType($shippingType, 'shipping');
        $pickup = validateShippigType($shippingType, 'pickup');
        if (!empty(Cart::session($this->cartSession)->getCondition('coupon'))) {
            if ($coupon = Cart::session($this->cartSession)->getCondition('coupon')) {
                CouponHistory::where('couponhistory_id', $coupon->getId())->delete();
                Cart::removeConditionsByType('coupon');
                $this->applyCoupon($request, $coupon->getCode());
            }
        }
        $updatedSummary = array_merge(['shipping' => $shipping, 'pickup' => $pickup], $this->updateCartPageSummary(true));
        return jsonresponse(true, __('Product deleted successfully!'), $updatedSummary);
    }
    private function deleteCartItem(Request $request, $cartId)
    {
        $cartObj =  Cart::session($this->cartSession);
        $attributes = $cartObj->getContent()->get($cartId)['attributes']->getItems();
        if (isset($attributes['gift'])) {
            $this->updateGiftItem($request, false, $cartId);
        }
        $cartObj->remove($cartId);
        ProductStockHold::where('pshold_prod_id', $cartId)->where('pshold_session_id', $this->cartSession)->delete();
    }
    public function quickView(Request $request)
    {
        $cartCollection = Cart::session($this->cartSession)->getContent();
       
        $products = [];
        if (!empty($cartCollection)) {
            foreach ($cartCollection as $key => $collection) {
                $fields =  'prod_min_order_qty, prod_stock_out_sellable, prod_max_order_qty, pov_code, prod_id,' . Product::getPrice();
                $code = $key;
                if ($collection->product_id ===  $key) {
                    $code = 0;
                }
                $product = Product::productById($collection->product_id, $fields, [], $code);
                if($product){
                    $products[$key] = Product::productById($collection->product_id, $fields, [], $code);
                }else{
                     $this->deleteCartItem($request,$key);
                }
            }
        }
        $cartCollection = Cart::session($this->cartSession)->getContent();
        $total = Cart::session($this->cartSession)->getTotal();
        $couponAmount = 0;
        if ($coupon = Cart::session($this->cartSession)->getCondition('coupon')) {
            $couponAmount = abs($coupon->getValue());
        }
        $earnRewardPoints = UserRewardPoint::calculateEarnRewardPoints(Cart::session($this->cartSession)->getSubTotal(), $couponAmount);
        $couponValue = 0;
        if ($coupon = Cart::session($this->cartSession)->getCondition('coupon')) {
            $couponValue = $coupon->getValue();
        }

      //  print_r(compact('cartCollection', 'couponValue', 'products', 'earnRewardPoints', 'total'));exit;
        return view('themes.' . config('theme') . '.partials.miniCartView', compact('cartCollection', 'couponValue', 'products', 'earnRewardPoints', 'total'))->render();
    }
}
