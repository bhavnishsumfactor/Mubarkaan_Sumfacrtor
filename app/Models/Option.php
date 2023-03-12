<?php

namespace App\Models;

use App\Models\YokartModel;

class Option extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'option_id';

    protected $fillable = [
        'option_id', 'option_name', 'option_type', 'option_has_image', 'option_has_sizechart'
    ];

    public static function getOptions($request)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $search = !empty($request['search']) ? $request['search'] : '';
        $query = Option::select('option_id', 'option_name', 'option_type', 'option_has_image', 'option_has_sizechart', 'option_created_by_id', 'option_updated_by_id', 'option_created_at', 'option_updated_at')->with(['createdAt', 'updatedAt']);
        if (!empty($search)) {
            $query->where('option_name', 'like', '%'.$search.'%');
        }
        if ($query->paginate((int)$per_page)->count() >= 1 && $query->paginate((int)$per_page)->currentPage() >= 1) {
            return $query->latest('option_id')->paginate((int)$per_page);
        } else {
            return $query->latest('option_id')->paginate((int)$per_page, ['*'], 'page', (int)$query->paginate((int)$per_page)->currentPage()-1);
        }
    }

    public function createdAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'option_created_by_id')->select(['admin_id', 'admin_username']);
    }

    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'option_updated_by_id')->select(['admin_id', 'admin_username']);
    }
}
