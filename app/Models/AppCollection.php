<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\AdminYokartModel;

class AppCollection extends AdminYokartModel
{
    use HasFactory;

    public $timestamps = false;

    const CAT_COLLECTION1 = 1;
    const BANNER_SLIDER1 = 2;
    const BANNER_SLIDER2 = 3;
    const PRODUCT_COLLECTION1 = 4;
    const BRAND_COLLECTION1 = 5;
    const TESTIMONIAL_COLLECTION1 = 6;
    const BLOG_COLLECTION1 = 7;
    const PRODUCT_COLLECTION2 = 8;
    const CAT_COLLECTION2 = 9;
    const BRAND_COLLECTION2 = 10;
    const BANNER_SLIDER3 = 11;
    const CAT_COLLECTION3 = 12;
    const BLOG_COLLECTION2 = 13;
    const RECENT_BOUGHT_COLLECTION1 = 14;
    const TESTIMONIAL_COLLECTION2 = 15;
    const H1_TAG = 16;
    const H2_TAG = 17;
    const H3_TAG = 18;
    const H4_TAG = 19;
    const H5_TAG = 20;
    const H6_TAG = 21;
    const DESCRIPTION_TAG = 22;
    const UL_TAG = 23;
    const IMAGE_TAG = 24;
    const FAV_CATEGORIES = 25;
    
    const TAGS_TYPES = [
        self::H1_TAG => 'h1_tag',
        self::H2_TAG => 'h2_tag',
        self::H3_TAG => 'h3_tag',
        self::H4_TAG => 'h4_tag',
        self::H5_TAG => 'h5_tag',
        self::H6_TAG => 'h6_tag',
        self::DESCRIPTION_TAG => 'desc_tag',
        self::UL_TAG => 'ul_tag',
        self::IMAGE_TAG => 'img_tag',
    ];
     
    protected $primaryKey = 'post_id';
    protected $fillable = [
        'ac_id', 'ac_page_id', 'ac_type', 'ac_display_order'
    ];


    public static function getListing($pageId)
    {
        return AppCollection::where('ac_page_id', $pageId)->orderBy('ac_display_order', 'ASC')->get();
    }
}
