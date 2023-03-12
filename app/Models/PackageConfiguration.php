<?php

namespace App\Models;

use App\Models\YokartModel;

class PackageConfiguration extends YokartModel
{
  public $timestamps = false;

  public static function getConfigurations($package_id)
  {
      return PackageConfiguration::where('pconf_pkg_id', $package_id)->pluck('pconf_value', 'pconf_key')->toArray();
  } 
}
