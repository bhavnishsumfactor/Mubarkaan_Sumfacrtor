<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductReviewLog;
use App\Models\AttachedFile;
use App\Models\ProductReviewHelpful;
use App\Models\ProductReviewComment;
use Carbon\Carbon;
use App\Http\Requests\ProductReviewCommentRequest;
use App\Models\Admin\AdminRole;
use DB;

class ProductReviewController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::PRODUCT_REVIEWS)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getListing(Request $request, $product_id = null)
    {
        $request['prod_id'] = $product_id;
        $data = [];
        $data['reviews'] = ProductReview::getRecords($request->all());
        if (!empty($product_id)) {
            $data['product'] = Product::select('prod_name')->where('prod_id', $product_id)->first();
        }
        $data['summary'] = ProductReview::getRatingSummaryByProductId($product_id);
        
        $data['showEmpty'] = 0;
        if (empty($request['search']) && $data['reviews']->count() == 0) {
            $data['showEmpty'] = 1;
        }

        return jsonresponse(($data['reviews']->count() > 0) ? true : false, __('LBL_REVIEW_LISTED'), $data);
    }

    public function detail(Request $request, $preview_id)
    {
        $reviews = ProductReview::getReviewById($preview_id);
        
        return jsonresponse(($reviews->count() > 0) ? true : false, __('LBL_REVIEW_LISTED'), $reviews);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!canWrite(AdminRole::PRODUCT_REVIEWS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        ProductReview::where('preview_id', $id)->update(['preview_publish' => ($request->input('status') == 'true') ? 1 : 0]);
        return jsonresponse(true, ($request->input('status') == 'true') ? __('LBL_REVIEW_PUBLISHED') : __('LBL_REVIEW_UNPUBLISHED'));
    }

    public function updateReviewStatus(Request $request)
    {
        if (!canWrite(AdminRole::PRODUCT_REVIEWS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $review = [];
        $review['preview_status'] = $request->input('status');
        if ($request->input('status') == 2) {
            $review['preview_updated_at'] = $review['preview_approved_at'] = Carbon::now();
        }
        $review['preview_updated_at'] = Carbon::now();
        ProductReview::where('preview_id', $request->input('id'))->update($review);
        return jsonresponse(true, __('LBL_REVIEW_STATUS_UPDATED'));
    }

    public function updateEditedReviewStatus(Request $request)
    {
      
        DB::beginTransaction();
        try {
            $review = [];
            $review['preview_status'] = $request->input('status');
            if ($request->input('status') == 2) { //approved
                $review['preview_approved_at'] = Carbon::now();

                $preview = ProductReviewLog::select('prl_preview_id', 'prl_preview_rating', 'prl_preview_title', 'prl_preview_description')
                ->where('prl_id', $request->input('id'))->first();
                ProductReview::where('preview_id', $preview->prl_preview_id)->update([
                    'preview_rating' => $preview->prl_preview_rating,
                    'preview_title' => $preview->prl_preview_title,
                    'preview_description' => $preview->prl_preview_description,
                    'preview_status' => $request->input('status'),
                    'preview_approved_at' => Carbon::now(),
                    'preview_updated_at' => Carbon::now()
                ]);
                ProductReviewLog::where('prl_id', $request->input('id'))->delete();

                ProductReviewComment::where('prc_preview_id', $preview->prl_preview_id)->delete();
                ProductReviewHelpful::where('prh_preview_id', $preview->prl_preview_id)->delete();

                AttachedFile::where('afile_type', AttachedFile::getConstantVal('productReviewImage'))
                ->where('afile_record_id', $preview->prl_preview_id)->delete();

                AttachedFile::where('afile_type', AttachedFile::getConstantVal('productReviewLogImage'))
                ->where('afile_record_id', $request->input('id'))
                ->update([
                    'afile_type' => AttachedFile::getConstantVal('productReviewImage'),
                    'afile_record_id' => $preview->prl_preview_id
                ]);
            } elseif ($request->input('status') == 3) { //rejected
                ProductReviewLog::where('prl_id', $request->input('id'))->delete();
                AttachedFile::where('afile_type', AttachedFile::getConstantVal('productReviewLogImage'))
                ->where('afile_record_id', $request->input('id'))->delete();
            }
            DB::commit();
            return jsonresponse(true, __('LBL_REVIEW_STATUS_UPDATED'));
        } catch (Exception $e) {
            DB::rollBack();
            return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
        }
    }

    public function postReply(ProductReviewCommentRequest $request)
    {
        if (!canWrite(AdminRole::PRODUCT_REVIEWS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        ProductReviewComment::create([
            'prc_preview_id' => $request['id'],
            'prc_admin_id' => \Auth::guard('admin')->user()->admin_id,
            'prc_comments' => $request['reply'],
            'prc_created_at' => Carbon::now()
        ]);
        return jsonresponse(true, '');
    }
}
