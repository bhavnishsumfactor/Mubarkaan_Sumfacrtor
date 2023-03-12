<?php

namespace App\Helpers;

use Mail;
use App\Models\AttachedFile;
use App\Models\EmailTemplate;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\Admin\EmailArchive;
use Carbon\Carbon;
use Config;
use Log;
use URL;
use App\Helpers\YokartHelper;

class EmailHelper extends YokartHelper
{
    public static function sendMailTemplate($to, $subject, $data, $headers = array())
    {
        if (Configuration::getValue('SEND_SMTP_EMAIL')) {
            self::setSmtpConfigurations();
        } else {
            self::setSendmailConfigurations();
        }
        return static::sendEmail($to, $subject, $data, $headers);
    }

    public static function sendEmail($to, $subject, $data, $headers)
    {
        $records = Configuration::getKeyValues(['REPLY_TO_EMAIL', 'ADDITIONAL_EMAIL_ALERTS']);
        $replyToEmail = $records['REPLY_TO_EMAIL'];
        $additionalEmails = $records['ADDITIONAL_EMAIL_ALERTS'];

        $html = view('emails.body', $data)->render();
        try {
            if (config("app.ALLOW_EMAILS")) {
                $mailsent = Mail::send([], [], function ($message) use ($data, $to, $subject, $replyToEmail, $html) {
                    $dataHeaders = $message->getSwiftMessage()->getHeaders()->toString();
                    $message->setBody($html, 'text/html');
                    $message->to($to);
                    $message->subject($subject);


                    if (!empty($additionalEmails)) {
                        $ccEmails = explode(',', $additionalEmails);
                        foreach ($ccEmails as $ccEmail) {
                            if (filter_var($ccEmail, FILTER_VALIDATE_EMAIL)) {
                                $message->cc($ccEmail);
                            }
                        }
                    }
                    if (!empty($replyToEmail)) {
                        $message->replyTo($replyToEmail);
                    }
                    if (!empty($data['attachment'])) {
                        foreach ($data['attachment'] as $attachment) {
                            $params = !empty($attachment['params']) ? $attachment['params'] : [];
                            $message->attach($attachment['file'], $params);
                        }
                    }
                    EmailArchive::create([
                        'emailarchive_to_email' =>  $to,
                        'emailarchive_tpl_name' => '',
                        'emailarchive_subject' => $subject,
                        'emailarchive_body' => $html,
                        'emailarchive_headers' => $dataHeaders,
                        'emailarchive_created_at' => Carbon::now()
                    ]);
                });

                if (count(Mail::failures())) {
                    return 'failed';
                }
            } else {
                EmailArchive::create([
                    'emailarchive_to_email' =>  $to,
                    'emailarchive_tpl_name' => '',
                    'emailarchive_subject' => $subject,
                    'emailarchive_body' => $html,
                    'emailarchive_headers' => '',
                    'emailarchive_created_at' => Carbon::now()
                ]);
            }

            return 'sent';
        } catch (\Exception $e) {
            return 'failed';
        }
    }

    public static function getEmailData($slug)
    {
        $data = [];
        /*if (Configuration::getValue('SEND_SMTP_EMAIL')) {
            self::setSmtpConfigurations();
        }*/
        $conf = Configuration::getKeyValues([
            'BUSINESS_NAME',
            'BUSINESS_EMAIL',
            'BUSINESS_PHONE_COUNTRY_CODE',
            'BUSINESS_PHONE_NUMBER',
            'EMAIL_FOOTER_HTML',
            'EMAIL_FOOTER2_HTML'
        ]);
        $url = URL::to('/');
        if (!empty($conf['BUSINESS_PHONE_COUNTRY_CODE'])) {
            $country = Country::getCountries(['country_phonecode'], ['country_id' => $conf['BUSINESS_PHONE_COUNTRY_CODE']])->first();
        }
        
        $data['body'] = EmailTemplate::select('etpl_slug', 'etpl_subject', 'etpl_body', 'etpl_priority')->where('etpl_slug', $slug)->first();
        $footerHtml = str_replace('{websiteName}', ($conf['BUSINESS_NAME'] ?? ""), $conf['EMAIL_FOOTER_HTML']);
        $footerHtml = str_replace('{businessEmail}', ($conf['BUSINESS_EMAIL'] ?? ""), $footerHtml);
        $footerHtml = str_replace('{businessPhone}', (!empty($country->country_phonecode) ? '+' . $country->country_phonecode : '') . ' ' . ($conf['BUSINESS_PHONE_NUMBER'] ?? ''), $footerHtml);
        $data['footer'] = $footerHtml;
        $bodyHtml =str_replace('{baseurl}', ($url ?? ""), $data['body']['etpl_body']);
        $data['body']['etpl_body'] = $bodyHtml;
        $footer2Html = str_replace('{privacyUrl}', getPageByType('privacy'), $conf['EMAIL_FOOTER2_HTML']);
        $footer2Html = str_replace('{termsUrl}', getPageByType('terms'), $footer2Html);
        $data['footer2'] = $footer2Html;

        $data['social'] = Configuration::getRecordsByPrefix('EMAIL_SOCIAL_LINKS_');
        $data['logo'] = AttachedFile::getFileUrl('emailLogo', 0, 0, 'thumb');
        if (empty($data['logo'])) {
            $data['logo'] = noImage("160x40.png");
        }
        return $data;
    }

    private static function setSmtpConfigurations()
    {
        $configurationKeys = ['SMTP_HOST', 'SMTP_PORT', 'BUSINESS_NAME', 'FROM_EMAIL', 'SMTP_SECURE', 'SMTP_USERNAME', 'SMTP_PASSWORD'];
        $smtpConfigurationValues = Configuration::getKeyValues($configurationKeys);
        $config = array(
            'driver'     => 'smtp',
            'host'       => $smtpConfigurationValues['SMTP_HOST'],
            'port'       => $smtpConfigurationValues['SMTP_PORT'],
            'from'       => array('address' => $smtpConfigurationValues['FROM_EMAIL'], 'name' => $smtpConfigurationValues['BUSINESS_NAME']),
            'encryption' => $smtpConfigurationValues['SMTP_SECURE'],
            'username'   => $smtpConfigurationValues['SMTP_USERNAME'],
            'password'   => $smtpConfigurationValues['SMTP_PASSWORD'],
            'sendmail'   => '/usr/sbin/sendmail -bs',
            'pretend'    => false,
            'stream' => [
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]
        );
        Config::set('mail', $config);
    }

    private static function setSendmailConfigurations()
    {
        $configurationKeys = [ 'BUSINESS_NAME', 'FROM_EMAIL', ];
        $confValues = Configuration::getKeyValues($configurationKeys);
        $config = array(
            'driver'     => 'sendmail',
            'host'       => 'localhost',
            'port'       => 2525,
            'from'       => array('address' => $confValues['FROM_EMAIL'], 'name' => $confValues['BUSINESS_NAME']),
            'sendmail'   => '/usr/sbin/sendmail -t'            
        );
        Config::set('mail', $config);
    }
}
