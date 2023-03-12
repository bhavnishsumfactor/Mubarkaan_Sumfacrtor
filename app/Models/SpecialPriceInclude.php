<?php

namespace App\Models;

use App\Models\YokartModel;

class SpecialPriceInclude extends YokartModel
{
    public $timestamps = false;

    
    protected $fillable = [
      'spi_splprice_id', 'spi_record_id', 'spi_subrecord_id'
    ];
} 
