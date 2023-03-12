<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\TaxGroupStatesData;
use App\Models\State;

class TaxGroupType extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'tgtype_id';

    protected $fillable = ['tgtype_tgroup_id', 'tgtype_name', 'tgtype_state_type', 'tgtype_rate',  'tgtype_country_id', 'tgtype_combined'];

    public function groupsCountry()
    {
        return $this->belongsTo('App\Models\Country', 'tgtype_country_id', 'country_id');
    }
    public function stateData()
    {
        return $this->hasMany('App\Models\TaxGroupStatesData', 'tgsd_tgtype_id', 'tgtype_id')->with('statesName');
    }
    
    public function combinedData()
    {
        return $this->hasMany('App\Models\TaxGroupCombined', 'tgc_tgtype_id', 'tgtype_id');
    }
    public static function groupTypesById($id) 
    {
        //return TaxGroupType::with('stateData')->with('combinedData')->where('tgtype_tgroup_id', $id)->get();
        $taxGroups = TaxGroupType::with('stateData')->with('combinedData')->where('tgtype_tgroup_id', $id)->get();
        if (isset($taxGroups) && !empty($taxGroups)) {
            foreach ($taxGroups as $key => $taxGroup) {
                $taxGroups[$key]['totalState'] = State::where('state_country_id', $taxGroup->tgtype_country_id)->pluck('state_name', 'state_id');
            }
        }
        return $taxGroups;
    }

    public static function allStates($id)
    {
        return TaxGroup::leftjoin('tax_group_types as gt', 'gt.tgtype_tgroup_id', 'tax_groups.taxgrp_id')->leftjoin('tax_group_states_data as gs', 'gs.tgsd_tgtype_id', 'gt.tgtype_id')->where('tax_groups.taxgrp_id', $id)->join('states', 'gs.tgsd_state_id', 'states.state_id')->pluck('state_name', 'state_id');
    }
    public static function getRecords($request)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }

        $query = TaxGroupType::with('groupsCountry:country_id,country_name')->select('tgtype_id', 'tgtype_name', 'tgtype_country_id', 'tgtype_rate', 'tgtype_state_type');
        if (!empty($request['search'])) {
            $query->where('tgtype_name', 'like', '%'.$request['search'].'%');
        }
        return $query->latest('tgtype_id')->paginate((int)$per_page);
    }
    public static function getRecordByGroupId($id)
    {
        return TaxGroupType::with('combinedData')->where('tgtype_tgroup_id', $id)->first();
    }
}
