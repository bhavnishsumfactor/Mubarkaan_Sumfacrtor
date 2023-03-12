<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\MetaTag;
use App\Models\Page;
use App\Models\Product;
use App\Models\Admin\AdminRole;
use App\Models\Brand;
use App\Models\ProductCategory;
use App\Models\BlogPost;

class MetaTagController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::META_TAGS)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getListing(Request $request)
    {
        $meta_type = !empty($request->input('module_type')) ? $request->input('module_type') : 'pages';
        
        switch ($meta_type) {
            case 'pages':
                $records = Page::getAllPages($request->all(), true, [3,4]);
                break;
            case 'products':
                $records = Product::getProducts($request->all(), true);
                break;
            case 'brands':
                $records = Brand::getBrands($request->all(), true);
                break;
            case 'categories':
                $records = ProductCategory::getAllCategories($request->all());
                break;
            case 'blogs':
                $records = BlogPost::getRecords($request->all(), true);
                break;
            default:
                break;
        }
        $status = (isset($records) && $records->count() > 0) ? true : false;
        return jsonresponse($status, '', ['records' => ($records ?? '')]);
    }

    public function getRecord(Request $request)
    {
        $module_type = !empty($request->input('module_type')) ? $request->input('module_type') : 'pages';
        $record_id = $request->input('record_id');
        $routeData = getControllerAction(MetaTag::getRouteName($module_type));
        $metaTags = MetaTag::getMetaTags($routeData['controller'], $routeData['action'], $record_id);
        return jsonresponse(true, '', $metaTags);
    }

    public function updateRecord(Request $request)
    {
        if (!canWrite(AdminRole::META_TAGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $module_type = !empty($request->input('module_type')) ? $request->input('module_type') : 'pages';
        $record_id = $request->input('record_id');
        $routeData = getControllerAction(MetaTag::getRouteName($module_type));
        MetaTag::saveMetaTags($request->except(['record_id', 'module_type', 'record_title', 'page_type']), $routeData['controller'], $routeData['action'], $record_id, null, $this->admin->admin_id);
        return jsonresponse(true, __('NOTI_META_TAGS_UPDATED'));
    }

    public function updateMetaTags()
    {        
        /*Product::select('prod_id','prod_name')->chunk(100, function ($products) {
            foreach ($products as $product) {
                $routeData = getControllerAction(MetaTag::getRouteName('products'));
                $productData['meta_title'] = $product->prod_name; 
                $productData['meta_keywords'] = '';
                $productData['meta_description'] = '';
                $productData['meta_other_meta_tags'] = '';
                MetaTag::saveMetaTags($productData, $routeData['controller'], $routeData['action'], $product->prod_id, null, $this->admin->admin_id);
            }
        });*/

        /*ProductCategory::select('cat_id','cat_name')->chunk(100, function ($categories) {
            foreach ($categories as $category) {
                $routeData = getControllerAction(MetaTag::getRouteName('categories'));
                $categoryData['meta_title'] = $category->cat_name; 
                $categoryData['meta_keywords'] = '';
                $categoryData['meta_description'] = '';
                $categoryData['meta_other_meta_tags'] = '';
                MetaTag::saveMetaTags($categoryData, $routeData['controller'], $routeData['action'], $category->cat_id, null, $this->admin->admin_id);
            }
        });*/

        /*Brand::select('brand_id','brand_name')->chunk(100, function ($brands) {
            foreach ($brands as $brand) {
                $routeData = getControllerAction(MetaTag::getRouteName('brands'));
                $brandData['meta_title'] = $brand->brand_name; 
                $brandData['meta_keywords'] = '';
                $brandData['meta_description'] = '';
                $brandData['meta_other_meta_tags'] = '';
                MetaTag::saveMetaTags($brandData, $routeData['controller'], $routeData['action'], $brand->brand_id, null, $this->admin->admin_id);
            }
        });

        BlogPost::select('post_id','post_title')->chunk(100, function ($blogs) {
            foreach ($blogs as $blog) {
                $routeData = getControllerAction(MetaTag::getRouteName('blogs'));
                $blogData['meta_title'] = $blog->post_title; 
                $blogData['meta_keywords'] = '';
                $blogData['meta_description'] = '';
                $blogData['meta_other_meta_tags'] = '';
                MetaTag::saveMetaTags($blogData, $routeData['controller'], $routeData['action'], $blog->post_id, null, $this->admin->admin_id);
            }
        });*/
    }
}
