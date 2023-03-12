<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\OrderMessage;

class OrderComplete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'complete order status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $orders = Order::select('order_id')->with('products')->where('order_shipping_status', Order::SHIPPING_STATUS_DELIVERED)->get();
        if (isset($orders) && $orders->count() > 0) {
            foreach ($orders as $order) {
                $returnNotAvailable = true;
                foreach ($order->products as $product) {
                    $returnData = productReturnVerify($orders->order_shipping_status, $order->order_date_added, $product);
                    if ($returnData['status']) {
                        $returnNotAvailable = false;
                        break;
                    }
                }
                if ($returnNotAvailable) {
                    Order::where('order_id', $order->order_id)->update(['order_status' => Order::ORDER_STATUS_COMPLETED ]);
                    OrderMessage::createComment($order->order_id, "Marked completed by system", Order::MSG_ORDER_ACTION_TYPE);
                }
            }
        }
    }
}
