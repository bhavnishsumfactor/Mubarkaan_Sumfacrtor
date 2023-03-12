<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
 
class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    public function definition()
    {
        $randomCategory = ProductCategory::select('cat_id')->where('cat_publish', config('constants.YES'))
        ->inRandomOrder()->first();
        $randomParentId = $this->faker->randomElement(['0', $randomCategory->cat_id]);
        
        static $displayOrder = 1;
        return [
            'cat_name' => $this->faker->company,
            'cat_parent' => $randomParentId,
            'cat_publish' => config('constants.YES'),
            'cat_display_order' => $displayOrder++
        ];
     }
}
