<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\YokartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Thread;
use App\Models\ThreadMessage;
use App\Models\Product;
use Auth;
use DB;

class MessageController extends YokartController
{
    public function list(Request $request, $page)
    {
        $messages = Thread::getMessageListingForApp(Auth::guard('api')->user()->user_id, $page, $request->input('search'));
        foreach ($messages as $key => $message) {
            $fields =  'prod_id,pov_display_title, ' . Product::getPrice();
            $product = Product::productById($message->thread_product_id, $fields, [], $message->thread_product_variant, true);
            if($product){
                $messages[$key]->price = $product->price;
                $messages[$key]->finalprice = $product->finalprice;
                $messages[$key]->discount = $product->discount;
                $messages[$key]->variant_display_title = str_replace('_', '|', $product->pov_display_title);
                $messages[$key]->thread_created_at = getConvertedDateTime($message->thread_created_at);
                if (isset($message->getLastMessage->message_created_at)) {
                    $messages[$key]->getLastMessage->message_created_at = getConvertedDateTime($message->getLastMessage->message_created_at);
                }
            }
            
        }
        $messages = $messages->toArray();
        $response = [];
        $response['messages'] = $messages['data'];
        $response['total'] = $messages['total'];
        $response['pages'] = ceil($messages['total'] / $messages['per_page']);
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }

    public function getMessageDetail(Request $request, $thread_id)
    {
        $response = [];

        $messageThread = Thread::getMessageDetailsForApp($thread_id, Auth::guard('api')->user()->user_id);
         
        $fields =  'prod_id,pov_display_title,prod_min_order_qty,pov_code, ' . Product::getPrice();
        $product = Product::productById($messageThread->thread_product_id, $fields, [], $messageThread->thread_product_variant, true);
        
        $available = false;
        $favorite = false;
        if (!empty($product)) {
            $available = true;
            $favorite = getProductfavorite($product);
            $messageThread->price = $product->price;
            $messageThread->povCode = $product->pov_code;
            $messageThread->finalprice = $product->finalprice;
            $messageThread->discount = $product->discount;
            $messageThread->prod_min_order_qty = $product->prod_min_order_qty;
            $messageThread->variant_display_title = str_replace('_', '|', $product->pov_display_title);
        }
     
        $response['available'] = $available;
        $response['favorite'] = $favorite;
        Thread::readAdminMessages($thread_id);
        
       

        foreach ($messageThread->threadMessages as $key => $message) {
            $messageThread->threadMessages[$key]->message_created_at = getConvertedDateTime($message->message_created_at);
        }
        
        $response['messages'] = $messageThread;
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }

    public function saveMessage(Request $request)
    {
        $rules = [
            'thread_id' => 'required'
        ];
        if ($request->hasFile('image')) {
            $rules['image'] = 'required|mimes:jpeg,jpg,png|max:2000';
        } else {
            $rules['message'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        if ($request->hasFile('image')) {
            $request["txtMessage"] = $request->file('image')->getClientOriginalName();
        } else {
            $request["txtMessage"] = $request["message"];
        }
        $request["file"] = $request["image"];
        $response = [];
        $message = ThreadMessage::saveMessage($request->all(), Auth::guard('api')->user()->user_id);
        
        $response["message"] = ThreadMessage::select('message_id', 'message_thread_id', 'message_text', 'message_created_at', 'message_from_admin')
            ->where('message_id', $message->message_id)
            ->with(array('uploadImages' => function ($query) {
                $query->select('afile_record_id', DB::raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id,'?t=', UNIX_TIMESTAMP(afile_updated_at)) as message_image"));
            }))
            ->first();
        $response["message"]["message_created_at"] = getConvertedDateTime($response["message"]["message_created_at"]);
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }
}
