<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductContent;
use App\Models\Brand;
use App\Models\TaxGroup;
use App\Models\Option;
use App\Models\ProductOption;
use App\Models\ProductOptionVarient;
use App\Models\UrlRewrite;
use App\Models\ProductToCategory;
use App\Models\OptionToVarient;
use App\Models\Configuration;
use App\Models\ProductOptionName;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Validation\Rule;
use DB;
use Carbon\Carbon;
use Auth;

class DigitalProductImport implements ToCollection,WithHeadingRow
{
    use Importable;
    private $productError = [];

    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        $tempProductId = 0;
        $productId = 0;
        $required = '';
        $brandMessage = '';
        $configuration = Configuration::getKeyValues(['BRAND_IS_OPTIONAL', 'DECIMAL_VALUES']);
        //$brandOptional = Configuration::getValue('BRAND_IS_OPTIONAL');
        if($configuration['BRAND_IS_OPTIONAL'] != 1) {
            $required = 'required';
            $brandMessage = 'Brand name';
        }
        if($configuration['DECIMAL_VALUES'] ==  1) {
            $decimalValue = '|max:22|regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/';
        } else {
            $decimalValue = '|numeric';
        }
        $tempRows = '';
        foreach ($rows->toArray() as $key => $row) {
            $totalRows = array_slice($row, 25);
            if($tempProductId != $row['product_id']) {
                $tempProductId = $row['product_id'];
                $validator = $this->productValidation($row, $required, $brandMessage,$decimalValue);
                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $message) {
                        $this->displayError($message, ($key+2));
                    }
                } else {
                    $brand = [];
                    if (!empty(trim($row['brand']))) {
                        $brand = Brand::whereRaw('LOWER(`brand_name`) = ?',[trim(strtolower($row['brand']))])->first();
                    }
                    if ($configuration['BRAND_IS_OPTIONAL'] == 0) {
                        if (!isset($brand->brand_id) && empty($brand->brand_id)) {
                            $this->displayError("brand name not found", ($key+2));
                            continue;
                        }
                    }
                    $taxGroup = TaxGroup::whereRaw('LOWER(`taxgrp_name`) = ? ',[trim(strtolower($row['tax_category']))])->first();
                    if (!isset($taxGroup->taxgrp_id) && empty($taxGroup->taxgrp_id)) {
                        $this->displayError("tax group not found", ($key+2));
                        continue;
                    }
                    DB::beginTransaction();
                    try{
                        $productData = [
                            'prod_name'=> trim($row['product_title']),
                            'prod_type' =>  Product::DIGITAL_PRODUCT,
                            'prod_brand_id' => isset($brand->brand_id) ? $brand->brand_id : 0,
                            'prod_taxcat_id' => $taxGroup->taxgrp_id,
                            'prod_cost' => isset($row['cost_price']) ? trim($row['cost_price']) : 0,
                            'prod_price' => isset($row['selling_price']) ? trim($row['selling_price']) : 0,
                            'prod_stock' => isset($row['available_quantity']) ? trim($row['available_quantity']) : 0,
                            'prod_description' => trim($row['description']),
                            'prod_model' => isset($row['model_no']) ? trim($row['model_no']) : '',
                            'prod_published' => ( isset($row['publish']) && strtolower($row['publish']) == "yes")  ?  Product::PRODUCT_PUBLISHED : Product::PRODUCT_UNPUBLISHED,
                            'prod_publish_from' => isset($row['product_published_from']) ? $row['product_published_from'] : '',
                            'prod_min_order_qty' => isset($row['min_purchase_qty']) ? $row['min_purchase_qty'] : '',
                            'prod_max_order_qty' => isset($row['max_purchase_qty']) ? $row['max_purchase_qty'] : '',
                            'prod_updated_on' => Carbon::now(),
                            'prod_updated_by_id' => Auth::guard('admin')->user()->admin_id
                        ];
                        $product = Product::where('prod_id', trim($row['product_id']))->where('prod_type', Product::DIGITAL_PRODUCT);
                        if ($product->count() > 0) {
                            $product->update($productData);
                            $productId = $row['product_id'];
                        } else {
                            $productId= Product::create($productData)->prod_id;
                        }
                            $productContentData = [
                                'pc_prod_id' => $productId,
                                'pc_sku' => isset($row['sku']) ? trim($row['sku']) : "",
                                'pc_isbn' => isset($row['isbn']) ? trim($row['isbn']) : "",
                                'pc_hsn' => isset($row['hsn']) ? trim($row['hsn']) : "",
                                'pc_sac' => isset($row['sac']) ? trim($row['sac']) : "",
                                'pc_upc' => isset($row['upc']) ? trim($row['upc']) : "",
                                'pc_video_link'=>  isset($row['video_url']) ? trim($row['video_url']) : "",
                                'pc_max_download_times'=> isset($row['max_download']) ? trim($row['max_download']) : 0,
                                'pc_download_validity_in_days'=> isset($row['validitydays']) ? trim($row['validitydays']) : 0
                            ]; 
                            $productContent = ProductContent::where('pc_prod_id', $productId);
                            if ($productContent->count() > 0) {
                                $productContent->update($productContentData);
                            } else {                                    
                                ProductContent::create($productContentData);
                            }
                            $productCategory = ProductToCategory::where('ptc_prod_id', $productId);
                            if ($productCategory->count() > 0) {
                                ProductToCategory::where('ptc_prod_id', $productId)->update(['ptc_cat_id' => $row['category_id']]);
                            } else {
                                ProductToCategory::Create([
                                    'ptc_prod_id' => $productId,
                                    'ptc_cat_id' => $row['category_id']
                                ]);
                            }
                               
                            if (strtolower(trim($row['inventory_level'])) == "variant") {
                                if ($totalRows >= 1 ) {
                                    $productDefaultVariant = ProductOptionVarient::where('pov_prod_id', $productId)->where('pov_default', config('constants.YES'))->get()->count();
                                    $variantDefault = ($productDefaultVariant == 0) ? 1 : 0;
                                    $tempRows = $totalRows;
                                    $variantErrorMessage = $this->saveVariantInformation($totalRows, $productId, $row, $variantDefault, true,$tempRows);
                                }
                            }
                            UrlRewrite::saveUrl('products', $productId);
                        DB::commit();
                        
                    } catch(\Exception $e) {
                        $this->displayError($e->getMessage(), ($key+1));
                        DB::rollBack();
                        continue;
                    }
                }
            } else if(Product::where('prod_id', $productId)->count() > 0) {
                DB::beginTransaction();
                try{
                    if($totalRows >= 1 ) {
                        $validator = $this->variantValidation($row, $required, $brandMessage, $decimalValue);
                        if ($validator->fails()) {
                            foreach ($validator->errors()->all() as $message) {
                                $this->displayError($message, ($key+2));
                            }
                        } else {
                            $variantErrorMessage = $this->saveVariantInformation($totalRows, $productId, $row,0, false,$tempRows);
                            if(isset($variantErrorMessage['message']) && !empty($variantErrorMessage['message'])) {
                                $this->displayError($variantErrorMessage['message'],($key+2));
                            }
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

    public function getErrors()
    {
        return $this->productError;
    }

    private function displayError($message,$key){
        $messages = [];
        array_push($messages,$key);
        array_push($messages,$message);
        array_push($this->productError,$messages);
    }

    private function productValidation($row,$required,$brandMessage,$decimalValue) {

        $validator = Validator::make($row, [
            'product_id' => '',
            'product_title' => 'required',
            'brand' => $required,
            'category_id' => 'required',
            'category' => 'required',
            'description' => 'required',
            'model_no' => '',
            'tax_category' => 'required',
            'track_inventory' => '',
            'min_purchase_qty' => 'required|numeric',
            'max_purchase_qty' => '',
            'cost_price' => 'required'.$decimalValue,
            'selling_price' => 'required'.$decimalValue,
            'available_quantity' => 'required|numeric',
            'sku' => 'required',
            'isbn' => '',
            'hsn' => '',
            'sac' => '',
            'upc' => '',
            'video_url' => '',
            'publish' => ['required', Rule::in(['yes','no','Yes','No']) ],
            'product_published_from' => '',
            'max_download' => 'required|numeric',
            'validitydays' => 'required|numeric'
        ]);

        $validator->setAttributeNames([
            'product_id' => '',
            'product_title'=> 'Product name',
            'brand'=> $brandMessage,
            'category_id'=> 'Category id',
            'category'=> 'Category name',
            'description'=> 'Description',
            'model_no'=> '',
            'tax_category'=> 'tax category',
            'track_inventory'=> '',
            'min_purchase_qty'=> 'Min Purchase Qty',
            'max_purchase_qty'=> '',
            'cost_price'=> '',
            'selling_price'=> 'Selling Price',
            'available_quantity'=> 'Available Quantity',
            'sku'=> 'sku',
            'isbn' => '',
            'hsn' => '',
            'sac' => '',
            'upc' => '',
            'video_url' => '',
            'publish'=> 'publish',
            'product_published_from'=> '',
            'max_download'=> 'Max Download',
            'validitydays'=> 'validity'
        ]);
        return $validator;
    }

    private function variantValidation($row, $decimalValue) {
        $validator = Validator::make($row, [
            'product_id' => '',
            'product_title' => '',
            'brand' => '',
            'category_id' => '',
            'category' => '',
            'description' => '',
            'model_no' => '',
            'tax_category' => '',
            'track_inventory' => '',
            'min_purchase_qty' => '',
            'max_purchase_qty' => '',
            'cost_price' => '',
            'selling_price' => 'required'.$decimalValue,
            'available_quantity' => 'required|numeric',
            'sku' => 'required',
            'isbn' => '',
            'hsn' => '',
            'sac' => '',
            'upc' => '',
            'video_url' => '',
            'publish' => ['required', Rule::in(['yes','no','Yes','No','YES','NO']) ],
            'product_published_from' => '',
            'max_download' => '',
            'validitydays' => ''
        ]);
        
        $validator->setAttributeNames([
            'product_id' => '',
            'product_title'=> '',
            'brand'=> '',
            'category_id'=> '',
            'category'=> '',
            'description'=> '',
            'model_no'=> '',
            'tax_category'=> '',
            'track_inventory'=> '',
            'min_purchase_qty'=> '',
            'max_purchase_qty'=> '',
            'cost_price'=> '',
            'selling_price'=> 'Selling Price',
            'available_quantity'=> 'Available Quantity',
            'sku'=> 'sku',
            'isbn' => '',
            'hsn' => '',
            'sac' => '',
            'upc' => '',
            'video_url' => '',
            'publish'=> 'publish',
            'product_published_from'=> '',
            'max_download'=> '',
            'validitydays'=> ''
        ]);
        return $validator;
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