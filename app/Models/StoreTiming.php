<?php

namespace App\Models;

use App\Models\YokartModel;
use Carbon\Carbon;
use DB;
class StoreTiming extends YokartModel
{
    public $timestamps = false;
    public const DAYS = [
        '1' => 'Monday',
        '2' => 'Tuesday',
        '3' => 'Wednesday',
        '4' => 'Thursday',
        '5' => 'Friday',
        '6' => 'Saturday',
        '0' => 'Sunday',
    ];
    
    public const DaysValues = [
        'Monday' => 1,
        'Tuesday' => 2,
        'Wednesday' => 3,
        'Thursday' => 4,
        'Friday' => 5,
        'Saturday' => 6,
        'Sunday' => 0
    ];

    protected $fillable = [
        'st_saddr_id', 'st_day', 'st_from', 'st_to'
    ];

    public static function getStoreWeekDays($id)
    {
        return self::where('st_saddr_id',$id)->distinct('st_saddr_id')->pluck('st_day');
    }

    public static function getTimings($pickupId, $day,$pickDate)
    {
        $configuration = Configuration::getKeyValues(['ADMIN_TIMEZONE', 'PICKUP_AVAILABLE']);
        $carbonCurrentTime = Carbon::now();
        $carbonCurrentTime->setTimezone($configuration['ADMIN_TIMEZONE']);
        if(!empty($configuration['ADMIN_TIMEZONE'])) {
            $time = $carbonCurrentTime->addHours($configuration['PICKUP_AVAILABLE'])->format('H:i');
        } else {
            $time = $carbonCurrentTime->format('H:i');
        }
        
        $pickup = self::select('st_from' , 'st_to')->where('st_saddr_id', $pickupId)->where('st_day', $day);
        if ($carbonCurrentTime->dayOfWeek == $day && $pickDate == $carbonCurrentTime->format('Y-m-d')) {
            $pickup->where(function($query) use ($time) {
                $query->where('st_to', '>', $time);
            });
        }
        return $pickup->get()->toArray();
    }
}