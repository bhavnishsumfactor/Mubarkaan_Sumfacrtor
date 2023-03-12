<?php

namespace App\Listeners;

use App\Events\Event;
use App\Events\OrderDelivered;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\OrderAddress;
use App\Helpers\EmailHelper;
use App\Models\OrderMessage;
use App\Models\Admin\Admin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderDeliveredNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SystemNotification $event
     * @return void
     */
    public function handle(OrderDelivered $event)
    {
        $orderDetails = Order::getRecordById($event->data);
        
        if (
            $orderDetails->order_shipping_status == Order::SHIPPING_STATUS_IN_PROGRESS || 
            $orderDetails->order_shipping_status == Order::SHIPPING_STATUS_READY_FOR_SHIPPING || 
            $orderDetails->order_shipping_status == Order::SHIPPING_STATUS_SHIPPED || 
            $orderDetails->order_shipping_status != Order::SHIPPING_STATUS_DELIVERED
        ) {
            $this->sendNotification($orderDetails->order_id, EmailHelper::getEmailData('physical_order_delivered'), $orderDetails, $orderDetails->products);
            if ($orderDetails->payment_status == Order::PAYMENT_PENDING) {
                $response = [
                    'status' => true,
                        'data' => [
                            'amount' => $orderDetails->order_net_amount,
                            'id' => '3423423423'
                        ]
                    ];
                Transaction::createTransaction( $orderDetails->order_id, $orderDetails->order_net_amount, 'Paid', $orderDetails->order_user_id, 'Cash', '3423423423', json_encode($response), Transaction::DEBIT_TYPE);
            }
            $superAdminId = Admin::getSuperAdminId();
            OrderMessage::createComment($orderDetails->order_id, 'Delivered', OrderMessage::MSG_ORDER_ACTION_TYPE, 0, $superAdminId);
            Order::where('order_id', $event->data)->update(['order_shipping_status'=> Order::SHIPPING_STATUS_DELIVERED ]);
        }
    }

    private function sendNotification($orderId, $data, $order = '', $products = [], $summaryCalc = false)
    {
        if ($order == '') {
            $order = Order::getRecordById($orderId);
            $products = $order->products;
        }
        $shipAddress = $order->address->where('oaddr_type', OrderAddress::SHIPPING_ADDRESS_TYPE)->first();
        $billAddress = $order->address->where('oaddr_type', OrderAddress::BILLING_ADDRESS_TYPE)->first();
        //$priority = $data['body']->etpl_priority;
        //$data['subject'] = $this->replacementTags($data['body']->etpl_subject, $order, $products, $shipAddress, $billAddress, $summaryCalc);
        //$data['body'] = $this->replacementTags($data['body']->etpl_body, $order, $products, $shipAddress, $billAddress, $summaryCalc);

        //$data['to'] =  ($billAddress->oaddr_email) ?? '';
        //dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);
    }

    private function replacementTags($type, $order, $products, $shipAddress, $billAddress, $summaryCalc)
    {
        $orderProducts = view('emails.order-products', compact('products'))->render();

        $billingAddress = '';
        if (!empty($billAddress)) {
            $billingAddress = '<strong>' . $billAddress->oaddr_name . '</strong> <br>' . $billAddress->oaddr_address1 . ', ' . $billAddress->oaddr_address2 . '<br>' . $billAddress->oaddr_city . ',' . $billAddress->oaddr_state . '<br>' .  $billAddress->oaddr_country . '<br>' . $billAddress->oaddr_phone;
        }
        $shippingAddress = '';
        if (!empty($shipAddress)) {
            $shippingAddress = '<strong>' . $shipAddress->oaddr_name . '</strong> <br>' . $shipAddress->oaddr_address1 . ', ' . $shipAddress->oaddr_address2 . '<br>' . $shipAddress->oaddr_city . ',' . $shipAddress->oaddr_state . '<br>' .  $shipAddress->oaddr_country . '<br>' . $shipAddress->oaddr_phone;
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

        $type = str_replace('{number}', $order->order_id, $type);
        $type = str_replace('{websiteName}', env('APP_NAME'), $type);
        $type = str_replace('{amount}', $order->currency_symbol . '' .  $total, $type);
        $type = str_replace('{date}', getConvertedDateTime($order->order_date_added), $type);
        $type = str_replace('{paymentMethod}', $order->order_payment_method, $type);
        $type = str_replace('{orderProductListing}', $orderProducts, $type);
        $type = str_replace('{subtotal}', $order->currency_symbol . '' . $subTotal, $type);
        $type = str_replace('{tax}', $order->currency_symbol . '' . $taxPrice, $type);
        $type = str_replace('{shipping}', $order->currency_symbol . '' . $shippingPrice, $type);
        $type = str_replace('{discount}', $order->currency_symbol . '' . $discountPrice, $type);
        $type = str_replace('{giftWrapPrice}', $order->currency_symbol . '' . $giftPrice, $type);
        $type = str_replace('{rewardPrice}', $order->currency_symbol . '' . $rewardPrice, $type);
        $type = str_replace('{total}', $order->currency_symbol . '' . $total, $type);
        $type = str_replace('{shippingAddress}', $shippingAddress, $type);
        $type = str_replace('{billingAddress}', $billingAddress, $type);
        return $type;
    }

    private function calculateOrderSummary($order, $products)
    {
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
}
