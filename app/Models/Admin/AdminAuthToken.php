<?php

namespace App\Models\Admin;

use App\Models\Admin\AdminYokartModel;

class AdminAuthToken extends AdminYokartModel
{
     public $timestamps = false;

    protected $fillable = [
        'admauth_token', 'admauth_expiry', 'admauth_browser', 'admauth_last_access', 'admauth_last_ip'
    ];

    public static function getRecordByToken($token){

        return AdminAuthToken::where('admauth_token', $token)->first();
    }
}
