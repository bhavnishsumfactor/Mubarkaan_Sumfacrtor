<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\AttachedFile;
use Carbon\Carbon;
use DB;

class BlogPost extends YokartModel
{
    const CREATED_AT = 'post_created_at';
    const UPDATED_AT = 'post_updated_at';
    const FEATURED_BLOG_COUNT = 6;
    const RELATED_BLOG_COUNT = 5;
    const RECENT_BLOG_COUNT = 3;

    protected $primaryKey = 'post_id';

    protected $fillable = [
        'post_title', 'post_publish', 'post_author_name', 'post_featured', 'post_comment_opened', 'post_view_count', 'post_published_on'
    ];

    public function urlRewrite()
    {
        return $this->belongsTo('App\Models\UrlRewrite', 'post_id', 'urlrewrite_record_id')->where('urlrewrite_type', UrlRewrite::BLOG_TYPE);
    }
    public static function blogCollectionRecords($postIds)
    {
        $query = BlogPost::select(
            'post_id',
            'post_title',
            'post_author_name',
            'post_published_on',
            'afile_name',
            'afile_attribute_title',
            'afile_updated_at',
            'afile_id',
            'afile_attribute_alt'
        )
        ->with('urlRewrite')
        ->with('content')
        ->join('attached_files', 'attached_files.afile_record_id', 'blog_posts.post_id')->where('attached_files.afile_type', AttachedFile::SECTIONS['blogImage'])
        ->whereIn('post_id', $postIds);
        if (!empty($postIds) && count($postIds) > 0) {
            $orderByIds = implode(',', $postIds);
            $query->orderByRaw("FIELD(post_id, $orderByIds)");
        }
        return  $query->get();
    }
    public function uploadImages()
    {
        return $this->hasOne('App\Models\AttachedFile', 'afile_record_id', 'post_id')->where('attached_files.afile_type', '=', AttachedFile::SECTIONS['blogImage']);
    } 

    public static function getRecords($request, $seoInfo = false, $withImages = false)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        if ($seoInfo) {
            $query = BlogPost::select('post_id as record_id', 'post_title as record_title', 'urlrewrite_id', 'urlrewrite_original', 'urlrewrite_custom')
            ->leftJoin('url_rewrites', function ($sql) {
                $sql->on('urlrewrite_record_id', 'post_id')->where('urlrewrite_type', UrlRewrite::BLOG_TYPE);
            });
            if ($withImages) {
                $query->has('uploadImages', '>', 0);
            }
        } else {
            $query = BlogPost::select('post_id', 'post_title', 'post_publish', 'post_author_name', 'post_view_count', 'post_featured');
        }

        if (!empty($request['search'])) {
            $query->where('post_title', 'like', '%' . $request['search'] . '%');
        }
        
