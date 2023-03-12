<?php

namespace App\Models;

use App\Models\YokartModel;
use Carbon\Carbon;

class InstagramFeedToken extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'iftoken_id';

    protected $fillable = [
      'iftoken_id', 'iftoken_username', 'iftoken_user_id', 'iftoken_access_code', 'iftoken_expired_at', 'iftoken_created_at'
    ];

    public static function getValidTokenData()
    {
        $iftoken = InstagramFeedToken::select('iftoken_user_id', 'iftoken_username', 'iftoken_access_code')
        ->whereNotNull('iftoken_access_code')
        ->whereDate('iftoken_expired_at', '>', Carbon::now())
        ->first();
        return !empty($iftoken) ? $iftoken : '';
    }
}
