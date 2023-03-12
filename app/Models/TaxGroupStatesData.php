<?php

namespace App\Models;

use App\Models\YokartModel;

class TaxGroupStatesData extends YokartModel
{
    public $timestamps = false;
    protected $fillable = ['tgsd_tgtype_id', 'tgsd_state_id'];

    public function statesName(){
        return $this->hasOne('App\Models\State','state_id','tgsd_state_id');
    }
}
