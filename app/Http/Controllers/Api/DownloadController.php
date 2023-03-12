<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\YokartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\OrderProduct;
use App\Models\Order;
use App\Models\DigitalFileRecord;
use App\Models\OrderProductAdditionInfo;
use App\Models\UserTempTokenRequest;
use Carbon\Carbon;

class DownloadController extends YokartController
{
    public function list(Request $request, $page)
    {
        $response = [];

        $orderId = $request->input('order_id');
        $productId = $request->input('product_id');
        $opId = '';
        if (!empty($orderId) && !empty($productId)) {
            $record = OrderProduct::where(['op_order_id' => $orderId, 'op_product_id' => $productId])->first();
            if ($record) {
                $opId = $record->op_id;
            }
        }
        $fileTypes = DigitalFileRecord::FILE_TYPES;

        $request['user_id'] = Auth::guard('api')->user()->user_id;
        $products = OrderProduct::getDigitalOrderForApp($request, $page, $opId);

        foreach ($products as $key => $product) {
            $imageInfo = $this->getAttachedInfo($product, $fileTypes);
            $product->total_size = $imageInfo['total_size'];
            $product->attachments_count = count($imageInfo['total_attachments']);
            $product->expired = \Carbon\Carbon::parse($product->op_expired_on)->isPast();
            $product->attachment_type =  $imageInfo['attachmentType'];
            $product->op_expired_on = getConvertedDateTime($product->op_expired_on);
            $digitalArr = [];
            $digitalDownloadFiles = $product->digitalData->toArray();
            $imageCount = count(array_filter($digitalDownloadFiles, function($a) {return $a["dfr_file_type"] == 1;}));
            $videoCount = count(array_filter($digitalDownloadFiles, function($a) {return $a["dfr_file_type"] == 2;}));
            $audioCount = count(array_filter($digitalDownloadFiles, function($a) {return $a["dfr_file_type"] == 3;}));
            $fileCount = count(array_filter($digitalDownloadFiles, function($a) {return $a["dfr_file_type"] == 4;}));
            if($imageCount > 0){
                $digitalArr[] = [
                    'type' => 1,
                    'count' => $imageCount
                ];
            }
            if($videoCount > 0){
                $digitalArr[] = [
                    'type' => 2,
                    'count' => $videoCount
                ];
            }
            if($audioCount > 0){
                $digitalArr[] = [
                    'type' => 3,
                    'count' => $audioCount
                ];
            }
            if($fileCount > 0){
                $digitalArr[] = [
                    'type' => 4,
                    'count' => $fileCount
                ];
            }
            unset($products[$key]->digitalData);
            $products[$key]->digital_data = $digitalArr;
        }
        
        $products = $products->toArray(); 
        $response['total'] = $products['total'];
        $response['pages'] = ceil($products['total'] / $products['per_page']);
        $response['products'] = $products['data'];
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }

    private function getAttachedInfo($product, $fileTypes)
    {
        $size = [];
        $type = [];
        $attachments = [];
        $x = 0;
        foreach ($product->digitalData->where('dfr_subrecord_id', $product->op_product_id) as $file) {
            if ($file->dfr_url == '' && !empty($file->afile_physical_path)) {
                $size[$x] = getFileSize($file->afile_physical_path);
                $type[$x] = $file->dfr_file_type;
            }
            $attachments[$x] = $file->dfr_file_type;
            $x++;
        }
        if (count($size) > 0) {
            $maxTypeImage = strtolower($fileTypes[$type[max(array_keys($size))]]);
            unset($attachments[max(array_keys($size))]);
        } else {
            $maxTypeImage = 'link';
        }
        return ['total_size' => array_sum($size), 'total_attachments' => array_values($attachments),  'attachmentType' => $maxTypeImage];
    }

    public function downloadFiles(Request $request)
    {
        $orderId = $request->input('order_id');
        $productId = $request->input('product_id');
        $condistions = ['op_order_id' => $orderId, 'op_product_id' => $productId];
        if (!empty($orderId) && !empty($productId)) {
            $record = OrderProduct::where($condistions)->first();
            if ($record) {
                $opId = $record->op_id;
            }
        }
        $totalDownloads = OrderProductAdditionInfo::select('opainfo_max_download_limit', 'opainfo_downloads')->where('opainfo_op_id', $opId)->first();
        if ($totalDownloads->opainfo_max_download_limit == $totalDownloads->opainfo_downloads) {
            return apiresponse(config('constants.ERROR'), appLang('NOTI_DOWNLOAD_LIMIT_REACHED'));
        }

        OrderProductAdditionInfo::where('opainfo_op_id', $opId)->increment('opainfo_downloads');
        $userId = Auth::guard('api')->user()->user_id;
        $fileTypes = DigitalFileRecord::FILE_TYPES;
        $product = OrderProduct::digitalOrderByUserId($userId, $condistions);
        $imageInfo = $this->getAttachedInfo($product, $fileTypes);
        $product->totalSize = $imageInfo['total_size'];
        $product->attachments = $imageInfo['total_attachments'];
        $product->expired = \Carbon\Carbon::parse($product->op_expired_on)->isPast();
        $product->maxTypeImage =  $imageInfo['attachmentType'];

        $tempToken = randomString(25);
        
        UserTempTokenRequest::create([
            'uttr_type' => UserTempTokenRequest::DOWNLOAD_REQUEST,
            'uttr_user_id' => $userId,
            'uttr_code' => $tempToken,
            'uttr_expiry' => Carbon::now()->addMinutes(config('app.expiredToken'))
        ]);
        
        $url = route('downloadItemsByApp', [$orderId, $productId]) . "?t=".$tempToken;
        return apiresponse(config('constants.SUCCESS'), '', ['product' => $product, 'download_url' => $url]);
    }
}
