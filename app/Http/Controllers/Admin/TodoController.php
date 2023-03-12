<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\Admin\Todo;
use App\Models\Timezone;
use App\Models\Admin\Admin;
use App\Models\Configuration;
use App\Models\SmsTemplate;
use App\Models\Country;
use App\Helpers\EmailHelper;
use App\Jobs\SendNotification;
use Auth;
use Carbon\Carbon;

class TodoController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
    }

    public function getTodos(Request $request, $row = 0)
    {
        $data = [];
        $data['todos'] = view('admin.partials.todo_item')->with('todos', Todo::getTodos($row))->render();
        $data['total'] = Todo::getTodoCount();
        $data['todoCount'] = Todo::getTodoNotificationCount();
        $data['paginate_count'] = config('app.pagination');
        $data['configurations'] = Configuration::getKeyValues(['TODO_REMINDER_EMAIL', 'TODO_REMINDER_SMS']);
        $data['adminUsers'] = Admin::getAllAdminNames();
        return jsonresponse(true, null, $data);
    }

    public function updateStatus(Request $request, $id)
    {
        Todo::where('todo_id', $id)->update(['todo_status' => $request['todo_status']]);
        $data['todoCount'] = Todo::getTodoNotificationCount();
        return jsonresponse(true, '', $data);
    }

    public function setReminder(Request $request, $id)
    {
        Todo::where('todo_id', $id)->update(['todo_reminder_at' => Timezone::getAdminUtcTime($request['todo_reminder_at'])]);
        return jsonresponse(true, __('NOTI_REMINDER_SET_ON_TODO'));
    }

    public function store(Request $request)
    {
        $regex = "/^@/";
        $descrition = explode(' ', $request['todo_description']);
        $tags = preg_grep($regex, $descrition);
        if (count($tags) > 0) {
            foreach ($tags as $tag) {
                $description = str_replace($tag, '', $request['todo_description']);
                $adminName = str_replace('@', '', $tag);
                $adminData = Admin::select('admin_id', 'admin_email', 'admin_username', 'admin_name', 'admin_phone_country_id', 'admin_phone')
                    ->where('admin_username', '=', $adminName)->first();
                Todo::create([
                    'todo_admin_id' => $adminData->admin_id,
                    'todo_description' => $request['todo_description']
                ]);
                $notificationData = [];
                $sendSms = false;

                $data = EmailHelper::getEmailData('todo_assigned');
                $priority = $data['body']->etpl_priority;
                $data['subject'] = $data['body']->etpl_subject;
                $data['body'] = str_replace('{name}', $adminData->admin_username, $data['body']->etpl_body);
                $data['body'] = str_replace('{description}', $request['todo_description'], $data['body']);
                $data['body'] = str_replace('{user}', $this->admin->admin_username, $data['body']);
                $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
                $data['to'] = $adminData->admin_email;
                $notificationData['email'] = $data;

                $country = Country::select('country_phonecode')->where('country_id', $adminData->admin_phone_country_id)->first();
                if (!empty($country->country_phonecode) && !empty($adminData->admin_phone)) {
                    $data = SmsTemplate::getSMSTemplate('todo_assigned');
                    $priority = $data['body']->stpl_priority;
                    $data['body'] = str_replace('{toUserName}', $adminData->admin_name, $data['body']->stpl_body);
                    $data['body'] = str_replace('{fromUserName}', $this->admin->admin_name, $data['body']);
                    $data['body'] = str_replace('{websiteName}', (getConfigValueByName('BUSINESS_NAME') ?? ''), $data['body']);
                    $notificationData['sms'] = [
                        'message' => $data['body'],
                        'recipients' => array('+' . $country->country_phonecode . $adminData->admin_phone)
                    ];
                    $sendSms = true;
                }
                
                dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);
            }
        } else {
            Todo::create([
                'todo_admin_id' => Auth::guard('admin')->user()->admin_id,
                'todo_description' => $request['todo_description']
            ]);
        }
        
        return jsonresponse(true, '');
    }

    public function delete(Request $request, $id)
    {
        Todo::where('todo_id', $id)->delete();
        return jsonresponse(true, '');
    }
}
