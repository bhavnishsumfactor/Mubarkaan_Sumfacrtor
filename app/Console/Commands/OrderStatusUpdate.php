<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Package;
use App\Helpers\TrackingApiHelper;
use App\Models\Order;

class OrderStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orderstatus:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update order status via tracking api';

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
        //echo 'cron started<br>';
        $orders = Order::select('order_id')->where('order_shipping_status', '!=', Order::SHIPPING_STATUS_DELIVERED)->where('order_status', '!=', Order::ORDER_STATUS_COMPLETED)->with('additionInfo')->get()->toArray();
        $trackingPackage = Package::getActivePackage('trackShipping');
        if (isset($trackingPackage->pkg_slug) && !empty($trackingPackage->pkg_slug)) {
            $package = new TrackingApiHelper($trackingPackage->pkg_slug);
            if (count($orders) > 0) {
                foreach ($orders as $order) {
                    if (isset($order->addition_info) && !empty($order->addition_info) ) {
                        $package->getTrackingInfo($order->addition_info->oainfo_tracking_id, $order->addition_info->oainfo_courier_name, $order->order_id);
                    }
                }
            }
        }
        // echo 'cron finished<br>';
    }
}
