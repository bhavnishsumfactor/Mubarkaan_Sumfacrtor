<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderInvoice;
use App\Models\Admin\AdminRole;
use App\Models\OrderMessage;
use App\Models\Package;
use App\Models\SmsTemplate;
use App\Helpers\FileHelper;
use App\Helpers\EmailHelper;
use App\Jobs\SendNotification;
use App\Models\Transaction;
use App\Models\OrderTag;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use App\Models\TaxGroupType;
use App\Models\UserAddress;
use App\Models\AttachedFile;
use App\Models\Reason;
use App\Models\OrderReturnRequest;
use App\Models\UserRewardPoint;
use App\Models\OrderProductAdditionInfo;
use App\Models\ProductOption;
use App\Models\OrderProductCharge;
use App\Models\ProductOptionVarient;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\OrderAdditionInfo;
use App\Models\OrderReturnAmount;
use App\Models\StoreAddress;
use App\Models\EmailTemplate;
use App\Models\UrlShortener;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use App\Helpers\ShippingHelper;
use App\Helpers\TaxHelper;
use App\Helpers\PaymentGatewayHelper;
use Illuminate\Support\Facades\Validator;
use App\Models\UserRewardPointBreakup;
use App\Models\DigitalFileRecord;
use App\Models\Configuration;
use App\Models\Theme;
use App\Models\OrderProductTaxLog;
use App\Exports\OrderExport;
use PDF;
use Excel;
use Illuminate\Support\Facades\Crypt;
use App\Models\NotificationTemplate;
use App\Events\SystemNotification;
use App\Helpers\TrackingApiHelper;
use App\Models\Notification;
use Str;
use ZipArchive;
use File;

