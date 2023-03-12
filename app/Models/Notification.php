<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\Admin\NotificationToAdmin;
use Carbon\Carbon;
use Auth;
use App\Helpers\EmailHelper;
use Twilio\TwilioManagement\Models\Twilio;
use DB;
use Illuminate\Support\Str;

class Notification extends YokartModel
{
    protected $primaryKey = 'notify_id';

    const CREATED_AT = 'notify_created_at';
    const UPDATED_AT = 'notify_updated_at';

    protected $fillable = ['notify_type', 'notify_record_id', 'notify_record_subid',
        'notify_from_user_id', 'notify_msg',
        'notify_created_at', 'notify_updated_at'];

    public const PRODUCT_REVIEW_POSTED = 1;
    public const PRODUCT_REVIEW_EDITED = 2;
    public const ORDER_CREATED_BY_USER = 3;
    public const ORDER_CREATED_BY_ADMIN = 4;
    public const ORDER_CANCELLATION = 5;
    public const ORDER_PARTIAL_CANCELLATION = 6;
    public const ORDER_RETURN = 7;
    public const ORDER_PARTIAL_RETURN = 8;
    public const ORDER_PAYMENT = 9;
    public const ORDER_COMMENT = 10;
    public const ORDER_CANCELLATION_COMMENT = 11;
    public const ORDER_RETURN_COMMENT = 12;
    public const BLOG_COMMENT = 13;
    public const USER_REGISTER = 14;
    public const NEWSLETTER_SUBSCRIBED = 15;
    public const GDPR_DATA_REQUEST = 16;
    public const GDPR_DELETE_REQUEST = 17;
    public const TODO_REMINDER = 18;

    const NOTIFY_TYPE = [
        'PRODUCT_REVIEW_POSTED' => self::PRODUCT_REVIEW_POSTED,
        'PRODUCT_REVIEW_EDITED' => self::PRODUCT_REVIEW_EDITED,
        'ORDER_CREATED_BY_USER' => self::ORDER_CREATED_BY_USER,
        'ORDER_CREATED_BY_ADMIN' => self::ORDER_CREATED_BY_ADMIN,
        'ORDER_CANCELLATION' => self::ORDER_CANCELLATION,
        'ORDER_PARTIAL_CANCELLATION' => self::ORDER_PARTIAL_CANCELLATION,
        'ORDER_RETURN' => self::ORDER_RETURN,
        'ORDER_PARTIAL_RETURN' => self::ORDER_PARTIAL_RETURN,
        'ORDER_PAYMENT' => self::ORDER_PAYMENT,
        'ORDER_COMMENT' => self::ORDER_COMMENT,
        'ORDER_CANCELLATION_COMMENT' => self::ORDER_CANCELLATION_COMMENT,
        'ORDER_RETURN_COMMENT' => self::ORDER_RETURN_COMMENT,
        'BLOG_COMMENT' => self::BLOG_COMMENT,
        'USER_REGISTER' => self::USER_REGISTER,
        'NEWSLETTER_SUBSCRIBED' => self::NEWSLETTER_SUBSCRIBED,
        'GDPR_DATA_REQUEST' => self::GDPR_DATA_REQUEST,
        'GDPR_DELETE_REQUEST' => self::GDPR_DELETE_REQUEST,
        'TODO_REMINDER' => self::TODO_REMINDER
    ];

    public static function getNotifications($row = 0, $type='')
    {
        /*return Notification::select('notify_id', 'notify_type', 'notify_record_id', 'notify_msg', 'nta_read', 'notify_created_at')
            ->join('notification_to_admins', 'nta_notify_id', 'notify_id')
            ->where('nta_admin_id', Auth::guard('admin')->user()->admin_id)
            ->orderBy('notify_id', 'DESC')
            ->offset($row)
            ->take(15)
            ->get();*/
            
        $query = Notification::select('notify_id', 'notify_type', 'notify_record_id', 'notify_msg', 'nta_read', 'notify_created_at')
                ->join('notification_to_admins', 'nta_notify_id', 'notify_id')
                ->where('nta_admin_id', Auth::guard('admin')->user()->admin_id);
        if ($type != '') {
            switch ($type) {
                case "week":
                    $query->whereBetween('notify_created_at', [ Carbon::now()->subDays(7)->format('Y-m-d')." 00:00:00", Carbon::now()->format('Y-m-d')." 23:59:59"]);
                break;
                case "month":
                    $query->whereBetween('notify_created_at', [ Carbon::now()->subDays(30)->format('Y-m-d')." 00:00:00", Carbon::now()->format('Y-m-d')." 23:59:59"]);
                break;
            }
        }
        return $query->offset($row)->take(15)->orderBy('notify_id', 'DESC')->get();
    }

