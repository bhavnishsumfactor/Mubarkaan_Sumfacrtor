<?php

namespace App\Models;

use App\Models\YokartModel;
use Illuminate\Support\Str;

class UrlRewrite extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'urlrewrite_id';

    protected $fillable = [
      'urlrewrite_id', 'urlrewrite_type', 'urlrewrite_record_id', 'urlrewrite_original', 'urlrewrite_custom'
    ];
    public const PRODUCT_TYPE = 1;
    public const PAGE_TYPE = 2;
    public const CATEGORY_TYPE = 3;
    public const BLOG_TYPE = 4;
    public const BRAND_TYPE = 5;

    const TYPE = [
      self::PRODUCT_TYPE => 'products',
      self::PAGE_TYPE => 'pages',
      self::CATEGORY_TYPE => 'categories',
      self::BLOG_TYPE => 'blogs',
      self::BRAND_TYPE => 'brands'
    ];

    public static function getType($type)
    {
        return self::TYPE[$type];
    }

    public static function typeFlipped()
    {
        return array_flip(self::TYPE);
    }

    public static function getByCustomUrl($urlRewriteCustom)
    {
        return UrlRewrite::select('urlrewrite_type', 'urlrewrite_original', 'urlrewrite_record_id')->where('urlrewrite_custom', $urlRewriteCustom)->first();
    }

    public static function getByOriginalUrl($urlRewriteOriginal)
    {
        return UrlRewrite::where('urlrewrite_original', $urlRewriteOriginal)->pluck('urlrewrite_custom')->first();
    }

    public static function getUrl($type, $recordId)
    {
        $originalUrl = Str::singular($type).'/'.$recordId;
        $urlRewriteCustom = UrlRewrite::where('urlrewrite_type', self::typeFlipped()[$type])
          ->where('urlrewrite_original', $originalUrl)->pluck('urlrewrite_custom')->first();
        if (!empty($urlRewriteCustom)) {
            return $urlRewriteCustom;
        }
        return $originalUrl;
    }

    public static function saveUrl($type, $recordId)
    {
        UrlRewrite::create([
          'urlrewrite_type' => self::typeFlipped()[$type],
          'urlrewrite_record_id' => $recordId,
          'urlrewrite_original' => Str::singular($type).'/'.$recordId,
          'urlrewrite_custom' => ''
        ]);
    }

    public static function removeUrl($type, $recordId)
    {
        UrlRewrite::where('urlrewrite_type', self::typeFlipped()[$type])
          ->where('urlrewrite_original', Str::singular($type).'/'.$recordId)->delete();
    }

    public static function updateRecordId()
    {
       $brands = Brand::select('brand_id')->get();
       foreach ($brands as $brand) {
        
           UrlRewrite::create([
            'urlrewrite_type' => 5,
            'urlrewrite_record_id' => $brand->brand_id,
            'urlrewrite_original' => 'brand/'.$brand->brand_id,
           ]);
       }
       dd($brands);
        /*UrlRewrite::chunk(100, function ($urls) {
            foreach ($urls as $url) {
               
                $recordId = explode('/', $url->urlrewrite_original);
                if (!empty($recordId)) {
                  
                    $url->urlrewrite_record_id = (int)$recordId[1];
                    $url->save();
                    echo $url->urlrewrite_record_id ;
                }
            }
        });*/
    }

    public static function checkUrlExists($type, $recordId)
    {
        return UrlRewrite::where('urlrewrite_type', $type)->where('urlrewrite_record_id', $recordId)->get()->count();
    }
}
