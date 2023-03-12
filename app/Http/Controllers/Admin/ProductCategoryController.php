<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\ProductCategoryContent;
use App\Models\ProductToCategory;
use App\Models\Admin\AdminYokartModel;
use App\Models\Configuration;
use App\Models\Admin\AdminRole;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Requests\CategoryImportRequest;
use App\Models\UrlRewrite;
use App\Exports\CategoriesExport;
use App\Imports\CategoryImport;
use App\Exports\ErrorExport;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\HeadingRowImport;
use Carbon\Carbon;
use Excel;
use DB;

class ProductCategoryController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::CATEGORIES)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getListing()
    {
        if (!canRead(AdminRole::CATEGORIES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $productCategories = ProductCategory::getCategories();
        $productCategoriesCount = count($productCategories);
        if (!empty($productCategories) &&  $productCategoriesCount > 0) {
            $productCategories =  ProductCategory::buildTree1($productCategories);
        }
        
        $records['taxCode'] = Configuration::getValue('TAX_CODE');
        $records['listing'] = $productCategories;
        $records['total'] = $productCategoriesCount;
        $records['showEmpty'] = 0;
        if (count($records['listing']) == 0) {
            $records['showEmpty'] = 1;
        }
        return jsonresponse(true, '', $records);
    }

    public function getParentList(Request $request, $catId = null)
    {
        $categories = ProductCategory::getParentList($catId);
        if (!empty($categories) && count($categories) > 0) {
            $categories =  ProductCategory::buildTree($categories);
        }
        array_unshift($categories, ['id' => 0, 'label' => 'Root']);
        return jsonresponse(true, null, $categories);
    }

    public function updateOnDrag(Request $request)
    {
        if (!canWrite(AdminRole::CATEGORIES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $sourceId = $request->input('source_id'); //cat_id
        $destinationId = $request->input('destination_id'); //destination parent
        $position = $request->input('position'); //order

        $destinationCat = ProductCategory::select('cat_parent')->where('cat_id', $destinationId)->first();
        $sourceCat = ProductCategory::select('cat_parent', 'cat_display_order')->where('cat_id', $sourceId)->first();

        //shift down existing sub category position by one
        $destinationUpdateQuery = ProductCategory::where('cat_parent', $destinationId);
        if ($sourceCat->cat_parent == $destinationId && $destinationId == 0) { //root to root
            $destinationUpdateQuery->where('cat_display_order', '>=', $position)->where('cat_display_order', '<', $sourceCat->cat_display_order)->increment('cat_display_order');
            ProductCategory::where('cat_parent', $sourceCat->cat_parent)->where('cat_display_order', '>', $sourceCat->cat_display_order)->where('cat_display_order', '<=', $position)->decrement('cat_display_order');
        } elseif (($sourceCat->cat_parent == 0 && $destinationId != 0) || ($sourceCat->cat_parent != 0 && $destinationId == 0)) {
            $destinationUpdateQuery->where('cat_display_order', '>=', $position)->increment('cat_display_order');
        } elseif ($sourceCat->cat_parent != 0 && $destinationId != 0) { //child to child
            if ($sourceCat->cat_parent == $destinationId) {
                if ($position > $sourceCat->cat_display_order) {
                    $destinationUpdateQuery->where('cat_display_order', '>', $sourceCat->cat_display_order)->where('cat_display_order', '<=', $position)->decrement('cat_display_order');
                } elseif ($position < $sourceCat->cat_display_order) {
                    $destinationUpdateQuery->where('cat_display_order', '>=', $position)->where('cat_display_order', '<', $sourceCat->cat_display_order)->increment('cat_display_order');
                }
            }
        }
        //add dragged item under destination category as child category
        ProductCategory::where('cat_id', $sourceId)->update(['cat_parent' => $destinationId, 'cat_display_order' => $position, 'cat_updated_by_id' => $this->admin->admin_id, 'cat_updated_at' => Carbon::now()]);

        //shift up existing sub category position by one
        if (($sourceCat->cat_parent == 0 && $destinationId != 0) || ($sourceCat->cat_parent != 0 && $destinationId == 0)) { //root to child || child to root
            ProductCategory::where('cat_parent', $sourceCat->cat_parent)->where('cat_display_order', '>', $sourceCat->cat_display_order)->decrement('cat_display_order');
        }
        \Cache::forget('category-section1');
        \Cache::forget('header-section');
        return jsonresponse(true, __('NOTI_CATEGORY_DISPLAY_ORDER_UPDATED'));
    }


    public function store(ProductCategoryRequest $request)
    {
        if (!canWrite(AdminRole::CATEGORIES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $category = new ProductCategory;
        $category->cat_name = $request->input('cat_name');
        $category->cat_parent = $request->input('cat_parent');
        $category->cat_tax_code = $request->input('cat_tax_code');
        $category->cat_publish = 1;
        $displayOrder = AdminYokartModel::getDisplayOrder('App\Models\ProductCategory', 'cat_display_order', [
            'cat_parent' => $request->input('cat_parent')
        ]);
        $category->cat_display_order = $displayOrder;
        $category->cat_created_by_id = $this->admin->admin_id;
        $category->cat_updated_by_id = $this->admin->admin_id;
        $category->cat_created_at = Carbon::now();
        $category->cat_updated_at = Carbon::now();
        $category->save();
        UrlRewrite::saveUrl('categories', $category->cat_id);
        imageUpload($request, $category->cat_id, 'productCategoryBanner');
        \Cache::forget('category-section1');
        \Cache::forget('header-section');
        return jsonresponse(true, __('NOTI_CATEGORY_CREATED'), $category->cat_id);
    }

    public function update(ProductCategoryRequest $request, ProductCategory $productCategory)
    {
        if (!canWrite(AdminRole::CATEGORIES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        ProductCategory::where('cat_id', $request->input('cat_id'))->update([
            'cat_name' => $request->input('cat_name'),
            'cat_parent' => $request->input('cat_parent'),
            'cat_tax_code' => $request->input('cat_tax_code')
        ]);
        imageUpload($request, $request->input('cat_id'), 'productCategoryBanner');
        \Cache::forget('category-section1');
        \Cache::forget('header-section');
        return jsonresponse(true, __('NOTI_CATEGORY_UPDATED'));
    }


    public function destroy($id)
    {
        if (!canWrite(AdminRole::CATEGORIES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        DB::beginTransaction();
        try {
            $this->deleteDependencies($id);
            $this->findCategoriesRecursively($id);
            UrlRewrite::removeUrl('categories', $id);
            DB::commit();
            \Cache::forget(ProductCategory::cacheKey());
            \Cache::forget('category-section1');
            \Cache::forget('header-section');
            return jsonresponse(true, __('NOTI_CATEGORY_DELETED'));
        } catch (\Exception $e) {
            DB::rollback();
            return jsonresponse(true, __('NOTI_SOMETHING_WENT_WRONG'));
        }
    }

    public function updateStatus(Request $request, $id)
    {
        if (!canWrite(AdminRole::CATEGORIES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        ProductCategory::where('cat_id', $id)->update(['cat_publish' => ($request->input('status') == 'true') ? 1 : 0, 'cat_updated_by_id' => $this->admin->admin_id, 'cat_updated_at' => Carbon::now()]);
        return jsonresponse(true, ($request->input('status') == 'true') ? __('NOTI_CATEGORY_PUBLISHED') : __('NOTI_CATEGORY_UNPUBLISHED'));
    }

    private function deleteDependencies($id)
    {
        ProductCategory::where('cat_id', $id)->delete();
        ProductCategoryContent::where('pcc_cat_id', $id)->delete();
        ProductToCategory::where('ptc_cat_id', $id)->delete();
    }

    private function findCategoriesRecursively($child)
    {
        $categoryToDelete = [];
        $childs = ProductCategory::select('cat_id')->where('cat_parent', $child)->get();
        foreach ($childs as $v) {
            $categoryToDelete[$v->cat_id] = $v->cat_id;
        }
        foreach ($categoryToDelete as $categoryId) {
            $this->deleteDependencies($categoryId);
            $this->findCategoriesRecursively($categoryId);
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new CategoriesExport, 'categories.xlsx');
    }

    public function import(CategoryImportRequest $request)
    {
        clearStorageFolder('public/excel/');
        $category = new CategoryImport();
        $sheetHeadings = (new HeadingRowImport)->toArray($request->file('file'));
        $categoryHeading = ['category_id', 'parent_id', 'category_name', 'category_parent', 'category_publish'];
        foreach ($categoryHeading as $heading) {
            if (!in_array($heading, $sheetHeadings[0][0])) {
                return jsonresponse(false, __('Please import the correct file'));
            }
        }
        Excel::import($category, $request->file('file'));
        $error = $category->getErrors();
        if (isset($error) && !empty($error)) {
            $export = new ErrorExport($error);
            $name = 'category-error-'.time() . '.xls';
            Excel::store($export, 'public/excel/' . $name);
            return jsonresponse(true, __('NOTI_CATEGORIES_IMPORTED'), url('storage/excel/'.$name));
        } else {
            return jsonresponse(true, __('NOTI_CATEGORIES_IMPORTED'));
        }
    }

    public function categoryForExcel(Request $request)
    {
        $productCategory = ProductCategory::getCategoryWithParent();
        if (!empty($request->input('category'))) {
            $productCategory->where('product_categories.cat_name', 'LIKE', '%'. trim($request->input('category')). '%');
        }
        $data['category'] = $productCategory->get()->toArray();
        $data['maxId'] = ProductCategory::max('cat_id');
        return jsonresponse(true, '', $data);
    }

    public function categoryExcelUpdate(Request $request)
    {
        $categoryError = [];
        $categoryData  = [];
        if (!empty($request->all())) {
            foreach ($request->all() as $key => $category) {
                $validator = Validator::make($category, [ 'cat_name' => 'required']);
                if (empty($category['cat_name']) && empty($category['parent_name'])) {
                    continue;
                }
                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $message) {
                        $categoryError[$key]['key'] = $category['cat_id'];
                        $categoryError[$key]['message'] = $message;
                    }
                    array_push($categoryData, $category);
                    continue;
                }
                if (isset($category['parent_id']) && !empty($category['parent_id'])) {
                    $parentCategory = ProductCategory::where('cat_id', [trim($category['parent_id'])])->first();
                    
                    if (isset($parentCategory->cat_id) && !empty($parentCategory->cat_id)) {
                        $categorySaveData = [
                            'cat_name' => $category['cat_name'],
                            'cat_parent' => $parentCategory->cat_id,
                            'cat_tax_code' => !empty($category['cat_tax_code']) ? $category['cat_tax_code'] : '',
                            'cat_publish' =>  (strtolower($category['cat_publish']) == 'yes') ? 1: 0
                        ];

                        $productCategory = ProductCategory::where('cat_id', trim($category['cat_id']));
                        if ($productCategory->count() > 0) {
                            $productCategory->update($categorySaveData);
                        } else {
                            $categoryId =  ProductCategory::create($categorySaveData)->cat_id;
                            UrlRewrite::saveUrl('categories', $categoryId);
                        }
                    } else {
                        $categoryError[$key]['key'] = $category['cat_id'];
                        $categoryError[$key]['message'] = "Parent Category Not Found";
                        array_push($categoryData, $category);
                    }
                } else {
                    $categorySaveData = [
                        'cat_name' => $category['cat_name'],
                        'cat_parent' => config('constants.NO'),
                        'cat_tax_code' => !empty($category['cat_tax_code']) ? $category['cat_tax_code'] : '',
                        'cat_publish' =>  (strtolower($category['cat_publish']) == 'yes') ? 1: 0,
                    ];
                    $productCategory = ProductCategory::where('cat_id', trim($category['cat_id']));
                    if ($productCategory->count() > 0) {
                        $productCategory->update($categorySaveData);
                    } else {
                        $categoryId =  ProductCategory::create($categorySaveData)->cat_id;
                        UrlRewrite::saveUrl('categories', $categoryId);
                    }
                }
            }
            if (isset($categoryError) && !empty($categoryError)) {
                $export = new ErrorExport($categoryError);
                $name = 'category-error-' . time() . '.xls';
                Excel::store($export, 'public/excel/' . $name);
                $data['categoryError'] = url('storage/excel/'.$name);
            }
            $data['categoryData']  = $categoryData;
            return jsonresponse(true, __('NOTI_CATEGORIES_UPDATED'), $data);
        }
        return jsonresponse(true, __('NOTI_SOMETHING_WENT_WRONG'), []);
    }

    public function getLastId(Request $request)
    {
        $data = [];
        $data['taxCode'] = Configuration::getValue('TAX_CODE');
        $data['lastId'] = ProductCategory::max('cat_id');
        return jsonresponse(true, '', $data);
    }
    public function searchRecord(Request $request)
    {
        $keyword = $request->input('query');
        $data = ProductCategory::getCategoriesByName($keyword, []);
        return jsonresponse(true, '', $data);
    }
}
