<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Thread;
use App\Models\ThreadMessage;
use App\Models\Admin\AdminRole;
use App\Http\Controllers\Admin\AdminYokartController;

class AdminMessagesController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::MESSAGES)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getAllthreadsWithLastMessage(Request $request, $row = 0)
    {
        return jsonresponse(true, null, Thread::getAllMessageThreads($request->all(), $row));
    }

    public function getAllthreadMessage(Request $request, $threadId)
    {
        $messages = Thread::getThreadMessages($threadId);
        ThreadMessage::readUserMessages($threadId);
        //dd($messages);
        //$messages['unreadCount'] = ThreadMessage::getMessageCount();
        return jsonresponse(true, null, $messages);
    }

    public function sendMessage(Request $request)
    {
        if (!canWrite(AdminRole::MESSAGES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $response =  ThreadMessage::saveAdminMessage($request->all(), $this->admin->admin_id);
        //$response = ThreadMessage::with(['messageFrom', 'uploadImages'])->where('message_id', $saveMessageId)->get();
        return jsonresponse(true, null, $response);
    }

    /*public function readMessages(Request $request, $threadId)
    {
        ThreadMessage::where('message_thread_id', $threadId)->where('message_is_unread', '=', config('constants.NO'))
        ->where('message_from_admin', '=', config('constants.NO'))
        ->update(['message_is_unread' => config('constants.YES')]);
        return jsonresponse(true, '', ['messageCount' => ThreadMessage::getMessageCount()]);
    }*/
}
