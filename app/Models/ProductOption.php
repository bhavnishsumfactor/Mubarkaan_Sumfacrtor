<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\Option;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductOption extends YokartModel
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'poption_id';
    protected $fillable = [
        'poption_prod_id', 'poption_option_id', 'poption_opn_id'
    ];
    public function options()
    {
        return $this->hasOne('App\Models\Option', 'option_id', 'poption_option_id');
    }
    
    public static function optionsByIds($productId, $ids, $limit = '', $type = '', $selectedFilters = [])
    {

        $query = ProductOption::join('options', 'options.option_id', 'product_options.poption_option_id')
            ->join('product_option_names as pname', 'opn_id', 'poption_opn_id');
        $query->whereIN('product_options.poption_opn_id', $ids)->whereIN("poption_prod_id", $productId);


        if (count($selectedFilters) > 0) {
            $selectedIds = implode(',', $selectedFilters);
            $query->whereIN('opn_id', $selectedFilters)->orderByRaw("IF(FIELD(opn_id, $selectedIds)=0,1,0),FIELD(opn_id, $selectedIds)");
        }
        $total = $query->count();
        if ($limit != "" || $type != "") {
            $query->where('poption_option_id', $type);
            if ($limit != "") {
                $query->limit(6);
            }
        }
        $query->groupBy('pname.opn_value', 'poption_option_id');
        $query->orderBy('opn_value', 'ASC');
        $records = $query->get();

        return self::oprionsHierarchy($records, $total);
    }
    public static function oprionsHierarchy($records, $total)
    {

        $optionsArray = array();
        foreach ($records as $record) {
            $optionName = $record->option_name;
            if ($record->option_type == 1) {
                $optionName = "Color";
            } elseif ($record->option_type == 2) {
                $optionName = "Size";
            }
            $optionsArray[$optionName]['option_name'] = $record->opn_value;
            $optionsArray[$optionName]['option_type'] = $record->option_type;
            $optionsArray[$optionName]['total'] = $total;
            $optionsArray[$optionName]['has_size_cart'] = $record->option_has_sizechart;
            $optionsArray[$optionName]['poption_name'][$record->opn_value] = $record->opn_value;
            $optionsArray[$optionName]['poption_id'][$record->opn_value] = $record->opn_id;
            $optionsArray[$optionName]['option_color'][$record->opn_id] = $record->opn_color_code;
        }

        return $optionsArray;
    }

    public static function optionsByCode($productId, $code)
    {
        $optionIds = array_filter(explode('|', $code));
        unset($optionIds[0]);
        return  ProductOption::join('options', 'options.option_id', 'product_options.poption_option_id')
            ->join('product_option_names as pname', 'opn_id', 'poption_opn_id')
            ->where('product_options.poption_prod_id', $productId)
            ->whereIN('product_options.poption_opn_id', $optionIds)->pluck('pname.opn_value', 'pname.opn_value')->toArray();
    }

    public function getOptionNames()
    {
        return $this->hasOne('App\Models\ProductOptionName', 'opn_id', 'poption_opn_id');
    }
    public static function optionSizeExist($productId)
    {
        return ProductOption::join('options', 'options.option_id', 'product_options.poption_option_id')
        ->where("poption_prod_id", $productId)->exists();
    }

    public static function getRecordsByIds($ids)
    {
        return ProductOption::join('product_option_names as pname', 'opn_id', 'poption_opn_id')
            ->whereIn('poption_id', $ids)->pluck('opn_value', 'poption_id');
    }
    public static function generateVarientId($varientIds, $productId)
    {
        $options = ProductOption::where('poption_prod_id', $productId)->whereIN('poption_opn_id', $varientIds)->orderBy('poption_id', 'ASC')->get()->pluck('poption_opn_id')->toArray();
       
        return  $productId . '|' . implode('|', array_unique($options)) . '|';
    }    
}
