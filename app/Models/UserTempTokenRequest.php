<?php

namespace App\Models;

use App\Models\YokartModel;
use Carbon\Carbon;

use DB;

class UserTempTokenRequest extends YokartModel
{
    public $timestamps = false;
    protected $primaryKey = 'ureq_id';

    protected $fillable = [
        'uttr_id', 'uttr_user_id', 'uttr_type', 'uttr_code', 'uttr_expiry'
    ];

    public const ORDER_OTP_REQUEST = 1;
    public const DOWNLOAD_REQUEST = 2;

    public const REQUEST_TYPE = [
        'ORDER_OTP_REQUEST' => self::ORDER_OTP_REQUEST ,
        'DOWNLOAD_REQUEST' => self::DOWNLOAD_REQUEST
    ];
}
