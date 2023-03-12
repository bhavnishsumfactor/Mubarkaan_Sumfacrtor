<?php

namespace App\Models\Admin;

use App\Models\Admin\AdminYokartModel;
use Auth;

class NotificationToAdmin extends AdminYokartModel
{
    public $timestamps = false;
    
    protected $fillable = ['nta_notify_id', 'nta_admin_id', 'nta_read'];
}
