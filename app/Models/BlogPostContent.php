<?php

namespace App\Models;

use App\Models\YokartModel;


class BlogPostContent extends YokartModel
{
    public $timestamps = false;
   
    protected $fillable = [
        'bpc_post_id', 'bpc_short_description', 'bpc_description'
    ];
}
