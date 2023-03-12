<?php

namespace App\Models\Admin;

use App\Models\Admin\AdminYokartModel;

class AdminPermission extends AdminYokartModel
{

    public $timestamps = false;

    protected $fillable = [
        'ap_role_id', 'ap_section_id', 'ap_value',
    ];

    public function scopeRole($query, $roleId)
    {
        return $query->where('ap_role_id', $roleId);
    }

    public function scopeModule($query, $moduleId)
    {
        return $query->where('ap_section_id', $moduleId);
    }

    public function adminRoles()
    {
        return $this->belongsTo('App\Models\Admin\AdminRole', 'ap_role_id', 'role_id');
    }
}
