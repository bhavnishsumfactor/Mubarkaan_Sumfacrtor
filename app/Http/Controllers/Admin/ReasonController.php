<?php

namespace App\Http\Controllers\Admin;

use App\Models\Reason;
use App\Http\Requests\ReasonRequest;
use App\Models\Admin\AdminRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;

class ReasonController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::RETURN_AND_CANCELLATION)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getListing(Request $request)
    {
        $reasons = Reason::getAllReasons($request->all());
        $types = Reason::TYPE;
        $status = ($reasons->count() > 0) ? true : false;

        $showEmpty = 0;
        if (empty($request['search']) && $reasons->count() == 0) {
            $showEmpty = 1;
        }

        return jsonresponse($status, '', compact('reasons', 'types', 'showEmpty'));
    }

    public function store(ReasonRequest $request)
    {
        if (!canWrite(AdminRole::RETURN_AND_CANCELLATION)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $request['reason_publish'] = 1;
        Reason::create($request->all());
        return jsonresponse(true, __('NOTI_REASON_CREATED'));
    }

    public function update(ReasonRequest $request, $reason_id)
    {
        if (!canWrite(AdminRole::RETURN_AND_CANCELLATION)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        Reason::where('reason_id', $reason_id)->update([
            'reason_type' => $request['reason_type'],
            'reason_title' => $request->input('reason_title')
        ]);
        return jsonresponse(true, __('NOTI_REASON_UPDATED'));
    }

    public function updateStatus(Request $request, $reason_id)
    {
        if (!canWrite(AdminRole::RETURN_AND_CANCELLATION)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $publishStatus = (!empty($request->input('status')) && $request->input('status') == 'true') ? 1 : 0;
        Reason::where('reason_id', $reason_id)->update(['reason_publish' => $publishStatus]);
        $message = (!empty($request->input('status')) && $request->input('status') == 'true') ? __('NOTI_REASON_PUBLISHED') : __('NOTI_REASON_UNPUBLISHED');
        return jsonresponse(true, $message);
    }

    public function destroy($id)
    {
        if (!canWrite(AdminRole::RETURN_AND_CANCELLATION)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        Reason::where('reason_id', $id)->delete();
        return jsonresponse(true, __('NOTI_REASON_DELETED'));
    }
}
