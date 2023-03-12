<?php

namespace App\Models;

use App\Models\YokartModel;

class TaxGroupCombined extends YokartModel
{
     public $timestamps = false;
     protected $fillable = ['tgc_tgtype_id', 'tgc_name', 'tgc_rate', 'tgc_applied_on'];
       protected $table = 'tax_group_combined';
}