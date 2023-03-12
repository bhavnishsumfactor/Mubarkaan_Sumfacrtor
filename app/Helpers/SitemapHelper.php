<?php

namespace App\Helpers;

use App\Helpers\YokartHelper;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\UrlRewrite;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Page;
use App\Models\BlogPost;
use Carbon\Carbon;

class SitemapHelper extends YokartHelper
{
    public static function generateSitemap()
    {
        $siteMapObject = Sitemap::create();
        self::productSiteMapGenerate($siteMapObject);
        self::categorySiteMapGenerate($siteMapObject);
        self::blogSiteMapsGenerate($siteMapObject);
        self::pageSiteMapsGenerate($siteMapObject);
        $siteMapObject->writeToDisk('public', 'sitemap.xml');
        return true;
    }

    public static function productSiteMapGenerate($siteMapObject)
    {
        Product::select('prod_id', 'prod_name')->orderBy('prod_id')->chunk(100, function ($products) use ($siteMapObject) {
            foreach ($products as $product) {
                self::siteMapCode($siteMapObject, 'products', $product->prod_id, Url::CHANGE_FREQUENCY_WEEKLY, 0.8);
            }
        });
    }

    public static function categorySiteMapGenerate($siteMapObject)
    {
        ProductCategory::select('cat_id', 'cat_name')->orderBy('cat_id')->chunk(100, function ($categories) use ($siteMapObject) {
            foreach ($categories as $category) {
                self::siteMapCode($siteMapObject, 'categories', $category->cat_id, Url::CHANGE_FREQUENCY_WEEKLY, 0.8);
            }
        });
    }

    public static function blogSiteMapsGenerate($siteMapObject)
    {
        BlogPost::select('post_id')->orderBy('post_id')->chunk(100, function ($blogPosts) use ($siteMapObject) {
            foreach ($blogPosts as $blogPost) {
                self::siteMapCode($siteMapObject, 'blogs', $blogPost->post_id, Url::CHANGE_FREQUENCY_WEEKLY, 0.8);
            }
        });
    }

    public static function pageSiteMapsGenerate($siteMapObject)
    {
        Page::select('page_id')->orderBy('page_id')->chunk(100, function ($pages) use ($siteMapObject) {
            foreach ($pages as $page) {
                self::siteMapCode($siteMapObject, 'pages', $page->page_id, Url::CHANGE_FREQUENCY_WEEKLY, 0.8);
            }
        });
    }

    public static function siteMapCode($siteMapObject, $type, $id, $frequecy, $priority)
    {
        $siteMapObject->add(Url::create('/' . UrlRewrite::getUrl($type, $id))
                    ->setLastModificationDate(Carbon::today())
                    ->setChangeFrequency($frequecy)
                    ->setPriority($priority));
    }
}
