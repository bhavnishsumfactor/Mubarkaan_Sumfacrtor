<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDeviceToken extends Model
{
    use HasFactory;

    const USER_DEVICE_TYPE_IOS = 1;
    const USER_DEVICE_TYPE_ANDROID = 2;

    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $fillable = [
        'udt_user_id', 'udt_sess_id', 'udt_device_type', 'udt_device_token'
    ];

    public static function isTokenAlreadyExists($sessId, $deviceType, $deviceToken)
    {
        $response = UserDeviceToken::select('udt_id')->where([ 'udt_sess_id' => $sessId, 'udt_device_type' => $deviceType, 'udt_device_token'=> $deviceToken])->first();
        if (isset($response->udt_id) && 0 < $response->udt_id) {
            return true;
        }
        return false;
    }
}
