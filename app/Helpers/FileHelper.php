<?php

namespace App\Helpers;

use Storage;
use App\Helpers\YokartHelper;
use Image;
use File;

class FileHelper extends YokartHelper
{
    public const DRIVER = 'uploads';
    public const BRANDING = ['yokart', 'yo-kart'];
    public const NO_IMAGES_DIR = 'noimages/';
    public const NO_IMAGE = self::NO_IMAGES_DIR . 'no_image.png';

    public static function getStoragePathPrefix()
    {
        return Storage::disk(self::DRIVER)->getDriver()->getAdapter()->getPathPrefix();
    }

    public static function createResizedImage($path, $imageName, $w, $h)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }

    public static function resizeImage($image, $w, $h, $convertType = '')
    {
        $mimeType = mime_content_type($image);

        list($width, $height) = getimagesize($image);
        $ratio_orig = $width / $height;
        $newwidth = $width;
        $newheight = $height;

        if ($newwidth / $newheight > $ratio_orig) {
            $newwidth = $newheight * $ratio_orig;
        } else {
            $newheight = $newwidth / $ratio_orig;
        }

        $originx = 0;
        if ($w != $newwidth) {
            $originx = ($w - $newwidth) / 2;
        }

        $originY = 0;
        if ($h != $newheight) {
            $originY = ($h - $newheight) / 2;
        }

        switch ($mimeType) {
            case 'image/png':
                $img = imagecreatefrompng($image);
                break;
            case 'image/webp':
                $img = imagecreatefromwebp($image);
                break;
            case 'image/jpg':
            case 'image/jpeg':
                $img = imagecreatefromjpeg($image);
                break;
            default:
                $img = imagecreatefromjpeg($image);
                break;
        }

        $thumb = imagecreatetruecolor($w, $h);
        $color_fill = imagecolorallocate($thumb, 204, 204, 204);
        imagefill($thumb, 0, 0, $color_fill);

        imagecopyresampled($thumb, $img, 0, 0, 0, 0, $w, $h, $width, $height);
        if (!empty($convertType)) {
            imagewebp($thumb, null, 100);
        } else {
            imagejpeg($thumb, null, 100);
        }
    }

    public static function displayImage($imageName, $w, $h, $module = null, $convertType = '')
    {
        $fileExists = Storage::disk(self::DRIVER)->exists($imageName);
        if (!$fileExists) {
            $no_image = self::NO_IMAGE;
            if ($module) {
                $no_image = FileHelper::getNoImage($module);
            }
            // static::imagePath($no_image);
            static::dummyImagePath($no_image, $w, $h);
            return false;
        }

        try {
            $image = storage_path('app/public') . '/' . $imageName;
            self::setHeaders();
            $fileTime =  filemtime($image);
            $mimeType = mime_content_type($image);
            self::checkModifiedHeader($imageName, $fileTime);
            self::setLastModified($imageName, $fileTime);
            if (!empty($convertType)) {
                $mimeType = 'image/webp';
            }
            header('Content-Type: ' . $mimeType);
            static::resizeImage($image, $w, $h, $convertType);
            exit;
        } catch (Exception $e) {
            $no_image = self::NO_IMAGE;
            if ($module) {
                $no_image = FileHelper::getNoImage($module);
            }
            // static::imagePath($no_image);
            static::dummyImagePath($no_image, $w, $h);
        }
    }

    public static function dummyImagePath($imageName, $w, $h)
    {
        $image = storage_path('app/public') . '/' . $imageName;
        self::setHeaders();
        $fileTime =  filemtime($image);
        $mimeType = mime_content_type($image);
        self::checkModifiedHeader($imageName, $fileTime);
        self::setLastModified($imageName, $fileTime);
        header('Content-Type: ' . $mimeType);
        static::resizeImage($image, $w, $h);
    }
    public static function display($path, $size = "", $module = null)
    {
        if ($module && $path == "") {
            $path = FileHelper::getNoImage($module);
        }
        $dirArr =  explode('-', $size);
        $w = 0;
        $h = 0;
        $imgType = '';
        if (count($dirArr) >=  2) {
            $w = $dirArr[0];
            $h = $dirArr[1];
            $imgType = (isset($dirArr[2]) && strtoupper($dirArr[2]) == 'WEBP') ? $dirArr[2] : '';
            FileHelper::displayImage($path, $w, $h, $module, $imgType);
        } else {
            static::imagePath($path);
        }
    }

    /*
    public static function displayOldStyleImage11($image_name = self::NO_IMAGE, $module = null)
    {
        $no_image = self::NO_IMAGE;
        if ($module) {
            $no_image = FileHelper::getNoImage($module);
        }
        $fileExists = Storage::disk(self::DRIVER)->exists($image_name);

        if (!$fileExists) {
            static::imagePath($no_image);
            return false;
        }

        try {
            static::imagePath($image_name);
        } catch (Exception $e) {
            static::imagePath($no_image);
        }
    }
     */

    private static function getNoImage($module)
    {
        $imagePath = "";
        $module = strtoupper($module);
        switch ($module) {
            case "PRODUCTIMAGES":
                $imagePath = self::NO_IMAGES_DIR . productDummyImage(false);
                break;

            case "BRANDLOGO":
                $imagePath = self::NO_IMAGES_DIR . imageConfig("BRAND.DUMMY");
                break;

            case "PRODUCTCATEGORYBANNER":
                $imagePath = self::NO_IMAGES_DIR . "4_1/2000x500.png";
                break;

            case "DISCOUNTCOUPONIMAGE":
                $imagePath = self::NO_IMAGES_DIR . "coupon-image.png";
                break;

            case "BLOGIMAGE":
                $imagePath = self::NO_IMAGES_DIR . "4_3/1000x750.png";
                break;

            case "TESTIMONIALMEDIA":
                $imagePath = self::NO_IMAGES_DIR . "1_1/100x100.png";
                break;

            case "BANNERSLIDER":
                $imagePath = self::NO_IMAGES_DIR . "4_1/2000x500.png";
                break;

            case "USERPROFILEIMAGE":
                $imagePath = self::NO_IMAGES_DIR . "1_1/profile-user-icon.png";
                break;
            case "PROFILEIMAGE":
                $imagePath = self::NO_IMAGES_DIR . "1_1/profile-user-icon.png";
                break;
            default:
                $imagePath = self::NO_IMAGE;
                break;
        }
        return $imagePath;
    }
    public static function imagePath($image_name)
    {
        self::setHeaders();
        $img = storage_path('app/public') . '/' . $image_name;
        $fileTime =  filemtime($img);
        $mimeType = mime_content_type($img);
        self::checkModifiedHeader($image_name, $fileTime);
        self::setLastModified($image_name, $fileTime);
        header('Content-Type: ' . $mimeType);
        echo file_get_contents($img);
        exit;
        /* self::setHeaders();

        $driverPath = Storage::disk(self::DRIVER)->getDriver();
        $metaData = $driverPath->getMetadata($image_name);

        self::checkModifiedHeader($image_name, $metaData['timestamp']);
        self::setLastModified($image_name, $metaData['timestamp']);

        header('Content-Type: ' . $metaData['type']);

        $handle = $driverPath->readStream($image_name);

        $chunkSize = 1024 * 1024;

        while (!feof($handle)) {
            $buffer = fread($handle, $chunkSize);
            echo $buffer;
            ob_flush();
            flush();
        }
        fclose($handle);
        exit; */
    }
    /*public static function imagePath($image_name)
    {


        self::setHeaders();

        $driverPath = Storage::disk(self::DRIVER)->getDriver();
        $metaData = $driverPath->getMetadata($image_name);

        self::checkModifiedHeader($image_name, $metaData['timestamp']);
        self::setLastModified($image_name, $metaData['timestamp']);

        header('Content-Type: ' . $metaData['type']);

        $handle = $driverPath->readStream($image_name);

        $chunkSize = 1024 * 1024;

        while (!feof($handle)) {
            $buffer = fread($handle, $chunkSize);
            echo $buffer;
            ob_flush();
            flush();
        }
        fclose($handle);
        exit;
    }*/

    public static function deleteFile($filePath)
    {
        Storage::disk(self::DRIVER)->delete($filePath);
    }

    public static function fileExists($filePath)
    {
        return Storage::disk(self::DRIVER)->exists($filePath);
    }

    public static function fileUrl($filePath)
    {
        return Storage::url($filePath);
    }

    public static function getFile($filePath)
    {
        return Storage::disk(self::DRIVER)->get($filePath);
    }

    public static function uploadFile($file)
    {
        $dateWisePath = date('Y') . '/' . date('m');
        $brandingName = self::BRANDING[array_rand(self::BRANDING)];
        $saveName = time() . '-' . $brandingName . '-' . preg_replace('/[^a-zA-Z0-9]/', '', $file->getClientOriginalName());

        $destinationPath = Storage::disk(self::DRIVER)->path($dateWisePath);
        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }
        $img = Image::make($file->path());
        $img->save($destinationPath . '/' . $saveName);
        return $dateWisePath . '/' . $saveName;
    }
    public static function uploadThumbFile($fileName, $file)
    {
        $destinationPath = Storage::disk(self::DRIVER)->path('/');
        $img = Image::make($file);
        $img->save($destinationPath . '/' . $fileName);
        return $fileName;
    }

    public static function uploadDigitalFiles($file)
    {
        $dateWisePath = date('Y') . '/' . date('m');
        $brandingName = self::BRANDING[array_rand(self::BRANDING)];
        $extension = $file->getClientOriginalExtension();
        $saveName = time() . '-' . $brandingName . '-' . preg_replace('/[^a-zA-Z0-9]/', '', $file->getFilename()) . '.' . $extension;
        $destinationPath = Storage::disk(self::DRIVER)->path($dateWisePath);
        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }


        Storage::disk(self::DRIVER)->put($dateWisePath . '/' . $saveName, File::get($file));
        return $dateWisePath . '/' . $saveName;
    }

    public static function directUpload($file, $fileName, $filePath = '/')
    {
        if (!is_string($file)) {
            $file = File::get($file);
        }
        $destinationPath = Storage::disk(self::DRIVER)->path($filePath);
        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }
        Storage::disk(self::DRIVER)->put($filePath . $fileName, $file);
        return $filePath . $fileName;
    }

    public static function saveFile($fileContent, $fileName, $filePath = '/')
    {
        $destinationPath = Storage::disk(self::DRIVER)->path($filePath);
        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }
        Storage::disk(self::DRIVER)->put($filePath . $fileName, $fileContent);
        return $filePath . $fileName;
    }

    public static function randomFile($dir)
    {
        $path = '';
        if ($files = Storage::disk(self::DRIVER)->allFiles($dir)) {
            $path = $files[array_rand($files)];
        }
        return $path;
    }

    public static function setHeaders()
    {
        header('Cache-Control: public, max-age=2592000, stale-while-revalidate=604800');
        header("Pragma: public");
        header("Expires: " . date('r', strtotime("+30 days")));
    }

    public static function checkModifiedHeader($image_name, $fileTime = '')
    {
        $headers = self::getApacheHeaders();
        if (empty($fileTime)) {
            $fileTime = filemtime($image_name);
        }
        if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == $fileTime)) {
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $fileTime) . ' GMT', true, 304);
            exit;
        }
    }

    public static function setLastModified($image_name, $fileTime = '')
    {
        if (empty($fileTime)) {
            $fileTime = filemtime($image_name);
        }
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $fileTime) . ' GMT', true, 200);
    }

    public static function getApacheHeaders()
    {
        if (!function_exists('apache_request_headers')) {
            $arh = array();
            $rx_http = '/\AHTTP_/';
            foreach ($_SERVER as $key => $val) {
                if (preg_match($rx_http, $key)) {
                    $arh_key = preg_replace($rx_http, '', $key);
                    $rx_matches = array();
                    // do some nasty string manipulations to restore the original letter case
                    // this should work in most cases
                    $rx_matches = explode('_', $arh_key);
                    if (count($rx_matches) > 0 and strlen($arh_key) > 2) {
                        foreach ($rx_matches as $ak_key => $ak_val) {
                            $rx_matches[$ak_key] = ucfirst($ak_val);
                        }
                        $arh_key = implode('-', $rx_matches);
                    }
                    $arh[$arh_key] = $val;
                }
            }
            return ($arh);
        }
        return  apache_request_headers();
    }
}
