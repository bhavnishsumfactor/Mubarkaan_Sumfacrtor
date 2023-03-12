<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\ProductCategory;

class CategoriesMediaSampleExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $fields = ['cat_id', 'cat_name','afile_physical_path'];
        return ProductCategory::getRecords($fields, [], [], 'asc');
    }

    public function headings(): array
    {
        return [
            'Category Id',
            'Category Name',
            'Image Url'
        ];
    }
}
