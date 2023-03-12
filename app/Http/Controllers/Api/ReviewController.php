<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\YokartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use Str;
use Carbon\Carbon;
use App\Helpers\EmailHelper;
use App\Helpers\FileHelper;
use App\Models\OrderProduct;
use App\Models\ProductReview;
use App\Models\ProductReviewLog;
use App\Models\Notification;
use App\Models\AttachedFile;
use App\Models\Admin\Admin;
use App\Models\Admin\AdminRole;
use App\Models\NotificationTemplate;
use App\Jobs\SendNotification;
use App\Events\SystemNotification;
use App\Models\Configuration;

class ReviewController extends YokartController
{
    public function list(Request $request, $page)
    {
        $request['userId'] = Auth::guard('api')->user()->user_id;
        $request['page'] = $page;
        $request['type'] = !empty($request->type) ? $request->type : 'pending';
        $reviews = OrderProduct::userProductsForApp($request)->toArray();
        
        foreach ($reviews['data'] as $key => $review) {
            $tempJson = json_decode($review['op_product_variants'], true);
            if (!empty($tempJson['styles']) && count($tempJson['styles']) > 0) {
                $reviews['data'][$key]['op_product_variants'] = implode(" | ", $tempJson['styles']);
            } else {
                $reviews['data'][$key]['op_product_variants'] = "";
            }
            unset($reviews['data'][$key]['op_pov_code']);
            unset($reviews['data'][$key]['pov_code']);
            $reviewRating = "0";
            $reviewStatus = "0";
            if (!empty($reviews['data'][$key]['product_review'])) {
                if (empty($reviews['data'][$key]['product_review']["prl_preview_title"])) {
                    $reviews['data'][$key]['product_review']["prl_preview_title"] = "";
                }
                if (empty($reviews['data'][$key]['product_review']["prl_preview_description"])) {
                    $reviews['data'][$key]['product_review']["prl_preview_description"] = "";
                }
                if (empty($reviews['data'][$key]['product_review']["prl_preview_rating"])) {
                    $reviews['data'][$key]['product_review']["prl_preview_rating"] = "";
                }
                $reviews['data'][$key]['product_review']["preview_status"] = (string) $reviews['data'][$key]['product_review']["preview_status"];
                $reviewRating = $reviews['data'][$key]['product_review']["preview_rating"];
                $reviewStatus = $reviews['data'][$key]['product_review']["preview_status"];

                if (empty($reviews['data'][$key]['product_review']["prl_preview_id"])) {
                    $reviews['data'][$key]['product_review']["prl_preview_id"] = null;
                } else {
                    $reviewStatus = ProductReview::PENDING;
                }

                $productReview = $reviews['data'][$key]['product_review'];
                if (!empty($productReview["attached_files"])) {
                    foreach ($productReview["attached_files"] as $akey => $file) {
                        $reviews['data'][$key]['product_review']["attached_files"][$akey]["afile_image"] = url('/') . '/yokart/image/' . $file["afile_id"] . '?t=' . strtotime($file["afile_updated_at"]);
                        unset($reviews['data'][$key]['product_review']["attached_files"][$akey]["afile_updated_at"]);
                        unset($reviews['data'][$key]['product_review']["attached_files"][$akey]["afile_record_id"]);
                    }
                }
            }
            $reviews['data'][$key]['order_date_added'] = getConvertedDateTime($reviews['data'][$key]['order_date_added']);
            $reviews['data'][$key]["preview_status"] = (string) $reviewStatus;
            $reviews['data'][$key]["preview_editable"] = Configuration::getValue('ENABLE_EDIT_REVIEW') == 1 ? "1" : "0";
            $reviews['data'][$key]["preview_rating"] = (string) $reviewRating;
        }
        $response = [];
        $response['reviews'] = $reviews['data'];
        $response['total'] = $reviews['total'];
        $response['pages'] = ceil($reviews['total'] / $reviews['per_page']);
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }

