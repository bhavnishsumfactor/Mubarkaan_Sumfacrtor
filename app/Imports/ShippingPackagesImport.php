<?php

namespace App\Imports;

use App\Models\ShippingBoxPackage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;	
use Maatwebsite\Excel\Concerns\SkipsFailures;

class ShippingPackagesImport implements ToModel, WithStartRow, WithValidation, SkipsOnFailure
{
    use Importable;
    private $shippingError = [];
    private $data;    

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        if (!isset($row[1]) && !empty($row[1])) {
            return null;
        }
        return ShippingBoxPackage::updateOrCreate(
            ['sbpkg_id' => $row[0]],
            [
                'sbpkg_name' => trim($row[1]),
                'sbpkg_length' => trim($row[2]),
                'sbpkg_width' => trim($row[3]),
                'sbpkg_heigth' => trim($row[4])
            ]);
    }

    public function rules(): array
    {
        return [
            '1' => 'required'
        ];
    }

    public function customValidationMessages()
    {
        return [
            '1.required' => 'shipping package name required',
        ];        
    }

    public function onFailure(Failure ...$failures)
    {
        foreach($failures as $failure)
        {
            $message = [];
            array_push($message,$failure->row());
            array_push($message,$failure->errors()[0]);
            array_push($this->shippingError,$message);
        }     
    }

    public function getErrors()
    {
        return $this->shippingError;
    }
}