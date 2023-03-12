<?php

namespace App\Models;

use App\Models\YokartModel;

class BlogPostToComment extends YokartModel
{
    public $timestamps = false;


    protected $fillable = [
        'bptc_post_id', 'bptc_bpcomment_id'
    ];

    public function comments()
    {
        return $this->belongsTo('App\Models\BlogPostComment', 'bpcomment_id', 'bptc_bpcomment_id');
    }
}
