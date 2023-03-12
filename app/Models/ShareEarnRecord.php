<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\Configuration;
use App\Models\User;
use App\Models\UserRewardPoint;
use App\Models\UserRewardPointBreakup;
use Carbon\Carbon;
use App\Helpers\EmailHelper;
use App\Jobs\SendNotification;
use DB;
use Auth;

class ShareEarnRecord extends YokartModel
{
    public const CREATED_AT = 'ser_created_at';
    public $timestamps = false;
    protected $primaryKey = 'ser_id';

    public const EMAIL_SHARE = 1;
    public const SOCIAL_SHARE = 2;

    public const SHARE_TYPE = [
        self::EMAIL_SHARE => 'Email',
        self::SOCIAL_SHARE => 'Social Network'
    ];

    protected $fillable = [
        'ser_type', 'ser_user_id', 'ser_user_email', 'ser_user_phone', 'ser_user_phone_code', 'ser_code', 'ser_created_at', 'ser_expiry', 'ser_publish'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'user_referral_code', 'ser_code');
    }

    public static function createReferralCode($code, $email, $type)
    {
        $expiry = Carbon::now()->addDays(Configuration::getValue('REWARD_POINTS_SOCIAL_SHARING_POINTS_VALIDITY'));
        DB::beginTransaction();
        try {
            ShareEarnRecord::create([
                'ser_type' => $type,
                'ser_user_id' => Auth::user()->user_id,
                'ser_user_email' => $email,
                'ser_code' => $code,
                'ser_created_at' => Carbon::now(),
                'ser_expiry' =>  $expiry->format('Y-m-d H:i')
            ]);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public static function addRewardPoints($code, $userId)
    {
        $data = [];
        if (ShareEarnRecord::where('ser_code', $code)->where('ser_expiry', '>', Carbon::now()->format('Y-m-d H:i'))->count() >= 1) {
            ShareEarnRecord::addRewards($code, $userId);
        } else {
            $data['message'] = "Referral Code Expired";
            return $data;
        }
    }
    
    public static function addRewards($code, $userId)
    {
        $config = Configuration::getKeyValues(['REWARD_POINTS_SOCIAL_SHARING_POINTS_ONSIGNUP','REWARD_POINTS_PURCHASE_POINTS_VALIDITY']);
        $shareRecord = ShareEarnRecord::where('ser_code', $code)->where('ser_expiry', '>', Carbon::now()->format('Y-m-d H:i'))->get()->first();
        if (isset($shareRecord->ser_user_id) && !empty($shareRecord->ser_user_id)) {
            $user = User::getUserById($userId);
            $sharingUser = User::getUserById($shareRecord->ser_user_id);
            $insertID = UserRewardPoint::create([
                'urp_user_id' => $shareRecord->ser_user_id,
                'urp_referral_user_id' => $userId,
                'urp_comments' => 'Earned points via signup (' . $user->user_email . ')',
                'urp_points' => $config['REWARD_POINTS_SOCIAL_SHARING_POINTS_ONSIGNUP'],
                'urp_type' => UserRewardPoint::REGISTRATION_POINT,
                'urp_order_id' => 0,
                'urp_date_added' => Carbon::now(),
                'urp_date_expiry' => Carbon::now()->addDays($config['REWARD_POINTS_PURCHASE_POINTS_VALIDITY']),
             ])->urp_id;

            UserRewardPointBreakup::create([
                'urpbreakup_urp_id' => $insertID,
                'urpbreakup_referral_user_id' => $userId,
                'urpbreakup_used_order_id' => 0,
                'urpbreakup_points' => $config['REWARD_POINTS_SOCIAL_SHARING_POINTS_ONSIGNUP'],
                'urpbreakup_used' => 0,
                'urp_date_added' => Carbon::now(),
                'urpbreakup_expiry' => Carbon::now()->addDays($config['REWARD_POINTS_PURCHASE_POINTS_VALIDITY']),
            ]);

            self::userRewardonSharing($sharingUser, $config, $user);
            $phoneCode = '';
            if (!empty($user->user_phone_country_id)) {
                $country = Country::getCountries(['country_phonecode'], ['country_id' => $user->user_phone_country_id])->first();
                $phoneCode = !empty($country->country_phonecode) ? '+' . $country->country_phonecode : '';
            }
            $emailField = !empty($user->user_email) ? $user->user_email : '';
            if ($emailField == '' && (!empty($user->user_facebook_id) || !empty($user->user_instagram_id) || !empty($user->user_google_id))) {
                $emailField = $user->user_fname;
            }
            $phoneField = !empty($user->user_phone) ? $user->user_phone : '';
            if ($shareRecord->ser_type == self::SOCIAL_SHARE) {
                ShareEarnRecord::create([
                    'ser_type' => $shareRecord->ser_type,
                    'ser_user_id' => $shareRecord->ser_user_id,
                    'ser_user_email' => $emailField,
                    'ser_user_phone_code' => $phoneCode,
                    'ser_user_phone' => $phoneField,
                    'ser_code' => $shareRecord->ser_code,
                    'ser_created_at' => Carbon::now(),
                    'ser_expiry' =>  $shareRecord->ser_expiry
                ]);
            }
            $data = EmailHelper::getEmailData('rewards_earned_on_social_sharing');
            $priority = $data['body']->etpl_priority;
            $data['subject'] = str_replace('{rewardPoints}', $config['REWARD_POINTS_SOCIAL_SHARING_POINTS_ONSIGNUP'], $data['body']->etpl_subject);
            $data['body'] = str_replace('{name}', $sharingUser->user_name, $data['body']->etpl_body);
            $data['body'] = str_replace('{rewardPoints}', $config['REWARD_POINTS_SOCIAL_SHARING_POINTS_ONSIGNUP'], $data['body']);
            $data['body'] = str_replace('{referredUser}', (($emailField != '') ? $emailField : $phoneField), $data['body']);
            $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
            $data['to'] = $sharingUser->user_email;
            sendPushNotification($userId, 'rewards_earned_on_social_sharing', ['reward_points' => $config['REWARD_POINTS_SOCIAL_SHARING_POINTS_ONSIGNUP']]);
            dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);
            
            return true;
        } else {
            return false;
        }
    }
 
    public static function getInvitesListing($userId, $row = 0)
    {
        $query = ShareEarnRecord::select('ser_id', 'ser_type', 'ser_user_email', 'ser_user_phone', 'ser_user_phone_code', 'ser_code', 'ser_created_at')
        ->where('ser_user_id', $userId)
        ->where(function ($q) {
            $q->where('ser_user_email', '<>', '')->orWhere('ser_user_phone', '<>', '');
        })
        ->with('user:user_referral_code,user_email')->latest('ser_id');
        if ($row != 0) {
            return $query->skip($row)->take(5)->get()->toArray();
        } else {
            return $query->paginate(10);
        }
    }

    private static function userRewardonSharing($sharingUser, $config, $user)
    {
        $insertID = UserRewardPoint::create([
            'urp_user_id' => $user->user_id,
            'urp_referral_user_id' => 0,
            'urp_comments' => 'Signup Bonus Points',
            'urp_points' => $config['REWARD_POINTS_SOCIAL_SHARING_POINTS_ONSIGNUP'],
            'urp_type' => UserRewardPoint::SIGNUP_BONUS,
            'urp_order_id' => 0,
            'urp_date_added' => Carbon::now(),
            'urp_date_expiry' => Carbon::now()->addDays($config['REWARD_POINTS_PURCHASE_POINTS_VALIDITY']),
         ])->urp_id;

        UserRewardPointBreakup::create([
            'urpbreakup_urp_id' => $insertID,
            'urpbreakup_referral_user_id' => 0,
            'urpbreakup_used_order_id' => 0,
            'urpbreakup_points' => $config['REWARD_POINTS_SOCIAL_SHARING_POINTS_ONSIGNUP'],
            'urpbreakup_used' => 0,
            'urp_date_added' => Carbon::now(),
            'urpbreakup_expiry' => Carbon::now()->addDays($config['REWARD_POINTS_PURCHASE_POINTS_VALIDITY']),
        ]);

        $data = EmailHelper::getEmailData('rewards_earned_on_signup_bonus');
        $priority = $data['body']->etpl_priority;
        $data['subject'] = str_replace('{rewardPoints}', $config['REWARD_POINTS_SOCIAL_SHARING_POINTS_ONSIGNUP'], $data['body']->etpl_subject);
        $data['body'] = str_replace('{name}', $user->user_name, $data['body']->etpl_body);
        $data['body'] = str_replace('{InviteeFirstname}',$sharingUser->user_fname , $data['body']);
        $data['body'] = str_replace('{rewardPoints}', $config['REWARD_POINTS_SOCIAL_SHARING_POINTS_ONSIGNUP'], $data['body']);
        $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
        $data['to'] = $user->user_email;
        dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);
    }
}
