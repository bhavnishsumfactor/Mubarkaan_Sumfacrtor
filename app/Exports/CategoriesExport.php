<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\ProductCategory;
use App\Models\Configuration;
use DB;

class CategoriesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $dbprefix = DB::getTablePrefix();
        $selectArray = ['cat_id', 'cat_name', 'cat_parent', DB::raw("(SELECT prod_cat.cat_name FROM ".$dbprefix."product_categories as prod_cat WHERE prod_cat.cat_id =".$dbprefix."product_categories.cat_parent) as Identifier")];
        if(getConfigValueByName('TAX_CODE') == 1) {
            $selectArray[] = 'cat_tax_code';
        }
        $selectArray[] = DB::raw("(CASE WHEN (cat_publish = 1) THEN 'YES' ELSE 'No' END) as active");
       
        return ProductCategory::select(
            $selectArray
        )->get();
    }

    public function headings(): array
    {
        $exportArray = ['Category Id', 'Category Name', 'Parent Id', 'Category Parent'];
        if(getConfigValueByName('TAX_CODE') == 1) {
            $exportArray[] = 'Category Tax Code';
        }
        $exportArray[] ='Category Publish';
        return $exportArray;
    }
}