class OrderController extends AdminYokartController
{
    private $exceptFunction = ['orderRequests'];

    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::ORDERS)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        })->except($this->exceptFunction);
    }

    public function getListing(Request $request)
    {
        $data = [];
        $data['rows'] = $data['total'] = $data['statuses'] = $data['blocks'] = [];
        $orderStatuses = Order::SHIPPING_STATUS;
        if ($request['active-tab'] == "pickup") {
            $orderStatuses = Order::PICKUP_STATUS;
        }
        foreach ($orderStatuses as $statusId => $statusName) {
            $temp = Order::getRecords($request, $statusId)->toArray();
            $data['blocks'] = array_merge($data['blocks'], $temp['data']);
            $data['statuses'][$statusId] = $statusName;
            $data['total'][$statusName] = $temp['total'];
            $data['rows'][$statusName] = count($temp['data']);
        }
        $data['orderStatus'] = Order::ORDER_STATUS;
        $data['orderDigitalStatus'] = Order::DIGITAL_STATUS;
        $configValues = getConfigValues(['PRODUCT_TYPES', 'SHIPPING_MESSAGES_ENABLE']);
        $data['productType'] = $configValues['PRODUCT_TYPES'];
        $data['shippingMesages'] = $configValues['SHIPPING_MESSAGES_ENABLE'];
        $trackShippingPackage = Package::getActivePackage('trackShipping');
        if (isset($trackShippingPackage->pkg_slug) && !empty($trackShippingPackage->pkg_slug)) {
            $trackingApi = new TrackingApiHelper('AfterShip');
            $shippingCouriers = $trackingApi->trackingCouriers();
        } else {
            $shippingCouriers = [];
        }
        $data['shippingCouriers'] = $shippingCouriers;
        $data['showEmpty'] = 0;
        if (Order::limit(1)->count() == 0) {
            $data['showEmpty'] = 1;
        }

        return jsonresponse(true, null, $data);
    }

    public function getRetrunListing(Request $request)
    {
        $data = [];
        $data['rows'] = $data['total'] = $data['statuses'] = $data['blocks'] = [];


        if ($request->input('type') == OrderReturnRequest::CANCELLATION) {
            $orderStatuses =  Arr::except(
                OrderReturnRequest::REQUEST_STATUS,
                [
                    OrderReturnRequest::RETURN_REQUEST_STATUS_RECEIVED,
                ]
            );
        } else {
            $orderStatuses = OrderReturnRequest::REQUEST_STATUS;
        }
        foreach ($orderStatuses as $statusId => $statusName) {
            $temp = Order::getReturnRecords($request, $request->input('type'), $statusId)->toArray();
            $data['blocks'] = array_merge($data['blocks'], $temp['data']);
            $data['statuses'][$statusId] = $statusName;
            $data['total'][$statusName] = $temp['total'];
            $data['rows'][$statusName] = count($temp['data']);
        }
        $data['orderStatus'] = Order::ORDER_STATUS;
        $data['orderDigitalStatus'] = [];
        $data['productType'] = '';

        $showEmpty = 0;
        if (OrderReturnRequest::limit(1)->count() == 0) {
            $showEmpty = 1;
        }
        $data['showEmpty'] = $showEmpty;

        return jsonresponse(true, null, $data);
    }

    public function comments(Request $request)
    {
        if (!canWrite(AdminRole::ORDERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $type = OrderMessage::MSG_ORDER_TIMELLINE_TYPE;
        $orderId = $request->input('record-id');
        $order = '';
        if (!empty($request->input('type'))) {
            $type = OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE;
            $order = OrderProduct::select('order_user_id')
            ->join('orders', 'op_order_id', 'order_id')->where('op_id', $orderId)->first();
        } else {
            $order = Order::select('order_user_id')->where('order_id', $orderId)->first();
        }
  
        $comment = $request->input('comment');
        $this->saveComments($orderId, $comment, $type);
        $user = User::select('user_fname', 'user_lname', 'user_email', 'user_phone_country_id', 'user_phone')->where('user_id', $order->order_user_id)->first();

        $notificationData = [];
        $sendSms = false;
        /*send email code*/
        $data = EmailHelper::getEmailData('comment_by_admin_on_order');
        $priority = $data['body']->etpl_priority;
        $data['subject'] = $this->replaceCommentTags($data['body']->etpl_subject, $comment, $user, $orderId);
        $data['body'] = $this->replaceCommentTags($data['body']->etpl_body, $comment, $user, $orderId);
        $data['to'] = $user->user_email;
        $notificationData['email'] = $data;
        
        $country = Country::select('country_phonecode')->where('country_id', $user->user_phone_country_id)->first();
        if (!empty($country->country_phonecode) && !empty($user->user_phone)) {
            $data = SmsTemplate::getSMSTemplate('comment_by_admin_on_order');
            $priority = $data['body']->stpl_priority;
            $data['body'] = str_replace('{name}', $user->user_fname . ' ' . $user->user_lname, $data['body']->stpl_body);
            $data['body'] = str_replace('{orderNumber}', $orderId, $data['body']);
            $notificationData['sms'] = [
                'message' => $data['body'],
                'recipients' => array('+' . $country->country_phonecode . $user->user_phone)
            ];
            $sendSms = true;
        }
        sendPushNotification($order->order_user_id, 'new_comment_posted_by_admin_on_order', ['order_id' => $orderId]);
        dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);

        return jsonresponse(true, __('NOTI_ORDER_COMMENT_SENT'));
    }

    private function replaceCommentTags($content, $comment, $user, $orderNumber)
    {
        $content = str_replace('{buyerName}', $user->user_fname . ' ' . $user->user_lname, $content);
        $content = str_replace('{adminName}', $this->admin->admin_name, $content);
        $content = str_replace('{commentText}', $comment, $content);
        $content = str_replace('{link}', url('') . '/user/orders', $content);
        $content = str_replace('{orderNumber}', $orderNumber, $content);
        $content = str_replace('{websiteName}', (getConfigValueByName('BUSINESS_NAME') ?? ''), $content);
        return $content;
    }

    protected function saveComments($orderId, $comment, $type, $subrecordId = 0)
    {
        if (!canWrite(AdminRole::ORDERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        return OrderMessage::create([
            'omsg_type' => $type,
            'omsg_record_id' => $orderId,
            'omsg_subrecord_id' => $subrecordId,
            'omsg_from_type' => OrderMessage::MSG_FROM_ADMIN,
            'omsg_from_id' => $this->admin->admin_id,
            'omsg_comment' => $comment,
            'omsg_created_at' => Carbon::now(),
        ])->omsg_id;
    }
    public function loadMore(Request $request)
    {
        if (!empty($request->input('type')) && $request->input('type') != "undefined") {
            $statusId = OrderReturnRequest::flipedStatus()[$request->input('status')];
            $data = Order::getReturnRecords($request, $request->input('type'), $statusId, $request->input('rows'));
            return jsonresponse(true, null, $data);
        }
        $status = Order::flipedStatus()[$request->input('status')];

        $data = Order::getRecords($request, $status, $request->input('rows'));
        return jsonresponse(true, null, $data);
    }

    public function updateReturnStatus(Request $request, $requestId)
    {
        if (!canWrite(AdminRole::ORDERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $orderRequest = OrderReturnRequest::getRecordById($requestId);
       
        $orderData = Order::getRecordById($orderRequest->orrequest_order_id);
        if (OrderReturnRequest::flipedStatus()[$request->input('status')] == $orderRequest->orrequest_status) {
            return jsonresponse(false, __('NOTI_ORDER_STATUS_ALREADY') . ' ' . $request->input('status'));
        }
        OrderReturnRequest::where('orrequest_id', $requestId)->update([
             'orrequest_status' => OrderReturnRequest::flipedStatus()[$request->input('status')]
         ]);
        $comment = $request->input('status');
        $message = $request->input('message');

        $requestType = OrderReturnRequest::TYPE[$orderRequest->orrequest_type];
        $transId = '';
        if ($request->input('status') == 'approved' && $orderRequest->orrequest_order_status == 1 && $orderRequest->oramount_payment_status == 0) {
            $totalRefund = $this->calculateRefund($orderRequest);

            $order = Order::getRecordById($orderRequest->orrequest_order_id);
       
            $gatewayType = $order->paymentslug;
            $transactionId = "";
            if ($order->transaction) {
                $transactionId = $order->transaction->txn_gateway_transaction_id;
                if (!in_array($order->transaction->txn_gateway_type, ['cash', 'card', 'rewards'])) {
                    $gatewayType = $order->transaction->txn_gateway_type;
                }
            }
        
            if ($gatewayType != 'cod' && $gatewayType != 'cash' && $gatewayType != 'rewards') {
                $orderStatus = $this->paymentRefund($gatewayType, $transactionId, $totalRefund, $order->order_currency_code);
                if ($orderStatus['status'] != 1) {
                    return jsonresponse(false, $orderStatus['message']);
                }

                $transId = $orderStatus['data']['id'];
                $amount =  $totalRefund;
                $response = $orderStatus;
            } elseif ($gatewayType == 'rewards') {
                $orderStatus['order_payment_status'] = Order::PAYMENT_PAID;
                $amount =  $request->input("amount");
                $gatewayType = '';
                $transId = rand(0, 5);
                $response = [
                    'status' => true,
                    'data' => [
                        'amount' => $amount,
                        'id' => $transId
                    ]
                ];
            } else {
                $orderStatus['order_payment_status'] = Order::PAYMENT_PAID;
                $transferDetails = json_decode($request->input("transfer-detail"), true);
                $amount =  $transferDetails['amount'];
                $gatewayType = $transferDetails['payment_method'];
                $transId = $transferDetails['transaction_id'];
                $response = [
                    'status' => true,
                    'data' => [
                        'amount' => $transferDetails['amount'],
                        'id' => $transferDetails['transaction_id']
                    ]
                ];
            }
            $this->orderTransaction($orderRequest->orrequest_order_id, $amount, __('LBL_PAYMENT_REFUNDED') .'#' . $orderRequest->orrequest_order_id, $order->user_id, $gatewayType, $transId, json_encode($response), Transaction::CREDIT_TYPE, $requestId);
            OrderReturnAmount::where('oramount_id', $orderRequest->oramount_id)->update(['oramount_payment_status' => 1]);
        }

        if ($request->input('status') == 'approved') {
            $this->updateReturnInventory($orderRequest->product, $orderRequest->orrequest_qty, $requestId, $orderRequest->orrequest_user_id);
            $commentId =  $this->saveComments($requestId, $requestType . ' ' . __('LBL_REQUEST_APPROVED'), OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE);

            $orderCommentId = $this->saveComments($orderRequest->orrequest_order_id, __('LBL_REFUND_REQUEST_PROCESSED_FOR_REQUEST_ID') . ' #' . $requestId, OrderMessage::MSG_ORDER_TIMELLINE_TYPE);
            $reqComment = "";
            $orderComment = "";
            if ($message) {
                $reqComment = '<p> <span class="text-bold">' . __('LBL_REASON') . ':</span> ' . $message . '</p>';
                $orderComment = '<p> <span class="text-bold">' . __('LBL_COMMENT') . ':</span> ' . $message . '</p>';
            }
            $transMessage = '<p><span class="text-bold">  '. __('LBL_TRANSACTION_DETAILS') .': </span>' . $transId . '</p>';
            $this->saveComments($requestId, $reqComment . '' . $transMessage, OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE, $commentId);
            $this->saveComments($orderRequest->orrequest_order_id, $orderComment . '' . $transMessage, OrderMessage::MSG_ORDER_TIMELLINE_TYPE, $orderCommentId);

            /** Approve sms */
            $billingAddress = $orderData->address->where('oaddr_type', OrderAddress::BILLING_ADDRESS_TYPE)->first();
            if (!empty($billingAddress->oaddr_phone) && !empty($billingAddress->oaddr_phone_country_id)) {
                $country = Country::select('country_phonecode')->where('country_id', $billingAddress->oaddr_phone_country_id)->first();
                $smsData = SmsTemplate::getSMSTemplate('return_approved');
                $priority = $smsData['body']->stpl_priority;
                $smsData['body'] = str_replace('{requestId}', $orderRequest->orrequest_order_id.'-'.$orderRequest->orrequest_id, $smsData['body']->stpl_body);
                $smsData['body'] = str_replace('{name}', $billingAddress->oaddr_name, $smsData['body']);
                $notificationData['sms'] = [
                    'message' => $smsData['body'],
                    'recipients' => array('+' . $country->country_phonecode . $billingAddress->oaddr_phone)
                ];
                sendPushNotification($orderData->user_id, 'return_approved', ['request_id' => $orderRequest->orrequest_id]);
                dispatch(new SendNotification($notificationData, false, false, true))->onQueue($priority);
            }
        }
        if ($request->input('status') == 'declined') {

            $totalDeclined = OrderReturnRequest::where([
                'orrequest_status' => OrderReturnRequest::RETURN_REQUEST_STATUS_DECLINED,
                'orrequest_type' => OrderReturnRequest::RETURN,
                'orrequest_order_id' => $orderRequest->orrequest_order_id
            ])->count();


            $totalOrderedProducts = OrderProduct::where([
                'op_order_id' => $orderRequest->orrequest_order_id
            ])->count();

            if ($totalOrderedProducts  === $totalDeclined) {
                $orderStatus = Order::ORDER_STATUS_OPEN;
                if ($orderRequest->orrequest_type = OrderReturnRequest::RETURN) {
                    $orderStatus = Order::ORDER_STATUS_COMPLETED;
                }
                Order::where('order_id', $orderRequest->orrequest_order_id)->update([
                    'order_status' => $orderStatus,
                ]);
                $billingAddress = $orderData->address->where('oaddr_type', OrderAddress::BILLING_ADDRESS_TYPE)->first();
                if (!empty($billingAddress->oaddr_phone) && !empty($billingAddress->oaddr_phone_country_id)) {
                    $country = Country::select('country_phonecode')->where('country_id', $billingAddress->oaddr_phone_country_id)->first();
                    $smsData = SmsTemplate::getSMSTemplate('return_declined');
                    $priority = $smsData['body']->stpl_priority;
                    $smsData['body'] = str_replace('{requestId}', $orderRequest->orrequest_order_id . '-' . $orderRequest->orrequest_id, $smsData['body']->stpl_body);
                    $smsData['body'] = str_replace('{name}', $billingAddress->oaddr_name, $smsData['body']);
                    $notificationData['sms'] = [
                        'message' => $smsData['body'],
                        'recipients' => array('+' . $country->country_phonecode . $billingAddress->oaddr_phone)
                    ];
                    sendPushNotification($orderData->user_id, 'return_declined', ['request_id' => $orderRequest->orrequest_id]);
                    dispatch(new SendNotification($notificationData, false, false, true))->onQueue($priority);
                }
            }
        }
        if ($request->input('status') != 'approved') {
            $commentId =  $this->saveComments($requestId, $requestType . ' '. __('LBL_REQUEST') .' ' . $request->input('status'), OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE);

            $orderCommentId = $this->saveComments($orderRequest->orrequest_order_id, $requestType . ' '. __('LBL_REQUEST_ID') . ' #' . $requestId . ' ' . $request->input('status'), OrderMessage::MSG_ORDER_TIMELLINE_TYPE);
            if ($message) {
                $reqComment = '<p> <span class="text-bold">' . __('LBL_REASON') . ':</span> ' . $message . '</p>';
                $orderComment = '<p> <span class="text-bold">' . __('LBL_COMMENT') . ':</span> ' . $message . '</p>';
                $this->saveComments($requestId, $reqComment, OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE, $commentId);
                $this->saveComments($orderRequest->orrequest_order_id, $orderComment, OrderMessage::MSG_ORDER_TIMELLINE_TYPE, $orderCommentId);
            }
        }
        $this->sendReturnCancellationEmail($request->input('status'), $orderRequest, $orderData, $comment);
        $this->setOrderCompleted($orderRequest->orrequest_order_id);
        return jsonresponse(true, __('NOTI_ORDER_STATUS_UPDATED'));
    }

    private function sendReturnCancellationEmail($status, $orderRequest, $orderData, $reason='')
    {
        if ($status == "approved") {
            $slug = "return_approved";
        } elseif ($status == "item-received") {
            $slug = "return_item_received_buyer";
        } else {
            $slug = "return_declined";
        }
        $emailData = EmailHelper::getEmailData($slug);
        $priority = $emailData['body']->etpl_priority;
        $userDetail = User::getUserById($orderRequest->orrequest_user_id);
        $orderProducts = OrderProduct::select('op_product_name', 'op_product_variants', 'op_pov_code')->where('op_order_id', $orderRequest->orrequest_order_id)
                        ->where('op_product_id', $orderRequest->op_product_id)->first();

       
        $variants = json_decode($orderProducts->op_product_variants, true);
        if (!empty($variants["styles"])) {
            $variants = " (" . implode(" | ", $variants["styles"]) . ") ";
        } else {
            $variants = "";
        }
        $emailData['subject'] = str_replace('{returnId}', $orderData->order_id.'-'.$orderRequest->orrequest_id, $emailData['body']->etpl_subject);
        if (OrderReturnRequest::CANCELLATION == $orderRequest->orrequest_type) {
            $requestType = "Cancellation";
        } else {
            $requestType = "Return";
        }
        $emailData['subject'] = str_replace('{type}', $requestType, $emailData['subject']);
        $emailData['body'] = str_replace("{userName}", $userDetail->user_name, $emailData['body']->etpl_body);
        $emailData['body'] = str_replace('{productName}', $orderProducts->op_product_name. $variants .' X '. $orderRequest->orrequest_qty, $emailData['body']);
        $emailData['body'] = str_replace('{productPrice}', formatPriceByCurrencyCode($orderRequest->op_product_price, $orderData->order_currency_code), $emailData['body']);
        $emailData['body'] = str_replace('{productImage}', productImageUrl($orderRequest->op_product_id, $orderProducts->op_pov_code), $emailData['body']);
        if ($slug == "return_item_received_buyer") {
            $emailData['body'] =  str_replace('{cancellationReason}', $orderRequest->orrequest_reason, $emailData['body']);
        } else {
            $emailData['body'] =  str_replace('{cancellationReason}', $reason, $emailData['body']);
        }
        $emailData['body'] =  str_replace('{returnRequestId}', $orderData->order_id.'-'.$orderRequest->orrequest_id, $emailData['body']);
        $emailData['body'] =  str_replace('{requestDeclinedComment}', $reason, $emailData['body']);
        $emailData['body'] =  str_replace('{subtotal}', formatPriceByCurrencyCode($orderRequest->op_product_price * $orderRequest->orrequest_qty, $orderData->order_currency_code), $emailData['body']);
        $emailData['body'] =  str_replace('{tax}', formatPriceByCurrencyCode($orderRequest->oramount_tax, $orderData->order_currency_code), $emailData['body']);
        $emailData['body'] =  str_replace('{cancellationAdditionInformation}', $orderRequest->orrequest_comment, $emailData['body']);
        $emailData['body'] =  str_replace('{shipping}', formatPriceByCurrencyCode($orderRequest->oramount_shipping, $orderData->order_currency_code), $emailData['body']);
        $refundableAmount = (($orderRequest->op_product_price * $orderRequest->orrequest_qty + $orderRequest->oramount_tax + $orderRequest->oramount_shipping) - $orderRequest->oramount_discount);
        $emailData['body'] =  str_replace('{requestType}', $requestType, $emailData['body']);
        $emailData['body'] =  str_replace('{totalRefundAmount}', formatPriceByCurrencyCode($refundableAmount, $orderData->order_currency_code), $emailData['body']);
        $emailData['body']  = str_replace('{websiteName}', env('APP_NAME'), $emailData['body']);
        $billAddress = $orderData->address->where('oaddr_type', OrderAddress::BILLING_ADDRESS_TYPE)->first();
        $emailData['to'] = $billAddress->oaddr_email;
        $notificationData['email'] = $emailData;
        dispatch(new SendNotification($notificationData, true, false, false))->onQueue($priority);
    }

    private function calculateRefund($orderRequest)
    {
        $total = ($orderRequest->op_product_price * $orderRequest->orrequest_qty + $orderRequest->oramount_tax + $orderRequest->oramount_shipping) - $orderRequest->oramount_discount - $orderRequest->oramount_reward_price;
        if ($orderRequest->oramount_giftwrap_price > 0) {
            $total = $total + $orderRequest->oramount_giftwrap_price;
        }
        return  priceFormat($total, false);
    }
    public function orderTransaction($orderId, $price, $comment, $userId, $gateWayType, $transId, $response, $type, $requestId = 0)
    {
        if (!canWrite(AdminRole::ORDERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        Transaction::create([
            'txn_user_id' => $userId,
            'txn_date' => Carbon::now(),
            'txn_amount' => $price,
            'txn_comments' => $comment,
            'txn_status' => Transaction::STATUS_COMPLETED,
            'txn_order_id' => $orderId,
            'txn_gateway_type' => $gateWayType,
            'txn_gateway_transaction_id' => $transId,
            'txn_orrequest_id' => $requestId,
            'txn_gateway_response' => $response,
            'txn_type' => $type
        ]);
    }
    public function paymentRefund($paymentGateway, $transactionId, $amount, $currencyCode)
    {
        $payment = new PaymentGatewayHelper($paymentGateway);
        return $payment->orderRefund($transactionId, $amount, $currencyCode);
    }
    public function pendingRequestCount(Request $request)
    {
        $requestCount = OrderReturnRequest::where([
            'orrequest_order_id' => $request->input('order-id'),
            'orrequest_status' => OrderReturnRequest::RETURN_REQUEST_STATUS_PENDING
        ])->count();
        if ($requestCount > 0) {
            return jsonresponse(false, __('NOTI_YOU_HAVE_PENDING_REQUESTS'));
        }
        return jsonresponse(true, null);
    }
    public function updateStatus(Request $request, $order_id)
    {
        if (!canWrite(AdminRole::ORDERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $order = Order::getRecordById($order_id);
        
        $status =   array_flip(Order::PICKUP_STATUS);
        if ($order->order_shipping_type == Order::ORDER_SHIPPING) {
            $status = Order::flipedStatus();
        }
      
        $orderStatus = [
            'order_shipping_status' => $status[$request->input('status')]
        ];
        if ($request->input('status') != "in-progress") {
            $this->genrateInvoice($order_id);
        }
        $transferDetails = json_decode($request->input("transfer-detail"), true);
        $message = $request->input('message');
        $comment = $request->input('status');
        if ($comment == "delivered" && !empty($transferDetails['payment_comment'])) {
            $message = $transferDetails['payment_comment'];
            $orderStatus['order_payment_status'] = Order::PAYMENT_PAID;
            $response = [
                'status' => true,
                'data' => [
                    'amount' => $transferDetails['amount'],
                    'id' => $transferDetails['transaction_id']
                ]
            ];
            /* trigger event for system notification*/
            $data = NotificationTemplate::getBySlug('order_payment_received');
            $message = str_replace('{orderNumber}', $order_id, $data->ntpl_body);
            $message = str_replace('{orderAmount}', formatPriceByCurrencyCode($transferDetails['amount'], $order->order_currency_code), $message);
            $message = str_replace('{paymentMethod}', $transferDetails['payment_method'], $message);
            event(new SystemNotification([
                'type' => Notification::ORDER_PAYMENT,
                'record_id' => $order_id,
                'from_id' => $order->order_user_id,
                'message' => $message
            ]));

            $this->orderTransaction($order_id, $transferDetails['amount'], 'Payment received #' . $order_id, $order->order_user_id, $transferDetails['payment_method'], $transferDetails['transaction_id'], json_encode($response), Transaction::DEBIT_TYPE);
        }

        if ($order->digital_products == count($order->products)) {
            $comment =  Order::DIGITAL_STATUS[$request->input('status')];
        }
        if ($request->input('complete') == 1) {
            $orderStatus['order_status'] = Order::ORDER_STATUS_COMPLETED;
            $comment = 'completed';

            if ($order->order_pending_rewards == 1) {
                $orderStatus['order_pending_rewards'] = false;
                $this->saveRewardPoints($order);
            }
        }
        $pickupSmsSlug = '';
        
        if ($status[$request->input('status')] == Order::SHIPPING_STATUS_READY_FOR_SHIPPING) {
            if ($order->order_shipping_type == Order::ORDER_PICKUP) {
                $pickupSmsSlug = 'pickup_order_packing';
            }
            if (count($order->products) == $order->digital_products) {
                $this->orderNotification($order_id, EmailHelper::getEmailData('digital_ready_for_shipping'), $order, $order->products);
                sendPushNotification($order->order_user_id, 'digital_ready_for_shipping', ['order_number' => $order->order_id, 'order_amount' => formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code)]);
            } elseif ($order->digital_products == 0) {
                $this->orderNotification($order_id, EmailHelper::getEmailData('physical_ready_for_shipping'), $order, $order->products, false, $pickupSmsSlug);
                sendPushNotification($order->order_user_id, 'physical_ready_for_shipping', ['order_number' => $order->order_id, 'order_amount' => formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code)]);
            } elseif (count($order->products) != $order->digital_products && $order->digital_products != 0) {
                sendPushNotification($order->order_user_id, 'physical_ready_for_shipping', ['order_number' => $order->order_id, 'order_amount' => formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code)]);
                sendPushNotification($order->order_user_id, 'digital_ready_for_shipping', ['order_number' => $order->order_id, 'order_amount' => formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code)]);

                $this->orderNotification($order_id, EmailHelper::getEmailData('digital_ready_for_shipping'), $order, $order->products->where('op_product_type', Product::DIGITAL_PRODUCT), true);
                $this->orderNotification($order_id, EmailHelper::getEmailData('physical_ready_for_shipping'), $order, $order->products->where('op_product_type', Product::PHYSICAL_PRODUCT), true, $pickupSmsSlug);
            }
        } elseif ($status[$request->input('status')] == Order::SHIPPING_STATUS_SHIPPED) {
            if ($order->order_shipping_type == Order::ORDER_PICKUP) {
                $pickupSmsSlug = 'pickup_order_ready_for_pickup';
            }
            if (count($order->products) == $order->digital_products) {
                sendPushNotification($order->order_user_id, 'digital_order_shipped', ['order_number' => $order->order_id, 'order_amount' => formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code)]);
                $this->orderNotification($order_id, EmailHelper::getEmailData('digital_order_shipped'), $order, $order->products);
            } elseif ($order->digital_products == 0) {
                sendPushNotification($order->order_user_id, 'physical_order_shipped', ['order_number' => $order->order_id, 'order_amount' => formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code)]);
                $this->orderNotification($order_id, EmailHelper::getEmailData('physical_order_shipped'), $order, $order->products, false, $pickupSmsSlug);
            } elseif (count($order->products) != $order->digital_products  && $order->digital_products != 0) {
                sendPushNotification($order->order_user_id, 'digital_order_shipped', ['order_number' => $order->order_id, 'order_amount' => formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code)]);
                sendPushNotification($order->order_user_id, 'physical_order_shipped', ['order_number' => $order->order_id, 'order_amount' => formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code)]);
                $this->orderNotification($order_id, EmailHelper::getEmailData('digital_order_shipped'), $order, $order->products->where('op_product_type', Product::DIGITAL_PRODUCT), true);
                $this->orderNotification($order_id, EmailHelper::getEmailData('physical_order_shipped'), $order, $order->products->where('op_product_type', Product::PHYSICAL_PRODUCT), true, $pickupSmsSlug);
            }
        } elseif ($status[$request->input('status')] == Order::SHIPPING_STATUS_DELIVERED && $request->input('complete') == 0) {
            if ($order->order_shipping_type == Order::ORDER_PICKUP) {
                $pickupSmsSlug = 'pickup_order_pickedup';
            }
            if (count($order->products) == $order->digital_products) {
                sendPushNotification($order->order_user_id, 'digital_order_delivered', ['order_number' => $order->order_id, 'order_amount' => formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code)]);


                $this->orderNotification($order_id, EmailHelper::getEmailData('digital_order_delivered'), $order, $order->products);
            } elseif ($order->digital_products == 0) {
                sendPushNotification($order->order_user_id, 'physical_order_delivered', ['order_number' => $order->order_id, 'order_amount' => formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code)]);
                $this->orderNotification($order_id, EmailHelper::getEmailData('physical_order_delivered'), $order, $order->products, false, $pickupSmsSlug);
            } elseif (count($order->products) != $order->digital_products  && $order->digital_products != 0) {
                sendPushNotification($order->order_user_id, 'digital_order_delivered', ['order_number' => $order->order_id, 'order_amount' => formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code)]);
                sendPushNotification($order->order_user_id, 'physical_order_delivered', ['order_number' => $order->order_id, 'order_amount' => formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code)]);
                $this->orderNotification($order_id, EmailHelper::getEmailData('digital_order_delivered'), $order, $order->products->where('op_product_type', Product::DIGITAL_PRODUCT), true);
                $this->orderNotification($order_id, EmailHelper::getEmailData('physical_order_delivered'), $order, $order->products->where('op_product_type', Product::PHYSICAL_PRODUCT), true, $pickupSmsSlug);
            }
        }
        Order::where('order_id', $order_id)->update($orderStatus);

        $commentId =  $this->saveComments($order_id, $comment, OrderMessage::MSG_ORDER_ACTION_TYPE);
        if ($message) {
            $this->saveComments($order_id, $message, OrderMessage::MSG_ORDER_ACTION_TYPE, $commentId);
        }
        if ($comment == "shipped" && !empty($request->input('courier_name'))) {
            $info = [
                'oainfo_order_id' => $order_id,
                'oainfo_courier_name' => $request->input('courier_name'),
                'oainfo_tracking_id' => $request->input('tracking_number')
            ];
            $exist = OrderAdditionInfo::where('oainfo_order_id', $order_id)->count();
            if ($exist) {
                OrderAdditionInfo::where('oainfo_order_id', $order_id)->update($info);
            } else {
                OrderAdditionInfo::create($info);
            }
        }
        
        if ($request->input('status') == "delivered") {
            /** send email */
            $user = User::getUserById($order->order_user_id);
            $orderProducts = OrderProduct::getByOrderId($order_id);
            foreach ($orderProducts as $orderProduct) {
                $variants = json_decode($orderProduct->op_product_variants, true);
                if (!empty($variants["styles"])) {
                    $variants = " (" . implode(" | ", $variants["styles"]) . ") ";
                } else {
                    $variants = "";
                }
                $data = EmailHelper::getEmailData("review_ask_buyer");
                $priority = $data['body']->etpl_priority;
                $data['subject'] = $data['body']->etpl_subject;
                $data['body'] = str_replace('{name}', $user->user_name, $data['body']->etpl_body);
                $data['body'] = str_replace('{productName}', $orderProduct->op_product_name, $data['body']);
                $data['body'] = str_replace('{productVariant}', $orderProduct->op_product_name . $variants, $data['body']);
                $data['body'] = str_replace('{productImage}', productImageUrl($orderProduct->op_product_id, $orderProduct->op_pov_code), $data['body']);
                $data['body'] = str_replace('{productUrl}', getRewriteUrl("products", $orderProduct->op_product_id), $data['body']);
                $data['body'] = str_replace('{reviewUrl}', url("/user/reviews"), $data['body']);
                $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
                $data['to'] = $user->user_email;
                dispatch(new SendNotification(array('email' => $data)))->onQueue($priority)->delay(now()->addMinutes(2880));
            //    sendPushNotification(Auth::guard('api')->user()->user_id, 'buyer_give_review', ['id' => (string)$orderProduct->op_id]);
                
                if ($orderProduct->op_product_type == Product::DIGITAL_PRODUCT) {
                    $notificationData = [];
                    $sendSms = false;
                    $data = EmailHelper::getEmailData("digital_download");
                    $priority = $data['body']->etpl_priority;
                    $data['subject'] = str_replace('{orderNumber}', $order_id, $data['body']->etpl_subject);
                    $data['body'] = str_replace('{name}', $user->user_name, $data['body']->etpl_body);
                    $data['body'] = str_replace('{productName}', $orderProduct->op_product_name . $variants, $data['body']);
                    $data['body'] = str_replace('{productImage}', productImageUrl($orderProduct->op_product_id, $orderProduct->op_pov_code), $data['body']);
                    $data['body'] = str_replace('{productUrl}', getRewriteUrl("products", $orderProduct->op_product_id), $data['body']);
                    $data['body'] = str_replace('{downloadUrl}', url("/user/orders/download"), $data['body']);
                    $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
                    $data['to'] = $user->user_email;
                    $notificationData['email'] = $data;
                    /*
                    $orderData = Order::getOrderById($order_id);
                    if (!empty($user->user_phone) && !empty($user->user_phone_country_id)) {
                        $country = Country::select('country_phonecode')->where('country_id', $user->user_phone_country_id)->first();
                        $data = SmsTemplate::getSMSTemplate('digital_download');
                        $priority = $data['body']->stpl_priority;
                        $data['body'] = str_replace('{number}', $order_id, $data['body']->stpl_body);
                        $data['body'] = str_replace('{name}', $user->user_name, $data['body']);
                        $data['body'] = str_replace('{amount}', $orderData->order_net_amount, $data['body']);
                        $data['body'] = str_replace('{websiteName}', (getConfigValueByName('BUSINESS_NAME') ?? ''), $data['body']);
                        $notificationData['sms'] = [
                            'message' => $data['body'],
                            'recipients' => array('+' . $country->country_phonecode . $user->user_phone)
                        ];
                        $sendSms = true;
                    }*/
                    sendPushNotification($order->order_user_id, 'digital_products_download', ['order_number' => $order_id, 'order_amount' => formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code)]);
                    dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);
                }
            }
        }

        return jsonresponse(true, __('NOTI_ORDER_STATUS_UPDATED'));
    }
    private function saveRewardPoints($order)
    {
        $config = Configuration::getKeyValues(['REWARD_POINTS_ROUNDING_MODE', 'REWARD_POINTS_PURCHASE_POINTS_VALIDITY']);
        $totalRewards = 0;
        foreach ($order->products as $product) {
            if ($product->rewards) {
                $rAmount = $product->rewards['opc_value'];
                if ($product->cancelRequest && $product->cancelRequest->orrequest_status != 3) {
                    $balanceQty = $product->op_qty - $product->cancelRequest->orrequest_qty;
                    if ($balanceQty != 0) {
                        $rAmount = ($rAmount / $product->op_qty) * $balanceQty;
                        if (($config['REWARD_POINTS_ROUNDING_MODE']) == 1) {
                            if (strpos($rAmount, ".") !== false) {
                                $rAmount =  ceil($rAmount);
                            }
                        } else {
                            $rAmount =  floor($rAmount);
                        }
                    } else {
                        $rAmount = 0;
                    }

                    OrderProductCharge::where(['opc_type' => 'rewardpoints', 'opc_op_id' => $product->op_id])->update(['opc_value' => $rAmount]);
                }
                $totalRewards += $rAmount;
            }
        }
        if ($totalRewards != 0) {
            $insertId = UserRewardPoint::create([
                'urp_user_id' => $order->user_id,
                'urp_referral_user_id' => 0,
                'urp_comments' => __('LBL_EARN_REWARD_POINT').' #' . $order->order_id,
                'urp_type' => UserRewardPoint::ORDER_EARNED_POINT,
                'urp_points' => $totalRewards,
                'urp_order_id' => $order->order_id,
                'urp_date_added' => Carbon::now(),
                'urp_date_expiry' => Carbon::now()->addDays($config['REWARD_POINTS_PURCHASE_POINTS_VALIDITY']),
            ])->urp_id;
            UserRewardPointBreakup::create([
                'urpbreakup_urp_id' => $insertId,
                'urpbreakup_referral_user_id' => 0,
                'urpbreakup_used_order_id' => 0,
                'urpbreakup_points' => $totalRewards,
                'urpbreakup_used' => 0,
                'urp_date_added' => Carbon::now(),
                'urpbreakup_expiry' => Carbon::now()->addDays($config['REWARD_POINTS_PURCHASE_POINTS_VALIDITY']),
            ]);
        }
    }
    private function genrateInvoice($orderId)
    {
        $invoiceExist = OrderInvoice::where('oinv_order_id', $orderId)->count();

        if ($invoiceExist == 0) {
            $invoiceDetails = Configuration::getKeyValues(['TAX_INVOICE_ALPHABET_NUMBER', 'TAX_INVOICE_NUMERIC_NUMBER']);
            $newInvoiceNumber = $invoiceDetails['TAX_INVOICE_ALPHABET_NUMBER'] . '-' . $invoiceDetails['TAX_INVOICE_NUMERIC_NUMBER'];
            $lastInvoice = OrderInvoice::select('oinv_number')->where('oinv_number', 'LIKE', '%' . $invoiceDetails['TAX_INVOICE_ALPHABET_NUMBER'] . '%')->orderBy('oinv_created_at', 'DESC')->first();
            if (!empty($lastInvoice)) {
                $explodeNumber = array_filter(explode('-', $lastInvoice->oinv_number));
                if (isset($explodeNumber) && count($explodeNumber) > 0) {
                    $lastinvoiceNumber = end($explodeNumber);
                    if (is_numeric($lastinvoiceNumber)) {
                        $newNumber = str_pad(intval($lastinvoiceNumber) + 1, strlen($lastinvoiceNumber), '0', STR_PAD_LEFT);
                        $newInvoiceNumber =  str_replace($lastinvoiceNumber, $newNumber, $lastInvoice->oinv_number);
                    }
                }
            }
            $currentTime = Carbon::now();


            $records = [
                'order' => Order::getRecordById($orderId),
                'addressTypes' => OrderAddress::ADDRESS_TYPES,
                'taxDetails' => Configuration::getRecordsByPrefix('TAX'),
                'businessDetails' => Configuration::getRecordsByPrefix('BUSINESS'),
                'invoiceNumber' => $newInvoiceNumber,
                'invoiceDate' => $currentTime,
                'taxInfo' => OrderProductTaxLog::where('optl_order_id', $orderId)->get()->toArray(),
            ];
            if (!empty($records["businessDetails"]["BUSINESS_COUNTRY"])) {
                $country = Country::getRecordById($records["businessDetails"]["BUSINESS_COUNTRY"]);
                $records["businessDetails"]["BUSINESS_COUNTRY"] = $country->country_name;
            }
            if (!empty($records["businessDetails"]["BUSINESS_STATE"])) {
                $state = State::getRecordById($records["businessDetails"]["BUSINESS_STATE"]);
                $records["businessDetails"]["BUSINESS_STATE"] = $state->state_name;
            }
            $html = View('pdf.order-invoice', $records)->render();

            OrderInvoice::create([
                'oinv_order_id' =>  $orderId,
                'oinv_number' =>  $newInvoiceNumber,
                'oinv_created_at' => $currentTime,
                'oinv_content' => $html
            ]);
        }
    }
    public function orderComplete(Request $request, $order_id)
    {
        if (!canWrite(AdminRole::ORDERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        Order::where('order_id', $order_id)->update([
            'order_status' => Order::ORDER_STATUS_COMPLETED
        ]);
        $comment = 'Order Status updated to completed';
        $this->saveComments($order_id, $comment, OrderMessage::MSG_ORDER_ACTION_TYPE);

        return jsonresponse(true, __('NOTI_ORDER_STATUS_UPDATED'));
    }

    public function orderReturn(Request $request)
    {
        if (!canWrite(AdminRole::ORDERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $orderId = $request->input('order-id');
        $order = Order::getRecordById($orderId);
        /* $comment = 'Order Return by admin';
        $msgId = $this->saveComments($orderId, $comment, OrderMessage::MSG_ORDER_TYPE);*/
        $transactionId = 0;
        $transaction =  Transaction::where('txn_order_id', $orderId)->where('txn_type', Transaction::DEBIT_TYPE)->first();
        if ($transaction) {
            $transactionId = $transaction->txn_gateway_transaction_id;
        }
        $returnType = OrderReturnRequest::RETURN;
        if ($order->order_shipping_status == 4) {
            $returnType = OrderReturnRequest::CANCELLATION;
        }
        foreach ($order->products as $item) {
            $requestExist = OrderReturnRequest::where(['orrequest_op_id' => $item->op_id, 'orrequest_order_id' => $orderId])->count();
            if ($requestExist != 0) {
                return jsonresponse(false, __('Invalid request!'));
            }
            OrderReturnRequest::create([
                'orrequest_user_id' => $order->user_id,
                'orrequest_op_id' => $item->op_id,
                'orrequest_order_id' => $orderId,
                'orrequest_type' => $returnType,
                'orrequest_qty' =>  $item->op_qty,
                'orrequest_date' => Carbon::now(),
                'orrequest_txn_gateway_transaction_id' =>  $transactionId,
                'orrequest_details' =>  '',
                'orrequest_order_status' => isOrderPaid($order->payment_status) ? 1 : 0,
            ]);
        }

        $orderStatus  = Order::ORDER_STATUS_RETURNED;

        Order::where('order_id', $orderId)->update(['order_status' => $orderStatus]);
        return jsonresponse(true, __('NOTI_REQUEST_SUBMITTED'));
    }

    private function sendAdminCancelledEmailToBuyer($order, $orderRequest, $product, $orrequestData, $reason = '')
    {
        $data = EmailHelper::getEmailData('order_cancelled_by_admin_to_buyer');
        $priority = $data['body']->etpl_priority;
        $shipAddress = $order->address->where('oaddr_type', OrderAddress::SHIPPING_ADDRESS_TYPE)->first();
        $billAddress = $order->address->where('oaddr_type', OrderAddress::BILLING_ADDRESS_TYPE)->first();
        $data['subject'] = $this->cancellationReplacementTags($data['body']->etpl_subject, $order, $product, $shipAddress, $billAddress, $orderRequest, $orrequestData, $reason);
        $data['body'] = $this->cancellationReplacementTags($data['body']->etpl_body, $order, $product, $shipAddress, $billAddress, $orderRequest, $orrequestData, $reason);
        $data['to'] = $billAddress->oaddr_email;
        dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);
    }

    public function cancellationReplacementTags($type, $order, $product, $shipAddress, $billAddress, $orderRequest, $orrequestData, $reason)
    {
        $products = [$product];
        $orderProducts = view('emails.order-products', compact('products'))->render();
        
        $billingAddress = '';
        if (!empty($billAddress)) {
            $country = Country::getCountries(['country_phonecode'], ['country_id' => $billAddress->oaddr_phone_country_id])->first();
            $phoneCode = !empty($country->country_phonecode) ? '+' . $country->country_phonecode . ' ' : '';
            
            $billingAddress = '<strong>' . $billAddress->oaddr_name . '</strong> <br>' . $billAddress->oaddr_address1 . ', ' . $billAddress->oaddr_address2 . '<br>' . $billAddress->oaddr_city . ',' . $billAddress->oaddr_state . '<br>' .  $billAddress->oaddr_country . '<br>' . $phoneCode . $billAddress->oaddr_phone;
        }
        $shippingAddress = '';
        if (!empty($shipAddress)) {
            $country = Country::getCountries(['country_phonecode'], ['country_id' => $shipAddress->oaddr_phone_country_id])->first();
            $phoneCode = !empty($country->country_phonecode) ? '+' . $country->country_phonecode . ' ' : '';

            $shippingAddress = '<strong>' . $shipAddress->oaddr_name . '</strong> <br>' . $shipAddress->oaddr_address1 . ', ' . $shipAddress->oaddr_address2 . '<br>' . $shipAddress->oaddr_city . ',' . $shipAddress->oaddr_state . '<br>' .  $shipAddress->oaddr_country . '<br>' . $phoneCode . $shipAddress->oaddr_phone;
        }
        $pickupAddress = "";
        $pickAddress = $order->address->where('oaddr_type', OrderAddress::SHIPPING_PICKUP_TYPE)->first();
        if (!empty($pickAddress)) {
            $country = Country::getCountries(['country_phonecode'], ['country_id' => $pickAddress->oaddr_phone_country_id])->first();
            $phoneCode = !empty($country->country_phonecode) ? '+' . $country->country_phonecode . ' ' : '';

            $pickupAddress = '<strong>' . $pickAddress->oaddr_name . '</strong> <br>' . $pickAddress->oaddr_address1 . ', ' . $pickAddress->oaddr_address2 . '<br>' . $pickAddress->oaddr_city . ',' . $pickAddress->oaddr_state . '<br>' .  $pickAddress->oaddr_country . '<br>' . $phoneCode . $pickAddress->oaddr_phone;
        }

        $orderType = '';
        if ($order->order_shipping_type == Order::ORDER_SHIPPING) {
            $orderType = 'shipping';
        } elseif ($order->order_shipping_type == Order::ORDER_PICKUP) {
            $orderType = 'pickup';
        }
        
        if (isset($order->products) && isset($order->digital_products) && count($order->products) == $order->digital_products) {
            $orderType = 'digital';
        }
        
        $type =  str_replace('{cancellationReason}', $reason, $type);
        $type =  str_replace('{subtotal}', formatPriceByCurrencyCode($product->op_product_price * $orrequestData->orrequest_qty, $order->order_currency_code), $type);
        $type =  str_replace('{tax}', formatPriceByCurrencyCode($orderRequest->oramount_tax, $order->order_currency_code), $type);
        $type =  str_replace('{shipping}', formatPriceByCurrencyCode($orderRequest->oramount_shipping, $order->order_currency_code), $type);
        $refundableAmount = (($product->op_product_price * $orrequestData->orrequest_qty + $orderRequest->oramount_tax + $orderRequest->oramount_shipping) - $orderRequest->oramount_discount);
        $type =  str_replace('{totalRefundAmount}', formatPriceByCurrencyCode($refundableAmount, $order->order_currency_code), $type);

        $type = str_replace('{number}', $order->order_id, $type);
        $type = str_replace('{websiteName}', env('APP_NAME'), $type);
        $type = str_replace('{amount}', $order->currency_symbol . '' .  $order->order_net_amount, $type);
        $type = str_replace('{date}', getConvertedDateTime($order->order_date_added), $type);
        $type = str_replace('{paymentMethod}', $order->order_payment_method, $type);
        $type = str_replace('{productListing}', $orderProducts, $type);
        $shippingMethods = json_decode($order->order_shipping_methods, true);
        if (isset($shippingMethods['pick_ups']) && !empty($shippingMethods['pick_ups'])) {
            $pickupDateTime = $shippingMethods['pick_ups']['pickup_date'] . ' ' . $shippingMethods['pick_ups']['pickup_time'];
        } else {
            $pickupDateTime = '';
        }
        $orderAddresses = view('emails.order-addresses', compact('shippingAddress', 'billingAddress', 'pickupAddress', 'orderType', 'pickupDateTime'))->render();
        $type = str_replace('{orderAddresses}', $orderAddresses, $type);

        return $type;
    }

    public function getOrdersListing(Request $request)
    {
        $result = Order::getOrders($request->all());
        $orders = $result['orders'];
        $shippingStatus = Order::SHIPPING_STATUS;
        $pickupStatus = Order::PICKUP_STATUS;

        $orderDigitalStatus = Order::DIGITAL_STATUS;
        $configValues = getConfigValues(['PRODUCT_TYPES', 'PICK_UP_ENABLE', 'SHIPPING_MESSAGES_ENABLE']);
        $productType = $configValues['PRODUCT_TYPES'];
        $orderStatus = Arr::except(
            Order::ORDER_STATUS,
            [
                Order::ORDER_STATUS_OPEN,
            ]
        );
        $orderReturnStatus = OrderReturnRequest::TYPE;
        $orderReturnRequests = OrderReturnRequest::REQUEST_STATUS;
        $pickupEnabled = $configValues['PICK_UP_ENABLE'];
        if ($pickupEnabled == 0) {
            $pickupOrder = Order::where('order_shipping_type', Order::ORDER_PICKUP)->where('order_status', '!=', Order::ORDER_STATUS_COMPLETED)->limit(1)->count();
            if ($pickupOrder > 0) {
                $pickupEnabled = 1;
            }
        }

        $showEmpty = 0;
        if (Order::limit(1)->count() == 0) {
            $showEmpty = 1;
        }
        $trackShippingPackage = Package::getActivePackage('trackShipping');
        if (isset($trackShippingPackage->pkg_slug) && !empty($trackShippingPackage->pkg_slug)) {
            $trackingApi = new TrackingApiHelper('AfterShip');
            $shippingCouriers = $trackingApi->trackingCouriers();
        } else {
            $shippingCouriers = [];
        }
        $records = [
            'orders' => $orders,
            'shippingStatus' => $shippingStatus,
            'orderDigitalStatus' => $orderDigitalStatus,
            'orderStatus' => $orderStatus,
            'productType' => $productType,
            'pickupStatus' => $pickupStatus,
            'orderReturnStatus' => $orderReturnStatus,
            'orderReturnRequests' => $orderReturnRequests,
            'showEmpty' => $showEmpty,
            'pickupEnabled' => $pickupEnabled,
            'shippingMesages' => $configValues['SHIPPING_MESSAGES_ENABLE'],
            'shippingCouriers' => $shippingCouriers
        ];

        return jsonresponse(true, null, $records);
    }
    public function show(Request $request, $id)
    {
        $adminName = $this->admin->admin_name;
        $firstLetter = substr($adminName, 0, 1);
        $fullname = explode(' ', $adminName);
        $last = '';
        if (!empty($fullname[1])) {
            $last = substr($fullname[1], 0, 1);
        }
   
        $order = Order::getRecordById($id);
        $shipType = Order::SHIPPING_STATUS;
        if ($order->order_shipping_type == 1) {
            $shipType = Order::PICKUP_STATUS;
        }
        $configValues = getConfigValues(['PRODUCT_TYPES', 'SHIPPING_MESSAGES_ENABLE']);
        $data['productType'] = $configValues['PRODUCT_TYPES'];
        $data['shippingMesages'] = $configValues['SHIPPING_MESSAGES_ENABLE'];
        $data['userName'] = $firstLetter . '' . $last;
        $order->user_phone_country_id = !empty($order->user_phone_country_id) ? Country::getRecordById($order->user_phone_country_id)->country_phonecode : '';
        $data['order'] = $order;
        $data['addressTypes'] = OrderAddress::ADDRESS_TYPES;
        $data['orderDigitalStatus'] = Order::DIGITAL_STATUS;
        $data['shippingStatus'] = $shipType;
        $data['orderStatus'] = Order::ORDER_STATUS;
        $data['orderReturnStatus'] = OrderReturnRequest::REQUEST_STATUS;
        $data['messages'] = OrderMessage::getRecords($id, [OrderMessage::MSG_ORDER_TIMELLINE_TYPE, OrderMessage::MSG_ORDER_ACTION_TYPE]);
        $data['previous'] = Order::where('order_id', '<', $id)->max('order_id');
        $data['next'] = Order::where('order_id', '>', $id)->min('order_id');
        $data['tags'] = OrderTag::where('otag_order_id', $id)->pluck('otag_name');
        $data['trackingInfo'] = OrderAdditionInfo::getTrackingIDAndCourierFromOrder($order->order_id);

        $data['shippingTypes'] = array_reverse(Order::SHIPPING_STATUS);
        $data['digitalStatus'] = array_reverse(Order::DIGITAL_STATUS);
        $data['pickupStatus'] =  array_reverse(Order::PICKUP_STATUS);

        $trackShippingPackage = Package::getActivePackage('trackShipping');
        if (isset($trackShippingPackage->pkg_slug) && !empty($trackShippingPackage->pkg_slug)) {
            $trackingApi = new TrackingApiHelper('AfterShip');
            $shippingCouriers = $trackingApi->trackingCouriers();
        } else {
            $shippingCouriers = [];
        }
        $data['shippingCouriers'] = $shippingCouriers;
        $data['trackingEnabled'] = count($shippingCouriers) >= 1 ? true : false;
        return jsonresponse(true, null, $data);
    }
    public function ykReturnOrder(Request $request, $orderId, $requestId)
    {
        $adminName = $this->admin->admin_name;
        $firstLetter = substr($adminName, 0, 1);
        $fullname = explode(' ', $adminName);
        $last = '';
        if (!empty($fullname[1])) {
            $last = substr($fullname[1], 0, 1);
        }
        $data['userName'] = $firstLetter . '' . $last;
        $order = Order::getRecordById($orderId, 0, $requestId, true);
        $order->user_phone_country_id = !empty($order->user_phone_country_id) ? Country::getRecordById($order->user_phone_country_id)->country_phonecode : '';
        $data['order'] = $order;
        $data['addressTypes'] = OrderAddress::ADDRESS_TYPES;
        $data['shippingStatus'] = Order::SHIPPING_STATUS;
        $data['orderStatus'] = Order::ORDER_STATUS;
        $data['orderReturnStatus'] = OrderReturnRequest::REQUEST_STATUS;
        $data['messages'] = OrderMessage::getRecords($requestId, [OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE]);
        return jsonresponse(true, null, $data);
    }
    public function getComments(Request $request)
    {
        $condition = [OrderMessage::MSG_ORDER_TIMELLINE_TYPE, OrderMessage::MSG_ORDER_ACTION_TYPE];
        if ($request->input('comment')  == 'true') {
            $condition = OrderMessage::MSG_ORDER_TIMELLINE_TYPE;
        }
        if (!empty($request->input('type'))) {
            $condition = OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE;
        }
        $messages = OrderMessage::getRecords($request->input('record-id'), $condition, $request);
        return jsonresponse(true, null, compact('messages'));
    }
    public function updateNote(Request $request)
    {
        if (!canWrite(AdminRole::ORDERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        Order::where('order_id', $request->input('order-id'))->update([
            'order_notes' => $request->input('note')
        ]);
        return jsonresponse(true, __('NOTI_NOTE_UPDATED'));
    }
    public function getOrderDetails(Request $request, $orderId)
    {
        return jsonresponse(true, null, Order::getRecordById($orderId));
    }
    public function updateCourierInfo(Request $request)
    {
        OrderAdditionInfo::where('oainfo_order_id', $request->input('oainfo_order_id'))->update($request->all());
        return jsonresponse(true, __('NOTI_INFO_UPDATED'));
    }
    protected function validation(array $data)
    {
        $validator = Validator::make($data, [
            'amount' => ['required', 'numeric'],
            'payment_comment' => ['required'],
            'payment_method' => ['required'],
            'transaction_id' => ['required'],
        ]);
        return $validator->setAttributeNames([
            'amount' => 'amount',
            'payment_comment' => 'comment',
            'payment_method' => 'payment method',
            'transaction_id' => 'transaction id',
        ]);
    }
    public function savePaymentDetail(Request $request)
    {
        if (!canWrite(AdminRole::ORDERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $this->validation($request->all())->validate();
        $order = Order::where('order_id', $request->input('order-id'))->first();
        $amount = $request->input('amount');
        if ($order->order_amount_charged > 0) {
            $amount = $order->order_amount_charged + $request->input('amount');
        }
        Order::where('order_id', $request->input('order-id'))->update([
            'order_payment_status' => Order::PAYMENT_PAID,
            'order_amount_charged' => $amount
        ]);
        $commentId =  $this->saveComments($order->order_id, $request->input('payment_comment'), OrderMessage::MSG_ORDER_ACTION_TYPE);
        $response = [
            'status' => true,
            'data' => [
                'amount' => $request['amount'],
                'id' => $request['transaction_id']
            ]
        ];

        /** trigger event for system notification */
        $data = NotificationTemplate::getBySlug('order_payment_received');
        $message = str_replace('{orderNumber}', $order->order_id, $data->ntpl_body);
        $message = str_replace('{orderAmount}', formatPriceByCurrencyCode($request['amount'], $order->order_currency_code), $message);
        $message = str_replace('{paymentMethod}', $request['payment_method'], $message);
        event(new SystemNotification([
            'type' => Notification::ORDER_PAYMENT,
            'record_id' => $order->order_id,
            'from_id' => $order->order_user_id,
            'message' => $message
        ]));

        $this->orderTransaction($order->order_id, $request['amount'], 'Payment received #' . $order->order_id, $order->order_user_id, $request['payment_method'], $request['transaction_id'], json_encode($response), Transaction::DEBIT_TYPE);
        return jsonresponse(true, __('NOTI_PAYMENT_UPDATED'));
    }
    public function returnApproved(Request $request)
    {
        $this->validation($request->all())->validate();
        $amount = $request->input('amount');
        $requestId = $request->input('request-id');
        $orderRequest = OrderReturnRequest::getRecordById($requestId);
        if ($orderRequest->orrequest_status ==  OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED) {
            //    return jsonresponse(false, __('NOTI_ORDER_STATUS_ALREADY_APPROVED'));
        }
        OrderReturnRequest::where('orrequest_id', $requestId)->update([
            'orrequest_status' => OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED
        ]);

        $commentId =  $this->saveComments($requestId, "approved", OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE);
        $comment = $request->input('payment_comment');
        if ($comment) {
            $this->saveComments($requestId, $comment, OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE, $commentId);
        }

        if ($orderRequest->oramount_payment_status !=  1) {
            $response = [
                'status' => true,
                'data' => [
                    'amount' => $amount,
                    'id' => $request['transaction_id']
                ]
            ];

            $this->orderTransaction($orderRequest->orrequest_order_id, $amount, 'Payment refunded #' . $orderRequest->orrequest_order_id, $orderRequest->orrequest_user_id, $request['payment_method'], $request['transaction_id'], json_encode($response), Transaction::CREDIT_TYPE, $requestId);

            OrderReturnAmount::where('oramount_id', $orderRequest->oramount_id)->update(['oramount_payment_status' => config('constants.YES')]);
        }
        $this->setOrderCompleted($orderRequest->orrequest_order_id);
        return jsonresponse(true, __('NOTI_PAYMENT_UPDATED'));
    }
    private function setOrderCompleted($orderId)
    {
        $order = Order::select('order_status')->where('order_id', $orderId)->first();
        $totalApproved = OrderReturnRequest::where([
            'orrequest_status' => OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED,
            'orrequest_order_id' => $orderId
        ])->count();

        $totalOrderedProducts = OrderProduct::where([
            'op_order_id' => $orderId
        ])->count();
        if ($totalOrderedProducts  === $totalApproved && $order->order_status == Order::ORDER_STATUS_RETURNED) {
            Order::where('order_id', $orderId)->update([
                'order_status' => Order::ORDER_STATUS_COMPLETED,
            ]);
        }
    }
    public function saveTags(Request $request, $orderId = 0)
    {
        if (!canWrite(AdminRole::ORDERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $tags = json_decode($request->input('tags'), true);
        if ($orderId == 0) {
            $orderId = $request->input('order-id');
        }
        $tagsIds = [];
        foreach ($tags as $tag) {
            $input = ['otag_name' => $tag['text'], 'otag_order_id' => $orderId];
            $exist  = OrderTag::where($input)->first();
            if ($exist) {
                $tagsIds[] = $exist->otag_id;
            } else {
                $tagsIds[] = OrderTag::create($input)->otag_id;
            }
        }
        OrderTag::whereNotIn('otag_id', $tagsIds)->delete();
        return jsonresponse(true, __('NOTI_TAGS_UPDATED'));
    }
    public function getOrderAddress(Request $request, $userId)
    {
        $defaultState = '';
        $address = [];
        $defalutAddress = [];
        $billingAddress = [];
        $shippingAddress = [];
        $states = [];
        $address = UserAddress::getRecordByUserId($userId, false);
        $countryCode = Str::upper(getDefaultCountryCode());
        if ($address->count() > 0) {
            $shippingAddress = $address->where('addr_shipping_default', config('constants.YES'))->first();

            $billingAddress = $address->where('addr_billing_default', config('constants.YES'))->first();
            $defalutAddress = $address->first()->toArray();
            if (empty($shippingAddress)) {
                $shippingAddress = $defalutAddress;
            }
            if (empty($billingAddress)) {
                $billingAddress = $defalutAddress;
            }
            if ($shippingAddress) {
                $shippingAddress['country_code'] = $shippingAddress['country']['country_code'];
                $shippingAddress['country_name'] = $shippingAddress['country']['country_name'];
                $shippingAddress['state_name'] = $shippingAddress['state']['state_name'];
            } else {
                $shippingAddress['country_code'] = $countryCode;
            }
            if ($billingAddress) {
                $billingAddress['country_code'] = $billingAddress['country']['country_code'];
                $billingAddress['country_name'] = $billingAddress['country']['country_name'];
                $billingAddress['state_name'] = $billingAddress['state']['state_name'];
            } else {
                $billingAddress['country_code'] = $countryCode;
            }

            $states = State::where('state_country_id', $defalutAddress['addr_country_id'])->pluck('state_name', 'state_id');
        } else {
            $stateObj = Country::getStatesByCountryCode($countryCode, ['state_name', 'state_id']);
            $defaultState = $stateObj->first()->state_name;
            $states = $stateObj->pluck('state_name', 'state_id');
        }


        $userDetail = User::select('user_email', 'user_id', 'user_phone', 'user_phone_country_id')->with('phoneCountry')->where('user_id', $userId)->first();

        $result['countries'] = Country::all();
        $result['countryCode'] = $countryCode;
        $result['defaultAddress'] = $defalutAddress;
        $result['allAddress'] = $address;
        $result['shippingAddress'] = $shippingAddress;
        $result['billingAddress'] = $billingAddress;
        $result['states'] = $states;
        $result['userDetail'] = $userDetail;
        $result['defaultState'] = $defaultState;
        return jsonresponse(true, null, $result);
    }
    public function getStates(Request $request)
    {
        $records = State::where('state_country_id', $request->input('country-id'))->pluck('state_name', 'state_id');
        return jsonresponse(true, null, $records);
    }


    public function orderSummary(Request $request)
    {
        $countryId = $request->input('country-id');
        $stateId = $request->input('state-id');
        if (empty($countryId) && empty($stateId)) {
            $countryCode = Str::upper(getDefaultCountryCode());
            $country = Country::getRecordByCode($countryCode);
            $countryId = $country->country_id;
            $stateId = $country->state_id;
        }
        $products = json_decode($request->input('products'), true);
        $shippingResults = '';
        $pickupAddress = [];
        if ($request->input('shipping-enable') == 1) {
            $shipping = new ShippingHelper($countryId, $stateId);
            $shippingResults =  $shipping->getShipments($products);
        } else {
            $pickupAddress = StoreAddress::getRecords([], false);
        }

        $record['shippingResults'] =  $shippingResults;
        $record['pickupAddress'] =  $pickupAddress;
        foreach ($products as $product) {
            $taxCountryId = $countryId;
            $taxStateId = $stateId;
            if ($product['prod_type'] == Product::DIGITAL_PRODUCT) {
                $taxCountryId = $request->input('shipping-country-id');
                $taxStateId = $request->input('shipping-state-id');
            }
            $tax = new TaxHelper($taxCountryId, $taxStateId);
            $tax = $tax->getRates($product['taxId']);
            $record['taxResults'][$product['product_id']] = $tax['value'] / 100;
        }
        return jsonresponse(true, null, $record);
    }
    public function saveAddress(Request $request)
    {
        if (!canWrite(AdminRole::ORDERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        UserAddress::addressValidate($request->all())->validate();
        $records = $request->all();
        if (isset($records['country_code']) && !empty($records['country_code'])) {
            $records['addr_phone_country_id'] = Country::getCountryIdByCountryCode($records['country_code']);
        }
        if (!empty($request['addr_id'])) {
            $data['addr_id'] = $request['addr_id'];
            $data['addr_user_id'] = $request['addr_user_id'];

            if (isset($records['country_code']) && !empty($records['country_code'])) {
                $records['addr_phone_country_id'] = Country::getCountryIdByCountryCode($records['country_code']);
            } else {
                $records['addr_phone_country_id'] = Country::getCountryIdByCountryCode('us');
            }
            unset($records['country_code']);
            UserAddress::where('addr_id', $request['addr_id'])->update($records);
            $userRecords = $request->only('addr_first_name', 'addr_last_name', 'addr_email', 'addr_phone');
            $userRecords['addr_phone_country_id'] = $records['addr_phone_country_id'];
            $user = User::generateUserId($userRecords);
            $insertedId = $request['addr_id'];
        } else {
            if (isset($records['country_code']) && !empty($records['country_code'])) {
                $records['addr_phone_country_id'] = Country::getCountryIdByCountryCode($records['country_code']);
            } else {
                $records['addr_phone_country_id'] = Country::getCountryIdByCountryCode('us');
            }
            $userRecords = $request->only('addr_first_name', 'addr_last_name', 'addr_email', 'addr_phone');
            $userRecords['addr_phone_country_id'] = $records['addr_phone_country_id'];
            $user = User::generateUserId($userRecords);
            $records['addr_user_id'] = $user->user_id;
            unset($records['addr_id']);
            unset($records['country_code']);
            $insertedId = UserAddress::create($records)->addr_id;
            $data['addr_id'] = $insertedId;
        }

        $address = UserAddress::getRecordByid($insertedId);
        $data['defaultAddress'] = $address;
        $data['shippingAddress'] = $address;
        $data['billingAddress'] = $address;
        $user['phone_country'] = $address['phonecountry'];
        $data['userDetail'] = $user;

        return jsonresponse(true, __('NOTI_ORDER_ADDRESS_ADDED'), $data);
    }
    protected function cartValidation(array $data)
    {
        $validator = Validator::make($data, [
            'number' => ['required'],
            'name' => ['required', 'string', 'max:191'],
            'exp_month' => ['required', 'min:2', 'max:2'],
            'exp_year' => ['required', 'min:4', 'max:4'],
            'cvv' => ['required'],
        ]);
        return $validator->setAttributeNames([
            'number' => 'card number',
            'name' => 'card name',
            'exp_month' => 'expired month',
            'exp_year' => 'expired year',
            'cvv' => 'cvv',
        ]);
    }
    public function saveOrder(Request $request)
    {
        if (!canWrite(AdminRole::ADD_ORDERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $userId = $request->input('user-id');
        $couponCode = '';
        $couponValue = '';
        $couponType = '';

        $price = $request->input('total-price');

        if ($request->input('total-discount') != 0) {
            $couponCode = 'AdminFlatDiscount';
            $couponValue = $request->input('total-discount');
            $couponType = 0;
            $price = $price - $couponValue;
        }
        $cardDetails = json_decode($request->input('card-details'), true);
        $paymentMethod = 'cod';
        $orderChargeAmount = $price;
        $paymentGateways = '';
        if ($request->input('payment_type') == 2 && $request->input('share-link') != "true") {
            $paymentGateways = Package::getActivePaymentGateways('paymentGateways')->where('pkg_card_type', 1)->first();
            if (empty($paymentGateways)) {
                return jsonresponse(false, __('NOTI_PAYMENT_GATEWAY_NOT_ACTIVATED'));
            }
            $this->cartValidation($cardDetails)->validate();
            $paymentMethod = $paymentGateways->pkg_slug;
            $orderChargeAmount = 0;
        }
        $orderStatus =  Order::PAYMENT_PENDING;
        if ($request->input('payment_type') == 1) {
            $orderStatus = Order::PAYMENT_PAID;
        }

        $shippingDetails = $request->input('shipping');
        $shipType = Order::ORDER_SHIPPING;
        $timeLineMessage = Order::SHIPPING_STATUS[Order::SHIPPING_STATUS_IN_PROGRESS];
        if ($request['order-shipping-type'] != 1) {
            $date = Carbon::parse($request->input('pickup_time'));
            $shippingDetails = json_encode([
                'pick_ups' => [
                    'pickup_date' => $date->format('Y-m-d'),
                    'pickup_time' => $date->format('h:i'),
                ]
            ]);
            $shipType = Order::ORDER_PICKUP;
            $timeLineMessage = Order::PICKUP_STATUS[Order::SHIPPING_STATUS_IN_PROGRESS];
        }


        $insertId = Order::create([
            'order_user_id' => $userId,
            'order_payment_status' => $orderStatus,
            'order_shipping_status' =>  $request['order-shipping-status'],
            'order_status' => Order::ORDER_STATUS_OPEN,
            'order_payment_method' => $paymentMethod,
            'order_net_amount' => $price,
            'order_amount_charged' => $orderChargeAmount,
            'order_tax_charged' =>  $request->input('total-tax'),
            'order_discount_coupon_code' => $couponCode,
            'order_discount_type' => $couponType,
            'order_shipping_type' =>  $shipType,
            'order_discount_amount' => $couponValue,
            'order_currency_code' => currencyCode(),
            'order_currency_symbol' => currencySymbol(),
            'order_currency_value' => currencyValue(),
            'order_shipping_value' => $request->input('total-shipping'),
            'order_gift_amount' => $request->input('total-gift-price'),
            'order_reward_points' => 0,
            'order_reward_amount' => 0,
            'order_shipping_methods' => $shippingDetails,
            'order_notes' => $request->input('order_notes'),
        ])->id;

        /** trigger event for system notification */
        $userData = User::select('user_fname', 'user_lname')->where('user_id', $userId)->first();
        $data = NotificationTemplate::getBySlug('order_created_by_admin');
        $message = str_replace('{orderNumber}', $insertId, $data->ntpl_body);
        $message = str_replace('{orderAmount}', formatPriceByCurrencyCode($price, currencyCode()), $message);
        $message = str_replace('{userName}', Str::title($userData->user_fname . ' ' . $userData->user_lname), $message);
        event(new SystemNotification([
            'type' => Notification::ORDER_CREATED_BY_ADMIN,
            'record_id' => $insertId,
            'from_id' => $userId,
            'message' => $message
        ]));

        $products = json_decode($request->input('products'), true);
        $this->saveProducts($insertId, $products, $request);
        $this->saveComments($insertId, $timeLineMessage, OrderMessage::MSG_ORDER_TIMELLINE_TYPE);

        if (json_decode($request->input('tags'), true)) {
            $this->saveTags($request, $insertId);
        }

        $billingAddress = json_decode($request->input('billing-address'), true);

        if (empty($billingAddress['addr_phone_country_id'])) {
            $billingAddress['addr_phone_country_id'] = $billingAddress['phonecountry']['country_id'];
        }

        $this->saveOrderAddress($insertId, $billingAddress, OrderAddress::BILLING_ADDRESS_TYPE);

        if ($request['order-shipping-type'] == 1) {
            $shippingAddress = json_decode($request->input('shipping-address'), true);
            if (empty($shippingAddress['addr_phone_country_id'])) {
                $shippingAddress['addr_phone_country_id'] = $shippingAddress['phonecountry']['country_id'];
            }
            $this->saveOrderAddress($insertId, $shippingAddress, OrderAddress::SHIPPING_ADDRESS_TYPE);
        } else {
            $pickupAddress = json_decode($request->input('selected-pickup-address'), true);

            $address  = [
                'addr_first_name' => $pickupAddress['saddr_name'],
                'addr_last_name'  => '',
                'addr_email' => '',
                'addr_address1' =>  $pickupAddress['saddr_address'],
                'addr_city' => $pickupAddress['saddr_city'],
                'addr_address2' => '',
                'country_name' => $pickupAddress['country_name'],
                'state_name' => $pickupAddress['state_name'],
                'addr_postal_code' => $pickupAddress['saddr_postal_code'],
                'addr_phone' => $pickupAddress['saddr_phone'],
                'addr_phone_country_id' => $pickupAddress['saddr_phone_country_id'],
                'country_code' => ''
            ];

            $this->saveOrderAddress($insertId, $address, OrderAddress::SHIPPING_PICKUP_TYPE);
        }

        $this->orderNotification($insertId, EmailHelper::getEmailData('order_confirmation'));
        if (empty($paymentMethod)) {
            $paymentMethod = 'cash';
        }
        $orderStatus = [];
        $orderStatus['data']['id'] = '';
        if ($request->input('payment_type') == 2 && $request->input('share-link') != "true") {
            $orderStatus = $this->orderPayment($cardDetails, $insertId, $paymentMethod);

            $chargePrice = 0;
            if (empty($orderStatus['status'])) {
                return jsonresponse(false, $orderStatus['message']);
            }
            $chargePrice = $orderStatus['data']['amount'];
            if (round($chargePrice) != round($price)) {
                return jsonresponse(false, __('NOTI_PAYMENT_FAILED'));
            }

            $this->orderUpdate($insertId, Order::PAYMENT_PAID, $chargePrice);

            /** trigger event for system notification */
            $data = NotificationTemplate::getBySlug('order_payment_received');
            $message = str_replace('{orderNumber}', $insertId, $data->ntpl_body);
            $message = str_replace('{orderAmount}', formatPriceByCurrencyCode($price, currencyCode()), $message);
            $message = str_replace('{paymentMethod}', $paymentMethod, $message);
            event(new SystemNotification([
                'type' => Notification::ORDER_PAYMENT,
                'record_id' => $insertId,
                'from_id' => $userId,
                'message' => $message
            ]));
        }
        $this->orderTransaction($insertId, $price, 'Payment received #' . $insertId, $userId, $paymentMethod, $orderStatus['data']['id'], json_encode($orderStatus), Transaction::DEBIT_TYPE);

        $paymentUrl = "";
        if ($request->input('share-link') == "true") {
            $paymentUrl = $this->paymentLink($request, $insertId, true);
        }
        $this->updateProductInventory($products);
        if ($request['order-shipping-status'] != Order::SHIPPING_STATUS_IN_PROGRESS) {
            $this->genrateInvoice($insertId);
        }
        return jsonresponse(true, __('NOTI_ORDER_CREATED'), ['orderId' => $insertId, 'paymentUrl' => $paymentUrl]);
    }
    public function orderUpdate($orderId, $orderStatus, $chargePrice = 0)
    {
        if (!canWrite(AdminRole::ORDERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        Order::where('order_id', $orderId)->update([
            'order_payment_status' => $orderStatus,
            'order_amount_charged' => $chargePrice,
        ]);
    }
    public function orderPayment($request, $orderId, $paymentGateway)
    {
        if (!canWrite(AdminRole::ORDERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $payment = new PaymentGatewayHelper($paymentGateway);
        return $payment->orderPayment($request, $orderId);
    }

    public function saveProducts($orderId, $cartCollection, $request)
    {
        $giftPrice = getConfigValueByName('PRODUCT_GIFT_WRAP_PRICE');

        $ordersLog = [];
        $shippingDetails = json_decode($request->input('shipping'), true);
        foreach ($cartCollection as $cart) {
            $productPrice = priceFormat($cart['price']);
            $variants = '';
            $varientsResults = [];
            if ($cart['pov_code']) {
                $varientsResults['styles'] = ProductOption::optionsByCode($cart['id'], $cart['pov_code']);
                $varientsResults['code'] = $cart['pov_code'];
            }

            if ($cart['giftWrap'] == 1) {
                $varientsResults['gift'] = json_decode($request['gift-message'], true);
            }
            if (count($varientsResults) >  0) {
                $variants  = json_encode($varientsResults);
            }
            $cartFields = 'prod_type, prod_name, cat_tax_code, pov_id, prod_cost, prod_taxcat_id, pc_max_download_times,pc_return_age, pc_upload_additional_files, pc_weight, pc_download_validity_in_days, coalesce(`pov_sku`, `pc_sku`) as sku, ' . Product::getPrice();
            $product = Product::productById($cart['id'], $cartFields, [], $cart['pov_code']);
            $expiredDate = 'null';
            if ($product->prod_type == Product::DIGITAL_PRODUCT) {
                $expiredDate = Carbon::now()->addDays($product->pc_download_validity_in_days);
                DigitalFileRecord::saveAttachedFiles($product->product_id, $orderId, $product->pov_id);
            }
            if ($cart['includeTax'] == 1 && !empty($cart['tax'])) {
                $totalTax = $cart['price'] * $cart['tax'];
                $productPrice = $cart['price'] - $totalTax;
            }
            $isPickup = 0;
            $shipType = "shipping";
            if ($request['order-shipping-type'] == 1) {
                $isPickup = 1;
                $shipType = "pickup";
            }
            $taxInfo = TaxGroupType::getRecordByGroupId($product->prod_taxcat_id);
 
            $orderProductid = OrderProduct::create([
                'op_order_id' => $orderId,
                'op_qty' => $cart['selectedqty'],
                'op_product_id' => $cart['id'],
                'op_is_pickup' => $isPickup,
                'op_product_name' => $product->prod_name,
                'op_product_price' => $productPrice,
                'op_prod_cost' =>  $product->prod_cost,
                'op_product_variants' => $variants,
                'op_pov_id' => $product->pov_id,
                'op_product_type' => $product->prod_type,
                'op_expired_on' => $expiredDate,
                'op_return_age' => $product->pc_return_age,
                'op_product_sku' => $product->pc_sku,
                'op_pov_code' => $cart['pov_code'],
                'op_product_width' => null,
                'op_product_height' => null,
                'op_product_weight' => $product->pc_weight,
            ])->op_id;
            $tax = 0;
            if (!empty($cart['tax'])) {
                $tax = $cart['price'] * $cart['tax'];

                OrderProductCharge::create([
                    'opc_op_id' => $orderProductid,
                    'opc_type' => 'tax',
                    'opc_value' => $tax,
                ]);
            }
            $this->saveTaxLogs($orderId, $orderProductid, $taxInfo, $productPrice, $tax, $cart['selectedqty']);
            $additionInfo = [
                'opainfo_op_id' => $orderProductid,
                'opainfo_cat_tax_code' => $product->cat_tax_code,
                'opainfo_tgtype_rate' => $taxInfo->tgtype_rate,
            ];
            if ($product->prod_type == Product::DIGITAL_PRODUCT) {
                $additionInfo['opainfo_max_download_limit'] = $product->pc_max_download_times;
                $additionInfo['opainfo_upload_additional_files'] = $product->pc_upload_additional_files;
                $additionInfo['opainfo_downloads'] = 0;
            }
            if (isset($shippingDetails['shipping'])) {
                $shippingAmount = $this->calculateShipping($shippingDetails['shipping'], ($cart['pov_code'] != 0 ? $cart['pov_code'] : $cart['id']));
                $additionInfo['opainfo_shipping_amount'] = priceFormat($shippingAmount / $cart['selectedqty'], false);
            }
            if ($request['total-discount']) {
                $discountAmount = $productPrice * (abs($request['total-discount']) * 100 /  $request['order-subtotal']) / 100;
                $additionInfo['opainfo_discount_amount'] = priceFormat($discountAmount, false);
            }

            if ($cart['giftWrap'] == 1) {
                $additionInfo['opainfo_gift_amount'] = $giftPrice;
            }
            $exist = OrderProductAdditionInfo::where('opainfo_op_id', $orderProductid)->count();
            if ($exist) {
                OrderAdditionInfo::where('opainfo_op_id', $orderProductid)->update($additionInfo);
            } else {
                OrderProductAdditionInfo::create($additionInfo);
            }
            $logKey = ($cart['pov_code']) ?? $cart['id'];
            $ordersLog[$logKey] = [
                'id' => $logKey,
                'product_id' => $cart['id'],
                'name' => $product->prod_name,
                'shipType' => $shipType,
                'price' => $productPrice,
                'quantity' => $cart['selectedqty'],
                'attributes' => $varientsResults
            ];
        }
        Order::where('order_id', $orderId)->update([
            'order_cart_data' => serialize($ordersLog)
        ]);
    }
    private function calculateShipping($shippings, $productId)
    {
        foreach ($shippings as $key => $ship) {
            $explode = explode(',', $key);
            if (count($explode) > 1) {
                if (in_array($productId, $explode)) {
                    return last($ship) / count($explode);
                }
            } elseif ($key == $productId) {
                return last($ship);
            }
        }
        return 0;
    }
    private function saveTaxLogs($orderId, $orderProductId, $taxInfo, $productPrice, $taxAmount, $qty)
    {
        if (count($taxInfo->combinedData) > 0) {
            foreach ($taxInfo->combinedData as $combinedData) {
                if ($taxAmount != 0) {
                    $taxAmount = round($productPrice * $combinedData->tgc_rate / 100, 2);
                }
                $taxAmount = $taxAmount * $qty;

                OrderProductTaxLog::create([
                    'optl_order_id' => $orderId,
                    'optl_op_id' => $orderProductId,
                    'optl_key' =>  $combinedData->tgc_name,
                    'optl_value' => $taxAmount,
                ]);
            }
        } else {
            if ($taxAmount != 0) {
                $taxAmount = round($productPrice * $taxInfo->tgtype_rate / 100, 2);
            }
            $taxAmount = $taxAmount * $qty;

            OrderProductTaxLog::create([
                'optl_order_id' => $orderId,
                'optl_op_id' => $orderProductId,
                'optl_key' => $taxInfo->tgtype_name,
                'optl_value' => $taxAmount,
            ]);
        }
    }

    public function saveOrderAddress($orderId, $address, $type)
    {
        OrderAddress::create([
            'oaddr_order_id' => $orderId,
            'oaddr_name' => (isset($address['addr_first_name']) ? $address['addr_first_name'] : '') . ' ' . (isset($address['addr_last_name']) ? $address['addr_last_name'] : ''),
            'oaddr_type' => $type,
            'oaddr_email' => (isset($address['addr_email']) ? $address['addr_email'] : ''),
            'oaddr_address1' => $address['addr_address1'],
            'oaddr_address2' => ($address['addr_address2'] ?? ''),
            'oaddr_city' => $address['addr_city'],
            'oaddr_state' => $address['state_name'],
            'oaddr_country' => $address['country_name'],
            'oaddr_country_code' => $address['country_code'],
            'oaddr_postal_code' => $address['addr_postal_code'],
            'oaddr_phone' => $address['addr_phone'],
            'oaddr_phone_country_id' => $address['addr_phone_country_id'],
        ]);
    }


    public function updateProductInventory($cartData)
    {
        foreach ($cartData as $data) {
            $qty = $data['selectedqty'];
            if ($data['pov_code']) {
                $varientCode = $data['pov_code'];
                if (substr($varientCode, -1) != "|") {
                    $varientCode = $varientCode . '|';
                }
                $varient = ProductOptionVarient::select('pov_quantity')->where('pov_code', $varientCode)->first();
                if ($varient->pov_quantity < $qty) {
                    $qty = 0;
                }
                $varient->where('pov_code', $varientCode)->decrement('pov_quantity', $qty);
            } else {
                $product = Product::select('prod_stock')->where('prod_id', $data['id'])->first();
                if ($product->prod_stock < $qty) {
                    $qty = 0;
                }
                $product->where('prod_id', $data['id'])->decrement('prod_stock', $qty);
            }
            Product::where('prod_id', $data['id'])->update(['prod_sold_count' => $qty]);
            Product::checkIfProductLowStock($data['id']);
        }
    }

    public function downloadPackingSlip(Request $request, $orderId)
    {
        $order = Order::getRecordById($orderId);
        $deliveryAddress = $order->address->where('oaddr_type', 2)->first();

        $phone = '';
        $name = '';
        if ($deliveryAddress) {
            $phone = $deliveryAddress->oaddr_phone;
            $name = $deliveryAddress->oaddr_name;
        }
        $data['addressTypes'] = OrderAddress::ADDRESS_TYPES;
        $data['shippingStatus'] = Order::SHIPPING_STATUS;
        $data['orderStatus'] = Order::ORDER_STATUS;
        $configValues = Configuration::getKeyValues(['PACKING_SLIP_HTML', 'BUSINESS_NAME', 'BUSINESS_ADDRESS1', 'BUSINESS_ADDRESS2', 'BUSINESS_CITY', 'BUSINESS_COUNTRY', 'BUSINESS_EMAIL', 'BUSINESS_PINCODE', 'BUSINESS_STATE']);
        $configValues['PACKING_SLIP_HTML'] = str_replace('{name}', $name, $configValues['PACKING_SLIP_HTML']);
        $configValues['PACKING_SLIP_HTML'] = str_replace('{phone}', $phone, $configValues['PACKING_SLIP_HTML']);
        $configValues['PACKING_SLIP_HTML'] = str_replace('{orderId}', $order->order_id, $configValues['PACKING_SLIP_HTML']);
        $configValues['PACKING_SLIP_HTML'] = str_replace('{orderDate}', $order->order_date_updated, $configValues['PACKING_SLIP_HTML']);

        /** Bussiness Information */

        $configValues['PACKING_SLIP_HTML'] = str_replace('{businessName}', $configValues['BUSINESS_NAME'], $configValues['PACKING_SLIP_HTML']);
        $configValues['PACKING_SLIP_HTML'] = str_replace('{businessEmail}', $configValues['BUSINESS_EMAIL'], $configValues['PACKING_SLIP_HTML']);
        $businessAddress = '';
        if (isset($configValues['BUSINESS_ADDRESS1']) && !empty($configValues['BUSINESS_ADDRESS1'])) {
            $businessAddress .= $configValues['BUSINESS_ADDRESS1'] . ', ';
        }
        if (isset($configValues['BUSINESS_ADDRESS2']) && !empty($configValues['BUSINESS_ADDRESS2'])) {
            $businessAddress .= $configValues['BUSINESS_ADDRESS2'] . ', ';
        }
        if (isset($configValues['BUSINESS_CITY']) && !empty($configValues['BUSINESS_CITY'])) {
            $businessAddress .= $configValues['BUSINESS_CITY'];
        }
        $configValues['PACKING_SLIP_HTML'] = str_replace('{businessAddress}', $businessAddress, $configValues['PACKING_SLIP_HTML']);
        $theme = Theme::getActiveTheme();
        $themeSlug = !empty($theme->theme_slug) ? $theme->theme_slug : config('constants.THEME');
        /** Bussiness Information */
        $products = $order->products;
        $productList = view('themes.' . $themeSlug . '.packingSlip.productList', compact('products'))->render();
        $configValues['PACKING_SLIP_HTML'] = str_replace('{productList}', $productList, $configValues['PACKING_SLIP_HTML']);
        $configValues['PACKING_SLIP_HTML'] = str_replace('{logo}', getFileUrl('frontendLogo', 0, 0, 'thumb'), $configValues['PACKING_SLIP_HTML']);

        $billingAddress = $order->address->where('oaddr_type', OrderAddress::BILLING_ADDRESS_TYPE)->first();

        $billing = '';
        if (isset($billingAddress) && !empty($billingAddress)) {
            $billing .= $billingAddress->oaddr_address1 . ', ';

            if (!empty($billingAddress->oaddr_address2)) {
                $billing .= $billingAddress->oaddr_address2 . ', ';
            }
            $billing .= $billingAddress->oaddr_city . ', ';
            $billing .= $billingAddress->oaddr_state . ', ';
            $billing .= $billingAddress->oaddr_country;
        }
        $configValues['PACKING_SLIP_HTML'] = str_replace('{billingAddress}', $billing, $configValues['PACKING_SLIP_HTML']);
        $delivery = '';
        if (isset($deliveryAddress) && !empty($deliveryAddress)) {
            $delivery .= $deliveryAddress->oaddr_address1 . ', ';

            if (!empty($deliveryAddress->oaddr_address2)) {
                $delivery .= $deliveryAddress->oaddr_address2 . ', ';
            }
            $delivery .= $deliveryAddress->oaddr_city . ', ';
            $delivery .= $deliveryAddress->oaddr_state . ', ';
            $delivery .= $deliveryAddress->oaddr_country;

            $configValues['PACKING_SLIP_HTML'] = str_replace('{deliveryAddress}', $delivery, $configValues['PACKING_SLIP_HTML']);
            $configValues['PACKING_SLIP_HTML'] = str_replace('{pinCode}', $deliveryAddress->oaddr_postal_code, $configValues['PACKING_SLIP_HTML']);
        }

        $pickupAddress = $order->address->where('oaddr_type', OrderAddress::SHIPPING_PICKUP_TYPE)->first();
        $pickup = '';
        if (isset($pickupAddress) && !empty($pickupAddress)) {
            $pickup .= $pickupAddress->oaddr_address1 . ', ';
            if (isset($pickupAddress->oaddr_address2) && !empty($pickupAddress->oaddr_address2)) {
                $pickup .= $pickupAddress->oaddr_address2 . ', ';
            }
            $pickup .= $pickupAddress->oaddr_city . ', ';
            $pickup .= $pickupAddress->oaddr_state . ', ';
            $pickup .= $pickupAddress->oaddr_country;

            $configValues['PACKING_SLIP_HTML'] = str_replace('{deliveryAddress}', $pickup, $configValues['PACKING_SLIP_HTML']);
            $configValues['PACKING_SLIP_HTML'] = str_replace('Delivery Address', 'Pickup Address', $configValues['PACKING_SLIP_HTML']);
            $configValues['PACKING_SLIP_HTML'] = str_replace('{pinCode}', $pickupAddress->oaddr_postal_code, $configValues['PACKING_SLIP_HTML']);
        }
        return view('themes.' . $themeSlug . '.packingSlip.index', compact('configValues'));
    }

    public function export(Request $request)
    {
        return Excel::download(new OrderExport, 'orders.xlsx');
    }
    public function paymentLink(Request $request, $orderId = 0, $returnUrl = false)
    {
        $paymentGateways = Package::getActivePaymentGateways('paymentGateways')->where('pkg_card_type', 1)->first();
        if (empty($paymentGateways)) {
            return jsonresponse(false, __('NOTI_PAYMENT_GATEWAY_NOT_ACTIVATED'));
        }
        $type = $request->input('type');

        if ($orderId == 0) {
            $orderId = $request->input('order-id');
        }
        $paymentGateway = '-' . $paymentGateways->pkg_slug;
        $encrypted =  Crypt::encryptString(config('app.salt') . '' . $orderId . '' . $paymentGateway);
        $shortUrl = UrlShortener::saveUrl('/payment/' . $encrypted);
        $url = url('/' . $shortUrl);
        // $url = url('/payment/' . $encrypted);
        $message = __("NOTI_URL_COPIED");
        if ($type == "email") {
            $email = $this->sentPaymentUrlByEmail($orderId, $url);
            $message = __("NOTI_SMS_SENT_ON") . " " . $email;
        } elseif ($type == "sms") {
            $number = $this->sentPaymentUrlBySms($orderId, $url);
            $message = __("NOTI_SMS_SENT_ON") . " " . $number;
        }
        $order = Order::getRecordById($orderId);


        sendPushNotification($order->order_user_id, 'cod_payment_link', ['order_id' => $orderId, 'link' => $url, 'order_amount' => formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code)]);

        if ($returnUrl == true) {
            return $url;
        }
        return jsonresponse(true, __($message), $url);
    }
    private function sentPaymentUrlByEmail($orderId, $url)
    {
        $address = OrderAddress::where('oaddr_order_id', $orderId)->where('oaddr_type', OrderAddress::BILLING_ADDRESS_TYPE)->first();
        $order = Order::getRecordById($orderId);
        if ($address) {
            $data = EmailHelper::getEmailData('cod_payment_link');
            $priority = $data['body']->etpl_priority;
            $subject = $data['body']->etpl_subject;
            $shipAddress = $order->address->where('oaddr_type', OrderAddress::SHIPPING_ADDRESS_TYPE)->first();
            $billAddress = $order->address->where('oaddr_type', OrderAddress::BILLING_ADDRESS_TYPE)->first();
            $data['subject'] = $this->replacementTags($data['body']->etpl_subject, $order, $order->products, $shipAddress, $billAddress, false);
            $data['body'] = $this->replacementTags($data['body']->etpl_body, $order, $order->products, $shipAddress, $billAddress, true);
            $data['body'] = str_replace('{paymentLink}', $url, $data['body']);
            $data['to'] = $address->oaddr_email;
            dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);
            return $address->oaddr_email;
        }
    }
    private function sentPaymentUrlBySms($orderId, $url)
    {
        $address = OrderAddress::where('oaddr_order_id', $orderId)->where('oaddr_type', OrderAddress::BILLING_ADDRESS_TYPE)->first();
        $order = Order::getRecordById($orderId);
        if ($address) {
            $country = Country::select('country_phonecode')->where('country_id', $address->oaddr_phone_country_id)->first();
            $data = SmsTemplate::getSMSTemplate('cod_payment_link');
            $priority = $data['body']->stpl_priority;
            $data['body'] = str_replace('{name}', $address->oaddr_name, $data['body']->stpl_body);
            $data['body'] = str_replace('{url}', $url, $data['body']);
            $data['body'] = str_replace('{orderAmount}', formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code), $data['body']);
            $data['body'] = str_replace('{orderNumber}', $orderId, $data['body']);
            $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
            $notificationData['sms'] = [
                'message' => $data['body'],
                'recipients' => array('+' . $country->country_phonecode . $address->oaddr_phone)
            ];
            dispatch(new SendNotification($notificationData, false, false, true))->onQueue($priority);
            return $address->oaddr_phone;
        }
    }
    public function pageLoadData()
    {
        $config = Configuration::getRecordsByPrefix('PRODUCT_GIFT_WRAP');
        $data = [
            'shipstatus' => Order::SHIPPING_STATUS,
            'pickupStatus' => Order::PICKUP_STATUS,
            'giftPrice' => $config['PRODUCT_GIFT_WRAP_PRICE'],
            'gWarpEnable' => $config['PRODUCT_GIFT_WRAP_ENABLE'],
            'lastOrderId' => (Order::max('order_id') ? Order::max('order_id') : 0),
        ];
        return jsonresponse(true, null, $data);
    }
    public function requestDetails($id)
    {
        $order = Order::getRecordById($id);
        $reasonType = Reason::CANCELLATION;
        if ($order->order_shipping_status == Order::SHIPPING_STATUS_DELIVERED) {
            $reasonType = Reason::RETURN;
        }
        $type =  Reason::TYPE[$reasonType];
        $retrunReasons = Reason::where(['reason_type' => $reasonType, 'reason_publish' => 1])->get();
        $returnShipping = getConfigValueByName('RETURN_SHIPPING_ENABLE');
        $data['order'] = $order;
        $data['returnShipping'] = $returnShipping;
        $data['orderReturnStatus'] = OrderReturnRequest::REQUEST_STATUS;
        $data['retrunReasons'] = $retrunReasons;
        $data['reasonType'] = $type;
        $data['productDiscountCount'] = $order->products->whereNotNull('opainfo_discount_amount')->count();
        return jsonresponse(true, null, $data);
    }
    public function generateRequest(Request $request)
    {
        $orderId = $request->input('order-id');
        $refundStatus = OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED;
        $order = Order::getRecordById($orderId);

        $gatewayType = $order->paymentslug;
        $transactionId = "";
        if ($order->transaction) {
            $transactionId = $order->transaction->txn_gateway_transaction_id;
            if (!in_array($order->transaction->txn_gateway_type, ['cash', 'card'])) {
                $gatewayType = $order->transaction->txn_gateway_type;
            }
        }

        $type = Reason::CANCELLATION;
        if ($order->order_shipping_status == Order::SHIPPING_STATUS_DELIVERED) {
            $type = Reason::RETURN;
        }
        $items = json_decode($request->input('items'), true);
        $refundAmount = [];
        $x = 1;
        $comment = $request->input('comment');
        foreach ($items as $item) {
            $product = $order->products->where('op_id', $item['id'])->first();
            if ($item['reason'] != "others") {
                $item['reason'] = Reason::where('reason_id', $item['reason'])->first()->reason_title;
            }
            $orrequest = OrderReturnRequest::create([
                'orrequest_user_id' => $order->user_id,
                'orrequest_op_id' => $item['id'],
                'orrequest_order_id' => $orderId,
                'orrequest_qty' => $item['qty'],
                'orrequest_type' => $type,
                'orrequest_date' => Carbon::now(),
                'orrequest_order_status' => isOrderPaid($order->payment_status) ? 1 : 0,
                'orrequest_status' => $refundStatus,
                'orrequest_txn_gateway_transaction_id' =>  $transactionId,
                'orrequest_reason' => $item['reason'],
                'orrequest_comment' =>  $comment,
            ]);
            $orrequestId = $orrequest->orrequest_id;
            $orrequestData = $orrequest->fresh();

            $shipping = 0;
            if ($x == count($order->products) && $request->input('shipping') != 0) {
                $shipping = $request->input('shipping');
            }
            $additionInfoMessage =  '';
            if ($comment) {
                $additionInfoMessage =  '<p> <span class="text-bold">Addition Information:</span> ' . $comment . '</p>';
            }
            $refundAmount = $this->calculateYKRefundableAmount($shipping, $order, $product, $item, $orrequestId, $refundStatus, $comment, $orrequestData);

            $this->updateReturnInventory($product, $item['qty'], $orrequestId, $order->user_id);
            if (!in_array($gatewayType, ['cash', 'cod', 'rewards']) && $refundAmount != 0 && $order->payment_status == 2) {
                $orderReturnStatus = $this->paymentRefund($gatewayType, $transactionId, $refundAmount, $order->order_currency_code);
                if ($orderReturnStatus['status'] != 1) {
                    return jsonresponse(false, $orderReturnStatus['message']);
                }
                $refundTransactionId = $orderReturnStatus['data']['id'];
            } else {
                $refundTransactionId  = rand(0, 6);
                $orderReturnStatus = [
                    'status' => true,
                    'data' => [
                        'amount' => $refundAmount,
                        'id' => $refundTransactionId
                    ]
                ];
            }
            $this->orderTransaction($orderId, $refundAmount, __('LBL_PAYMENT_REFUNDED').' #' . $orderId, $order->user_id, $gatewayType, $refundTransactionId, json_encode($orderReturnStatus), Transaction::CREDIT_TYPE, $orrequestId);
            $transMessage = '<p><span class="text-bold">'.__('LBL_TRANSACTION_DETAILS').': </span>' . $refundTransactionId. '</p>';
            $commentId =  $this->saveComments($orrequestId, __('LBL_REFUND_PROCESS'), OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE);
            $this->saveComments($orrequestId, '<p><span class="text-bold"> '.__('LBL_REASON').':</span>' . $item['reason'] . '</p>' . $additionInfoMessage . '' . $transMessage, OrderMessage::MSG_ORDER_RETURN_TIMELLINE_TYPE, $commentId);

            $orderCommentId = $this->saveComments($orderId, 'Refund processed for request ID #' . $orrequestId, OrderMessage::MSG_ORDER_TIMELLINE_TYPE);


            $orderMessage = "Canceled " . $product->op_product_name . " with request ID #" . $orrequestId . '<p> <span class="text-bold"> '.__('LBL_REASON').':</span>' . $item['reason'] . '</p>' . $additionInfoMessage . '' . $transMessage;
            $this->saveComments($orderId, $orderMessage, OrderMessage::MSG_ORDER_TIMELLINE_TYPE, $orderCommentId);
            $x++;
        }

        $orderStatus  = Order::ORDER_STATUS_PARTIAL_RETURNED;

        $oldRequests = OrderReturnRequest::where('orrequest_order_id', $orderId)->pluck('orrequest_qty', 'orrequest_op_id')->toArray();

        if ($order->products->count() == count($oldRequests)) {
            $orderedProducts = $order->products->pluck('op_qty', 'op_id')->toArray();
            if ($orderedProducts == $oldRequests) {
                $orderStatus  = Order::ORDER_STATUS_RETURNED;
            };
        }

        Order::where('order_id', $orderId)->update(['order_status' => $orderStatus]);

        $this->setOrderCompleted($orderId);

        return jsonresponse(true, __('NOTI_REQUEST_SUBMITTED'));
    }

    public function ordersExport(Request $request)
    {
        if (!canWrite(AdminRole::PRODUCTS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }

        $result = Order::exportOrders($request->all());
        try {
            if ($request['type'] == 1 || $request['type'] == 2) {
                $heading = [
                    'First Name',
                    'Last Name',
                    'Phone Number',
                    'Order Id',
                    'order amount',
                    OrderReturnRequest::TYPE[$request['type']] . ' Id',
                    OrderReturnRequest::TYPE[$request['type']] . ' Product',
                    OrderReturnRequest::TYPE[$request['type']] . ' Quanity',
                    OrderReturnRequest::TYPE[$request['type']] . ' Amount',
                    OrderReturnRequest::TYPE[$request['type']] . ' Date',
                    OrderReturnRequest::TYPE[$request['type']] . ' Reason',
                    OrderReturnRequest::TYPE[$request['type']] . ' Comment',
                ];
            } else {
                $heading = [
                    'First Name',
                    'Last Name',
                    'Phone Number',
                    'Order Id',
                    'order amount',
                    'Payment Mode',
                    'order Notes',
                    'Billing Address',
                    'Shipping Address',
                    'Order Date'
                ];
            }
            $export = new OrderExport($result['orders'], $heading);
            $name = 'orders-' . time() . '.xls';
            Excel::store($export, 'public/excel/' . $name);
        } catch (Exception $e) {
            return jsonresponse(true, $e->getMessage());
        }
        return jsonresponse(true, __('NOTI_ORDERS_EXPORTED'), url('storage/excel/' . $name));
    }
    public function invoiceExport(Request $request)
    {
        $results = Order::exportInvoices($request->all());

        $zipFileName = 'invoice.zip';
        $txtFiles = [];
        $zip = new ZipArchive;
        if ($zip->open(public_path() . '/' . $zipFileName, ZipArchive::CREATE) === true) {
            foreach ($results as $result) {
                $pdf = PDF::loadHTML($result->oinv_content);
                $filename = $result->oinv_number . '.pdf';
                $pdf->save('public/' . $filename);
                $destinationPath = public_path('/' . $filename);
                $txtFiles[] = $destinationPath;
                $zip->addFile($destinationPath, $filename);
            }
        }
        $zip->close();
        if (count($txtFiles) > 0) {
            File::delete($txtFiles);
        }

        $headers = array(
            'Content-Type' => 'application/pdf',
        );
        return jsonresponse(true, __('NOTI_ORDERS_EXPORTED'), url('/public/' . $zipFileName));
    }

    private function updateReturnInventory($product, $qty, $requestId, $userId)
    {
        if (!empty($product->rewards)) {
            $rewardAmount = $product->rewards->opc_value;
            if ($product->op_qty != $qty) {
                $rewardAmount = round($rewardAmount / $qty);
            }
            UserRewardPoint::redeemRewardPoints($userId, $product->op_order_id, $rewardAmount, UserRewardPoint::ORDER_REQUEST_REFUND_POINT, $requestId);
        }
        $productId = $product->op_product_id;
        $varientCode = $product->op_pov_code;
        Product::where('prod_id', $productId)->decrement('prod_sold_count', $qty);
        if ($varientCode != "0") {
            ProductOptionVarient::where('pov_code', $varientCode)->increment('pov_quantity', $qty);
        } else {
            Product::where('prod_id', $productId)->increment('prod_stock', $qty);
        }
    }
    private function calculateYKRefundableAmount($shipping, $order, $product, $item, $requestId, $refundStatus, $comment = "", $orrequestData)
    {
        $tax = 0;
        if (!empty($product) && !empty($product->tax)) {
            $tax = $product->tax->opc_value *  $item['qty'];
        }

        $discountCoupon = 0;
        if (!empty($order->order_discount_amount)) {
            $discountCoupon =  $product->opainfo_discount_amount  * $item['qty'];
        }
        $refundableAmount = (($product->op_product_price * $item['qty'] + $tax + $shipping) - $discountCoupon);


        $refundAmount = [
            'oramount_orrequest_id' => $requestId,
            'oramount_op_id' => $item['id'],
            'oramount_tax' => $tax,
            'oramount_shipping' => $shipping,
            'oramount_discount' => $discountCoupon,
            'oramount_giftwrap_price' => 0,
            'oramount_payment_status' => ($refundStatus == OrderReturnRequest::RETURN_REQUEST_STATUS_PENDING) ? OrderReturnAmount::PENDING : OrderReturnAmount::PAID,
        ];
        if (!empty($order->order_gift_amount) && !empty($product->opainfo_gift_amount)) {
            $giftPrice =  $product->opainfo_gift_amount * $item['qty'];
            if ($order->order_shipping_status == Order::SHIPPING_STATUS_IN_PROGRESS) {
                $refundableAmount = $refundableAmount + $giftPrice;
                $refundAmount['oramount_giftwrap_price']  = $giftPrice;
            } else {
                $refundAmount['oramount_giftwrap_price']  = '-' . $giftPrice;
            }
        }
        $rewardAmount = 0;
        $rewardPrice = 0;
        if ($order->order_reward_amount != 0) {
            $rewardPrice =  ((($order->order_net_amount * $product->opainfo_reward_rate) / 100) / $product->op_qty) * $item['qty'];
            $rewardAmount = ($refundableAmount - $rewardPrice > 0) ? $refundableAmount - $rewardPrice : 0;
            $refundableAmount = $rewardPrice;
        }
        $refundAmount['oramount_reward_price'] = round($rewardAmount, 2);

        $orderReturnRequest = OrderReturnAmount::create($refundAmount);
        $orderReturnRequest = $orderReturnRequest->fresh();

        $this->sendAdminCancelledEmailToBuyer($order, $orderReturnRequest, $product, $orrequestData, $comment);

        return priceFormat($refundableAmount, false);
    }



    public function validateInvoiceByNumber(Request $request)
    {
        $exist = OrderInvoice::where('oinv_number', 'LIKE', '%' . $request->input('taxNumber') . '%')->count();
        return jsonresponse(true, null, $exist);
    }
    public function orderRequests(Request $request, $requestType, $id)
    {
        $data['order_total'] = OrderProduct::getRequestsByUserId($id, $requestType, true);
        $data['orders'] = OrderProduct::getRequestsByUserId($id, $requestType);
        return jsonresponse(true, null, $data);
    }
    public function digitalAdditionalUpload(Request $request)
    {
        $records = OrderProduct::getDigitalAdditionalInfo($request->input('order-id'));
        return jsonresponse(true, null, $records);
    }
    public function uploadDigitalFile(Request $request)
    {
        $uploadedFileName = FileHelper::uploadDigitalFiles($request->file('file'));
        $response = AttachedFile::saveFile(AttachedFile::getConstantVal('digitalFiles'), $request->input('order_id'), $uploadedFileName, $request->file('file')->getClientOriginalName(), $request->input('op_id'));

        DigitalFileRecord::create([
            'dfr_file_type' => 4,
            'dfr_record_id' => $request->input('op_id'),
            'dfr_subrecord_id' => $request->input('product_id'),
            'dfr_record_type' => DigitalFileRecord::ORDERED_PRODUCT_RECORD_TYPE,
            'dfr_afile_id' => $response,
            'dfr_name' => 'file',
            'dfr_url' => ''
        ]);

        return jsonresponse(true, __('NOTI_FILE_UPLOADED'), $response);
    }
    public function orderNotification($orderId, $data, $order = '', $products = [], $summaryCalc = false, $pickupSmsSlug = '')
    {
        if (empty($pickupSmsSlug)) {
            $smsdata = SmsTemplate::getSMSTemplate($data['body']->etpl_slug);
        } else {
            $smsdata = SmsTemplate::getSMSTemplate($pickupSmsSlug);
        }
        
        $notificationData = [];
        $sendSms = false;
        if ($order == '') {
            $order = Order::getRecordById($orderId);
            $products = $order->products;
        }
        $shipAddress = $order->address->where('oaddr_type', OrderAddress::SHIPPING_ADDRESS_TYPE)->first();
        $billAddress = $order->address->where('oaddr_type', OrderAddress::BILLING_ADDRESS_TYPE)->first();
        $priority = $data['body']->etpl_priority;
        $data['subject'] = $this->replacementTags($data['body']->etpl_subject, $order, $products, $shipAddress, $billAddress, $summaryCalc);

        $data['body']->etpl_body = EmailTemplate::graphicByOrderStatus($data['body'], $order->order_shipping_type);

        $data['body'] = $this->replacementTags($data['body']->etpl_body, $order, $products, $shipAddress, $billAddress, $summaryCalc);

        $data['to'] =  ($billAddress->oaddr_email) ?? '';
        $notificationData['email'] = $data;
        if (!empty($billAddress->oaddr_phone) && !empty($billAddress->oaddr_phone_country_id) && !empty($smsdata['body']->stpl_slug)) {
            $country = Country::select('country_phonecode')->where('country_id', $billAddress->oaddr_phone_country_id)->first();

            $smsdata['body'] = str_replace('{name}', $billAddress->oaddr_name, $smsdata['body']->stpl_body);
            $smsdata['body'] = str_replace('{number}', $order->order_id, $smsdata['body']);
            $smsdata['body'] = str_replace('{websiteName}', env('APP_NAME'), $smsdata['body']);
            $smsdata['body'] = str_replace('{amount}', formatPriceByCurrencyCode($order->order_net_amount, $order->order_currency_code), $smsdata['body']);
            $notificationData['sms'] = [
                'message' => $smsdata['body'],
                'recipients' => array('+' . $country->country_phonecode . $billAddress->oaddr_phone)
            ];
            $sendSms = true;
        }
        dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);
    }
    public function replacementTags($type, $order, $products, $shipAddress, $billAddress, $summaryCalc)
    {
        $orderProducts = view('emails.order-products', compact('products'))->render();
                
        $billingAddress = '';
        if (!empty($billAddress)) {
            $country = Country::getCountries(['country_phonecode'], ['country_id' => $billAddress->oaddr_phone_country_id])->first();
            $phoneCode = !empty($country->country_phonecode) ? '+' . $country->country_phonecode . ' ' : '';
            
            $billingAddress = '<strong>' . $billAddress->oaddr_name . '</strong> <br>' . $billAddress->oaddr_address1 . ', ' . $billAddress->oaddr_address2 . '<br>' . $billAddress->oaddr_city . ',' . $billAddress->oaddr_state . '<br>' .  $billAddress->oaddr_country . '<br>' . $phoneCode . $billAddress->oaddr_phone;
        }
        $shippingAddress = '';
        if (!empty($shipAddress)) {
            $country = Country::getCountries(['country_phonecode'], ['country_id' => $shipAddress->oaddr_phone_country_id])->first();
            $phoneCode = !empty($country->country_phonecode) ? '+' . $country->country_phonecode . ' ' : '';

            $shippingAddress = '<strong>' . $shipAddress->oaddr_name . '</strong> <br>' . $shipAddress->oaddr_address1 . ', ' . $shipAddress->oaddr_address2 . '<br>' . $shipAddress->oaddr_city . ',' . $shipAddress->oaddr_state . '<br>' .  $shipAddress->oaddr_country . '<br>' . $phoneCode . $shipAddress->oaddr_phone;
        }
        $pickupAddress = "";
        $pickAddress = $order->address->where('oaddr_type', OrderAddress::SHIPPING_PICKUP_TYPE)->first();
        if (!empty($pickAddress)) {
            $country = Country::getCountries(['country_phonecode'], ['country_id' => $pickAddress->oaddr_phone_country_id])->first();
            $phoneCode = !empty($country->country_phonecode) ? '+' . $country->country_phonecode . ' ' : '';

            $pickupAddress = '<strong>' . $pickAddress->oaddr_name . '</strong> <br>' . $pickAddress->oaddr_address1 . ', ' . $pickAddress->oaddr_address2 . '<br>' . $pickAddress->oaddr_city . ',' . $pickAddress->oaddr_state . '<br>' .  $pickAddress->oaddr_country . '<br>' . $phoneCode . $pickAddress->oaddr_phone;
        }

        $orderType = '';
        if ($order->order_shipping_type == Order::ORDER_SHIPPING) {
            $orderType = 'shipping';
        } elseif ($order->order_shipping_type == Order::ORDER_PICKUP) {
            $orderType = 'pickup';
        }
        
        if (isset($order->products) && isset($order->digital_products) && count($order->products) == $order->digital_products) {
            $orderType = 'digital';
        }
        
        $shippingPrice = 0;
        $taxPrice = 0;
        $discountPrice = 0;
        $rewardPrice = 0;
        $giftPrice = 0;
        if ($summaryCalc == true) {
            $summary = $this->calculateOrderSummary($order, $products);
            $shippingPrice = $summary['shipping'];
            $taxPrice = $summary['tax'];
            $discountPrice = $summary['discount'];
            $rewardPrice = $summary['reward'];
            $giftPrice = $summary['giftPrice'];
            $subTotal = $summary['subTotal'];
            $total = $summary['total'];
        } else {
            if (!empty($order->order_shipping_value)) {
                $shippingPrice = $order->order_shipping_value;
            }

            if (!empty($order->order_tax_charged)) {
                $taxPrice = $order->order_tax_charged;
            }

            if (!empty($order->order_discount_amount)) {
                $discountPrice = $order->order_discount_amount;
            }
            if (!empty($order->order_reward_amount)) {
                $rewardPrice = $order->order_reward_amount;
            }
            if (!empty($order->order_gift_amount)) {
                $giftPrice =  $order->order_gift_amount;
            }

            $subTotal = $order->order_net_amount - $taxPrice - $shippingPrice - $giftPrice + $discountPrice + $rewardPrice;
            $total = $order->order_net_amount;
        }

        $calculatedRefund = $this->calculateCancelReturn($order, $products);
        
        if (count($calculatedRefund) > 0) {
            $subTotal = $subTotal - array_sum($calculatedRefund['subtotal']);
            $taxPrice = $taxPrice - array_sum($calculatedRefund['tax']);
            $shippingPrice = $shippingPrice - array_sum($calculatedRefund['shipping']);
            $discountPrice = $discountPrice - array_sum($calculatedRefund['discount']);
            $rewardPrice = $rewardPrice - array_sum($calculatedRefund['rewardprice']);
            $giftPrice = $giftPrice - abs(array_sum($calculatedRefund['giftwrap_amount']));
            $refundtotal = array_sum($calculatedRefund['subtotal']) + array_sum($calculatedRefund['tax']) + array_sum($calculatedRefund['shipping']) - array_sum($calculatedRefund['discount']) - array_sum($calculatedRefund['rewardprice']);

            if (array_sum($calculatedRefund['giftwrap_amount']) != 0) {
                if (array_sum($calculatedRefund['giftwrap_amount']) < 0) {
                    $refundtotal = $refundtotal - abs(array_sum($calculatedRefund['giftwrap_amount']));
                } else {
                    $refundtotal = $refundtotal + array_sum($calculatedRefund['giftwrap_amount']);
                }
            }
            $total = $total - $refundtotal;
        }

        $type = str_replace('{number}', $order->order_id, $type);
        $type = str_replace('{websiteName}', env('APP_NAME'), $type);
        $type = str_replace('{amount}', $order->currency_symbol . '' .  $total, $type);
        $type = str_replace('{date}', getConvertedDateTime($order->order_date_added), $type);
        $type = str_replace('{paymentMethod}', $order->order_payment_method, $type);
        $type = str_replace('{orderProductListing}', $orderProducts, $type);
        $type = str_replace('{subtotal}', $order->currency_symbol . '' . $subTotal, $type);
        $type = str_replace('{tax}', $order->currency_symbol . '' . $taxPrice, $type);
        $type = str_replace('{shipping}', $order->currency_symbol . '' . $shippingPrice, $type);

        $discountCoupon = !empty($order->order_discount_coupon_code) ? '<br>(' . $order->order_discount_coupon_code . ')' : '';
        $type = str_replace('{discount}', '-' . $order->currency_symbol . '' . $discountPrice . $discountCoupon, $type);

        $type = str_replace('{giftWrapPrice}', $order->currency_symbol . '' . $giftPrice, $type);
        $type = str_replace('{rewardPrice}', '-' . $order->currency_symbol . '' . $rewardPrice, $type);
        $type = str_replace('{total}', $order->currency_symbol . '' . $total, $type);
        $shippingMethods = json_decode($order->order_shipping_methods, true);
        if (isset($shippingMethods['pick_ups']) && !empty($shippingMethods['pick_ups'])) {
            $pickupDateTime = $shippingMethods['pick_ups']['pickup_date'] . ' ' . $shippingMethods['pick_ups']['pickup_time'];
        } else {
            $pickupDateTime = '';
        }
        $orderAddresses = view('emails.order-addresses', compact('shippingAddress', 'billingAddress', 'pickupAddress', 'orderType', 'pickupDateTime'))->render();

        $type = str_replace('{orderAddresses}', $orderAddresses, $type);
        // $type = str_replace('{shippingAddress}', $shippingAddress, $type);
        // $type = str_replace('{billingAddress}', $billingAddress, $type);
        return $type;
    }

    public function getTrackingInformation(Request $request)
    {
        $trackShippingPackage = Package::getActivePackage('trackShipping');
        if (isset($trackShippingPackage->pkg_slug) && !empty($trackShippingPackage->pkg_slug)) {
            $trackingApi = new TrackingApiHelper($trackShippingPackage->pkg_slug);
            $data = $trackingApi->getTrackingInfo($request->input('tracking_number'), $request->input('courier_name'), $request->input('order_id'));
            return jsonresponse(true, '', $data);
        }
        return jsonresponse(true, '', []);
    }

    private function calculateOrderSummary($order, $products)
    {
        $totalAmount = [];
        $totalGiftPrice = [];
        $totalDiscount = [];
        $totalReward = [];
        $totalTax = [];
        $subTotal = [];
        $totalShipping = [];
        foreach ($products as $product) {
            $tax = 0;
            if (!empty($product->tax)) {
                $tax = $product->tax->opc_value *  $product->op_qty;
            }
            $shipping = 0;
            if (!empty($order->order_shipping_value)) {
                $shipping =  $order->order_shipping_value / count($order->products);
            }

            $discountCoupon = 0;
            if (!empty($order->order_discount_amount)) {
                if ($order->products->whereNotNull('opainfo_discount_amount')->count() > 0 && !empty($product->opainfo_discount_amount)) {
                    $discountCoupon =  $product->opainfo_discount_amount;
                } elseif ($order->products->whereNotNull('opainfo_discount_amount')->count() == 0) {
                    $discountCoupon = $order->order_discount_amount / $order->products->count() / $product->op_qty;
                }
            }
            if ($discountCoupon !=  0) {
                $discountCoupon =  $discountCoupon * $product->op_qty;
            }
            $rewardPrice = 0;
            if ($order->order_reward_amount != 0) {
                $rewardPrice =  ($order->order_reward_amount / $order->products->count() / $product->op_qty) * $product->op_qty;
            }
            $giftPrice = 0;
            if ($product->opainfo_gift_amount != 0) {
                $giftPrice = $product->opainfo_gift_amount;
            }
            $totalTax[] = $tax;
            $totalReward[] = $rewardPrice;
            $totalDiscount[] = $discountCoupon;
            $totalShipping[] = $shipping;
            $totalGiftPrice[] = $giftPrice;
            $totalAmount[] = ($product->op_product_price * $product->op_qty) + $tax + $shipping + $giftPrice - $discountCoupon - $rewardPrice;
            $subTotal[] = $product->op_product_price * $product->op_qty;
        }
        return  [
            'subTotal' => array_sum($subTotal),
            'tax' => array_sum($totalTax),
            'shipping' => array_sum($totalShipping),
            'reward' => array_sum($totalReward),
            'giftPrice' => array_sum($totalGiftPrice),
            'discount' => array_sum($totalDiscount),
            'total' => array_sum($totalAmount),
        ];
    }
    private function calculateCancelReturn($order, $products)
    {
        $calculatedRefund = [];
        foreach ($products as $product) {
            if ($product->cancelRequest && $product->cancelRequest->orrequest_status == 2) {
                $calculatedRefund['subtotal'][] = $product->op_product_price *
                $product->cancelRequest->orrequest_qty;
                $calculatedRefund['tax'][] = $product->cancelRequest->oramount_tax;
                $calculatedRefund['shipping'][] = $product->cancelRequest->oramount_shipping;
                $calculatedRefund['discount'][] = $product->cancelRequest->oramount_discount;
                $calculatedRefund['giftwrap_amount'][] = $product->cancelRequest->oramount_giftwrap_price;
                $calculatedRefund['rewardprice'][] = $product->cancelRequest->oramount_reward_price;
            }
        }
        return $calculatedRefund;
    }
}
