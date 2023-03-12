<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppExplore extends Model
{
    use HasFactory;

    const EXPLORE_PAGE_TYPE_EXTRA = 1;
    const EXPLORE_PAGE_TYPE_QUICK_LINKS = 2;

    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $fillable = ['ae_app_page_id', 'ae_type', 'ae_display_order', 'ae_title'];

    public static function getAllPages()
    {
        $explorePages = AppExplore::select("ae_app_page_id as page_id", "ae_type as type", "ae_display_order as display_order", "ae_title as page_title", "page_slug", "page_type")
                            ->join("app_pages", "app_pages.page_id", "app_explores.ae_app_page_id")
                            ->orderBy('ae_display_order', 'ASC')
                            ->get();
        return $explorePages;
    }
}
