<?php

namespace YoKartTax\SystemTaxManagement\Models;

use App\Models\TaxGroup;
use App\Models\TaxGroupStatesData;
use App\Models\PackageYokartModel;

class SystemTax extends PackageYokartModel
{
    public static function taxCalculate($taxId, $countryId, $stateId)
    {
       
        $records = TaxGroup::join('tax_group_types as tgt', 'tgt.tgtype_tgroup_id', 'tax_groups.taxgrp_id')
        ->where('tgt.tgtype_country_id', $countryId)
        ->where('tax_groups.taxgrp_id', $taxId)->get();
     
        $taxRate = 0;
        $taxArray = [];
        foreach ($records as $record) {
            if ($record->tgtype_state_type == TaxGroup::ALL_STATES) {
                $taxRate = $record->tgtype_rate;
            }
            if ($record->tgtype_state_type == TaxGroup::SPECIFIC_STATES) {
                $state = static::stateRecord($record->tgtype_id, $stateId);
                if ($state) {
                    $taxRate = $record->tgtype_rate;
                }
            }
            if ($record->tgtype_state_type == TaxGroup::EXCLUDING_STATES) {
                $state = static::stateRecord($record->tgtype_id, $stateId);
                if (empty($state)) {
                    $taxRate = $record->tgtype_rate;
                }
            }
            $taxArray = ['name' => $record->taxgrp_name,  'value' => $taxRate];
        }
        return $taxArray;
    }

    public static function stateRecord($typeId, $stateId)
    {
        return TaxGroupStatesData::where('tgsd_tgtype_id', $typeId)->where('tgsd_state_id', $stateId)->first();
    }
}
