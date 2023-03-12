<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\Todo;
use App\Models\Admin\Admin;
use App\Models\Configuration;
use App\Models\Notification;
use App\Models\SmsTemplate;
use App\Models\Country;
use App\Helpers\EmailHelper;
use Carbon\Carbon;
use App\Jobs\SendNotification;
use App\Models\NotificationTemplate;
use App\Events\SystemNotification;

class SendTodoReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todoreminder:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send todo reminders to admin users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // echo 'cron started<br>';
        // echo 'DateTime: ' . Carbon::now()->format('Y-m-d H:i') . '<br>';
        $configurations = Configuration::getKeyValues(['TODO_REMINDER_EMAIL', 'TODO_REMINDER_SMS']);
        $reminders = Todo::getReminders(Carbon::now()->format('Y-m-d H:i'));

        if (!empty($reminders)) {
            foreach ($reminders as $key => $reminder) {
                $admin = Admin::getAdminById($reminder->todo_admin_id);
                $country = Country::select('country_phonecode')
                    ->where('country_id', $admin->admin_phone_country_id)->first();
                
                $notificationData = [];
                $sendEmail = false;
                $sendSms = false;
                $slug = 'todo_reminder';
                if ($configurations['TODO_REMINDER_SMS'] == 1 && !empty($admin->admin_phone) && !empty($country->country_phonecode)) {
                    /*send sms*/
                    $data = SmsTemplate::getSMSTemplate($slug);
                    $priority = $data['body']->stpl_priority;
                    $data['body'] = str_replace('{name}', $admin->admin_name, $data['body']->stpl_body);
                    $data['body'] = str_replace('{description}', $reminder->todo_description, $data['body']);
                    $notificationData['sms'] = [
                        'message' => $data['body'],
                        'recipients' => array('+' . $country->country_phonecode . $admin->admin_phone)
                    ];
                    $sendSms = true;
                }
                
                if ($configurations['TODO_REMINDER_EMAIL'] == 1) {
                    /*send email*/
                    $data = EmailHelper::getEmailData($slug);
                    $priority = $data['body']->etpl_priority;
                    $data['subject'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']->etpl_subject);
                    $data['body'] = str_replace('{name}', $admin->admin_name, $data['body']->etpl_body);
                    $data['body'] = str_replace('{description}', $reminder->todo_description, $data['body']);
                    $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
                    $data['to'] = $admin->admin_email;
                    $notificationData['email'] = $data;
                    $sendEmail = true;
                }

                /** trigger event for system notification */
                $data = NotificationTemplate::getBySlug('todo_task_reminder');
                $message = str_replace('{todoTitle}', $reminder->todo_description, $data->ntpl_body);
                $message = str_replace('{dateTime}', getConvertedAdminDateTime($reminder->todo_reminder_at), $message);
                event(new SystemNotification([
                    'type' => Notification::TODO_REMINDER,
                    'record_id' => $reminder->todo_id,
                    'from_id' => $reminder->todo_admin_id,
                    'message' => $message
                ]));
                if ($sendEmail == true || $sendSms == true) {
                    echo $reminder->todo_description . '<br>';
                    dispatch(new SendNotification($notificationData, $sendEmail, false, $sendSms))->onQueue($priority);
                }
                Todo::where('todo_id', $reminder->todo_id)->update(['todo_reminder_at'=>null]);
            }
        }
        // echo 'cron finished<br>';
    }
}
