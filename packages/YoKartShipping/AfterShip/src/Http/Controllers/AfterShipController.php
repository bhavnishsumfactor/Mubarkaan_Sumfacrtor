<?php

namespace YokartShipping\AfterShip\Http\Controllers;

use App\Http\Controllers\PackageYokartController;
use YoKartShipping\AfterShip\Models\AfterShipping;
use App\Events\OrderDelivered;
use App\Models\Package;

class AfterShipController extends PackageYokartController
{
    public static function getTrackingCouriers()
    {
        $response = [];
        $records = Package::getRecordBySlug('AfterShip');
        if (count($records) == 0) {
            return ['status' => false, 'data' => []];
        }
        $parameters  = [
            'url'     => 'couriers',
            'headers' => ["aftership-api-key:". $records['AFTERSHIP_KEY']."","content-type: application/json"]
        ];
        $data = self::sendRequest($parameters['url'], $parameters);
        $couriers = [];
        if (isset($data['meta']['code']) && $data['meta']['code'] == 200) {
            foreach ($data['data']['couriers'] as  $courier) {
                $couriers[$courier['slug']] = $courier['name'];
            }
        }
        return ['status' => true, 'data' => $couriers];
    }

    public static function getTrackingInfo($trackingNumber, $courierCode, $orderId)
    {
        $response = [];
        $records = Package::getRecordBySlug('AfterShip');
        if (count($records) == 0) {
            return ['status' => false, 'data' => []];
        }
        $parameters  = [
            'url'     => 'trackings/'.$courierCode.'/'.$trackingNumber,
            'headers' => ["aftership-api-key:". $records['AFTERSHIP_KEY']."","content-type: application/json"]
        ];
        $data = self::sendRequest($parameters['url'], $parameters);
        if (isset($data['meta']['code']) && $data['meta']['code'] == 200) {
            $response['status'] = true;
            $response['data']['tracking'] = $data['data']['tracking'];
            $response['data']['tracking']['checkpoints'] = array_reverse($data['data']['tracking']['checkpoints']);
            if ($data['data']['tracking']['tag'] == "Delivered") {
                event(new OrderDelivered($orderId));
            }
        } else {
            $response['status'] = false;
            $response['data']['checkpoints'] = [];
        }
        
        return $response;
    }

    public static function createTracking($trackingNumber, $courierCode, $orderId)
    {
        $response = [];
        $records = Package::getRecordBySlug('AfterShip');
        if (empty($records['AFTERSHIP_KEY'])) {
            $response['status'] = false;
            $response['data'] = [];
        }

        $parameters  = [
            'url'     => 'https://api.aftership.com/v4/trackings',
            'headers' => [ "aftership-api-key:". $records['AFTERSHIP_KEY']."", "content-type: application/json"],
            'data' =>   [   'tracking' => [
                                'slug' => $courierCode,
                                'tracking_number' => $trackingNumber,
                                'title' => $orderId,
                                'order_id' => $orderId
                            ] 
                        ]
        ];
        $data = self::sendRequest($parameters['url'], $parameters);
        if (isset($data['meta']['code']) && $data['meta']['code'] == 200) {
            $response['data']['tracking']['checkpoints'] = array_reverse($data['data']['tracking']['checkpoints']);
        }
        $response['status'] = true;
        return $response;

    }

    public static function sendRequest(string $urlEndpoint, array $requestParam = [], array $body = [])
    {
        $url = AfterShipping::URL.'/'.AfterShipping::VERSION.'/'.$urlEndpoint;
        if (!empty($body)) {
            $body = json_encode($body);
        }
        $data = executeCurl($url, $body, $requestParam['headers']);
        return $data;
    }
}
