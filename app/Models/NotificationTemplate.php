<?php

namespace App\Models;

use App\Models\YokartModel;

class NotificationTemplate extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'ntpl_id';

    protected $fillable = [
        'ntpl_id', 'ntpl_name', 'ntpl_body', 'ntpl_updated_at', 'ntpl_updated_at'
    ];

    public static function getRecords($request)
    {
        $per_page = !empty($request['per_page'])?$request['per_page']:'';
        $search = !empty($request['search'])?$request['search']:'';

        $sort = 'ntpl_id';
        $sortBy = 'DESC';
        if (!empty($request['sort']) && !empty($request['sortBy'])) {
            $sort = $request['sort'];
            $sortBy = ($request['sortBy'])?$request['sortBy']:'ASC';
        }
        if (empty($request['per_page'])) {
            $per_page = config('app.pagination');
        }
        $query = NotificationTemplate::select('ntpl_id', 'ntpl_name', 'ntpl_body', 'ntpl_default_body', 'ntpl_replacements', 'ntpl_publish', 'ntpl_updated_by_id', 'ntpl_updated_at');
        if (!empty($search)) {
            $query->where('ntpl_name', 'like', '%'.$search.'%');
        }
        $records = $query->with(['updatedAt'])->orderBy($sort, $sortBy)->paginate((int)$per_page);
        return $records;
    }

    public static function getBySlug($slug)
    {
        return NotificationTemplate::select('ntpl_body')->where('ntpl_slug', $slug)->first();
    }

    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'ntpl_updated_by_id')->select(['admin_id', 'admin_username']);
    }
}
