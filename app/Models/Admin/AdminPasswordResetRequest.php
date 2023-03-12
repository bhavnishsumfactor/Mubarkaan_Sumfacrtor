<?php

namespace App\Models\Admin;

use App\Models\Admin\AdminYokartModel;

class AdminPasswordResetRequest  extends AdminYokartModel
{
      public $timestamps = false;

    protected $fillable = [
        'aprr_admin_id', 'aprr_token', 'aprr_otp', 'aprr_expiry'
    ];

}
