<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductContent;
use App\Models\Brand;
use App\Models\TaxGroup;
use App\Models\Option;
use App\Models\ProductOption;
use App\Models\UrlRewrite;
use App\Models\ProductOptionVarient;
use App\Models\ProductToCategory;
use App\Models\Configuration;
use App\Models\ProductCategory;
use App\Models\ProductOptionName;
use App\Models\OptionToVarient;
use App\Models\ShippingBoxPackage;
use App\Models\Country;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;
use DB;
use Carbon\Carbon;
use Auth;

class ProductImport implements ToCollection, WithHeadingRow
{
    use Importable;
    private $productError = [];

    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows) {
        $weightUnit = ProductContent::getWeightOptions();
        $weightUnitValue = array_flip($weightUnit);
        $tempProductId = 0;
        $productId = 0;
        $deliveryMethod = 0;
        $required = '';
        $brandMessage = '';
        $configuration = Configuration::getKeyValues(['PICK_UP_ENABLE', 'BRAND_IS_OPTIONAL', 'DECIMAL_VALUES']);
        $tempRows = '';
        if($configuration['BRAND_IS_OPTIONAL'] != 1) {
            $required = 'required';
            $brandMessage = 'Brand name';
        }
        if($configuration['DECIMAL_VALUES'] ==  1) {
            $decimalValue = '|max:22|regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/';
        } else {
            $decimalValue = '|numeric';
        }
        
        foreach ($rows->toArray() as $key => $row) {
            $cod = 0;
            $totalVariantRows = array_slice($row, 34);
            if ($tempProductId != $row['product_id']) {
                $tempProductId = $row['product_id'];
                $validator = $this->productLevelValidation($row, $weightUnit, $required, $brandMessage, $decimalValue);
                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $message) {
                        $this->displayError($message,($key+2));
                    }
                } else {
                    $brand = [];
                    if (!empty(trim($row['brand']))) {
                        $brand = Brand::whereRaw('LOWER(`brand_name`) = ?',[trim(strtolower($row['brand']))])->first();
                    }
                    if($configuration['BRAND_IS_OPTIONAL'] == 0){
                        if(!isset($brand->brand_id) && empty($brand->brand_id)){
                            $this->displayError("brand name not found", ($key+2));
                            continue;
                        }
                    }
                    /*$shippingPackage = [];
                    $shippingPackage = ShippingBoxPackage::whereRaw('LOWER(`sbpkg_name`) LIKE ?', [ '%'. trim(strtolower($row[27])) . '%'])->first();
                    if(!isset($shippingPackage->sbpkg_id) && empty($shippingPackage->sbpkg_id)){
                        $this->displayError("shipping package not found", $key);
                        continue;
                    }*/
                    
                    $countryOrigin = [];
                    $countryOrigin = Country::whereRaw('LOWER(`country_name`) = ?', [ trim(strtolower($row['country_of_origin'])) ])->first();
                    if(!isset($countryOrigin->country_id) && empty($countryOrigin->country_id)){
                        $this->displayError("country origin not found",($key+2));
                        continue;
                    }
                    
                    $taxGroup = TaxGroup::whereRaw('LOWER(`taxgrp_name`) = ?', [ trim(strtolower($row['tax_category'])) ])->first();
                    
                    if (!isset($taxGroup->taxgrp_id) && empty($taxGroup->taxgrp_id)) {
                        $this->displayError("tax group not found", ($key+2));
                        continue;
                    }

                    if(0 == ProductCategory::where('cat_id', $row['category_id'])->count()) {
                        $this->displayError("Category Not found", ($key+2));
                        continue; 
                    }
                    
                    DB::beginTransaction();
                    try {
                        $deliveryMethod = '';
                        if(isset($row['product_delivery_method']) && !empty($row['product_delivery_method'])) {
                            if (strtolower(trim($row['product_delivery_method'])) == 'shipping only') {
                                $deliveryMethod = Product::SHIPPING_ONLY;
                            } else if(strtolower(trim($row['product_delivery_method'])) == 'pickup only' && $configuration['PICK_UP_ENABLE'] == 1) {
                                $deliveryMethod = Product::PICKUP_ONLY;
                            } else if(strtolower(trim($row['product_delivery_method'])) == 'both' && $configuration['PICK_UP_ENABLE'] == 1) {
                                $deliveryMethod = Product::BOTH;
                            } else {
                                $this->displayError("Invalid delivery method", ($key+2));
                                continue; 
                            }
                        }
                        if (strtolower($row['cod']) == 'yes') {
                            $cod = 1;
                        }
                        $productData = [
                            'prod_name' => trim($row['product_title']),
                            'prod_type' =>  Product::PHYSICAL_PRODUCT,
                            'prod_brand_id' => isset($brand->brand_id) ? $brand->brand_id : '',
                            'prod_taxcat_id' => isset($taxGroup->taxgrp_id) ? $taxGroup->taxgrp_id : '',
                            'prod_cost' => isset($row['cost_price']) ? trim($row['cost_price']) : '',
                            'prod_price' => isset($row['selling_price']) ? trim($row['selling_price']) : '',
                            'prod_description' => trim($row['description']),
                            'prod_stock' => isset($row['available_quantity']) ? trim($row['available_quantity']) : 0,
                            'prod_model' => trim($row['model_no']),
                            'prod_self_pickup' => $deliveryMethod,
                            'prod_condition' => Product::PRODUCT_CONDITIONS_VALUES[strtolower($row['product_condition'])],
                            'prod_published' => ( isset($row['publish']) && strtolower($row['publish']) == "yes")  ?  Product::PRODUCT_PUBLISHED : Product::PRODUCT_UNPUBLISHED,
                            'prod_cod_available' => $cod,
                            'prod_stock_out_sellable' => ( isset($row['sell_when_out_of_stock']) && strtolower($row['sell_when_out_of_stock']) == "yes")  ?  1 : 0,
                            'prod_from_origin_country_id' => isset($countryOrigin->country_id) ? $countryOrigin->country_id : '',
                            //'prod_sbpkg_id' => isset($shippingPackage->sbpkg_id) ? $shippingPackage->sbpkg_id : '',
                            'prod_sbpkg_id' => 0,
                            'prod_publish_from' => isset($row['product_published_from']) ? $row['product_published_from'] : '',
                            'prod_min_order_qty' => isset($row['min_purchase_qty']) ? $row['min_purchase_qty'] : '',
                            'prod_max_order_qty' => isset($row['max_purchase_qty']) ? $row['max_purchase_qty'] : '',
                            'prod_updated_on' => Carbon::now(),
                            'prod_updated_by_id' => Auth::guard('admin')->user()->admin_id
                        ];
                        $product = Product::where('prod_id', trim($row['product_id']))->where('prod_type', Product::PHYSICAL_PRODUCT);
                        if ($product->count() > 0) {
                            $product->update($productData);
                            $productId = $row['product_id'];
                        } else {
                            $productId= Product::create($productData)->prod_id;
                        }

                        $productContentData = [
                            'pc_prod_id' => $productId,
                            'pc_sku' => $row['sku'],
                            'pc_weight' => $row['weight'],
                            'pc_gift_wrap_available' => ( isset($row['gift_wrap']) && strtolower($row['gift_wrap']) == "yes")  ?  config('constants.YES') : config('constants.NO'),
                            'pc_weight_unit' => isset($weightUnitValue[strtolower(trim($row['weight_unit']))]) ? $weightUnitValue[strtolower(trim($row['weight_unit']))] : '',
                            'pc_threshold_stock_level' => ( isset($row['track_inventory']) && strtolower($row['track_inventory']) == "yes") ? config('constants.YES') : config('constants.NO'),
                            'pc_substract_stock' => ( isset($row['stock_alert_qty']) ) ? $row['stock_alert_qty'] : 0,
                            'pc_warranty_age' => trim($row['warranty_days']),
                            'pc_return_age' => trim($row['return_days']),
                            'pc_isbn' => trim($row['isbn']),
                            'pc_hsn' => trim($row['hsn']),
                            'pc_sac' => trim($row['sac']),
                            'pc_upc' => trim($row['upc']),
                            'pc_video_link' => trim($row['video_url'])
                        ];
                        $productContentFound = ProductContent::where('pc_prod_id', $productId)->count();
                        if ($productContentFound > 0) {
                            ProductContent::where('pc_prod_id', $productId)->update($productContentData);
                        } else {
                            ProductContent::create($productContentData);
                        }
                        
                        if (ProductToCategory::where('ptc_prod_id', $productId)->count() > 0) {
                            ProductToCategory::where('ptc_prod_id', $productId)->update(['ptc_cat_id' => $row['category_id']]);
                        } else {
                            ProductToCategory::Create([
                                'ptc_prod_id' => $productId,
                                'ptc_cat_id' => $row['category_id']
                            ]);
                        }
                        if (strtolower(trim($row['inventory_level'])) == "variant") {
                            if ($totalVariantRows >= 1 ) {
                                $productDefaultVariant = ProductOptionVarient::where('pov_prod_id', $productId)->where('pov_default', config('constants.YES'))->get()->count();
                                $variantDefault = ($productDefaultVariant == 0) ? 1 : 0;
                                $tempRows = $totalVariantRows;
                                $this->saveVariantInformation($totalVariantRows, $productId, $row, $variantDefault,true, $tempRows);
                            }
                        }
                        UrlRewrite::saveUrl('products', $productId);
                        DB::commit();
                    }catch(\Exception $e) {
                        $this->displayError($e->getMessage(), ($key+2));
                        DB::rollBack();
                    }
                }
            } elseif(Product::where('prod_id', $productId)->count() > 0) {
                $validator = $this->variantLevelValidation($row, $decimalValue);
                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $message) {
                        $this->displayError($message, ($key+2));
                    }
                } else {
                    DB::beginTransaction();
                    try{
                        if($totalVariantRows >= 1 ) {
                            //$productDefaultVariant = ProductOptionVarient::where('pov_prod_id', $productId)->where('pov_default', config('constants.YES'))->get()->count();
                            //$variantDefault = ($productDefaultVariant == 0) ? 1 : 0;
                            $variantErrorMessage = $this->saveVariantInformation($totalVariantRows, $productId, $row, 0,false, $tempRows);
                            if(isset($variantErrorMessage['message']) && !empty($variantErrorMessage['message'])) {
                                $this->displayError($variantErrorMessage['message'],($key+2));
                            }
                        }
                        DB::commit();
                    } catch(\Exception $e) {
                        $this->displayError($e->getMessage(), ($key+2));
                        DB::rollBack();
                    }
                }
            }
        }
    }

    public function getErrors()
    {
        return $this->productError;
    }

    private function productLevelValidation($row, $weightUnit, $required, $brandMessage, $decimalValue) {

        $validator = Validator::make($row, [
            'product_id' => 'required',
            'product_title' => 'required',
            'brand' => $required,
            'category_id' => 'required',
            'category' => 'required',
            'description' => 'required',
            'model_no' => '',
            'tax_category' => 'required',
            'product_condition' => ['required', Rule::in(['New','Old','Refurbished','new','old','refurbished']) ],
            'warranty_days' => 'required|numeric',
            'return_days' => 'required|numeric',
            'track_inventory' => ['required', Rule::in(['yes','no','Yes','No']) ],
            'stock_alert_qty' => '',
            'product_delivery_method' => ['required', Rule::in(['Shipping only','Pickup Only','Both','shipping only','pickup only','both']) ],
            'sell_when_out_of_stock' => ['required', Rule::in(['yes','no','Yes','No']) ],
            "cod" => ['required', Rule::in(['yes','no','Yes','No']) ],
            "gift_wrap" => ['required', Rule::in(['yes','no','Yes','No']) ],
            "min_purchase_qty" => 'required|numeric',
            "max_purchase_qty" => 'required|numeric',
            "cost_price" => 'required'.$decimalValue,
            "selling_price" => 'required'.$decimalValue,
            "available_quantity" => 'required|numeric',
            "sku" => "required",
            "country_of_origin" => '',
            "weight" => 'required|numeric',
            "weight_unit" => ['required', Rule::in($weightUnit)],
            "isbn" => '',
            "hsn" =>  '',
            "sac" =>  '',
            "upc" =>  '',
            "video_url" =>  '',
            'publish' => ['required', Rule::in(['yes','no','Yes','No']) ],
            'product_published_from' => ''
        ]);
        
        $validator->setAttributeNames([
            'product_id' => 'Product Id',
            'product_title' => 'Product name',
            'brand'=> $brandMessage,
            'category_id'=> 'Category id',
            'category'=> 'Category name',
            'description'=> 'Description',
            'model_no'=> '',
            'tax_category'=> 'tax group',
            'product_condition'=> 'product condition',
            'warranty_days'=> 'Warranty',
            'return_days'=> 'Return',
            'track_inventory' => 'Track Inventory',
            'stock_alert_qty'=> '',
            'product_delivery_method'=> 'Product Delivery Method',
            'sell_when_out_of_stock'=> 'Sell when out of stock',
            'cod'=> 'COD',
            'gift_wrap'=> 'Gift Wrap',
            'min_purchase_qty'=> 'Min Purchase Qty',
            'max_purchase_qty' => 'Max Purchase Qty',
            "cost_price" => 'Cost Price',
            "selling_price" => 'Selling Price',
            "available_quantity" => "Available Quantity",
            "sku" => "SKU",
            "country_of_origin" => '',
            "weight" => 'weight',
            "weight_unit" => 'Weight Unit',
            "isbn" => '',
            "hsn" =>  '',
            "sac" =>  '',
            "upc" =>  '',
            "video_url" =>  '',
            'publish' => 'Publish',
            'product_published_from' => ''
        ]);
        return $validator;
    }

    private function variantLevelValidation($row, $decimalValue) {
        $validator = Validator::make($row, [
            'product_id' => 'required',
            'product_title' => '',
            'brand' => '',
            'category_id' => '',
            'category' => '',
            'description' => '',
            'model_no' => '',
            'tax_category' => '',
            'product_condition' => '',
            'warranty_days' => '',
            'return_days' => '',
            'track_inventory' => '',
            'stock_alert_qty' => '',
            'product_delivery_method' => '',
            'sell_when_out_of_stock' => '',
            "cod" => '',
            "gift_wrap" => '',
            "min_purchase_qty" => '',
            "max_purchase_qty" => '',
            "cost_price" => '',
            "selling_price" => 'required'.$decimalValue,
            "available_quantity" => 'required',
            "sku" => "required",
            "country_of_origin" => '',
            "weight" => '',
            "weight_unit" => '',
            "isbn" => '',
            "hsn" =>  '',
            "sac" =>  '',
            "upc" =>  '',
            "video_url" =>  '',
            'publish' => ['required', Rule::in(['yes','no','Yes','No']) ],
            'product_published_from' => ''
        ]);
        
        $validator->setAttributeNames([
            'product_id' => 'Product Id',
            'product_title' => '',
            'brand'=> '',
            'category_id'=> '',
            'category'=> '',
            'description'=> '',
            'model_no'=> '',
            'tax_category'=> '',
            'product_condition'=> '',
            'warranty_days'=> '',
            'return_days'=> '',
            'track_inventory' => '',
            'stock_alert_qty'=> '',
            'product_delivery_method'=> '',
            'sell_when_out_of_stock'=> '',
            'cod'=> '',
            'gift_wrap'=> '',
            'min_purchase_qty'=> '',
            'max_purchase_qty' => '',
            "cost_price" => '',
            "selling_price" => 'Selling Price',
            "available_quantity" => "Available Quantity",
            "sku" => "",
            "country_of_origin" => '',
            "weight" => '',
            "weight_unit" => '',
            "isbn" => '',
            "hsn" =>  '',
            "sac" =>  '',
            "upc" =>  '',
            "video_url" =>  '',
            'publish' => 'Publish',
            'product_published_from' => ''
        ]);
        
        return $validator;
    }

    private function displayError($message,$key){
        $messages = [];
        array_push($messages,$key);
        array_push($messages,$message);
        array_push($this->productError,$messages);
    }

    private function saveVariantInformation($totalRows, $productId, $allRows, $default = 0, $isFirstRow = false, $tempRows)
    {
        $x = 0;
        $y = 0;
        foreach($totalRows as $optionkey => $optionRow) {
            if(($y % 2) == 0) {
                if(isset($totalRows['option_group_'.$x]) && !empty($totalRows['option_group_'.$x])) {

                    if((!$isFirstRow) && $totalRows['option_group_'.$x] != $tempRows['option_group_'.$x]) {
                        return ['message' => 'Invalid Option Group'];
                    }

                    $options = Option::select('option_id')->whereRaw('LOWER(`option_name`) = ? ',[trim(strtolower($totalRows['option_group_'.$x])) ])->first();
                    if (!empty($options)) {
                        $optionId = $options->option_id;
                    } else {
                        $optionId = Option::create([
                            'option_name' => $totalRows['option_group_'.$x],
                        ])->option_id;
                    }
                    $optionName = ProductOptionName::select('opn_id')->where('opn_value', trim(strtolower($totalRows['option_value_'.$x])))->first();
                    if (!empty($optionName)) {
                        $optionNameId = $optionName->opn_id;
                    } else {
                        $optionNameId = ProductOptionName::create([
                            'opn_value' => trim(strtolower($totalRows['option_value_'.$x])),
                        ])->opn_id;
                    }
                    $productOptionRecords = [
                        'poption_prod_id' => $productId,
                        'poption_option_id' => $optionId,
                        'poption_opn_id' => $optionNameId
                    ];

                    $options = ProductOption::select('poption_id')->where($productOptionRecords)->first();
                    if (!empty($options)) {
                        $optionId = $options->poption_id;
                    }else{
                        $optionId = ProductOption::create($productOptionRecords)->poption_id;
                    }
                    $optionIds[] =  $optionId;
                    $optionNameIds[] =  $optionNameId;
                    $variantDisplayCode[$optionId][] = $optionNameId;
                    $variantDisplayTitle[$optionId][] = trim(strtolower($totalRows['option_value_'.$x]));
                    if ($x < 3 ){
                        $x++;
                    }
                }
            }
            $y++;
        }
        if(isset($variantDisplayCode) && !empty($variantDisplayCode)) {
            $variantPovCode = cartesianArray($variantDisplayCode);
            $variantTitle = cartesianArray($variantDisplayTitle);
            $povCode = $productId . '|' . implode('|', $variantPovCode[0]) . '|';
            $varientRecords = [
                'pov_display_title' => implode('_', $variantTitle[0]),
                'pov_code' => $povCode,
                'pov_price' => $allRows['selling_price'],
                'pov_quantity' => $allRows['available_quantity'],
                'pov_prod_id' => $productId,
                'pov_sku' => $allRows['sku'],
                'pov_publish' => ( isset($allRows['publish']) && strtolower($allRows['publish']) == "yes")  ?  Product::PRODUCT_PUBLISHED : Product::PRODUCT_UNPUBLISHED,
            ];
            if ($default == 1) {
                $varientRecords['pov_default'] = $default;
            }
            $varientExist  = ProductOptionVarient::where('pov_code', $povCode)->first();
            if ( !empty($varientExist) ) {
                $povId = $varientExist->pov_code;
                ProductOptionVarient::where('pov_id', $varientExist->pov_id)->update($varientRecords);
            }else{
                $povId = ProductOptionVarient::create($varientRecords)->pov_id;
            }

            foreach($optionNameIds as $optionValueId) {
                $optionToVariant = OptionToVarient::where('otv_pov_id', $povId)->get()->count();
                if ($optionToVariant == 0) {
                    OptionToVarient::create([
                        'otv_pov_id' => $povId,
                        'otv_opn_id' => $optionValueId
                    ]);
                }
            }
        }
    }
}