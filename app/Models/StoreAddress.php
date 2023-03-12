<?php

namespace App\Models;

use App\Models\YokartModel;

class StoreAddress extends YokartModel
{
    public $timestamps = false;
    protected $primaryKey = 'saddr_id';
    protected $fillable = [
        'saddr_country_id', 'saddr_name', 'saddr_state_id', 'saddr_city', 'saddr_address', 'saddr_phone', 'saddr_phone_country_id', 'saddr_postal_code', 'saddr_shop_open_status'
    ];

    public function timings()
    {
        return $this->hasMany('App\Models\StoreTiming', 'st_saddr_id', 'saddr_id');
    }

    public function days()
    {
        return $this->hasMany('App\Models\StoreTiming', 'st_saddr_id', 'saddr_id')->groupBy('st_day');
    }

    public static function getRecords($request, $paginate = true)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $query = StoreAddress::join('countries', 'countries.country_id', 'store_addresses.saddr_country_id')
            ->join('states', 'states.state_id', 'store_addresses.saddr_state_id')
            ->select('saddr_phone', 'saddr_name', 'saddr_id', 'country_name',  'saddr_phone_country_id', 'state_name', 'saddr_postal_code', 'saddr_city', 'saddr_address')->latest('saddr_id');

        if (!empty($request['search'])) {
            $query->where('saddr_address', 'like', '%' . $request['search'] . '%');
            $query->orWhere('saddr_city', 'like', '%' . $request['search'] . '%');
            $query->orWhere('country_name', 'like', '%' . $request['search'] . '%');
            $query->orWhere('state_name', 'like', '%' . $request['search'] . '%');
        }
        if ($paginate == true) {
            return $query->paginate((int) $per_page);
        }
        return $query->with('timings:st_saddr_id,st_day,st_from,st_to')->withCount('timings')->having('timings_count', '>', 0)->get();
    }
    public static function getRecordById($id)
    {
        return StoreAddress::join('countries', 'countries.country_id', 'store_addresses.saddr_country_id')
            ->join('states', 'states.state_id', 'store_addresses.saddr_state_id')
            ->select('saddr_phone', 'saddr_phone_country_id', 'saddr_name', 'saddr_id', 'country_name', 'country_code', 'state_name', 'saddr_postal_code', 'saddr_city', 'saddr_address')
            ->where('saddr_id', $id)->first();
    }
}
