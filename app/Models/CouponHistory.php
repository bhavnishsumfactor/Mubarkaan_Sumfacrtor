<?php

namespace App\Models;

use App\Models\YokartModel;
use Carbon\Carbon;
class CouponHistory extends YokartModel
{
    public $timestamps = false;
    protected $primaryKey = 'couponhistory_id';

    protected $fillable = ['couponhistory_coupon_id', 'couponhistory_order_id'
    ,'couponhistory_amount', 'couponhistory_user_id', 'couponhistory_added_on'];

    public static function CouponHistoryValidate($record, $userId, $couponhistoryId = 0)
    {
       
        CouponHistory::where('couponhistory_order_id', '')->where('couponhistory_added_on', '<', Carbon::now()->subMinutes(config('app.expiredToken'))->toDateTimeString())->delete();
        $status = true;
        $historyobj = CouponHistory::where('couponhistory_coupon_id', $record->discountcpn_id);
        if($couponhistoryId != 0) {
            $historyobj->where('couponhistory_id', '!=', $couponhistoryId);
        }
        if ($record->discountcpn_total_uses <= $historyobj->count()) {
            $status = false;
        } elseif ($userId  && $record->discountcpn_uses_per_user <= $historyobj->where('couponhistory_user_id', $userId)->count()) {
            $status = false;
        }
        return $status;
    }
}
