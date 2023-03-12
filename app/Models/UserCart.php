<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\DiscountCoupon;
use App\Models\CouponHistory;
use App\Models\UserSavedProduct;
use Auth;
use Session;
use Cart;

class UserCart extends YokartModel
{
    public $timestamps = false;
    protected $table = 'user_cart';
    protected $primaryKey = 'ucart_last_session_id';
    protected $fillable = [
        'ucart_user_id', 'ucart_details', 'ucart_from_type', 'ucart_added_date', 'usp_temp', 'ucart_last_session_id'
    ];

    public const UCART_FROM_TYPE_WEB = 1;
    public const UCART_FROM_TYPE_MOBILE = 2;

    public function setUcartDetailsAttribute($value)
    {
        $this->attributes['ucart_details'] = serialize($value);
    }

    public function getUcartDetailsAttribute($value)
    {
        return unserialize($value);
    }
    public static function updateCartItems()
    {
        $id = Auth::user()->user_id;
        $cartItems = UserCart::select('ucart_last_session_id as cartSession', 'ucart_details')->where('ucart_user_id', $id)->where('ucart_last_session_id', 'Like', '%_cart_items')->first();

        UserSavedProduct::where(['usp_session_id' => Session::get('cartSession'), 'usp_temp' => 1])->update(['usp_user_id' => $id]);
       
        if (!empty($cartItems) && !empty($cartItems->ucart_details)) {
            $oldCartArray =  json_decode($cartItems->ucart_details, true);
            $cartSession =  $cartItems->cartSession;
            UserCart::where('ucart_user_id', $id)->delete();

            foreach ($oldCartArray as $cartArray) {
                if (!isset($cartArray['product_id'])) {
                    continue;
                }
                Cart::session(Session::get('cartSession'))->add($cartArray['product_id'], $cartArray['name'], $cartArray['price'], $cartArray['quantity'], $cartArray['id'], $cartArray['shipType'], $cartArray['attributes']);
            }

            if ($coupon = Cart::session(Session::get('cartSession'))->getCondition('coupon')) {
                $historyId = $coupon->getId();
                $couponCode = $coupon->getCode();
                $amount = $coupon->getValue();
                $applyType = $coupon->getTarget();
                $couponRecord = DiscountCoupon::getRecordByCouponCode($coupon->getCode());
                Cart::session(Session::get('cartSession'))->condition($coupon);
            }

            UserCart::where('ucart_last_session_id', Session::get('cartSession') . '_cart_items')
                ->update([
                    'ucart_user_id' => $id,
                    'ucart_last_session_id' => $cartSession,
                ]);
            if ($coupon = Cart::session(Session::get('cartSession'))->getCondition('coupon')) {
                static::setCoupon($coupon, $id, $cartSession);
            }
            UserCart::where('ucart_last_session_id', Session::get('cartSession') . '_cart_conditions')
                ->update([
                    'ucart_user_id' => $id,
                    'ucart_last_session_id' => str_replace("_cart_items", "_cart_conditions", $cartSession),
                ]);
            Session::put('cartSession', str_replace("_cart_items", "", $cartSession));
        } elseif (!empty(Session::get('cartSession'))) {
            UserCart::where('ucart_last_session_id', 'like', '%' . Session::get('cartSession') . '%')->update(['ucart_user_id' => $id]);
        }
        UserCart::where('ucart_user_id', 0)->delete();
    }
    public static function setCoupon($coupon, $id, $cartSession)
    {
        $historyId = $coupon->getId();
        $couponCode = $coupon->getCode();
        $amount = $coupon->getValue();
        $applyType = $coupon->getTarget();
        $couponRecord = DiscountCoupon::getRecordByCouponCode($coupon->getCode());
        Cart::session(Session::get('cartSession'))->condition($coupon);

        UserCart::where('ucart_last_session_id', Session::get('cartSession') . '_cart_conditions')
            ->update([
                'ucart_user_id' => $id,
                'ucart_last_session_id' => str_replace("_cart_items", "_cart_conditions", $cartSession),
            ]);
    }

    public static function updateCartItemsForApp($newCartSession, $id)
    {
        $cartItems = UserCart::select('ucart_last_session_id as cartSession', 'ucart_details')->where('ucart_user_id', $id)->where('ucart_from_type', self::UCART_FROM_TYPE_WEB)->where('ucart_last_session_id', 'Like', '%_cart_items')->first();
        
        if (!empty($cartItems) && !empty($cartItems->ucart_details)) {
            $userMobileCart = UserCart::where('ucart_last_session_id', 'like', '%' . $newCartSession . '%')
                    ->where('ucart_from_type', self::UCART_FROM_TYPE_MOBILE)->where('ucart_last_session_id', 'Like', '%_cart_items')->first();

            $oldCartArray =  json_decode($cartItems->ucart_details, true);
            $cartSession =  $cartItems->cartSession;
            UserCart::where('ucart_user_id', $id)->where('ucart_last_session_id', $cartSession)->delete();
            if (!empty($userMobileCart) && !empty($userMobileCart->ucart_details)) {
                $oldMobileCartArray =  json_decode($userMobileCart->ucart_details, true);
                foreach ($oldMobileCartArray as $cartMobileArray) {
                    if (!isset($cartMobileArray['product_id'])) {
                        continue;
                    }
                    $oldCartArray[$cartMobileArray['id']] = $cartMobileArray;
                }
            }
            foreach ($oldCartArray as $cartArray) {
                if (!isset($cartArray['product_id'])) {
                    continue;
                }
                Cart::session($newCartSession)->add($cartArray['product_id'], $cartArray['name'], $cartArray['price'], $cartArray['quantity'], $cartArray['id'], $cartArray['shipType'], $cartArray['attributes']);
            }
            UserCart::where('ucart_last_session_id', $newCartSession . '_cart_items')
                ->update([
                    'ucart_user_id' => $id,
                    'ucart_from_type' => self::UCART_FROM_TYPE_MOBILE
                ]);
            UserCart::where('ucart_last_session_id', str_replace("_cart_items", "_cart_conditions", $cartSession))
                ->update([
                    'ucart_user_id' => $id,
                    'ucart_last_session_id' => $newCartSession . '_cart_conditions',
                    'ucart_from_type' => self::UCART_FROM_TYPE_MOBILE
                ]);
        } elseif (!empty($newCartSession)) {
            $userMobileCart = UserCart::where('ucart_last_session_id', 'like', '%' . $newCartSession . '%')
                    ->where('ucart_from_type', self::UCART_FROM_TYPE_MOBILE)->first();
            
            if (!empty($userMobileCart) && !empty($userMobileCart->ucart_details)) {
                UserCart::where('ucart_last_session_id', 'like', '%' . $newCartSession . '%')
                    ->where('ucart_from_type', self::UCART_FROM_TYPE_MOBILE)->update(['ucart_user_id'=> $id]);
            }
        }
        UserCart::where('ucart_user_id', 0)->delete();
    }

    public static function updateMobileDeviceType($newCartSession)
    {
        UserCart::where('ucart_last_session_id', $newCartSession . '_cart_items')
            ->update([
                'ucart_from_type' => self::UCART_FROM_TYPE_MOBILE
            ]);
        UserCart::where('ucart_last_session_id', $newCartSession . '_cart_conditions')
            ->update([
                'ucart_from_type' => self::UCART_FROM_TYPE_MOBILE
            ]);
    }
   
}
