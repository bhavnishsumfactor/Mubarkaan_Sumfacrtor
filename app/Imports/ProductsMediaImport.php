<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Product;
use App\Models\AttachedFile;
use App\Models\ProductOption;
use App\Helpers\FileHelper;
use Illuminate\Support\Collection;

class ProductsMediaImport implements ToCollection, WithStartRow
{
    use Importable;

    private $errors = [];
    
    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        $storagePath  = FileHelper::getStoragePathPrefix();
        $dateWisePath = date('Y') . '/' . date('m') . '/';

        if (!empty($rows) && count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $productId = $row[0];
                $optionId = !empty($row[2]) ? $row[2] : 0;
                $images = $row[4];
                if (empty($productId)) {
                    $this->displayError("Product id field is required", $key + 2);
                }
                if (empty($images)) {
                    $this->displayError("Product image field is required", $key + 2);
                }
                if (!empty($productId) && !empty($images)) {
                    $productCount = Product::where('prod_id', $productId)->count();
                    $optionCount = ProductOption::where('poption_id', $optionId)
                        ->where('poption_prod_id', $productId)->count();
                    $imagesArr = explode(',', $images);
                    if ($productCount == 0) {
                        $this->displayError("Product id is invalid", $key + 2);
                    }
                    if ($productCount == 1 && (empty($optionId) || $optionCount == 1) && count($imagesArr) > 0) {
                        foreach ($imagesArr as $imagePath) {
                            $imagePath = trim($imagePath);
                            if (strpos($imagePath, 'zip/') !== false) {
                                if (!FileHelper::fileExists($imagePath)) {
                                    $this->displayError("Specified image " . $imagePath . " does not exist", $key + 2);
                                    continue;
                                }
                                $file = FileHelper::getFile($imagePath);
                            
                                $imagePathArr = explode('/', $imagePath);
                                $imageName = end($imagePathArr);
                                $brandingName = FileHelper::BRANDING[array_rand(FileHelper::BRANDING)];
                            
                                $saveName = time() . '-' . $brandingName . '-' . preg_replace('/[^a-zA-Z0-9]/', '', $imageName);
                            
                                $physicalPath = FileHelper::directUpload($file, $saveName, $dateWisePath);
    
                                AttachedFile::saveFile(AttachedFile::getConstantVal('productImages'), $productId, $physicalPath, $imageName, $optionId);
                            }
                            //FileHelper::deleteFile($imagePath);
                        }
                    }
                }
            }
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function displayError($message, $key)
    {
        $messages = [];
        array_push($messages, $key);
        array_push($messages, $message);
        array_push($this->errors, $messages);
    }
}
