<?php

namespace App\Http\Controllers\Admin;

use App\Models\Configuration;
use App\Models\Product;
use App\Models\Timezone;
use App\Models\Country;
use App\Models\InstagramFeedToken;
use App\Helpers\SocialHelper;
use App\Models\State;
use App\Models\Currency;
use App\Http\Controllers\Admin\AdminYokartController;
use Illuminate\Http\Request;
use Artisan;

class ConfigurationController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
    }

    public function getSettings(Request $request, $type = '')
    {
        $data = [];
        if (!empty($request['keys'])) {
            $request['keys'] = explode(',', $request['keys']);
            $data  = Configuration::getKeyValues($request['keys']);
            if (in_array('WEIGHT_UNIT', $request['keys'])) {
                $data['units'] = Product::PRODUCT_UNIT_SYSTEMS;
            }
            if (in_array('CMS_SKILL_LEVEL', $request['keys'])) {
                $username = InstagramFeedToken::pluck('iftoken_username')->first();
                $data['insta_feed_username'] = ($username ?? '');
                $data['authorize_url'] = SocialHelper::authorizeInstagram();
            }
            if (in_array('LISTING_RECORDS_PER_PAGE', $request['keys'])) {
                $data['four_column'] = Product::FOUR_COLUMN_PER_PAGE_RECORD;
                $data['five_column'] = Product::FIVE_COLUMN_PER_PAGE_RECORD;
            }
        }
        return jsonresponse(true, null, $data);
    }

    public function updateSettings(Request $request, $type = '')
    {
        $requestData = $request->all();
        if (!empty($requestData)) {
            $data  = $requestData;
            if (array_key_exists('ROBOT_TXT', $data)) {
                $filePath = public_path() . '/robots.txt';
                $robotfile = fopen($filePath, "w");
                fwrite($robotfile, $data['ROBOT_TXT']);
                fclose($robotfile);
            }
            if (array_key_exists('MAINTENANCE_MODE', $data)) {
                $this->maintenanceMode($data);
            }
            if (array_key_exists('BUSINESS_PHONE_COUNTRY_CODE', $data)) {
                $data['BUSINESS_PHONE_COUNTRY_CODE'] = Country::getCountryIdByCountryCode($data['BUSINESS_PHONE_COUNTRY_CODE']);
            }
            if (array_key_exists('CURRENCY_API_STATUS', $data)) {
                if ($data['CURRENCY_API_STATUS'] == 0) {
                    Currency::disabledAllCurrencyExpectDefault();
                }
            }
            Configuration::bulkUpdate($data);
        }
        if ($request->input('type') != "") {
            $message = $this->getConfigurationMessage($request->input('type'));
            return jsonresponse(true, __($message));
        }
        return jsonresponse(true, __('LBL_SETTINGS_UPDATED'));
    }

    public function getTimeZoneArr()
    {
        $data['timezone'] = Timezone::getAllTimeZones();
        $data['dateFormat'] = Timezone::getAllDateFormat();
        $data['timeFormat'] = Timezone::getAllTimeFormat();
        return jsonresponse(true, null, $data);
    }

    public function getBusinessSettings()
    {
        $bussniessInfo = Configuration::getRecordsByPrefix('BUSINESS_');
        $countries = Country::getAll();
        $states = '';
        $phoneCode = 'us';
        if (!empty($bussniessInfo['BUSINESS_COUNTRY'])) {
            $states = State::getStatesByCountryId($bussniessInfo['BUSINESS_COUNTRY']);
        }
        if (!empty($bussniessInfo['BUSINESS_PHONE_COUNTRY_CODE'])) {
            $phoneCode = $countries->where('country_id', $bussniessInfo['BUSINESS_PHONE_COUNTRY_CODE'])->first()->country_code;
        }
        $bussniessInfo['BUSINESS_PHONE_COUNTRY_CODE'] = $phoneCode;
        $records['bussniessInfo'] = $bussniessInfo;
        $records['states'] = $states;
        $records['countries'] = $countries;
        $records['phoneCode'] = $phoneCode;
        return jsonresponse(true, null, $records);
    }

    private function getConfigurationMessage($type)
    {
        $message = '';
        switch ($type) {
            case "marketingtools":
                $message = __("LBL_MARKETING_TOOL_UPDATED");
                break;
        }
        return $message;
    }

    private function maintenanceMode($data)
    {
        if ($data['MAINTENANCE_MODE'] == "true") {
            Artisan::call("down");
        } else {
            Artisan::call("up");
        }
    }
    public function getTaxInfo()
    {
        $records = Configuration::getRecordsByPrefix('TAX_');

        return jsonresponse(true, null, $records);
    }
    public function updateTaxInfo()
    {
        $records = Configuration::getRecordsByPrefix('TAX_');

        return jsonresponse(true, null, $records);
    }
}
