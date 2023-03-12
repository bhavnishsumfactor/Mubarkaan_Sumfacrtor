<?php

namespace App\Models;

use App\Models\YokartModel;

class ProductToBlogPost extends YokartModel
{
     public $timestamps = false;

     protected $fillable = ['ptbp_post_id', 'ptbp_prod_id'];
}
