<?php

namespace App\Listeners;

use App\Events\Event;
use App\Events\SystemNotification;
use App\Models\Configuration;
use App\Models\Notification;
use App\Models\Admin\Admin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotificationToAdmin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SystemNotification $event
     * @return void
     */
    public function handle(SystemNotification $event)
    {
        $ids = Admin::prepareDataForNotification($event->data);
        if (!empty($ids)) {
            Notification::sendNotification(array('notify' => [
                'type' => $event->data['type'],
                'record_id' => !empty($event->data['record_id']) ? $event->data['record_id'] : 0,
                'record_subid' => !empty($event->data['record_subid']) ? $event->data['record_subid'] : 0,
                'from_id' => !empty($event->data['from_id']) ? $event->data['from_id'] : 0,
                'to_id' => $ids,
                'message' => $event->data['message']
            ]), false, true, false);
        }
    }
}
