<?php

namespace App\Http\Controllers;

use App\Http\Controllers\YokartController;
use App\Http\Requests;
use Config;
use Artisan;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class LanguageController extends YokartController
{
    public function switchLang($lang)
    {
        Session::put('applocale', $lang);
        Artisan::call("cache:clear");
        Artisan::call("config:clear");
        Artisan::call("route:clear");
        Artisan::call("view:clear");
        $data = file_get_contents(base_path('resources/lang/labels.php'));
        $fp = fopen(base_path('resources/lang/en.json'), 'w');
        fwrite($fp, $data);
        return Redirect::back();
    }
    public function adminLangData()
    {
        $lang = Session::get('applocale');
        $strings = Cache::rememberForever('lang.js', function () use ($lang) {
            if (empty($lang)) {
                $lang = config('app.locale');
            }
            $strings = [];
            $strings[$lang] = openJSONFile(base_path('resources/lang/' . $lang . '.json'));
            return $strings;
        });
        
        header('Content-Type: text/javascript');
        echo('window.i18n = ' . json_encode($strings) . ';');
        exit();
    }

    public function switchDashboardLocale(Request $request)
    {
        Session::put('appdashboardLocale', $request->input('language'));
        $this->setLanguage($request);
        \Cache::forget('user-lang-' . $this->user->user_id . '.js');
        return jsonresponse(true, '');
    }

    public function dashboardLangData($userId)
    {
        $lang = Session::get('appdashboardLocale');
        $strings = Cache::rememberForever('user-lang-' . $userId . '.js', function () use ($lang) {
            if (empty($lang)) {
                $lang = config('app.dashboardLocale');
            }
            $strings = [];
            $defaultLanguageVariables = openJSONFile(base_path('resources/lang/' . $lang . '.json'));
            $strings[$lang] = $defaultLanguageVariables;
            return $strings;
        });
        header('Content-Type: text/javascript');
        echo('window.i19n = ' . json_encode($strings) . ';');
        exit();
    }
}
