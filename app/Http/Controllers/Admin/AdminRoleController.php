<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\AdminRole;
use App\Models\Admin\Admin;
use App\Http\Requests\RoleRequest;
use App\Http\Controllers\Admin\AdminYokartController;
use DB;
use Carbon\Carbon;

class AdminRoleController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::ADMIN_USERS)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }
    
    public function getListing(Request $request)
    {
        $data = [];

        $data['roles'] = AdminRole::getRoles($request->all());

        $data['showEmpty'] = 0;
        if (empty($request['search']) && $data['roles']->count() == 0) {
            $data['showEmpty'] = 1;
        }

        return jsonresponse(true, null, $data);
    }
    
    public function store(RoleRequest $request)
    {
        if (!canWrite(AdminRole::ADMIN_USERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        DB::beginTransaction();
        try {
            $role = AdminRole::create(['role_name' => $request->input('role_name'), 'role_created_by_id' => $this->admin->admin_id, 'role_updated_by_id' => $this->admin->admin_id, 'role_created_at' =>Carbon::now(), 'role_updated_at'=> Carbon::now() ]);
            $request['role'] = json_decode($request['role']);
            AdminRole::updatePermissions($role->role_id, $request->only(['role']));
            DB::commit();
            return jsonresponse(true, __('NOTI_ROLE_CREATED'));
        } catch (\Exception $e) {
            DB::rollback();
            return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
        }
    }

    public function show(Request $request, $id)
    {
        $roles = AdminRole::getRoleById($id)->toArray();
        if (isset($roles['permissions']) && !empty($roles['permissions'])) {
            foreach ($roles['permissions'] as $permission) {
                $roles['role'][AdminRole::SECTION_NAMES[$permission['ap_section_id']]] = $permission['ap_value'];
            }
        }
        $roles['permissions'] = AdminRole::getTabs();
        return jsonresponse(true, null, $roles);
    }

    public function update(RoleRequest $request, $id)
    {
        if (!canWrite(AdminRole::ADMIN_USERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        DB::beginTransaction();
        try {
            $roles = $request->except(['role_id','role', 'roleGroup']);
            $roles['role_updated_by_id'] = $this->admin->admin_id;
            $roles['role_updated_at'] = Carbon::now();
            AdminRole::find($id)->update($roles);
            $request['role'] = json_decode($request['role']);
            AdminRole::updatePermissions($id, $request->only(['role']));
            DB::commit();
            return jsonresponse(true, __('NOTI_ROLE_UPDATED'));
        } catch (\Exception $e) {
            return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
        }
    }
    
    public function destroy($id)
    {
        if (!canWrite(AdminRole::ADMIN_USERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        if (Admin::where('admin_role_id', $id)->count() > 0) {
            return jsonresponse(false, __('NOTI_ROLE_ASSIGNED'), []);
        }
        AdminRole::find($id)->delete();
        return jsonresponse(true, __('NOTI_ROLE_DELETED'));
    }
    
    public function getAdminRolePermission(Request $request)
    {
        $selectedPermission = array();
        $permissions = AdminRole::getPermissions(Auth::guard('admin')->user()->admin_id);
        foreach ($permissions as $moduleId => $permission) {
            $selectedPermission[ AdminRole::SECTION_NAMES[$moduleId] ] = $permission;
        }
        return jsonresponse(true, null, $selectedPermission);
    }

    public function getAllPermissionSections(Request $request)
    {
        return jsonresponse(true, null, AdminRole::getTabs());
    }
}
