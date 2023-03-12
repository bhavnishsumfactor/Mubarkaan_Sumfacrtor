<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\YokartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use App\Models\ShareEarnRecord;
use App\Models\Configuration;
use App\Models\UserRewardPoint;
use App\Helpers\EmailHelper;
use App\Jobs\SendNotification;

class ShareAndEarnController extends YokartController
{
    public function list(Request $request, $page)
    {
        $response = [];
        
        $invites = ShareEarnRecord::getInvitesForApp(Auth::guard('api')->user()->user_id, $page)->toArray();

        $response['total'] = $invites['total'];
        $response['pages'] = ceil($invites['total'] / $invites['per_page']);
        $response['invites'] = $invites['data'];
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }

    public function summary(Request $request)
    {
        $configVal = getConfigValues(['REWARD_POINTS_ENABLE', 'REWARD_POINTS_SOCIAL_SHARING_POINTS_ENABLE']);
        if ($configVal['REWARD_POINTS_ENABLE'] == 0 || $configVal['REWARD_POINTS_SOCIAL_SHARING_POINTS_ENABLE'] == 0) {
            abort(404);
        }
        $expiry = Carbon::now()->addDays(Configuration::getValue('REWARD_POINTS_SOCIAL_SHARING_POINTS_VALIDITY'));
        $shareEarnCode = ShareEarnRecord::where('ser_user_id', Auth::guard('api')->user()->user_id)
            ->where('ser_type', ShareEarnRecord::SOCIAL_SHARE)
            ->where('ser_user_email', null)
            ->where('ser_user_phone', null)
            ->where('ser_expiry', '>', Carbon::now()->format('Y-m-d H:i'))
            ->get()->first();
        if (empty($shareEarnCode->ser_code)) {
            $shareEarnCode = ShareEarnRecord::create([
                'ser_user_id' => Auth::guard('api')->user()->user_id,
                'ser_type' => ShareEarnRecord::SOCIAL_SHARE,
                'ser_code' => randomString(12),
                'ser_created_at' => Carbon::now(),
                'ser_expiry' =>  $expiry->format('Y-m-d H:i')
            ]);
        }
        $referralCode = $shareEarnCode->ser_code;
       
        $registerationUrl = url('/register?referralcode=' . $referralCode);
        $response = [
            'referral_url' => $registerationUrl,
            'referral_code' => $referralCode
        ];
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }

    public function sendInvites(Request $request)
    {
        $configVal = getConfigValues(['REWARD_POINTS_ENABLE', 'REWARD_POINTS_SOCIAL_SHARING_POINTS_ENABLE']);
        if ($configVal['REWARD_POINTS_ENABLE'] == 0 || $configVal['REWARD_POINTS_SOCIAL_SHARING_POINTS_ENABLE'] == 0) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_UNAUTHORIZED_ACCESS"));
        }
        $emails = $request->input('emails');
        if (! is_array($emails) && count($emails) === 0) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_EMAIL_IS_REQUIRED"));
        }
        $referralCode = randomString(12);
        $registerationUrl = url('/register?referralcode=' . $referralCode);
        $slug = 'share_and_earn';
        $messages = [];
        $sent = false;
        foreach ($emails as $key => $email) {
            $email = str_replace(' ', '', $email);
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
                $sent = true;
            }
        }
        if ($sent === false) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_NO_VALID_EMAIL_IS_INPUT"));
        }
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_INVITE_SENT_SUCCESSFULLY"));
    }
}
