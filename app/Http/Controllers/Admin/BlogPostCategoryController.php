<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogPostCategory;
use App\Models\Admin\AdminYokartModel;
use App\Http\Requests\BlogPostCategoryRequest;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\Admin\AdminRole;
use App\Models\BlogPostToCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;

use DB;

class BlogPostCategoryController extends AdminYokartController
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

    public function getListing()
    {
        $data = [];
        $data['listing'] = BlogPostCategory::getRecords();
        $status = false;
        if (!empty($data['listing']) && count($data['listing']) > 0) {
            $data['listing'] =  BlogPostCategory::buildTree($data['listing']);
            $status = true;
        }

        $data['showEmpty'] = 0;
        if (count($data['listing']) == 0) {
            $data['showEmpty'] = 1;
        }

        return jsonresponse($status, '', $data);
    }
    
    public function getParentList(Request $request, $catId = null)
    {
        $categories = BlogPostCategory::getParentList($catId);
        if (!empty($categories) && count($categories) > 0) {
            $categories =  BlogPostCategory::buildTree($categories);
        }
        array_unshift($categories, ['id'=> 0, 'label'=> 'Root']);
        return jsonresponse(true, null, $categories);
    }

    public function store(BlogPostCategoryRequest $request)
    {
        if (!canWrite(AdminRole::BLOGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $category = new BlogPostCategory;
        $category->bpcat_name = $request->input('bpcat_name');
        $category->bpcat_parent = $request->input('bpcat_parent');
        $category->bpcat_publish = 1;
        $category->bpcat_created_by_id = $category->bpcat_updated_by_id = $this->admin->admin_id;
        $category->bpcat_created_at = $category->bpcat_updated_at = Carbon::now();
        $displayOrder = AdminYokartModel::getDisplayOrder('App\Models\BlogPostCategory', 'bpcat_display_order', [
            'bpcat_parent' => $request->input('bpcat_parent')
        ]);
        $category->bpcat_display_order = $displayOrder;
        $category->save();

        return jsonresponse(true, __('NOTI_BLOG_CATEGORY_CREATED'));
    }

    public function updateOnDrag(Request $request)
    {
        if (!canWrite(AdminRole::BLOGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $sourceId = $request->input('source_id'); //bpcat_id
        $destinationId = $request->input('destination_id'); //destination parent
        $position = $request->input('position'); //order

        $destinationCat = BlogPostCategory::select('bpcat_parent')->where('bpcat_id', $destinationId)->first();
        $sourceCat = BlogPostCategory::select('bpcat_parent', 'bpcat_display_order')->where('bpcat_id', $sourceId)->first();

        //shift down existing sub category position by one
        $destinationUpdateQuery = BlogPostCategory::where('bpcat_parent', $destinationId);
        if ($sourceCat->bpcat_parent == $destinationId && $destinationId == 0) { //root to root
            $destinationUpdateQuery->where('bpcat_display_order', '>=', $position)->where('bpcat_display_order', '<', $sourceCat->bpcat_display_order)->increment('bpcat_display_order');
            BlogPostCategory::where('bpcat_parent', $sourceCat->bpcat_parent)->where('bpcat_display_order', '>', $sourceCat->bpcat_display_order)->where('bpcat_display_order', '<=', $position)->decrement('bpcat_display_order');
        } elseif (($sourceCat->bpcat_parent == 0 && $destinationId != 0) || ($sourceCat->bpcat_parent != 0 && $destinationId == 0)) {
            $destinationUpdateQuery->where('bpcat_display_order', '>=', $position)->increment('bpcat_display_order');
        } elseif ($sourceCat->bpcat_parent != 0 && $destinationId != 0) { //child to child
            if ($sourceCat->bpcat_parent == $destinationId) {
                if ($position > $sourceCat->bpcat_display_order) {
                    $destinationUpdateQuery->where('bpcat_display_order', '>', $sourceCat->bpcat_display_order)->where('bpcat_display_order', '<=', $position)->decrement('bpcat_display_order');
                } elseif ($position < $sourceCat->bpcat_display_order) {
                    $destinationUpdateQuery->where('bpcat_display_order', '>=', $position)->where('bpcat_display_order', '<', $sourceCat->bpcat_display_order)->increment('bpcat_display_order');
                }
            }
        }

        //add dragged item under destination category as child category
        BlogPostCategory::where('bpcat_id', $sourceId)->update(['bpcat_parent' => $destinationId, 'bpcat_display_order' => $position, 'bpcat_updated_by_id' => $this->admin->admin_id, 'bpcat_updated_at' => Carbon::now()]);

        //shift up existing sub category position by one
      if (($sourceCat->bpcat_parent == 0 && $destinationId != 0) || ($sourceCat->bpcat_parent != 0 && $destinationId == 0)) { //root to child || child to root
        BlogPostCategory::where('bpcat_parent', $sourceCat->bpcat_parent)->where('bpcat_display_order', '>', $sourceCat->bpcat_display_order)->decrement('bpcat_display_order');
      }

        return jsonresponse(true, __('NOTI_BLOG_CATEGORY_UPDATED'));
    }

    public function update(BlogPostCategoryRequest $request, $id)
    {
        if (!canWrite(AdminRole::BLOGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        BlogPostCategory::where('bpcat_id', $id)->update([
            'bpcat_name' => $request->input('bpcat_name'),
            'bpcat_parent' => $request->input('bpcat_parent'),
            'bpcat_updated_by_id' => $this->admin->admin_id,
            'bpcat_updated_at' => Carbon::now()
        ]);
        return jsonresponse(true, __('NOTI_BLOG_CATEGORY_UPDATED'));
    }

    public function updateStatus(Request $request, $id)
    {
        if (!canWrite(AdminRole::BLOGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        BlogPostCategory::where('bpcat_id', $id)->update(['bpcat_publish' => ($request->input('status')=='true') ? 1 : 0, 'bpcat_updated_by_id' =>$this->admin->admin_id,'bpcat_updated_at' => Carbon::now()]);
        return jsonresponse(true, ($request->input('status')=='true')?__('Category published'):__('Category unpublished'));
    }

    public function destroy($id)
    {
        if (!canWrite(AdminRole::BLOGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        DB::beginTransaction();
        try {
            $this->deleteDependencies($id);
            $this->findCategoriesRecursively($id);
            DB::commit();
            return jsonresponse(true, __('NOTI_BLOG_CATEGORY_DELETED'));
        } catch (\Exception $e) {
            DB::rollback();
            return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
        }
    }
    
    private function deleteDependencies($id)
    {
        BlogPostCategory::where('bpcat_id', $id)->delete();
        BlogPostToCategory::where('ptc_bpcat_id', $id)->delete();
    }

    private function findCategoriesRecursively($child)
    {
        $categoryToDelete = [];
        $childs = BlogPostCategory::select('bpcat_id')->where('bpcat_parent', $child)->get();
        foreach ($childs as $v) {
            $categoryToDelete[$v->bpcat_id] = $v->bpcat_id;
        }
        foreach ($categoryToDelete as $categoryId) {
            $this->deleteDependencies($categoryId);
            $this->findCategoriesRecursively($categoryId);
        }
    }
}
