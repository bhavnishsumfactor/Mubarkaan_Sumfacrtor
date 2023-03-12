<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\YokartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Auth;
use App\Models\UserAddress;
use App\Models\Country;

class AddressController extends YokartController
{
    public function list(Request $request, $page)
    {
        $response = [];
        $addresses = UserAddress::getAddressesForApp(Auth::guard('api')->user()->user_id, $page)->toArray();
        $response['addresses'] = $addresses['data'];
        $response['total'] = $addresses['total'];
        $response['pages'] = ceil($addresses['total'] / $addresses['per_page']);
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }

    public function addOrUpdate(Request $request)
    {
        $lastId = $request->input('addr_id');
        if (!empty($lastId)) {
            $userAddress = UserAddress::where('addr_id', $lastId)->first();
            if (! Auth::guard('api')->user()->can('update', $userAddress)) {
                return apiresponse(config('constants.ERROR'), appLang("NOTI_YOU_DO_NOT_OWN_THIS_RECORD"));
            }
        }
        $validator = Validator::make($request->all(), [
            'addr_name' => 'required',
            'addr_title' => 'required',
            'addr_address1' => 'required',
            'addr_city' => 'required',
            'addr_country_id' => 'required',
            'addr_state_id' => 'required',
            'addr_postal_code' => 'required',
            'addr_email' => 'required',
            'addr_phone_country_code' => 'required',
            'addr_phone' => 'required',
            'addr_shipping_default' => 'required',
            'addr_billing_default' => 'required'
        ]);
        $validator->setAttributeNames([
            'addr_name' => 'name',
            'addr_title' => 'title',
            'addr_address1' => 'address1',
            'addr_city' => 'city name',
            'addr_country_id' => 'country name',
            'addr_state_id' => 'state name',
            'addr_postal_code' => 'postal code',
            'addr_email' => 'email',
            'addr_phone_country_code' => 'phone country',
            'addr_phone' => 'phone number',
            'addr_shipping_default' => 'shipping default',
            'addr_billing_default' => 'billing default'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }

        $nameArr = explode(" ", $request->input('addr_name'));
        $firstName = ($nameArr[0] ?? '');
        array_shift($nameArr);
        $lastName = implode(" ", $nameArr);
        
        $records = $request->except('addr_phone_country_code', 'addr_id', 'addr_name');
        $records['addr_first_name'] = $firstName;
        $records['addr_last_name'] = $lastName;
        $records['addr_user_id'] = Auth::guard('api')->user()->user_id;
        $records['addr_phone_country_id'] = Country::getCountryIdByCountryCode($request->input('addr_phone_country_code'));
        if (!empty($lastId)) {
            UserAddress::where('addr_id', $lastId)->update($records);
            $message = appLang("NOTI_ADDRESS_UPDATED");
        } else {
            // $existingAddresses = UserAddress::where('addr_user_id', Auth::guard('api')->user()->user_id)->count();
            // if ($existingAddresses == 0) {
            //     $records['addr_shipping_default'] = config('constants.YES');
            //     $records['addr_billing_default'] = config('constants.YES');
            // }
            $lastId = UserAddress::create($records)->addr_id;
            $message = appLang("NOTI_ADDRESS_ADDED");
        }

        $updateValues = [];
        if ($records['addr_shipping_default'] == config('constants.YES') && $records['addr_billing_default'] == config('constants.YES')) {
            $updateValues = [
                'addr_shipping_default' => config('constants.NO'),
                'addr_billing_default' => config('constants.NO')
            ];
        } elseif ($records['addr_shipping_default'] == config('constants.YES')) {
            $updateValues = [
                'addr_shipping_default' => config('constants.NO'),
            ];
        } elseif ($records['addr_billing_default'] == config('constants.YES')) {
            $updateValues = [
                'addr_billing_default' => config('constants.NO'),
            ];
        }
        if (count($updateValues) != 0) {
            UserAddress::where('addr_user_id', Auth::guard('api')->user()->user_id)->where('addr_id', '!=', $lastId)->update($updateValues);
        }
        $response = [];
        $response['address'] = UserAddress::getAddressForApp(Auth::guard('api')->user()->user_id, $lastId);
        return apiresponse(config('constants.SUCCESS'), $message, $response);
    }

    public function updateDefaultAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'addr_id' => 'required',
            'type' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $typeField = ($request->input('type') == 'billing') ? 'addr_billing_default' : 'addr_shipping_default';
        UserAddress::where('addr_user_id', Auth::guard('api')->user()->user_id)->where('addr_id', $request->input('addr_id'))->update([$typeField => config('constants.YES')]);
        UserAddress::where('addr_user_id', Auth::guard('api')->user()->user_id)->where('addr_id', '!=', $request->input('addr_id'))->update([$typeField => config('constants.NO')]);
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_DEFAULT_ADDRESS_UPDATED"));
    }

    public function delete(Request $request, $addr_id)
    {
        $userAddress = UserAddress::where('addr_id', $addr_id)->first();
        if (! Auth::guard('api')->user()->can('update', $userAddress)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_YOU_DO_NOT_OWN_THIS_RECORD"));
        }
        UserAddress::where(['addr_user_id' => Auth::guard('api')->user()->user_id, 'addr_id' => $addr_id])->delete();
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_ADDRESS_REMOVED"));
    }
}
