<?php
  namespace Database\Factories;

  use App\Models\ProductToCategory;
  use App\Models\ProductCategory;
  use Illuminate\Database\Eloquent\Factories\Factory;
  
class ProductToCategoryFactory extends Factory
{
    protected $model = ProductToCategory::class;
     
    public function definition()
    {
        $randomCategory = ProductCategory::select('cat_id')->where('cat_publish', config('constants.YES'))
        ->inRandomOrder()->first();
            return [
                'ptc_cat_id' => $randomCategory->cat_id
            ];
    }
}
