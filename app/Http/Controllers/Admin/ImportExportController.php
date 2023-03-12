<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use Zip;
use App\Helpers\FileHelper;
use App\Exports\ErrorExport;
use App\Exports\MediaUrlExport;
use App\Exports\BrandsMediaSampleExport;
use App\Exports\CategoriesMediaSampleExport;
use App\Exports\ProductsMediaSampleExport;
use App\Imports\BrandsMediaImport;
use App\Imports\CategoriesMediaImport;
use App\Imports\ProductsMediaImport;
use App\Models\Product;
use Excel;

class ImportExportController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
    }

    public function uploadExtractZip(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:zip'
        ]);
        $zipFile = $request->file('file');
        $uploadedPath = FileHelper::directUpload($zipFile, $zipFile->getClientOriginalName(), '');
        
        $storagePath  = FileHelper::getStoragePathPrefix();
        $dateWisePath = date('Y') . '/' . date('m');

        $zip = Zip::open($storagePath . "/" . $uploadedPath);
        $files = $zip->listFiles();
        $filesArr = [];
        $acceptableFormats = ['jpg', 'jpeg', 'png'];
        $filesToDelete = [];

        foreach ($files as $key => $value) {
            $extension = pathinfo((basename($value)), PATHINFO_EXTENSION);
            if (in_array($extension, $acceptableFormats)) {
                $filesArr[$key][1] = basename($value);
                $filesArr[$key][2] = 'zip/'.$dateWisePath . '/' . $value;
            } else {
                $filesArr[$key][1] = basename($value);
                $filesArr[$key][2] = __('LBL_FILE') . ' ' . basename($value) . ' ' . __('LBL_NOT_AN_ACCEPTABLE_IMAGE_FORMAT');
                $filesToDelete[] = $value;
            }
        }
        if (count($filesToDelete) > 0) {
            $zip->delete($filesToDelete);
        }
        $zip->close();

        $zip = Zip::open($storagePath . "/" . $uploadedPath);
        $files = $zip->listFiles();
        if (count($files) > 0) {
            $zip->extract($storagePath . '/' . $dateWisePath . '/');
        }
        $zip->close();
        FileHelper::deleteFile($uploadedPath);
    
        $export = new MediaUrlExport($filesArr);
        Excel::store($export, 'public/excel/media-urls.xls');

        return jsonresponse(true, __('NOTI_ZIP_UPLOADED'), url('') . '/storage/excel/media-urls.xls');
    }
    
    public function downloadSheet(Request $request, $module_type)
    {
        switch ($module_type) {
            case 'brands':
                $export = new BrandsMediaSampleExport();
                break;
            
            case 'categories':
                $export = new CategoriesMediaSampleExport();
                break;
            
            case 'products':
                $query = Product::select('prod_id', 'prod_name', 'poption_id', 'opn_value as poption_name')
                ->leftJoin('product_options as po', function ($sql) {
                    $sql->on('po.poption_prod_id', 'products.prod_id')
                     ->join('product_option_names', 'opn_id', 'po.poption_opn_id')
                    ->join('options', 'options.option_id', 'po.poption_option_id')
                    ->where(['options.option_has_image' => 1]);
                });
                $products = $query->orderBy('prod_id','asc')->get()->toArray();
                $export = new ProductsMediaSampleExport($products);
                break;
        }
        if (isset($export)) {
            return Excel::download($export, $module_type . '-sample-sheet.xls');
        }
        return true;
    }
    
    public function uploadSheet(Request $request, $module_type = '')
    {
        if (empty($module_type)) {
            return jsonresponse(false, __('NOTI_PLEASE_SELECT_MODULE'));
        }
        
        $request->validate([
            'file' => 'required|mimes:xls'
        ]);
        
        switch ($module_type) {
            case 'brands':
                $import = new BrandsMediaImport();
                break;
            
            case 'categories':
                $import = new CategoriesMediaImport();
                break;
            
            case 'products':
                $import = new ProductsMediaImport();
                break;
        }
        Excel::import($import, $request->file('file'));
        $error = $import->getErrors();
        if (isset($error) && !empty($error)) {
            $export = new ErrorExport($error);
            $name = 'error-' . time() . '.xls';
            Excel::store($export, 'public/excel/' . $name);
            return jsonresponse(true, __('NOTI_MEDIA_IMPORTED'), url('storage/excel/' . $name));
        } else {
            return jsonresponse(true, __('NOTI_MEDIA_IMPORTED'));
        }
    }
}
