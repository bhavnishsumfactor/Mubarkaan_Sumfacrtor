<?php

namespace App\Models;

use App\Models\YokartModel;

class AppNotificationTemplate extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'antpl_id';

    protected $fillable = [
        'antpl_id', 'antpl_name', 'antpl_body', 'antpl_updated_at', 'antpl_updated_at'
    ];

    public static function getRecords($request)
    {
        $per_page = !empty($request['per_page']) ? $request['per_page'] : '';
        $search = !empty($request['search']) ? $request['search'] : '';

        $sort = 'antpl_id';
        $sortBy = 'DESC';
        if (!empty($request['sort']) && !empty($request['sortBy'])) {
            $sort = $request['sort'];
            $sortBy = ($request['sortBy']) ? $request['sortBy'] : 'ASC';
        }
        if (empty($request['per_page'])) {
            $per_page = config('app.pagination');
        }
        $query = AppNotificationTemplate::select('antpl_id', 'antpl_name', 'antpl_body', 'antpl_default_body', 'antpl_replacements', 'antpl_publish', 'antpl_updated_by_id', 'antpl_updated_at');
        if (!empty($search)) {
            $query->where('antpl_name', 'like', '%'.$search.'%');
        }
        $records = $query->with(['updatedAt'])->orderBy($sort, $sortBy)->paginate((int)$per_page);
        return $records;
    }

    public static function getBySlug($slug)
    {
        return AppNotificationTemplate::select('antpl_body', 'antpl_name')->where('antpl_slug', $slug)->first();
    }

    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'antpl_updated_by_id')->select(['admin_id', 'admin_username']);
    }
}
