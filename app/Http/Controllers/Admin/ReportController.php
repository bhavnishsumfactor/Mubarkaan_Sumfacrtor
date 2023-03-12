<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Admin\Report;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Search;
use App\Models\OrderProductCharge;
use App\Models\OrderProductAdditionInfo;
use App\Models\OrderProductTaxLog;
use App\Models\OrderReturnRequest;
use DB;
use App\Http\Controllers\YokartController;
use Analytics;
use Spatie\Analytics\Period;
use App\Models\User;
use Spatie\Analytics\Exceptions\InvalidConfiguration;
use App\Models\Configuration;
use Illuminate\Support\Arr;

use Storage;

class ReportController extends YokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
    }
    /***
    *  Sale Report Functions
    *
    */
    public function getSaleReports(Request $request) 
    {
        switch ($request->input('type')) {
        case "saleOverTime":
            $data = $this->getSaleReportOverTime($request);
            break;
        case "saleReportByProduct":
            $data = $this->getSaleReportByProduct($request);
            break;
        case "saleReportByProductVariant":
            $data = $this->getSaleReportByProductVariant($request);
            break;
        case "saleReportByCustomerName":
            $data = $this->getSaleReportByCustomerName($request);
            break;
        case "saleReportAvergeOrderTime":
            $data = $this->getSaleReportAvergeOrderTime($request);
            break;
        case "saleReportTrafficRefferals":
            $data = $this->getSaleTrafficRefferals($request);
            break;
        }
        return jsonresponse(true, '', $data);
    }

    private function getSaleReportOverTime($request)
    {
        $thiryDaysDate = Report::getLastThiryDayDates($request->all());
        $endDateCarbon = $thiryDaysDate['startDateCarbon'];
        $startDateCarbon = $thiryDaysDate['endDateCarbon'];
        $labels = [];
        $data   = [];
        while ($startDateCarbon->gte($endDateCarbon)) {
            $labels[] = $startDateCarbon->format('d M');
            $dates[] = $startDateCarbon->toDateString();
            $startDateCarbon->subDay();
        }
        $totalOrders = [];

        $orderProduct = Order::select(['order_id','order_user_id', 'order_shipping_value AS shipping_charge', 'order_tax_charged AS tax_charge', 'order_net_amount AS total_sale','order_discount_amount AS discount_amount', DB::raw('DATE_FORMAT(order_date_added, "%Y-%m-%d") AS day')])
        ->addSelect([
            'gross_sale' => OrderProduct::selectRaw('(SUM(op_product_price*op_qty)) as total')->whereColumn('op_order_id', 'order_id')
            ->groupBy('op_order_id')
        ])
        ->whereBetween('order_date_added', [$thiryDaysDate['startDate']." 00:00:00", $thiryDaysDate['endDate']." 23:59:59"])
        ->get()->toArray();
        if(count($orderProduct) > 0) {
            foreach($orderProduct as $key=> $order) {
                if (!array_key_exists($order['day'], $totalOrders)) {
                    $totalOrders[$order['day']]['order_count'] = 1;
                    $totalOrders[$order['day']]['gross_sale'] = priceFormat($order['gross_sale'], false);
                    $totalOrders[$order['day']]['discount_amount'] = priceFormat($order['discount_amount'], false);
                    // return 
                    $totalOrders[$order['day']]['net_sale'] = priceFormat(($order['gross_sale'] - $order['discount_amount']),false);
                    $totalOrders[$order['day']]['shipping_charge'] = priceFormat($order['shipping_charge'],false);
                    $totalOrders[$order['day']]['tax_charge'] = priceFormat($order['tax_charge'],false);
                    //totalSale
                } else {                    
                    $totalOrders[$order['day']]['order_count'] +=1;
                    $totalOrders[$order['day']]['gross_sale'] += priceFormat($order['gross_sale'],false);
                    $totalOrders[$order['day']]['discount_amount'] += priceFormat($order['discount_amount'],false);
                    // return 
                    $totalOrders[$order['day']]['net_sale'] += priceFormat(($order['gross_sale'] - $order['discount_amount']),false);
                    $totalOrders[$order['day']]['shipping_charge'] += priceFormat($order['shipping_charge'],false);
                    $totalOrders[$order['day']]['tax_charge'] += priceFormat($order['tax_charge'],false);
                    //totalSale
                }
                $returnOrderData = OrderProduct::getRequestsByOrderId($order['order_id']);
                $orderReturnAmount = ($returnOrderData->order_net_amount ? $returnOrderData->order_net_amount : 0);
                $orderReturnDate = ($returnOrderData->orrequest_date ? $returnOrderData->orrequest_date : 0);
                    if((!array_key_exists($orderReturnDate, $totalOrders)) && ($orderReturnDate != 0 && $orderReturnAmount != 0)) {
                        $totalOrders[$orderReturnDate]['return_amount'] =  priceFormat($orderReturnAmount,false);
                        $totalOrders[$orderReturnDate]['net_sale'] = ((isset($totalOrders[$orderReturnDate]['net_sale']) ? priceFormat($totalOrders[$orderReturnDate]['net_sale'],false) : 0) - $orderReturnAmount);
                        $totalOrders[$orderReturnDate]['order_count'] = ((isset($totalOrders[$orderReturnDate]['order_count'])) ?  $totalOrders[$orderReturnDate]['order_count'] : 0);
                        $totalOrders[$orderReturnDate]['gross_sale'] = ((isset($totalOrders[$orderReturnDate]['gross_sale'])) ?  priceFormat($totalOrders[$orderReturnDate]['gross_sale'],false) : 0);
                        $totalOrders[$orderReturnDate]['shipping_charge'] = ((isset($totalOrders[$orderReturnDate]['shipping_charge'])) ?  priceFormat(($totalOrders[$orderReturnDate]['shipping_charge'] - $returnOrderData->oramount_shipping),false) : - priceFormat($returnOrderData->oramount_shipping,false));
                        $totalOrders[$orderReturnDate]['tax_charge'] = ((isset($totalOrders[$orderReturnDate]['tax_charge'])) ?  priceFormat(($totalOrders[$orderReturnDate]['tax_charge'] - $returnOrderData->oramount_tax),false) : - priceFormat($returnOrderData->oramount_tax,false));
                        $totalOrders[$orderReturnDate]['total_sale'] = ((isset($totalOrders[$orderReturnDate]['total_sale'])) ?  priceFormat($totalOrders[$orderReturnDate]['total_sale'], false) : 0);
                        $totalOrders[$orderReturnDate]['discount_amount'] = ((isset($totalOrders[$orderReturnDate]['discount_amount'])) ?  priceFormat($totalOrders[$orderReturnDate]['discount_amount'], false) : 0);
                    } else {
                        $totalOrders[$orderReturnDate]['return_amount'] = (isset($totalOrders[$orderReturnDate]['return_amount']) ? priceFormat($totalOrders[$orderReturnDate]['return_amount'], false) : 0) + priceFormat($orderReturnAmount,false);
                        $totalOrders[$orderReturnDate]['net_sale'] = ((isset($totalOrders[$orderReturnDate]['net_sale']) ? priceFormat($totalOrders[$orderReturnDate]['net_sale'],false) : 0) - priceFormat($orderReturnAmount,false));
                        $totalOrders[$orderReturnDate]['order_count'] = ((isset($totalOrders[$orderReturnDate]['order_count'])) ?  $totalOrders[$orderReturnDate]['order_count'] : 0);
                        $totalOrders[$orderReturnDate]['gross_sale'] = ((isset($totalOrders[$orderReturnDate]['gross_sale'])) ?  priceFormat($totalOrders[$orderReturnDate]['gross_sale'],false) : 0);
                        $totalOrders[$orderReturnDate]['shipping_charge'] = ((isset($totalOrders[$orderReturnDate]['shipping_charge'])) ?  priceFormat(($totalOrders[$orderReturnDate]['shipping_charge'] - $returnOrderData->oramount_shipping),false) : 0);
                        $totalOrders[$orderReturnDate]['tax_charge'] = ((isset($totalOrders[$orderReturnDate]['tax_charge'])) ?  priceFormat(($totalOrders[$orderReturnDate]['tax_charge'] - $returnOrderData->oramount_tax),false) : 0);
                        $totalOrders[$orderReturnDate]['total_sale'] = ((isset($totalOrders[$orderReturnDate]['total_sale'])) ?  priceFormat($totalOrders[$orderReturnDate]['total_sale'],false) : 0);
                        $totalOrders[$orderReturnDate]['discount_amount'] = ((isset($totalOrders[$orderReturnDate]['discount_amount'])) ?  priceFormat($totalOrders[$orderReturnDate]['discount_amount'],false) : 0);
                    }

                $totalOrders[$order['day']]['total_sale'] = priceFormat(($totalOrders[$order['day']]['net_sale'] + $totalOrders[$order['day']]['shipping_charge'] + $totalOrders[$order['day']]['tax_charge']),false);
            }
        }

        foreach ($dates as  $key => $date) {
            if (array_key_exists($date, $totalOrders)) {
                $data[$key] = $totalOrders[$date];
                $data[$key]['day'] = $labels[$key];
                $series[$key] = isset($totalOrders[$date]['total_sale']) ? $totalOrders[$date]['total_sale'] : 0;
            } else {
                $data[$key]['order_count'] = $data[$key]['gross_sale'] = $data[$key]['discount_amount'] = $data[$key]['return_amount'] = $data[$key]['net_sale'] = $data[$key]['shipping_charge'] = $data[$key]['tax_charge'] = $data[$key]['total_sale'] = $series[$key] = 0;
                $data[$key]['day'] = $labels[$key];
            }
        }

        $saleOverTimedata['data'] = $data;
        $saleOverTimedata['labels'] = $labels;
        $saleOverTimedata['series'] = $series;
        $saleOverTimedata['dateRange'][0]  = $thiryDaysDate['startDate'];
        $saleOverTimedata['dateRange'][1]  = $thiryDaysDate['endDate'];
        return $saleOverTimedata;
    }

    private  function getSaleReportByProduct(Request $request)
    {
        $thiryDaysDate = Report::getLastThiryDayDates($request->all());
        $data   = [];
        $totalProducts = [];
        $query = Order::
        select([
            'order_id',
            'op_id',
            'op_order_id',
            'op_product_id',
            'op_product_name',
            'op_product_type',
            'op_product_price',
            'op_qty',
            'order_shipping_value AS shipping_charge',
            'order_tax_charged AS tax_charge',
            DB::raw('coalesce(SUM(op_qty), 0)  AS order_net_quantity'),
            DB::raw('SUM(op_product_price*op_qty) AS gross_sale')
        ])
        ->leftjoin('order_products', 'op_order_id', 'order_id')
        ->whereBetween('order_date_added', [$thiryDaysDate['startDate']." 00:00:00", $thiryDaysDate['endDate']." 23:59:59"]);

        if ($request->input('search') != '' && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where('op_product_name', 'LIKE', '%'.$search.'%');
        }
        $orderProducts = $query->groupBy('op_order_id','op_product_variants','op_product_id')->get()->toArray();
        if(count($orderProducts) > 0) {
            foreach($orderProducts as $key => $order) {
                if(!empty($order['op_id'])) {
                    if(!array_key_exists($order['op_product_id'], $totalProducts)) {
                        $totalProducts[$order['op_product_id']]['op_product_name'] = $order['op_product_name'];
                        $totalProducts[$order['op_product_id']]['gross_sale'] = priceFormat($order['gross_sale'],false);
                        $totalProducts[$order['op_product_id']]['op_product_type'] = $order['op_product_type'];
                        $totalProducts[$order['op_product_id']]['order_net_quantity'] = $order['order_net_quantity'];
                        $totalProducts[$order['op_product_id']]['op_product_type'] = $order['op_product_type'];
                        $orderProductTaxCharge = OrderProductTaxLog::calculateTax($order['op_id']);
                        if(isset($orderProductTaxCharge) && !empty($orderProductTaxCharge)) {
                            $totalProducts[$order['op_product_id']]['tax_charge'] =  priceFormat($orderProductTaxCharge->optl_value,false);
                        } else {
                            $totalProducts[$order['op_product_id']]['tax_charge'] = 0;
                        }
                        
                        //$totalProducts[$order['op_product_id']]['tax_charge'] = $order['tax_charge'];
                        $shippingCharges = OrderProductAdditionInfo::getProductShippingCharges($order['op_id']);
                        $totalProducts[$order['op_product_id']]['shipping_charge'] =  (!empty($shippingCharges->opainfo_shipping_amount) ? priceFormat($shippingCharges->opainfo_shipping_amount, false) : 0 );
    
                        $productReturnData = OrderReturnRequest::getReturnDataOrderProductId($order['op_id'], $order['op_product_price']);
                        if(isset($productReturnData['price']) && isset($productReturnData['quantity'])) {
                            $totalProducts[$order['op_product_id']]['return_amount'] =  priceFormat($productReturnData['price'],false);
                            $totalProducts[$order['op_product_id']]['order_net_quantity'] = ($totalProducts[$order['op_product_id']]['order_net_quantity'] - $productReturnData['quantity']);
                            $totalProducts[$order['op_product_id']]['tax_charge'] = priceFormat(($totalProducts[$order['op_product_id']]['tax_charge'] - $productReturnData['tax']),false);
                            $totalProducts[$order['op_product_id']]['shipping_charge'] = priceFormat(($totalProducts[$order['op_product_id']]['shipping_charge'] - $productReturnData['shipping']),false);
                        } else {
                            $totalProducts[$order['op_product_id']]['return_amount'] = 0 ;
                        }
                    } else {
                        $totalProducts[$order['op_product_id']]['gross_sale'] += priceFormat($order['gross_sale'],false);
                        $totalProducts[$order['op_product_id']]['order_net_quantity'] += $order['order_net_quantity'];
                        $orderProductTaxCharge = OrderProductTaxLog::calculateTax($order['op_id']);
                        if(isset($orderProductTaxCharge) && !empty($orderProductTaxCharge)) {
                            $totalProducts[$order['op_product_id']]['tax_charge'] +=  priceFormat($orderProductTaxCharge->optl_value,false);
                        } else {
                            $totalProducts[$order['op_product_id']]['tax_charge'] += 0;
                        }
                        $productReturnData = OrderReturnRequest::getReturnDataOrderProductId($order['op_id'], $order['op_product_price']);
                        if(isset($productReturnData['price']) && isset($productReturnData['quantity'])) {
                            $totalProducts[$order['op_product_id']]['return_amount'] += priceFormat($productReturnData['price'], false);
                            $totalProducts[$order['op_product_id']]['order_net_quantity'] = ($totalProducts[$order['op_product_id']]['order_net_quantity'] - $productReturnData['quantity']);
                            $totalProducts[$order['op_product_id']]['tax_charge'] = priceFormat($totalProducts[$order['op_product_id']]['tax_charge'],false) - priceFormat($productReturnData['tax'],false);
                            $totalProducts[$order['op_product_id']]['shipping_charge'] = priceFormat(($totalProducts[$order['op_product_id']]['shipping_charge'] - $productReturnData['shipping']),false);
                        }
                        $shippingCharges = OrderProductAdditionInfo::getProductShippingCharges($order['op_id']);
                        $totalProducts[$order['op_product_id']]['shipping_charge'] +=  (!empty($shippingCharges->opainfo_shipping_amount)) ? priceFormat(($shippingCharges->opainfo_shipping_amount * $order['op_qty']),false) : 0;
    
                    }
                    $totalProducts[$order['op_product_id']]['net_sale'] = priceFormat(($totalProducts[$order['op_product_id']]['gross_sale'] - $totalProducts[$order['op_product_id']]['return_amount']),false);
    
                    $totalProducts[$order['op_product_id']]['total_sale'] = priceFormat(($totalProducts[$order['op_product_id']]['net_sale'] + $totalProducts[$order['op_product_id']]['tax_charge'] + $totalProducts[$order['op_product_id']]['shipping_charge']),false);
                }
            }
        }
        $data['totalProducts'] = $totalProducts;
        $data['summary'] = [];
        $data['dateRange'][0]  = $thiryDaysDate['startDate'];
        $data['dateRange'][1]  = $thiryDaysDate['endDate'];
        $data['types'] = Product::getTypes();
        return $data;
    }

    private function getSaleReportByProductVariant(Request $request)
    {
        $thiryDaysDate = Report::getLastThiryDayDates($request->all());
        $totalProducts = [];
        $query = Order::
        select([
            'order_id',
            'op_id',
            'op_order_id',
            'op_product_id',
            'op_product_name',
            'op_product_type',
            'op_product_price',
            'op_pov_code',
            'op_product_variants',
            'op_product_sku',
            'op_qty',
            DB::raw('coalesce(SUM(op_qty), 0)  AS order_net_quantity'),
            DB::raw('SUM(op_product_price*op_qty) AS gross_sale')
        ])
        ->leftjoin('order_products', 'op_order_id', 'order_id')
        ->whereBetween('order_date_added', [$thiryDaysDate['startDate']." 00:00:00", $thiryDaysDate['endDate']." 23:59:59"]);

        if ($request->input('search') != '' && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where('op_product_name', 'LIKE', '%'.$search.'%');
        }
        $orderProducts = $query->groupBy('op_order_id','op_product_variants','op_product_id')->get()->toArray();
        if(count($orderProducts) > 0) {
            foreach($orderProducts as $key => $order) {
                if(!empty($order['op_id'])) {
                    $productVariantCode = $this->getProductIdWithVariantCode($order['op_product_id'], $order['op_pov_code']);
                    if(!array_key_exists($productVariantCode, $totalProducts)) {
                        $totalProducts[$productVariantCode]['op_product_name'] = $order['op_product_name'];
                        $totalProducts[$productVariantCode]['gross_sale'] = priceFormat($order['gross_sale'],false);
                        $totalProducts[$productVariantCode]['op_product_type'] = $order['op_product_type'];
                        $totalProducts[$productVariantCode]['order_net_quantity'] = $order['order_net_quantity'];
                        $totalProducts[$productVariantCode]['op_product_type'] = $order['op_product_type'];
                        $totalProducts[$productVariantCode]['tax_charge'] =  priceFormat(OrderProductCharge::calculateTax($order['op_id'], $order['op_qty']), false);
                        $totalProducts[$productVariantCode]['op_product_variants'] = $order['op_product_variants'];
                        $totalProducts[$productVariantCode]['op_product_sku'] = $order['op_product_sku'];
                        $shippingCharges = OrderProductAdditionInfo::getProductShippingCharges($order['op_id']);
                        $totalProducts[$productVariantCode]['shipping_charge'] =  (!empty($shippingCharges->opainfo_shipping_amount) ? priceFormat($shippingCharges->opainfo_shipping_amount, false) : 0 );

                        $productReturnData = OrderReturnRequest::getReturnDataOrderProductId($order['op_id'], $order['op_product_price']);
                        if(isset($productReturnData['price']) && isset($productReturnData['quantity'])) {
                            $totalProducts[$productVariantCode]['return_amount'] = priceFormat($productReturnData['price'], false);
                            $totalProducts[$productVariantCode]['order_net_quantity'] = ($totalProducts[$productVariantCode]['order_net_quantity'] - $productReturnData['quantity']);
                            $totalProducts[$productVariantCode]['tax_charge'] = (priceFormat($totalProducts[$productVariantCode]['tax_charge'],false) - priceFormat($productReturnData['tax'],false));
                            $totalProducts[$productVariantCode]['shipping_charge'] = priceFormat(($totalProducts[$productVariantCode]['shipping_charge'] - $productReturnData['shipping']),false);
                        } else {
                            $totalProducts[$productVariantCode]['return_amount'] = 0 ;
                        }
                        
                        
                    } else {
                        $totalProducts[$productVariantCode]['gross_sale'] += priceFormat($order['gross_sale'], false);
                        $totalProducts[$productVariantCode]['order_net_quantity'] += $order['order_net_quantity'];
                        $totalProducts[$productVariantCode]['tax_charge'] +=  priceFormat(OrderProductCharge::calculateTax($order['op_id'], $order['op_qty']), false);
                        $productReturnData = OrderReturnRequest::getReturnDataOrderProductId($order['op_id'], $order['op_product_price']);
                        if(isset($productReturnData['price']) && isset($productReturnData['quantity'])) {
                            $totalProducts[$productVariantCode]['return_amount'] = priceFormat($totalProducts[$productVariantCode]['return_amount'],false) + priceFormat($productReturnData['price'],false);
                            $totalProducts[$productVariantCode]['order_net_quantity'] = ($totalProducts[$productVariantCode]['order_net_quantity'] - $productReturnData['quantity']);
                            $totalProducts[$productVariantCode]['tax_charge'] = priceFormat(($totalProducts[$productVariantCode]['tax_charge'] - $productReturnData['tax']),false);
                            $totalProducts[$productVariantCode]['shipping_charge'] = priceFormat(($totalProducts[$productVariantCode]['shipping_charge'] - $productReturnData['shipping']),false);
                        }
                        $shippingCharges = OrderProductAdditionInfo::getProductShippingCharges($order['op_id']);
                        $totalProducts[$productVariantCode]['shipping_charge'] += (!empty($shippingCharges->opainfo_shipping_amount) ? priceFormat($shippingCharges->opainfo_shipping_amount, false) : 0 );
                    }
                    $totalProducts[$productVariantCode]['net_sale'] = priceFormat(($totalProducts[$productVariantCode]['gross_sale'] - $totalProducts[$productVariantCode]['return_amount']),false);
                    $totalProducts[$productVariantCode]['total_sale'] = priceFormat(($totalProducts[$productVariantCode]['net_sale'] + $totalProducts[$productVariantCode]['tax_charge'] + $totalProducts[$productVariantCode]['shipping_charge']),false);
                }
            }
        }
        $data['totalProducts'] = $totalProducts;
        $data['summary'] = [];
        $data['dateRange'][0]  = $thiryDaysDate['startDate'];
        $data['dateRange'][1]  = $thiryDaysDate['endDate'];
        $data['types'] = Product::getTypes();
        return $data;
    }

    private function getProductIdWithVariantCode($prodId, $povCode)
    {
        if($povCode != 0){
            $povCode = substr($povCode, 0, -1);
            $povCode = str_replace('|', '_', $povCode);
        }
        return $prodId.'_'.$povCode;
    }

    private function getSaleReportByCustomerName(Request $request)
    {
        $thiryDaysDate = Report::getLastThiryDayDates($request->all());
        $totalOrders = [];
        $query = Order::select([
                        'order_id',
                        'order_user_id',
                        'user_email',
                        'order_discount_amount',
                        'order_tax_charged',
                        'order_shipping_value',
                        DB::raw('CONCAT(user_fname, " ", user_lname) as user_username'),
                    ])->addSelect([
                        'gross_sale' => OrderProduct::selectRaw('(SUM(op_product_price*op_qty)) as total')
                        ->whereColumn('op_order_id', 'order_id')
                        ->groupBy('op_order_id')
                    ])
                    ->join('users', 'order_user_id', 'user_id')
                    ->whereBetween('order_date_added', [$thiryDaysDate['startDate']." 00:00:00", $thiryDaysDate['endDate']." 23:59:59"]);
        if ($request->input('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function($subQuery) use ($search) {
                $subQuery->where('user_fname', 'LIKE', '%'.$search.'%')
                        ->orWhere('user_lname', 'LIKE', '%'.$search.'%')
                        ->orWhere('user_email', 'LIKE', '%'.$search.'%');
            });
        }        
        $totalCustomerOrders = $query->get()->toArray();
        if(count($totalCustomerOrders) > 0) {
            foreach($totalCustomerOrders as $key=>$customer) {
                $returnData = OrderProduct::getRequestsByOrderId($customer['order_id']);
                if (isset($returnData->order_net_amount) && !empty($returnData->order_net_amount)) {
                    $returnAmount = $returnData->order_net_amount;
                    $returnTax = $returnData->oramount_tax;
                    $returnShipping = $returnData->oramount_shipping;
                } else {
                    $returnAmount = $returnTax = $returnShipping= 0;
                }
                if (!array_key_exists($customer['order_user_id'], $totalOrders)) {
                    $totalOrders[$customer['order_user_id']]['user_username'] = $customer['user_username'];
                    $totalOrders[$customer['order_user_id']]['user_email'] = $customer['user_email'];
                    $totalOrders[$customer['order_user_id']]['gross_sale'] = $customer['gross_sale'];
                    $totalOrders[$customer['order_user_id']]['order_count'] = 1;
                    $totalOrders[$customer['order_user_id']]['return_amount'] = $returnAmount;
                    $totalOrders[$customer['order_user_id']]['total_sale'] = ((isset($totalOrders[$customer['order_user_id']]['total_sale'])) ?  $totalOrders[$customer['order_user_id']]['total_sale'] : 0);
                    $totalOrders[$customer['order_user_id']]['net_sale'] = ($customer['gross_sale'] - ($customer['order_discount_amount'] + $returnAmount));
                    $totalOrders[$customer['order_user_id']]['order_tax_charged'] = ($customer['order_tax_charged']);
                    $totalOrders[$customer['order_user_id']]['order_shipping_value'] = ($customer['order_shipping_value']);
                } else {
                    $totalOrders[$customer['order_user_id']]['gross_sale'] += $customer['gross_sale'];
                    $totalOrders[$customer['order_user_id']]['order_count'] += 1;
                    $totalOrders[$customer['order_user_id']]['return_amount'] += $returnAmount;
                    $totalOrders[$customer['order_user_id']]['net_sale'] += ($customer['gross_sale'] - ($customer['order_discount_amount'] + $returnAmount));
                    $totalOrders[$customer['order_user_id']]['total_sale'] = ((isset($totalOrders[$customer['order_user_id']]['total_sale'])) ?  $totalOrders[$customer['order_user_id']]['total_sale'] : 0);
                    $totalOrders[$customer['order_user_id']]['order_tax_charged'] += $customer['order_tax_charged'];
                    $totalOrders[$customer['order_user_id']]['order_shipping_value'] += $customer['order_shipping_value'];
                }
                if ($returnTax != 0 || $returnShipping != 0) {
                    $totalOrders[$customer['order_user_id']]['order_tax_charged'] = ($totalOrders[$customer['order_user_id']]['order_tax_charged'] - $returnTax);
                    $totalOrders[$customer['order_user_id']]['order_shipping_value'] = ($totalOrders[$customer['order_user_id']]['order_shipping_value'] - $returnShipping);
                }
                $totalOrders[$customer['order_user_id']]['total_sale'] = priceFormat(($totalOrders[$customer['order_user_id']]['net_sale'] + $totalOrders[$customer['order_user_id']]['order_tax_charged'] + $totalOrders[$customer['order_user_id']]['order_shipping_value']),false);
            }
        }

        $data['totalCustomer'] = $totalOrders;
        $data['summary'] = [];
        $data['dateRange'][0]  = $thiryDaysDate['startDate'];
        $data['dateRange'][1]  = $thiryDaysDate['endDate'];
        return $data;
    }

    private function getSaleReportAvergeOrderTime(Request $request)
    {
        $thiryDaysDate = Report::getLastThiryDayDates($request->all());
        $endDateCarbon = $thiryDaysDate['startDateCarbon'];
        $startDateCarbon = $thiryDaysDate['endDateCarbon'];
        $labels = [];
        $data   = [];
        while ($startDateCarbon->gte($endDateCarbon)) {
            $labels[] = $startDateCarbon->format('d M');
            $dates[] = $startDateCarbon->toDateString();
            $startDateCarbon->subDay();
        }

        $totalOrders = [];

        $orderProduct = Order::select(['order_id','order_user_id', 'order_shipping_value AS shipping_charge', 'order_tax_charged AS tax_charge', 'order_net_amount AS total_sale','order_discount_amount AS discount_amount', DB::raw('DATE_FORMAT(order_date_added, "%Y-%m-%d") AS day')])
        ->addSelect([
            'gross_sale' => OrderProduct::selectRaw('(SUM(op_product_price*op_qty)) as total')->whereColumn('op_order_id', 'order_id')
            ->groupBy('op_order_id')
        ])
        ->addSelect([
            'total_qty' => OrderProduct::selectRaw('(SUM(op_qty)) as total_qty')->whereColumn('op_order_id', 'order_id')
            ->groupBy('op_order_id')
        ])
        ->whereBetween('order_date_added', [$thiryDaysDate['startDate']." 00:00:00", $thiryDaysDate['endDate']." 23:59:59"])
        ->get()->toArray();
        if(count($orderProduct) > 0) {
            foreach($orderProduct as $key=> $order) {
                $orderReturnQuantity = OrderReturnRequest::getReturnQuantityByOrderId($order['order_id']);
                if (!array_key_exists($order['day'], $totalOrders)) {
                    if ($orderReturnQuantity->quantity != $order['total_qty']) {
                        $totalOrders[$order['day']]['order_count'] = 1;
                    }else {
                        $totalOrders[$order['day']]['order_count'] =0;
                    }
                    
                    $totalOrders[$order['day']]['net_sale'] = ($order['gross_sale'] - $order['discount_amount']);
                    $totalOrders[$order['day']]['shipping_charge'] = $order['shipping_charge'];
                    $totalOrders[$order['day']]['tax_charge'] = $order['tax_charge'];
                } else {
                    if ($orderReturnQuantity->quantity != $order['total_qty']) {
                        $totalOrders[$order['day']]['order_count'] +=1;
                    } else {
                        $totalOrders[$order['day']]['order_count'] +=0;
                    }
                    $totalOrders[$order['day']]['net_sale'] += ($order['gross_sale'] - $order['discount_amount']);
                    $totalOrders[$order['day']]['shipping_charge'] += $order['shipping_charge'];
                    $totalOrders[$order['day']]['tax_charge'] += $order['tax_charge'];
                }
                
                $returnOrderData = OrderProduct::getRequestsByOrderId($order['order_id']);                
                $orderReturnAmount = ($returnOrderData->order_net_amount ? $returnOrderData->order_net_amount : 0);
                $orderReturnDate = ($returnOrderData->orrequest_date ? $returnOrderData->orrequest_date : 0);

                if((!array_key_exists($orderReturnDate, $totalOrders)) && ($orderReturnDate != 0 && $orderReturnAmount != 0)) {
                    $totalOrders[$orderReturnDate]['net_sale'] = ((isset($totalOrders[$orderReturnDate]['net_sale']) ? $totalOrders[$orderReturnDate]['net_sale'] : 0) - $orderReturnAmount);
                    $totalOrders[$orderReturnDate]['shipping_charge'] = ((isset($totalOrders[$orderReturnDate]['shipping_charge'])) ?  $totalOrders[$orderReturnDate]['shipping_charge'] : 0);
                    $totalOrders[$orderReturnDate]['tax_charge'] = ((isset($totalOrders[$orderReturnDate]['tax_charge'])) ?  $totalOrders[$orderReturnDate]['tax_charge'] : 0);
                } else {
                    $totalOrders[$orderReturnDate]['net_sale'] = ((isset($totalOrders[$orderReturnDate]['net_sale']) ? $totalOrders[$orderReturnDate]['net_sale'] : 0) - $orderReturnAmount);
                    $totalOrders[$orderReturnDate]['shipping_charge'] = ((isset($totalOrders[$orderReturnDate]['shipping_charge'])) ?  $totalOrders[$orderReturnDate]['shipping_charge'] - $returnOrderData->oramount_shipping  : - $returnOrderData->oramount_shipping);
                    $totalOrders[$orderReturnDate]['tax_charge'] = ((isset($totalOrders[$orderReturnDate]['tax_charge'])) ?  $totalOrders[$orderReturnDate]['tax_charge'] - $returnOrderData->oramount_tax: - $returnOrderData->oramount_tax);
                }
                $totalOrders[$order['day']]['total_sale'] = priceFormat(($totalOrders[$order['day']]['net_sale'] + $totalOrders[$order['day']]['shipping_charge'] + $totalOrders[$order['day']]['tax_charge']),false);
                if($totalOrders[$order['day']]['order_count'] == 0) {
                    $totalOrders[$order['day']]['avg_order_amount'] = priceFormat(($totalOrders[$order['day']]['total_sale']));
                } else {
                    $totalOrders[$order['day']]['avg_order_amount'] = priceFormat(($totalOrders[$order['day']]['total_sale']/$totalOrders[$order['day']]['order_count']),false);
                }
            }
        }

        foreach ($dates as  $key => $date) {
            if (array_key_exists($date, $totalOrders)) {
                $data[$key] = $totalOrders[$date];
                $data[$key]['day'] = $labels[$key];
                $series[$key] = $totalOrders[$date]['avg_order_amount'];
            } else {
                $data[$key]['order_count'] = 0;
                $data[$key]['total_sale'] = 0;
                $data[$key]['day'] = $labels[$key];
                $data[$key]['avg_order_amount'] = 0;
                $series[$key] = 0;
            }
        }
        $saleAvgOrderTimedata['data'] = $data;
        $saleAvgOrderTimedata['labels'] = $labels;
        $saleAvgOrderTimedata['series'] = $series;
        $saleAvgOrderTimedata['summary'] = [];
        $saleAvgOrderTimedata['dateRange'][0]  = $thiryDaysDate['startDate'];
        $saleAvgOrderTimedata['dateRange'][1]  = $thiryDaysDate['endDate'];
        return $saleAvgOrderTimedata;
    }

    private function getSaleTrafficRefferals(Request $request)
    {
        $reports = [];
        $thiryDaysDate = Report::getLastThiryDayDates($request->all());
        $reports['data'][] = $this->getSocialOrders($thiryDaysDate, 'user_google_id', 'google');
        $reports['data'][] = $this->getSocialOrders($thiryDaysDate, 'user_facebook_id', 'facebook');
        $reports['data'][] = $this->getSocialOrders($thiryDaysDate, 'user_instagram_id', 'instagram');

        $reports['data'][] = $this->getDirectOrders($thiryDaysDate);
        
        $reports['dateRange'][0]  = $thiryDaysDate['startDate'];
        $reports['dateRange'][1]  = $thiryDaysDate['endDate'];
        $reports['summary'] = [];
        return $reports;
    }

    /***
    *  Acquisition Report Functions
    *
    */
    public function getAcquisitonReport(Request $request)
    {
        config(['analytics.view_id' => Configuration::getValue('GOOGLE_ANALYTICS_VIEW_ID')]);
        $dateRanges = Report::getLastSevenDayDates($request->all());
        $date[0] = $dateRanges['startDate'];
        $date[1] = $dateRanges['endDate'];
        $labels = [];
        $series = [];
        if ($fileExist= Storage::exists('public/analytics/service_credentials.json')) {
            switch ($request->input('type')) {
                case "sessionOverTime":
                    try {
                        $analyticsArray = ['dimensions'=> 'ga:date'];
                        if ( !empty($request->input('sorting-by')) && !empty($request->input('sorting-type'))) {
                            $setSortingOperator = '-';
                            if ($request->input('sorting-type') == 'asc') {
                                $setSortingOperator = '';
                            }
                            $analyticsArray = Arr::add($analyticsArray, 'sort', $setSortingOperator.'ga:' . $request->input('sorting-by'));
                        }
                        $response = Analytics::performQuery(
                            Period::create($dateRanges['startDateCarbon'], $dateRanges['endDateCarbon']), 
                            'ga:users,ga:sessions',
                            $analyticsArray
                        );
                        $sessionData = collect($response['rows'] ?? [])->map(function (array $dateRow) use ($labels) {
                            return [
                                'date' =>  Carbon::createFromFormat('Ymd', $dateRow[0])->format('d M'),
                                'visitors'=>(int) $dateRow[1],
                                'sessions' => (int) $dateRow[2]
                            ];
                        });
                        foreach ($sessionData as $data) {
                            $labels[] = $data['date'];
                            $series[] = $data['sessions'];
                        }
                    } catch (\Spatie\Analytics\Exceptions\InvalidConfiguration $e) {
                        return jsonresponse(false, $e->getMessage());
                    } catch (\Exception $e) {
                        return jsonresponse(false, $e->getMessage());
                    }
                    break;
                case "sessionByReferrer":
                    try {
                        $analyticsArray = ['dimensions'=> 'ga:source,ga:medium'];
                        if ( !empty($request->input('sorting-by')) && !empty($request->input('sorting-type')) ) {
                            $setSortingOperator = '-';
                            if ($request->input('sorting-type') == 'asc') {
                                $setSortingOperator = '';
                            }
                            $analyticsArray = Arr::add($analyticsArray, 'sort', $setSortingOperator.'ga:' . $request->input('sorting-by'));
                        }
                        $response = Analytics::performQuery(
                            Period::create($dateRanges['startDateCarbon'], $dateRanges['endDateCarbon']),
                            'ga:users,ga:sessions',
                            $analyticsArray
                        );
                        $sessionData = collect($response['rows'] ?? [])->map(function (array $dateRow) {
                            return [
                                'source' => $dateRow[0],
                                'name' => $dateRow[1],
                                'visitors' =>(int) $dateRow[2],
                                'sessions' => (int) $dateRow[3]
                            ];
                        });
                    } catch (\Spatie\Analytics\Exceptions\InvalidConfiguration $e) {
                        return jsonresponse(false, $e->getMessage());
                    }
                    break;
                case "sessionByLocation":
                    try {
                        $analyticsArray = ['dimensions' => 'ga:country'];
                        if ( !empty($request->input('sorting-by')) && !empty($request->input('sorting-type')) ) {
                            $setSortingOperator = '-';
                            if ($request->input('sorting-type') == 'asc') {
                                $setSortingOperator = '';
                            }
                            $analyticsArray = Arr::add($analyticsArray, 'sort', $setSortingOperator.'ga:' . $request->input('sorting-by'));
                        }
                        $response = Analytics::performQuery(
                            Period::create($dateRanges['startDateCarbon'], $dateRanges['endDateCarbon']),
                            'ga:users,ga:sessions',
                            $analyticsArray
                        );
            
                        $sessionData = collect($response['rows'] ?? [])->map(function (array $dateRow) {
                            return [
                                'location' => $dateRow[0],
                                'visitors' => (int) $dateRow[1],
                                'sessions' => (int) $dateRow[2],
                            ];
                        });
                    } catch (\Spatie\Analytics\Exceptions\InvalidConfiguration $e) {
                        return jsonresponse(false, $e->getMessage());
                    }
                    break;
                }
        } else {
            $sessionData = []; 
        }
        
        $analytics['data'] = $sessionData;
        $analytics['labels'] = $labels;
        $analytics['series'] = $series;
        $analytics['dateRange'] = $date;
        $analytics['serviceFileExists'] = $fileExist;
        return jsonresponse(true, '', $analytics);
    }
    
    /***
     *  Profit margin Report
     *
     */
    public function getProfitByProductReport(Request $request)
    {
        $date = Report::getLastThiryDayDates($request->all());
        switch ($request->input('type')) {
        case "profitByProduct":
            $products = $this->profitByProduct($date, $request);
            break;
        case "profitByProductVariant":
            $products = $this->profitByProductVariant($date, $request);
            break;
        }
        $data['totalProducts'] = $products;
        $data['summary'] = [];
        $data['dateRange'][0]  = $date['startDate'];
        $data['dateRange'][1]  = $date['endDate'];
        $data['types'] = Product::getTypes();
        return jsonresponse(true, '', $data);
    }

    private function profitByProduct($date, $request)
    {
        $totalProducts = [];
        $query = Order::select([
            'order_id',
            'op_id',
            'op_order_id',
            'op_product_id',
            'op_product_name',
            'op_product_type',
            'op_product_price',
            'op_qty',
            'op_prod_cost',
            DB::raw('coalesce(SUM(op_qty), 0)  AS order_net_quantity'),
            DB::raw('SUM(op_product_price*op_qty) AS gross_sale'),
            DB::raw('prod_cost')
        ])
        ->leftjoin('order_products', 'op_order_id', 'order_id')
        ->join('products', 'op_product_id', 'prod_id')
        ->whereBetween('order_date_added', [$date['startDate']." 00:00:00", $date['endDate']." 23:59:59"]);

        if ($request->input('search') != '' && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where('op_product_name', 'LIKE', '%'.$search.'%');
        }
        $orderProducts = $query->groupBy('op_order_id','op_product_id')->get()->toArray();

        if(count($orderProducts) > 0) {
            foreach($orderProducts as $key => $order) {
                if(!array_key_exists($order['op_product_id'], $totalProducts)) {
                    $totalProducts[$order['op_product_id']]['op_product_name'] = $order['op_product_name'];
                    $totalProducts[$order['op_product_id']]['prod_cost'] = priceFormat($order['op_prod_cost'],false);
                    $totalProducts[$order['op_product_id']]['gross_sale'] = priceFormat($order['gross_sale'],false);
                    $totalProducts[$order['op_product_id']]['op_product_type'] = $order['op_product_type'];
                    $totalProducts[$order['op_product_id']]['order_net_quantity'] = $order['order_net_quantity'];
                    $totalProducts[$order['op_product_id']]['op_product_type'] = $order['op_product_type'];
                    $totalProducts[$order['op_product_id']]['tax_charge'] =  priceFormat((OrderProductCharge::calculateTax($order['op_id'], $order['op_qty'])),false);
                    $productReturnData = OrderReturnRequest::getReturnDataOrderProductId($order['op_id'], $order['op_product_price']);

                    if(isset($productReturnData['price']) && isset($productReturnData['quantity'])) {
                        $totalProducts[$order['op_product_id']]['return_amount'] = priceFormat($productReturnData['price'],false);
                        $totalProducts[$order['op_product_id']]['order_net_quantity'] = ($totalProducts[$order['op_product_id']]['order_net_quantity'] - $productReturnData['quantity']);
                    } else {
                        $totalProducts[$order['op_product_id']]['return_amount'] = 0 ;
                    }
                } else {
                    $totalProducts[$order['op_product_id']]['gross_sale'] += $order['gross_sale'];
                    $totalProducts[$order['op_product_id']]['order_net_quantity'] += $order['order_net_quantity'];
                    $totalProducts[$order['op_product_id']]['tax_charge'] +=  priceFormat(OrderProductCharge::calculateTax($order['op_id'], $order['op_qty']),false);
                    $productReturnData = OrderReturnRequest::getReturnDataOrderProductId($order['op_id'], $order['op_product_price']);
                    if(isset($productReturnData['price']) && isset($productReturnData['quantity'])) {
                        $totalProducts[$order['op_product_id']]['return_amount'] += priceFormat($productReturnData['price'],false);
                        $totalProducts[$order['op_product_id']]['order_net_quantity'] = ($totalProducts[$order['op_product_id']]['order_net_quantity'] - $productReturnData['quantity']);
                    }
                }
                $totalProducts[$order['op_product_id']]['net_sale'] = priceFormat(($totalProducts[$order['op_product_id']]['gross_sale'] - $totalProducts[$order['op_product_id']]['return_amount']), false);

                $totalProducts[$order['op_product_id']]['total_sale'] = priceFormat(($totalProducts[$order['op_product_id']]['net_sale'] + $totalProducts[$order['op_product_id']]['tax_charge']),false);

                $totalProducts[$order['op_product_id']]['cost'] = priceFormat(($totalProducts[$order['op_product_id']]['prod_cost'] * $totalProducts[$order['op_product_id']]['order_net_quantity']),false);

                $totalProducts[$order['op_product_id']]['gross_profit'] = priceFormat(($totalProducts[$order['op_product_id']]['net_sale'] - $totalProducts[$order['op_product_id']]['cost']),false);
                if($totalProducts[$order['op_product_id']]['net_sale'] != 0 ){
                    $totalProducts[$order['op_product_id']]['gross_percent'] = priceFormat(( ($totalProducts[$order['op_product_id']]['gross_profit'] * 100) / $totalProducts[$order['op_product_id']]['net_sale']),false);
                } else {
                    $totalProducts[$order['op_product_id']]['gross_percent'] = 0;
                }
                
            }
        }
        return $totalProducts;
    }

    private function profitByProductVariant($date, $request)
    {
        $totalProducts = [];
        $query = Order::
        select([
            'order_id',
            'op_id',
            'op_order_id',
            'op_product_id',
            'op_product_name',
            'op_product_type',
            'op_product_price',
            'op_pov_code',
            'op_product_variants',
            'op_product_sku',
            'op_qty',
            'op_prod_cost as prod_cost',
            DB::raw('coalesce(SUM(op_qty), 0)  AS order_net_quantity'),
            DB::raw('SUM(op_product_price*op_qty) AS gross_sale')
        ])
        ->leftjoin('order_products', 'op_order_id', 'order_id')
        ->join('products', 'op_product_id', 'prod_id')
        ->whereBetween('order_date_added', [$date['startDate']." 00:00:00", $date['endDate']." 23:59:59"]);

        if ($request->input('search') != '' && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where('op_product_name', 'LIKE', '%'.$search.'%');
        }
        $orderProducts = $query->groupBy('op_order_id','op_product_variants','op_product_id')->get()->toArray();
        if(count($orderProducts) > 0) {
            foreach($orderProducts as $key => $order) {
                $productVariantCode = $this->getProductIdWithVariantCode($order['op_product_id'], $order['op_pov_code']);
                if(!array_key_exists($productVariantCode, $totalProducts)) {
                    $totalProducts[$productVariantCode]['op_product_name'] = $order['op_product_name'];
                    $totalProducts[$productVariantCode]['order_net_quantity'] = $order['order_net_quantity'];
                    $totalProducts[$productVariantCode]['prod_cost'] = priceFormat($order['prod_cost'], false);
                    $totalProducts[$productVariantCode]['op_product_type'] = $order['op_product_type'];
                    $totalProducts[$productVariantCode]['tax_charge'] =  OrderProductCharge::calculateTax($order['op_id'], $order['op_qty']);
                    $totalProducts[$productVariantCode]['op_product_variants'] = $order['op_product_variants'];
                    $totalProducts[$productVariantCode]['op_product_sku'] = $order['op_product_sku'];
                    $totalProducts[$productVariantCode]['gross_sale'] = priceFormat($order['gross_sale'],false);
                    $productReturnData = OrderReturnRequest::getReturnDataOrderProductId($order['op_id'], $order['op_product_price']);

                    if(isset($productReturnData['price']) && isset($productReturnData['quantity'])) {
                        $totalProducts[$productVariantCode]['return_amount'] = priceFormat($productReturnData['price'],false);
                        $totalProducts[$productVariantCode]['order_net_quantity'] = priceFormat(($totalProducts[$productVariantCode]['order_net_quantity'] - $productReturnData['quantity']), false);
                    } else {
                        $totalProducts[$productVariantCode]['return_amount'] = 0 ;
                    }
                }  else {
                    $totalProducts[$productVariantCode]['gross_sale'] += priceFormat($order['gross_sale'],false);
                    $totalProducts[$productVariantCode]['order_net_quantity'] += priceFormat($order['order_net_quantity']);
                    $totalProducts[$productVariantCode]['tax_charge'] +=  priceFormat((OrderProductCharge::calculateTax($order['op_id'], $order['op_qty'])),false);
                    $productReturnData = OrderReturnRequest::getReturnDataOrderProductId($order['op_id'], $order['op_product_price']);
                    if(isset($productReturnData['price']) && isset($productReturnData['quantity'])) {
                        $totalProducts[$productVariantCode]['return_amount'] += priceFormat($productReturnData['price'],false);
                        $totalProducts[$productVariantCode]['order_net_quantity'] = ($totalProducts[$productVariantCode]['order_net_quantity'] - $productReturnData['quantity']);
                    }
                }
                $totalProducts[$productVariantCode]['net_sale'] = priceFormat(($totalProducts[$productVariantCode]['gross_sale'] - $totalProducts[$productVariantCode]['return_amount']),false);
                $totalProducts[$productVariantCode]['total_sale'] = priceFormat(($totalProducts[$productVariantCode]['net_sale'] + $totalProducts[$productVariantCode]['tax_charge']),false);
                $totalProducts[$productVariantCode]['cost'] = priceFormat(($totalProducts[$productVariantCode]['prod_cost'] * $totalProducts[$productVariantCode]['order_net_quantity']),false);
                $totalProducts[$productVariantCode]['gross_profit'] = priceFormat(($totalProducts[$productVariantCode]['net_sale'] - $totalProducts[$productVariantCode]['cost']),false);
                if ($totalProducts[$productVariantCode]['net_sale'] != 0 ) {
                    //$totalProducts[$productVariantCode]['gross_percent'] = round( ($totalProducts[$productVariantCode]['cost'] * 100) / $totalProducts[$productVariantCode]['net_sale']);
                    $totalProducts[$productVariantCode]['gross_percent'] = priceFormat(( ($totalProducts[$productVariantCode]['gross_profit'] * 100) / $totalProducts[$productVariantCode]['net_sale']),false);
                } else {
                    $totalProducts[$productVariantCode]['gross_percent'] = 0;
                }
            }
        }
        return $totalProducts;
    }
    /***
     * Customer Reports
     *
    */

    public function getCustomerReport(Request $request)
    {
        $date = Report::getLastThiryDayDates($request->all());
        switch ($request->input('type')) {
            case "oneTimeCustomer":
                $query =  Order::select([
                    'order_id', 'order_user_id', 
                    DB::raw('CONCAT(user_fname, " ", user_lname) as user_username'), 'user_email', 'order_date_added', 'order_net_amount', DB::raw("DATE_FORMAT(order_date_added, '%d %M') as order_formt_date")
                ])
                ->Join('users', 'order_user_id', 'user_id')
                ->whereBetween('order_date_added', [$date['startDate']." 00:00:00", $date['endDate']." 23:59:59"])
                ->groupBy('order_user_id')->havingRaw('count(order_user_id) = ?', [1]);
                if (!empty($request->input('sorting-by')) && !empty($request->input('sorting-type')) ) {
                    $query->orderBy($request->input('sorting-by'), $request->input('sorting-type'));
                }
                $totalOrders = $query->get();
                
            break;
            case "returningCustomer":
                $query =  Order::select([
                    'order_id', 'order_user_id', 
                    DB::raw('CONCAT(user_fname, " ", user_lname) as user_username'), 'user_email', 'order_date_added', 'order_net_amount', DB::raw("DATE_FORMAT(order_date_added, '%d %M') as order_formt_date"), DB::raw("Count(order_user_id) as order_count "), DB::raw("SUM(order_net_amount) AS order_amount") ])
                ->Join('users', 'order_user_id', 'user_id')
                ->whereBetween('order_date_added', [$date['startDate']." 00:00:00", $date['endDate']." 23:59:59"])
                ->groupBy('order_user_id')->havingRaw('count(order_user_id) > ?', [1]);
                if (!empty($request->input('sorting-by')) && !empty($request->input('sorting-type')) ) {
                    $query->orderBy($request->input('sorting-by'), $request->input('sorting-type'));
                }
                $totalOrders = $query->get();
            break;
            case "locationCustomer":
                $query =  Order::select(['countries.country_name as user_country', DB::raw("Count(order_user_id) as order_count "), DB::raw("COUNT(DISTINCT order_user_id) AS order_customer"), DB::raw("SUM(order_net_amount) AS order_amount")])
                ->join('users', 'order_user_id', 'user_id')
                ->join('countries', 'countries.country_id', 'user_country_id')
                ->whereBetween('order_date_added', [$date['startDate']." 00:00:00", $date['endDate']." 23:59:59"])
                ->groupBy('user_country_id');
                if (!empty($request->input('sorting-by')) && !empty($request->input('sorting-type')) ) {
                    $query->orderBy($request->input('sorting-by'), $request->input('sorting-type'));
                }
                $totalOrders = $query->get();
            break;
        }
        $data['data'] = $totalOrders;
        $data['dateRange'][0] = $date['startDate'];
        $data['dateRange'][1] = $date['endDate'];
        return jsonresponse(true, '', $data);
    }
    
    public function getFirstVsReturnCustomer(Request $request)
    {
        $startDateCarbon = new Carbon(Carbon::today()->startOfMonth()->subMonth(11)->toDateString());
        $endDateCarbon = new Carbon(Carbon::today()->toDateString());
        $startDate =  $startDateCarbon->toDateString();
        $endDate =  Carbon::today()->toDateString();

        if ($request->input('dateRange') != '') {
            $date = explode(',', $request->input('dateRange'));
            $startDate = $date[0] ? $date[0] : $startDate;
            $endDate = $date[1] ? $date[1] : $endDate;
            $startDateCarbon = new Carbon($startDate);
            $endDateCarbon = new Carbon($endDate);
        }

        while ($startDateCarbon->lte($endDateCarbon)) {
            $labels[] = $startDateCarbon->format('F Y');
            $newData[$startDateCarbon->format('F Y')]['month'] = $startDateCarbon->format('M Y');
            $newData[$startDateCarbon->format('F Y')]['month_number'] = $startDateCarbon->format('m');
            $newData[$startDateCarbon->format('F Y')]['year_number'] = $startDateCarbon->format('Y');
            $startDateCarbon->addMonth();
        }
        $count = 0;
        $series[0] = [];
        $series[1] = [];
        $customerReport = [];
        foreach ($newData as $key => $data) {
            $firstTimeCustomer =  Order::select([
                DB::raw("count(order_id) as order_count"),
                DB::raw("DATE_FORMAT(order_date_added, '%M %Y') as order_formt_date"),
                DB::raw("TRUNCATE(SUM(order_net_amount),2)  as order_amount"),
            ])
            ->whereIn('order_user_id', function($query) {
                $query->select('order_user_id')
                    ->from('orders')
                    ->groupBy('order_user_id')
                    ->havingRaw('COUNT(order_id) = ?', [1]);
            })
            ->whereMonth('order_date_added', $data['month_number'])
            ->whereYear('order_date_added', $data['year_number'])
            ->groupBy('order_formt_date')->get()->toArray();
            $returningTimeCustomer =  Order::select([
                DB::raw("DATE_FORMAT(order_date_added, '%M %Y') as order_formt_date"),
                DB::raw("count(order_id) as order_count"),
                DB::raw("TRUNCATE(SUM(order_net_amount),2)  as order_amount")
            ])
            ->whereIn('order_user_id', function($query) {
                $query->select('order_user_id')
                    ->from('orders')
                    ->groupBy('order_user_id')
                    ->havingRaw('COUNT(order_id) > ?', [1]);
            })
            ->whereMonth('order_date_added', $data['month_number'])
            ->whereYear('order_date_added', $data['year_number'])
            ->groupBy('order_formt_date')->get()->toArray();
            
            $series[0][$count] = (count($firstTimeCustomer) == 1) ? $firstTimeCustomer[0]['order_amount'] : 0;
            $series[1][$count] = (count($returningTimeCustomer) == 1) ? $returningTimeCustomer[0]['order_amount'] : 0;
            
            $customerReport[$count]['month'] = $data['month'];
            $customerReport[$count]['first_time_spent'] =  $series[0][$count];
            $customerReport[$count]['returning_spent'] = $series[1][$count];
            $count ++;
        }
        $data['labels']       = $labels;
        $data['series']       = $series;
        $data['data']         = $customerReport;
        $data['dateRange'][0] = $startDate;
        $data['dateRange'][1] = $endDate;
        return jsonresponse(true, '', $data);
    }

    public function getCustomerOverTime(Request $request)
    {
        $startDateCarbon = new Carbon(Carbon::today()->startOfMonth()->subMonth(11)->toDateString());
        $endDateCarbon = new Carbon(Carbon::today()->toDateString());
        $startDate =  $startDateCarbon->toDateString();
        $endDate =  Carbon::today()->toDateString();
        
        if ($request->input('dateRange') != '') {
            $date = explode(',', $request->input('dateRange'));
            $startDate = $date[0] ? $date[0] : $startDate;
            $endDate = $date[1] ? $date[1] : $endDate;
            $startDateCarbon = new Carbon($startDate);
            $endDateCarbon = new Carbon($endDate);
        }

        while ($startDateCarbon->lte($endDateCarbon)) {
            $labels[] = $startDateCarbon->format('F Y');
            $newData[$startDateCarbon->format('F Y')]['month'] = $startDateCarbon->format('M Y');
            $newData[$startDateCarbon->format('F Y')]['month_number'] = $startDateCarbon->format('m');
            $newData[$startDateCarbon->format('F Y')]['year_number'] = $startDateCarbon->format('Y');
            $startDateCarbon->addMonth();
        }
        $count = 0;
        $series[0] = [];
        $series[1] = [];
        $customerReport = [];
        foreach ($newData as $key => $data) {
            $firstTimeCustomer =  Order::select([
                DB::raw("DATE_FORMAT(order_date_added, '%M %Y') as order_formt_date"),
                DB::raw("count(order_id)  as order_count")
            ])
            ->whereIn('order_user_id', function($query){
                $query->select('order_user_id')
                    ->from('orders')
                    ->groupBy('order_user_id')
                    ->havingRaw('COUNT(order_id) = ?', [1]);
            })
            ->whereMonth('order_date_added', $data['month_number'])
            ->whereYear('order_date_added', $data['year_number'])
            ->groupBy('order_formt_date')->get()->toArray();

            $returningTimeCustomer =  Order::select([
                DB::raw("DATE_FORMAT(order_date_added, '%M %Y') as order_formt_date"),
                DB::raw("count(order_id) as order_count")
            ])
            ->whereIn('order_user_id', function($query) {
                $query->select('order_user_id')
                    ->from('orders')
                    ->groupBy('order_user_id')
                    ->havingRaw('COUNT(order_id) > ?', [1]);
            })
            ->whereMonth('order_date_added', $data['month_number'])
            ->whereYear('order_date_added', $data['year_number'])
            ->groupBy('order_formt_date')->get()->toArray();
            
            $series[0][$count] = (count($firstTimeCustomer) == 1) ? $firstTimeCustomer[0]['order_count'] : 0;
            $series[1][$count] = (count($returningTimeCustomer) == 1) ? $returningTimeCustomer[0]['order_count'] : 0;
            
            $customerReport[$count]['month'] = $data['month'];
            $customerReport[$count]['first_time_customer'] =  $series[0][$count];
            $customerReport[$count]['returning_time_customer'] = $series[1][$count];
            $count ++;
        }
        $data['labels']       = $labels;
        $data['series']       = $series;
        $data['data']         = $customerReport;
        $data['dateRange'][0] = $startDate;
        $data['dateRange'][1] = $endDate;
        return jsonresponse(true, '', $data);
    }

    /***
     * Behavior Reports
     *
    */
    public function getBehaviorReport(Request $request)
    {
        config(['analytics.view_id' => Configuration::getValue('GOOGLE_ANALYTICS_VIEW_ID')]);
        $date = Report::getLastThiryDayDates($request->all());
        switch ($request->input('type')) {
        case "sessionByLandingPage":
            try {
                $behaviorArray = ['dimensions' => 'ga:landingPagePath'];
                if ( !empty($request->input('sorting-by')) && !empty($request->input('sorting-type'))) {
                    $setSortingOperator = '-';
                    if ($request->input('sorting-type') == 'asc') {
                        $setSortingOperator = '';
                    }
                    $behaviorArray = Arr::add($behaviorArray, 'sort', $setSortingOperator.'ga:' . $request->input('sorting-by'));
                }
                $response = Analytics::performQuery(
                    Period::create($date['startDateCarbon'], $date['endDateCarbon']),
                    'ga:users,ga:sessions',
                    $behaviorArray
                );
                $responseData = collect($response['rows'] ?? [])->map(function (array $dateRow) {
                    $landingPageType = explode('/', $dateRow[0]);
                    $pageType =  $landingPageType[1] ? $landingPageType[1] : '';
                    if (str_contains($landingPageType[1], 'referralcode')) { 
                        $pageType = "Referral" ;
                    }
                    return [
                        'page_type' => $pageType,
                        'landing_page' => $dateRow[0],
                        'visitors' => (int) $dateRow[1],
                        'sessions' => (int) $dateRow[2],
                    ];
                });
            } catch (\Spatie\Analytics\Exceptions\InvalidConfiguration $e) {
                    return jsonresponse(false, $e->getMessage());
            }
            break;
        case "sessionByDevice":
            try {
                $behaviorArray = ['dimensions' => 'ga:deviceCategory'];
                if ( !empty($request->input('sorting-by')) && !empty($request->input('sorting-type'))) {
                    $setSortingOperator = '-';
                    if ($request->input('sorting-type') == 'asc') {
                        $setSortingOperator = '';
                    }
                    $behaviorArray = Arr::add($behaviorArray, 'sort', $setSortingOperator.'ga:' . $request->input('sorting-by'));
                }
                $response = Analytics::performQuery(
                    Period::create($date['startDateCarbon'], $date['endDateCarbon']),
                    'ga:users,ga:sessions',
                    $behaviorArray
                );
                $responseData = collect($response['rows'] ?? [])->map(function (array $dateRow) {
                    return [
                        'device_type' => $dateRow[0],
                        'visitors' => (int) $dateRow[1],
                        'sessions' => (int) $dateRow[2],
                    ];
                });
            } catch (\Spatie\Analytics\Exceptions\InvalidConfiguration $e) {
                return jsonresponse(false, $e->getMessage());
            }
            break;
        case "topOnlineStoreWithResult":
            $responseData = $this->getTopOnlineStoreResults($request, $date, config('constants.YES'));
            break;
        case "topOnlineStoreWithNoResult":
            $responseData = $this->getTopOnlineStoreResults($request, $date, config('constants.NO'));
            break;
        }
        $data['data'] = $responseData;
        $data['dateRange'][0] = $date['startDate'];
        $data['dateRange'][1] = $date['endDate'];
        return jsonresponse(true, '', $data);
    }

    /** 
     * Private Function 
    */

    private function getTopOnlineStoreResults($request, $date, $status)
    {
        $query = Search::select(['search_term', DB::raw("COUNT(search_id) as total_count")])
            ->whereBetween('search_created_at', [$date['startDate']." 00:00:00", $date['endDate']." 23:59:59"])
            ->where('search_is_found', $status)
            ->groupBy('search_term');
        if (!empty($request->input('sorting-by')) && !empty($request->input('sorting-type'))) {
            $query->orderBy($request->input('sorting-by'), $request->input('sorting-type'));
        }
        return $query->get()->toArray();
    }

    private function getSocialOrders($thiryDaysDate, $key, $socialType)
    {
        $userIds = User::where($key,'>', 1)->pluck('user_id')->toArray();
        $totalOrders = [];
        $orderProducts = Order::select(['order_id','order_user_id','order_discount_amount', 'order_shipping_value', 'order_tax_charged'])
        ->addSelect([
            'gross_sale' => OrderProduct::selectRaw('(SUM(op_product_price*op_qty)) as total')->whereColumn('op_order_id', 'order_id')
            ->groupBy('op_order_id')
        ])
        ->join('users', 'order_user_id', '=', 'user_id')
        ->whereBetween('order_date_added', [$thiryDaysDate['startDate']." 00:00:00", $thiryDaysDate['endDate']." 23:59:59"])
        ->whereIn('user_id', $userIds)
        ->get()->toArray();
        if (count($orderProducts) > 0) {
            foreach($orderProducts as $key=> $order ) {
                if(!empty($totalOrders)) {
                    $totalOrders['order_count'] += 1;
                    $totalOrders['net_sale'] += ($order['gross_sale'] - $order['order_discount_amount']);
                    $totalOrders['tax'] += ($order['order_tax_charged']);
                    $totalOrders['shipping'] += ($order['order_shipping_value']);
                } else {
                    $totalOrders['order_count'] = 1;
                    $totalOrders['net_sale'] = ($order['gross_sale'] - $order['order_discount_amount']);
                    $totalOrders['tax'] = ($order['order_tax_charged']);
                    $totalOrders['shipping'] = ($order['order_shipping_value']);
                }
                
                $returnOrderData = OrderProduct::getRequestsByOrderId($order['order_id']);                
                $orderReturnAmount = ($returnOrderData->order_net_amount ? $returnOrderData->order_net_amount : 0);
                if(($orderReturnAmount != 0)) {
                    $totalOrders['net_sale'] = ($totalOrders['net_sale'] - $orderReturnAmount);
                }
                $totalOrders['total_sale'] = priceFormat(($totalOrders['net_sale'] + $totalOrders['shipping'] + $totalOrders['tax']),false);
                $totalOrders['name'] = $socialType;
            }
        } else {
            $totalOrders['name'] =  $socialType;
            $totalOrders['total_sale'] = 0;
            $totalOrders['order_count'] = 0;
        }
        return $totalOrders;
    }

    private function getDirectOrders($thiryDaysDate)
    {
        $totalOrders = [];
        $orders =  Order::select([
                'order_id','order_user_id','order_discount_amount', 'order_shipping_value', 'order_tax_charged'
            ])->addSelect([
                'gross_sale' => OrderProduct::selectRaw('(SUM(op_product_price*op_qty)) as total')->whereColumn('op_order_id', 'order_id')
                ->groupBy('op_order_id')
            ])
            ->addSelect([
                'total_qty' => OrderProduct::selectRaw('(SUM(op_qty)) as total_qty')->whereColumn('op_order_id', 'order_id')
                ->groupBy('op_order_id')
            ])
            ->join('users', 'order_user_id', '=', 'user_id')
            ->whereBetween('order_date_added', [$thiryDaysDate['startDate']." 00:00:00", $thiryDaysDate['endDate']." 23:59:59"])
            ->whereNull('user_instagram_id')
            ->whereNull('user_google_id')
            ->whereNull('user_facebook_id')
            ->get()
            ->toArray();

        if (count($orders) > 0) {
            foreach($orders as $key=> $order) {
                $orderReturnQuantity = OrderReturnRequest::getReturnQuantityByOrderId($order['order_id']);
                if(!empty($totalOrders)) {
                    if ($orderReturnQuantity->quantity != $order['total_qty']) {
                        $totalOrders['order_count'] += 1;
                    }
                    $totalOrders['net_sale'] += ($order['gross_sale'] - $order['order_discount_amount']);
                    $totalOrders['tax'] += ($order['order_tax_charged']);
                    $totalOrders['shipping'] += ($order['order_shipping_value']);
                } else {
                    if ($orderReturnQuantity->quantity != $order['total_qty']) {
                        $totalOrders['order_count'] = 1;
                    }
                    $totalOrders['net_sale'] = ($order['gross_sale'] - $order['order_discount_amount']);
                    $totalOrders['tax'] = ($order['order_tax_charged']);
                    $totalOrders['shipping'] = ($order['order_shipping_value']);
                }
                $returnOrderData = OrderProduct::getRequestsByOrderId($order['order_id']);                
                $orderReturnAmount = ($returnOrderData->order_net_amount ? $returnOrderData->order_net_amount : 0);
                if(($orderReturnAmount != 0)) {
                    $totalOrders['net_sale'] = ($totalOrders['net_sale'] - $orderReturnAmount);
                    $totalOrders['tax'] = ($totalOrders['tax'] - $returnOrderData->oramount_tax);
                    $totalOrders['shipping'] = ($totalOrders['shipping'] - $returnOrderData->oramount_shipping);
                }
            }
            $totalOrders['total_sale'] = priceFormat(($totalOrders['net_sale'] + $totalOrders['shipping'] + $totalOrders['tax']),false);
            $totalOrders['name'] = 'direct';
        } else {
            $totalOrders['name'] =  'direct';
            $totalOrders['total_sale'] = 0;
            $totalOrders['order_count'] = 0;
        }
        return $totalOrders;
    }

}
