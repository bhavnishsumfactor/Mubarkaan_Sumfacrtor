<?php

namespace Database\Factories;

use App\Models\ProductContent;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductContentFactory extends Factory
{
    protected $model = ProductContent::class;
    
    public function definition()
    {
        return [
            'pc_gift_wrap_available' => 0,
            'pc_warranty_age' => 90,
            'pc_return_age' => 7,
            'pc_cancellation_age' => 3
        ];
    }
}
