<?php

namespace App\Models;

use App\Models\YokartModel;

class DiscountCouponRecord extends YokartModel
{
    public $timestamps = false;

    protected $fillable = [
      'dcr_type', 'dcr_discountcpn_id', 'dcr_record_id', 'dcr_subrecord_id'
    ];

    public const DISCOUNT_RECORD_PRODUCT_TYPE = 1;
    public const DISCOUNT_RECORD_CATEGORY_TYPE = 2;
    public const DISCOUNT_RECORD_BRAND_TYPE = 3;
    public const DISCOUNT_RECORD_USER_TYPE = 4;

    const DISCOUNT_RECORD_TYPE = [
      self::DISCOUNT_RECORD_PRODUCT_TYPE => 'products',
      self::DISCOUNT_RECORD_CATEGORY_TYPE => 'categories',
      self::DISCOUNT_RECORD_BRAND_TYPE => 'brands',
      self::DISCOUNT_RECORD_USER_TYPE => 'users',
    ];

    public static function validCouponByUserId($couponId, $userId)
    {
        $count = DiscountCouponRecord::where('dcr_discountcpn_id', $couponId)
        ->where('dcr_type', self::DISCOUNT_RECORD_USER_TYPE)
        ->count();
        $countByUser = DiscountCouponRecord::where('dcr_discountcpn_id', $couponId)
        ->where('dcr_type', self::DISCOUNT_RECORD_USER_TYPE)
        ->where('dcr_record_id', $userId)
        ->count();
        if ($count == 0 || ($count > 0 && $countByUser > 0)) {
            return true;
        }
        return false;
    }
}
