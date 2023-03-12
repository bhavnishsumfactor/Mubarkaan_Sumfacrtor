<?php

namespace App\Models;

use App\Models\YokartModel;

class UserRewardPointBreakup extends YokartModel
{
    protected $table = 'user_reward_point_breakup';
     public $timestamps = false;

    protected $primaryKey = 'urpbreakup_id';

    protected $fillable = [
        'urpbreakup_urp_id', 'urpbreakup_points', 'urpbreakup_expiry', 'urpbreakup_used', 'urpbreakup_referral_user_id', 'urpbreakup_used_order_id', 'urpbreakup_used_date'
    ];
}
