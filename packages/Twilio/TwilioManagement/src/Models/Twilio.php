<?php

namespace Twilio\TwilioManagement\Models;

use Twilio\Rest\Client;
use Twilio\Rest\Lookups\V1\PhoneNumberList;
use Twilio\Rest\Numbers;
use App\Models\PackageYokartModel;
use Twilio\Exceptions\TwilioException;

class Twilio extends PackageYokartModel
{
    public static function sendSms($message, $recipients, $config)
    {
        try {
            $client = new Client($config['sid'], $config['auth_token']);
            $response = $client->messages->create($recipients, ['from' => $config['number'], 'body' => $message]);
            if ($response) {
                return true;
            }
        } catch (Exception $e) {
            return false;
        }
    }
  
    public static function lookup($config, $number)
    {
        try {
            $client = new Client($config['sid'], $config['auth_token']);
            $response = $client->lookups->v1->phoneNumbers($number)->fetch();
            if ($response) {
                $status = true;
                $data = $response;
            } else {
                $status = false;
                $data = "";
            }
            return ['status' => $status, 'data' => $data];
        } catch (TwilioException $e) {
            return ['status' => false, 'data' => $e->getMessage()];
        }
    }
}
