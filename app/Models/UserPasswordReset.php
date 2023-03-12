<?php

namespace App\Models;

use App\Models\YokartModel;

class UserPasswordReset  extends YokartModel
{
    public $timestamps = false;

    protected $fillable = [
        'upr_user_id', 'upr_token', 'upr_expiry'
    ];

}
