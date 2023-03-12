<?php

namespace App\Models;

use App\Models\YokartModel;
use DB;
use Illuminate\Support\Facades\Validator;

class UserAddress extends YokartModel
{
    public $timestamps = false;
    protected $primaryKey = 'addr_id';
    protected $fillable = [
        'addr_id', 'addr_title', 'addr_email', 'addr_user_id', 'addr_first_name', 'addr_last_name', 'addr_address1', 'addr_address2', 'addr_city', 'addr_country_id', 'addr_state_id',
        'addr_phone_country_id', 'addr_phone', 'addr_postal_code', 'addr_billing_default', 'addr_shipping_default', 'addr_apartment'
    ];

    public static function getRecordByUserId($id, $paginate = true, $perPage = '')
    {
        $per_page = config('app.pagination');
        if (!empty($perPage)) {
            $per_page = $perPage;
        }

        $obj = UserAddress::with('country')
            ->with('phonecountry:country_name,country_id,country_phonecode,country_code')
            ->with('state:state_id,state_name')
            ->where('addr_user_id', $id)
            ->orderBy('addr_id', 'desc');
        if ($paginate == true) {
            return $obj->paginate((int) $per_page);
        }
        return $obj->get();
    }

    public static function getAddresses($userId, $row = 0)
    {
        $per_page = config('app.pagination');
        $query = UserAddress::with('country')
            ->with('phonecountry:country_name,country_id,country_phonecode,country_code')
            ->with('state:state_id,state_name')
            ->where('addr_user_id', $userId)
            ->orderBy('addr_id', 'desc');
        if ($row != 0) {
            return $query->skip($row)->take($per_page)->get()->toArray();
        } else {
            return $query->paginate($per_page);
        }
    }

    public static function getAddressByid($id)
    {
        return UserAddress::with('country')->with('state')->with('phonecountry:country_id,country_code,country_phonecode')->where('addr_id', $id)->first();
    }
    public function state()
    {
        return $this->belongsTo('App\Models\State', 'addr_state_id', 'state_id');
    }
    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'addr_country_id', 'country_id');
    }
    public function phonecountry()
    {
        return $this->belongsTo('App\Models\Country', 'addr_phone_country_id', 'country_id');
    }
    public static function getUserAddresses($userId, $addressType = '')
    {
        $address = UserAddress::where('addr_user_id', $userId)->join('countries', 'countries.country_id', 'user_addresses.addr_country_id')
            ->join('states', 'states.state_id', 'user_addresses.addr_state_id')
            ->select('user_addresses.*', 'country_name', 'country_code', 'state_name')
            ->where('addr_user_id', $userId);
        if (!empty($addressType)) {
            $address->where($addressType, 1);
        } else {
            $address->latest('addr_id');
        }
        return $address->first();
    }
    public static function getAddress($userId)
    {
        $address = UserAddress::where('addr_user_id', $userId)->first();
        if (!empty($address)) {
            return $address;
        }
        return UserAddress::where('addr_user_id', $userId)->latest('addr_id')->first();
    }
    public static function getRecordByid($id)
    {
        return UserAddress::join('countries', 'countries.country_id', 'user_addresses.addr_country_id')
            ->join('states', 'states.state_id', 'user_addresses.addr_state_id')
            ->select('user_addresses.*', 'country_name', 'country_code', 'state_name')
            ->with('phonecountry:country_name,country_id,country_phonecode,country_code')
            ->where('addr_id', $id)->first();
    }
    public static function addressValidate(array $data)
    {
        $validator = Validator::make($data, [
            'addr_email' => ['required', 'email'],
            'addr_first_name' => ['required', 'string', 'max:191'],
            'addr_last_name' => ['required', 'string', 'max:191'],
            'addr_address1' => ['required'],
            'addr_city' => ['required'],
            'addr_country_id' => ['required', 'integer'],
            'addr_state_id' => ['required', 'integer'],
            'addr_postal_code' => ['required'],
            'addr_phone' => 'required|unique:user_addresses|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ]);
        return $validator->setAttributeNames([
            'addr_email' => 'email address',
            'addr_first_name' => 'firest name',
            'addr_last_name' => 'last name',
            'addr_address1' => 'address',
            'addr_city' => 'city',
            'addr_country_id' => 'country',
            'addr_state_id' => 'state',
            'addr_postal_code' => 'pin code',
            'addr_phone' => 'phone',
        ]);
    }
    public static function getAddressesForApp($userId, $page, $paginate = true)
    {
        $query = UserAddress::select(
            '*',
            DB::Raw("IF(addr_address2 IS NULL, '', addr_address2) as addr_address2"),
            DB::Raw("CONCAT(addr_first_name, ' ', addr_last_name) as addr_name")
        )
            ->with('country')
            ->with('phonecountry:country_name,country_id,country_phonecode,country_code')
            ->with('state:state_id,state_name')
            ->where('addr_user_id', $userId)
            ->orderBy('addr_id', 'desc');
        if ($paginate === true) {
            return $query->paginate((int) config('app.app_pagination'), ['*'], 'page', $page);
        }
        return $query->get();
    }

    public static function getAddressForApp($userId, $addrId)
    {
        return UserAddress::select(
            '*',
            DB::Raw("IF(addr_address2 IS NULL, '', addr_address2) as addr_address2"),
            DB::Raw("CONCAT(addr_first_name, ' ', addr_last_name) as addr_name")
        )
            ->with('country')
            ->with('phonecountry:country_name,country_id,country_phonecode,country_code')
            ->with('state:state_id,state_name')
            ->where('addr_user_id', $userId)
            ->where('addr_id', $addrId)->first();
    }

    public static function getAddressByidForApp($id)
    {
        return UserAddress::where('addr_id', $id)->first();
    }
}
