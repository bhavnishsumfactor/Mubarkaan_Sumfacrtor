<?php

namespace App\Models;

use App\Models\YokartModel;

class UrlShortener extends YokartModel
{
    public $timestamps = false;
    protected $primaryKey = 'urlshortener_id';
    protected $fillable = [
        'urlshortener_id', 'urlshortener_short', 'urlshortener_full'
    ];

    public static function getFullUrl($shortUrl)
    {
        return UrlShortener::select('urlshortener_full')->where('urlshortener_short', $shortUrl)->first();
    }

    public static function saveUrl($url)
    {
        $shortUrl = randomString(10);
        $exists = UrlShortener::getFullUrl($shortUrl);
        if (!empty($exists)) {
            $shortUrl = randomString(10);
        }
        UrlShortener::create([
          'urlshortener_short' => $shortUrl,
          'urlshortener_full' => $url
        ]);
        return $shortUrl;
    }
}
