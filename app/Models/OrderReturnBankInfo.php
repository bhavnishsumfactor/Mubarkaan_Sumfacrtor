<?php

namespace App\Models;

use App\Models\YokartModel;

class OrderReturnBankInfo extends YokartModel
{
    protected $table = 'order_return_bank_info';

    public $timestamps = false;
    protected $fillable = ['orbinfo_order_id', 'orbinfo_user_id', 'orbinfo_name', 'orbinfo_branch', 'orbinfo_account_name', 'orbinfo_branch_code', 'orbinfo_account_number', 'orbinfo_notes'];

}
