<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppReferralCodeLog extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'arcl_ip_address', 'arcl_referral_code', 'arcl_device', 'arcl_status'
    ];
}
