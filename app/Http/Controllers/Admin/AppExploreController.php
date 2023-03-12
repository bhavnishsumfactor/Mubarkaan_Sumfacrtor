<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\YokartController;
use Illuminate\Support\Arr;
use App\Models\AppExplore;
use App\Models\AppPage;

class AppExploreController extends YokartController
{ 
    public function getData(Request $request)
    {
        $records['page_data']['extra'] = [];
        $records['page_data']['quick_links'] = [];
        $defaultLangCode = getConfigValueByName('MOBILE_DEFAULT_LANGUAGE');
        $data = openJSONFile(base_path('public/mobile-lang.json'));

      
        $explorePages = AppExplore::select("ae_id", "ae_title", "ae_app_page_id", "ae_type", "ae_display_order")
                    ->join("app_pages", "app_pages.page_id", "app_explores.ae_app_page_id")
                    ->get();
        if (!empty($explorePages)) {
            foreach ($explorePages as $explorePage) {
                if ($explorePage['ae_type'] == AppExplore::EXPLORE_PAGE_TYPE_EXTRA) {
                    $records['page_data']['extra'][] = $explorePage;
                } else if ($explorePage['ae_type'] == AppExplore::EXPLORE_PAGE_TYPE_QUICK_LINKS) {
                    $records['page_data']['quick_links'][] = $explorePage;
                } 
            }
        }
        $records['language_labels'] =  Arr::only($data, $request->input('labels'));
        $records['app_pages'] =  AppPage::appPages();
        return jsonresponse(true, '', $records);
    }
    public function delete(Request $request)
    {
        AppExplore::where(['ae_id' => $request->ae_id])->delete();
        return jsonresponse(true, 'Deleted successfully');
    }
}