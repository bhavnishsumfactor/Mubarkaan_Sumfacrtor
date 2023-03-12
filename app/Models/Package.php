<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\PackageConfiguration;

class Package extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'pkg_id';

    const PKG_ENVIRONMENT_SANDBOX = 1;
    const PKG_ENVIRONMENT_PRODUCTION = 0;

    const PKG_TYPE = [
        'sms' => 1,
        'tax' => 2,
        'shipping' => 3,
        'paymentGateways' => 4,
        'trackShipping' => 5
    ];

    public static function getPublishedPackages($type)
    {
        return Package::where('pkg_publish', config('constants.YES'))->where('pkg_type', self::PKG_TYPE[$type])
          ->pluck('pkg_name', 'pkg_id');
    }
    
    public static function getSmsPackages()
    {
        return Package::where('pkg_type', self::PKG_TYPE['sms'])
          ->pluck('pkg_name', 'pkg_id');
    }

    public static function getActivePackage($type)
    {
        return Package::select('pkg_id', 'pkg_slug')->where('pkg_type', self::PKG_TYPE[$type])->where('pkg_publish', config('constants.YES'))
          ->first();
    }

    public static function getPackage($packageId)
    {
        return Package::select('pkg_name', 'pkg_slug', 'pkg_publish')->where('pkg_id', $packageId)->first();
    }
    public static function getPackageBySlug($slug)
    {
        return Package::select('pkg_environment', 'pkg_name', 'pkg_publish')->where('pkg_slug', $slug)->first();
    }

    public static function getActivePaymentGateways($type)
    {
        return Package::select('pkg_id', 'pkg_card_type', 'pkg_slug')->where('pkg_type', self::PKG_TYPE[$type])->where('pkg_publish', 1)->where('pkg_is_deleted',0)
          ->get();
    }

    public function configurations()
    {
        return $this->hasMany('App\Models\PackageConfiguration', 'pconf_pkg_id', 'pkg_id');
    }

    public static function getRecordBySlug($slug)
    {
        return Package::join('package_configurations as config', 'config.pconf_pkg_id', 'packages.pkg_id')
        ->where('packages.pkg_slug', $slug)->pluck('config.pconf_value', 'config.pconf_key');
    }
    
    public static function getRecordsByType($request, $type)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $search = !empty($request['search']) ? $request['search'] : '';
        $query = Package::with('configurations')
        ->where('pkg_type', self::PKG_TYPE[$type])->where('pkg_is_deleted',0);
        if (!empty($search)) {
            $query->where('pkg_name', 'like', '%' . $search . '%');
        }
        return $query->latest('pkg_id')->paginate((int)$per_page);
    }

    public static function getTrackShippingPackage()
    {
        $query = Package::with('configurations')
            ->where('pkg_type', self::PKG_TYPE['trackShipping']);
        return $query->first();
    }

    public static function updateTrackingApiInformation($request)
    {
        try {
            Package::where('pkg_type', self::PKG_TYPE['trackShipping'])->update(['pkg_publish' => $request['pkg_publish']]);
            PackageConfiguration::where('pconf_key', 'AFTERSHIP_KEY')->update(['pconf_value' => $request['AFTERSHIP_KEY']]);
            return true;
        } catch(\Exception $e) {
            return false;
        }
    }
}
