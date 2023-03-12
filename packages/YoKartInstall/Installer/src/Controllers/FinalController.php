<?php

namespace YoKartInstall\Installer\Controllers;

use Illuminate\Routing\Controller;
use YoKartInstall\Installer\Helpers\EnvironmentManager;
use YoKartInstall\Installer\Helpers\FinalInstallManager;
use YoKartInstall\Installer\Helpers\InstalledFileManager;
use YoKartInstall\Installer\Events\InstallerFinished;
use App\Helpers\FileHelper;
use App\Models\Admin\Admin;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Configuration;
use Illuminate\Support\Facades\Hash;
use App\Models\AttachedFile;
use Session;

class FinalController extends Controller
{
    /**
     * Update installed file and display finished view.
     *
     * @param InstalledFileManager $fileManager
     * @return \Illuminate\View\View
     */
    public function finish(InstalledFileManager $fileManager, FinalInstallManager $finalInstall, EnvironmentManager $environment)
    {
        $finalMessages = $finalInstall->runFinal();
        $finalStatusMessage = $fileManager->update();
        $finalEnvFile = $environment->getEnvContent();
        event(new InstallerFinished);
        $adminData = json_decode(file_get_contents(storage_path('adminlog')), true);
        $this->insertAdmin($adminData);
        Configuration::bulkUpdate([
            'BUSINESS_NAME' => $adminData['business_name'],
            'BRAND_COLOR' => $adminData['brand_color'],
        ]);
        $this->updateDefaultLanguage($adminData['admin_default_lang']);
        $this->updateDefaultCurrency($adminData['admin_default_currency']);
        if (array_key_exists('admin_sitelogo', $adminData) && array_key_exists('admin_sitelogoOriginal', $adminData)) {
            AttachedFile::saveOrUpdateFile(AttachedFile::getConstantVal('adminLogo'), 0, $adminData['admin_sitelogo'], $adminData['admin_sitelogoOriginal']);
        }
        return view('installer::finished', compact('finalMessages', 'finalStatusMessage', 'finalEnvFile'));
    }

    public function insertAdmin($data)
    {
        $data['admin_name'] = $data['admin_username'];
        $data['admin_password'] = Hash::make($data['admin_password']);
        $data['admin_publish'] = config('constants.YES');
        Admin::create($data);
    }

    public function updateDefaultLanguage($languageId)
    {
        $language = Language::find($languageId);
        $language->lang_default = config('constants.YES');
        $language->save();
    }

    public function updateDefaultCurrency($currencyId)
    {
        $currency = Currency::find($currencyId);
        $currency->currency_default = config('constants.YES');
        $currency->save();
    }
}
