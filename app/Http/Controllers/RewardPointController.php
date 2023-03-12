<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\YokartController;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserRewardPoint;
use App\Models\ShareEarnRecord;
use App\Models\Configuration;
use App\Helpers\EmailHelper;
use App\Jobs\SendNotification;
use Inertia\Inertia;
use Auth;

class RewardPointController extends YokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function rewardPointsListing(Request $request)
    {
        if (getConfigValueByName('REWARD_POINTS_ENABLE') == 0) {
            abort(404);
        }
        $usablePoints = UserRewardPoint::getRecordsByUserId($this->user->user_id, true);
        $shopUrl = route('productListing');
        $rewardTypes = UserRewardPoint::TYPE;
        $configVal = getConfigValues(['REWARD_POINTS_SPENDING_POINTS_EARNINGS', 'REWARD_POINTS_SPENDING_POINTS_AMOUNT']);
        $pointsWorth = $usablePoints * ($configVal['REWARD_POINTS_SPENDING_POINTS_AMOUNT'] / $configVal['REWARD_POINTS_SPENDING_POINTS_EARNINGS']);
        return Inertia::render('Activity/RewardPoints/Index', ['usablePoints' => $usablePoints, 'pointsWorth' => displayPrice($pointsWorth), 'shopUrl' => $shopUrl, 'rewardTypes' => $rewardTypes]);
    }

    public function rewardsListing(Request $request)
    {
        $request['paginate'] = 5;
        $records = UserRewardPoint::getRewardPointsListing($this->user->user_id, $request, $request->input('total'));
        
        return jsonresponse(true, '', $records);
    }

    public function shareAndEarn(Request $request)
    {
        $configVal = getConfigValues(['REWARD_POINTS_ENABLE', 'REWARD_POINTS_SOCIAL_SHARING_POINTS_ENABLE']);
        if ($configVal['REWARD_POINTS_ENABLE'] == 0 || $configVal['REWARD_POINTS_SOCIAL_SHARING_POINTS_ENABLE'] == 0) {
            abort(404);
        }
        $expiry = Carbon::now()->addDays(Configuration::getValue('REWARD_POINTS_SOCIAL_SHARING_POINTS_VALIDITY'));
        $shareEarnCode = ShareEarnRecord::where('ser_user_id', $this->user->user_id)
            ->where('ser_type', ShareEarnRecord::SOCIAL_SHARE)
            ->where('ser_user_email', null)
            ->where('ser_user_phone', null)
            ->where('ser_expiry', '>', Carbon::now()->format('Y-m-d H:i'))
            ->get()->first();
        if (empty($shareEarnCode->ser_code)) {
            $shareEarnCode = ShareEarnRecord::create([
                'ser_user_id' => $this->user->user_id,
                'ser_type' => ShareEarnRecord::SOCIAL_SHARE,
                'ser_code' => randomString(12),
                'ser_created_at' => Carbon::now(),
                'ser_expiry' =>  $expiry->format('Y-m-d H:i')
            ]);
        }
        $referralCode = $shareEarnCode->ser_code;
        
        $totalEarnPoints = UserRewardPoint::referralRewardPoints($this->user->user_id);
        $socialShares = shareThis();
        return Inertia::render('Activity/ShareEarn', ['totalEarnPoints' => $totalEarnPoints, 'socialShares' => $socialShares->except('SHARETHIS_PINTEREST'), 'referralCode' => $referralCode]);
    }

    public function sharedListing(Request $request)
    {
        $result['referralTypes'] = ShareEarnRecord::SHARE_TYPE;
        $result['invites'] = ShareEarnRecord::getInvitesListing($this->user->user_id);
        return jsonresponse(true, '', $result);
    }
    public function loadRecords(Request $request)
    {
        $invites = ShareEarnRecord::getInvitesListing($this->user->user_id, $request->input('total'));
        return jsonresponse(true, '', $invites);
    }

    public function sendReferralCode(Request $request)
    {
        $configVal = getConfigValues(['REWARD_POINTS_ENABLE', 'REWARD_POINTS_SOCIAL_SHARING_POINTS_ENABLE']);
        if ($configVal['REWARD_POINTS_ENABLE'] == 0 || $configVal['REWARD_POINTS_SOCIAL_SHARING_POINTS_ENABLE'] == 0) {
            abort(404);
        }
        $emails = $request->input('userEmail');
        if ($emails != "") {
            $referralCode = randomString(12);
            $registerationUrl = url('/register?referralcode=' . $referralCode);
            $slug = 'share_and_earn';
            $emails = str_replace(' ', '', $emails);
            $emails = explode(",", $emails);
            $messages = [];
            if (count($emails) > 0) {
                foreach ($emails as $key => $email) {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        if (ShareEarnRecord::createReferralCode($referralCode, $email, ShareEarnRecord::EMAIL_SHARE)) {
                            $data = EmailHelper::getEmailData($slug);
                            $priority = $data['body']->etpl_priority;
                            $data['subject'] = str_replace('{buyerName}', Auth::user()->user_name, $data['body']->etpl_subject);
                            $data['body'] = str_replace('{email}', $email, $data['body']->etpl_body);
                            $data['body'] = str_replace('{registrationLink}', $registerationUrl, $data['body']);

                            $data['body'] = str_replace('{buyerEmail}', Auth::user()->user_email, $data['body']);
                            $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
                            $data['to'] = $email;
                            $notificationData['email'] = $data;
                            dispatch(new SendNotification($notificationData, true, false, false))->onQueue($priority);
                            $messages[$key] = $email;
                        }
                    }
                }
                return jsonresponse(true, 'NOTI_INVITE_SENT_SUCCESSFULLY');
            }
        }
    }

    public function saveReferralCode(Request $request)
    {
        if ($request->input('ser_code') != '') {
            if (ShareEarnRecord::createReferralCode($request->input('ser_code'), '', ShareEarnRecord::SOCIAL_SHARE)) {
                return jsonresponse(true, '');
            } else {
                return jsonresponse(true, '');
            }
        }
    }
}