        if ($query->paginate((int)$per_page)->count() >= 1 && $query->paginate((int)$per_page)->currentPage() >= 1) {
            return $query->latest('post_id')->paginate((int)$per_page);
        } else {
            return $query->latest('post_id')->paginate((int)$per_page, ['*'], 'page', (int)$query->paginate((int)$per_page)->currentPage()-1);
        }
    }
    public function content()
    {
        return $this->belongsTo('App\Models\BlogPostContent', 'post_id', 'bpc_post_id');
    }
    public function category()
    {
        return $this->hasMany('App\Models\BlogPostToCategory', 'ptc_post_id', 'post_id');
    }
    public function product()
    {
        return $this->hasMany('App\Models\ProductToBlogPost', 'ptbp_post_id', 'post_id');
    }
    public static function getRecordById($id)
    {
        return BlogPost::with('content', 'category', 'product', 'createdAt', 'updatedAt')
                ->leftJoin('attached_files AS af', function ($join) {
                    $join->on('post_id', '=', 'af.afile_record_id');
                    $join->where('af.afile_type', AttachedFile::getConstantVal('blogImage'));
                })
                ->where('post_id', $id)->first();
    }

    public static function getRecordByBlogId($id)
    {
        return BlogPost::with('content')->with('category')->where('post_id', $id)->where('post_publish', config('constants.YES'))
        ->leftJoin('attached_files', function ($join) {
            $join->on('post_id', '=', 'attached_files.afile_record_id');
            $join->where('attached_files.afile_type', AttachedFile::getConstantVal('blogImage'));
        })
        ->first();
    }

    public static function getPostsByName($searchTerm, $excludeIds)
    {
        return BlogPost::select('post_id as value', 'post_title as label')->where('post_title', 'like', '%'.$searchTerm.'%')
        ->whereNotIn('post_id', $excludeIds)
        ->where('post_publish', config('constants.YES'))
        ->oldest('post_title')->get()->toArray();
    }
    
    public static function getPostsById($postIds)
    {
        $query = BlogPost::select('post_id as value', 'post_title as label')->whereIn('post_id', $postIds);
        if (!empty($postIds) && count($postIds) > 0) {
            $orderByIds = implode(',', $postIds);
            $query->orderByRaw("FIELD(post_id, $orderByIds)");
        }
        return $query->get();
    }
    public static function getBlogs($filters, $limit = false)
    {
        $query = BlogPost::join('blog_post_contents as content', 'content.bpc_post_id', 'blog_posts.post_id')
        ->join('blog_post_to_categories', 'blog_post_to_categories.ptc_post_id', 'blog_posts.post_id')
        // ->leftJoin('attached_files', 'attached_files.afile_record_id', 'blog_posts.post_id')
        ->leftJoin('attached_files', function ($join) {
            $join->on('attached_files.afile_record_id', 'blog_posts.post_id');
            $join->where('attached_files.afile_type', AttachedFile::SECTIONS['blogImage']);
        })
        ->leftJoin('url_rewrites as urlr', 'urlr.urlrewrite_original', DB::Raw("CONCAT('blog/', post_id)"))
        ->select(
            'post_id',
            'post_title',
            'post_publish',
            'post_author_name',
            'post_created_at',
            'post_updated_at',
            'bpc_short_description',
            'afile_id',
            'afile_name',
            'afile_attribute_title',
            'afile_attribute_alt',
            DB::Raw("(CASE WHEN urlrewrite_custom IS NULL THEN CONCAT('blog/', post_id) WHEN urlrewrite_custom = '' THEN urlrewrite_original ELSE urlrewrite_custom END) as record_slug")
        )
        ->where('post_publish', config('constants.YES'));
        foreach ($filters as $key => $value) {
            if ($value == '') {
                continue;
            };
            switch ($key) {
                case 'featured':
                    $query->where('post_featured', $value);
                    break;
                case 'category':
                    $query->where('ptc_bpcat_id', $value);
                    break;
                case 'relatedPosts':
                    $query->where('ptc_post_id', '!=', $value);
                    break;
                case 'relatedCategory':
                    $query->whereIN('ptc_bpcat_id', $value);
                    break;
                case 'excludedBlogs':
                    $query->whereNotIN('ptc_post_id', $value);
                    break;
                case 'search':
                    $query->where('post_title', 'like', '%' . $value . '%');
                    break;
            }
        }
        $query->whereDate('post_published_on', '<=', Carbon::today()->toDateString())->distinct('post_id');
        if ($limit == false) {
            $per_page = config('app.pagination');
            return  $query->latest('post_id')->paginate((int)$per_page);
        } else {
            return  $query->latest('post_id')->limit($limit)->get();
        }
    }

    public static function getComments($postId)
    {
        return BlogPost::join('blog_post_to_comments as comments', 'comments.bptc_post_id', 'blog_posts.post_id')
        ->join('blog_post_comments as postcomments', 'postcomments.bpcomment_id', 'comments.bptc_bpcomment_id')
        ->join('users', 'users.user_id', 'postcomments.bpcomment_user_id')
        ->select('bpcomment_user_id', DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'), 'bpcomment_added_on', 'bptc_bpcomment_id', 'bpcomment_content')
        ->where('blog_posts.post_id', $postId)->where('post_comment_opened', config('constants.YES'))->where('bpcomment_approved', config('constants.YES'))
        ->latest('bpcomment_id')->get();
    }

    public static function getCollections($postIds)
    {
        $query = BlogPost::select(
            'post_id',
            'post_title',
            'post_author_name',
            'post_published_on',
            'afile_name',
            'afile_attribute_title',
            'afile_attribute_alt'
        )
        ->with('content')
        ->join('attached_files', 'attached_files.afile_record_id', 'blog_posts.post_id')->where('attached_files.afile_type', AttachedFile::SECTIONS['blogImage'])
        ->whereIn('post_id', $postIds);
        if (!empty($postIds) && count($postIds) > 0) {
            $orderByIds = implode(',', $postIds);
            $query->orderByRaw("FIELD(post_id, $orderByIds)");
        }
        return $query->get();
    }

    public function createdAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'post_created_by_id')->select(['admin_id', 'admin_username']);
    }

    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'post_updated_by_id')->select(['admin_id', 'admin_username']);
    }
    public static function getBlogsForApp($pageId)
    {
        $query = BlogPost::join('blog_post_contents as content', 'content.bpc_post_id', 'blog_posts.post_id')
        ->join('blog_post_to_categories', 'blog_post_to_categories.ptc_post_id', 'blog_posts.post_id')
        ->join('blog_post_categories', 'blog_post_categories.bpcat_id', 'blog_post_to_categories.ptc_bpcat_id')
        // ->leftJoin('attached_files', 'attached_files.afile_record_id', 'blog_posts.post_id')
        ->leftJoin('url_rewrites', function ($sql) {
            $sql->on('urlrewrite_record_id', 'post_id')->where('urlrewrite_type', UrlRewrite::BLOG_TYPE);
        })->leftJoin('attached_files', function ($join) {
            $join->on('attached_files.afile_record_id', 'blog_posts.post_id');
            $join->where('attached_files.afile_type', AttachedFile::SECTIONS['blogImage']);
        })
        ->select(
            'post_id',
            'post_title',
            'post_publish',
            'post_author_name',
            'post_created_at',
            'post_updated_at',
            'bpc_short_description',
            DB::Raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '?t=', UNIX_TIMESTAMP(afile_updated_at)) as image"),
            DB::Raw("(CASE WHEN urlrewrite_custom IS NULL THEN CONCAT('" . url('') . "', 'blog/', post_id) WHEN urlrewrite_custom = '' THEN CONCAT('" . url('') . "', '/',urlrewrite_original) ELSE CONCAT('" . url('') . "','/',urlrewrite_custom) END) as detail_url")
        )
        ->where('post_publish', config('constants.YES'))
        ->where('bpcat_publish', config('constants.YES'));
        $perPage = config('app.pagination');
        return $query->latest('post_id')->paginate((int) $perPage, ['*'], 'page', $pageId);
     
    }
}
