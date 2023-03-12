<?php

    namespace App\Models;

use App\Models\YokartModel;
use App\Models\TaxGroupType;
use App\Models\TaxGroupStatesData;

class TaxGroup extends YokartModel
{
    const ALL_STATES = 0;
    const SPECIFIC_STATES = 1;
    const EXCLUDING_STATES = 2;
    const STATE_TYPES = [
        self::ALL_STATES => 'All States',
        self::SPECIFIC_STATES => 'Specific States',
        self::EXCLUDING_STATES => 'Excluding States'
        ];
    const TAX_APPLY_ON = [
        0 => 'Net Amount',
        1 => 'Tax amount',
        ];
    public $timestamps = false;

    protected $fillable = ['taxgrp_name', 'taxgrp_description'];

    public static function deleteTaxGroup($taxGroupId, $taxGroupDelete = true)
    {
        TaxGroupStatesData::whereIn('tgsd_tgtype_id', function ($query) use ($taxGroupId) {
            $query->select('tgtype_id')
                ->from('tax_group_types')
                ->where('tgtype_tgroup_id', '=', $taxGroupId);
        })->delete();
        TaxGroupCombined::whereIn('tgc_tgtype_id', function ($query) use ($taxGroupId) {
            $query->select('tgtype_id')
                ->from('tax_group_types')
                ->where('tgtype_tgroup_id', '=', $taxGroupId);
        })->delete();
        TaxGroupType::where('tgtype_tgroup_id', $taxGroupId)->delete();
        if ($taxGroupDelete == true) {
            TaxGroup::where('taxgrp_id', $taxGroupId)->delete();
        }
    }
    public function groupTypes()
    { 
        return $this->hasMany('App\Models\TaxGroupType', 'tgtype_tgroup_id', 'taxgrp_id');
    }
    public static function getRecords($request)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $query = TaxGroup::with('groupTypes');
        if (!empty($request['search'])) {
            $query->where('taxgrp_name', 'like', '%'.$request['search'].'%');
        }
        if($query->paginate((int)$per_page)->count() >= 1 && $query->paginate((int)$per_page)->currentPage() >= 1){
            return $query->latest('taxgrp_id')->paginate((int)$per_page);
        }
        else{
            return $query->latest('taxgrp_id')->paginate((int)$per_page, ['*'], 'page',  (int)$query->paginate((int)$per_page)->currentPage()-1);
        }
    }

    public static function getCount()
    {
        return TaxGroup::count();
    }
    public static function getRecordById($id)
    {
        return TaxGroup::with('groupTypes.combinedData')->where('taxgrp_id', $id)->first();
    }


}
