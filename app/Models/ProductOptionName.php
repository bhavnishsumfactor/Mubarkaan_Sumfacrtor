<?php

namespace App\Models;

use App\Models\YokartModel;

class ProductOptionName extends YokartModel
{
    public $timestamps = false;
    protected $primaryKey = 'opn_id';

    protected $fillable = ['opn_value', 'opn_color_code'];


    public static function getRecordsByIds($ids)
    {
        return ProductOptionName::whereIn('opn_id', $ids)->pluck('opn_value', 'opn_id');
    }
}
