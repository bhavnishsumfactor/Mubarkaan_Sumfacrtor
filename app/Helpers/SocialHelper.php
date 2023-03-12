<?php

namespace App\Helpers;

use App\Helpers\YokartHelper;
use Exception;
use Socialite;
use Config;
use App\Models\Configuration;
use App\Services\InstagramApi;

class SocialHelper extends YokartHelper
{
    public const SHARETHIS_NETWORKS = [
        'facebook',
        'twitter',
        'pinterest',
        'email',
        'linkedin',
        'reddit',
        'tumblr',
        'digg',
        'whatsapp',
        'weibo',
        'wechat',
        'skype',
        'telegram'
    ];

    public static function authorizeInstagram()
    {
        self::initSocialite();
        $instaObj = new InstagramApi(config('services.instagram'));
        return $instaObj->getAuthorizeUrl();
    }

    public static function getInstagramAccessData($params)
    {
        self::initSocialite();
        $data = [
            'status' => true,
            'message' => '',
            'data' => [],
        ];

        $instaObj = new InstagramApi(config('services.instagram'));
        $result = $instaObj->getAccessToken($params);

        if (isset($result['code'])) {
            $data['status'] = false;
            $data['message'] = $result['error_message'];
            return $data;
        }
        $data['data']['user_id'] = $result['user_id'];
        
        $response = $instaObj->getLongLiveAccessToken($result['access_token']);
        
        $data['data']['access_token'] = $response['access_token'];
        $data['data']['expires_in'] = $response['expires_in'];
        
        $userData = $instaObj->getUser($response['access_token']);
        $data['data']['username'] = $userData['username'];
        return $data;
    }

    public static function getInstagramData($user_id, $access_token, $username, $limit = 0)
    {
        self::initSocialite();
        $data = [];

        $instaObj = new InstagramApi(config('services.instagram'));
        $result = $instaObj->getMedia($user_id, $access_token, $limit);
        $data['images'] = !empty($result['data'])?$result['data']:[];
        $data['handle'] = $username;
        $data['link'] = "https://www.instagram.com/" . $username;
        return $data;
    }

    public static function getSharethisNetworks()
    {
        return self::SHARETHIS_NETWORKS;
    }

    public static function initSocialite()
    {
        $configurationData  = Configuration::getKeyValues([
            'FACEBOOK_CLIENT_ID',
            'FACEBOOK_CLIENT_SECRET',
            'GOOGLE_CLIENT_ID',
            'GOOGLE_CLIENT_SECRET',
            'INSTAGRAM_CLIENT_ID',
            'INSTAGRAM_CLIENT_SECRET'
        ]);
        Config::set('services.facebook.client_id', $configurationData['FACEBOOK_CLIENT_ID']);
        Config::set('services.facebook.client_secret', $configurationData['FACEBOOK_CLIENT_SECRET']);
        Config::set('services.facebook.redirect', secure_url('/') . '/callback/facebook');
        Config::set('services.google.client_id', $configurationData['GOOGLE_CLIENT_ID']);
        Config::set('services.google.client_secret', $configurationData['GOOGLE_CLIENT_SECRET']);
        Config::set('services.google.redirect', secure_url('/') . '/callback/google');
        Config::set('services.instagram.client_id', $configurationData['INSTAGRAM_CLIENT_ID']);
        Config::set('services.instagram.client_secret', $configurationData['INSTAGRAM_CLIENT_SECRET']);
        Config::set('services.instagram.redirect', secure_url('/') . '/callback/instagram');
        Config::set('services.instagram.feed_auth_callback', secure_url('instagramfeed/auth/callback'));
    }
}
