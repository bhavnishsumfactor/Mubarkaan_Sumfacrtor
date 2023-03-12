<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\YokartController;
use App\Models\Configuration;

class RestoreController extends YokartController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function reset(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $restoreTime = Configuration::getValue('CONF_RESTORE_SCHEDULE_TIME');
        if (env('RESTORE') == true && strtotime($restoreTime) == strtotime(date('Y-m-d H:i:s')) || strtotime(date('Y-m-d H:i:s')) > strtotime($restoreTime)) {
            $dateTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +4 hours'));
            Configuration::saveValue('CONF_RESTORE_SCHEDULE_TIME', $dateTime);
            \Cache::forget('configuration');
            $this->restoreDabase();
        }
    }
    public function restoreDabase()
    {
        \Artisan::call("restoreDatabase");
    }
    public function mobilePreview()
    {
        $width = "375px";
        $height = "624px";
        $deviceClass = "smartphone";
        return view('restore-system.mobile', compact('width', 'height', 'deviceClass'));
    }
}