    public function getReview(Request $request, $opId)
    {
        $response = [];
        $op = OrderProduct::orderProductById($opId);
        $reviewStatus = "0";
        if (empty($op)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_INVALID_REQUEST"));
        }
        $review = ProductReview::getReviewByProduct($op->op_product_id, Auth::guard('api')->user()->user_id, $op->op_order_id)->first();
        if (!empty($review)) {
            $review = $review->toArray();
            if (!empty($review["attached_files"])) {
                foreach ($review["attached_files"] as $key => $file) {
                    $review["attached_files"][$key]["afile_image"] = url('/') . '/yokart/image/' . $file["afile_id"] . '/t=' . strtotime($file["afile_updated_at"]);
                    unset($review["attached_files"][$key]["afile_updated_at"]);
                    // unset($review["attached_files"][$key]["afile_id"]);
                    unset($review["attached_files"][$key]["afile_record_id"]);
                }
            }
            $variants = "";
            if (!empty($review['op_product_variants'])) {
                $tvariants = json_decode($review['op_product_variants'], true);
                if (!empty($tvariants["styles"])) {
                    $variants = " (" . implode(" | ", $tvariants["styles"]) . ") ";
                }
            }
            $review['op_product_variants'] = $variants;
            $reviewStatus = $review["preview_status"];

            if (!empty($review['product_review_log']) && !empty($review['product_review_log']["prl_preview_id"])) {
                $reviewStatus = ProductReview::PENDING;
            }
        }

        

        $review["preview_status"] = $reviewStatus;
        $review["preview_editable"] = Configuration::getValue('ENABLE_EDIT_REVIEW') == 1 ? "1" : "0";
        $response["review"] = $review;
        
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }

    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'preview_title' => 'string|max:255',
            'preview_rating' => 'required|numeric|digits_between:1,5'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        if (empty($request["preview_title"])) {
            $request["preview_title"] = "";
        }
        if (empty($request["preview_description"])) {
            $request["preview_description"] = "";
        }

        if ($this->saveOrUpdate($request)) {
            return apiresponse(config('constants.SUCCESS'), appLang("NOTI_REVIEW_SUBMITTED"));
        } else {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_REVIEW_NOT_SUBMITTED"));
        }
    }

    public function uploadReviewImages(Request $request)
    {
        $previewId = !empty($request->input('preview_id')) ? $request->input('preview_id') : 0;
        $attachedFileType = $previewId != 0 ? 'productReviewLogImage' : 'productReviewImage';
        $attachedFiles = [];
        /*if ($request->hasFile('preview_image')) {
            $files = $request->file('preview_image');
            foreach ($files as $akey => $file) {
                $uploadedFileName = FileHelper::uploadFile($file);
                $afileId = AttachedFile::saveFile(AttachedFile::getConstantVal($attachedFileType), $previewId, $uploadedFileName, $file->getClientOriginalName());
                $attachedFiles[$akey]["afile_id"] = $afileId;
                $attachedFiles[$akey]["afile_image"] = url('/') . '/yokart/image/' . $afileId . '/200-200/' . time();
            }
        }*/
        if ($request->hasFile('preview_image')) {
            $file = $request->file('preview_image');
            $akey = 0;
            $uploadedFileName = FileHelper::uploadFile($file);
            $afileId = AttachedFile::saveFile(AttachedFile::getConstantVal($attachedFileType), $previewId, $uploadedFileName, $file->getClientOriginalName());
            $attachedFiles[$akey]["afile_id"] = $afileId;
            $attachedFiles[$akey]["afile_image"] = url('/') . '/yokart/image/' . $afileId . '?t=' . time();
        }
        $response = [
            'attached_files' => $attachedFiles
        ];
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_REVIEW_IMAGE_UPLOADED"), $response);
    }

    public function deleteReviewImages(Request $request, $image_id)
    {
        AttachedFile::deleteFileByAfileId($image_id, false);
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_REVIEW_IMAGE_DELETED"));
    }

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
                ProductReview::where('preview_id', $prlId)->where('preview_user_id',Auth::guard('api')->user()->user_id)
                ->update($review);
                
            } 

            
            $preview = ProductReview::select('preview_prod_id', 'preview_order_id')->where('preview_id', $request['preview_id'])->first();
            $userName = Str::title(Auth::guard('api')->user()->user_fname . ' ' . Auth::guard('api')->user()->user_lname);
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
                'from_id' => Auth::guard('api')->user()->user_id,
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
            if (!empty($request['image_ids'])) {
                $request['image_ids'] = explode(',', $request['image_ids']);
                $newImageIds = array_diff($request['image_ids'], $oldImageIds);
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
                'preview_user_id' => Auth::guard('api')->user()->user_id,
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

            $userName = Str::title(Auth::guard('api')->user()->user_fname . ' ' . Auth::guard('api')->user()->user_lname);
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
                'from_id' => Auth::guard('api')->user()->user_id,
                'message' => $message
            ]));
            if (!empty($request['image_ids'])) {
                $request['image_ids'] = explode(',', $request['image_ids']);
                foreach ($request['image_ids'] as $fileId) {
                    if (!empty($fileId)) {
                        AttachedFile::where('afile_id', $fileId)->update(['afile_record_id' => $insertedId]);
                    }
                }
            }
        }
        return true;
    }
}
