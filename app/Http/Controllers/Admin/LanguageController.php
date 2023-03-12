<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\Language;
use App\Models\Configuration;
use App\Helpers\TranslateHelper;
use App\Models\Admin\AdminRole;
use App\Http\Requests\LanguageRequest;
use Maatwebsite\Excel\HeadingRowImport;
use File;
use App\Exports\LanguagesExport;
use App\Imports\LanguagesImport;
use Excel;
use Artisan;
use Illuminate\Support\Arr;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class LanguageController extends AdminYokartController
{
    private $exceptFunction = ['getListing', 'updateStatus', 'saveLanguage', 'markAsDefault', 'deleteLanguage'];

    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::LANGUAGE_LABELS)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        })->except($this->exceptFunction);
    }

    public function languageslabels(Request $request)
    {
        $type = !empty($request['type']) ? $request['type'] : '';
        $filter = !empty($request['filter']) ? $request['filter'] : '';
        $search = !empty($request['search']) ? $request['search'] : '';

        if (empty($type) || !in_array($type, ['admin', 'frontend'])) {
            abort(404);
        }
        $page = !empty($_GET['page']) ? $_GET['page'] : 1;
        $perPage = !empty($request['per_page']) ? $request['per_page'] : config('app.pagination');
        $default = Language::getDefault();
        $defaultLangCode = $default->lang_code;
        $defaultLangName = $default->lang_name;
        $variables = openJSONFile(base_path('resources/lang/' . $defaultLangCode . '.json'));
        $labelPages = include_once(base_path('resources/lang/labelPages.php'));

        $camelCaseType = ucwords($type);

        $pagesTemp = Language::PAGES;
        $pages = [];
        $i = 0;
        foreach ($pagesTemp as $key => $page) {
            if (!(strpos(strtolower($pagesTemp[$key]), $type . '||') === false)) {
                $pages[$key] = str_replace($camelCaseType . '||', "", $page);
                if ($i == 0 && empty($filter)) {
                    $filter = $pages[$key];
                }
                $i++;
            }
        }

        $variables = array_filter($variables, function ($data, $key) use ($filter, $variables, $labelPages, $search, $camelCaseType) {
            $typeCondition = !(strpos($labelPages[$key], $camelCaseType . '||') === false);
            $filterCondition = !empty($filter) && !empty($labelPages[$key]) && !(strpos(strtolower($labelPages[$key]), strtolower($filter)) === false);
            
            if ($typeCondition && $filterCondition) {
                if (!empty($search)) {
                    if (!(strpos(str_replace('_', ' ', strtolower($key)), str_replace('_', ' ', strtolower($search))) === false) || !(strpos(str_replace('_', ' ', strtolower($data)), str_replace('_', ' ', strtolower($search))) === false)) {
                        return true;
                    }
                } else {
                    return true;
                }
            }
        }, ARRAY_FILTER_USE_BOTH);
                
        $finalVariables = [];
        $i = 0;
        foreach ($variables as $key => $variable) {
            $finalVariables[$i] =  (object) [];
            $finalVariables[$i]->key = $key;
            $pagesTemp = '';
            if (!empty($labelPages[$key])) {
                $pagesTemp = str_replace("Admin||", "", $labelPages[$key]);
                $pagesTemp = str_replace("Frontend||", "", $pagesTemp);
            }
            $finalVariables[$i]->pages = explode(", ", $pagesTemp);
            $finalVariables[$i]->$defaultLangCode = $variable;
            $i++;
        }

        $data = $this->paginate($finalVariables, $perPage);
        
        return jsonresponse(true, '', ['data' => $data->toArray(), 'default_lang_code' => $defaultLangCode, 'default_lang_name' => $defaultLangName, 'pages' => $pages, 'selected_page' => $filter]);
    }

    public function getLabelDataByKey(Request $request)
    {
        $key = $request['key'];

        $fields = ['lang_id', 'lang_name', 'lang_code'];
        $conditions = ['lang_publish' => config('constants.YES')];
        $languages = Language::getLanguages($fields, $conditions);
        
        $data = [];
        $data['key'] = $key;
        foreach ($languages as $language) {
            $langCode = $language->lang_code;
            $variables = openJSONFile(base_path('resources/lang/' . $langCode . '.json'));
            if (!empty($variables[$key])) {
                $data['lang'][$langCode] =  (object) [];
                $data['lang'][$langCode]->lang_name = $language->lang_name;
                $data['lang'][$langCode]->value = $variables[$key];
            }
        }
        return jsonresponse(true, '', $data);
    }

    public function saveLabelDataByKey(Request $request)
    {
        if (!canWrite(AdminRole::LANGUAGE_LABELS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $key = $request['key'];
        $languages = json_decode($request['lang']);
        foreach ($languages as $langCode => $language) {
            $variables = openJSONFile(base_path('resources/lang/' . $langCode . '.json'));
            if (!empty($variables[$key])) {
                $variables[$key] = $language->value;
                saveJSONFile(base_path('resources/lang/' . $langCode . '.json'), $variables);
            }
        }
        return jsonresponse(true, __('NOTI_LANGUAGE_LABELS_UPDATED'));
    }

    private function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    
    public function getListing(Request $request)
    {
        if (!canRead(AdminRole::LOCALIZATION)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $records = Language::getRecords($request->all());
        $status = (isset($records) && $records->count() > 0) ? true : false;
        $languages = openJSONFile(base_path('resources/languages.json'));
        $existingLanguages = Language::pluck('lang_code')->toArray();
        $languages = Arr::except($languages, $existingLanguages);
        return jsonresponse($status, null, ['records' => $records, 'languages' => $languages]);
    }

    public function saveLanguage(Request $request)
    {
        if (!canWrite(AdminRole::LOCALIZATION)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        Language::create([
            'lang_name' => $request['lang_name'],
            'lang_code' => $request['lang_code'],
            'lang_direction' => ($request['lang_direction']) ?? 'ltr'
        ]);
        $this->createTranslateFile($request['lang_code']);
        return jsonresponse(true, __('NOTI_LANGUAGE_ADDED'));
    }

    public function updateStatus(Request $request, $langId)
    {
        if (!canWrite(AdminRole::LOCALIZATION)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $publishStatus = ($request->input('status') == 'true') ? 1 : 0;
        Language::where('lang_id', $langId)->update(['lang_publish' => $publishStatus]);
        $message = ($request->input('status') == 'true') ? __('NOTI_LANGUAGE_PUBLISHED') : __('NOTI_LANGUAGE_UNPUBLISHED');
        return jsonresponse(true, $message);
    }

    public function markAsDefault(Request $request, $langId)
    {
        if (!canWrite(AdminRole::LOCALIZATION)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        Language::where('lang_view_default', 1)->update(['lang_view_default' => 0]);
        Language::where('lang_id', $langId)->update(['lang_view_default' => 1]);
        return jsonresponse(true, __('NOTI_LANGUAGE_MARKED_DEFAULT'));
    }

    public function deleteLanguage(Request $request, $langId)
    {
        if (!canWrite(AdminRole::LOCALIZATION)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $language = Language::where('lang_id', $langId);
        $langCode = $language->pluck('lang_code')->first();
        File::delete(base_path('resources/lang/' . $langCode . '.json'));
        $language->delete();
        return jsonresponse(true, __('NOTI_LANGUAGE_DELETED'));
    }

    public function export()
    {
        $data = [];
        $languages = Language::getAll();
        $otherLanguages = $languages;

        $default = Language::getDefault();
        $defaultLanguageVariables = openJSONFile(base_path('resources/lang/' . $default->lang_code . '.json'));
        $data[] = array_merge(['key' => 'key'], $languages);
        
        if (!empty($defaultLanguageVariables)) {
            foreach ($defaultLanguageVariables as $key => $value) {
                $tempData = [];
                $tempData['key'] = $key;
                if (!empty($otherLanguages)) {
                    foreach ($otherLanguages as $langCode => $langName) {
                        $otherLanguageVariables = openJSONFile(base_path('resources/lang/' . $langCode . '.json'));
                        $tempData[$langName] = ($otherLanguageVariables[$key] ?? '');
                    }
                }
                $data[] = $tempData;
            }
        }
        $export = new LanguagesExport($data);
        return Excel::download($export, 'languages.xls');
    }

    public function import(LanguageRequest $request)
    {
        $sheetHeadings = (new HeadingRowImport)->toArray($request->file('file'));
        $languageHeading = ['key'];
        if (!in_array($languageHeading[0], $sheetHeadings[0][0])) {
            return jsonresponse(false, __('Please import the correct file'));
        }
        $data = Excel::toArray(new LanguagesImport(), $request->file('file'));
        $languages = Language::getAll();
        $languagesData = [];

        if (!empty($languages)) {
            $i = 1;
            foreach ($languages as $langCode => $langName) {
                $languagesData[$i] = openJSONFile(base_path('resources/lang/' . $langCode . '.json'));
                $i++;
            }
        }

        if (!empty($data[0])) {
            foreach ($data[0] as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                $langVariable = $value[0];
                foreach ($value as $key1 => $value1) {
                    if ($key1 == 0) {
                        continue;
                    }
                    if (!empty($languagesData[$key1][$langVariable])) {
                        $languagesData[$key1][$langVariable] = $value1;
                    }
                }
            }
        }
        if (!empty($languages)) {
            $i = 1;
            foreach ($languages as $langCode => $langName) {
                if (is_array($languagesData[$i])) {
                    saveJSONFile(base_path('resources/lang/' . $langCode . '.json'), $languagesData[$i]);
                }
                $i++;
            }
        }
        return jsonresponse(true, __('NOTI_LANGUAGE_FILE_IMPORTED'));
    }

    private function createTranslateFile($toLang = 'ar')
    {
        $default = Language::getDefault();
        $fromLang = $default->lang_code;
        $finalTranslatedData = [];

        $translateObj = new TranslateHelper(Configuration::getValue('MSN_TRANSLATION_KEY'));
        $defaultLanguageVariables = openJSONFile(base_path('resources/lang/' . $fromLang . '.json'));
        if (!empty($defaultLanguageVariables)) {
            $chunks = array_chunk($defaultLanguageVariables, 100, true);
            foreach ($chunks as $key => $chunk) {
                $tempTranslatedData = $temp = [];
                foreach ($chunk as $key => $lang) {
                    $temp[] = ['Text' => $lang];
                    $tempTranslatedData[] = ['key' => $key];
                }
                $toLangTemp = Language::checkException($toLang);
                $response = $translateObj->translateData($fromLang, $toLangTemp, $temp);
                foreach ($response as $key => $lang) {
                    $tempTranslatedData[$key]['value'] = str_replace(array( '\'', '"', ',' , ';', '<', '>' ), '', $lang["translations"][0]["text"]);
                }
                $finalTranslatedData = array_merge($finalTranslatedData, $tempTranslatedData);
            }
        }
        $finalTranslatedData = array_reduce($finalTranslatedData, function ($result, $item) {
            $result[$item['key']] = $item['value'];
            return $result;
        }, []);
        
        saveJSONFile(base_path('resources/lang/' . $toLang . '.json'), $finalTranslatedData);
    }

    /* Mobile */
    public function languageslabelsMobile(Request $request)
    {
        $type = !empty($request['type']) ? $request['type'] : '';
        $filter = !empty($request['filter']) ? $request['filter'] : '';
        $search = !empty($request['search']) ? $request['search'] : '';

        $page = !empty($_GET['page']) ? $_GET['page'] : 1;
        $perPage = !empty($request['per_page']) ? $request['per_page'] : config('app.pagination');
        $defaultLangCode = 'en';
        $variables = openJSONFile(base_path('public/mobile-lang.json'));
        $data = [];
        $camelCaseType = ucwords($type);
        $pagesTemp = Language::PAGES;
        $pages = [];
        if (!empty($variables)) {
            
            $i = 0;
            foreach ($pagesTemp as $key => $page) {
                if (!(strpos(strtolower($pagesTemp[$key]), $type . '||') === false)) {
                    $pages[$key] = str_replace($camelCaseType . '||', "", $page);
                    if ($i == 0 && empty($filter)) {
                        $filter = $pages[$key];
                    }
                    $i++;
                }
            }

            $variables = array_filter($variables, function ($data, $key) use ($search) {
                if (!empty($search)) {
                    if (!(strpos(str_replace('_', ' ', strtolower($key)), str_replace('_', ' ', strtolower($search))) === false) || !(strpos(str_replace('_', ' ', strtolower($data)), str_replace('_', ' ', strtolower($search))) === false)) {
                        return true;
                    }
                } else {
                    return true;
                }
            
            }, ARRAY_FILTER_USE_BOTH);

            $finalVariables = [];
            $i = 0;
            foreach ($variables as $key => $variable) {
                $finalVariables[$i] =  (object) [];
                $finalVariables[$i]->key = $key;            
                $finalVariables[$i]->$defaultLangCode = $variable;
                $i++;
            }

            $data = $this->paginate($finalVariables, $perPage)->toArray();
        }
        return jsonresponse(true, '', ['data' => $data, 'default_lang_code' => $defaultLangCode, 'pages' => $pages, 'selected_page' => $filter]);
    }
    public function getAppLanguageLabelData(Request $request)
    {
        $records = [];
        $data = openJSONFile(base_path('public/mobile-lang.json'));
        $records =  Arr::only($data, $request->input('labels'));
        return jsonresponse(true, '', $records);
    }

    public function getLabelMobileDataByKey(Request $request)
    {
        $key = $request['key'];

        $fields = ['lang_id', 'lang_name', 'lang_code'];
        $conditions = ['lang_publish' => config('constants.YES')];
        $languages = Language::getLanguages($fields, $conditions);
        
        $data = [];
        $data['key'] = $key;
        foreach ($languages as $language) {
            $langCode = $language->lang_code;
            $variables = openJSONFile(base_path('public/mobile-lang.json'));
            if (!empty($variables[$key])) {
                $data['lang'][$langCode] =  (object) [];
                $data['lang'][$langCode]->lang_name = $language->lang_name;
                $data['lang'][$langCode]->value = $variables[$key];
            }
        }
        return jsonresponse(true, '', $data);
    }

    public function saveLabelMobileDataByKey(Request $request)
    {
      
        if (!canWrite(AdminRole::LANGUAGE_LABELS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $defaultLangCode = 'en';
        $key = $request['key'];
        $variables = openJSONFile(base_path('public/mobile-lang.json'));
        if (!empty($variables[$key]) && !empty($request[$defaultLangCode])) {
            $variables[$key] = $request[$defaultLangCode];
            saveJSONFileMobile(base_path('public/mobile-lang.json'), $variables);
        }
        return jsonresponse(true, __('NOTI_LANGUAGE_LABELS_UPDATED'));
    }
   
}
