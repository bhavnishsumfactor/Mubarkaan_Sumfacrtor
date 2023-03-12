<?php

namespace Database\Seeders;

use App\Models\TaxGroup;
use App\Models\TaxGroupType;
use Illuminate\Database\Seeder;

class TaxTableSeeder extends Seeder
{
    
  public $tax = [
      [
        'taxgrp_id' => 1,
        'taxgrp_name' => "Tax",
        'taxgrp_description' => '',
      ]
    ];
  public $groupTypes = [
      [
        'tgtype_id' => 1,
        'tgtype_tgroup_id' => 1,
        'tgtype_name' => "Tax",
        'tgtype_rate' => 10,
        'tgtype_state_type' => 0,
        'tgtype_country_id' => 101,
        'tgtype_combined' => 0,
      ],
      [
        'tgtype_id' => 2,
        'tgtype_tgroup_id' => 1,
        'tgtype_name' => "Tax",
        'tgtype_rate' => 5,
        'tgtype_state_type' => 0,
        'tgtype_country_id' => 231,
        'tgtype_combined' => 0,
      ]
    ];

  public function run()
  {
      TaxGroup::truncate();
      TaxGroupType::truncate();
      TaxGroup::insert($this->tax);
      TaxGroupType::insert($this->groupTypes);
  }
}
