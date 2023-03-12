<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\YokartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UserCard;
use App\Models\Package;
use App\Models\PackageConfiguration;
use App\Helpers\PaymentGatewayHelper;
use Auth;

class CardController extends YokartController
{
    public function list(Request $request)
    {
        $package = Package::select('pkg_slug', 'pkg_id')->where('pkg_type', Package::PKG_TYPE['paymentGateways'])->where('pkg_publish', config('constants.YES'))->where('pkg_card_type', config('constants.YES'))->first();
        
        $response = UserCard::getCardsListing(Auth::guard('api')->user()->user_id);
        $response['pub_key'] = "";
        if ($package->pkg_id) {
            $packageConf = PackageConfiguration::getConfigurations($package->pkg_id);
            if (!empty($packageConf["STRIPE_KEY"])) {
                $response['pub_key'] = $packageConf["STRIPE_KEY"];
            }
            $customerData = UserCard::getCustomerId(Auth::guard('api')->user()->user_id, $package->pkg_slug);
            if (empty($customerData)) {
                $payment = new PaymentGatewayHelper($package->pkg_slug);
                $payment->createCustomer(Auth::guard('api')->user()->user_id);
            }
        }
        
        unset($response["type"]);
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }

    public function saveCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $package = Package::select('pkg_slug')->where('pkg_type', Package::PKG_TYPE['paymentGateways'])->where('pkg_publish', config('constants.YES'))->where('pkg_card_type', config('constants.YES'))->first();
        if (!isset($package->pkg_slug) || empty($package->pkg_slug)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_SOMETHING_WENT_WRONG"));
        }
        $payment = new PaymentGatewayHelper($package->pkg_slug);
        
        $customerData = UserCard::getCustomerId(Auth::guard('api')->user()->user_id, $package->pkg_slug);
        if (empty($customerData)) {
            $payment = new PaymentGatewayHelper($package->pkg_slug);
            $customer = $payment->createCustomer(Auth::guard('api')->user()->user_id);
            $customerId = $customer->id ?? '';
        } else {
            $customerId = $customerData->ucard_customer_id;
        }

        $response = $payment->saveCardByToken($request->input('token'), $customerId);
        if ($response['status']) {
            return apiresponse(config('constants.SUCCESS'), appLang("NOTI_CARD_ADDED"));
        } else {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_SOMETHING_WENT_WRONG"));
        }
    }

    public function updateDefaultCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $package = Package::select('pkg_slug')->where('pkg_type', Package::PKG_TYPE['paymentGateways'])->where('pkg_publish', config('constants.YES'))->where('pkg_card_type', config('constants.YES'))->first();
        if (!isset($package->pkg_slug) || empty($package->pkg_slug)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_SOMETHING_WENT_WRONG"));
        }
        $payment = new PaymentGatewayHelper($package->pkg_slug);
        if ($payment->updateDefaultCard(Auth::guard('api')->user()->user_id, $request['id'])) {
            return apiresponse(config('constants.SUCCESS'), appLang("NOTI_DEFAULT_CARD_UPDATED"));
        } else {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_SOMETHING_WENT_WRONG"));
        }
    }

    public function cardDelete(Request $request, $card_id)
    {
        $package = Package::select('pkg_slug')->where('pkg_type', Package::PKG_TYPE['paymentGateways'])->where('pkg_card_type', config('constants.YES'))->where('pkg_publish', config('constants.YES'))->first();
        if (!isset($package->pkg_slug) || empty($package->pkg_slug)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_SOMETHING_WENT_WRONG"));
        }
        $payment = new PaymentGatewayHelper($package->pkg_slug);
        if ($payment->deleteCard($card_id, Auth::guard('api')->user()->user_id)) {
            return apiresponse(config('constants.SUCCESS'), appLang("NOTI_CARD_REMOVED"));
        } else {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_SOMETHING_WENT_WRONG"));
        }
    }
}
