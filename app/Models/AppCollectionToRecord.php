<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\AdminYokartModel;
use App\Models\AttachedFile;
use App\Models\AppCollection;
use DB;

class AppCollectionToRecord extends AdminYokartModel
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'actr_id';
    protected $fillable = [
        'actr_ac_id', 'actr_type','actr_title', 'actr_slide_duration', 'actr_display_order', 'actr_updated_on'
    ];
    public function collectionsData()
    {
        return $this->hasMany('App\Models\AppCollectionRecordToData', 'acrd_actr_id', 'actr_id')->orderBy('acrd_display_order', 'ASC');
    }
    public function images()
    {
        return $this->hasOne('App\Models\AttachedFile', 'afile_record_id', 'acrd_id');
    }
    public function catCollection1Records()
    {
        return $this->hasMany('App\Models\AppCollectionRecordToData', 'acrd_actr_id', 'actr_id')->join('product_categories', 'cat_id', 'acrd_record_id')->join('app_collection_to_records', 'actr_id', 'acrd_actr_id')->where('actr_type', AppCollection::CAT_COLLECTION1)->orderBy('acrd_display_order', 'ASC');
    }
    public function catCollection2Records()
    {
        return $this->hasMany('App\Models\AppCollectionRecordToData', 'acrd_actr_id', 'actr_id')->join('product_categories', 'cat_id', 'acrd_record_id')->join('app_collection_to_records', 'actr_id', 'acrd_actr_id')->where('actr_type', AppCollection::CAT_COLLECTION2)->orderBy('acrd_display_order', 'ASC');
    }
    public function catCollection3Records()
    {
        return $this->hasMany('App\Models\AppCollectionRecordToData', 'acrd_actr_id', 'actr_id')->join('product_categories', 'cat_id', 'acrd_record_id')->join('app_collection_to_records', 'actr_id', 'acrd_actr_id')->where('actr_type', AppCollection::CAT_COLLECTION3)->orderBy('acrd_display_order', 'ASC');
    }
    public function brandCollection1Records()
    {
        return $this->hasMany('App\Models\AppCollectionRecordToData', 'acrd_actr_id', 'actr_id')->join('brands', 'brand_id', 'acrd_record_id')->join('app_collection_to_records', 'actr_id', 'acrd_actr_id')->where('actr_type', AppCollection::BRAND_COLLECTION1)->orderBy('acrd_display_order', 'ASC');
    }
    public function brandCollection2Records()
    {
        return $this->hasMany('App\Models\AppCollectionRecordToData', 'acrd_actr_id', 'actr_id')->join('brands', 'brand_id', 'acrd_record_id')->join('app_collection_to_records', 'actr_id', 'acrd_actr_id')->where('actr_type', AppCollection::BRAND_COLLECTION2)->orderBy('acrd_display_order', 'ASC');
    }
    public function productCollection1Records()
    {
        return $this->hasMany('App\Models\AppCollectionRecordToData', 'acrd_actr_id', 'actr_id')->join('products', 'prod_id', 'acrd_record_id')->leftjoin('product_option_varients', function ($sql) {
            $sql->on('pov_prod_id', 'prod_id')->where('pov_default', config('constants.YES'));
        })->join('app_collection_to_records', 'actr_id', 'acrd_actr_id')->where('actr_type', AppCollection::PRODUCT_COLLECTION1)->orderBy('acrd_display_order', 'ASC');
    }
    public function productCollection2Records()
    {
        return $this->hasMany('App\Models\AppCollectionRecordToData', 'acrd_actr_id', 'actr_id')->join('products', 'prod_id', 'acrd_record_id')->leftjoin('product_option_varients', function ($sql) {
            $sql->on('pov_prod_id', 'prod_id')->where('pov_default', config('constants.YES'));
        })->join('app_collection_to_records', 'actr_id', 'acrd_actr_id')->where('actr_type', AppCollection::PRODUCT_COLLECTION2)->orderBy('acrd_display_order', 'ASC');
    }
    public function blogCollection1Records()
    {
        return $this->hasMany('App\Models\AppCollectionRecordToData', 'acrd_actr_id', 'actr_id')->join('blog_posts', 'post_id', 'acrd_record_id')->join('app_collection_to_records', 'actr_id', 'acrd_actr_id')->where('actr_type', AppCollection::BLOG_COLLECTION1)->orderBy('acrd_display_order', 'ASC');
    }
    public function blogCollection2Records()
    {
        return $this->hasMany('App\Models\AppCollectionRecordToData', 'acrd_actr_id', 'actr_id')->join('blog_posts', 'post_id', 'acrd_record_id')->join('app_collection_to_records', 'actr_id', 'acrd_actr_id')->where('actr_type', AppCollection::BLOG_COLLECTION2)->orderBy('acrd_display_order', 'ASC');
    }
    public function testimonialCollection1Records()
    {
        return $this->hasMany('App\Models\AppCollectionRecordToData', 'acrd_actr_id', 'actr_id')->join('testimonials', 'testimonial_id', 'acrd_record_id')->join('app_collection_to_records', 'actr_id', 'acrd_actr_id')->where('actr_type', AppCollection::TESTIMONIAL_COLLECTION1)->orderBy('acrd_display_order', 'ASC');
    }
    public function testimonialCollection2Records()
    {
        return $this->hasMany('App\Models\AppCollectionRecordToData', 'acrd_actr_id', 'actr_id')->join('testimonials', 'testimonial_id', 'acrd_record_id')->join('app_collection_to_records', 'actr_id', 'acrd_actr_id')->where('actr_type', AppCollection::TESTIMONIAL_COLLECTION2)->orderBy('acrd_display_order', 'ASC');
    }
    public function bannerSliderCollection1Records()
    {
        return $this->hasMany('App\Models\AppCollectionSlider', 'acs_actr_id', 'actr_id')->join('app_collection_to_records', 'actr_id', 'acs_actr_id')->where('actr_type', AppCollection::BANNER_SLIDER1);
    }
    public function bannerSliderCollection2Records()
    {
        return $this->hasMany('App\Models\AppCollectionSlider', 'acs_actr_id', 'actr_id')->join('app_collection_to_records', 'actr_id', 'acs_actr_id')->where('actr_type', AppCollection::BANNER_SLIDER2);
    }
    public function bannerSliderCollection3Records()
    {
        return $this->hasMany('App\Models\AppCollectionSlider', 'acs_actr_id', 'actr_id')->join('app_collection_to_records', 'actr_id', 'acs_actr_id')->where('actr_type', AppCollection::BANNER_SLIDER3);
    }
    public static function getHomePageListing($pageId)
    {
        return AppCollectionToRecord::select('actr_id', 'actr_ac_id', 'actr_title', 'ac_type', 'actr_display_order', 'actr_updated_on')
        ->with('catCollection1Records:acrd_actr_id,cat_name,acrd_id,cat_id,acrd_display_order')
        ->with('catCollection2Records:acrd_actr_id,cat_name,acrd_id,cat_id,acrd_display_order')
        ->with('catCollection3Records:acrd_actr_id,cat_name,acrd_id,cat_id,acrd_display_order')
        ->with('testimonialCollection1Records:acrd_actr_id,testimonial_title,acrd_id,testimonial_description,testimonial_id,acrd_display_order')
        ->with('testimonialCollection2Records:acrd_actr_id,testimonial_title,acrd_id,testimonial_description,testimonial_id,acrd_display_order')
        ->with('brandCollection1Records:acrd_actr_id,brand_name,acrd_id,brand_id,acrd_display_order')
        ->with('brandCollection2Records:acrd_actr_id,brand_name,acrd_id,brand_id,acrd_display_order')
        ->with('blogCollection1Records:acrd_actr_id,post_title,acrd_id,post_id,post_published_on,acrd_display_order')
        ->with('blogCollection2Records:acrd_actr_id,post_title,acrd_id,post_id,post_published_on,acrd_display_order')
        ->with('bannerSliderCollection1Records:acs_id,acs_actr_id')
        ->with('bannerSliderCollection2Records:acs_id,acs_actr_id')
        ->with('bannerSliderCollection3Records:acs_id,acs_actr_id')
        ->with(['productCollection1Records' => function ($sql) {
            $sql->selectraw('acrd_actr_id,prod_name,pov_code,prod_updated_on,acrd_id,prod_id,acrd_display_order, coalesce(`pov_price`, `prod_price`) as price');
        }])
        ->with(['productCollection2Records' => function ($sql) {
            $sql->selectraw('acrd_actr_id,prod_name,pov_code,pov_display_title,prod_updated_on,acrd_id,prod_id,acrd_display_order, coalesce(`pov_price`, `prod_price`) as price');
        }])
        ->join('app_collections as ac', 'ac.ac_id', 'actr_ac_id')
        ->where('ac.ac_page_id', $pageId)->orderBy('actr_display_order', 'ASC')->get();
    }
    public static function getContentPageListing($pageId)
    {
        return AppCollectionToRecord::select('actr_id', 'actr_ac_id', 'ac_type', 'actr_display_order', 'actr_updated_on')
        ->with('collectionsData')->join('app_collections as ac', 'ac.ac_id', 'actr_ac_id')
        ->where('ac.ac_page_id', $pageId)->orderBy('actr_display_order', 'ASC')->get();
    }
  
    public static function getCatCollectionById($collectionId)
    {
        return AppCollectionToRecord::join('app_collection_record_to_data as acrd', 'acrd.acrd_actr_id', 'actr_id')
        ->join('product_categories', 'cat_id', 'acrd_record_id')->select('cat_name', 'actr_title', 'cat_id', 'acrd_display_order', 'acrd.acrd_id')->with(['images' => function ($sql) {
            $sql->select('afile_id', 'afile_updated_at', 'afile_physical_path', 'afile_record_id')->where('afile_type', AttachedFile::getConstantVal('appCategoryCollection'));
        }])
        ->where('actr_id', $collectionId)->orderBy('acrd_display_order', 'ASC')->get();
    }
    public static function getBrandCollectionById($collectionId)
    {
        return AppCollectionToRecord::join('app_collection_record_to_data as acrd', 'acrd.acrd_actr_id', 'actr_id')
        ->join('brands', 'brand_id', 'acrd_record_id')->select('brand_name', 'brand_id', 'actr_title', 'acrd_display_order', 'acrd.acrd_id')->with(['images' => function ($sql) {
            $sql->select('afile_id', 'afile_updated_at', 'afile_physical_path', 'afile_record_id')->where('afile_type', AttachedFile::getConstantVal('appBrandCollection'));
        }])
        ->where('actr_id', $collectionId)->orderBy('acrd_display_order', 'ASC')->get();
    }
    public static function getBlogCollectionById($collectionId)
    {
        return AppCollectionToRecord::join('app_collection_record_to_data as acrd', 'acrd.acrd_actr_id', 'actr_id')
        ->join('blog_posts', 'post_id', 'acrd_record_id')->select('post_title', 'actr_title', 'acrd_display_order', 'post_id', 'acrd.acrd_id')
        ->where('actr_id', $collectionId)->orderBy('acrd_display_order', 'ASC')->get();
    }
    public static function getTestimonialCollectionById($collectionId)
    {
        return AppCollectionToRecord::join('app_collection_record_to_data as acrd', 'acrd.acrd_actr_id', 'actr_id')
        ->join('testimonials', 'testimonial_id', 'acrd_record_id')->select('testimonial_title', 'actr_title', 'acrd_display_order', 'testimonial_id', 'acrd.acrd_id')->with(['images' => function ($sql) {
            $sql->select('afile_id', 'afile_updated_at', 'afile_physical_path', 'afile_record_id')->where('afile_type', AttachedFile::getConstantVal('appTestimonialCollection'));
        }])
        ->where('actr_id', $collectionId)->orderBy('acrd_display_order', 'ASC')->get();
    }
    public static function getProductCollectionById($collectionId)
    {
        return AppCollectionToRecord::join('app_collection_record_to_data as acrd', 'acrd.acrd_actr_id', 'actr_id')
        ->join('products', 'prod_id', 'acrd_record_id')->select('prod_name', 'actr_title', 'prod_id', 'acrd_display_order', 'acrd.acrd_id')->where('actr_id', $collectionId)->orderBy('acrd_display_order', 'ASC')->get();
    }
    public static function getFavCategories()
    {
        return AppCollectionToRecord::join('app_collection_record_to_data as acrd', 'acrd.acrd_actr_id', 'actr_id')
        ->join('product_categories', 'cat_id', 'acrd_record_id')->select('cat_name', 'cat_id')
        ->where('actr_type', AppCollection::FAV_CATEGORIES)->orderBy('acrd_display_order', 'ASC')->get();
    }
 
    public static function getAppListing($pageId)
    {
        $obj = AppCollectionToRecord::select('actr_id', 'actr_title', 'actr_type')
        ->with(['blogCollection1Records' => function ($bSql) {
            $bSql->leftJoin('attached_files', function ($sql) {
                $sql->on('afile_record_id', 'post_id')->where('afile_type', AttachedFile::SECTIONS['blogImage']);
            });
            $bSql->leftJoin('url_rewrites', function ($sql) {
                $sql->on('urlrewrite_record_id', 'post_id')->where('urlrewrite_type', UrlRewrite::BLOG_TYPE);
            })->select('acrd_actr_id', 'post_title', 'post_published_on', DB::Raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '?t=', UNIX_TIMESTAMP(afile_updated_at)) as image"),DB::Raw("(CASE WHEN urlrewrite_custom IS NULL THEN CONCAT('" . url('') . "', 'blog/', post_id) WHEN urlrewrite_custom = '' THEN CONCAT('" . url('') . "', '/',urlrewrite_original) ELSE CONCAT('" . url('') . "','/',urlrewrite_custom) END) as detail_url"));
        }]);

        $obj->with(['blogCollection2Records' => function ($bSql) {
            $bSql->leftJoin('attached_files', function ($sql) {
                $sql->on('afile_record_id', 'post_id')->where('afile_type', AttachedFile::SECTIONS['blogImage']);
            })
            ->leftJoin('url_rewrites', function ($sql) {
                $sql->on('urlrewrite_record_id', 'post_id')->where('urlrewrite_type', UrlRewrite::BLOG_TYPE);
            })
            ->select('acrd_actr_id', 'post_title', 'post_published_on', DB::Raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '?t=', UNIX_TIMESTAMP(afile_updated_at)) as image"),DB::Raw("(CASE WHEN urlrewrite_custom IS NULL THEN CONCAT('" . url('') . "', 'blog/', post_id) WHEN urlrewrite_custom = '' THEN CONCAT('" . url('') . "', '/',urlrewrite_original) ELSE CONCAT('" . url('') . "','/',urlrewrite_custom) END) as detail_url"));
        }]);


        $obj->with(['testimonialCollection1Records' => function ($tSql) {
            $tSql->leftJoin('attached_files', function ($sql) {
                $sql->on('afile_record_id', 'acrd_id')->where('afile_type', AttachedFile::SECTIONS['appTestimonialCollection']);
            })->select('acrd_actr_id', 'testimonial_title', 'testimonial_user_name', 'testimonial_description', DB::Raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '?t=', UNIX_TIMESTAMP(afile_updated_at)) as image"));
        }]);

        $obj->with('testimonialCollection2Records:acrd_actr_id,testimonial_title,testimonial_description,testimonial_user_name');

        $obj->with(['brandCollection1Records' => function ($bSql) {
            $bSql->with(['image' => function ($imgSql) {
                $imgSql->selectRaw("afile_record_id,CONCAT('" . url('') . "', '/yokart/image/', afile_id, '?t=', UNIX_TIMESTAMP(afile_updated_at)) as uploaded_img")->where('afile_type', AttachedFile::SECTIONS['appBrandCollection']);
            }])->leftJoin('attached_files', function ($sql) {
                $sql->on('afile_record_id', 'brand_id')->where('afile_type', AttachedFile::SECTIONS['brandLogo']);
            })->select('acrd_actr_id', 'acrd_id', 'brand_id', 'brand_name', DB::Raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '?t=', UNIX_TIMESTAMP(afile_updated_at)) as brand_img"));
        }]);
        $obj->with(['brandCollection2Records' => function ($bSql) {
            $bSql->with(['image' => function ($imgSql) {
                $imgSql->selectRaw("afile_record_id,CONCAT('" . url('') . "', '/yokart/image/', afile_id, '?t=', UNIX_TIMESTAMP(afile_updated_at)) as uploaded_img")->where('afile_type', AttachedFile::SECTIONS['appBrandCollection']);
            }])->leftJoin('attached_files', function ($sql) {
                $sql->on('afile_record_id', 'brand_id')->where('afile_type', AttachedFile::SECTIONS['brandLogo']);
            })->select('acrd_actr_id', 'acrd_id', 'brand_id', 'brand_name', DB::Raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '?t=', UNIX_TIMESTAMP(afile_updated_at)) as brand_img"));
        }]);
        $obj->with(['catCollection1Records' => function ($cSql) {
            $cSql->leftJoin('attached_files', function ($sql) {
                $sql->on('afile_record_id', 'acrd_id')->where('afile_type', AttachedFile::SECTIONS['appCategoryCollection']);
            })->select('acrd_actr_id', 'cat_id', 'cat_name', DB::Raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '?t=', UNIX_TIMESTAMP(afile_updated_at)) as image"));
        }]);
        $obj->with(['catCollection2Records' => function ($cSql) {
            $cSql->leftJoin('attached_files', function ($sql) {
                $sql->on('afile_record_id', 'acrd_id')->where('afile_type', AttachedFile::SECTIONS['appCategoryCollection']);
            })->select('acrd_actr_id', 'cat_id', 'cat_name', DB::Raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '?t=', UNIX_TIMESTAMP(afile_updated_at)) as image"));
        }]);
        $obj->with(['catCollection3Records' => function ($cSql) {
            $cSql->leftJoin('attached_files', function ($sql) {
                $sql->on('afile_record_id', 'acrd_id')->where('afile_type', AttachedFile::SECTIONS['appCategoryCollection']);
            })->select('acrd_actr_id', 'cat_id', 'cat_name', DB::Raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '?t=', UNIX_TIMESTAMP(afile_updated_at)) as image"));
        }]);
        $obj->with(['bannerSliderCollection1Records' => function ($banSql) {
            $banSql->leftJoin('app_collection_slider_links','acs_id','acsl_acs_id')->groupBy('acs_id');
            $banSql->leftJoin('attached_files', function ($sql) {
                $sql->on('afile_record_id', 'acs_id')->where('afile_type', AttachedFile::SECTIONS['appBannerSliderCollection']);
            })->select('acs_actr_id','acs_type',DB::raw('group_concat(acsl_value) as value'),'actr_slide_duration', DB::Raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '?t=', UNIX_TIMESTAMP(afile_updated_at)) as image"));
        }]);
        $obj->with(['bannerSliderCollection2Records' => function ($banSql) {
            $banSql->leftJoin('app_collection_slider_links','acs_id','acsl_acs_id')->groupBy('acs_id');
            $banSql->leftJoin('attached_files', function ($sql) {
                $sql->on('afile_record_id', 'acs_id')->where('afile_type', AttachedFile::SECTIONS['appBannerSliderCollection']);
            })->select('acs_actr_id','acs_type',DB::raw('group_concat(acsl_value) as value'), 'actr_slide_duration', DB::Raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '?t=', UNIX_TIMESTAMP(afile_updated_at)) as image"));
        }]);
        $obj->with(['bannerSliderCollection3Records' => function ($banSql) {
            $banSql->leftJoin('app_collection_slider_links','acs_id','acsl_acs_id')->groupBy('acs_id');
            $banSql->leftJoin('attached_files', function ($sql) {
                $sql->on('afile_record_id', 'acs_id')->where('afile_type', AttachedFile::SECTIONS['appBannerSliderCollection']);
            })->select('acs_actr_id','acs_type',DB::raw('group_concat(acsl_value) as value'),'actr_slide_duration', DB::Raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '?t=', UNIX_TIMESTAMP(afile_updated_at)) as image"));
        }]);
        $obj->with('productCollection1Records:acrd_actr_id,prod_id');

        $obj->with('productCollection2Records:acrd_actr_id,prod_id');

        return $obj->join('app_collections as ac', 'ac.ac_id', 'actr_ac_id')
        ->where('ac.ac_page_id', $pageId)->orderBy('actr_display_order', 'ASC')->get();
    }
    public static function getContentListingForApp($pageId)
    {
        return AppCollectionToRecord::select('actr_id', 'ac_type', 'actr_updated_on')
        ->with(['collectionsData' => function ($banSql) {
            $banSql->leftJoin('attached_files', function ($sql) {
                $sql->on('afile_record_id', 'acrd_id')->where('afile_type', AttachedFile::SECTIONS['appImageCollection']);
            })->select('acrd_description', 'acrd_actr_id', DB::Raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '?t=', UNIX_TIMESTAMP(afile_updated_at)) as image"));
        }])->join('app_collections as ac', 'ac.ac_id', 'actr_ac_id')
        ->where('ac.ac_page_id', $pageId)->orderBy('actr_display_order', 'ASC')->get();
    }
}
