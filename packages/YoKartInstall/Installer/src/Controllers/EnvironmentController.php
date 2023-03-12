<?php

namespace YoKartInstall\Installer\Controllers;

use App\Http\Controllers\YokartController;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use YoKartInstall\Installer\Helpers\EnvironmentManager;
use YoKartInstall\Installer\Events\EnvironmentSaved;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\DB;
use Validator;
use Session;
use Illuminate\Validation\Rule;
use App\Models\Language;
use App\Models\Currency;

class EnvironmentController extends YokartController
{
    /**
     * @var EnvironmentManager
     */
    protected $EnvironmentManager;

    /**
     * @param EnvironmentManager $environmentManager
     */
    public function __construct(EnvironmentManager $environmentManager)
    {
        $this->EnvironmentManager = $environmentManager;
    }

    /**
     * Display the Environment menu page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentMenu()
    {
        return view('installer::environment');
    }

    /**
     * Display the Environment page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentWizard()
    {
        $envConfig = $this->EnvironmentManager->getEnvContent();
        $languages = Language::LANGUAGES;
        $currencies = Currency::CURRENCIES;
        return view('installer::environment-wizard', compact('envConfig', 'languages', 'currencies'));
    }

    /**
     * Display the Environment page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentClassic()
    {
        $envConfig = $this->EnvironmentManager->getEnvContent();

        return view('installer::environment-classic', compact('envConfig'));
    }

    /**
     * Processes the newly saved environment configuration (Classic).
     *
     * @param Request $input
     * @param Redirector $redirect
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveClassic(Request $input, Redirector $redirect)
    {
        $message = $this->EnvironmentManager->saveFileClassic($input);

        event(new EnvironmentSaved($input));

        return $redirect->route('Installer::environmentClassic')
                        ->with(['message' => $message]);
    }

    /**
     * Processes the newly saved environment configuration (Form Wizard).
     *
     * @param Request $request
     * @param Redirector $redirect
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveWizard(Request $request, Redirector $redirect)
    {
        $rules = config('installer.environment.form.rules');
        $messages = [
            'environment_custom.required_if' => trans('An environment name is required'),
        ];
        
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withInput($request->input())->withErrors($validator->errors());
        }
        if (! $this->checkDatabaseConnection($request)) {
            return $redirect->route('Installer::environmentWizard')->withInput()->withErrors([
                'database_connection' => trans('Database Connection Failed'),
            ]);
        }
        
        $results = $this->EnvironmentManager->saveFileWizard($request);
        
        event(new EnvironmentSaved($request));
        $adminData['business_name'] = $request->input('business_name');
        $adminData['brand_color'] = $request->input('brand_color');
        $adminData['admin_username'] = $request->input('admin_username');
        $adminData['admin_password'] = $request->input('admin_password');
        $adminData['admin_email'] = $request->input('admin_email');
        $adminData['admin_default_lang'] = $request->input('admin_default_lang');
        $adminData['admin_default_currency'] = $request->input('admin_default_currency');
        if ($request->hasFile('admin_site_logo')) {
            $siteLogoPath = FileHelper::uploadFile($request->file('admin_site_logo'));
            $siteLogoOriginalName= $request->file('admin_site_logo')->getClientOriginalName();
            $adminData['admin_sitelogo'] = $siteLogoPath;
            $adminData['admin_sitelogoOriginal'] = $siteLogoOriginalName;
        }
        $adminLogFile = storage_path('adminlog');
        $message = json_encode($adminData);
        file_put_contents($adminLogFile, $message);
        
        if ($request->input('dummy_data') == 'true') {
            $dummyData = 1;
        } else {
            $dummyData = 0;
        }
        return $redirect->route('Installer::database', ['dummyDataStatus'=> $dummyData]);
    }

    private function checkDatabaseConnection(Request $request)
    {
        $connection = 'mysql';
        $settings = config("database.connections.mysql");
        config([
            'database' => [
                'default' => $connection,
                'connections' => [
                    $connection => array_merge($settings, [
                        'driver' => $connection,
                        'host' => $request->input('database_hostname'),
                        'port' => $request->input('database_port'),
                        'database' => $request->input('database_name'),
                        'username' => $request->input('database_username'),
                        'password' => $request->input('database_password'),
                    ]),
                ],
            ],
        ]);
        try {
            DB::connection()->getPdo();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
