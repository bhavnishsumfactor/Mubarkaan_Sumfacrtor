<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\UrlRewrite;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Auth;

class BrandsImport implements ToCollection, WithHeadingRow
{
    use Importable;
    private $brandError = [];

    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows) {
        
        foreach ($rows->toArray() as $key => $row) {
            $validator = $this->brandLevelValidation($row);
            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $message) {
                    $this->displayError($message, trim($row['sno']));                    
                }
            } else {
                $brandData = [
                    'brand_name' => trim($row['brand_name']),
                    'brand_updated_by_id' => Auth::guard('admin')->user()->admin_id,
                    'brand_updated_at' => Carbon::now(),
                    'brand_publish' => (strtolower(trim($row['publish'])) == 'yes') ? 1 : 0
                ];
                $brand = Brand::where('brand_id', trim($row['sno']))->first();
                if (isset($brand->brand_id) && !empty($brand->brand_id)) {
                    Brand::where(['brand_id' => $brand->brand_id])->update($brandData);
                } else {
                    $brandId = Brand::Create($brandData)->brand_id;
                    UrlRewrite::saveUrl('brands', $brandId);
                }
            }
        }
    }

    public function getErrors()
    {
        return $this->brandError;
    }

    private function brandLevelValidation($row) {
        $brandValidation= '';
        if(!empty($row['sno'])) {
            $brandValidation= ','. $row['sno'] . ',brand_id';
        }
        $validator = Validator::make($row, [
            'sno' => 'required',
            'brand_name' => 'required|unique:brands,brand_name'.$brandValidation
        ]);
        
        $validator->setAttributeNames([
            'sno' => 'serial number',
            'brand_name' => 'Brand Name'
        ]);
        return $validator;
    }

    private function displayError($message,$key){
        $messages = [];
        array_push($messages, $key);
        array_push($messages, $message);
        array_push($this->brandError, $messages);
    }
}