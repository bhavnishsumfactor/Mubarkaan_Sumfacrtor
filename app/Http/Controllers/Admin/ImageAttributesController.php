<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\AttachedFile;
use App\Models\Product;
use App\Models\Admin\AdminRole;
use App\Models\Brand;
use App\Models\ProductCategory;
use App\Models\BlogPost;
use App\Models\ProductOption;
use App\Models\ProductOptionName;
use Carbon\Carbon;

class ImageAttributesController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::ALT_IMAGES)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getListing(Request $request)
    {
        $meta_type = !empty($request->input('module_type')) ? $request->input('module_type') : 'products';
        $afile_type = 'productImages';
        switch ($meta_type) {
            case 'products':
                $records = Product::getProducts($request->all(), true, true);
                $afile_type = 'productImages';
                break;
            case 'brands':
                $records = Brand::getBrands($request->all(), true, true);
                $afile_type = 'brandLogo';
                break;
            case 'categories':
                $records = ProductCategory::getAllCategories($request->all(), true);
                $afile_type = 'productCategoryBanner';
                break;
            case 'blogs':
                $records = BlogPost::getRecords($request->all(), true, true);
                $afile_type = 'blogImage';
                break;
            default:
                break;
        }
        $status = (isset($records) && $records->count() > 0) ? true : false;
        return jsonresponse($status, '', ['records' => ($records ?? ''), 'afile_type' => $afile_type]);
    }

    public function getRecord(Request $request)
    {
        $afile_type = $request->input('afile_type');
        $record_id = $request->input('record_id');
        $imageTags = AttachedFile::getBulkFiles(AttachedFile::SECTIONS[$afile_type], $record_id, 0, true, false);
        if ($afile_type == 'productImages') {
            foreach ($imageTags as $key => $imageTag) {
                $imageTags[$key]->variant_name = '';
                if (!empty($imageTag->afile_record_subid)) {
                    $option = ProductOption::select('poption_opn_id')->where('poption_prod_id', $imageTag->afile_record_id)->where('poption_id', $imageTag->afile_record_subid)->first();
                    $optionName = ProductOptionName::where('opn_id', $option->poption_opn_id)->first();
                    $imageTags[$key]->variant_name = $optionName->opn_value;
                }
            }
        }
        return jsonresponse(true, '', $imageTags);
    }

    public function updateRecord(Request $request)
    {
        if (!canWrite(AdminRole::ALT_IMAGES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $images = $request->input('images');
        if (!empty($images)) {
            $images = json_decode($images);
            foreach ($images as $k => $v) {
                AttachedFile::where('afile_id', $v->afile_id)->update([
                  'afile_attribute_title' => !empty($v->afile_attribute_title) ? $v->afile_attribute_title : '',
                  'afile_attribute_alt' => !empty($v->afile_attribute_alt) ? $v->afile_attribute_alt : '',
                  'afile_updated_by_id' => $this->admin->admin_id,
                  'afile_meta_updated_at' => Carbon::now()
                ]);
            }
        }
        return jsonresponse(true, __('NOTI_ALT_IMAGES_UPDATED'));
    }
}
