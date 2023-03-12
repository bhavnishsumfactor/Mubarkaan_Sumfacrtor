<?php

namespace App\Models;

use App\Models\YokartModel;
use Carbon\Carbon;
use App\Models\Configuration;
use Auth;

class Timezone extends YokartModel
{
    public const TWELVE_HOUR = 1;
    public const TWENTY_FOUR = 2;
    
    public const TIME_FORMAT_BACKEND = [
        self::TWELVE_HOUR => 'g:i A',
        self::TWENTY_FOUR => 'H:i'
    ];

    public const DATE_FORMAT_BACKEND = [
        'DD-MM-YYYY' => 'd-m-Y',
        'YYYY-MM-DD' => 'Y-m-d',
        'MM-DD-YYYY' => 'm-d-yy',
        'MMM DD, YYYY' => 'M d, Y'
    ];

    public const TWELVE_HOUR_TIME_FORMAT = '';
    public const TWENTY_FOUR_TIME_FORMAT = '';

    public const TIME_FORMAT = [
        self::TWELVE_HOUR => 'AM/PM',
        self::TWENTY_FOUR => '24 hours'
    ];

    public const DATE_FORMAT = [
        'DD-MM-YYYY' => 'dd-mm-yy',
        'YYYY-MM-DD' => 'yy-mm-dd',
        'MM-DD-YYYY' => 'mm-dd-yy',
        'MMM DD, YYYY' => 'M d, Y'
    ];


    public static function getAllTimeZones()
    {
        return timezone_identifiers_list();
    }

    public static function getAllTimeFormat()
    {
        return self::TIME_FORMAT;
    }

    public static function getAllDateFormat()
    {
        return self::DATE_FORMAT;
    }

    /** This function will get the utc time based on the user timezone */
    public static function getUserUtcDateTime($dateTime)
    {
        $date = Carbon::parse($dateTime, Auth::user()->user_timezone)->setTimezone('UTC');
        return $date->format('Y-m-d H:i:s');
    }

    /** This function will get the utc time based on the admin timezone selection */
    public static function getAdminUtcTime($dateTime)
    {
        $date = Carbon::parse($dateTime, Configuration::getValue('ADMIN_TIMEZONE'))->setTimezone('UTC');
        return $date->format('Y-m-d H:i:s');
    }

    public static function getConvertedAdminDateTime($dateTime)
    {
        $dt = Carbon::parse($dateTime);
        $format = self::dateFormat();
        return $dt->setTimezone(Configuration::getValue('ADMIN_TIMEZONE'))->format($format);
    }

    public static function getConvertedDateTime($dateTime, $time)
    {
        $dt = Carbon::parse($dateTime);
        $format = self::dateFormat($time);
        if (Auth::check()) {
            $date = $dt->setTimezone(Auth::user()->user_timezone)->format($format);
            return $date;
        } else {
            return $dt->setTimezone(Configuration::getValue('ADMIN_TIMEZONE'))->format($format);
        }
    }

    public static function getConvertedTime($dateTime)
    {
        $dt = Carbon::parse($dateTime);
        $format = self::timeFormat($dateTime);
        if (Auth::check()) {
            $date = $dt->setTimezone(Auth::user()->user_timezone)->format($format);
            return $date;
        } else {
            return $dt->setTimezone(Configuration::getValue('ADMIN_TIMEZONE'))->format($format);
        }
    }

    public static function formatTime($time)
    {
        $dt = Carbon::parse($time);
        $format = self::timeFormat($time, false);
        $format = str_replace(":s", "", $format);
        return $dt->format($format);
    }

    public static function getUtcDateTimeForAdmin($dateTime)
    {
        if (!empty(Auth::guard('admin')->user()->admin_default_timezone)) {
            $timezone = Auth::guard('admin')->user()->admin_default_timezone;
        } else {
            $timezone = Configuration::getValue(['ADMIN_TIMEZONE']);
        }
        return Carbon::parse($dateTime, $timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');
    }

    private static function dateFormat($time = true)
    {
        $configurationData = Configuration::getKeyValues(['ADMIN_DATE_FORMAT','ADMIN_TIME_FORMAT']);
        
        if ($time == true) {
            $format = self::DATE_FORMAT_BACKEND[$configurationData['ADMIN_DATE_FORMAT']]  ." " . self::TIME_FORMAT_BACKEND[$configurationData['ADMIN_TIME_FORMAT']];
        } else {
            $format = self::DATE_FORMAT_BACKEND[$configurationData['ADMIN_DATE_FORMAT']];
        }
        return $format;
    }

    private static function timeFormat($time, $seconds = true)
    {
        $configurationData = Configuration::getKeyValues(['ADMIN_TIME_FORMAT']);
        $format = self::TIME_FORMAT_BACKEND[$configurationData['ADMIN_TIME_FORMAT']];
        return $format;
    }
}
