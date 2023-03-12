<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Brand;
use DB;

class BrandsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Brand::select('brand_id', 'brand_name',
                DB::raw("(CASE WHEN (brand_publish = 1) THEN 'YES' ELSE 'No' END)"))->orderBy('brand_id', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'S.No.',
            'Brand Name',
            'Publish '
        ];
    }
}
