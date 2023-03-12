<?php

namespace App\Models;

use App\Models\YokartModel;

class State extends YokartModel
{
    public $timestamps = false;
    protected $primaryKey = 'state_id';

    public function shippingToLocation()
    {
        return $this->hasMany('App\Models\ShippingToLocation', 'stloc_state_id', 'state_id');
    }

    public static function getRecordById($id)
    {
        return State::where('state_id', $id)->first();
    }
    public static function getStatesByCountryId($countryId)
    {
        return State::where('state_country_id', $countryId)->pluck('state_name', 'state_id');
    }

    public static function getStates()
    {
        return State::get()->pluck('state_name', 'state_id');
    }
    public static function getStatesByCountry($countryId)
    {
        return State::select('state_id', 'state_name')->where('state_country_id', $countryId)->get();
    }

    public static function getRecordByCode($code)
    {
        return State::where('state_code', $code)->first();
    }

    public static function getCountryStateFromCsv($countryId)
    {
        $file = public_path() . '/dummydata/states.csv';
        $fileD = fopen($file, "r");
        $state = [];
        while (!feof($fileD)) {
            $rowData = fgetcsv($fileD);
            if (isset($rowData[0]) && !empty($rowData[0])) {
                if ($rowData[3] == $countryId) {
                    $state[] = $rowData;
                }
            }  
        }
        return array_filter($state); 
    }
}
