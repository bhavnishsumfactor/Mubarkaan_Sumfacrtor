<?php

namespace App\Imports;

use App\Models\Option;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Auth;

class OptionGroupImport implements ToCollection, WithHeadingRow
{
    use Importable;
    private $optionError = [];

    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows) {
        foreach ($rows->toArray() as $key => $row) {
            $validator = $this->optionLevelValidation($row);
            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $message) {
                    $this->displayError($message, $key);                    
                }
            } else {
                $optionData = [
                    'option_name' => (trim($row['option_name'])),
                    'option_type' => (strtolower(trim($row['option_is_color'])) == 'yes') ? 1 : 0,
                    'option_has_image' => (strtolower(trim($row['option_has_image'])) == 'yes') ? 1 : 0,
                    'option_has_sizechart' => (trim(strtolower($row['option_has_sizechart'])) == 'yes') ? 1 : 0,
                    'option_updated_by_id' => Auth::guard('admin')->user()->admin_id,
                    'option_updated_at' => Carbon::now()
                ]; 
                $option = Option::where('option_id', trim($row['sno']))->first();
                if (isset($option->option_id) && !empty($option->option_id)) {                    
                    Option::where('option_id', trim($row['sno']))->update($optionData);                  
                } else {
                    Option::create($optionData);
                }
            }
        }
    }

    public function getErrors()
    {
        return $this->optionError;
    }

    private function optionLevelValidation($row) {
        $optionValidation= '';
        if(!empty($row['sno'])) {
            $optionValidation= ','. $row['sno'] . ',option_id';
        }
        $validator = Validator::make($row, [
            'option_name' => 'required|unique:options,option_name'.$optionValidation
        ]);
        
        $validator->setAttributeNames([
            'option_name' => 'Option Name'
        ]);
        return $validator;
    }

    private function displayError($message,$key){
        $messages = [];
        array_push($messages, $key);
        array_push($messages, $message);
        array_push($this->optionError, $messages);
    }
}