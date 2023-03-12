<?php

namespace App\Models;

use App\Models\YokartModel;

class ProductReviewComment extends YokartModel
{
    public $timestamps = false;
    protected $fillable = ['prc_preview_id', 'prc_admin_id', 'prc_comments','prc_created_at'];

    public function admin()
    {
        return $this->belongsTo('App\Models\Admin\Admin', 'prc_admin_id', 'admin_id');
    }
}
