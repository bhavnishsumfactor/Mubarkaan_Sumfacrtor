<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\DigitalFileRecord;
use App\Models\ProductOption;
use App\Models\Option;
use App\Models\AttachedFile;
use App\Models\ProductOptionVarient;
use App\Models\ProductContent;
use App\Models\ProductSpecification;
use App\Models\UrlRewrite;
use App\Models\ProductOptionName;
use App\Models\ProductToCategory;
use App\Models\OptionToVarient;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

         Product::truncate();
        $sql =  base_path('public/dummydata/yokart_products.sql');
        \DB::unprepared(file_get_contents($sql));
/*
        ini_set('memory_limit', '-1');
        
        $hasVariants = [
            1 => 'Yes',
            2 => 'No'
        ];
        
        $prodTypes = array_flip(Product::PRODUCT_TYPES);
        $hasVariantsFlipped = array_flip($hasVariants);
    
        Product::factory()->count(1000)->create()->each(function ($product) use ($prodTypes, $hasVariants, $hasVariantsFlipped) {
            UrlRewrite::saveUrl('products', $product->prod_id);
           if ($product->prod_type == $prodTypes['Digital']) {
                $product->digitalFiles()->saveMany(DigitalFileRecord::factory()->times(rand(1, 5))->make());
            }
            
            if (array_rand($hasVariants) == $hasVariantsFlipped['Yes']) {
                $options = $product->options()->saveMany(ProductOption::factory()->times(rand(1, 5))->make());
               
                $optionsArr = ProductOptionName::whereIn('opn_id', $options->pluck('poption_opn_id')->toArray())->pluck('opn_value', 'opn_id')->toArray();
                $sortedOptions = [];
                foreach ($options as $option) {
                    $sortedOptions[$option->poption_option_id][] = $option->poption_opn_id;
                    $optionHasImage = Option::where('option_id', $option->poption_option_id)->where('option_has_image', 1)->count();
                    if ($optionHasImage > 0) {
                        AttachedFile::assignImageRandomly('productImages', rand(1, 2), $product->prod_id, $option->poption_id);
                    }else{
                        AttachedFile::assignImageRandomly('productImages', rand(1, 2), $product->prod_id, 0);
                    }
                }
                $sortedOptions = cartesianArray($sortedOptions);
               
                foreach ($sortedOptions as $key => $option) {
                    $temp = [];
                    foreach ($option as $value) {
                        $temp[] = $optionsArr[$value];
                    }
                    
                    $optionIds = $option;
                    array_unshift($option, $product->prod_id);
                    $variant = $product->varients()->save(ProductOptionVarient::factory()->make([
                        'pov_code' => implode('|', $option) .  '|',
                        'pov_display_title' => implode('_', $temp),
                        'pov_default' => ($key == 0) ? 1 : 0
                    ]));
                    foreach ($optionIds as $optionId) {
                        OptionToVarient::create([
                            'otv_pov_id' =>  $variant->pov_id,
                            'otv_opn_id' => $optionId
                        ]);
                    }
                    unset($temp);
                }
            } else {
                AttachedFile::assignImageRandomly('productImages', rand(1, 5), $product->prod_id, 0);
            }

            $product->productContent()->save(ProductContent::factory()->make());
            $product->specifications()->saveMany(ProductSpecification::factory()->times(rand(2, 10))->make());
            $product->productCategory()->save(ProductToCategory::factory()->make());
        });*/
    }
}
