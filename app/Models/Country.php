<?php

namespace App\Models;

use App\Models\YokartModel;
use Illuminate\Support\Str;

class Country extends YokartModel
{
    public $timestamps = false;
    protected $primaryKey = 'country_id';

    public function states()
    {
        return $this->hasMany('App\Models\State', 'state_country_id', 'country_id');
    }
    public function shippingToLocation()
    {
        return $this->hasMany('App\Models\ShippingToLocation', 'stloc_country_id', 'country_id');
    }
    public static function getRecordById($id)
    {
        return Country::where('country_id', $id)->first();
    }

    public static function getAll()
    {
        return Country::select('country_id', 'country_name', 'country_code', 'country_phonecode')->get();
    }

    public static function getStatesByCountryCode($code, $fields)
    {
        return Country::select($fields)->join('states', 'states.state_country_id', 'countries.country_id')->where('country_code', $code)->get();
    }

    public static function getRecordByCode($code)
    {
        return Country::join('states', 'states.state_country_id', 'countries.country_id')
            ->select('country_id', 'state_id')->where('country_code', $code)->first();
    }
    public static function getCountries($fields, $conditions = [])
    {
        $query = Country::select($fields);
        $query->where($conditions);
        return $query->get();
    }

    public static function getCountryIdByCountryCode($code)
    {
        return Country::select('country_id')->where('country_code', Str::upper($code))->first()->country_id;
    }

    public static function getAllCountriesFromCsv()
    {
        $file = public_path() . '/dummydata/countries.csv';
        $fileD = fopen($file, "r");
        while (!feof($fileD)) {
            $rowData[] = fgetcsv($fileD);
        }
        return array_filter($rowData);
    }
}
