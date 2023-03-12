<?php

namespace Database\Seeders;

use App\Models\StoreAddress;
use Illuminate\Database\Seeder;

class StoreAddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public $storeAddress = [
        [
            'saddr_id' => 1,
            'saddr_name' => "Tribe Store 1",
            'saddr_country_id' => 101,
            'saddr_state_id' => 32,
            'saddr_city' => "Sahibzada Ajit Singh Nagar",
            'saddr_address' => "Plot no. 268, Sector-82, JLPL Industrial Area",
            'saddr_phone' => "09779200800",
            'saddr_phone_country_id' => 101,
            'saddr_postal_code' => 140308,
            'saddr_shop_open_status' => 2,
        ],
        [
            'saddr_id' => 2,
            'saddr_name' => "Tribe Store 2",
            'saddr_country_id' => 101,
            'saddr_state_id' => 32,
            'saddr_city' => "Mohali",
            'saddr_address' => "Unit No. A-712, Tower A, 7th Floor,
              Bestech Business Towers, Sector-66",
            'saddr_phone' => "08559008860",
            'saddr_phone_country_id' => 101,
            'saddr_postal_code' => 160066,
            'saddr_shop_open_status' => 1,
        ]
      ];
    public function run()
    {
        StoreAddress::truncate();
        StoreAddress::insert($this->storeAddress);
       
    }
}
