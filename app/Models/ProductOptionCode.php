<?php

namespace App\Models;

use App\Models\YokartModel;

class ProductOptionCode extends YokartModel
{
    public $timestamps = false;

    protected $fillable = ['poc_prod_id', 'poc_poption_id', 'poc_pov_code'];
}
