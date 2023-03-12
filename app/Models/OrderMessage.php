<?php

namespace App\Models;

use App\Models\YokartModel;
use Carbon\Carbon;

class OrderMessage extends YokartModel
{
    public $timestamps = false;
    public const MSG_ORDER_TYPE = 1;
    public const MSG_ORDER_PRODUCT_TYPE = 2;
    public const MSG_ORDER_TIMELLINE_TYPE = 3;
    public const MSG_ORDER_ACTION_TYPE = 4;
    public const MSG_ORDER_RETURN_TIMELLINE_TYPE = 5;

    public const MSG_FROM_ADMIN = 1;
    public const MSG_FROM_USER = 2;
    protected $primaryKey = 'omsg_id';

    protected $fillable = [
        'omsg_type', 'omsg_record_id', 'omsg_subrecord_id', 'omsg_from_type', 'omsg_from_id', 'omsg_comment', 'omsg_created_at'
    ];
    public static function getRecords($recordId, $msgType, $request = '')
    {
        $per_page = config('app.pagination');
        if (isset($request) && !empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $messages = OrderMessage::with('comments:omsg_comment,omsg_subrecord_id')
            ->select('omsg_id', 'omsg_comment', 'omsg_type', 'omsg_created_at', 'omsg_from_type');
        if (is_array($msgType)) {
            $messages->whereIn('omsg_type', $msgType);
        } else {
            $messages->where('omsg_type', $msgType);
        }
        return $messages->where('omsg_subrecord_id', 0)->where('omsg_record_id', $recordId)->latest('omsg_id')->paginate((int) $per_page);
    }
    public static function getOrderHistory($recordId, $requestIds, $orderMsgType, $reqType)
    {
        $per_page = config('app.pagination');
        $requests = OrderMessage::with('comments:omsg_comment,omsg_subrecord_id')
            ->select('omsg_id', 'omsg_comment', 'omsg_type', 'omsg_created_at', 'omsg_from_type')->whereIn('omsg_record_id', $requestIds)->where('omsg_type', $reqType)->where('omsg_subrecord_id', 0);
        $messages = OrderMessage::with('comments:omsg_comment,omsg_subrecord_id')
            ->select('omsg_id', 'omsg_comment', 'omsg_type', 'omsg_created_at', 'omsg_from_type')->where('omsg_record_id', $recordId)->whereIn('omsg_type', $orderMsgType)->where('omsg_subrecord_id', 0)
            ->union($requests)->groupBy('omsg_id')->latest('omsg_id')->paginate((int) $per_page);

        return $messages;
    }


    public function comments()
    {
        return $this->belongsTo('App\Models\OrderMessage', 'omsg_id', 'omsg_subrecord_id');
    }

    public static function createComment($orderId, $comment, $type, $subrecordId = 0, $adminId = 0)
    {
        $orderMessage = OrderMessage::create([
            'omsg_type' => $type,
            'omsg_record_id' => $orderId,
            'omsg_subrecord_id' => $subrecordId,
            'omsg_from_type' => OrderMessage::MSG_FROM_ADMIN,
            'omsg_from_id' => $adminId,
            'omsg_comment' => $comment,
            'omsg_created_at' => Carbon::now(),
        ])->omsg_id;
        return $orderMessage;
    }
    public static function getRecordsForApp($recordId, $msgType, $page, $paginate = true)
    {
        $messages = OrderMessage::with('comments:omsg_comment,omsg_subrecord_id')
            ->select('omsg_id', 'omsg_comment', 'omsg_type', 'omsg_created_at', 'omsg_from_type');
        if (is_array($msgType)) {
            $messages->whereIn('omsg_type', $msgType);
        } else {
            $messages->where('omsg_type', $msgType);
        }
        $messages->where('omsg_subrecord_id', 0)
        ->where('omsg_record_id', $recordId)
        ->latest('omsg_id');
        if($paginate){
            return $messages->paginate((int) config('app.app_pagination'), ['*'], 'page', $page);
        }        
        return $messages->limit(1)->get();
    }

    public static function getRecordForApp($omsgId)
    {
        return OrderMessage::with('comments:omsg_comment,omsg_subrecord_id')
            ->select('omsg_id', 'omsg_comment', 'omsg_type', 'omsg_created_at', 'omsg_from_type')->where('omsg_id', $omsgId)->first();
    }
}
