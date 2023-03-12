<?php

namespace App\Models;

use App\Models\YokartModel;

class BlogPostToCategory extends YokartModel
{
     public $timestamps = false;
   
    protected $fillable = [
        'ptc_post_id', 'ptc_bpcat_id'
    ];
}
