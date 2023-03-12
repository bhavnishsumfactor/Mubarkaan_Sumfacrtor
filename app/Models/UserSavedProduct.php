<?php

namespace App\Models;

use App\Models\YokartModel;

class UserSavedProduct extends YokartModel
{
    
    const SAVED_PRODUCTS = 0;
    const TEMP_SAVED_PRODUCTS = 1;

    public $timestamps = false; 

    protected $primaryKey = 'usp_id';
    protected $fillable = ['usp_user_id', 'usp_prod_id', 'usp_pov_code', 'usp_temp', 'usp_session_id'];

}
