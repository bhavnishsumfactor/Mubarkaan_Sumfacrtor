<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\ShippingBoxPackage;

class ShippingPackageExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return ShippingBoxPackage::select('sbpkg_id', 'sbpkg_name','sbpkg_length','sbpkg_width','sbpkg_heigth')->get();
    }

    public function headings(): array
    {
        return [
            'S.No.',
            'Package Name',
            'Length ',
            'Width',
            'Height'
        ];
    }
}
