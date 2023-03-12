<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Product;
use App\Models\ProductOption;
use App\Helpers\ProductSearchHelper;
use DB;

class DigitalProductExport implements FromCollection, WithHeadings
{
    use Exportable;

    private $products;
    private $heading = [
        'Product Id',
        'Product Title',
        'Brand',
        'Category Id',
        'Category',
        'Description',
        'Model No',
        'Tax Category',
        'Track Inventory',
        'Min Purchase Qty',
        'Max Purchase Qty',
        'Cost Price',
        'Selling Price',
        'Available Quantity',
        'SKU',        
        'ISBN',
        'HSN',
        'SAC',
        'UPC',
        'Video URL',
        'Publish',
        'Product published from',
        'Max Download',
        'validity(days)',
        'Inventory Level',
        //'Option Id'
    ];

    public function collection()
    {
        return $this->products;
    }

    public function getCollection() 
    {
        $fields = 'prod_id, prod_name, brand_name, cat_id, cat_name, prod_description, prod_model, taxgrp_name,pc_threshold_stock_level,prod_min_order_qty,prod_max_order_qty,prod_cost,'. Product::getPrice().',COALESCE(`pov_sku`,`pc_sku`) as sku, pc_isbn,pc_hsn,pc_sac,pc_upc,pc_video_link,prod_published, prod_publish_from,pc_max_download_times,pc_download_validity_in_days,pov_display_title,pov_id, pov_code';
        $query =  Product::getListingObj([], $fields, false, [], false);
        $query->addCondition(Product::DIGITAL_PRODUCT, 'prod_type');
        $products =  $query->getRecords();
        if (isset($products) && !empty($products)) {
            $tempProductId = 0;
            foreach ($products as $key => $product) {
                if ($tempProductId != $product->prod_id) {
                    $tempProductId = $product->prod_id;
                    $counter = 0;
                }
                $product->prod_description = strip_tags($product->prod_description);
                $product->pc_threshold_stock_level = ($product->pc_threshold_stock_level == config('constants.YES')) ? 'Yes' : 'No';
                $product->pc_isbn = (!empty($product->pc_isbn) && ($product->pc_isbn != 'null' )) ? $product->pc_isbn : '';
                $product->pc_hsn = (!empty($product->pc_hsn) && ($product->pc_hsn != 'null') ) ? $product->pc_hsn : '';
                $product->pc_sac = (!empty($product->pc_sac) && ($product->pc_sac != 'null')) ? $product->pc_sac : '';
                $product->pc_upc = (!empty($product->pc_upc) && ($product->pc_upc != 'null') ) ? $product->pc_upc : '';
                $product->pc_video_link = (!empty($product->pc_video_link) && ($product->pc_video_link != 'null')) ? $product->pc_video_link : '';
                $product->prod_published =  ($product->prod_published == config('constants.YES')) ? 'Yes' : 'No';
                
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
                            //$product['option_id'] = isset($product->pov_id) ? $product->pov_id : '';
                            $product['option_group_name_' . $key1] = isset($GroupName->options->option_name) ? $GroupName->options->option_name : '';
                            $product['option_group_value_' . $key1] = isset($GroupName->getOptionNames->opn_value) ? $GroupName->getOptionNames->opn_value : '';
                        }
                    }
                }
                if ($counter >= 1) {
                    $product = $this->emptyFields($product);
                }
                unset($product->pov_id);
                unset($product->finalprice);
                unset($product->discount);
                unset($product->pov_display_title);
                unset($product->pov_code);
                $counter++;
            }
        }
        return $products;
    }

    public function headings(): array
    {
        $this->products = $this->getCollection();
        return $this->heading;
    }

    private function emptyFields($product)
    {
        $product->prod_name  = '';
        $product->brand_name = '';
        $product->cat_id = '';
        $product->cat_name = '';
        $product->prod_description = '';
        $product->prod_model = '';
        $product->taxgrp_name = '';
        $product->prod_cost = '';
        $product->pc_isbn = '';
        $product->pc_hsn = '';
        $product->pc_sac = '';
        $product->pc_upc = '';
        $product->pc_video_link = '';
        $product->prod_publish_from = '';                    
        $product->inventory_management_level = "";
        return $product;
    }
}