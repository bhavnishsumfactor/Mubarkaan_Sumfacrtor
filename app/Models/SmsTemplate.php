<?php

namespace App\Models;

use App\Models\YokartModel;

class SmsTemplate extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'stpl_id';

    protected $fillable = [
        'stpl_id', 'stpl_name', 'stpl_body', 'stpl_updated_by_id', 'stpl_updated_at'
    ];

    public static function getAllSmsTemplates($request)
    {
        $per_page = !empty($request['per_page'])?$request['per_page']:'';
        $search = !empty($request['search'])?$request['search']:'';

        $sort = 'stpl_id';
        $sortBy = 'DESC';
        if (!empty($request['sort']) && !empty($request['sortBy'])) {
            $sort = $request['sort'];
            $sortBy = ($request['sortBy'])?$request['sortBy']:'ASC';
        }
        if (empty($request['per_page'])) {
            $per_page = config('app.pagination');
        }
        $query = SmsTemplate::select('stpl_id', 'stpl_name', 'stpl_body', 'stpl_default_body', 'stpl_replacements', 'stpl_publish', 'stpl_updated_by_id', 'stpl_updated_at');
        if (!empty($search)) {
            $query->where('stpl_name', 'like', '%'.$search.'%');
        }
        $records = $query->with(['updatedAt'])->orderBy($sort, $sortBy)->paginate((int)$per_page);
        return $records;
    }

    public static function getSMSTemplate($slug)
    {
        $data = [];
        $data['body'] = SmsTemplate::select('stpl_slug', 'stpl_body', 'stpl_priority')->where('stpl_slug', $slug)->first();
        return $data;
    }

    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'stpl_updated_by_id')->select(['admin_id', 'admin_username']);
    }
}
