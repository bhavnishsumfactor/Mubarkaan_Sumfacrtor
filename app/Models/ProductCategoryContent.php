<?php

namespace App\Models;

use App\Models\YokartModel;

class ProductCategoryContent extends YokartModel
{
    public $timestamps = false;

    protected $fillable = [
        'pcc_cat_id', 'cat_description'
    ];
}
