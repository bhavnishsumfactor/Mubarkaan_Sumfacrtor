<?php

namespace App\Http\Controllers\Admin;

use App\Models\SmsTemplate;
use App\Helpers\EmailHelper;
use App\Models\Admin\AdminRole;
use App\Models\Notification;
use App\Models\Package;
use Carbon\Carbon;
use App\Models\PackageConfiguration;
use App\Http\Controllers\Admin\AdminYokartController;
use Illuminate\Http\Request;
use App\Http\Requests\SmsTemplateRequest;
use App\Http\Requests\PackageConfigurationRequest;
use View;

class SmsTemplateController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::THIRD_PARTY_INTEGRATION)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getListing(Request $request)
    {
        $smsTemplates = SmsTemplate::getAllSmsTemplates($request->all());
        $status = ($smsTemplates->count() > 0) ? true : false;
        return jsonresponse($status, '', $smsTemplates);
    }

    public function update(SmsTemplateRequest $request, SmsTemplate $smsTemplate)
    {
        if (!canWrite(AdminRole::THIRD_PARTY_INTEGRATION)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        SmsTemplate::where('stpl_id', $request->input('stpl_id'))->update([
            'stpl_name' => $request->input('stpl_name'),
            'stpl_body' => $request->input('stpl_body'),
            'stpl_updated_by_id' => $this->admin->admin_id,
            'stpl_updated_at' => Carbon::now()
        ]);
        return jsonresponse(true, __('NOTI_SMS_TEMPLATE_UPDATED'));
    }

    public function updateStatus(Request $request, $package_id)
    {
        if (!canWrite(AdminRole::THIRD_PARTY_INTEGRATION)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $publishStatus = ($request->input('status') == 'true') ? 1 : 0;
        SmsTemplate::where('stpl_id', $package_id)->update(['stpl_publish' => $publishStatus, 'stpl_updated_by_id' => $this->admin->admin_id, 'stpl_updated_at' => Carbon::now() ]);
        $message = ($request->input('status') == 'true') ? __('NOTI_SMS_TEMPLATE_PUBLISHED') : __('NOTI_SMS_TEMPLATE_UNPUBLISHED');
        return jsonresponse(true, $message);
    }

    public function getPackages(Request $request)
    {
        $packageHtml = '';
        $packages = Package::getSmsPackages();
        if (!empty($packages) && count($packages)==1) {
            $package_id = '';
            foreach ($packages as $key=>$val) {
                $package_id = $key;
            }
            $package = Package::getPackage($package_id);
            $configurations = PackageConfiguration::getConfigurations($package_id);
            $configurations['status'] = $package->pkg_publish;
            $packageHtml = view($package->pkg_slug.'::form', $configurations)->render();
        }
        return jsonresponse(true, '', ['packages'=>$packages, 'packageHtml'=>$packageHtml]);
    }

    public function getConfigurations(Request $request, $package_id)
    {
        $package = Package::getPackage($package_id);
        $configurations = PackageConfiguration::getConfigurations($package_id);
        return view($package->pkg_slug.'::form', $configurations);
    }

    public function saveConfigurations(PackageConfigurationRequest $request, $package_id)
    {
        if (!canWrite(AdminRole::THIRD_PARTY_INTEGRATION)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        foreach ($request->except('twilio_status') as $pconf_key => $pconf_value) {
            $count = PackageConfiguration::where('pconf_pkg_id', $package_id)
          ->where('pconf_key', $pconf_key)->count();
            if ($count == 0) {
                PackageConfiguration::insert([
              'pconf_pkg_id' => $package_id,
              'pconf_key' => $pconf_key,
              'pconf_value' => $pconf_value
            ]);
            } else {
                PackageConfiguration::where('pconf_pkg_id', $package_id)
                ->where('pconf_key', $pconf_key)
                ->update(['pconf_value' => $pconf_value]);
            }
        }
        if ($request->input('twilio_status') != '') {
            Package::where('pkg_id', $package_id)->update(['pkg_publish' => $request->input('twilio_status') ]);
        }
        return jsonresponse(true, __('NOTI_CONFIGURATION_UPDATE'));
    }
}
