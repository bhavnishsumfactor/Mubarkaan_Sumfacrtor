<?php

namespace Database\Factories;

use App\Models\ProductOption;
use App\Models\ProductOptionName;
use App\Models\Option;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductOptionFactory extends Factory
{
    protected $model = ProductOption::class;
    
    public function definition()
    {
        $randomOption = Option::select('option_id', 'option_type')->inRandomOrder()->first();
        $opn = ProductOptionName::create([
            'opn_value' => $this->faker->word,
            'opn_color_code' => $randomOption->option_type == 1 ? $this->faker->hexcolor() : ''
        ]);
        return [
            'poption_option_id' => $randomOption->option_id,
            'poption_opn_id' => $opn->opn_id
        ];
    }
}

