<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmailTemplate;
use App\Models\Admin\AdminRole;
use App\Http\Controllers\Admin\AdminYokartController;
use Illuminate\Http\Request;
use App\Http\Requests\EmailTemplateRequest;
use App\Models\Configuration;
use App\Models\AttachedFile;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class EmailTemplateController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
    }

    public function getListing(Request $request)
    {
        if (!canRead(AdminRole::EMAIL_TEMPLATES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $emailTemplates = EmailTemplate::getAllEmailTemplates($request->all());
        $status = ($emailTemplates->count() > 0) ? true : false;
        return jsonresponse($status, null, $emailTemplates);
    }

    public function getRecord($id)
    {
        if (!canRead(AdminRole::EMAIL_TEMPLATES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $data = EmailTemplate::where('etpl_id', $id)->with('updatedAt')->first();
        $data->etpl_body = str_replace('{baseurl}', url('/'), $data->etpl_body);
        $data->etpl_body = '<table width="600px" cellspacing="0" cellpadding="0" style="margin:0 auto;table-layout:fixed;background:#ffffff;border-radius: 4px;box-shadow: 0 0 10px rgba(0,0,0,0.04);"><tbody id="emailWrapper">' . $data->etpl_body . '</tbody></table>';

        $data->etpl_default_body = str_replace('{baseurl}', url('/'), $data->etpl_default_body);
        $data->etpl_default_body = '<table width="600px" cellspacing="0" cellpadding="0" style="margin:0 auto;table-layout:fixed;background:#ffffff;border-radius: 4px;box-shadow: 0 0 10px rgba(0,0,0,0.04);"><tbody id="emailWrapper">' . $data->etpl_default_body . '</tbody></table>';

        $conf = Configuration::getKeyValues([
            'BUSINESS_NAME',
            'BUSINESS_EMAIL',
            'BUSINESS_PHONE_COUNTRY_CODE',
            'EMAIL_FOOTER_HTML',
            'BUSINESS_PHONE_NUMBER',
            'EMAIL_FOOTER2_HTML'
        ]);
        $footer = str_replace('{websiteName}', $conf['BUSINESS_NAME'], $conf['EMAIL_FOOTER_HTML']);
        $footer = str_replace('{businessEmail}', $conf['BUSINESS_EMAIL'], $footer);
        $footer = str_replace('{businessPhone}', (!empty($country->country_phonecode) ? '+' . $country->country_phonecode : '') . ' ' . (!empty($conf['BUSINESS_PHONE_NUMBER']) ? $conf['BUSINESS_PHONE_NUMBER'] : ''), $footer);

        $footer2 = str_replace('{privacyUrl}', getPageByType('privacy'), $conf['EMAIL_FOOTER2_HTML']);
        $footer2 = str_replace('{termsUrl}', getPageByType('terms'), $footer2);

        $logo = AttachedFile::getFileUrl('emailLogo', 0, 0, 'thumb');
        if (empty($logo)) {
            $logo = noImage("160x40.png");
        }

        $data->header = view('emails.partials.header', compact('logo'))->render();
        $social = Configuration::getRecordsByPrefix('EMAIL_SOCIAL_LINKS_');
        $data->footer = view('emails.partials.footer', compact('footer', 'footer2', 'social'))->render();

        return jsonresponse(true, null, $data);
    }

    public function update(EmailTemplateRequest $request, EmailTemplate $emailTemplate)
    {
        if (!canWrite(AdminRole::EMAIL_TEMPLATES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $etpl_body = str_replace(url('/'), '{baseurl}', $request->input('etpl_body'));
        preg_match_all('/<tbody id="emailWrapper">(.*?)<\/tbody>/s', $etpl_body, $instances);
        $etpl_body = $instances[1][0];
        EmailTemplate::where('etpl_id', $request->input('etpl_id'))->update([
            'etpl_name' => $request->input('etpl_name'),
            'etpl_subject' => $request->input('etpl_subject'),
            'etpl_body' => $etpl_body,
            'etpl_updated_by_id' => $this->admin->admin_id,
            'etpl_updated_at' => Carbon::now()
        ]);
        return jsonresponse(true, __('NOTI_EMAIL_TEMPLATE_UPDATED'));
    }

    public function loadSettings()
    {
        if (!canWrite(AdminRole::SYSTEM_SETTINGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $keys = ['EMAIL_BG_COLOR', 'EMAIL_FOOTER_DEFAULT_HTML', 'EMAIL_FOOTER_HTML', 'EMAIL_FOOTER2_DEFAULT_HTML', 'EMAIL_FOOTER2_HTML', 'EMAIL_IMAGE_RATIO', 'EMAIL_FOOTER_HTML_REPLACEMENTS', 'EMAIL_FOOTER2_HTML_REPLACEMENTS'];
        $configurations = Configuration::getRecordsByPrefix('EMAIL_');
        $data = $configurations->toArray();
        $records['settingData'] =  Arr::only($data, $keys);
        $records['socialDetails'] = Arr::except($data, $keys);
        return jsonresponse(true, '', $records);
    }

    public function saveSettings(Request $request)
    {
        if (!canWrite(AdminRole::SYSTEM_SETTINGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        Configuration::bulkUpdate($request->except(['actualImage', 'cropImage']));
        imageUpload($request, 0, 'emailLogo');
        return jsonresponse(true, __('NOTI_EMAIL_SETTINGS_UPDATED'));
    }
}
