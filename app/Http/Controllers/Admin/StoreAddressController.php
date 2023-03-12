<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\StoreAddress;
use App\Models\StoreTiming;
use App\Models\Country;
use App\Models\Admin\AdminRole;
use App\Models\State;
use App\Models\Configuration;
use App\Http\Requests\StoreAddressRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StoreAddressController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::PICKUP_ADDRESS)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getListing(Request $request)
    {
        $data = [];
        $data['addresses'] = StoreAddress::getRecords($request->all());

        $data['showEmpty'] = 0;
        if (empty($request['search']) && $data['addresses']->count() == 0) {
            $data['showEmpty'] = 1;
        }
        return jsonresponse(true, null, $data);
    }


    public function pageLoadData()
    {
        $records['days'] = StoreTiming::DAYS;
        $records['countries'] = Country::pluck('country_name', 'country_id');
        $records['timeFormat'] = Configuration::getValue('ADMIN_TIME_FORMAT');
        return jsonresponse(true, null, $records);
    }

    public function getStates(Request $request)
    {
        return jsonresponse(true, null, State::getStatesByCountryId($request->input('coutry-id')));
    }

  
    public function store(StoreAddressRequest $request)
    {
        if (!canWrite(AdminRole::PICKUP_ADDRESS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $days = [];
        if (empty($request->input('saddr_phone_country_id'))) {
            $request['saddr_phone_country_id'] = Country::getCountryIdByCountryCode('us');
        } else {
            $request['saddr_phone_country_id'] = Country::getCountryIdByCountryCode($request->input('saddr_phone_country_id'));
        }
        $insertId = StoreAddress::create($request->except('days'))->saddr_id;
        if (!empty($request->input('days')) && $request->input('saddr_shop_open_status') == 1) {
            $days = json_decode($request->input('days'), true);
        } elseif ($request->input('saddr_shop_open_status') == 2) {
            foreach (StoreTiming::DAYS as $key => $day) {
                $days[$day][0]  = ['open'=> $request->input('start_time'), 'close' => $request->input('end_time') ];
            }
        } else {
            foreach (array_slice(StoreTiming::DAYS, 0, 5, true) as $key => $day) {
                $days[$day][0] = ['open'=> $request->input('start_time'), 'close' => $request->input('end_time') ];
            }
        }
        $this->saveStoreTiming($insertId, $days, $request->input('saddr_shop_open_status'));
        return jsonresponse(true, __('NOTI_PICKUP_CREATED'));
    }

    public function edit($id)
    {
        $timeFormat = Configuration::getValue('ADMIN_TIME_FORMAT');
        $storeAddress = StoreAddress::where('saddr_id', $id)->first();
        if (isset($storeAddress['saddr_phone_country_id']) && !empty($storeAddress['saddr_phone_country_id'])) {
            $countryData  = Country::getRecordById($storeAddress['saddr_phone_country_id']);
            $records['country_code']  = Str::lower($countryData->country_code);
        } else {
            $records['country_code']  = 'us';
        }
        $records['record'] = $storeAddress;
        $timingArray = [];
        $storeTimings = StoreTiming::where('st_saddr_id', $id)->get()->toArray();
        $days = [];
        foreach ($storeTimings as $key => $timing) {
            if (!in_array(StoreTiming::DAYS[$timing['st_day']], $days)) {
                $i = 0;
                $days[] = StoreTiming::DAYS[$timing['st_day']];
            }
            if ($storeAddress->saddr_shop_open_status == 2 || $storeAddress->saddr_shop_open_status == 3) {
                $timingArray[ strtolower(StoreTiming::DAYS[$timing['st_day']]) ][$i]['open'] =  str_replace(":", "", date("H:i", strtotime($timing['st_from'])));
                $timingArray[ strtolower(StoreTiming::DAYS[$timing['st_day']]) ][$i]['close'] =  str_replace(":", "", date("H:i", strtotime($timing['st_to'])));
                if ($timeFormat == 1) {
                    $from = date("h:i A", strtotime($timing['st_from']));
                    $to = date("h:i A", strtotime($timing['st_to']));
                } else {
                    $from = $timing['st_from'];
                    $to = $timing['st_to'];
                }
                $storeAddress->start_time = $from;
                $storeAddress->end_time   = $to;
            } else {
                $timingArray[ strtolower(StoreTiming::DAYS[$timing['st_day']]) ][$i]['open'] =  str_replace(['AM','PM'], ['',''], str_replace(":", "", $timing['st_from']));
                $timingArray[ strtolower(StoreTiming::DAYS[$timing['st_day']]) ][$i]['close'] = str_replace(['AM','PM'], ['','',], str_replace(":", "", $timing['st_to']));
                
                $storeAddress->start_time = date("h:iA", strtotime(str_replace(['AM','PM'], ['',''], $timing['st_from'])));
                $storeAddress->end_time   = date("h:iA", strtotime(str_replace(['AM','PM'], ['',''], $timing['st_to'])));
            }
            $timingArray[ strtolower(StoreTiming::DAYS[$timing['st_day']]) ][$i]['id'] = '';
            $timingArray[ strtolower(StoreTiming::DAYS[$timing['st_day']]) ][$i]['isOpen'] = true;
            $i++;
        }
        foreach (StoreTiming::DAYS as $key => $day) {
            $day = strtolower($day);
            if (!array_key_exists($day, $timingArray)) {
                $i=0;
                $timingArray[$day][$i]['open'] = '';
                $timingArray[$day][$i]['close'] = '';
                $timingArray[$day][$i]['id'] = '';
                $timingArray[$day][$i]['isOpen'] = false;
            }
        }
        $records['timings'] = $timingArray;
        $records['countries'] = Country::pluck('country_name', 'country_id');
        $records['states'] = State::getStatesByCountryId($storeAddress->saddr_country_id);
        $records['timeFormat'] = Configuration::getValue('ADMIN_TIME_FORMAT');
        
        return jsonresponse(true, null, $records);
    }

    
    public function update(StoreAddressRequest $request, $id)
    {
        if (!canWrite(AdminRole::PICKUP_ADDRESS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        StoreTiming::where('st_saddr_id', $id)->delete();
        
        if (!empty($request->input('days')) && $request->input('saddr_shop_open_status') == 1) {
            $days = json_decode($request->input('days'), true);
        } elseif ($request->input('saddr_shop_open_status') == 2) {
            foreach (StoreTiming::DAYS as $key => $day) {
                $days[$day][0]  = ['open'=> $request->input('start_time'), 'close' => $request->input('end_time') ];
            }
        } else {
            foreach (array_slice(StoreTiming::DAYS, 0, 5, true) as $key => $day) {
                $days[$day][0] = ['open'=> $request->input('start_time'), 'close' => $request->input('end_time') ];
            }
        }
        $this->saveStoreTiming($id, $days, $request->input('saddr_shop_open_status'));
        if (isset($request['saddr_phone_country_id']) && !empty($request['saddr_phone_country_id'])) {
            $request['saddr_phone_country_id'] = Country::getCountryIdByCountryCode($request->input('saddr_phone_country_id'));
        } else {
            $request['saddr_phone_country_id'] = Country::getCountryIdByCountryCode('us');
        }
        StoreAddress::where('saddr_id', $id)->update($request->except('_method', 'days', 'start_time', 'end_time'));
        return jsonresponse(true, __('NOTI_PICKUP_UPDATED'));
    }

  
    public function saveStoreTiming($saddrId, $days, $pickupStatus = 0)
    {
        foreach ($days as $key => $value) {
            foreach ($value as $timeslot) {
                if ( !empty($timeslot['open']) && !empty($timeslot['close']) ) {
                    if ($pickupStatus == 1) {
                        $from =  substr_replace($timeslot['open'], ":", 2, 0);
                        $to =  substr_replace($timeslot['close'], ":", 2, 0);
                    } else {
                        $fromTime =  Carbon::createFromTimeString($timeslot['open']);
                        $from = $fromTime->format('H:i');
                        $toTime =  Carbon::createFromTimeString($timeslot['close']);
                        $to =  $toTime->format('H:i');
                    }
                    StoreTiming::create([
                        'st_saddr_id' => $saddrId,
                        'st_day' => StoreTiming::DaysValues[ucfirst($key)],
                        'st_from' => $from,
                        'st_to' => $to
                    ]);
                }
            }
        }
    }
    
    public function destroy($id)
    {
        if (!canWrite(AdminRole::PICKUP_ADDRESS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        StoreAddress::where('saddr_id', $id)->delete();
        return jsonresponse(true, __('NOTI_PICKUP_DELETED'));
    }
}
