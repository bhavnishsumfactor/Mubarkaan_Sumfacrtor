<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\YokartController;
use App\Http\Requests\ReviewRequest;
use App\Models\ProductReview;
use App\Models\OrderProduct;
use App\Models\ProductReviewHelpful;
use App\Models\Admin\Admin;
use App\Models\Admin\AdminRole;
use App\Models\AttachedFile;
use App\Models\Configuration;
use App\Models\ProductReviewLog;
use App\Models\Notification;
use App\Models\Product;
use App\Models\NotificationTemplate;
use App\Jobs\SendNotification;
use App\Helpers\EmailHelper;
use App\Helpers\FileHelper;
use Str;
use Carbon\Carbon;
use App\Events\SystemNotification;
use Inertia\Inertia;
use Redirect;

class ProductReviewController extends YokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth', ['except' => ['getProductReviews', 'helpful', 'nothelpful']]);
    }

    public function helpful(Request $request)
    {
        if (empty($this->signed_in)) {
            return jsonresponse(false, '', loginPopup());
        } else {
            $userId = $this->user->user_id;
        }
        ProductReviewHelpful::saveHelpful($request['id'], $userId, 1);
        $review = ProductReview::getFeedbackSummaryById($request['id'], $userId);
        return jsonresponse(true, __('Helpful'), view('themes.' . config('theme') . '.product.partials.reviewsHelpful', compact('review'))->render());
    }

    public function nothelpful(Request $request)
    {
        if (empty($this->signed_in)) {
            return jsonresponse(false, '', loginPopup());
        } else {
            $userId = $this->user->user_id;
        }
        ProductReviewHelpful::saveHelpful($request['id'], $userId, 0);
        $review = ProductReview::getFeedbackSummaryById($request['id'], $userId);
        return jsonresponse(true, __('Not Helpful'), view('themes.' . config('theme') . '.product.partials.reviewsHelpful', compact('review'))->render());
    }

    public function getProductReviews(Request $request)
    {
        $record = [];
        if (empty($this->signed_in)) {
            $request['userId'] = '';
            $record['can_post'] = ['status' => false, 'message' => '', 'purchase' => false, 'data' => ''];
        } else {
            $request['userId'] = $this->user->user_id;
            $record['can_post'] = ProductReview::authorizedToPost($request['userId'], $request['id']);
        }
      
        $record['productReviews'] = ProductReview::getReviews($request);
        $record['rating'] = ProductReview::getRatingByProductId($request['id']);
        $record['review_count'] = $record['productReviews']->total();
        $record['prod_id'] = $request['id'];
        return view('themes.' . config('theme') . '.product.partials.reviews', $record);
    }

    /* buyer dashboard reviews listing functions */
    private function saveOrUpdate($request)
    {
        $status = ProductReview::PENDING;
        if (Configuration::getValue('PRODUCT_REVIEW_APPROVE_STATUS') == 1) {
            $status = ProductReview::APPROVED;
        }
        if (!empty($request['preview_id'])) {
            if($status == ProductReview::PENDING){
                $review = [
                    'prl_preview_id' => $request['preview_id'],
                    'prl_preview_rating' => $request['preview_rating'],
                    'prl_preview_title' => $request['preview_title'],
                    'prl_preview_description' => $request['preview_description'],
                    'prl_preview_status' => $status,
                    'prl_preview_created_at' => Carbon::now()
                ];
                $prlId = ProductReviewLog::create($review)->prl_id;
            }else{
                $review = [
                    'preview_id' => $request['preview_id'],
                    'preview_rating' => $request['preview_rating'],
                    'preview_title' => $request['preview_title'],
                    'preview_description' => $request['preview_description'],
                    'preview_status' => $status,
                    'preview_updated_at' => Carbon::now()
                ];
                $prlId = $request['preview_id'];
                ProductReview::where('preview_id', $prlId)->where('preview_user_id',$this->user->user_id)
                ->update($review);
                
            } 
            

            $preview = ProductReview::select('preview_prod_id', 'preview_order_id')->where('preview_id', $request['preview_id'])->first();

            $userName = Str::title($this->user->user_fname . ' ' . $this->user->user_lname);
            $orderProduct = OrderProduct::getByOrderIdProductId($preview->preview_order_id, $preview->preview_prod_id);
            
            /*send email code*/
            $emailSlug = 'review_received_require_approval';
            if ($status == ProductReview::APPROVED) {
                $review['preview_approved_at'] = Carbon::now();
                $emailSlug = 'review_received_auto_approved';
            }
            $admins = Admin::getAdminsByModule(AdminRole::PRODUCT_REVIEWS);
            if (!empty($admins)) {
                $variants = json_decode($orderProduct->op_product_variants, true);
                if (!empty($variants["styles"])) {
                    $variants = " (" . implode(" | ", $variants["styles"]) . ") ";
                } else {
                    $variants = "";
                }
                foreach ($admins as $key => $admin) {
                    $data = EmailHelper::getEmailData($emailSlug);
                    $priority = $data['body']->etpl_priority;
                    $data['subject'] = str_replace('{buyerName}', $userName, $data['body']->etpl_subject);
                    $data['subject'] = str_replace('{productName}', $orderProduct->op_product_name, $data['subject']);
                    $data['body'] = str_replace('{name}', $admin['admin_name'], $data['body']->etpl_body);
                    $data['body'] = str_replace('{buyerName}', $userName, $data['body']);
                    $data['body'] = str_replace('{productName}', $orderProduct->op_product_name, $data['body']);
                    $data['body'] = str_replace('{productVariant}', $orderProduct->op_product_name . $variants, $data['body']);
                    $data['body'] = str_replace('{productImage}', productImageUrl($orderProduct->op_product_id, $orderProduct->op_pov_code), $data['body']);
                    $data['body'] = str_replace('{productUrl}', getRewriteUrl("products", $orderProduct->op_product_id), $data['body']);
                    $data['body'] = str_replace('{reviewUrl}', url("/admin#/product-reviews/" . $request['preview_id'] . "/detail"), $data['body']);
                    $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
                    $data['to'] = $admin['admin_email'];
                    dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);
                }
            }

            /* * trigger event for system notification*/
            $data = NotificationTemplate::getBySlug('review_edited');
            $message = str_replace('{userName}', $userName, $data->ntpl_body);
            $message = str_replace('{reviewTitle}', $request['preview_title'], $message);
            event(new SystemNotification([
                'type' => Notification::PRODUCT_REVIEW_EDITED,
                'record_id' => $request['preview_id'],
                'from_id' => $this->user->user_id,
                'message' => $message
            ]));
            $reviewImages = AttachedFile::getBulkFiles(AttachedFile::getConstantVal('productReviewImage'), $request['preview_id'], 0, true);
            $oldImageIds = [];
            if (!empty($reviewImages)) {
                if (!empty($request['deleteids'])) {
                    $deleteids = explode(',', $request['deleteids']);
                    if( $status == ProductReview::APPROVED){
                        foreach ($reviewImages as $reviewImage) {
                            if (!empty($deleteids) && in_array($reviewImage->afile_id, $deleteids)) {
                                AttachedFile::deleteAttachedFileById($reviewImage->afile_id);
                            }
                        }    
                    }
                }
                if( $status != ProductReview::APPROVED){
                    foreach ($reviewImages as $reviewImage) {
                        if (!empty($deleteids) && in_array($reviewImage->afile_id, $deleteids)) {
                            continue;
                        }
                        $oldImageIds[] = $reviewImage->afile_id;
                        $reviewLogImage = $reviewImage->replicate();
                        unset($reviewLogImage->prod_image);
                        $reviewLogImage->afile_type = $status == ProductReview::APPROVED ? AttachedFile::getConstantVal('productReviewImage') : AttachedFile::getConstantVal('productReviewLogImage');
                        $reviewLogImage->afile_record_id = $prlId;
                        $reviewLogImage->save();
                    }       
                }
            }
            if (!empty($request['imageIds'])) {
                $request['imageIds'] = explode(',', $request['imageIds']);
                $newImageIds = array_diff($request['imageIds'], $oldImageIds);
                foreach ($newImageIds as $fileId) {
                    if (!empty($fileId)) {
                        AttachedFile::where('afile_id', $fileId)
                        ->where('afile_type', $status == ProductReview::APPROVED ? AttachedFile::getConstantVal('productReviewImage') : AttachedFile::getConstantVal('productReviewLogImage'))
                        ->update(['afile_record_id' => $prlId]);
                    }
                }
            }
        } else {
            $review = [
                'preview_prod_id' => $request['preview_prod_id'],
                'preview_order_id' => $request['preview_order_id'],
                'preview_user_id' => $this->user->user_id,
                'preview_rating' => $request['preview_rating'],
                'preview_title' => $request['preview_title'],
                'preview_description' => $request['preview_description'],
                'preview_publish' => 1,
                'preview_status' => $status
            ];
            $emailSlug = 'review_received_require_approval';
            if ($status == 2) {
                $review['preview_approved_at'] = Carbon::now();
                $emailSlug = 'review_received_auto_approved';
            }
            $insertedId = ProductReview::create($review)->preview_id;

            $userName = Str::title($this->user->user_fname . ' ' . $this->user->user_lname);
            $orderProduct = OrderProduct::getByOrderIdProductId($request['preview_order_id'], $request['preview_prod_id']);
            /*send email code*/
            $admins = Admin::getAdminsByModule(AdminRole::PRODUCT_REVIEWS);
            if (!empty($admins)) {
                $variants = json_decode($orderProduct->op_product_variants, true);
                if (!empty($variants["styles"])) {
                    $variants = " (" . implode(" | ", $variants["styles"]) . ") ";
                } else {
                    $variants = "";
                }
                foreach ($admins as $key => $admin) {
                    $data = EmailHelper::getEmailData($emailSlug);
                    $priority = $data['body']->etpl_priority;
                    $data['subject'] = str_replace('{buyerName}', $userName, $data['body']->etpl_subject);
                    $data['subject'] = str_replace('{productName}', $orderProduct->op_product_name, $data['subject']);
                    $data['body'] = str_replace('{name}', $admin['admin_name'], $data['body']->etpl_body);
                    $data['body'] = str_replace('{buyerName}', $userName, $data['body']);
                    $data['body'] = str_replace('{productName}', $orderProduct->op_product_name, $data['body']);
                    $data['body'] = str_replace('{productVariant}', $orderProduct->op_product_name . $variants, $data['body']);
                    $data['body'] = str_replace('{productImage}', productImageUrl($orderProduct->op_product_id, $orderProduct->op_pov_code), $data['body']);
                    $data['body'] = str_replace('{productUrl}', getRewriteUrl("products", $orderProduct->op_product_id), $data['body']);
                    $data['body'] = str_replace('{reviewUrl}', url("/admin#/product-reviews/" . $insertedId . "/detail"), $data['body']);
                    $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
                    $data['to'] = $admin['admin_email'];
                    dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);
                }
            }

            /* trigger event for system notification*/
            $data = NotificationTemplate::getBySlug('review_posted');
            $message = str_replace('{userName}', $userName, $data->ntpl_body);
            $message = str_replace('{productName}', $orderProduct['op_product_name'], $message);
            $message = str_replace('{orderNumber}', $request['preview_order_id'], $message);
            event(new SystemNotification([
                'type' => Notification::PRODUCT_REVIEW_POSTED,
                'record_id' => $insertedId,
                'from_id' => $this->user->user_id,
                'message' => $message
            ]));
            if (!empty($request['imageIds'])) {
                $request['imageIds'] = explode(',', $request['imageIds']);
                foreach ($request['imageIds'] as $fileId) {
                    if (!empty($fileId)) {
                        AttachedFile::where('afile_id', $fileId)->update(['afile_record_id' => $insertedId]);
                    }
                }
            }
        }
    }

    public function index(Request $request)
    {
        return Inertia::render('Activity/Reviews', ['shopUrl' => route('productListing')]);
    }
    public function listing(Request $request)
    {
        $records = OrderProduct::userProducts($request->all(), $this->user->user_id);
        return jsonresponse(true, '', $records);
    }

    public function loadRecords(Request $request)
    {
        $records = OrderProduct::userProducts($request->all(), $this->user->user_id, $request->input('total'));
        return jsonresponse(true, '', $records);
    }
    public function getReview(Request $request, $opId, $prodId = 0, $prlId = 0)
    {
        $viewOnly = 0;
        if (empty($prlId)) {
            if (empty($opId) && !empty($prodId)) {
                $orders = OrderProduct::userOrderedProduct($this->user->user_id, $prodId);
                if ($orders->count() == 1) {
                    $order = $orders->first();
                    $orderId = $order->order_id;
                    $review = ProductReview::getReviewByProduct($prodId, $this->user->user_id, $orderId)->first();
                    if (!empty($review)) {
                        return jsonresponse(false, __('NOTI_ALREADY_SUBMITTED_REVIEW'), []);
                    } else {
                        $op = OrderProduct::select('op_product_id', 'op_order_id', 'op_product_name', 'op_pov_code', 'op_product_variants')
                        ->where('op_order_id', $orderId)->where('op_product_id', $prodId)->first();
                    }
                } elseif ($orders->count() > 1) {
                    $orderId = '';
                    $submittedReviews = 0;
                    foreach ($orders as $key => $order) {
                        $review = ProductReview::getReviewByProduct($prodId, $this->user->user_id, $order->order_id)->first();
                        if (empty($review) && empty($orderId)) {
                            $orderId = $order->order_id;
                        }
                        if (!empty($review)) {
                            $submittedReviews++;
                        }
                    }
                    if ($submittedReviews == count($orders)) {
                        return jsonresponse(false, __('NOTI_ALREADY_SUBMITTED_REVIEW'), []);
                    } else {
                        $op = OrderProduct::select('op_product_id', 'op_order_id', 'op_product_name', 'op_pov_code', 'op_product_variants')
                        ->where('op_order_id', $orderId)->where('op_product_id', $prodId)->first();
                    }
                }
            } else {
                $op = OrderProduct::orderProductById($opId);
            }
            $review = ProductReview::getReviewByProduct($op->op_product_id, $this->user->user_id, $op->op_order_id)->first();
            if (!empty($review->preview_status) && $review->preview_status == 1) {
                $viewOnly = 1;
            }
        } else {
            $review = ProductReviewLog::getReviewLogById($prlId)->first();
            $op = OrderProduct::orderProductById($opId);
            $viewOnly = 1;
        }
        $html = view('partials.addOrEditReview', compact('review', 'op', 'viewOnly'))->render();
        return jsonresponse(true, '', $html);
    }

    public function saveOrUpdateReview(ReviewRequest $request)
    {
        $this->saveOrUpdate($request);
        return jsonresponse(true, __('NOTI_REVIEW_SUBMITTED'));
    }

    public function uploadReviewImages(Request $request)
    {
        $previewId = !empty($request->input('preview_id')) ? $request->input('preview_id') : 0;
        $attachedFileType = Configuration::getValue('PRODUCT_REVIEW_APPROVE_STATUS') == 1 ? 'productReviewImage' : 'productReviewLogImage';
        if($previewId == 0){
            $attachedFileType = 'productReviewImage';
        }
        $uploadedImageIds = [];
        if ($request->hasFile('preview_image')) {
            $files = $request->file('preview_image');
            foreach ($files as $file) {
                $uploadedFileName = FileHelper::uploadFile($file);
                $uploadedImageIds[] = AttachedFile::saveFile(AttachedFile::getConstantVal($attachedFileType), $previewId, $uploadedFileName, $file->getClientOriginalName());
            }
        }
        return jsonresponse(true, '', $uploadedImageIds);
    }

    public function deleteReviewImages(Request $request, $imageId)
    {
        AttachedFile::deleteFileByAfileId($imageId, false);
        return jsonresponse(true, __('Image removed'));
    }
    public function uploadedImages(Request $request)
    {
        $uploadedImageIds = AttachedFile::getBulkFiles(AttachedFile::getConstantVal($request->input('type')), $request->input('id'), 0, true)->pluck('afile_id')->toArray();
        return jsonresponse(true, '', $uploadedImageIds);
    }

    public function showReviewProducts(Request $request)
    {
        $products = OrderProduct::userProducts($request->all(), $this->user->user_id);
        $configurations = getConfigValues([
            'ENABLE_EDIT_REVIEW',
            'DISPLAY_REVIEW_STATUS'
        ]);
        $html = view('themes.' . config('theme') . '.dashboard.reviews.listAjax', compact('products', 'configurations'))->render();
        return jsonresponse(true, __('NOTI_REVIEW_SUBMITTED'), $html);
    }
}
