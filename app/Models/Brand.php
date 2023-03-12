<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\AttachedFile;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends YokartModel
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'brand_id';
    protected $fillable = ['brand_name', 'brand_publish', 'brand_created_by_id', 'brand_updated_by_id', 'brand_created_at', 'brand_updated_at'];

    public function uploadImages()
    {
        return $this->hasMany('App\Models\AttachedFile', 'afile_record_id', 'brand_id')->where('attached_files.afile_type', '=', AttachedFile::SECTIONS['brandLogo']);
    }
    public function urlRewrite()
    {
        return $this->belongsTo('App\Models\UrlRewrite', 'brand_id', 'urlrewrite_record_id')->where('urlrewrite_type', UrlRewrite::BRAND_TYPE);
    }
    public static function getBrands($request, $seoInfo = false, $withImages = false)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        if ($seoInfo) {
            $query = Brand::select('brand_id as record_id', 'brand_name as record_title', 'urlrewrite_id', 'urlrewrite_original', 'urlrewrite_custom')
            ->leftJoin('url_rewrites', function ($sql) {
                $sql->on('urlrewrite_record_id', 'brand_id')->where('urlrewrite_type', UrlRewrite::BRAND_TYPE);
            });
            if ($withImages) {
                $query->has('uploadImages', '>', 0);
            }
        } else {
            $query = Brand::with('urlRewrite:urlrewrite_record_id,urlrewrite_original,urlrewrite_custom')->select(
                'brand_name',
                'brand_id',
                'brand_publish',
                'brand_created_by_id',
                'brand_updated_by_id',
                'brand_created_at',
                'brand_updated_at',
            )
            ->with(['attachedFile' => function ($q) {
                $q->select('afile_id', 'afile_record_id');
            }])
            ->withCount(['attachedFile' => function ($query) {
                $query->where('afile_type', AttachedFile::getConstantVal('brandLogo'));
            }])->with(['createdAt', 'updatedAt']);
        }
        if (!empty($request['search'])) {
            $query->where('brand_name', 'like', '%' . $request['search'] . '%');
        }
        if ($query->paginate((int)$per_page)->count() >= 1 && $query->paginate((int)$per_page)->currentPage() >= 1) {
            return $query->latest('brand_id')->paginate((int)$per_page);
        } else {
            return $query->latest('brand_id')->paginate((int)$per_page, ['*'], 'page', (int)$query->paginate((int)$per_page)->currentPage()-1);
        }
    }

    public function attachedFile()
    {
        return $this->hasOne('App\Models\AttachedFile', 'afile_record_id', 'brand_id')->where('afile_type', AttachedFile::getConstantVal('brandLogo'));
    }


    public static function getRecords($fields, $conditions = [], $orderBy='')
    {
        $query = Brand::select($fields)->leftJoin('attached_files AS af', function ($join) {
            $join->on('brand_id', '=', 'af.afile_record_id');
            $join->where('af.afile_type', AttachedFile::getConstantVal('brandLogo'));
        });
        if (count($conditions) > 0) {
            $query->where($conditions);
        }
        if($orderBy != '') {
            return $query->orderBy('brand_id', $orderBy)->get();
        }
        return $query->oldest('brand_name')->get();
    }

    public static function getBrandsByName($searchTerm, $excludeIds)
    {
        return Brand::select('brand_id as value', 'brand_name as label')->where('brand_name', 'like', '%'.$searchTerm.'%')
        ->whereNotIn('brand_id', $excludeIds)
        ->oldest('brand_name')->get()->toArray();
    }

    public static function getBrandsById($brandIds)
    {
        $query = Brand::select('brand_id as value', 'brand_name as label', 'brand_id', 'brand_name', 'af.afile_id', 'af.afile_attribute_alt', 'af.afile_attribute_title', 'brand_updated_at')->with('urlRewrite')
        ->leftJoin('attached_files AS af', function ($join) {
            $join->on('brand_id', '=', 'af.afile_record_id');
            $join->where('af.afile_type', AttachedFile::getConstantVal('brandLogo'));
        })
        ->whereIn('brand_id', $brandIds)
        ->where('brand_publish', config('constants.YES'));
        if (!empty($brandIds) && count($brandIds) > 0) {
            $orderByIds = implode(',', $brandIds);
            $query->orderByRaw("FIELD(brand_id, $orderByIds)");
        }
        return $query->get();
    }
    
    public static function getCount()
    {
        return Brand::where('brand_publish', 1)->count();
    }
    public static function getRecordsByIds($ids)
    {
        return Brand::whereIn('brand_id', $ids)->pluck('brand_name', 'brand_id');
    }
    public static function getRecordsById($id)
    {
        return Brand::select('brand_name', 'brand_id')->with('attachedFile')->where('brand_id', $id)->first();
    }

    public static function getActiveBrandDropdown()
    {
        return Brand::select('brand_name as label', 'brand_id as id')->where('brand_publish', config('constants.YES'))->oldest('brand_name')->get()->toArray();
    }

    public function createdAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'brand_created_by_id');
    }

    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'brand_updated_by_id')->select(['admin_id', 'admin_username']);
    }

    public static function getBrandData($fields, $conditions = [], $limit = 0)
    {
        if ($limit == 0) {
            $limit = config('app.pagination');
        }
        $query = Brand::select($fields);
        $query->where($conditions);
        return $query->latest('brand_id')->take($limit)->get()->toArray();
    }
}
