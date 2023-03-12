<?php

namespace App\Models;

use App\Models\YokartModel;
use Carbon\Carbon;

class MetaTag extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'meta_id';

    protected $fillable = [
        'meta_controller', 'meta_action', 'meta_record_id', 'meta_subrecord_id', 'meta_default',
        'meta_title', 'meta_keywords', 'meta_description', 'meta_other_meta_tags', 'meta_created_by_id', 'meta_updated_by_id', 'meta_created_at', 'meta_updated_at'
    ];

    const ROUTE_NAMES = [
      'pages' => 'showPage',
      'faqs' => 'faqs',
      'products' => 'productDetail',
      'productslisting' => 'productListing',
      'brands' => 'brandListing',
      'categories' => 'categoryListing',
      'blogs' => 'blogDetail',
      'blogslisting' => 'blogListing'
    ];

    public static function getRouteName($module_type)
    {
        return self::ROUTE_NAMES[$module_type];
    }

    public static function getMetaTags($controller, $action, $record_id = null, $subrecord_id = null)
    {
        return MetaTag::select('meta_id', 'meta_title', 'meta_keywords', 'meta_description', 'meta_other_meta_tags', 'meta_created_by_id', 'meta_updated_by_id', 'meta_created_at', 'meta_updated_at')
          ->where('meta_controller', $controller)
          ->where('meta_action', $action)
          ->where('meta_record_id', $record_id)
          ->where('meta_subrecord_id', $subrecord_id)
          ->with(['createdAt', 'updatedAt'])
          ->first();
    }

    public static function saveMetaTags($data, $controller, $action, $record_id = null, $subrecord_id = null, $admin_id = null )
    {        
        $data['meta_other_meta_tags'] = (!empty($data['meta_other_meta_tags']) && $data['meta_other_meta_tags'] != 'null')?$data['meta_other_meta_tags']:'';
        $data['meta_updated_by_id'] = $admin_id;
        $data['meta_updated_at'] = Carbon::now();
        $metaTag = MetaTag::getMetaTags($controller, $action, $record_id, $subrecord_id);
        if (isset($metaTag->meta_id)) {
            MetaTag::where('meta_id', $metaTag->meta_id)->update($data);
        } else {
            MetaTag::create([
                'meta_controller' => $controller,
                'meta_action' => $action,
                'meta_record_id' => $record_id,
                'meta_subrecord_id' => $subrecord_id,
                'meta_title' => $data['meta_title'],
                'meta_keywords' => $data['meta_keywords'],
                'meta_description' => $data['meta_description'],
                'meta_other_meta_tags' => $data['meta_other_meta_tags'],
                'meta_created_by_id' => $admin_id,
                'meta_updated_by_id' => $admin_id,
                'meta_created_at' => Carbon::now(),
                'meta_updated_at' => Carbon::now()
            ]);
        }
    }

    public function createdAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'meta_created_by_id')->select(['admin_id', 'admin_username']);
    }

    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'meta_updated_by_id')->select(['admin_id', 'admin_username']);
    }
}
