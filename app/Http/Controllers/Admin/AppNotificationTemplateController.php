<?php


namespace App\Http\Controllers\Admin;

use App\Models\AppNotificationTemplate;
use App\Models\Admin\AdminRole;
use App\Http\Controllers\Admin\AdminYokartController;
use Illuminate\Http\Request;
use App\Http\Requests\AppNotificationTemplateRequest;
use Carbon\Carbon;
use Auth;

class AppNotificationTemplateController extends AdminYokartController
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
        $notificationTemplates = AppNotificationTemplate::getRecords($request->all());
        $status = ($notificationTemplates->count() > 0) ? true : false;
        return jsonresponse($status, '', $notificationTemplates);
    }

    public function update(AppNotificationTemplateRequest $request, AppNotificationTemplate $notificationTemplate)
    {
        if (!canWrite(AdminRole::NOTIFICATION_TEMPLATES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }       
        AppNotificationTemplate::where('antpl_id', $request->input('antpl_id'))->update([
            'antpl_name' => $request->input('antpl_name'),
            'antpl_body' => $request->input('antpl_body'),
            'antpl_updated_by_id' => Auth::guard('admin')->user()->admin_id,
            'antpl_updated_at' => Carbon::now()
        ]);
        return jsonresponse(true, __('NOTI_NOTIFICATION_TEMPLATE_UPDATED'));
    }

    public function updateStatus(Request $request, $ntpl_id)
    {
        if (!canWrite(AdminRole::NOTIFICATION_TEMPLATES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $publishStatus = ($request->input('status') == 'true') ? 1 : 0;
        AppNotificationTemplate::where('antpl_id', $ntpl_id)->update(['antpl_publish' => $publishStatus]);
        $message = ($request->input('status') == 'true') ? __('NOTI_NOTIFICATION_TEMPLATE_PUBLISHED') : __('NOTI_NOTIFICATION_TEMPLATE_UNPUBLISHED');
        return jsonresponse(true, $message);
    }
}
