<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogReplyToComment;
use App\Models\BlogPostCategory;
use App\Models\AttachedFile;
use App\Models\Configuration;
use Illuminate\Http\Request;
use App\Http\Controllers\YokartController;
use Carbon\Carbon;

class BlogController extends YokartController
{
    public function index(Request $request, $categoryId = '')
    {
        $filters = [
            'featured' => config('constants.NO'),
            'category' => $categoryId,
            'search' => $request->input('query')
        ];
        $latestBlogs = BlogPost::getBlogs([], 2);
        $filters['excludedBlogs'] = $latestBlogs->pluck('post_id');
        $blogs = BlogPost::getBlogs($filters);
        $logo = AttachedFile::getFileUrl('frontendLogo', 0, 0, 'thumb');
        $featuredBlogs = BlogPost::getBlogs(['featured' => config('constants.YES')], BlogPost::FEATURED_BLOG_COUNT);
        return view('blogs.index', compact('blogs', 'featuredBlogs', 'latestBlogs','logo'));
    }

    public function show($blogId)
    {
        $blog = BlogPost::getRecordByBlogId($blogId);
        if (empty($blog)) {
            abort(404);
        }
        $blog->content->bpc_description = str_ireplace('public/storage/', url('') . '/public/storage/', $blog->content->bpc_description);

        $categoryArray = $blog->category->pluck('ptc_bpcat_id');
        $postComments = BlogPost::getComments($blog->post_id);
        $replies = [];
        if (!empty($postComments)) {
            $commentIds = $postComments->pluck('bptc_bpcomment_id');
            foreach ($commentIds as $id) {
                $replies[$id] = BlogReplyToComment::getReplies($id);
            }
        }

        $filters = [
            'relatedCategory' => $categoryArray,
            'relatedPosts' => $blog->post_id
        ];
        
        $relatedBlogs = BlogPost::getBlogs($filters, BlogPost::RELATED_BLOG_COUNT);
        $blog->update(['post_view_count' => $blog->post_view_count + 1 ]);
        $businessName = Configuration::getValue('BUSINESS_NAME');
        return view('blogs.detail', compact('blog', 'relatedBlogs', 'postComments', 'replies', 'businessName'))->with('record_id', $blog->post_id);
    }

    public function sidebarContent()
    {
        $categories = BlogPostCategory::withCount('blogs')->where('bpcat_publish', config('constants.YES'))->get();
        $recentBlogs = BlogPost::getBlogs(['featured' => config('constants.NO')], BlogPost::RECENT_BLOG_COUNT);
        return view('blogs.sidebar', compact('recentBlogs', 'categories'));
    }
}
