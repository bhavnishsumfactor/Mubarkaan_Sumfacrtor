<?php


namespace App\Http\Controllers\Admin;

use App\Models\NotificationTemplate;
use App\Models\Admin\AdminRole;
use App\Http\Controllers\Admin\AdminYokartController;
use Illuminate\Http\Request;
use App\Http\Requests\NotificationTemplateRequest;
use Carbon\Carbon;
use Auth;

class NotificationTemplateController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::NOTIFICATION_TEMPLATES)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getListing(Request $request)
    {
        $notificationTemplates = NotificationTemplate::getRecords($request->all());
        $status = ($notificationTemplates->count() > 0) ? true : false;
        return jsonresponse($status, '', $notificationTemplates);
    }

    public function update(NotificationTemplateRequest $request, NotificationTemplate $notificationTemplate)
    {
        if (!canWrite(AdminRole::NOTIFICATION_TEMPLATES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        NotificationTemplate::where('ntpl_id', $request->input('ntpl_id'))->update([
            'ntpl_name' => $request->input('ntpl_name'),
            'ntpl_body' => $request->input('ntpl_body'),
            'ntpl_updated_by_id' => Auth::guard('admin')->user()->admin_id,
            'ntpl_updated_at' => Carbon::now()
        ]);
        return jsonresponse(true, __('NOTI_NOTIFICATION_TEMPLATE_UPDATED'));
    }

    public function updateStatus(Request $request, $ntpl_id)
    {
        if (!canWrite(AdminRole::NOTIFICATION_TEMPLATES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $publishStatus = ($request->input('status') == 'true') ? 1 : 0;
        NotificationTemplate::where('ntpl_id', $ntpl_id)->update(['ntpl_publish' => $publishStatus]);
        $message = ($request->input('status') == 'true') ? __('NOTI_NOTIFICATION_TEMPLATE_PUBLISHED') : __('NOTI_NOTIFICATION_TEMPLATE_UNPUBLISHED');
        return jsonresponse(true, $message);
    }
}
