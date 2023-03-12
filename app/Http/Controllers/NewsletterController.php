<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\YokartController;
use Newsletter;
use Illuminate\Support\Facades\Validator;
use App\Models\NewsletterSubscription;
use App\Events\SystemNotification;
use App\Models\NotificationTemplate;
use App\Models\Notification;
use Str;

class NewsletterController extends YokartController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);
        if ($validator->fails()) {
            return jsonresponse(false, $validator->errors()->first('email'));
        }
        $email = $request->input('email');
        $response = NewsletterSubscription::subscribe($email);
        
        if ($response['status']) {
            return jsonresponse(true, $response['message']);
        } else {
            return jsonresponse(false, $response['message']);
        }
    }

    public function confirmNewsletter(Request $request, $token)
    {
        if (empty($token)) {
            toastr()->error(__('Your link seems to have expired. Please contact site admin.'));
            return redirect()->route('home');
        }
        $tokenIsValid = NewsletterSubscription::checkToken($token);
        if (!$tokenIsValid['status']) {
            toastr()->error($tokenIsValid['message']);
            return redirect()->route('home');
        }
        $token = Str::random(64);
        NewsletterSubscription::where('subscription_id', $tokenIsValid['data']->subscription_id)
        ->update([
            'subscription_optin' => true,
            'subscription_token' => $token,
            'subscription_expiry' => ''
        ]);
        $checkMailChimpStatus = getConfigValueByName('MAILCHIMP_STATUS');
        if ($checkMailChimpStatus == 1) {
            Newsletter::subscribe($tokenIsValid['data']->subscription_email);
        }

        /** trigger event for system notification */
        $data = NotificationTemplate::getBySlug('newsletter_marketing_subscription');
        $message = str_replace('{email}', $tokenIsValid['data']->subscription_email, $data->ntpl_body);
        event(new SystemNotification([
            'type' => Notification::NEWSLETTER_SUBSCRIBED,
            'message' => $message
        ]));

        NewsletterSubscription::sendThanksEmail($tokenIsValid['data']->subscription_email, $token);
        // toastr()->success(__('You are now subscribed to our Newsletter'));
        return redirect()->route('home')->with('subscribed', __('NOTI_SUCCESSFULLY_SUBSCRIBE_MAILINIG_LIST'));
    }

    public function unsubscribeNewsletter(Request $request, $token)
    {
        if (empty($token)) {
            toastr()->error(__('Invalid url.'));
            return redirect()->route('home');
        }
        $tokenIsValid = NewsletterSubscription::checkUnsubscribeToken($token);
        if (!$tokenIsValid['status']) {
            toastr()->error($tokenIsValid['message']);
            return redirect()->route('home');
        }
        Newsletter::unsubscribe($tokenIsValid['data']->subscription_email);
        NewsletterSubscription::where('subscription_id', $tokenIsValid['data']->subscription_id)->delete();
        NewsletterSubscription::sendUnsubscriptionEmail($tokenIsValid['data']->subscription_email);
        // toastr()->success(__('You have unsubscribed to our Newsletter'));
        return redirect()->route('home')->with('unsubscribed', __('NOTI_SUCCESSFULLY_UNSUBSCRIBE_MAILINIG_LIST'));
    }
}
