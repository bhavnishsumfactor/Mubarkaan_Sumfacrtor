<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\Configuration;
use App\Models\Admin\Admin;
use Carbon\Carbon;
use Newsletter;
use App\Events\SystemNotification;
use App\Jobs\SendNotification;
use App\Helpers\EmailHelper;
use App\Models\NotificationTemplate;
use App\Models\User;
use Str;
use Auth;

class NewsletterSubscription extends YokartModel
{
    public $timestamps = false;
    protected $primaryKey = 'subscription_id';
    protected $fillable = ['subscription_provider', 'subscription_list_id', 'subscription_email', 'subscription_optin', 'subscription_token', 'subscription_expiry'];

    const PROVIDERS_TYPE = [
      'mailchimp' => 1
    ];

    const DEFAULT_PROVIDER = 'mailchimp';

    private static function getProvider()
    {
        return self::PROVIDERS_TYPE[self::DEFAULT_PROVIDER];
    }

    private static function getListId()
    {
        return Configuration::getValue('MAILCHIMP_LIST_ID');
    }

    private static function getApiKey()
    {
        return Configuration::getValue('MAILCHIMP_API_KEY');
    }

    private static function updateConfig($apiKey, $listId)
    {
        config(['newsletter.apiKey' => $apiKey, 'newsletter.lists.subscribers.id' => $listId]);
    }

    public static function checkToken($token)
    {
        $confirmationToken = NewsletterSubscription::select('subscription_id', 'subscription_email', 'subscription_expiry')->where('subscription_token', $token)->first();
        if (empty($confirmationToken)) {
            return ['status' => false, 'message' => __('Your link seems to have expired. Please contact site admin.')];
        } elseif (!empty($confirmationToken) && Carbon::parse($confirmationToken->subscription_expiry)->addMinutes(config('app.expiredToken')) < Carbon::now()) {
            return ['status' => false, 'message' => __('Your link seems to have expired. Please contact site admin.')];
        }
        self::updateConfig(self::getApiKey(), self::getListId());
        return ['status' => true, 'data' => $confirmationToken];
    }

    public static function checkUnsubscribeToken($token)
    {
        $confirmationToken = NewsletterSubscription::select('subscription_id', 'subscription_email', 'subscription_expiry')->where('subscription_token', $token)->first();
        if (empty($confirmationToken)) {
            return ['status' => false, 'message' => __('Your link seems to have expired. Please contact site admin.')];
        }
        self::updateConfig(self::getApiKey(), self::getListId());
        return ['status' => true, 'data' => $confirmationToken];
    }

    public static function saveSubscriber($email, $doubleOptIn, $token)
    {
        $provider = self::getProvider();
        $listId = self::getListId();

        $subscribed = NewsletterSubscription::where('subscription_provider', $provider)
        ->where('subscription_list_id', $listId)
        ->where('subscription_email', $email)->count();
        if ($subscribed > 0) {
            return true;
        }
        $data = [
            'subscription_provider' => $provider,
            'subscription_list_id' => $listId,
            'subscription_email' => $email
        ];
        if ($doubleOptIn == 1) {
            $data['subscription_optin'] = false;
            $data['subscription_token'] = $token;
            $data['subscription_expiry'] = Carbon::now();
        } else {
            $data['subscription_optin'] = true;
            $data['subscription_token'] = $token;
            $data['subscription_expiry'] = '';
        }
        
        NewsletterSubscription::create($data);

        self::updateConfig(self::getApiKey(), $listId);
        return false;
    }

    public static function subscribe($email)
    {
        $configData = getConfigValues(['NEWSLETTER_DOUBLE_OPTIN', 'MAILCHIMP_STATUS']);
        $token = Str::random(64);
        $alreadySubscribed = NewsletterSubscription::saveSubscriber($email, $configData['NEWSLETTER_DOUBLE_OPTIN'], $token);
        if ($alreadySubscribed) {
            return ['status' => false, 'message' => __('You are already part of our mailing list.')];
        }
        
        if ($configData['NEWSLETTER_DOUBLE_OPTIN'] == 0) {
            if ($configData['MAILCHIMP_STATUS'] == 1) {
                Newsletter::subscribe($email);
            }
            
            /** trigger event for system notification */
            $data = NotificationTemplate::getBySlug('newsletter_marketing_subscription');
            $message = str_replace('{email}', $email, $data->ntpl_body);
            event(new SystemNotification([
                'type' => Notification::NEWSLETTER_SUBSCRIBED,
                'message' => $message
            ]));
            
            NewsletterSubscription::sendThanksEmail($email, $token);
            return ['status' => true, 'message' => __('You are successfully subscribed to our mailing list.')];
        } else {
            NewsletterSubscription::sendDoubleOptInEmail($email, $token);
            return ['status' => true, 'message' => __('Your subscription request to our mailing list is successfully placed. Please re-confirm your subscription via email sent on your registered email address.')];
        }
    }

