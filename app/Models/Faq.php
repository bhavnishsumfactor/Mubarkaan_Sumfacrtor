<?php

namespace App\Models;

use App\Models\YokartModel;

class Faq extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'faq_id';

    protected $fillable = [
      'faq_faqcat_id', 'faq_title', 'faq_content', 'faq_display_order','faq_created_by_id', 'faq_updated_by_id', 'faq_created_at', 'faq_updated_at'
    ];

    public function category()
    {
        return $this->hasOne('App\Models\FaqCategory', 'faqcat_id', 'faq_faqcat_id');
    }
    //for backend
    public static function getAllFaqs($request)
    {
        $per_page = !empty($request['per_page']) ? $request['per_page'] : '';
        $search = !empty($request['search'])?$request['search']:'';
        if (empty($request['per_page'])) {
            $per_page = config('app.pagination');
        }
        $query = Faq::select('faq_id', 'faq_faqcat_id', 'faq_title', 'faq_content', 'faq_publish', 'faq_display_order', 'faq_created_by_id', 'faq_updated_by_id', 'faq_created_at', 'faq_updated_at');
        if (!empty($request['search'])) {
            $query->where('faq_title', 'like', '%'.$search.'%');
        }
        $query = $query->with('category:faqcat_id,faqcat_name');
        $query->with(['createdAt','updatedAt']);
        if ($query->paginate((int)$per_page)->count() >= 1 && $query->paginate((int)$per_page)->currentPage() >= 1) {
            $records = $query->latest('faq_id')->paginate((int)$per_page);
        } else {
            $records = $query->latest('faq_id')->paginate((int)$per_page, ['*'], 'page', (int)$query->paginate((int)$per_page)->currentPage()-1);
        }
        return $records;
    }
    //for frontend
    public static function getFaqs($request)
    {
        $query = Faq::select('faq_id', 'faq_faqcat_id', 'faq_title', 'faq_content', 'faq_publish', 'faq_display_order');
        if (!empty($request['search'])) {
            $query->where('faq_title', 'like', '%' . $request['search'] . '%');
        }
        if (!empty($request['category_id'])) {
            $query->where('faq_faqcat_id', $request['category_id']);
        }
        $records = $query->where('faq_publish', 1)->oldest('faq_title')->paginate((int)config('app.pagination'));
        return $records;
    }

    public static function getCollections($cid, $pageid)
    {
        
        $query = Faq::select('faq_id', 'faq_title', 'faq_content')
        ->join('collections', 'faq_id', 'collection_record_id')
        ->where('collection_component_id', $cid)
        ->where('collection_page_id', $pageid)
        ->where('collection_record_subid', 0);
        
        return $query->where('faq_publish', config("constants.YES"))
        ->oldest('collection_display_order')->get();
    }

    public static function getRecordsByName($searchTerm, $excludeIds)
    {
        return Faq::select('faq_id as value', 'faq_title as label')->where('faq_title', 'like', '%' . $searchTerm . '%')
        ->whereNotIn('faq_id', $excludeIds)
        ->where('faq_publish', config("constants.YES"))
        ->oldest('faq_title')->get()->toArray();
    }
    
    public static function getRecordsById($recordIds)
    {
        $query = Faq::select('faq_id as value', 'faq_title as label')->whereIn('faq_id', $recordIds);
        if (!empty($recordIds) && count($recordIds) > 0) {
            $orderByIds = implode(',', $recordIds);
            $query->orderByRaw("FIELD(faq_id, $orderByIds)");
        }
        return $query->get();
    }

    public function createdAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'faq_created_by_id')->select(['admin_id', 'admin_username']);
    }

    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'faq_updated_by_id')->select(['admin_id', 'admin_username']);
    }
}
