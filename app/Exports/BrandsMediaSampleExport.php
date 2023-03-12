<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Brand;

class BrandsMediaSampleExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $fields = ['brand_id', 'brand_name','afile_physical_path'];
        return Brand::getRecords($fields, [], 'asc');
    }

    public function headings(): array
    {
        return [
            'Brand Id',
            'Brand Name',
            'Image Url'
        ];
    }
}
