<?php

namespace App\Models\Admin;

use App\Models\Admin\AdminYokartModel;

class AdminEmailVerification extends AdminYokartModel
{
     public $timestamps = false;

     protected $fillable = [
        'aev_token', 'aev_email', 'aev_expiry'
    ];


}
