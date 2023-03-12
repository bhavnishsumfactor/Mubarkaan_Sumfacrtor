<?php

namespace App\Models;

use App\Models\YokartModel;
use Illuminate\Pagination\Paginator;
use DB;

class Page extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'page_id';

    protected $fillable = [
      'page_id', 'page_type', 'page_title', 'page_content', 'page_publish'
    ];

    const PAGE_TYPE = [
      '1' => 'home',
      '2' => 'content',
      '3' => 'listing',
      '4' => 'detail',
      '5' => 'terms',
      '6' => 'privacy',
      '7' => 'faq'
    ];
    
    public static function getPageTypeFlipped()
    {
        return array_flip(self::PAGE_TYPE);
    }
    public function urlRewrite()
    {
        return $this->belongsTo('App\Models\UrlRewrite', 'page_id', 'urlrewrite_record_id')->where('urlrewrite_type', UrlRewrite::PAGE_TYPE);
    }
    public static function getAllPages($request, $seoInfo = false, $exclude = [])
    {
        $per_page = !empty($request['per_page']) ? $request['per_page'] : '';
        $search = !empty($request['search']) ? $request['search'] : '';
        if (empty($request['per_page'])) {
            $per_page = config('app.pagination');
        }
        if ($seoInfo) {
            $query = Page::select('page_id as record_id', 'page_name as record_title', 'page_type', 'urlrewrite_id', 'urlrewrite_original', 'urlrewrite_custom')
            ->leftJoin('url_rewrites', function ($sql) {
                $sql->on('urlrewrite_record_id', 'page_id')->where('urlrewrite_type', UrlRewrite::PAGE_TYPE);
            });
        } else {
            $query = Page::with('urlRewrite:urlrewrite_record_id,urlrewrite_original,urlrewrite_custom')->select('page_id', 'page_type', 'page_name', 'page_publish', 'page_default');
        }
        if (!empty($request['search'])) {
            $query->where('page_name', 'like', '%' . $search . '%');
        }
        if (!empty($request['showLastPage']) && $request['showLastPage'] == 1) {
            $request['page'] = $query->paginate((int)$per_page)->lastPage();
        }
        $exclude = array_merge($exclude, ['7']);
        if (!empty($exclude) && count($exclude) > 0) {
            $query->whereNotIn('page_type', $exclude);
        }
        $records = $query->orderByRaw("FIELD(page_type , '1', '3', '4', '5', '6', '2') ASC")->oldest('page_id')->paginate((int)$per_page, ['*'], 'page', $request['page']);
        return $records;
    }

    public static function getPages($fields, $conditions)
    {
        $query = Page::select($fields);
        $query->where($conditions);
        $query->whereNotIn('page_type', [3,4]);
        return $query->get();
    }

    public static function getPageById($pageId)
    {
        return Page::select('page_id', 'page_content')->where('page_id', $pageId)->first();
    }

    public static function getPageByType($type)
    {
        return Page::select('page_id')->where('page_type', array_flip(self::PAGE_TYPE)[$type])->first();
    }
}
