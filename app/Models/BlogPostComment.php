<?php

namespace App\Models;

use App\Models\YokartModel;
use DB;

class BlogPostComment extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'bpcomment_id';

    protected $fillable = [
        'bpcomment_user_id', 'bpcomment_content', 'bpcomment_approved', 'bpcomment_added_on', 'bpcomment_user_ip', 'bpcomment_user_agent', 'bpcomment_created_by_id','bpcomment_updated_by_id', 'bpcomment_created_at', 'bpcomment_updated_at'
    ];
    public static function getRecords($request)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $query = BlogPostComment::join('blog_post_to_comments', 'blog_post_to_comments.bptc_bpcomment_id', 'blog_post_comments.bpcomment_id')
        ->join('blog_posts', 'blog_posts.post_id', 'blog_post_to_comments.bptc_post_id')
        ->join('users', 'users.user_id', 'blog_post_comments.bpcomment_user_id');
        $query->select(DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'), 'bpcomment_id', 'bpcomment_approved', 'bpcomment_added_on', 'post_title', 'bpcomment_created_by_id', 'bpcomment_updated_by_id', 'bpcomment_created_at', 'bpcomment_updated_at');
        if (!empty($request['search'])) {
            $query->where('user_fname', 'like', '%'.$request['search'].'%');
            $query->orWhere('user_lname', 'like', '%'.$request['search'].'%');
            $query->orWhere('post_title', 'like', '%'.$request['search'].'%');
            $query->orWhere(DB::raw('CONCAT(user_fname, " ", user_lname)'), 'like', '%'.$request['search'].'%');
        }
        if ($query->paginate((int)$per_page)->count() >= 1 && $query->paginate((int)$per_page)->currentPage() >= 1) {
            return $query->latest('bpcomment_id')->paginate((int)$per_page);
        } else {
            return $query->latest('bpcomment_id')->paginate((int)$per_page, ['*'], 'page', (int)$query->paginate((int)$per_page)->currentPage()-1);
        }
    }
    public static function getRecordById($id)
    {
        $query = BlogPostComment::join('blog_post_to_comments', 'blog_post_to_comments.bptc_bpcomment_id', 'blog_post_comments.bpcomment_id')
        ->join('blog_posts', 'blog_posts.post_id', 'blog_post_to_comments.bptc_post_id')
        ->join('users', 'users.user_id', 'blog_post_comments.bpcomment_user_id');
        return $query->where('bpcomment_id', $id)->first();
    }

    public function createdAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'bpcomment_created_by_id')->select(['admin_id', 'admin_username']);
    }

    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'bpcomment_updated_by_id')->select(['admin_id', 'admin_username']);
    }

}
