<?php

namespace App\Models;

use App\Models\YokartModel;

class NavigationMenu extends YokartModel
{
    public $timestamps = false;
    protected $primaryKey = 'navmenu_id';
    protected $fillable = ['navmenu_collection_id', 'navmenu_type', 'navmenu_label', 'navmenu_record_id', 'navmenu_url', 'navmenu_target', 'navmenu_display_order'];

    const MENU_TYPE = [
        1 => 'category',
        2 => 'cms page',
        3 => 'custom link'
    ];
    public function catUrls()
    {
        return $this->belongsTo('App\Models\UrlRewrite', 'navmenu_record_id', 'urlrewrite_record_id')->where('urlrewrite_type', UrlRewrite::CATEGORY_TYPE);
    }
    public function pageUrls()
    {
        return $this->belongsTo('App\Models\UrlRewrite', 'navmenu_record_id', 'urlrewrite_record_id')->where('urlrewrite_type', UrlRewrite::PAGE_TYPE);
    }
    public static function getMenusByCollectionId($collectionId)
    {
        return NavigationMenu::select('navmenu_id', 'navmenu_collection_id', 'navmenu_type', 'navmenu_label', 'navmenu_record_id', 'navmenu_url', 'navmenu_target', 'navmenu_display_order')->with('catUrls')->with('pageUrls')->where('navmenu_collection_id', $collectionId)
        ->oldest('navmenu_display_order')->get();
    }

    public static function getMenuByDisplayOrder($collectionId, $displayOrder)
    {
        return NavigationMenu::select('navmenu_id')->where('navmenu_collection_id', $collectionId)->where('navmenu_display_order', $displayOrder)
        ->first();
    }
}
