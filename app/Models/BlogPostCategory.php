<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\BlogPost;

class BlogPostCategory extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'bpcat_id';

    protected $fillable = [
        'bpcat_name', 'bpcat_parent', 'bpcat_publish', 'bpcat_display_order','bpcat_created_by_id','bpcat_updated_by_id','bpcat_created_at','bpcat_updated_at'
    ];

    public static function getRecords()
    {
        return BlogPostCategory::oldest('bpcat_display_order')->with(['createdAt','updatedAt'])->get()->toArray();
    }
    public static function buildTree($elements, $parentId = 0, $totalChildrenCount = 0)
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['bpcat_parent'] == $parentId) { 
                $children = self::buildTree($elements, $element['bpcat_id'], $totalChildrenCount);

                if ($children) {
                    $element['children'] = $children;
                } else {
                    $element['children'] = [];
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }
    public static function getActiveRecords()
    {
        return BlogPostCategory::where('bpcat_publish', config('constants.YES'))
            ->pluck('bpcat_name', 'bpcat_id');
    }
    
    public static function getParentList($catId = null)
    {
        $query = BlogPostCategory::select('bpcat_name as label', 'bpcat_id', 'bpcat_id as id', 'bpcat_parent', 'bpcat_created_by_id', 'bpcat_updated_by_id','bpcat_created_at','bpcat_updated_at');
        if ($catId != null) {
            $query->where('bpcat_id', '!=',  $catId);
            $childs = BlogPostCategory::getByParent($catId)->pluck('bpcat_id');
            $query->whereNotIn('bpcat_id',  $childs);
        }
        return $query->with(['createdAt', 'updatedAt'])->get()->toArray();
    }

    private static function getByParent($parentId)
    {
        $ids = BlogPostCategory::where('bpcat_parent', $parentId)->pluck('bpcat_id')->toArray();
        $unionQuery = BlogPostCategory::select('bpcat_id', 'bpcat_name', 'bpcat_parent', 'bpcat_display_order')
            ->whereIn('bpcat_parent', $ids);
        return BlogPostCategory::select('bpcat_id', 'bpcat_name', 'bpcat_parent', 'bpcat_display_order')
            ->where('bpcat_parent', $parentId)
            ->union($unionQuery)
            ->oldest('bpcat_display_order')
            ->get();
    }

    public function blogs()
    {
        $publishedBlog = BlogPost::getBlogs(['featured' => config('constants.NO')]);
        $blogIds = [];
        if ($publishedBlog) {
            $blogIds = $publishedBlog->pluck('post_id');
        }
        return $this->hasMany('App\Models\BlogPostToCategory', 'ptc_bpcat_id', 'bpcat_id')->whereIn('ptc_post_id', $blogIds);
    }
    
    public static function getRecordByCatId($catId)
    {
        return BlogPostCategory::where('bpcat_id', $catId)->first();
    }

    public function createdAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'bpcat_created_by_id')->select(['admin_id', 'admin_username']);
    }

    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'bpcat_updated_by_id')->select(['admin_id', 'admin_username']);
    }

}
