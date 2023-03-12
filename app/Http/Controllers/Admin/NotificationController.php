<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notification;
use App\Models\Thread;
use App\Models\Admin\Todo;
use App\Models\Admin\NotificationToAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use Auth;

class NotificationController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
    }

    public function listNotifications($row)
    {
        return view('admin.partials.notification_item')
            ->with('notifications', Notification::getNotifications($row));
    }

    public function udpateNotificationStatus(Request $request)
    {
        NotificationToAdmin::where('nta_admin_id', Auth::guard('admin')->user()->admin_id)
            ->where('nta_read', 0)
            ->update(['nta_read' => 1]);
        return view('admin.partials.notification_item')
            ->with('notifications', Notification::getNotifications(0));
    }

    public function redirectNotification(Request $request, $notify_id)
    {
        NotificationToAdmin::where('nta_notify_id', $notify_id)->where('nta_admin_id', Auth::guard('admin')->user()->admin_id)->where('nta_read', 0)->update(['nta_read' => 1]);
        $notification = Notification::where('notify_id', $notify_id)->first();
        return jsonresponse(true, '', $notification);
    }
    public function readUnread(Request $request, $notify_id, $nta_read)
    {
        NotificationToAdmin::where('nta_notify_id', $notify_id)->where('nta_admin_id', Auth::guard('admin')->user()->admin_id)->update(['nta_read' => $nta_read]);
        return jsonresponse(true, '');
    }

    /* get notifications sidebar data*/
    public function getNotifications(Request $request, $row = 0, $type = "")
    {
        $data = [];
        $data['notifications'] = view('admin.partials.notification_item')->with('notifications', Notification::getNotifications($row, $type))->render();
        $data['total'] = Notification::getNotificationCount();
        $data['unread'] = Notification::getUnreadNotificationCount();
        return jsonresponse(true, '', $data);
    }

    /* get unread notifications count*/
    public function getUnreadNotifications(Request $request)
    {
        $combinedCount = 0;

        $unreadNotifications = Notification::getUnreadNotificationCount();
        $unreadChatMessages = Thread::getAdminUnreadMessageCount();
        $pendingTodos = Todo::getTodoNotificationCount();
        
        $combinedCount = $unreadNotifications + $unreadChatMessages + $pendingTodos;
        return jsonresponse(true, '', $combinedCount);
    }
}
