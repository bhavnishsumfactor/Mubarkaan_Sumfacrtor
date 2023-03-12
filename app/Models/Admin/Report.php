<?php

namespace App\Models\Admin;

use App\Models\YokartModel;
use Carbon\Carbon;
use App\Models\Configuration;

class Report extends YokartModel
{
    /*public const SECTIONS = [
        [
            'Sales' => [ // component route name as key, Name as value
                'saleOverTime' => 'Sale over time',
                'saleByProduct' => 'Sales by product',
                'saleByProductVariant' => 'Sales by product variant',
                'saleByCustomerName' => 'Sale by customer name',
                'saleByAverageOrderOverTime' => 'Average order value over time',
                'saleByTrafficReferrer' => 'Sales by traffic referrer'
            ],
            'Acquisition' => [
                'sessionOverTime' => 'Sessions over time',
                'sessionByReferrer' => 'Sessions by referrer',
                'sessionByLocation' => 'Sessions by location'
            ]
        ],
        [
            'Customers' => [
                'customerOverTime' => 'Customers over time',
                'oneTimeCustomer' => 'One-time customers',
                'returningCustomer' => 'Returning customers',
                'locationCustomer'  => 'Location Customer',
                'firstTimeVsReturnCustomer' => 'First-time vs returning customer sales'
            ],
            'Behaviour' => [
                'sessionByLandingPage' => 'Sessions by landing page',
                'sessionByDevice' => 'Sessions by device',
                'topOnlineStoreWithResult'  => 'Top online store searches',
                'topOnlineStoreWithNoResult'  => 'Top online store with no results',
                //'onlineStoreConversion' => 'Online store conversion over time',
                //'productRecommendationConvertion'  => 'Product recommendation',
            ]
        ],
        [
            'Profit margin' => [
                'profitByProduct' => 'Profit by product',
                'profitByProductVariant' => 'Profit by product variant SKU',
            ],
        ]
    ];

    public static function getReportSection()
    {
        return self::SECTIONS;
    }*/

    public static function getLastThiryDayDates($request)
    {
        $installationDate = Configuration::getValue('INSTALLATION_DATE');
        $startCarbonDate = new Carbon(Carbon::today()->subDays(30)->toDateString());
        if (isset($installationDate) && !empty($installationDate)) {
            $installation = new Carbon($installationDate);
            if($startCarbonDate->lte($installation)) {
                $date['startDateCarbon'] = new Carbon($installationDate);
            } else {
                $date['startDateCarbon'] = $startCarbonDate;
            }
        } else {
            $date['startDateCarbon'] = $startCarbonDate;
        }
        $date['endDateCarbon'] = new Carbon(Carbon::today()->toDateString());
        $date['startDate'] =  $date['startDateCarbon']->toDateString();
        $date['endDate'] =  Carbon::today()->toDateString();
        if(isset($request['dateRange']) && !empty($request['dateRange']) ){
            $newDate = explode(',', $request['dateRange']);
            $date['startDate'] = $newDate[0] ? $newDate[0] : $date['startDate'];
            $date['endDate']  = $newDate[1] ? $newDate[1] : $date['endDate'];
            $date['startDateCarbon'] = $newDate[0] ? new Carbon($newDate[0]) : new Carbon($date['startDate']);
            $date['endDateCarbon']  =  $newDate[1] ? new Carbon($newDate[1]) : new Carbon($date['endDate']);
        }
        return $date;
    }

    public static function getLastSevenDayDates($request)
    {
        $installationDate = Configuration::getValue('INSTALLATION_DATE');
        $startCarbonDate = new Carbon(Carbon::today()->subDays(7)->toDateString());
        if (isset($installationDate) && !empty($installationDate)) {
            $installation = new Carbon($installationDate);
            if($startCarbonDate->lte($installation)) {
                $date['startDateCarbon'] = new Carbon($installationDate);
            } else {
                $date['startDateCarbon'] = $startCarbonDate;
            }
        } else {
            $date['startDateCarbon'] = $startCarbonDate;
        }
        $date['endDateCarbon'] = new Carbon(Carbon::today()->toDateString());
        $date['startDate'] =  $date['startDateCarbon']->toDateString();
        $date['endDate'] =  Carbon::today()->toDateString();
        if(isset($request['dateRange']) && !empty($request['dateRange']) ){
            $newDate = explode(',', $request['dateRange']);
            $date['startDate'] = $newDate[0] ? $newDate[0] : $date['startDate'];
            $date['endDate']  = $newDate[1] ? $newDate[1] : $date['endDate'];
            $date['startDateCarbon'] = $newDate[0] ? new Carbon($newDate[0]) : new Carbon($date['startDate']);
            $date['endDateCarbon']  =  $newDate[1] ? new Carbon($newDate[1]) : new Carbon($date['endDate']);
        }
        return $date;
    }
}
