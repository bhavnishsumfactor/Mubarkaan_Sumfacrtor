<?php

namespace App\Models;

use App\Models\YokartModel;

class CollectionMeta extends YokartModel
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['cmeta_collection_id', 'cmeta_key'];
    protected $fillable = ['cmeta_collection_id', 'cmeta_key', 'cmeta_value'];

    public static function recordExists($collection_id, $key, $value)
    {
        return CollectionMeta::where('cmeta_collection_id', $collection_id)
        ->where('cmeta_key', $key)
        ->count();
    }

    public static function getValue($collectionId, $key)
    {
        $query = CollectionMeta::where('cmeta_collection_id', $collectionId);
        if (is_array($key) && count($key) > 0) {
            return $query->whereIn('cmeta_key', $key)->get()->pluck('cmeta_value', 'cmeta_key')->toArray();
        }
        return $query->where('cmeta_key', $key)->pluck('cmeta_value')->first();
    } 

    public static function saveOrUpdate($collection_id, $key, $value, $allowEmptyValues = false)
    {
        if (empty($value) && $allowEmptyValues == false) {
            return false;
        }
        if (!CollectionMeta::recordExists($collection_id, $key, $value)) {
            CollectionMeta::create([
            'cmeta_collection_id' => $collection_id,
            'cmeta_key' => $key,
            'cmeta_value' => $value
            ]);
        } else {
            CollectionMeta::where('cmeta_collection_id', $collection_id)
            ->where('cmeta_key', $key)
            ->update(['cmeta_value' => $value]);
        }
    }
}
