<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\Configuration;
use App\Models\Admin\AdminRole;
use Redirect;
use Storage;

class AnalyticsController extends AdminYokartController
{
    private $redirectURL   = "/admin/googleanalytics/callbackUrl";
    private $analyticScope = "https://www.googleapis.com/auth/analytics";

    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
    }

    public function getListing(Request $request)
    {
        $data = [];
        $keys = array(
            'GOOGLE_ANALYTICS_ANALYTICS_ID',
            'GOOGLE_ANALYTICS_CLIENT_ID',
            'GOOGLE_ANALYTICS_SECRET_KEY',
            'GOOGLE_ANALYTICS_SITE_TRACKING_CODE',
            'GOOGLE_ANALYTICS_VIEW_ID',
            'GOOGLE_ANALYTICS_VERIFIED'
        );
        $data['analytics'] = Configuration::getKeyValues($keys);

        $keys = array(
            'GOOGLE_TAG_MANAGER_BODY_SCRIPT',
            'GOOGLE_TAG_MANAGER_HEAD_SCRIPT'
        );
        $data['analytics']['credentials_name'] = '';
        $data['analytics']['credentials_url'] = '';
        $data['analytics']['credentials_size'] = '';
        if (Storage::exists('public/analytics/service_credentials.json')) {
            $data['analytics']['credentials_url'] = url('public/storage/analytics/service_credentials.json');
            $data['analytics']['credentials_name'] = 'service_credentials.json';
            $data['analytics']['credentials_size'] = Storage::size('public/analytics/service_credentials.json');
        }
        $data['tagManager'] = Configuration::getKeyValues($keys);
        $data['FACEBOOK_PIXEL_ID'] = Configuration::getValue('FACEBOOK_PIXEL_ID');

        return jsonresponse(true, null, $data);
    }

    public function store(Request $request, $type)
    {
        $data = $request->all();
        switch ($type) {
            case 'analytics':
                Configuration::bulkUpdate($data);
                break;
            case 'tagManager':
                Configuration::bulkUpdate($data);
            break;
            case 'pixel':
                Configuration::saveValue('FACEBOOK_PIXEL_ID', $data['FACEBOOK_PIXEL_ID']);
            break;
            default:
                // code...
            break;
        }

        return jsonresponse(true, __('NOTI_SETTINGS_UPDATED'));
    }

    public function authenticate()
    {
        $googleAnalytics = Configuration::getRecordsByPrefix('GOOGLE_ANALYTICS');
        $client_id  = $googleAnalytics['GOOGLE_ANALYTICS_CLIENT_ID'];
        $secret_id  = $googleAnalytics['GOOGLE_ANALYTICS_SECRET_KEY'];

        if (empty($client_id) || empty($secret_id)) {
            return jsonresponse(false, __("NOTI_CLIENT_SECRET_KEY_REQUIRED"), '');
        }
        $client = new \Google_Client();
        $client->setClientId($client_id);
        $client->setClientSecret($secret_id);
        $client->setRedirectUri(url($this->redirectURL));
        $client->addScope($this->analyticScope);
        $data['url'] = $client->createAuthUrl();
        return jsonresponse(true, "", $data);
    }

    public function handleRedirectUrl(Request $request)
    {
        if ($request->input('code') == null) {
            return redirect('/admin/googleanalytics/authenticate');
        } else {
            $googleAnalytics = Configuration::getRecordsByPrefix('GOOGLE_ANALYTICS');
            $client_id  = $googleAnalytics['GOOGLE_ANALYTICS_CLIENT_ID'];
            $secret_id  = $googleAnalytics['GOOGLE_ANALYTICS_SECRET_KEY'];
            $client = new \Google_Client();
            $client->setClientId($client_id);
            $client->setClientSecret($secret_id);
            $client->setRedirectUri(url($this->redirectURL));
            $client->addScope($this->analyticScope);
            $client->setAccessType("offline");
            $client->authenticate($request->input('code'));
            if ($client->getAccessToken() != null  && array_key_exists('access_token', $client->getAccessToken())) {
                $data = $client->getAccessToken();
                $updateGoogleAnalyticsData['GOOGLE_ANALYTICS_ACCESS_TOKEN'] = $data['access_token'];
                $updateGoogleAnalyticsData['GOOGLE_ANALYTICS_VERIFIED'] = 1;
                Configuration::bulkUpdate($updateGoogleAnalyticsData);
                return redirect('admin#/thirdparty/analytics?message=success');
            }
            return redirect('admin#/thirdparty/analytics?message=failure');
        }
    }

    public function uploadServiceCredentails(Request $request)
    {
        try {
            $file = $request->file('file');
            $file->move(storage_path('app/public/analytics'), 'service_credentials.json');
            return jsonresponse(true, __('NOTI_JSON_FILE_UPLOADED'));
        } catch (Exception $e) {
            return jsonresponse(false, __($e->errorMessage()));
        }
    }
    
    public function removeCredentailFile(Request $request)
    {
        $file = 'public/analytics/service_credentials.json';
        if (Storage::exists($file)) {
            Storage::delete($file);
            return jsonresponse(true, __('NOTI_JSON_FILE_REMOVED'));
        } else {
            return jsonresponse(true, __('NOTI_FILE_NOT_EXISTS'));
        }
    }

    public function viewAnalyticsCredentailFile(Request $request)
    {
        $file = 'public/analytics/service_credentials.json';
        if (Storage::exists($file)) {
            return jsonresponse(true, __(''), url('public/storage/analytics/service_credentials.json'));
        } else {
            return jsonresponse(false, __('NOTI_FILE_NOT_EXISTS'));
        }
    }
}
