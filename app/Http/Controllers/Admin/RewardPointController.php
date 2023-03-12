<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\Configuration;
use App\Models\Admin\AdminRole;
use App\Models\Page;

class RewardPointController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::REWARDS_POINTS)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function get(Request $request)
    {
        $data = [];
        $fields = ['page_name', 'page_id'];
        // $data['pages'] = Page::getPages($fields, ['page_publish' => config('constants.YES')])->pluck('page_name', 'page_id');
        $data['settings'] = Configuration::getRecordsByPrefix('REWARD_POINTS');
        return jsonresponse(true, null, $data);
    }

    public function update(Request $request)
    {
        if (!canWrite(AdminRole::REWARDS_POINTS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        Configuration::bulkUpdate($request->all());
        return jsonresponse(true, __('NOTI_REWARD_POINTS_SETTINGS_UPDATED'));
    }
}
