<?php

namespace App\Models;

use App\Models\YokartModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductToCategory extends YokartModel
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = [
       'ptc_prod_id', 'ptc_cat_id'
    ];

    public function categories()
    {
        return $this->hasOne('App\Models\ProductCategory', 'ptc_cat_id', 'cat_id');
    }
}
