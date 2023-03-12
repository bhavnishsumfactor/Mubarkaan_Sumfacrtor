<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminYokartController;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\PackageConfiguration;

class PaymentGatewayController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
    }

    public function getListing(Request $request)
    {
        return jsonresponse(true, null, Package::getRecordsByType($request->all(), 'paymentGateways'));
    }
    public function updateStatus(Request $request, $id)
    {
        $request  = $request->all();
        if( isset($request['status']) && !empty($request['status']) ) {
            $publishStatus = ($request['status'] == 'true') ? 1 : 0;
            $updateData = ['pkg_publish' => $publishStatus];
            $message = ($request['status'] == 'true') ? __('NOTI_PAYMENTMETHOD_PUBLISHED') : __('NOTI_PAYMENTMETHOD_UNPUBLISHED');
        }

        if(isset($request['pkg_environment']) && !empty($request['pkg_environment'])) {
            $environmentStatus = ($request['pkg_environment'] == 'true') ? 1 : 0;
            $updateData = ['pkg_environment' => $environmentStatus];
            $message = __('NOTI_ENVRIONMENT_STATUS_UPDATED');
        }
        Package::where('pkg_id', $id)->update($updateData);
        if (isset($request['cardType']) && !empty($request['cardType'])) {
            Package::where('pkg_type', Package::PKG_TYPE['paymentGateways'])->where('pkg_card_type', config('constants.YES'))->where('pkg_id', '!=', $id)->update(['pkg_publish' => 0]);
        }
        return jsonresponse(true, $message);
    }

    public function update(Request $request, $id)
    {
        Package::where('pkg_id', $id)->update(['pkg_name' => $request->input('pkg_name')]);
        $configurations = json_decode($request->input('configurations'), true);
        foreach ($configurations as  $configuration) {
            PackageConfiguration::where('pconf_pkg_id', $id)->where('pconf_key', $configuration['key'])->update([
                'pconf_value' => $configuration['value'],
            ]);
        }
        imageUpload($request, $id, 'paymentGateways');
        return jsonresponse(true, __('NOTI_PAYMENTMETHOD_UPDATED'));
    }
}
