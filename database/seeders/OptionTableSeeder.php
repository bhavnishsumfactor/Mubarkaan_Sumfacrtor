<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Seeder;

class OptionTableSeeder extends Seeder
{
    public $options = [
        [
          'option_id' => 1,
          'option_name' => 'Color',
          'option_type' => 1,
          'option_has_image' => 1,
          'option_has_sizechart' => 0,
          'option_created_by_id' => 0,
          'option_updated_by_id' => 0,
         
        ],
        [
            'option_id' => 2,
            'option_name' => 'Size',
            'option_type' => 2,
            'option_has_image' => 0,
            'option_has_sizechart' => 1,
            'option_created_by_id' => 0,
            'option_updated_by_id' => 0,
        ],[
            'option_id' => 3,
            'option_name' => 'Carat',
            'option_type' => 0,
            'option_has_image' => 0,
            'option_has_sizechart' => 0,
            'option_created_by_id' => 0,
            'option_updated_by_id' => 0,
        ],
        [
            'option_id' => 4,
            'option_name' => 'Clarity',
            'option_type' => 0,
            'option_has_image' => 0,
            'option_has_sizechart' => 0,
            'option_created_by_id' => 0,
            'option_updated_by_id' => 0,
        ],
        [
            'option_id' => 5,
            'option_name' => 'Strap',
            'option_type' => 0,
            'option_has_image' => 0,
            'option_has_sizechart' => 0,
            'option_created_by_id' => 0,
            'option_updated_by_id' => 0,
        ]
      ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Option::truncate();
        Option::insert($this->options);
        /*ini_set('memory_limit', '1024M');
        factory(Option::class, 1)->create();*/
    }
}
