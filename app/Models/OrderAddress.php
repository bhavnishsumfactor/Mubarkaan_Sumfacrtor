<?php

namespace App\Models;

use App\Models\YokartModel;

class OrderAddress extends YokartModel
{
    const BILLING_ADDRESS_TYPE = 1;
    const SHIPPING_ADDRESS_TYPE = 2;
    const SHIPPING_PICKUP_TYPE = 3;

    const ADDRESS_TYPES = [
        self::BILLING_ADDRESS_TYPE => 'Billing Address',
        self::SHIPPING_ADDRESS_TYPE => 'Shipping Address',
        self::SHIPPING_PICKUP_TYPE => 'Pickup Address',
        ];
    public $timestamps = false;
    protected $fillable = [
        'oaddr_order_id', 'oaddr_type', 'oaddr_name', 'oaddr_email', 'oaddr_address1', 'oaddr_address2',
        'oaddr_city', 'oaddr_state', 'oaddr_country', 'oaddr_country_code', 'oaddr_phone_country_id', 'oaddr_phone', 'oaddr_postal_code'
        ];

    public function country()
    {
        return $this->hasOne('App\Models\Country', 'country_id', 'oaddr_phone_country_id');
    }

    public static function saveAddress($orderId, $address, $type)
    {
        OrderAddress::create([
            'oaddr_order_id' => $orderId,
            'oaddr_name' => $address['addr_first_name'] . ' ' . $address['addr_last_name'],
            'oaddr_type' => $type,
            'oaddr_email' => $address['addr_email'],
            'oaddr_address1' => $address['addr_address1'],
            'oaddr_address2' => $address['addr_address2'],
            'oaddr_city' => $address['addr_city'],
            'oaddr_state' => $address['state_name'],
            'oaddr_country' => $address['country_name'],
            'oaddr_country_code' => $address['country_code'],
            'oaddr_postal_code' => $address['addr_postal_code'],
            'oaddr_phone_country_id' => !empty($address['addr_phone_country_id']) ? $address['addr_phone_country_id'] : null,
            'oaddr_phone' => $address['addr_phone'],
        ]);
    }

    public static function saveAddressFromUserAddress($orderId, $userAddressId, $type)
    {
        $address = UserAddress::getAddressByidForApp($userAddressId)->toArray();
        $state = State::getRecordById($address['addr_state_id']);
        $country = Country::getRecordById($address['addr_country_id']);
        OrderAddress::create([
            'oaddr_order_id' => $orderId,
            'oaddr_name' => $address['addr_first_name'] . ' ' . $address['addr_last_name'],
            'oaddr_type' => $type,
            'oaddr_email' => $address['addr_email'],
            'oaddr_address1' => $address['addr_address1'],
            'oaddr_address2' => !empty($address['addr_address2']) ? $address['addr_address2'] : "",
            'oaddr_city' => $address['addr_city'],
            'oaddr_state' => ($state->state_name ?? ""),
            'oaddr_country' => ($country->country_name ?? ""),
            'oaddr_country_code' => ($country->country_code ?? ""),
            'oaddr_postal_code' => $address['addr_postal_code'],
            'oaddr_phone_country_id' => !empty($address['addr_phone_country_id']) ? $address['addr_phone_country_id'] : "",
            'oaddr_phone' => $address['addr_phone'],
        ]);
    }
}
