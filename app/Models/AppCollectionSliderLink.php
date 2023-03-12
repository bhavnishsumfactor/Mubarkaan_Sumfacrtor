<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\AdminYokartModel;

class AppCollectionSliderLink extends YokartModel
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'acsl_acs_id', 'acsl_value'
    ];
}
