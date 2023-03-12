<?php

namespace Database\Factories;

use App\Models\ProductOptionVarient;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductOptionVarientFactory extends Factory
{
    protected $model = ProductOptionVarient::class;
    
    public function definition()
    {
        return [
            'pov_price' => $this->faker->numberBetween(10, 5000),
            'pov_quantity' => $this->faker->numberBetween(10, 500),
            'pov_sku' => strtoupper($this->faker->bothify('#?##??#?')),
            'pov_publish' => config('constants.YES')
        ];
    }
}
