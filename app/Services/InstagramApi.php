<?php
namespace App\Services;

use App\Services\YokartApi;

class InstagramApi extends YokartApi
{
    private const API_BASE_URL = "https://api.instagram.com/";
    private const GRAPH_BASE_URL = "https://graph.instagram.com/";

    private $appId;
    private $appSecret;
    private $redirectUrl;

    public function __construct($config)
    {
        $this->appId = $config["client_id"];
        $this->appSecret = $config["client_secret"];
        $this->redirectUrl = $config["feed_auth_callback"];
    }

    public function getAuthorizeUrl()
    {
        $getVars = array(
            'app_id' => $this->appId,
            'redirect_uri' => secure_url($this->redirectUrl),
            'scope' => 'user_profile,user_media',
            'response_type' => 'code'
        );
        return self::API_BASE_URL . 'oauth/authorize?' . http_build_query($getVars);
    }

    public function getAccessToken($params)
    {
        $getVars = array(
            'client_id' => $this->appId,
            'client_secret' => $this->appSecret,
            'grant_type' => 'authorization_code',
            'redirect_uri' => secure_url($this->redirectUrl),
            'code' => $params['code']
        );
        return executeCurl(self::API_BASE_URL . 'oauth/access_token', $getVars);
    }

    public function getLongLiveAccessToken($access_token)
    {
        return executeCurl(self::GRAPH_BASE_URL . "access_token?grant_type=ig_exchange_token&client_secret={$this->appSecret}&access_token={$access_token}");
    }

    public function getUser($access_token)
    {
        return executeCurl(self::GRAPH_BASE_URL . "me?fields=username&access_token={$access_token}");
    }

    public function getMedia($user_id, $access_token, $limit)
    {
        return executeCurl(self::GRAPH_BASE_URL . "{$user_id}/media?fields=id,caption,media_type,media_url,permalink,thumbnail_url&access_token={$access_token}&limit={$limit}");
    }
}