    public static function getNotificationCount()
    {
        return Notification::join('notification_to_admins', 'nta_notify_id', 'notify_id')
        ->where('nta_admin_id', Auth::guard('admin')->user()->admin_id)
        ->count();
    }

    public static function getUnreadNotificationCount()
    {
        return Notification::join('notification_to_admins', 'nta_notify_id', 'notify_id')
        ->where('nta_admin_id', Auth::guard('admin')->user()->admin_id)
        ->where('nta_read', 0)
        ->count();
    }

    // public static function create($type, $recordId, $recordSubId, $fromUserId, $toUserId, $message)
    // {
    //     //Notification::create('ORDER_DISPATCHED', 1, 0, 2, 0, 'Order has been created.');
    //     Notification::insert([
    //         'notify_type' => Notification::NOTIFY_TYPE[$type],
    //         'notify_record_id' => $recordId,
    //         'notify_record_subid' => $recordSubId,
    //         'notify_from_user_id' => $fromUserId,
    //         'notify_msg' => $message,
    //         'notify_created_at' => Carbon::now(),
    //         'notify_updated_at' => Carbon::now()
    //     ]);
    // }

    /*send sms*/
    // $data = SmsTemplate::getSMSTemplate('forgot_password');
    // $priority = $data['body']->stpl_priority;
    // $data['body'] = str_replace('{name}', $admin->admin_name, $data['body']->stpl_body);
    // $data['body'] = str_replace('{description}', $reminder->todo_description, $data['body']);
    //echo Notification::sendNotification(array('sms'=>array('message'=>$data['body'], 'recipients'=> array('+919041448564'))), false, false, true);

    /*send email*/
    // $data = EmailHelper::getEmailData('forgot_password');
    // $etpl_subject = $data['body']->etpl_subject;
    // $priority = $data['body']->etpl_priority;
    // $data['body'] = str_replace('{name}', 'Rahul', $data['body']->etpl_body);
    // $data['to'] = 'sarabjit.singh@fatbit.in';
    // $data['subject'] = $etpl_subject;
    // Notification::sendNotification(array('email'=>$data), true, false, false);

    /*send sys notification*/
    // Notification::sendNotification(array('notify'=>[
    //   'type'=>'ORDER_CREATE',
    //   'record_id'=>1,
    //   'record_subid'=>0,
    //   'from_user_id'=>0,
    //   'to_user_id'=>1,
    //   'message'=>1
    //   ]), false, true, false);
    
    public static function sendNotification($data, $sendEmail = true, $sendNotification = false, $sendSms = false)
    {
        if ($sendEmail == true) {
            EmailHelper::sendMailTemplate($data['email']['to'], $data['email']['subject'], $data['email'], array());
        }

        if ($sendNotification == true) {
            $notifyId = Notification::insertGetId([
                'notify_type' => $data['notify']['type'],
                'notify_record_id' => $data['notify']['record_id'],
                'notify_record_subid' => $data['notify']['record_subid'],
                'notify_from_user_id' => $data['notify']['from_id'],
                'notify_msg' => $data['notify']['message'],
                'notify_created_at' => Carbon::now(),
                'notify_updated_at' => Carbon::now()
            ]);

            if (is_array($data['notify']['to_id'])) {
                foreach ($data['notify']['to_id'] as $adminId) {
                    NotificationToAdmin::insert([
                        'nta_notify_id' => $notifyId,
                        'nta_admin_id' => $adminId,
                        'nta_read' => 0
                    ]);
                }
            } else {
                NotificationToAdmin::insert([
                    'nta_notify_id' => $notifyId,
                    'nta_admin_id' => $data['notify']['to_id'],
                    'nta_read' => 0
                ]);
            }
        }

        if ($sendSms == true) {
            $package = Package::getActivePackage('sms');
            if($package){
                $configurations = PackageConfiguration::getConfigurations($package->pkg_id);
                $packageSlug = 'Twilio\TwilioManagement\Models\\'.ucwords($package->pkg_slug);
                $message = Str::limit($data['sms']['message'], 160, '');
                $packageSlug::sendSms($message, $data['sms']['recipients'], $configurations);
            }
        }
    }
}
