<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderReturnRequest;
use App\Models\Configuration;
use DB;
use Analytics;
use Spatie\Analytics\Period;

class DashboardController extends AdminYokartController
{
    private $hours = ['0:00','1:00','2:00','3:00','4:00','5:00','6:00','7:00','8:00','9:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00', '22:00','23:00'];
    private $hourShow = ['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23'];

    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
    }

    public function getTotalSalesByType(Request $request, $type)
    {
        switch ($type) {
        case 'today':
            $records = $this->getTodaySaleRecords();
            break;
        case 'weekly':
            $records = $this->getWeeklySaleRecords();
            break;
        case 'lastMonth':
            $records = $this->getLastMonthSaleRecords();
            break;
        case 'currentMonth':
            $records = $this->getCurrentMonthSaleRecords();
            break;
        case 'currentYear':
            $records = $this->getCurrentYearSaleRecords();
            break;
        }
        return jsonresponse(true, '', $records);
    }

    public function getTotalOrdersByType(Request $request, $type)
    {
        switch ($type) {
            case "today":
                $records = $this->getTodayOrderCounts();
            break;
            case "weekly":
                $records = $this->getWeeklyOrderCounts();
            break;
            case "lastMonth":
                $records = $this->getLastMonthOrderCounts();
            break;
            case "currentMonth":
                $records = $this->getCurrentMonthOrderCounts();
            break;
            case "currentYear":
                $records = $this->getAnuallyOrderCounts();
            break;
        }
        return jsonresponse(true, '', $records);
    }

    /* [ Start Function related to Total Sales Records */
    private function getSaleRecordData($fromDate, $toDate, $type)
    {
        switch ($type) {
            case "hours":
                $totalOrders = Order::select([ DB::raw('coalesce(SUM(order_net_amount), 0) AS order_amount'), DB::raw('DATE_FORMAT(order_date_added, "%k:00") AS perHr')])->whereBetween('order_date_added', [$fromDate." 00:00:00", $toDate." 23:59:59"])->groupBy(DB::raw('CONCAT(DATE_FORMAT(order_date_added, "%Y-%m-%d %H"), ":00:00.000")'))->pluck('order_amount', 'perHr')->toArray();
            break;
            case "days":
                $totalOrders = Order::select([ DB::raw('coalesce(SUM(order_net_amount), 0) AS order_amount'), DB::raw('DATE_FORMAT(order_date_added, "%Y-%m-%d") AS perDay')])->whereBetween('order_date_added', [$fromDate." 00:00:00", $toDate." 23:59:59"])->groupBy(DB::raw('CONCAT(DATE_FORMAT(order_date_added, "%Y-%m-%d"), ":00:00.000")'))->pluck('order_amount', 'perDay')->toArray();
            break;
            case "months":
                $totalOrders = Order::select([ DB::raw('coalesce(SUM(order_net_amount), 0) AS order_amount'), DB::raw('MONTH(order_date_added) AS perMonth')])->whereBetween('order_date_added', [$fromDate." 00:00:00", $toDate." 23:59:59"])->groupBy('perMonth')->pluck('order_amount', 'perMonth')
                ->toArray();
            break;
        }
        return $totalOrders;
    }

    private function getTodaySaleRecords()
    {
        $formattedFromDate = Carbon::today()->format('d M Y');
        $fromDate = Carbon::today()->toDateString();
        //$toDate = $fromDate;
        $totalOrders = $this->getSaleRecordData($fromDate, $fromDate, 'hours');
        $totalReturns = $this->getReturnOrderAmount($fromDate, $fromDate, 'hours');

        $previousDayDate = Carbon::today()->subDays(2)->toDateString();
        $previousDaySum = $this->getSaleAmountBetweenDates($previousDayDate, $previousDayDate);
      
        $hours = $this->hours;
        if (0 > count($totalOrders)) {
            $records =  ['labels' => $hours, 'data' => [],'fromDate' => $formattedFromDate, 'toDate' => $formattedFromDate ];
        }
        foreach ($hours as $key => $hour) {
            if (array_key_exists($hour, $totalOrders)) {
                if (isset($totalReturns[$hour]) && !empty($totalReturns[$hour])) {
                    $totalSale = $totalOrders[$hour] - $totalReturns[$hour];
                    $data[$key] = ($totalSale < 0) ? 0 : $totalSale;
                } else {
                    $data[$key] =  $totalOrders[$hour];
                }
            } else {
                $data[$key] =  0;
            }
        }
        $totalOrderAmount = array_sum($data);
        $percentage = $this->getPercentage($totalOrderAmount, $previousDaySum);
        $salePercentageStatus = $totalOrderAmount > $previousDaySum ? 1 : 0;
        $records =  ['labels' => $this->hourShow, 'data' => $data,'fromDate' => $formattedFromDate, 'toDate' => $formattedFromDate, 'total' => $totalOrderAmount,'percentage' => $percentage, 'salePercentageStatus' => $salePercentageStatus ];
     
        return $records;
    }

    private function getWeeklySaleRecords()
    {
        $startDate = new Carbon(Carbon::today()->subDays(6)->toDateString());
        $endDate = new Carbon(Carbon::today()->toDateString());
        $fromDate = $startDate->toDateString();
        $toDate = $endDate->toDateString();
        $previousDays = new Carbon(Carbon::today()->subDays(7)->toDateString());
        $previousDayStartDate = $previousDays->toDateString();
        $previousDayEndDate = $previousDays->addDays(6)->toDateString();
        $previousDaySum = $this->getSaleAmountBetweenDates($previousDayStartDate, $previousDayEndDate);
        
        $formattedFromDate = $startDate->format('d M Y');
        $formattedEndDate  = $endDate->format('d M Y');

        $labels = [];
        $data   = [];
        while ($startDate->lte($endDate)) {
            $labels[] = $startDate->day;
            $dates[] = $startDate->toDateString();
            $startDate->addDay();
        }
        $totalOrders =  $this->getSaleRecordData($fromDate, $toDate, 'days');
        $totalReturns = $this->getReturnOrderAmount($fromDate, $toDate, 'days');
        if (0 > count($totalOrders)) {
            $records =  ['labels' => $labels, 'data' => [], 'fromDate' => $formattedFromDate, 'toDate'=> $formattedEndDate, 'total' => 0 ];
            return $records;
        }
        foreach ($dates as  $date) {
            if (array_key_exists($date, $totalOrders)) {
                if (isset($totalReturns[$date]) && !empty($totalReturns[$date])) {
                    $data[] = $totalOrders[$date] - $totalReturns[$date];
                } else {
                    $data[] = $totalOrders[$date];
                }
            } else {
                $data[] = 0;
            }
        }
        $totalOrderAmount = array_sum($data);
        $percentage = $this->getPercentage($totalOrderAmount, $previousDaySum);
        $salePercentageStatus = $totalOrderAmount > $previousDaySum ? 1 : 0;
        $records =  ['labels'=> $labels, 'data'=> $data, 'fromDate'=> $formattedFromDate, 'toDate'=> $formattedEndDate, 'total'=> $totalOrderAmount,'percentage' => $percentage, 'salePercentageStatus' => $salePercentageStatus];
        return $records;
    }

    private function getLastMonthSaleRecords()
    {
        $startDataOfPreviousMonth = new Carbon('first day of last month');
        $fromDate = $startDataOfPreviousMonth->toDateString();
        $endDataOfPreviousMonth = new Carbon('last day of last month');
        $toDate = $endDataOfPreviousMonth->toDateString();

        $previousDays = new Carbon($startDataOfPreviousMonth->toDateString());
        $previousDayStartDate = $previousDays->subDays(30)->toDateString();
        $previousDayEndDate = $previousDays->addDays(29)->toDateString();
        $previousDaySum = $this->getSaleAmountBetweenDates($previousDayStartDate, $previousDayEndDate);

        $formattedFromDate = $startDataOfPreviousMonth->format('d M Y');
        $formattedToDate  = $endDataOfPreviousMonth->format('d M Y');

        $labels = [];
        $data   = [];
        while ($startDataOfPreviousMonth->lte($endDataOfPreviousMonth)) {
            $labels[] = $startDataOfPreviousMonth->day;
            $dates[] = $startDataOfPreviousMonth->toDateString();
            $startDataOfPreviousMonth->addDay();
        }
        $totalOrders =  $this->getSaleRecordData($fromDate, $toDate, 'days');
        $totalReturns = $this->getReturnOrderAmount($fromDate, $toDate, 'days');
        if (0 > count($totalOrders)) {
            $records =  ['labels'=> $labels, 'data'=> [],'fromDate'=> $formattedFromDate, 'toDate'=> $formattedToDate,'total'=> 0 ];
            return $records;
        }
        foreach ($dates as  $date) {
            if (array_key_exists($date, $totalOrders)) {
                if (isset($totalReturns[$date]) && !empty($totalReturns[$date])) {
                    $data[] = $totalOrders[$date] - $totalReturns[$date];
                } else {
                    $data[] = $totalOrders[$date];
                }
            } else {
                $data[] = 0;
            }
        }
        $totalOrderAmount = array_sum($data);
        $percentage = $this->getPercentage($totalOrderAmount, $previousDaySum);
        $salePercentageStatus = $totalOrderAmount > $previousDaySum ? 1 : 0;

        $records =  ['labels'=> $labels, 'data'=> $data,'fromDate'=> $formattedFromDate, 'toDate'=> $formattedToDate,'total'=> $totalOrderAmount, 'percentage' => $percentage, 'salePercentageStatus' => $salePercentageStatus ];
        return $records;
    }

    private function getCurrentMonthSaleRecords()
    {
        $startDataOfCurrentMonth = new Carbon('first day of this month');
        $endDateOfCurrentMonth = Carbon::today();
        $fromDate = $startDataOfCurrentMonth->toDateString();
        $toDate = $endDateOfCurrentMonth->toDateString();

        $formattedFromDate = $startDataOfCurrentMonth->format('d M Y');
        $formattedToDate  = $endDateOfCurrentMonth->format('d M Y');

        $previousMonthStart = new Carbon('first day of previous month');
        $previousMonthStartDate = $previousMonthStart->toDateString();
        $previousMonthEndDate = Carbon::today()->subMonth()->toDateString();
        $previousDaySum = $this->getSaleAmountBetweenDates($previousMonthStartDate, $previousMonthEndDate);
        $labels = [];
        $data   = [];
        
        for ($i =1 ; $i<=$endDateOfCurrentMonth->day; $i++) {
            $labels[] = $startDataOfCurrentMonth->day;
            $dates[] = $startDataOfCurrentMonth->toDateString();
            $startDataOfCurrentMonth->addDay();
        }
        $totalOrders =  $this->getSaleRecordData($fromDate, $toDate, 'days');
        $totalReturns = $this->getReturnOrderAmount($fromDate, $toDate, 'days');
        if (0 > count($totalOrders)) {
            $records =  ['labels'=> $labels, 'data'=> [],'fromDate'=> $formattedFromDate, 'toDate'=> $formattedToDate, 'total'=> 0 ];
            return $records;
        }
        foreach ($dates as  $date) {
            if (array_key_exists($date, $totalOrders)) {
                if (isset($totalReturns[$date]) && !empty($totalReturns[$date])) {
                    $data[] = $totalOrders[$date] - $totalReturns[$date];
                } else {
                    $data[] = $totalOrders[$date];
                }
            } else {
                $data[] = 0;
            }
        }
        $percentage = $this->getPercentage(array_sum($data), $previousDaySum);
        $salePercentageStatus = array_sum($data) > $previousDaySum ? 1 : 0;
        $records =  ['labels'=> $labels, 'data'=> $data,'fromDate'=> $formattedFromDate, 'toDate'=> $formattedToDate,'total'=> array_sum($data),'percentage' => $percentage, 'salePercentageStatus' => $salePercentageStatus ];
        return $records;
    }

    private function getCurrentYearSaleRecords()
    {
        $currentYearStart = Carbon::createFromDate(Carbon::today()->year, 1, 1);
        $currentYearStartDate = $currentYearStart->toDateString();
        
        $currentYearStartMonth = $currentYearStart->month;
        $currentYearEnd = Carbon::createFromDate(Carbon::today()->year, 12, 31);
        $currentYearEndDate = $currentYearEnd->toDateString();
        $currentYearEndMonth = $currentYearEnd->month;

        $previousYearStart = Carbon::createFromDate(Carbon::now()->subYears(1)->year, 1, 1);
        $previousYearStartDate = $previousYearStart->toDateString();

        $previousYearEnd = Carbon::createFromDate(Carbon::now()->subYears(1)->year, 12, 31);
        $previousYearEndDate = $previousYearEnd->toDateString();

        $previousDaySum = $this->getSaleAmountBetweenDates($previousYearStartDate, $previousYearEndDate);

        $formattedFromDate = $currentYearStart->format('d M Y');
        $formattedToDate  = $currentYearEnd->format('d M Y');

        $labels = [];
        $data   = [];
        while ($currentYearStartMonth <= $currentYearEndMonth) {
            $labels[] = $currentYearStart->format('M');
            $currentYearStart->addMonth();
            $currentYearStartMonth++;
        }
        $totalOrders =  $this->getSaleRecordData($currentYearStartDate, $currentYearEndDate, 'months');
        $totalReturns = $this->getReturnOrderAmount($currentYearStartDate, $currentYearEndDate, 'months');
        if (0 > count($totalOrders)) {
            $records =  ['labels'=> $labels, 'data'=> [], 'fromDate'=> $formattedFromDate, 'toDate'=> $formattedToDate,'total'=> 0 ];
            return $records;
        }
        foreach ($labels as  $key=>$label) {
            if (array_key_exists($key, $totalOrders)) {
                if (isset($totalReturns[$key]) && !empty($totalReturns[$key])) {
                    $data[] = $totalOrders[$key] - $totalReturns[$key];
                } else {
                    $data[] = $totalOrders[$key];
                }
            } else {
                $data[] = 0;
            }
        }
        $totalOrderAmount = array_sum($data);

        $percentage = $this->getPercentage($totalOrderAmount, $previousDaySum);
        $salePercentageStatus = $totalOrderAmount > $previousDaySum ? 1 : 0;
        $records =  ['labels'=> $labels, 'data'=> $data, 'fromDate'=> $formattedFromDate, 'toDate'=> $formattedToDate,'total'=> $totalOrderAmount,'percentage' => $percentage, 'salePercentageStatus' => $salePercentageStatus ];
        return $records;
    }

    private function getReturnOrderAmount($fromDate, $toDate, $type)
    {
        switch ($type) {
            case "hours":
                $totalReturns = OrderReturnRequest::join('order_products', 'op_id', 'orrequest_op_id')
                ->join('order_return_amounts','oramount_orrequest_id', 'orrequest_id')
                ->select([ DB::raw('coalesce((SUM((op_product_price*orrequest_qty)+oramount_tax+oramount_shipping)), 0) AS return_amount'), DB::raw('DATE_FORMAT(orrequest_date, "%k:00") AS perHr')])
                ->whereBetween('orrequest_date', [$fromDate." 00:00:00", $toDate." 23:59:59"])
                ->where('orrequest_status', OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED)
                ->groupBy(DB::raw('CONCAT(DATE_FORMAT(orrequest_date, "%Y-%m-%d %H"), ":00:00.000")'))->pluck('return_amount', 'perHr')->toArray();
            break;
            case "days":
                $totalReturns = OrderReturnRequest::join('order_products', 'op_id', 'orrequest_op_id')
                ->join('order_return_amounts','oramount_orrequest_id', 'orrequest_id')
                ->select([ DB::raw('coalesce((SUM((op_product_price*orrequest_qty)+oramount_tax+oramount_shipping)), 0) AS return_amount'), DB::raw('DATE_FORMAT(orrequest_date, "%Y-%m-%d") AS perDay')])
                ->whereBetween('orrequest_date', [$fromDate." 00:00:00", $toDate." 23:59:59"])
                ->where('orrequest_status', OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED)
                ->groupBy(DB::raw('CONCAT(DATE_FORMAT(orrequest_date, "%Y-%m-%d"), ":00:00.000")'))->pluck('return_amount', 'perDay')->toArray();
            break;
            case "months":
                $totalReturns = OrderReturnRequest::join('order_products', 'op_id', 'orrequest_op_id')
                ->join('order_return_amounts','oramount_orrequest_id', 'orrequest_id')
                ->select([ DB::raw('coalesce((SUM((op_product_price*orrequest_qty)+oramount_tax+oramount_shipping)), 0) AS return_amount'), DB::raw('MONTH(orrequest_date) AS perMonth')])
                ->whereBetween('orrequest_date', [$fromDate." 00:00:00", $toDate." 23:59:59"])
                ->where('orrequest_status', OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED)
                ->groupBy('perMonth')->pluck('return_amount', 'perMonth')->toArray();
            break;
        }
        return $totalReturns;
    }
    /* End Function related to Total Sales Records ] */

    /* [ Start Function related to Total Order Count */
    private function getOrderRecordData($fromDate, $toDate, $type)
    {
        switch ($type) {
            case "hours":
                $totalOrders = Order::select([ DB::raw('count(order_id) AS order_count'), DB::raw('DATE_FORMAT(order_date_added, "%k:00") AS perHr')])->whereBetween('order_date_added', [$fromDate." 00:00:00", $toDate." 23:59:59"])->groupBy(DB::raw('CONCAT(DATE_FORMAT(order_date_added, "%Y-%m-%d %H"), ":00:00.000")'))->pluck('order_count', 'perHr')->toArray();
            break;
            case "days":
                $totalOrders = Order::select([ DB::raw('COUNT(order_id) AS order_count'), DB::raw('DATE_FORMAT(order_date_added, "%Y-%m-%d") AS perDay')])->whereBetween('order_date_added', [$fromDate." 00:00:00", $toDate." 23:59:59"])->groupBy(DB::raw('CONCAT(DATE_FORMAT(order_date_added, "%Y-%m-%d"), ":00:00.000")'))->pluck('order_count', 'perDay')->toArray();
            break;
            case "months":
                $totalOrders = Order::select([ DB::raw('COUNT(order_net_amount) AS order_count'), DB::raw('MONTH(order_date_added) AS perMonth')])->whereBetween('order_date_added', [$fromDate." 00:00:00", $toDate." 23:59:59"])->groupBy('perMonth')->pluck('order_count', 'perMonth')->toArray();
            break;
        }
        return $totalOrders;
    }

    private function getOrderCountBetweenDates($fromDate, $toDate)
    {
        return Order::whereBetween('order_date_added', [$fromDate." 00:00:00", $toDate." 23:59:59"])->count('order_id');
    }

    private function getOrderCountPercentage($newOrder, $oldOrder)
    {
      
        if ($oldOrder == 0 || $newOrder == 0) {
            return 0;
        }
        $total = round(((($newOrder - $oldOrder) / $oldOrder) * 100));
        return ($total < 0) ? 0 : $total;
    }

    private function getTodayOrderCounts()
    {
        $fromDate = Carbon::today()->toDateString();
        $toDate = $fromDate;
        $hours = $this->hours;
        $data = [];

        $formattedFromDate = Carbon::today()->format('d M Y');

        $totalOrderCounts = $this->getOrderRecordData($fromDate, $toDate, "hours");
        
        $previousDayDate = Carbon::today()->subDays(1)->toDateString();
        $previousOrderCount = $this->getOrderCountBetweenDates($previousDayDate, $previousDayDate);

        if (0 > count($totalOrderCounts)) {
            $records =  ['labels'=> $this->hourShow, 'data'=> [], 'fromDate'=> $formattedFromDate, 'toDate'=> $formattedFromDate,'total'=> 0 ];
            return $records;
        }
        foreach ($hours as $key=>$hour) {
            if (array_key_exists($hour, $totalOrderCounts)) {
                $data[$key] =  $totalOrderCounts[$hour];
            } else {
                $data[$key] =  0;
            }
        }
        $orderCount = array_sum($data);
       
        $orderPercentage = $this->getOrderCountPercentage($orderCount, $previousOrderCount);
        $orderPercentageStatus = $orderCount > $previousOrderCount ? 1 : 0;

        $records =  ['labels'=> $this->hourShow, 'data'=> $data, 'fromDate'=> $formattedFromDate, 'toDate'=> $formattedFromDate,'total'=> $orderCount, 'orderPercentageStatus'=> $orderPercentageStatus, 'orderPercentage'=> $orderPercentage ];

        return $records;
    }

    private function getWeeklyOrderCounts()
    {
        $startDate = new Carbon(Carbon::today()->subDays(6)->toDateString());
        $endDate = new Carbon(Carbon::today()->toDateString());
        $fromDate = $startDate->toDateString();
        $toDate = $endDate->toDateString();

        $previousWeeks = new Carbon(Carbon::today()->subDays(7)->toDateString());
        $previousWeekStartDate = $previousWeeks->toDateString();
        $previousWeekEndDate = $previousWeeks->addDays(6)->toDateString();

        $formattedFromDate = $startDate->format('d M Y');
        $formattedToDate = $endDate->format('d M Y');

        $previousOrderCount =  $this->getOrderCountBetweenDates($previousWeekStartDate, $previousWeekEndDate);

        $labels = [];
        $data   = [];
        while ($startDate->lte($endDate)) {
            $labels[] = $startDate->day;
            $dates[] = $startDate->toDateString();
            $startDate->addDay();
        }
        $totalOrders = $this->getOrderRecordData($fromDate, $toDate, "days");
        if (0 > count($totalOrders)) {
            $records =  ['labels'=> $labels, 'data'=> [],'fromDate'=>$formattedFromDate,'toDate'=> $formattedToDate,'total'=> 0 ];
            return $records;
        }
        foreach ($dates as  $date) {
            if (array_key_exists($date, $totalOrders)) {
                $data[] = $totalOrders[$date];
            } else {
                $data[] = 0;
            }
        }
        $orderCount = array_sum($data);
        $orderPercentage = $this->getOrderCountPercentage($orderCount, $previousOrderCount);
        $orderPercentageStatus = $orderCount > $previousOrderCount ? 1 : 0;
        $records =  ['labels'=> $labels, 'data'=> $data,'fromDate'=> $formattedFromDate,'toDate'=>$formattedToDate, 'total'=> $orderCount,'orderPercentageStatus'=> $orderPercentageStatus, 'orderPercentage'=> $orderPercentage ];
        return $records;
    }

    private function getLastMonthOrderCounts()
    {
        $startDataOfPreviousMonth = new Carbon('first day of last month');
        $startDate = $startDataOfPreviousMonth->toDateString();

        $endDataOfPreviousMonth = new Carbon('last day of last month');
        $endDate = $endDataOfPreviousMonth->toDateString();
        $previousMonth = new Carbon($startDataOfPreviousMonth->toDateString());
        $previousMonthStartDate = $previousMonth->subDays(30)->toDateString();
        $previousMonthEndDate = $previousMonth->addDays(29)->toDateString();

        $previousOrderCount =  $this->getOrderCountBetweenDates($previousMonthStartDate, $previousMonthEndDate);

        $formattedFromDate = $startDataOfPreviousMonth->format('d M Y');
        $formattedToDate = $endDataOfPreviousMonth->format('d M Y');
        
        $labels = [];
        $data   = [];
        while ($startDataOfPreviousMonth->lte($endDataOfPreviousMonth)) {
            $labels[] = $startDataOfPreviousMonth->day;
            $dates[] = $startDataOfPreviousMonth->toDateString();
            $startDataOfPreviousMonth->addDay();
        }
        $totalOrders = $this->getOrderRecordData($startDate, $endDate, "days");
        if (0 > count($totalOrders)) {
            return $records =  ['labels'=> $labels, 'data'=> [],'total'=> 0,'fromDate'=>$formattedFromDate, 'toDate'=> $formattedToDate ];
        }
        foreach ($dates as  $date) {
            if (array_key_exists($date, $totalOrders)) {
                $data[] = $totalOrders[$date];
            } else {
                $data[] = 0;
            }
        }
        $orderCount = array_sum($data);
        $orderPercentage = $this->getOrderCountPercentage($orderCount, $previousOrderCount);
        $orderPercentageStatus = $orderCount > $previousOrderCount ? 1 : 0;
        $records =  ['labels'=> $labels, 'data'=> $data,'fromDate'=>$formattedFromDate,'toDate'=>$formattedToDate,'total'=> $orderCount, 'orderPercentageStatus'=> $orderPercentageStatus, 'orderPercentage'=> $orderPercentage ];
        return $records;
    }

    private function getCurrentMonthOrderCounts()
    {
        $startDataOfCurrentMonth = new Carbon('first day of this month');
        $endDateOfCurrentMonth = Carbon::today();
        $startDate = $startDataOfCurrentMonth->toDateString();
        $endDate = $endDateOfCurrentMonth->toDateString();
        $previousMonthStart = new Carbon('first day of previous month');
        $previousMonthStartDate = $previousMonthStart->toDateString();
        $previousMonthEndDate = Carbon::today()->subMonth()->toDateString();

        $previousOrderCount =  $this->getOrderCountBetweenDates($previousMonthStartDate, $previousMonthEndDate);
        
        $formattedFromDate = $startDataOfCurrentMonth->format('d M Y');
        $formattedToDate = $endDateOfCurrentMonth->format('d M Y');

        $labels = [];
        $data   = [];
        for ($i =1 ; $i<=$endDateOfCurrentMonth->day; $i++) {
            $labels[] = $startDataOfCurrentMonth->day;
            $dates[] = $startDataOfCurrentMonth->toDateString();
            $startDataOfCurrentMonth->addDay();
        }
        $totalOrders = $this->getOrderRecordData($startDate, $endDate, "days");
        if (0 > count($totalOrders)) {
            return $records =  ['labels'=> $labels, 'data'=> [],'fromDate'=>$formattedFromDate,'toDate'=>$formattedToDate ,'total'=> 0 ];
        }
        foreach ($dates as  $date) {
            if (array_key_exists($date, $totalOrders)) {
                $data[] = $totalOrders[$date];
            } else {
                $data[] = 0;
            }
        }
        $orderCount = array_sum($data);
        $orderPercentage = $this->getOrderCountPercentage($orderCount, $previousOrderCount);
        $orderPercentageStatus = $orderCount > $previousOrderCount ? 1 : 0;

        $records =  ['labels'=> $labels, 'data'=> $data,'fromDate'=>$formattedFromDate,'toDate'=>$formattedToDate, 'total'=> $orderCount, 'orderPercentageStatus'=> $orderPercentageStatus, 'orderPercentage'=> $orderPercentage ];
        return $records;
    }

    private function getAnuallyOrderCounts()
    {
        $currentYearStart = Carbon::createFromDate(Carbon::today()->year, 1, 1);
        $currentYearStartDate = $currentYearStart->toDateString();
        $currentYearStartMonth = $currentYearStart->month;
        
        $currentYearEnd = Carbon::createFromDate(Carbon::today()->year, 12, 31);
        $currentYearEndDate = $currentYearEnd->toDateString();
        $currentYearEndMonth = $currentYearEnd->month;

        $previousYearStart = Carbon::createFromDate(Carbon::now()->subYears(1)->year, 1, 1);
        $previousYearStartDate = $previousYearStart->toDateString();

        $previousYearEnd = Carbon::createFromDate(Carbon::now()->subYears(1)->year, 12, 31);
        $previousYearEndDate = $previousYearEnd->toDateString();

        $previousOrderCount =  $this->getOrderCountBetweenDates($previousYearStartDate, $previousYearEndDate);

        $formattedFromDate = $currentYearStart->format('d M Y');
        $formattedToDate = $currentYearEnd->format('d M Y');

        $labels = [];
        $data   = [];
        while ($currentYearStartMonth <= $currentYearEndMonth) {
            $labels[] = $currentYearStart->format('M');
            $currentYearStart->addMonth();
            $currentYearStartMonth++;
        }
        $totalOrders = $this->getOrderRecordData($currentYearStartDate, $currentYearEndDate, "months");
        if (0 > count($totalOrders)) {
            $records =  ['labels'=> $labels, 'data'=> [],'fromDate'=>$formattedFromDate , 'toDate'=> $formattedToDate,'total'=> 0 ];
            return $records;
        }
        foreach ($labels as  $key=>$label) {
            if (array_key_exists($key, $totalOrders)) {
                $data[] = $totalOrders[$key];
            } else {
                $data[] = 0;
            }
        }
        $orderCount = array_sum($data);
        $orderPercentage = $this->getOrderCountPercentage($orderCount, $previousOrderCount);
        $orderPercentageStatus = $orderCount > $previousOrderCount ? 1 : 0;

        $records =  ['labels'=> $labels, 'data'=> $data,'fromDate'=> $formattedFromDate, 'toDate'=> $formattedToDate,'total'=> $orderCount, 'orderPercentageStatus'=> $orderPercentageStatus, 'orderPercentage'=> $orderPercentage ];
        return $records;
    }
    /* End Function related to Total Order Count ] */

    public function getDashboardReportsData(Request $request, $reportType)
    {
        $records = [];
        switch ($reportType) {
            case "resentCustomers":
                $records = $this->getRecentCustomers();
            break;
            case "resentOrders":
                $records = $this->getRecentOrders();
            break;
            case "returnOrders":
                $records = $this->getReturnOrders();
            break;
            case "inventory":
                $records = Product::getAlertProducts($request)->toArray();
            break;
        }
        return jsonresponse(true, '', $records);
    }

    public function getTopReferrers(Request $request)
    {
        $analyticsViewId = Configuration::getValue('GOOGLE_ANALYTICS_VIEW_ID');
        if (isset($analyticsViewId) && !empty($analyticsViewId)) {
            config(['analytics.view_id' => $analyticsViewId ]);
            $response = Analytics::performQuery(
                Period::days(30),
                'ga:users',
                ['dimensions' => 'ga:source,ga:medium']
            );
            $sessionData = collect($response['rows'] ?? [])->map(function (array $dateRow) {
                return [
                    'source' => $dateRow[0],
                    'name' => $dateRow[1],
                    'visitors'=>(int) $dateRow[2]
                ];
            });
            return jsonresponse(true, '', $sessionData);
        }
        return jsonresponse(false, __('Something went wrong!! '));
    }

    public function getProfitByType(Request $request, $type)
    {
        $startDate =  Carbon::today()->subDays(30)->toDateString();
        $endDate =  Carbon::today()->toDateString();
        switch ($type) {
            case "today":
                $records =  $this->getTodayProfitRecord();
            break;
            case "weekly":
                $records =  $this->getWeeklyProfitRecords();
            break;
            case "lastMonth":
                $records =  $this->getLastMonthProfitRecords();
            break;
            case "currentMonth":
                $records =  $this->getCurrentMonthProfitRecords();
            break;
            case "currentYear":
                $records =  $this->getCurrentYearProfitRecords();
            break;
        }
        return jsonresponse(true, '', $records);
    }

    private function getProfitRecordData($startDate, $endDate, $type)
    {
        switch ($type) {
            case "hours":
                $totalProfit = Order::select([
                    DB::raw('SUM(  (op_product_price*op_qty) - (op_prod_cost*op_qty))   AS gross_profit'),
                    DB::raw('DATE_FORMAT(order_date_added, "%k:00") AS perHr')
                ])->whereBetween('order_date_added', [$startDate." 00:00:00", $endDate." 23:59:59"])
                ->leftjoin('order_products', 'op_order_id', 'order_id')
                ->groupBy(DB::raw('perHr'))->pluck('gross_profit', 'perHr')->toArray();
            break;
            case "days":
                $totalProfit = Order::select([
                    DB::raw('SUM(  (op_product_price*op_qty) - (op_prod_cost*op_qty))   AS gross_profit'),
                    DB::raw('DATE_FORMAT(order_date_added, "%Y-%m-%d") AS perDay')
                ])->whereBetween('order_date_added', [$startDate." 00:00:00", $endDate." 23:59:59"])
                ->leftjoin('order_products', 'op_order_id', 'order_id')
                ->groupBy(DB::raw('perDay'))->pluck('gross_profit', 'perDay')->toArray();
            break;
            case "months":
                $totalProfit = Order::select([
                    DB::raw('SUM(  (op_product_price*op_qty) - (op_prod_cost*op_qty))   AS gross_profit'),
                    DB::raw('MONTH(order_date_added) AS perMonth')
                ])->whereBetween('order_date_added', [$startDate." 00:00:00", $endDate." 23:59:59"])
                ->leftjoin('order_products', 'op_order_id', 'order_id')
                ->groupBy('perMonth')->pluck('gross_profit', 'perMonth')->toArray();
            break;
        }
        return $totalProfit;
    }

    private function getProfitBetweenDates($fromDate, $toDate)
    {
        return Order::select([
                DB::raw('SUM(  (op_product_price*op_qty) - (prod_cost*op_qty))   AS gross_profit')
            ])
            ->leftjoin('order_products', 'op_order_id', 'order_id')
            ->join('products', 'op_product_id', 'prod_id')
            ->whereBetween('order_date_added', [$fromDate." 00:00:00", $toDate." 23:59:59"])->get()
            ->first();
    }

    private function getTodayProfitRecord()
    {
        $fromDate = Carbon::today()->toDateString();
        $previousDayDate = Carbon::today()->subDays(1)->toDateString();
        $previousDaySum = $this->getProfitBetweenDates($previousDayDate, $previousDayDate);
        $previousDaySum = $previousDaySum->gross_profit ? $previousDaySum->gross_profit : 0;
        $toDate = $fromDate;
        $hours = $this->hours;
        $formattedFromDate = Carbon::today()->format('d M Y');
        $totalOrders = $this->getProfitRecordData($fromDate, $toDate, 'hours');
        $totalReturns = $this->getReturnProfitAmount($fromDate, $toDate, 'hours');
        if (0 > count($totalOrders)) {
            return $records =  ['labels'=> $hours, 'data'=> [],'fromDate'=> $formattedFromDate, 'toDate'=> $formattedFromDate ];
        }
        foreach ($hours as $key => $hour) {
            /*if (array_key_exists($hour, $totalOrders)) {
                $data[$key] =  $totalOrders[$hour];
            } else {
                $data[$key] =  0;
            }*/
            if (isset($totalReturns[$hour]) && !empty($totalReturns[$hour]) && isset($totalOrders[$hour]) && !empty($totalOrders[$hour])) {
                $totalSale = $totalOrders[$hour] - $totalReturns[$hour];
                $data[$key] = ($totalSale < 0) ? 0 : $totalSale;
            } else {
                $data[$key] =  (!empty($totalOrders[$hour]) ? $totalOrders[$hour] : 0);
            }
        }
        $totalAmount = round(array_sum($data), 2);
        $percentage = $this->getPercentage($totalAmount, $previousDaySum);
        $percentageStatus = $totalAmount > $previousDaySum ? 1 : 0;
        $records =  ['labels'=> $this->hourShow, 'data'=> $data,'total'=> $totalAmount, 'percentage' => $percentage, 'fromDate'=> $formattedFromDate, 'toDate'=> $formattedFromDate,'percentageStatus' => $percentageStatus ];
        return $records;
    }

    private function getWeeklyProfitRecords()
    {
        $startDate = new Carbon(Carbon::today()->subDays(6)->toDateString());
        $endDate = new Carbon(Carbon::today()->toDateString());
        $fromDate = $startDate->toDateString();
        $toDate = $endDate->toDateString();
        $formattedFromDate = $startDate->format('d M Y');
        $formattedEndDate  = $endDate->format('d M Y');

        $previousDays = new Carbon(Carbon::today()->subDays(7)->toDateString());
        $previousDayStartDate = $previousDays->toDateString();
        $previousDayEndDate = $previousDays->addDays(6)->toDateString();
        $previousWeeklySum = $this->getProfitBetweenDates($previousDayStartDate, $previousDayEndDate);
        $previousWeeklySum = $previousWeeklySum->gross_profit ? $previousWeeklySum->gross_profit : 0;

        $labels = [];
        $data   = [];
        while ($startDate->lte($endDate)) {
            $labels[] = $startDate->day;
            $dates[] = $startDate->toDateString();
            $startDate->addDay();
        }
        $totalOrders =  $this->getProfitRecordData($fromDate, $toDate, 'days');
        $totalReturns = $this->getReturnProfitAmount($fromDate, $toDate, 'days');
        
        if (0 > count($totalOrders)) {
            $records =  ['labels'=> $labels, 'data'=> [], 'fromDate'=> $formattedFromDate, 'toDate'=> $formattedEndDate, 'total'=> 0 ];
            return $records;
        }
        foreach ($dates as  $date) {
            /*if (array_key_exists($date, $totalOrders)) {
                $data[] = $totalOrders[$date];
            } else {
                $data[] = 0;
            }*/
            if (array_key_exists($date, $totalOrders)) {
                if (isset($totalReturns[$date]) && !empty($totalReturns[$date])) {
                    $data[] = $totalOrders[$date] - $totalReturns[$date];
                } else {
                    $data[] = $totalOrders[$date];
                }
            } else {
                $data[] = 0;
            }
        }
        $totalAmount = round(array_sum($data), 2);
        $percentage = $this->getPercentage($totalAmount, $previousWeeklySum);
        $percentageStatus = $totalAmount > $previousWeeklySum ? 1 : 0;
        $records =  ['labels'=> $labels, 'data'=> $data,'total'=> $totalAmount, 'percentage' => $percentage, 'fromDate'=> $formattedFromDate, 'toDate'=> $formattedEndDate,'percentageStatus' => $percentageStatus ];
        return $records;
    }

    private function getLastMonthProfitRecords()
    {
        $startDataOfPreviousMonth = new Carbon('first day of last month');
        $fromDate = $startDataOfPreviousMonth->toDateString();
        $endDataOfPreviousMonth = new Carbon('last day of last month');
        $toDate = $endDataOfPreviousMonth->toDateString();

        $previousDays = new Carbon($startDataOfPreviousMonth->toDateString());
        $previousDayStartDate = $previousDays->subDays(30)->toDateString();
        $previousDayEndDate = $previousDays->addDays(29)->toDateString();
        $previousDaySum = $this->getProfitBetweenDates($previousDayStartDate, $previousDayEndDate);
        $previousDaySum = $previousDaySum->gross_profit ? $previousDaySum->gross_profit: 0;
        $formattedFromDate = $startDataOfPreviousMonth->format('d M Y');
        $formattedEndDate  = $endDataOfPreviousMonth->format('d M Y');

        $labels = [];
        $data   = [];
        while ($startDataOfPreviousMonth->lte($endDataOfPreviousMonth)) {
            $labels[] = $startDataOfPreviousMonth->day;
            $dates[] = $startDataOfPreviousMonth->toDateString();
            $startDataOfPreviousMonth->addDay();
        }
        $totalOrders =  $this->getProfitRecordData($fromDate, $toDate, 'days');
        $totalReturns = $this->getReturnProfitAmount($fromDate, $toDate, 'days');
        if (0 > count($totalOrders)) {
            $records =  ['labels'=> $labels, 'data'=> [],'fromDate'=> $formattedFromDate,
            'toDate'=> $formattedEndDate,'total'=> 0 ];
            return $records;
        }
        foreach ($dates as  $date) {
            /*if (array_key_exists($date, $totalOrders)) {
                $data[] = $totalOrders[$date];
            } else {
                $data[] = 0;
            }*/
            if (array_key_exists($date, $totalOrders)) {
                if (isset($totalReturns[$date]) && !empty($totalReturns[$date])) {
                    $data[] = $totalOrders[$date] - $totalReturns[$date];
                } else {
                    $data[] = $totalOrders[$date];
                }
            } else {
                $data[] = 0;
            }
        }

        $totalAmount = round(array_sum($data), 2);
        $percentage = $this->getPercentage($totalAmount, $previousDaySum);
        $percentageStatus = $totalAmount > $previousDaySum ? 1 : 0;
        $records =  ['labels'=> $labels, 'data'=> $data,'total'=> $totalAmount, 'percentage' => $percentage, 'fromDate'=> $formattedFromDate, 'toDate'=> $formattedEndDate,'percentageStatus' => $percentageStatus ];
        return $records;
    }

    private function getCurrentMonthProfitRecords()
    {
        $startDataOfCurrentMonth = new Carbon('first day of this month');
        $endDateOfCurrentMonth = Carbon::today();
        $fromDate = $startDataOfCurrentMonth->toDateString();
        $toDate = $endDateOfCurrentMonth->toDateString();

        $formattedFromDate = $startDataOfCurrentMonth->format('d M Y');
        $formattedToDate  = $endDateOfCurrentMonth->format('d M Y');
        
        $previousMonthStart = new Carbon('first day of previous month');
        $previousMonthStartDate = $previousMonthStart->toDateString();
        $previousMonthEndDate = Carbon::today()->subMonth()->toDateString();
        $previousMonthSum = $this->getProfitBetweenDates($previousMonthStartDate, $previousMonthEndDate);
        $previousMonthSum = $previousMonthSum->gross_profit ? $previousMonthSum->gross_profit : 0;

        $labels = [];
        $data   = [];
        $dates = [];
        while ($startDataOfCurrentMonth->lte(Carbon::today())) {
            $labels[] = $startDataOfCurrentMonth->day;
            $dates[] = $startDataOfCurrentMonth->toDateString();
            $startDataOfCurrentMonth->addDay();
        }
        if(empty($dates)) {
            $dates[] = Carbon::now()->toDateString();
        }
        $totalOrders =  $this->getProfitRecordData($fromDate, $toDate, 'days');
        $totalReturns = $this->getReturnProfitAmount($fromDate, $toDate, 'days');
        if (0 > count($totalOrders)) {
            $records =  ['labels'=> $labels, 'data'=> [],'fromDate'=> $formattedFromDate, 'toDate'=> $formattedToDate, 'total'=> 0 ];
            return $records;
        }
        foreach ($dates as  $date) {
            /*if (array_key_exists($date, $totalOrders)) {
                $data[] = $totalOrders[$date];
            } else {
                $data[] = 0;
            }*/
            if (array_key_exists($date, $totalOrders)) {
                if (isset($totalReturns[$date]) && !empty($totalReturns[$date])) {
                    $data[] = $totalOrders[$date] - $totalReturns[$date];
                } else {
                    $data[] = $totalOrders[$date];
                }
            } else {
                $data[] = 0;
            }
        }

        $percentage = $this->getPercentage(array_sum($data), $previousMonthSum);
        $percentageStatus = array_sum($data) > $previousMonthSum ? 1 : 0;
        $records =  ['labels'=> $labels, 'data'=> $data,'fromDate'=> $formattedFromDate, 'toDate'=> $formattedToDate,'total'=> array_sum($data),'percentage' => $percentage, 'percentageStatus' => $percentageStatus ];
        return $records;
    }

    private function getCurrentYearProfitRecords()
    {
        $currentYearStart = Carbon::createFromDate(Carbon::today()->year, 1, 1);
        $currentYearStartDate = $currentYearStart->toDateString();
        
        $currentYearStartMonth = $currentYearStart->month;
        $currentYearEnd = Carbon::createFromDate(Carbon::today()->year, 12, 31);
        $currentYearEndDate = $currentYearEnd->toDateString();
        $currentYearEndMonth = $currentYearEnd->month;

        $previousYearStart = Carbon::createFromDate(Carbon::now()->subYears(1)->year, 1, 1);
        $previousYearStartDate = $previousYearStart->toDateString();

        $previousYearEnd = Carbon::createFromDate(Carbon::now()->subYears(1)->year, 12, 31);
        $previousYearEndDate = $previousYearEnd->toDateString();

        $previousYearSum = $this->getProfitBetweenDates($previousYearStartDate, $previousYearEndDate);
        $previousYearSum = $previousYearSum->gross_profit ? $previousYearSum->gross_profit : 0;

        $formattedFromDate = $currentYearStart->format('d M Y');
        $formattedToDate  = $currentYearEnd->format('d M Y');

        $labels = [];
        $data   = [];
        while ($currentYearStartMonth <= $currentYearEndMonth) {
            $labels[] = $currentYearStart->format('M');
            $currentYearStart->addMonth();
            $currentYearStartMonth++;
        }
        $totalOrders =  $this->getProfitRecordData($currentYearStartDate, $currentYearEndDate, 'months');
        $totalReturns = $this->getReturnProfitAmount($currentYearStartDate, $currentYearEndDate, 'months');
        if (0 > count($totalOrders)) {
            $records =  ['labels'=> $labels, 'data'=> [], 'fromDate'=> $formattedFromDate, 'toDate'=> $formattedToDate,'total'=> 0 ];
            return $records;
        }
        foreach ($labels as  $key=>$label) {
            /*if (array_key_exists($key, $totalOrders)) {
                $data[] = $totalOrders[$key];
            } else {
                $data[] = 0;
            }*/
            if (array_key_exists($key, $totalOrders)) {
                if (isset($totalReturns[$key]) && !empty($totalReturns[$key])) {
                    $data[] = $totalOrders[$key] - $totalReturns[$key];
                } else {
                    $data[] = $totalOrders[$key];
                }
            } else {
                $data[] = 0;
            }
        }
        $totalOrderAmount = round(array_sum($data), 2);
        $percentage = $this->getPercentage($totalOrderAmount, $previousYearSum);
        $percentageStatus = $totalOrderAmount > $previousYearSum ? 1 : 0;
        $records =  ['labels'=> $labels, 'data'=> $data, 'fromDate'=> $formattedFromDate, 'toDate'=> $formattedToDate,'total'=> $totalOrderAmount,'percentage' => $percentage, 'percentageStatus' => $percentageStatus ];
        return $records;
    }

    public function getDataByLocation()
    {
        config(['analytics.view_id' => Configuration::getValue('GOOGLE_ANALYTICS_VIEW_ID')]);
        $response = Analytics::performQuery(
            Period::days(30),
            'ga:sessions',
            ['dimensions' => 'ga:country']
        );
        $responseData = collect($response['rows'] ?? [])->map(function (array $dateRow) {
            return [
                'country' => $dateRow[0],
                'session' => (int) $dateRow[1],
            ];
        });
    }

    public function clearCache()
    {
        \Artisan::call("cache:clear");
        \Artisan::call("config:clear");
        \Artisan::call("route:clear");
        \Artisan::call("view:clear");
        \Artisan::call("storage:link");
        
        // $data = file_get_contents(base_path('resources/lang/labels.php'));
        // $fp = fopen(base_path('resources/lang/en.json'), 'w');
        // fwrite($fp, $data);
        return jsonresponse(true, __('NOTI_CACHE_CLEARED'), []);
    }

    public function getDashboardData(Request $request)
    {
        $records['todaySaleRecords'] = $this->getTodaySaleRecords();
        $records['todayOrderRecords'] = $this->getTodayOrderCounts();
        $records['todayProfitRecords'] = $this->getTodayProfitRecord();
        $records['resentCustomers'] = $this->getRecentCustomers();
        $records['resentOrders'] = $this->getRecentOrders();
        $records['returnOrders'] = $this->getReturnOrders(OrderReturnRequest::RETURN);
        $records['cancelOrders'] = $this->getReturnOrders(OrderReturnRequest::CANCELLATION);
        $inventory = Product::getAlertProducts($request)->toArray();
        $records['inventory'] =  $inventory['data'];

        // $records['resentCustomers'] = $records['inventory'] =  $records['cancelOrders'] = $records['returnOrders'] = $records['resentOrders'] = [];
        return jsonresponse(true, '', $records);
    }

    private function getRecentCustomers()
    {
        return User::select(['user_id',DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'),'user_email','user_phone'])
        ->orderBy('user_id', 'desc')
        ->take(4)->get();
    }

    private function getRecentOrders()
    {
        return  Order::select(['order_id','order_user_id','order_net_amount'])
                    ->with(['user' => function ($query) {
                        $query->select(['user_id',DB::raw('CONCAT(user_fname, " ", user_lname) as user_name')]);
                    }])
                    ->whereIn('order_status', [Order::ORDER_STATUS_OPEN, Order::ORDER_STATUS_COMPLETED ])
                    ->orderBy('order_id', 'desc')
                    ->take(5)->get();
    }

    private function getReturnOrders($type)
    {
        return  OrderReturnRequest::select(['orrequest_order_id','orrequest_user_id','orrequest_qty'])
                ->with(['user' => function ($query) {
                    $query->select(['user_id',DB::raw('CONCAT(user_fname, " ", user_lname) as user_name')]);
                }])->where('orrequest_type', $type)->orderBy('orrequest_id', 'desc')->take(5)->get();
    }

    private function getSaleAmountBetweenDates($fromDate, $toDate)
    {
        return Order::whereBetween('order_date_added', [$fromDate." 00:00:00", $toDate." 23:59:59"])->sum('order_net_amount');
    }

    private function getPercentage($newAmount, $oldAmount)
    {
        if ($oldAmount == 0 || $newAmount == 0) {
            return 0;
        }
        $total = round(((($newAmount - $oldAmount) / $oldAmount) * 100));
        return ($total < 0) ? 0 : $total;
    
    }
    
    private function getReturnProfitAmount($fromDate, $toDate, $type)
    {
        switch ($type) {
            case "hours":
                $totalReturns = OrderReturnRequest::join('order_products', 'op_id', 'orrequest_op_id')
                    ->join('order_return_amounts','oramount_orrequest_id', 'orrequest_id')
                    ->select([ DB::raw('coalesce((SUM((op_product_price*orrequest_qty)- (op_prod_cost) )), 0) AS return_profit_amount'), DB::raw('DATE_FORMAT(orrequest_date, "%k:00") AS perHr')])
                    ->whereBetween('orrequest_date', [$fromDate." 00:00:00", $toDate." 23:59:59"])
                    ->where('orrequest_status', OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED)
                    ->groupBy(DB::raw('CONCAT(DATE_FORMAT(orrequest_date, "%Y-%m-%d %H"), ":00:00.000")'))->pluck('return_profit_amount', 'perHr')->toArray();
            break;
            case "days":
                $totalReturns = OrderReturnRequest::join('order_products', 'op_id', 'orrequest_op_id')
                ->join('order_return_amounts','oramount_orrequest_id', 'orrequest_id')
                ->select([ DB::raw('coalesce((SUM((op_product_price*orrequest_qty)- (op_prod_cost) )), 0) AS return_profit_amount'), DB::raw('DATE_FORMAT(orrequest_date, "%Y-%m-%d") AS perDay')])
                ->whereBetween('orrequest_date', [$fromDate." 00:00:00", $toDate." 23:59:59"])
                ->where('orrequest_status', OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED)
                ->groupBy(DB::raw('CONCAT(DATE_FORMAT(orrequest_date, "%Y-%m-%d"), ":00:00.000")'))->pluck('return_profit_amount', 'perDay')->toArray();
            break;
            case "months":
                $totalReturns = OrderReturnRequest::join('order_products', 'op_id', 'orrequest_op_id')
                ->join('order_return_amounts','oramount_orrequest_id', 'orrequest_id')
                ->select([ DB::raw('coalesce((SUM((op_product_price*orrequest_qty)- (op_prod_cost) )), 0) AS return_profit_amount'), DB::raw('MONTH(orrequest_date) AS perMonth')])
                ->whereBetween('orrequest_date', [$fromDate." 00:00:00", $toDate." 23:59:59"])
                ->where('orrequest_status', OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED)
                ->groupBy('perMonth')->pluck('return_profit_amount', 'perMonth')->toArray();
            break;
        }
        return $totalReturns;
    }
}
