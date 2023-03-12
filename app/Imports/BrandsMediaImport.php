<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Brand;
use App\Models\AttachedFile;
use App\Helpers\FileHelper;
use Illuminate\Support\Collection;

class BrandsMediaImport implements ToCollection, WithStartRow
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
                $brandId = $row[0];
                $brandImage = $row[2];
                if (empty($brandId)) {
                    $this->displayError("Brand id field is required", $key + 2);
                }
                if (empty($brandImage)) {
                    $this->displayError("Brand image field is required", $key + 2);
                }
                if (!empty($brandId) && !empty($brandImage) && strpos($brandImage, 'zip/') !== false) {
                    $conditions = ['brand_id' => $brandId];
                    $brandCount = Brand::getRecords(['brand_id'], $conditions)->count();
                    if ($brandCount == 1) {
                        $imagePath = str_replace('zip/','',$brandImage);
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
                        
                        AttachedFile::saveOrUpdateFile(AttachedFile::getConstantVal('brandLogo'), $brandId, $physicalPath, $imageName);
                    } else {
                        $this->displayError("Brand id is invalid", $key + 2);
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
