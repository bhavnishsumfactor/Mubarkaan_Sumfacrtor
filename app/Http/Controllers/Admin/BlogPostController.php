<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\Admin\AdminRole;
use App\Models\BlogPostCategory;
use App\Models\BlogPostContent;
use App\Models\BlogPostToCategory;
use App\Models\Configuration;
use App\Models\AttachedFile;
use App\Models\Product;
use App\Models\ProductToBlogPost;
use App\Models\UrlRewrite;
use App\Http\Requests\BlogPostRequest;
use DB;
use Carbon\Carbon;

class BlogPostController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::BLOGS)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getListing(Request $request)
    {
        $data = [];

        $data['blogs'] = BlogPost::getRecords($request->all());

        $data['showEmpty'] = 0;
        if (empty($request['search']) && $data['blogs']->count() == 0) {
            $data['showEmpty'] = 1;
        }

        return jsonresponse(true, null, $data);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!canWrite(AdminRole::BLOGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $status = $request->input('status') == 'true' ? config('constants.YES') : config('constants.NO');
        if ($request->input('type') == 'post_featured' && $status == 1) {
            $count = BlogPost::where('post_featured', 1)->count();
            if ($count == BlogPost::FEATURED_BLOG_COUNT) {
                return jsonresponse(false, __("Atmost ".BlogPost::FEATURED_BLOG_COUNT." blogs can be featured"));
            }
        }
        BlogPost::where('post_id', $id)->update([$request->input('type') => $status, 'post_updated_by_id' => $this->admin->admin_id, 'post_updated_at'=> Carbon::now() ]);
        \Cache::forget('blog-section1');
        \Cache::forget('blog-section2');
        \Cache::forget('blog-section3');
        return jsonresponse(true, __('NOTI_BLOG_STATUS_UPDATED'));
    }

    public function pageLoadData()
    {
        $categories = BlogPostCategory::select('bpcat_name as label', 'bpcat_id as id', 'bpcat_parent', 'bpcat_id')
        ->where('bpcat_publish', config('constants.YES'))->get();
        if (!empty($categories) && count($categories) > 0) {
            $categories =  BlogPostCategory::buildTree($categories);
        }
        $products = [];
        if (Configuration::getValue('LINK_BLOG_WITH_PRODUCTS')) {
            $products = Product::where('prod_published', config('constants.YES'))
                        ->where('prod_cost', '>=', 1)
                        ->where('prod_stock', '>=', 0)
                        ->pluck('prod_name', 'prod_id');
        }
        return jsonresponse(true, null, compact('categories', 'products'));
    }

    public function store(BlogPostRequest $request)
    {
        if (!canWrite(AdminRole::BLOGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $blogRequest = $request->only(['post_title', 'post_author_name', 'post_published_on']);
        $blogRequest['post_publish'] =  (!empty($request->input('post_publish')) ? config('constants.YES') : config('constants.NO'));
        $blogRequest['post_published_on'] =  (!empty($request->input('post_published_on')) ? date('Y-m-d', strtotime($request->input('post_published_on'))) : Carbon::now());
        $blogRequest['post_comment_opened'] =  (($request->input('post_comment_opened') == 'true') ? config('constants.YES') : config('constants.NO'));
        $blogRequest['post_created_at'] = $blogRequest['post_updated_at'] = Carbon::now();
        $blogRequest['post_created_by_id'] = $blogRequest['post_updated_by_id'] = $this->admin->admin_id;

        $postId = BlogPost::create($blogRequest)->post_id;

        UrlRewrite::saveUrl('blogs', $postId);

        $contentRequest = $request->only(['bpc_short_description', 'bpc_description']);
        $contentRequest['bpc_post_id'] = $postId;
        BlogPostContent::create($contentRequest);

        $this->saveCategories($request->input('ptc_bpcat_id'), $postId);
        $this->saveProducts($request->input('ptbp_prod_id'), $postId);
        imageUpload($request, $postId, 'blogImage');
        \Cache::forget('blog-section1');
        \Cache::forget('blog-section2');
        \Cache::forget('blog-section3');
        return jsonresponse(true, __('NOTI_BLOG_CREATED'));
    }

    public function edit($id)
    {
        if (!canRead(AdminRole::BLOGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $blog = BlogPost::getRecordById($id);
        $categories = BlogPostCategory::select('bpcat_name as label', 'bpcat_id as id', 'bpcat_parent', 'bpcat_id')
        ->get();
        if (!empty($categories) && count($categories) > 0) {
            $categories =  BlogPostCategory::buildTree($categories);
        }
        $products = [];
        if (Configuration::getValue('LINK_BLOG_WITH_PRODUCTS')) {
            $products = Product::where('prod_published', config('constants.YES'))->pluck('prod_name', 'prod_id');
        }
        return jsonresponse(true, null, compact('categories', 'blog', 'products'));
    }

    public function update(BlogPostRequest $request, $id)
    {
        if (!canWrite(AdminRole::BLOGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $blogRequest = $request->only(['post_title', 'post_author_name', 'post_published_on']);
        $blogRequest['post_published_on'] =  date('Y-m-d', strtotime($request->input('post_published_on')));
        $blogRequest['post_publish'] =  (!empty($request->input('post_publish')) ? config('constants.YES') : config('constants.NO'));
        $blogRequest['post_comment_opened'] =  (($request->input('post_comment_opened') == 'true' || $request->input('post_comment_opened') == 1) ? config('constants.YES') : config('constants.NO'));
        $blogRequest['post_updated_at'] = Carbon::now();
        $blogRequest['post_updated_by_id'] = $this->admin->admin_id;

        $insertId = BlogPost::where('post_id', $id)->update($blogRequest);
        $contentRequest = $request->only(['bpc_short_description', 'bpc_description']);
        BlogPostContent::where('bpc_post_id', $id)->update($contentRequest);

        BlogPostToCategory::where('ptc_post_id', $id)->delete();
        ProductToBlogPost::where('ptbp_post_id', $id)->delete();
        $this->saveCategories($request->input('ptc_bpcat_id'), $id);
        $this->saveProducts($request->input('ptbp_prod_id'), $id);
        imageUpload($request, $id, 'blogImage');
        \Cache::forget('blog-section1');
        \Cache::forget('blog-section2');
        \Cache::forget('blog-section3');
        return jsonresponse(true, __('NOTI_BLOG_UPDATED'));
    }

    public function saveProducts($productId, $postId)
    {
        if (!canWrite(AdminRole::BLOGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $products = [];
        if ($productId) {
            $products = json_decode($productId, true);
        }
        foreach ($products as $product) {
            ProductToBlogPost::create([
                'ptbp_prod_id' =>$product,
                'ptbp_post_id' =>$postId
            ]);
        }
    }
    public function saveCategories($catId, $postId)
    {
        if (!canWrite(AdminRole::BLOGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $categories = [];
        if ($catId) {
            $categories = json_decode($catId, true);
        }
        foreach ($categories as $category) {
            BlogPostToCategory::create([
                'ptc_bpcat_id' =>$category,
                'ptc_post_id' =>$postId
            ]);
        }
    }
    
    public function destroy($id)
    {
        if (!canWrite(AdminRole::BLOGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        DB::beginTransaction();
        try {
            BlogPost::where('post_id', $id)->delete();
            UrlRewrite::removeUrl('blogs', $id);
            BlogPostContent::where('bpc_post_id', $id)->delete();
            BlogPostToCategory::where('ptc_post_id', $id)->delete();
            AttachedFile::deleteFileById(AttachedFile::SECTIONS['blogImage'], $id);
            \Cache::forget('blog-section1');
            \Cache::forget('blog-section2');
            \Cache::forget('blog-section3');
            DB::commit();
            return jsonresponse(true, __('NOTI_BLOG_DELETED'));
        } catch (\Exception $e) {
            DB::rollback();
            return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
        }
    }
}
