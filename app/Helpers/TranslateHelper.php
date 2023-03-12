<?php

namespace App\Helpers;

use App\Helpers\YokartHelper;

class TranslateHelper extends YokartHelper
{
    protected $url;
    protected $subscriptionKey;

    public function __construct($subscriptionKey)
    {
        $this->subscriptionKey = $subscriptionKey;
        $this->url = 'https://api.cognitive.microsofttranslator.com/translate?api-version=3.0';
    }

    public function translateData($from, $to, $data)
    {
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        
        $headers = array(
            'Content-type: application/json',
            'Content-length: ' . strlen($data) ,
            'Ocp-Apim-Subscription-Key: ' . $this->subscriptionKey ,
            'X-ClientTraceId: ' . $this->comCreateGuid()
        );

        $url = $this->url . "&to=" . $to . "&from=" . $from;
        
        return executeCurl($url, $data, $headers);
    }

    private function comCreateGuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