    public static function sendThanksEmail($email, $token)
    {
        $notificationData = [];
        $sendSms = false;
        $data = EmailHelper::getEmailData('newsletter_subscription_success');
        $priority = $data['body']->etpl_priority;
        $data['subject'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']->etpl_subject);
        $data['body'] = str_replace('{user}', $email, $data['body']->etpl_body);
        $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
        $data['body'] = str_replace('{websiteUrl}', url('/'), $data['body']);
        $data['body'] = str_replace('{contactUsEmail}', (getConfigValueByName('BUSINESS_EMAIL') ?? ''), $data['body']);
        $data['unsubscribeToken'] = $token;
        $data['to'] = $email;
        $notificationData['email'] = $data;
        $user = User::select('user_id', 'user_fname', 'user_lname', 'user_email', 'user_phone_country_id', 'user_phone')->where('user_email', trim($email))->first();
        if (!empty($user->user_phone) && !empty($user->user_phone_country_id)) {
            $country = Country::select('country_phonecode')->where('country_id', $user->user_phone_country_id)->first();
            if (!empty($country->country_phonecode) && !empty($admin['admin_phone'])) {
                $data = SmsTemplate::getSMSTemplate('newsletter_subscription_success');
                $priority = $data['body']->stpl_priority;
                $data['body'] = str_replace('{name}', $user->user_fname.' '.$user->user_lname, $data['body']->stpl_body);
                $notificationData['sms'] = [
                    'message' => $data['body'],
                    'recipients' => array('+' . $country->country_phonecode . $user->user_phone)
                ];
                $sendSms = true;
            }
        }
        
        dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);

        $superAdmin = Admin::getSuperAdminId();
        if (isset($superAdmin->admin_email) && !empty($superAdmin->admin_email)) {
            $adminNotification = [];
            $data = EmailHelper::getEmailData('newsletter_subscription_success_admin');
            $priority = $data['body']->etpl_priority;
            $data['subject'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']->etpl_subject);
            $data['body'] = str_replace('{adminName}', $superAdmin->admin_name, $data['body']->etpl_body);
            $data['body'] = str_replace('{buyerName}', $email, $data['body']);
            $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
            $data['to'] = $superAdmin->admin_email;
            $adminNotification['email'] = $data;
            dispatch(new SendNotification($adminNotification, true, false, false))->onQueue($priority);
        }
    }

    public static function sendDoubleOptInEmail($email, $token)
    {
        $notificationData = [];
        $data = EmailHelper::getEmailData('newsletter_subscription_confirmation');
        $priority = $data['body']->etpl_priority;
        $data['subject'] = $data['body']->etpl_subject;
        $data['body'] = str_replace('{email}', $email, $data['body']->etpl_body);
        $data['body'] = str_replace('{confirmlink}', url('newsletter/confirm', $token), $data['body']);
        $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
        $data['to'] = $email;
        $notificationData['email'] = $data;
        
        dispatch(new SendNotification($notificationData, true, false, false))->onQueue($priority);
    }

    public static function sendUnsubscriptionEmail($email)
    {
        /** Newsletter Subscriber Notification */
        $notificationData = [];
        $sendSms = false;
        $data = EmailHelper::getEmailData('newsletter_subscription_ended');
        $priority = $data['body']->etpl_priority;
        $data['subject'] = $data['body']->etpl_subject;
        $data['body'] = str_replace('{user}', $email, $data['body']->etpl_body);
        $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
        $data['body'] = str_replace('{contactUsEmail}', (getConfigValueByName('BUSINESS_EMAIL') ?? ''), $data['body']);
        $data['to'] = $email;
        $notificationData['email'] = $data;
        $user = User::select('user_id', 'user_fname', 'user_lname', 'user_email', 'user_phone_country_id', 'user_phone')->where('user_email', trim($email))->first();
        if (!empty($user->user_phone) && !empty($user->user_phone_country_id)) {
            $country = Country::select('country_phonecode')->where('country_id', $user->user_phone_country_id)->first();
            if (!empty($country->country_phonecode)) {
                $data = SmsTemplate::getSMSTemplate('newsletter_subscription_ended');
                $priority = $data['body']->stpl_priority;
                $data['body'] = str_replace('{name}', $user->user_fname.' '.$user->user_lname, $data['body']->stpl_body);
                $notificationData['sms'] = [
                    'message' => $data['body'],
                    'recipients' => array('+' . $country->country_phonecode . $user->user_phone)
                ];
                $sendSms = true;
            }
        }
        dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);
        
        /** Newsletter Admin Notification */
        $superAdmin = Admin::getSuperAdminId();
        if (isset($superAdmin->admin_email) && !empty($superAdmin->admin_email)) {
            $adminNotification = [];
            $data = EmailHelper::getEmailData('newsletter_subscription_ended_admin');
            $priority = $data['body']->etpl_priority;
            $data['subject'] = $data['body']->etpl_subject;
            $data['body'] = str_replace('{adminName}', $superAdmin->admin_name, $data['body']->etpl_body);
            $data['body'] = str_replace('{user}', $email, $data['body']);
            $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
            $data['to'] = $superAdmin->admin_email;
            $adminNotification['email'] = $data;
            dispatch(new SendNotification($adminNotification, true, false, false))->onQueue($priority);
        }
    }
}
