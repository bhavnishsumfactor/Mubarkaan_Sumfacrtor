<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Country;
use App\Models\State;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Configuration;
use App\Models\Admin\Admin;
use Database\Seeders\AdminTableSeeder;
use Database\Seeders\LanguageTableSeeder;
use App\Helpers\ThemeHelper;
use Database\Seeders\CurrencyTableSeeder;
use Database\Seeders\BlankConfigurationTableSeeder;
use Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class installerController extends BaseController
{
    private $envPath = '';

    public function __construct()
    {
        $this->envPath = base_path('.env');
    }

    public function index()
    {
        $installedLogFile = storage_path('installed');
        if (!file_exists($installedLogFile)) {
            return view('layouts.admin.installer');
        }
        return redirect()->to('/login');
    }

    public function getCountries(Request $request)
    {
        return jsonresponse(false, '', Country::getAllCountriesFromCsv());
    }

    public function getCurrencyWithLanguage(Request $request)
    {
        $data['currency'] = Currency::getDefaultCurrency();
        $data['language'] = Language::getAllSystemLanguage();
        return jsonresponse(true, '', $data);
    }

    public function checkRequirements(Request $request)
    {
        $requirements['php'] = 7.3;
        $requirements['extension'] = [
            'mysqli' => 'mysqli',
            'curl' => 'curl',
            'ctype' => 'ctype',
            'fileinfo' => 'fileinfo',
            'json' => 'json',
            'mbstring' => 'mbstring',
            'openssl' => 'openssl',
            'tokenizer' => 'tokenizer',
            'xml' => 'xml'
        ];
        $requirements['core'] = [
            'memory_limit' => 'memory_limit',
            'allow_url_fopen' => 'allow_url_fopen',
            'file_uploads' => 'file_uploads'
        ];
        $requirements['apache'] = [
            'mod_rewrite' => 'mod_rewrite'
        ];
        $data = [];
        foreach ($requirements as $type => $requirement) {
            switch ($type) {
            case 'php':
                if ($requirement <= phpversion()) {
                    $data[$type] = true;
                } else {
                    $data[$type] = false;
                }
                break;
            // check apache requirements
            case 'extension':
                foreach ($requirements[$type] as $key=>$requirement) {
                    if (!extension_loaded($requirement)) {
                        $data[$key] = false;
                    } else {
                        $data[$key] = true;
                    }
                }
                break;
            case 'apache':
                foreach ($requirements[$type] as $requirement) {
                    // if function doesn't exist we can't check apache modules
                    if (function_exists('apache_get_modules')) {
                        $results['requirements'][$type][$requirement] = true;
                        if (!in_array($requirement, apache_get_modules())) {
                            $data[$requirement] = false;
                        } else {
                            $data[$requirement] = true;
                        }
                    }
                }
                break;
            case 'core':
                $data['allow_url_fopen'] = (ini_get('allow_url_fopen')) ? true : false;
                $data['file_uploads'] = (ini_get('file_uploads')) ? true : false;
                $data['memory_limit'] = ($this->getInBytes(ini_get('memory_limit')) >= $this->getInBytes('64M') ? true : false);
                break;
            }
        }
        return jsonresponse(true, '', $data);
    }

    public function checkStoragePermission(Request $request)
    {
        $folderPermission['storage_framework'] = 'storage/framework';
        $folderPermission['storage_logs'] = 'storage/logs';
        $folderPermission['bootstrap_cache'] = 'bootstrap/cache';
        $folderPermission['test'] = 'storage/test';

        $data = [];
        foreach ($folderPermission as $key => $permission) {
            if (is_writable($permission)) {
                $data[$key] = true;
            } else {
                $data[$key] = false;
            }
        }
        return jsonresponse(true, '', $data);
    }

    private function getInBytes($value)
    {
        $value = trim($value);
        $last_char = strtolower($value[strlen($value) - 1]);
        $value = intval($value);
        switch ($last_char) {
            case 'g': $value *= 1024;
            // no break
            case 'm': $value *= 1024;
            // no break
            case 'k': $value *= 1024;
        }
        return $value;
    }

    /*public function check(array $requirements)
    {
        $results = [];

        foreach($requirements as $type => $requirement)
        {
            switch ($type) {
                // check php requirements
                case 'php':
                    foreach($requirements[$type] as $requirement)
                    {
                        $results['requirements'][$type][$requirement] = true;

                        if(!extension_loaded($requirement))
                        {
                            $results['requirements'][$type][$requirement] = false;

                            $results['errors'] = true;
                        }
                    }
                    break;
                // check apache requirements
                case 'apache':
                    foreach ($requirements[$type] as $requirement) {
                        // if function doesn't exist we can't check apache modules
                        if(function_exists('apache_get_modules'))
                        {
                            $results['requirements'][$type][$requirement] = true;

                            if(!in_array($requirement,apache_get_modules()))
                            {
                                $results['requirements'][$type][$requirement] = false;

                                $results['errors'] = true;
                            }
                        }
                    }
                    break;
            }
        }

        return $results;
    }*/

    
    private function saveFileWizard($request)
    {
        $file = "file";
        $password = "'" . $request['db_password'] . "'";
        $envFileData = 'APP_NAME=' . $request['app_name'] . "\n" .
        'APP_KEY=' . 'base64:bODi8VtmENqnjklBmNJzQcTTSC8jNjBysfnjQN59btE=' . "\n" .
        'APP_ENV=' . 'local' . "\n" .
        'APP_DEBUG=false'. "\n" .
        'APP_LOG_LEVEL=false'. "\n" .
        'ALLOW_EMAILS=true'. "\n" .
        'APP_URL=' . $request['app_url'] . "\n\n" .
        'API_VERSION=1.0'. "\n\n" .
        'DB_CONNECTION=mysql'. "\n" .
        'DB_HOST=' . $request['db_host'] . "\n" .
        'DB_PORT=' . $request['db_port'] . "\n" .
        'DB_DATABASE=' . $request['db_name'] . "\n" .
        'DB_USERNAME=' . $request['db_user'] . "\n" .
        'DB_PASSWORD=' . $password . "\n\n" .
        'BROADCAST_DRIVER=' . '' . "\n" .
        'CACHE_DRIVER=' . $file . "\n" .
        'SESSION_DRIVER=' . $file . "\n" .
        'SESSION_LIFETIME=120'. "\n" .
        'QUEUE_CONNECTION=database';

        try {
            file_put_contents($this->envPath, $envFileData);
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateConfig(Request $request)
    {
        if ($this->saveFileWizard($request->all())) {
            Artisan::call('key:generate');
            Artisan::call("cache:clear");
            Artisan::call("config:clear");
            Artisan::call("route:clear");
            Artisan::call("view:clear");
            Artisan::call("storage:link");
        }
    }
    public function migrationAndSeeding(Request $request)
    {
        ini_set('max_execution_time', '-1');
        try {
            ThemeHelper::migrateAndSeed($request);
            imageUpload($request, 0, 'adminLogo');
            $this->createInstalledFile();
            return jsonresponse(true, 'successfully installed');
        } catch (\Exception $e) {
            return jsonresponse(false, $e->getMessage(), []);
        }
    }

   /* public function migrationAndSeeding(Request $request)
    {
        ini_set('max_execution_time', '-1');
        $outputLog = '';
        try {
            Artisan::call('migrate', ["--force" => true], $outputLog);
            if ($request->input('install_dummy')) {
                Artisan::call('db:seed', ['--force' => true], $outputLog);
                File::deleteDirectory(storage_path('app/public'));
                File::copyDirectory(base_path('git-ignored-files/storage/app/public'), storage_path('app/public'));
            } else {
                Artisan::call('defaultMigration');
                $seeder = new BlankConfigurationTableSeeder;
                $seeder->callWith(BlankConfigurationTableSeeder::class, [$request->input('config'), $request->input('install_dummy')]);
            }
            

            $seeder = new AdminTableSeeder;
            $seeder->callWith(AdminTableSeeder::class, [$request->only(['admin_name', 'admin_email', 'admin_password'])]);
            $seeder = new LanguageTableSeeder;
            $seeder->callWith(LanguageTableSeeder::class, [$request->input('admin_language')]);
            $seeder = new CurrencyTableSeeder;
            $seeder->callWith(CurrencyTableSeeder::class, [$request->input('admin_currency')]);
            imageUpload($request, 0, 'adminLogo');
            $this->createInstalledFile();
            return jsonresponse(true, 'successfully installed');
        } catch (\Exception $e) {
            return jsonresponse(false, $e->getMessage(), []);
        }
    }*/

    public function getLanguages(Request $request)
    {
        $language = Language::getAllSystemLanguage();
        return jsonresponse(true, '', $language);
    }

    public function installerLanguage()
    {
        $strings = Cache::rememberForever('installer-lang.js', function () {
            $lang = config('app.locale');
            $strings = [];
            $defaultLanguageVariables = openJSONFile(base_path('resources/lang/' . $lang . '.json'));
            $strings[$lang] = $defaultLanguageVariables;
            return $strings;
        });
        header('Content-Type: text/javascript');
        echo('window.i18n = ' . json_encode($strings) . ';');
        exit();
    }

    public function getStates(Request $request)
    {
        return jsonresponse(false, '', State::getCountryStateFromCsv($request->input('country')));
    }

    public function switchLang($lang)
    {
        Session::put('applocale', $lang);
        Artisan::call("cache:clear");
        Artisan::call("config:clear");
        Artisan::call("route:clear");
        Artisan::call("view:clear");
        return Redirect::back();
    }

    private function createInstalledFile()
    {
        $installedLogFile = storage_path('installed');

        $dateStamp = date("Y/m/d h:i:sa");

        if (!file_exists($installedLogFile)) {
            $message = trans('Laravel Installer successfully INSTALLED on') . ' ' . $dateStamp . "\n";

            file_put_contents($installedLogFile, $message);
        } else {
            $message = trans('Laravel Installer successfully UPDATED on') . ' ' . $dateStamp;

            file_put_contents($installedLogFile, $message.PHP_EOL, FILE_APPEND | LOCK_EX);
        }
    }
}
