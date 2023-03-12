<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\ProductOption;
use App\Models\Option;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductOptionVarient extends YokartModel
{
    use HasFactory;

    protected $primaryKey = 'pov_id';
    public $timestamps = false;
    protected $fillable = [
        'pov_code', 'pov_display_title', 'pov_price', 'pov_prod_id', 'pov_quantity', 'pov_sku', 'pov_publish', 'pov_default'
    ];
    public static function getByCode($code)
    {
        return ProductOptionVarient::where('pov_code', $code)->first();
    }
    public function options()
    {
        return  $this->hasMany('App\Models\OptionToVarient', 'otv_pov_id', 'pov_id')
            ->join('product_option_names', 'otv_opn_id', 'opn_id')
            ->join('product_options', function ($sql) {
                $sql->on('poption_opn_id', 'opn_id')
                    ->join('options', 'poption_option_id', 'option_id');
            })->orderByRaw("FIELD(option_type, 1,2,0)")->groupBy('opn_id');
    } 
    public static function getActiveRecordsByProductId($prodId)
    {
        return ProductOptionVarient::withCount('options')->where(['pov_publish' => config('constants.YES'), 'pov_prod_id' => $prodId])->get();
    }
    public static function getRecordsByProductId($prodId)
    {

        $varients = ProductOptionVarient::where('pov_publish', config('constants.YES'));
        if (is_array($prodId) && count($prodId) > 0) {
            $varients->whereIN('pov_prod_id', $prodId);
        } else {
            $varients->where('pov_prod_id', $prodId);
        }
        $varientRecords = $varients->orderBy('pov_default', 'Desc')->pluck('pov_code');

        $codeArray = [];
        foreach ($varientRecords as $varient) {
            $varient = array_filter(explode('|', $varient));
            unset($varient[0]);
            $codeArray[] = $varient;
        }

        if (count($codeArray) != 0) {
            $mergeCodeArray = call_user_func_array('array_merge', $codeArray);

            return ProductOption::optionsByIds([$prodId], array_unique($mergeCodeArray));
        }
        return [];
    }

    public static function getRecordsByProductIds($records = [], $limit = false, $type = '', $searchedItems = [])
    {

        $prodId =  $records->pluck('prod_id');

        $varientRecords = $records->pluck('pov_code');
        $codeArray = [];
        foreach ($varientRecords as $varient) {
            $varient = array_filter(explode('|', $varient));
            unset($varient[0]);
            if (count($varient) != 0) {
                $codeArray[] = $varient;
            }
        }
        $codeArray = array_values(array_unique(\Arr::flatten($codeArray)));

        if (count($codeArray) != 0) {
            $mergeCodeArray = [];
            if ($limit != false && count($searchedItems) == 0) {

                $options = ProductOption::join('options', 'options.option_id', 'product_options.poption_option_id')->groupBy('option_id')->pluck('option_id');
                $optionRecords = [];
                foreach ($options as $option) {
                    $optionRecords = ProductOption::optionsByIds($prodId, $codeArray, true, $option, $optionRecords);
                }
                return $optionRecords;
            } elseif ($limit != false && count($searchedItems) > 0) {
                return ProductOption::optionsByIds($prodId, array_unique($mergeCodeArray), true, $type, $searchedItems);
            } else {
                return ProductOption::optionsByIds($prodId, array_unique($mergeCodeArray), false, $type);
            }
        }
        return [];
    }
    public static function getRelatedVarients($prodId, $varientCode)
    {
        return ProductOptionVarient::select('pov_code')
            ->where(['pov_prod_id' => $prodId, 'pov_publish' => 1])
            ->where(function ($query) use ($varientCode) {
                $variants = explode('|', $varientCode);
                unset($variants[0]);
                foreach ($variants as $variant) {
                    $query->oRwhere('pov_code', 'LIKE', '%' . $variant . '%');
                }
            })->get();
    }
}
