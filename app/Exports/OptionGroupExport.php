<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Option;
use DB;

class OptionGroupExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Option::select('option_id', 'option_name', 
            DB::raw("(CASE WHEN (option_type = 1) THEN 'YES' ELSE 'No' END)"),
            DB::raw("(CASE WHEN (option_has_image = 1) THEN 'YES' ELSE 'No' END)"),
            DB::raw("(CASE WHEN (option_has_sizechart = 1) THEN 'YES' ELSE 'No' END)")
        )->get();
    }

    public function headings(): array
    {
        return [
            'S.No.',
            'Option Name',
            'Option is color',
            'Option has image',
            'Option has sizechart'
        ];
    }
}
