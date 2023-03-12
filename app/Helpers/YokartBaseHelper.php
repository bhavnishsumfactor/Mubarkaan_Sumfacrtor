<?php

use App\Models\AppNotificationTemplate;
use App\Models\UserDeviceToken;
use App\Models\AppPushNotificaton;

/* function to return standard json response */
function jsonresponse($status, $message, $data = [])
{
    $response = [];
    $response['status'] = $status;
    $response['message'] = $message;
    $response['data'] = $data;
    return response()->json($response);
}

function displayImageAttr($filename = '', $attr = '')
{
    $filename = substr($filename, 0, strrpos($filename, "."));
    return ($attr ?? $filename) ?? '';
}

/* Function to read json file */
function openJSONFile($filePath)
{
    if (File::exists($filePath)) {
        $jsonString = file_get_contents($filePath);
        return json_decode($jsonString, true);
    }
    return false;
}

/* Function to write to json file */
function saveJSONFile($filePath, $data)
{
    ksort($data);
    $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($filePath, stripslashes($jsonData));
}

function saveJSONFileMobile($filePath, $data)
{
    ksort($data);
    $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($filePath, $jsonData);
}

function imgExists($url)
{
    $headers = get_headers($url);
    return stripos($headers[0], "200 OK") ? true : false;
}

function noImage($imageName)
{
    return url('') . '/noimages/' . $imageName;
}

function randomString($length)
{
    $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($str_result), 0, $length);
}

function convertDays($convert)
{
    $years = ($convert / 365);
    $years = floor($years);

    $month = ($convert % 365) / 30;
    $month = floor($month);

    $days = ($convert % 365) % 30;
    $text = '';
    $plural = '';
    if ($years != 0) {
        if ($years > 1) {
            $plural = 's';
        }
        $text .= $years . ' Year' . $plural . ' ';
    }
    if ($month != 0) {
        if ($month > 1) {
            $plural = 's';
        }
        $text .= $month . ' Month' . $plural . ' ';
    }
    if ($days != 0) {
        if ($days > 1) {
            $plural = 's';
        }
        $text .= $days . ' Day' . $plural;
    }
    return $text;
}

function initials($string)
{
    $firstLetter = substr($string, 0, 1);
    $fullname = explode(' ', $string);
    $last = '';
    if (!empty($fullname[1])) {
        $last = substr($fullname[1], 0, 1);
    }
    return $firstLetter . '' . $last;
}
function sendPushNotification($userId, $slug, $arr = [])
{
    $notiData = AppNotificationTemplate::getBySlug($slug);
    $arr['website_name'] = env('APP_NAME');
    $data['title'] = replacePlaceholders($notiData->antpl_name, $arr);
    $data['body'] = replacePlaceholders($notiData->antpl_body, $arr);
    $data['type'] = $slug;
    $data['extra'] = $arr;
    
    $devices = getUserDevices($userId);
    if (isset($devices[UserDeviceToken::USER_DEVICE_TYPE_IOS]) && count($devices[UserDeviceToken::USER_DEVICE_TYPE_IOS]) > 0) {
        sendPushNotificationRequest(UserDeviceToken::USER_DEVICE_TYPE_IOS, $devices[UserDeviceToken::USER_DEVICE_TYPE_IOS], $data);
    }
    if (isset($devices[UserDeviceToken::USER_DEVICE_TYPE_ANDROID]) && count($devices[UserDeviceToken::USER_DEVICE_TYPE_ANDROID]) > 0) {
        sendPushNotificationRequest(UserDeviceToken::USER_DEVICE_TYPE_ANDROID, $devices[UserDeviceToken::USER_DEVICE_TYPE_ANDROID], $data);
    }
     saveNotificatios($userId, $data);
}
function saveNotificatios($userId, $data)
{
    AppPushNotificaton::create([
        'apn_user_id' => $userId,
        'apn_type' => $data['type'],
        'apn_title' => $data['title'],
        'apn_body' => $data['body'],
        'apn_extra' => json_encode($data['extra']),
        'apn_status' => 0,
        'apn_created_on' => now(),
    ]);
}
function sendPushNotificationRequest($deviceType, $deviceToken, $data = array())
{
    
    if (!array_key_exists('body', $data)) {
        $data['body'] = '';
    }
    if (array_key_exists('text', $data)) {
        $data['body'] = $data['text'];
    }
    $fields = [
        'registration_ids' => $deviceToken,
        'priority' => 'high'
    ];
    if ($deviceType == UserDeviceToken::USER_DEVICE_TYPE_IOS) {

        $fields['notification'] = $data;

    } else if ($deviceType == UserDeviceToken::USER_DEVICE_TYPE_ANDROID) {

        $fields['data'] = $data;
    }
    $headers = [
        'Authorization: key=' . getConfigValueByName('FCM_SERVER_KEY'),
        'Content-Type: application/json'
    ];
    $url = "https://fcm.googleapis.com/fcm/send";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $response = curl_exec($ch);
    $data = array();
    if ($response === false) {
        $data['status'] = false;
        $data['msg'] = curl_error($ch);
    } else {
        $data['status'] = true;
        $data['msg'] = $response;
    }
    curl_close($ch);
    return $data;
}
function getUserDevices($userId)
{
    $userDevices = [];
    $records = UserDeviceToken::select('udt_device_type', 'udt_device_token')->where('udt_user_id', $userId)->get();
    if (empty($records)) {
        return array();
    }
    foreach ($records as $record) {
        switch ($record->udt_device_type) {
            case UserDeviceToken::USER_DEVICE_TYPE_IOS:
                $userDevices[UserDeviceToken::USER_DEVICE_TYPE_IOS][] = $record->udt_device_token;
                break;
            case UserDeviceToken::USER_DEVICE_TYPE_ANDROID:
                $userDevices[UserDeviceToken::USER_DEVICE_TYPE_ANDROID][] = $record->udt_device_token;
                break;
        }
    }
    return $userDevices;
}

function replacePlaceHolders($msg, $data )
{
    foreach ($data as $key => $value) {
        $msg = str_replace('{' . $key . '}', $value, $msg);
    }
    return $msg;
}

