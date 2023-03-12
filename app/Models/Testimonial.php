<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\AttachedFile;

class Testimonial extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'testimonial_id';

    protected $fillable = [
      'testimonial_id', 'testimonial_user_name', 'testimonial_designation', 'testimonial_title', 'testimonial_description', 'testimonial_publish','testimonial_created_by_id', 'testimonial_updated_by_id', 'testimonial_created_at', 'testimonial_updated_at'
    ];

    public static function getTestimonials($request)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $query = Testimonial::select('testimonial_id', 'testimonial_user_name', 'testimonial_designation', 'testimonial_title', 'testimonial_description', 'testimonial_publish', 'testimonial_updated_at', 'af.afile_id', 'testimonial_created_by_id', 'testimonial_updated_by_id', 'testimonial_created_at', 'testimonial_updated_at');
        $query->leftJoin('attached_files AS af', function ($join) {
            $join->on('testimonial_id', '=', 'af.afile_record_id');
            $join->where('af.afile_type', AttachedFile::getConstantVal('testimonialMedia'));
        });
        if (!empty($request['search'])) {
            $query->where('testimonial_title', 'like', '%'.$request['search'].'%');
        }
        $query->with(['createdAt','updatedAt']);
        if ($query->paginate((int)$per_page)->count() >= 1 && $query->paginate((int)$per_page)->currentPage() >= 1) {
            return $query->latest('testimonial_id')->paginate((int)$per_page);
        } else {
            return $query->latest('testimonial_id')->paginate((int)$per_page, ['*'], 'page', (int)$query->paginate((int)$per_page)->currentPage()-1);
        }
    }

    public static function getRecordsByName($searchTerm, $excludeIds)
    {
        return Testimonial::select('testimonial_id as value', 'testimonial_title as label')->where('testimonial_title', 'like', '%'.$searchTerm.'%')
        ->whereNotIn('testimonial_id', $excludeIds)
        ->oldest('testimonial_title')->get()->toArray();
    }

    public static function getRecordsById($testimonialIds)
    {
        $query = Testimonial::select('testimonial_id as value', 'testimonial_title as label')->whereIn('testimonial_id', $testimonialIds);
        if (!empty($testimonialIds) && count($testimonialIds) > 0) {
            $orderByIds = implode(',', $testimonialIds);
            $query->orderByRaw("FIELD(testimonial_id, $orderByIds)");
        }
        return $query->get();
    }

    public function createdAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'testimonial_created_by_id')->select(['admin_id', 'admin_username']);
    }

    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'testimonial_updated_by_id')->select(['admin_id', 'admin_username']);
    }
}
