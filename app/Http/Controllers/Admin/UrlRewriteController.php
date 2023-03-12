<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\UrlRewrite;
use App\Models\Page;
use App\Models\Product;
use App\Models\Admin\AdminRole;
use App\Models\ProductCategory;
use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\Configuration;
use App\Http\Requests\UrlRewriteRequest;

class UrlRewriteController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::URL_REWRITE)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getListing(Request $request)
    {
        $type = !empty($request->input('module_type')) ? $request->input('module_type') : 'products';
        switch ($type) {
            case 'pages':
                $records = Page::getAllPages($request->all(), 1, [3,4]);
                break;
            case 'products':
                $records = Product::getProducts($request->all(), 1);
                break;
            case 'categories':
                $records = ProductCategory::getAllCategories($request->all());
                break;
            case 'blogs':
                $records = BlogPost::getRecords($request->all(), 1);
                break;
            case 'brands':
                $records = Brand::getBrands($request->all(), 1);
                break;
            default:
                break;
        }
        $status = (isset($records) && $records->count() > 0) ? true : false;
        return jsonresponse($status, '', ['records' => ($records ?? ''), 'redirection' => Configuration::getValue('ENABLE_301_REDIRECTION')]);
    }

    public function updateRecord(UrlRewriteRequest $request)
    {
        if (!canWrite(AdminRole::URL_REWRITE)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        
        UrlRewrite::where('urlrewrite_id', $request->input('urlrewrite_id'))->update(['urlrewrite_custom' => $request->input('urlrewrite_custom')]);
        return jsonresponse(true, __('NOTI_URL_REWRITING_UPDATED'));
    }
    
    public function enable301(Request $request)
    {
        if (!canWrite(AdminRole::URL_REWRITE)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $status = (!empty($request->input('status')) && $request->input('status') == 'true') ? 1 : 0;
        Configuration::saveValue('ENABLE_301_REDIRECTION', $status);
        $message = (!empty($request->input('status')) && $request->input('status') == 'true') ?
                    __('NOTI_301_ENABLED') : __('NOTI_301_DISABLED');
        return jsonresponse(true, $message);
    }

    public function updateRecordId(Request $request)
    {
        UrlRewrite::updateRecordId();
    }

    public function updateUrlRewrite(Request $request)
    {
        Product::select('prod_id','prod_name')->chunk(100, function ($products) {
            foreach ($products as $product) {
                
                $productName = preg_replace('/[^A-Za-z0-9 !@#$%^&*().]/u','', strip_tags($product->prod_name));
                UrlRewrite::where('urlrewrite_type', UrlRewrite::PRODUCT_TYPE)->where('urlrewrite_record_id', $product->prod_id)
                ->update([
                    'urlrewrite_custom' =>  strtolower(str_replace(' ', '-', $productName))
                ]);
            }
        });

        ProductCategory::select('cat_id','cat_name')->chunk(100, function ($categories) {
            foreach ($categories as $category) {
                $productCategory = preg_replace('@[^A-Za-z0-9\w\ ]@', '', $category->cat_name);
                UrlRewrite::where('urlrewrite_type', UrlRewrite::CATEGORY_TYPE)->where('urlrewrite_record_id', $category->cat_id)
                ->update([
                    'urlrewrite_custom' => strtolower(str_replace(' ', '-', $productCategory))
                ]);
            }
        });
        Brand::select('brand_id','brand_name')->chunk(100, function ($brands) {
            foreach ($brands as $brand) {
                $brandName = preg_replace('/[^A-Za-z0-9 !@#$%^&*().]/u', '', $brand->brand_name);
                UrlRewrite::where('urlrewrite_type', UrlRewrite::BRAND_TYPE)->where('urlrewrite_record_id', $brand->brand_id)
                ->update([
                    'urlrewrite_custom' => strtolower(str_replace(' ', '-', $brandName))
                ]);
            }
        });

        BlogPost::select('post_id','post_title')->chunk(100, function ($blogs) {
            foreach ($blogs as $blog) {
                $blogName = preg_replace('/[^A-Za-z0-9 !@#$%^&*().]/u', '', $blog->post_title);
                UrlRewrite::where('urlrewrite_type', UrlRewrite::BLOG_TYPE)->where('urlrewrite_record_id', $blog->post_id)
                ->update([
                    'urlrewrite_custom' => strtolower(str_replace(' ', '-', $blogName))
                ]);
            }
        });
    }
}
