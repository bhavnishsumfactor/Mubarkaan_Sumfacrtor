<?php

namespace App\Imports;

use App\Models\ProductCategory;
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

class CategoryImport implements ToCollection, WithHeadingRow
{
    use Importable;
    private $categoryError = [];

    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows) {
        foreach ($rows->toArray() as $key => $row) {
            $validator = $this->categoryLevelValidation($row);
            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $message) {
                    $this->displayError($message, ($key+2));
                }
            } else {
                $productCategory = ProductCategory::where('cat_id', trim($row['category_id']));
                $categoryData = [
                    'cat_parent' => (isset($row['parent_id']) && !empty($row['parent_id'])) ? $row['parent_id'] : 0,
                    'cat_name' => trim($row['category_name']),
                    'cat_publish' => (strtolower(trim($row['publish'])) == 'yes') ? 1 : 0,
                    'cat_tax_code' => (isset($row['category_tax_code']) && !empty($row['category_tax_code'])) ? $row['category_tax_code'] : '',
                    'cat_updated_at' => Carbon::now(),
                    'cat_updated_by_id' => Auth::guard('admin')->user()->admin_id
                ];
                if ($productCategory->count() > 0) {
                    $productCategory->update($categoryData);
                } else {
                    $categoryId = ProductCategory::create($categoryData)->cat_id;
                    UrlRewrite::saveUrl('categories', $categoryId);
                }
            }
        }
    }

    public function getErrors()
    {
        return $this->categoryError;
    }

    private function categoryLevelValidation($row) {        
        $validator = Validator::make($row, [
            'category_name' => 'required'
        ]);
        
        $validator->setAttributeNames([
            'category_name' => 'Category Name'
        ]);
        return $validator;
    }

    private function displayError($message,$key){
        $messages = [];
        array_push($messages, $key);
        array_push($messages, $message);
        array_push($this->categoryError, $messages);
    }
}