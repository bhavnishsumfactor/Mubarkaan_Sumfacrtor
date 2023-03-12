<?php

 namespace Database\Factories;

 use App\Models\ProductSpecification;
 use Illuminate\Database\Eloquent\Factories\Factory;
 
 class ProductSpecificationFactory extends Factory
 {
    protected $model = ProductSpecification::class;
    
    public function definition()
    {
        return [
            'ps_product_key' => $this->faker->word,
            'ps_product_value' => $this->faker->word,
            'ps_product_group' => $this->faker->word
        ];
    }
 }

