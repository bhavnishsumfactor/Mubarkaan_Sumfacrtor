<?php
 
 namespace Database\Factories;

 use App\Models\Product;
 use App\Models\Brand;
 use App\Models\TaxGroup;
 use App\Models\Country;
 use App\Models\ShippingBoxPackage;
 use Illuminate\Database\Eloquent\Factories\Factory;
 
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $prodType = 1;
        $prodConditions = array_flip(Product::PRODUCT_CONDITIONS);
        $country = Country::select('country_id')->where('country_code', 'IN')->first();
        $randomBrand = Brand::select('brand_id')->where('brand_publish', config('constants.YES'))->inRandomOrder()->first();
        $randomTaxGroup = TaxGroup::select('taxgrp_id')->inRandomOrder()->first();
        $shippingPackage = ShippingBoxPackage::select('sbpkg_id')->inRandomOrder()->first();
        $price =  $this->faker->numberBetween(10, 5000);
        $stock =  $this->faker->numberBetween(10, 500);
        $stockSold = $this->faker->numberBetween(1, $stock);
        return [
            'prod_name' => $this->faker->sentence(rand(1, 4)),
            'prod_type' => $prodType,
            'prod_brand_id' => $randomBrand->brand_id,
            'prod_taxcat_id' => $randomTaxGroup->taxgrp_id,
            'prod_cost' => $price,
            'prod_price' => $price,
            'prod_stock' => $stock,
            'prod_model' => strtoupper($this->faker->bothify('#?##??#?')),
            'prod_condition' => $this->faker->randomElement($prodConditions),
            'prod_published' => config('constants.YES'),
            'prod_publish_from' => now(),
            'prod_stock_out_sellable' => 1,
            'prod_cod_available' => $this->faker->randomElement([0, 1]),
            'prod_self_pickup' => $this->faker->randomElement([0, 1]),
            'prod_min_order_qty' => 1,
            'prod_from_origin_country_id' => $country->country_id,
            'prod_sbpkg_id' => $shippingPackage->sbpkg_id,
            'prod_max_order_qty' => 5,
            'prod_description' => $this->faker->paragraph,
            'prod_sold_count' => $stockSold,
            'prod_created_on' => now(),
            'prod_updated_on' => now(),
        ];
    }
}