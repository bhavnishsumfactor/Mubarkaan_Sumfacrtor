<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\AttachedFile;
use DB;
class ProductsMediaSampleExport implements FromArray, WithHeadings,WithMapping
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Product Id',
            'Product Name',
            'Option Id',
            'Option Title',
            'Image Url'
        ];
    }
    public function map($product): array
    {
        $attacheFileUrl = '';
        $attachedFile = AttachedFile::select(DB::raw('group_concat(afile_physical_path separator ", ") AS image_url'))->where('afile_type', AttachedFile::getConstantVal('productImages'))
            ->where('afile_record_id', $product['prod_id'])->where('afile_record_subid', $product['poption_id'])->groupBy('afile_record_id', 'afile_record_subid')->first();
        if(isset($attachedFile->image_url) && !empty($attachedFile->image_url)) {
            $attacheFileUrl = $attachedFile->image_url;
        }
        return [
            $product['prod_id'],
            $product['prod_name'],
            $product['poption_id'],
            $product['poption_name'],
            $attacheFileUrl
        ];
    }
}
