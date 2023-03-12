<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\Admin\Admin;
use App\Models\Admin\AdminRole;
use Carbon\Carbon;
use Auth;
use App\Helpers\FileHelper;
use App\Models\AttachedFile;
use App\Helpers\EmailHelper;
use App\Jobs\SendNotification;

class ThreadMessage extends YokartModel
{
    protected $primaryKey = 'message_id';
    public $timestamps = false;
    
    const CREATED_AT = 'message_created_at';
    
    protected $fillable = [
        'message_thread_id', 'message_from_id','message_from_type',
        'message_to','message_text','message_is_unread','message_deleted','message_created_at','message_from_admin'
    ];
    
    public static function saveMessage($request, $loggedInUserId)
    {
        try {
            $superAdminId = Admin::getSuperAdminId();
            $message = ThreadMessage::create([
                'message_thread_id' => $request['thread_id'],
                'message_from_id' => $loggedInUserId,
                'message_from_type' => 'App\Models\User',
                'message_to' => $superAdminId->admin_id,
                'message_text'  => $request['txtMessage'],
                'message_is_unread' => \Config::get('constants.YES'),
                'message_deleted' => \Config::get('constants.NO'),
                'message_from_admin' => \Config::get('constants.NO'),
                'message_created_at' => Carbon::now()
            ]);
            if (!empty($request['file'])) {
                $uploadedFileName = FileHelper::uploadDigitalFiles($request['file']);
                $response = AttachedFile::saveOrUpdateFile(AttachedFile::getConstantVal('messageFile'), $message->message_id, $uploadedFileName, $request['file']->getClientOriginalName());
                $message->upload_images = 1;
            }
            $admins = Admin::getAdminsByModule(AdminRole::MESSAGES);
            $thread = Thread::with('product:prod_id,prod_name')->where('thread_id', $request['thread_id'])->first();
            if (!empty($admins)) {
                foreach ($admins as $key => $admin) {
                    $notificationData = [];
                    $sendSms = false;
                    $data = EmailHelper::getEmailData("message_admin");
                    $priority = $data['body']->etpl_priority;
                    $data['subject'] = str_replace('{buyerName}', Auth::user()->user_fname.' '. Auth::user()->user_lname, $data['body']->etpl_subject);
                    $data['subject'] = str_replace('{productName}', $thread->thread_product_name, $data['subject']);
                    $data['body'] = str_replace('{name}', $admin['admin_name'], $data['body']->etpl_body);
                    $data['body'] = str_replace('{buyerName}', Auth::user()->user_fname.' '. Auth::user()->user_lname, $data['body']);
                    $data['body'] = str_replace('{productName}', $thread->thread_product_name, $data['body']);
                    $data['body'] = str_replace('{productImage}', productImageUrl(
                        $thread->thread_product_id,
                        !empty($thread->thread_product_variant) ? str_replace("_", "|", $thread->thread_product_variant) : "0"
                    ), $data['body']);
                    $data['body'] = str_replace('{productUrl}', getRewriteUrl("products", $thread->thread_product_id), $data['body']);
                    $data['body'] = str_replace('{inboxUrl}', url("/admin#/thread/" . $request['thread_id'] . "/messages"), $data['body']);
                    $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
                    $data['to'] = $admin['admin_email'];
                    $notificationData['email'] = $data;
                    if (!empty($admin['admin_phone']) && !empty($admin['admin_phone_country_id'])) {
                        $country = Country::select('country_phonecode')->where('country_id', $admin['admin_phone_country_id'])->first();
                        if (!empty($country->country_phonecode) && !empty($admin['admin_phone'])) {
                            $data = SmsTemplate::getSMSTemplate('message_admin');
                            $priority = $data['body']->stpl_priority;
                            $data['body'] = str_replace('{name}', $admin['admin_name'], $data['body']->stpl_body);
                            $data['body'] = str_replace('{productName}', $thread->thread_product_name, $data['body']);
                            $notificationData['sms'] = [
                                'message' => $data['body'],
                                'recipients' => array('+' . $country->country_phonecode . $admin['admin_phone'])
                            ];
                            $sendSms = true;
                        }
                    }
                    dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);
                }
            }
            return $message;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function saveAdminMessage($request, $loggedInAdminId)
    {
        $notificationData = [];
        $sendSms = false;
        $previousMessage = ThreadMessage::where('message_thread_id', $request['threadId'])->get()->first();
        $data = ThreadMessage::create([
            'message_thread_id' => $request['threadId'],
            'message_from_id' => $loggedInAdminId,
            'message_from_type' => 'App\Models\Admin\Admin',
            'message_to' => $previousMessage->message_from_id,
            'message_from_admin' => \Config::get('constants.YES'),
            'message_text'  => $request['txtMessage'],
            'message_is_unread' => \Config::get('constants.YES'),
            'message_deleted' => \Config::get('constants.NO'),
            'message_created_at' => Carbon::now()
        ]);
        if (!empty($request['file'])) {
            $uploadedFileName = FileHelper::uploadDigitalFiles($request['file']);
            $response = AttachedFile::saveOrUpdateFile(AttachedFile::getConstantVal('messageFile'), $data->message_id, $uploadedFileName, $request['file']->getClientOriginalName());
            $data->upload_images = 1;
        }
        $threadData = Thread::where('thread_id', $request['threadId'])->first(); //->with('product:prod_id,prod_name')
        $user = User::getUserById($previousMessage->message_from_id);
        $messageData = EmailHelper::getEmailData('message_buyer');
        $priority = $messageData['body']->etpl_priority;
        $messageData['subject'] = str_replace('{websiteName}', env('APP_NAME'), $messageData['body']->etpl_subject);
        $messageData['subject'] = str_replace('{productName}', $threadData->thread_product_name, $messageData['subject']);
        $messageData['body'] = str_replace('{name}', $user->user_fname.' '.$user->user_lname, $messageData['body']->etpl_body);
        $messageData['body'] = str_replace('{inboxUrl}', url("/user/messages"), $messageData['body']);
        $messageData['body'] = str_replace('{websiteName}', env('APP_NAME'), $messageData['body']);
        $messageData['to'] = $user->user_email;

        $notificationData['email'] = $messageData;
        if (!empty($user->user_phone) && !empty($user->user_phone_country_id)) {
            $country = Country::select('country_phonecode')->where('country_id', $user->user_phone_country_id)->first();
            if (!empty($country->country_phonecode)) {
                $smsdata = SmsTemplate::getSMSTemplate('message_buyer');
                $priority = $smsdata['body']->stpl_priority;
                $smsdata['body'] = str_replace('{name}', $user->user_fname . ' '. $user->user_lname, $smsdata['body']->stpl_body);
                $smsdata['body'] = str_replace('{productName}', $threadData->thread_product_name, $smsdata['body']);
                $notificationData['sms'] = [
                    'message' => $smsdata['body'],
                    'recipients' => array('+' . $country->country_phonecode . $user->user_phone)
                ];
                $sendSms = true;
            }
        }
        sendPushNotification($user->user_id, 'buyer_message', ['product_name' => $threadData->thread_product_name, 'id' => $request['threadId']]);
        dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);
        return $data;
    }

    public function messageFrom()
    {
        return $this->morphTo();
    }

    public function uploadImages()
    {
        return $this->hasOne('App\Models\AttachedFile', 'afile_record_id', 'message_id')->where('attached_files.afile_type', '=', AttachedFile::SECTIONS['messageFile']);
    }

    public function thread()
    {
        return $this->belongsTo('App\Models\Thread', 'message_thread_id', 'thread_id');
    }

    public static function getMessageCount()
    {
        return ThreadMessage::where('message_is_unread', '=', config('constants.NO'))
            ->where('message_from_admin', '=', config('constants.YES'))->count();
    }

    public static function getUserUnreadMessageCount($userId)
    {
        return ThreadMessage::where('message_is_unread', '=', config('constants.YES'))->where('message_to', $userId)
            ->where('message_from_admin', '=', config('constants.YES'))->count();
    }
    
    public static function readUserMessages($threadId)
    {
        ThreadMessage::where('message_thread_id', $threadId)->where('message_is_unread', '=', config('constants.YES'))
        ->where('message_from_admin', '=', config('constants.NO'))
        ->update(['message_is_unread' => config('constants.NO')]);
    }
}
