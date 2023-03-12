<?php

namespace Database\Factories;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition()
    {
        return [
            'brand_name' => $this->faker->company,
            'brand_publish' => config('constants.YES'),
            'brand_created_by_id' => 1,
            'brand_updated_by_id' => 1,
            'brand_updated_at' => now(),
        ];
    }
}


