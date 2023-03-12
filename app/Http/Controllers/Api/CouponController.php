<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\YokartController;
use App\Models\Order;
use App\Models\DiscountCoupon;
use Illuminate\Http\Request;
use Auth;
use Cart;

class CouponController extends YokartController
{
    public function list(Request $request, $page)
    {
        $response = [];
      
        $totalSaving = Order::where('order_user_id', Auth::guard('api')->user()->user_id)->sum('order_discount_amount');
        
        $coupons = DiscountCoupon::getCouponsForApp(Auth::guard('api')->user()->user_id, $page);
        $cartCollection = [];
        if ($this->cartSession) {
            $cartCollection = Cart::session($this->cartSession)->getContent();

        }
       
        foreach ($coupons as $key => $coupon) {
            if (isValidCoupon($coupon, $cartCollection, Auth::guard('api')->user()->user_id) != true) {
                continue;
            }
            $saved = array_reduce(
                $coupon->orders->toArray(),
                function ($res, $a) {
                    return $res + $a["order_discount_amount"];
                },
                0
            );
            $coupons[$key]->saved = (string) $saved;
            $coupons[$key]->pending_uses = (string) $this->pendingUses($coupon);
            $coupons[$key]->no_of_uses = (string) $coupon->orders->count();
            $coupons[$key]->can_save = (($coupon->discountcpn_in_percent == 1) ? (string) priceFormat($coupon->discountcpn_maxamt_limit) : (string) priceFormat($coupon->discountcpn_amount));
            $coupon_image = url('') . '/noimages/coupon-image.png';
            if (!empty($coupon->attachedFile->discount_image)) {
                $coupon_image = $coupon->attachedFile->discount_image;
            }
            $coupons[$key]->coupon_image = $coupon_image;
            $coupons[$key]->is_expired = $coupon->orders->count() >= $coupon->discountcpn_total_uses ? "1" : "0";
            unset($coupons[$key]->orders);
            unset($coupons[$key]->attachedFile);
        }
        $coupons = $coupons->toArray();
        $response['totalsaving'] = (string) $totalSaving;
        $response['coupons'] = $coupons['data'];
        $response['total'] = $coupons['total'];
        $response['pages'] = ceil($coupons['total'] / $coupons['per_page']);
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }

    private function pendingUses($coupon)
    {
        if (
            $coupon->orders->count() < $coupon->discountcpn_total_uses &&
            $coupon->orders->count() < $coupon->discountcpn_uses_per_user
        ) {
            return $coupon->discountcpn_uses_per_user - $coupon->orders->count();
        }
        return 0;
    }

    public function detail(Request $request, $coupon_id)
    {
        $response = [];
        $response["included"] = DiscountCoupon::getCouponLinkedData($coupon_id);
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }
}
