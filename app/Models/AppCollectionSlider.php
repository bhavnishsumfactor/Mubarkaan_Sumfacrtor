<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\AdminYokartModel;

class AppCollectionSlider extends AdminYokartModel
{
    use HasFactory;

    const BRAND = 1;
    const CATEGORY = 2;
    const PRODUCT = 3;
    const CMS = 4;
    const CUSTOM = 5;

    const SLIDER_LINKING_TYPE = [
        self::BRAND => 'Brand',
        self::CATEGORY => 'Category',
        self::PRODUCT => 'Product',
    //    self::CMS => 'CMS',
        self::CUSTOM => 'Custom',
    ];
    public $timestamps = false;
    protected $primaryKey = 'acs_id';
    protected $fillable = [
        'acs_actr_id', 'acs_type'
    ];

    public function images()
    {
        return $this->hasOne('App\Models\AttachedFile', 'afile_record_id', 'acs_id');
    }
    public function brands()
    {
        return $this->hasMany('App\Models\AppCollectionSliderLink', 'acsl_acs_id', 'acs_id')->join('brands', 'brand_id', 'acsl_value')->join('app_collection_sliders as sliders', 'sliders.acs_id', 'acsl_acs_id')->where('acs_type', self::BRAND);
    }
    public function pages()
    {
        return $this->hasMany('App\Models\AppCollectionSliderLink', 'acsl_acs_id', 'acs_id')->join('pages', 'page_id', 'acsl_value')->join('app_collection_sliders as sliders', 'sliders.acs_id', 'acsl_acs_id')->where('acs_type', self::CMS);
    }
    public function categories()
    {
        return $this->hasMany('App\Models\AppCollectionSliderLink', 'acsl_acs_id', 'acs_id')->join('product_categories', 'cat_id', 'acsl_value')->join('app_collection_sliders as sliders', 'sliders.acs_id', 'acsl_acs_id')->where('acs_type', self::CATEGORY);
    }
    public function products()
    {
        return $this->hasMany('App\Models\AppCollectionSliderLink', 'acsl_acs_id', 'acs_id')->join('products', 'prod_id', 'acsl_value')->join('app_collection_sliders as sliders', 'sliders.acs_id', 'acsl_acs_id')->where('acs_type', self::PRODUCT);
    }
    public function custom()
    {
        return $this->belongsTo('App\Models\AppCollectionSliderLink', 'acs_id', 'acsl_acs_id')->join('app_collection_sliders as sliders', 'sliders.acs_id', 'acsl_acs_id')->where('acs_type', self::CUSTOM);
    }
    public static function getBannerCollectionById($collectionId)
    {
        return AppCollectionSlider::with('products:prod_id as record_id,prod_name as name,acsl_acs_id')->with('brands:brand_id as record_id,brand_name as name,acsl_acs_id')->with('pages:page_id as record_id,page_name as name,acsl_acs_id')->with('custom:acsl_acs_id,acsl_value')->with('categories:cat_id as record_id,cat_name as name,acsl_acs_id')->with(['images' => function ($sql) {
            $sql->select('afile_id', 'afile_updated_at', 'afile_physical_path', 'afile_record_id')->where('afile_type', AttachedFile::getConstantVal('appBannerSliderCollection'));
        }])->join('app_collection_to_records as actr', 'actr.actr_id', 'acs_actr_id')
        ->where('actr_id', $collectionId)->orderBy('actr.actr_display_order', 'ASC')->get();
    }
}
