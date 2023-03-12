<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\Country;
use App\Models\ProductContent;
use App\Models\Configuration;
use DB;

class ProductExport implements FromCollection, WithHeadings
{
    use Exportable;
    private $configuration = [];
    private $products;
    private $heading = Product::PHYSICAL_PRODUCT_HEADING;

    public function collection()
    {
        return $this->products;
    }

    public function headings(): array
    {
        $this->configuration = Configuration::getKeyValues(['COD_ENABLE', 'PRODUCT_GIFT_WRAP_ENABLE']);
        if(empty($this->configuration['COD_ENABLE']) && $this->configuration['COD_ENABLE'] ==0) {
            unset($this->heading[15]);
        }
        if(empty($this->configuration['PRODUCT_GIFT_WRAP_ENABLE']) && $this->configuration['PRODUCT_GIFT_WRAP_ENABLE'] == 0) {
            unset($this->heading[16]);
        }
        $this->products = $this->getCollection();
        return $this->heading;
    }

    private function getCollection() {
        $weightUnit = ProductContent::getWeightOptions();
        $fields = 'prod_id, prod_name, brand_name, cat_id, cat_name, prod_description, prod_model, taxgrp_name,prod_condition,pc_warranty_age,pc_return_age,
        pc_threshold_stock_level,pc_substract_stock,prod_self_pickup,prod_stock_out_sellable,prod_cod_available,pc_gift_wrap_available,
        prod_min_order_qty,prod_max_order_qty,prod_cost,'. Product::getPrice().',COALESCE(`pov_sku`,`pc_sku`) as sku, prod_from_origin_country_id,pc_weight,pc_weight_unit,
        pc_isbn,pc_hsn,pc_sac,pc_upc,pc_video_link,prod_published, prod_publish_from,pov_code';
        $query =  Product::getListingObj([], $fields, false, [], false);
        $query->addCondition(Product::PHYSICAL_PRODUCT, 'prod_type');
        $query->orderBy('prod_id', 'desc');
        $products =  $query->getRecords();
        if(isset($products) && !empty($products)) {
            $tempProductId = 0;
            foreach($products as $key => $product) {
                if($tempProductId != $product->prod_id){
                    $tempProductId = $product->prod_id;
                    $counter = 0;
                }
                $product->prod_condition = Product::PRODUCT_CONDITIONS[$product->prod_condition];
                $product->prod_published = ($product->prod_published == 1) ? 'Yes' : 'No';
                if(empty($this->configuration['COD_ENABLE']) && $this->configuration['COD_ENABLE'] ==0) {
                    unset($product->prod_cod_available);
                } else {
                    $product->prod_cod_available = ($product->prod_cod_available == 1) ? 'Yes' : 'No';
                }
                if(empty($this->configuration['PRODUCT_GIFT_WRAP_ENABLE']) && $this->configuration['PRODUCT_GIFT_WRAP_ENABLE'] ==0 ) {
                    unset($product->pc_gift_wrap_available);
                } else {
                    $product->pc_gift_wrap_available = ($product->pc_gift_wrap_available == 1) ? 'Yes' : 'No';
                }
                $product->pc_threshold_stock_level = (isset($product->pc_threshold_stock_level) && $product->pc_threshold_stock_level == 1) ? 'Yes' : 'No';
                $product->prod_stock_out_sellable = (isset($product->prod_stock_out_sellable) && $product->prod_stock_out_sellable == 1) ? 'Yes' : 'No';
                $product->pc_isbn = (!empty($product->pc_isbn) && ($product->pc_isbn != 'null' )) ? $product->pc_isbn : '';
                $product->pc_hsn = (!empty($product->pc_hsn) && ($product->pc_hsn != 'null') ) ? $product->pc_hsn : '';
                $product->pc_sac = (!empty($product->pc_sac) && ($product->pc_sac != 'null')) ? $product->pc_sac : '';
                $product->pc_upc = (!empty($product->pc_upc) && ($product->pc_upc != 'null') ) ? $product->pc_upc : '';
                $product->pc_video_link = (!empty($product->pc_video_link) && ($product->pc_video_link != 'null')) ? $product->pc_video_link : '';
                $product->prod_self_pickup = (isset(Product::PRODUCT_SHIPPING_TYPE[$product->prod_self_pickup])) ? strtolower(Product::PRODUCT_SHIPPING_TYPE[$product->prod_self_pickup]) : '';
                if (isset($product->prod_from_origin_country_id) && !empty($product->prod_from_origin_country_id)) {
                    $product->prod_from_origin_country_id = Country::where('country_id', $product->prod_from_origin_country_id)->first()->country_name;
                } else {
                    $product->prod_from_origin_country_id = '';
                }
                if (isset($product->pc_weight_unit)) {
                    $product->pc_weight_unit =  $weightUnit[$product->pc_weight_unit];
                } else {
                    $product->pc_weight_unit = '';
                }
                if($counter >= 1 ) {
                    $product = $this->emptyFields($product);
                }
                if (isset($product->pov_code) && !empty($product->pov_code)) {
                    if ($counter >= 1) {
                        $product->product_type = "";
                    } else {
                        $product->product_type = "Variant";
                    }
                    $totalVariant = array_filter(explode('|', $product->pov_code));
                    array_splice($totalVariant, 0, 1);
                    if (count($totalVariant) > 0) {
                        foreach ($totalVariant as $key1 => $variant) {
                            if (!in_array('Option_Group_' . $key1, $this->heading)) {
                                array_push($this->heading, 'Option_Group_' . $key1);
                                array_push($this->heading, 'Option_value_' . $key1);
                            }
                            $GroupName = ProductOption::with(['options'])->where(['poption_opn_id' => $variant, 'poption_prod_id' => $product->prod_id])->first();
                            //$product['option_id'] = isset($GroupName->poption_id) ? $GroupName->poption_id : '';
                            $product['option_group_name_' . $key1] = isset($GroupName->options->option_name) ? $GroupName->options->option_name : '';
                            $product['option_group_value_' . $key1] = isset($GroupName->getOptionNames->opn_value) ? $GroupName->getOptionNames->opn_value : '';
                        }
                    }
                }
                unset($product->finalprice);
                unset($product->discount);
                unset($product->pov_code);
                $counter++;
            }
        }
        return $products;
    }

    private function emptyFields($prod) {
        $prod->prod_name  = '';
        $prod->brand_name = '';
        $prod->cat_id = '';
        $prod->cat_name = '';
        $prod->prod_description = '';
        $prod->prod_model = '';
        $prod->taxgrp_name = '';
        $prod->prod_condition = '';
        $prod->pc_warranty_age = '';
        $prod->pc_return_age = '';
        if (!empty($this->configuration['COD_ENABLE'])) {
            $prod->prod_cod_available = '';
        }
        if (!empty($this->configuration['PRODUCT_GIFT_WRAP_ENABLE'])) {
            $prod->pc_gift_wrap_available = '';
        }
        $prod->pc_weight = '';
        $prod->pc_isbn = '';
        $prod->pc_hsn = '';
        $prod->pc_sac = '';
        $prod->pc_upc = '';
        $prod->pc_video_link = '';
        $prod->prod_publish_from = '';
        $prod->pc_weight_unit = '';
        $prod->prod_from_origin_country_id = '';
        $prod->prod_self_pickup = '';
        $prod->prod_min_order_qty = '';
        $prod->prod_max_order_qty = '';
        $prod->prod_cost = '';
        $prod->pc_threshold_stock_level = '';
        $prod->prod_stock_out_sellable = '';
        //$prod->sbpkg_name = '';
        return $prod;
    }
}
