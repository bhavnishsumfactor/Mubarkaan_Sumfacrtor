<?php

namespace App\Models;

use App\Models\YokartModel;

class CollectionLabel extends YokartModel
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'collection_component_id';
    protected $fillable = ['collection_component_id', 'collection_title', 'collection_caption', 'collection_featured_product'];

    public static function recordExists($component_id)
    {
        return CollectionLabel::where('collection_component_id', $component_id)
        ->count();
    }

    public static function getData($component_id)
    {
        return CollectionLabel::select('collection_featured_product')->where('collection_component_id', $component_id)->first();
    }

    public static function saveOrUpdate($component_id, $data)
    {
        if (!CollectionLabel::recordExists($component_id)) {
            CollectionLabel::create([
              'collection_component_id' => $component_id,
              'collection_title' => ($data['collection_title'] ?? ''),
              'collection_caption' => ($data['collection_caption'] ?? ''),
              'collection_featured_product' => ($data['collection_featured_product'] ?? '')
            ]);
        } else {
            CollectionLabel::where('collection_component_id', $component_id)->update($data);
        }
    }
}
