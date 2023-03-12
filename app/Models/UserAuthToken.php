<?php

namespace App\Models;

use App\Models\YokartModel;

class UserAuthToken extends YokartModel
{
  public $timestamps = false;

  protected $fillable = [
     'usrauth_user_id', 'usrauth_token', 'usrauth_expiry', 'usrauth_browser', 'usrauth_last_access', 'usrauth_last_ip'
  ];

  public static function getRecordByToken($token){

     return UserAuthToken::where('usrauth_token', $token)->first();
  }
}
