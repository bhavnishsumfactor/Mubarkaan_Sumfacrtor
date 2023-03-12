<?php

namespace App\Models;

use App\Models\YokartModel;
use DB;

class UserGroup extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'ugroup_id';

    protected $fillable = ['ugroup_id', 'ugroup_name', 'ugroup_created_by_id', 'ugroup_updated_by_id', 'ugroup_created_at', 'ugroup_updated_at'];

    public function members()
    {
        return $this->hasMany('App\Models\UserGroupMember', 'ugm_ugroup_id', 'ugroup_id');
    }

    public static function getGroups($request)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $dbprefix = DB::getTablePrefix();
        $query = UserGroup::select(
        'ugroup_id',
        'ugroup_name',
        DB::raw("(SELECT GROUP_CONCAT(CONCAT(users.user_fname, ' ', users.user_lname)) as user_names FROM ".$dbprefix."user_group_members as user_group_members INNER JOIN ".$dbprefix."users as users ON users.user_id = user_group_members.ugm_user_id WHERE user_group_members.ugm_ugroup_id = ".$dbprefix."user_groups.ugroup_id) as members")
    );
        if (!empty($request['search'])) {
            $query->where('ugroup_name', 'like', '%'.$request['search'].'%');
        }
        if ($query->paginate((int)$per_page)->count() >= 1 && $query->paginate((int)$per_page)->currentPage() >= 1) {
            return $query->with('members.user:user_id')->latest('ugroup_id')->paginate((int)$per_page);
        } else {
            return $query->with('members.user:user_id')->latest('ugroup_id')->paginate((int)$per_page, ['*'], 'page', (int)$query->paginate((int)$per_page)->currentPage()-1);
        }
    }

    public function createdAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'ugroup_created_by_id')->select(['admin_id', 'admin_username']);
    }

    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'ugroup_updated_by_id')->select(['admin_id', 'admin_username']);
    }

    public static function getUserGroups()
    {
        return UserGroup::select('ugroup_id', 'ugroup_name')->get()->toArray();
    }
}
