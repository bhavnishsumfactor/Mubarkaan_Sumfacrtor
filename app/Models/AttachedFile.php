<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Helpers\FileHelper;
use Carbon\Carbon;
use DB;

class AttachedFile extends YokartModel
{
    public const CREATED_AT = 'afile_created_at';
    public const UPDATED_AT = 'afile_updated_at';

    protected $primaryKey = 'afile_id';
     
    public const SECTIONS = [
        'profileImage' => 1,
        'adminLogo' => 2,
        'emailLogo' => 3,
        'brandLogo' => 4,
        'productCategoryBanner' => 5,
        'productChartUpload' => 6,
        'productImages' => 7,
        'favicon' => 11,
        'blogImage' => 13,
        'userProfileImage' => 15,
        'digitalFiles' => 16,
        'testimonialMedia' => 17,
        'productReviewImage' => 19,
        'frontendLogo' => 21,
        'discountCouponImage' => 22,
        'productReviewLogImage' => 23,
        'adminDarkModeLogo' => 28,
        'frontendDarkModeLogo' => 29,
        'messageFile' => 30,
        'bannerSlider' => 8,
        'promotionalBannerLayout1' => 9,
        'newsletterFullwidth' => 10,
        'categoryCollectionLayout2' => 12,
        'categoryCollection5' => 42,
        'imageTag' => 14, //image widget
        'categoryCollectionLayout3' => 20, //to be deleted
        'titleWithBackgroundImage' => 24,
        'imageGallery' => 25,
        'textWithTwoImagesOnLeft' => 26,
        'textWithTwoImagesOnRight' => 27,
        'categoryCollection3' => 31,
        'categoryCollection4' => 32,
        'promotionalBannerCollection2' => 33,
        'newsletterCollection2' => 34,
        'promotionalBannerCollection3' => 35,
        'appCategoryCollection' => 36,
        'appTestimonialCollection1' => 37,
        'appTestimonialCollection' => 37,
        'appLogo' => 38,
        'appBannerSliderCollection' => 39,
        'appBrandCollection' => 40,
        'appImageCollection' => 41,
    ];

    public const DEVICE_TYPE = [
        'desktop' => 1,
        'tablet' => 2,
        'mobile' => 3
    ];

    protected $fillable = [
        'afile_type', 'afile_record_id', 'afile_record_subid', 'afile_device', 'afile_physical_path',
        'afile_name', 'afile_display_order', 'afile_attribute_title', 'afile_attribute_alt', 'afile_enable_background', 'afile_updated_by_id', 'afile_meta_updated_at'
    ];

    public static function getConstantVal($key)
    {
        return self::SECTIONS[$key];
    }

    public static function getFlippedSections()
    {
        return array_flip(self::SECTIONS);
    }

    public static function saveFile($recordType, $recordId, $filePath, $fileName, $subRecordId = 0, $enableBackground = 0)
    {
        $insertID = AttachedFile::create([
            'afile_type' => $recordType,
            'afile_record_id' => $recordId,
            'afile_record_subid' => $subRecordId,
            'afile_physical_path' => $filePath,
            'afile_name' => $fileName,
            'afile_enable_background' => $enableBackground,
            'afile_updated_at' => Carbon::now()
        ])->afile_id;
        return $insertID;
    }


    public static function saveOrUpdateFile($sectionId, $recordId, $filePath, $fileName, $subRecordId = 0, $deviceType = 0)
    {
        $attachedfile = self::getAttachedFile($sectionId, $recordId, $subRecordId, $deviceType);
        $id = 0;
        if (empty($attachedfile)) {
            $attachedfile = AttachedFile::create([
                'afile_type' => $sectionId,
                'afile_record_id' => $recordId,
                'afile_record_subid' => $subRecordId,
                'afile_device' => $deviceType,
                'afile_physical_path' => $filePath,
                'afile_name' => $fileName,
                'afile_updated_at' => Carbon::now()
            ]);
        } else {
            if (!empty($attachedfile->afile_physical_path)) {
                FileHelper::deleteFile($attachedfile->afile_physical_path);
                if (strpos($attachedfile->afile_physical_path, '-thumb') == true) {
                    FileHelper::deleteFile(str_replace('-thumb', '', $attachedfile->afile_physical_path));
                }
            }
            AttachedFile::find($attachedfile->afile_id)->update([
                'afile_physical_path' => $filePath,
                'afile_name' => $fileName,
                'afile_updated_at' => Carbon::now()
            ]);
        }
        return $attachedfile;
    }

    public static function showFile($sectionId, $recordId, $subRecordId, $size, $time)
    {

        $attachedFile = \Cache::remember('img-' . $sectionId . '-' . $recordId . '-' . $subRecordId . '-' . $time,  604800, function () use ($sectionId, $recordId, $subRecordId) {
              return self::getAttachedFile(self::getConstantVal($sectionId), $recordId, $subRecordId);
         });
        $dirArr =  explode('-', $size);
      
        if ($attachedFile && count($dirArr) >=  2) {
            FileHelper::display($attachedFile->afile_physical_path, $size, $sectionId);
        }

        if (!empty($attachedFile->afile_physical_path)) {
            if ($size == 'original' && strpos($attachedFile->afile_physical_path, '-thumb') == true) {
                $attachedFile->afile_physical_path = str_replace('-thumb', '', $attachedFile->afile_physical_path);
            }
            FileHelper::display($attachedFile->afile_physical_path, $size);
        }
     
        self::showDefaultImage($sectionId);
    }


    public static function getAttachedFileCount($section, $recordId, $subRecordId = 0)
    {
        if (!is_int($section)) {
            $section = self::getConstantVal($section);
        }
        return AttachedFile::where('afile_record_id', $recordId)
            ->where('afile_record_subid', $subRecordId)
            ->where('afile_type', $section)
            ->count();
    }

    public static function getAttachedFile($section, $recordId, $subRecordId = 0, $deviceType = 0)
    {
        $attachedFileQuery = AttachedFile::select('afile_id', 'afile_physical_path', 'afile_name', 'afile_updated_at');

        if ($subRecordId == 0 && (empty($recordId) || $recordId == 0)) {
            $attachedFileQuery->where('afile_type', $section);
        } else {
            $attachedFileQuery->where('afile_record_id', $recordId)
                ->where('afile_record_subid', $subRecordId)
                ->where('afile_type', $section);
        }
        if ($deviceType != 0) {
            $attachedFileQuery->where('afile_device', $deviceType);
        }
        $attachedFile = $attachedFileQuery->first(); //->latest()
        if (!empty($attachedFile)) {
            return $attachedFile;
        }
        return null;
    }

    public static function getFileUrl($section, $recordId, $subRecordId = 0, $size = 'original', $onlyOriginal = false)
    {
        $attachedFile = self::getAttachedFile(self::getConstantVal($section), $recordId, $subRecordId);
        if ($attachedFile == null) {
            // if ($onlyOriginal == false) {
            //     return url('/yokart/image/0/original');
            // } else {
            return '';
            // }
        }
        return url('/yokart/image/' . $section . '/' . $recordId . '/' . $subRecordId . '/' . $size . '?t=' . strtotime($attachedFile->afile_updated_at));
    }
    public static function showFileById($afileId, $size = '', $timestamp = null)
    {
        $attachedFile = \Cache::remember('img-' . $afileId . '-' . $timestamp, 604800, function () use ($afileId) {
            return AttachedFile::where('afile_id', $afileId)->first();
       });
       
        $dirArr =  explode('-', $size);
        if (count($dirArr) >=  2) {
            if (!empty($attachedFile->afile_physical_path)) {
                FileHelper::display($attachedFile->afile_physical_path, $size);
            } else {
                FileHelper::display("", $size);
            }
        }

        if (!empty($attachedFile->afile_physical_path)) {
            if ($size == 'original' && strpos($attachedFile->afile_physical_path, '-thumb') == true) {
                $attachedFile->afile_physical_path = str_replace('-thumb', '', $attachedFile->afile_physical_path);
            }
            FileHelper::display($attachedFile->afile_physical_path, $size);
        }
        self::showDefaultImage();
    }

    public static function getNoImage($name)
    {
        FileHelper::display("noimages/" . $name);
    }

    public static function getBulkFiles($section, $recordId, $subRecordId = 0, $allImages = false, $subCondition = true, $size = '', $type = 'web')
    {
        if (empty($size)) {
            $size = '/200-200-webp';
        }
     
        if ($type != 'web') {
            $size = '';
        }
        $obj = AttachedFile::select(
            'afile_id',
            'afile_record_id',
            'afile_record_subid',
            'afile_physical_path',
            'afile_attribute_title',
            'afile_attribute_alt',
            'afile_enable_background',
            'afile_updated_at',
            'afile_updated_by_id',
            'afile_meta_updated_at',
            DB::Raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '" . $size . "', '?t=', UNIX_TIMESTAMP(afile_updated_at)) as prod_image")
        )
            ->where('afile_type', $section)->where('afile_record_id', $recordId);
        if ($allImages) {
            $obj->with('updatedAt');
        }
        if ($subCondition) {
            $obj->where('afile_record_subid', $subRecordId);
        }

        if ($allImages == false) {
            $obj->take(1);
        }
        return $obj->get();
    }

    public static function deleteFile($filePath)
    {
        AttachedFile::where('afile_physical_path', $filePath)->delete();
        FileHelper::deleteFile($filePath);
    }

    public static function deleteFileByAfileId($afileId, $physically = true)
    {
        $attachedFile = AttachedFile::select('afile_physical_path')->where('afile_id', $afileId)->first();
        if (!empty($attachedFile->afile_physical_path)) {
            AttachedFile::where('afile_physical_path', $attachedFile->afile_physical_path)->delete();
            if ($physically) {
                FileHelper::deleteFile($attachedFile->afile_physical_path);
            }
        }
    }

    public static function deleteFileById($sectionId, $recordId, $subRecordId = 0, $deviceType = 0)
    {
        $attachedRecords = AttachedFile::select('afile_physical_path')->where([
            'afile_type' => $sectionId, 'afile_record_id' => $recordId, 'afile_record_subid' => $subRecordId, 'afile_device' => $deviceType
        ])->get();
        foreach ($attachedRecords as $attachedRecord) {
            self::deleteFile($attachedRecord->afile_physical_path);
        }
    }

    public static function showDefaultImage($sectionId = null)
    {
        FileHelper::display('', '', $sectionId);
    }

    public static function deleteDigitalFiles($afileId, $prodID)
    {
        $attachedRecords = AttachedFile::select('afile_physical_path')->where('afile_record_id', $prodID)->whereNotIn('afile_id', $afileId)->get();
        foreach ($attachedRecords as $attachedRecord) {
            self::deleteFile($attachedRecord->afile_physical_path);
        }
    }

    public static function updateAttachedFiles($afileId, $filePath, $fileName, $enableBackground = 0)
    {
        $attachedRecord = AttachedFile::where('afile_id', $afileId)->first();

        if (isset($attachedRecord->afile_id) && !empty($attachedRecord->afile_id)) {
            $attachedRecord->afile_physical_path = $filePath;
            $attachedRecord->afile_name = $fileName;
            $attachedRecord->afile_enable_background = $enableBackground;
            $attachedRecord->afile_updated_at = Carbon::now();
            $attachedRecord->save();
        }
    }

    public static function assignImageRandomly($section, $imageNo, $recordId, $subRecordId = 0)
    {
        for ($i = 0; $i < $imageNo; $i++) {

            $record = AttachedFile::where('afile_type', self::getConstantVal($section))->inRandomOrder()->first();
            $duplicate = $record->replicate();
            $duplicate->afile_record_id = $recordId;
            $duplicate->afile_record_subid = $subRecordId;
            $duplicate->save();
        }
    }
    public static function deleteBulkFilesByIds($sectionId, $recordIds)
    {
        $attachedRecords = AttachedFile::select('afile_physical_path')->where('afile_type', $sectionId)->whereIn('afile_record_id', $recordIds)->get();
        foreach ($attachedRecords as $attachedRecord) {
            self::deleteFile($attachedRecord->afile_physical_path);
        }
    }
    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'afile_updated_by_id')->select(['admin_id', 'admin_username']);
    }

    public static function deleteAttachedFileById($fileId)
    {
        $attachedRecord = AttachedFile::select('afile_id', 'afile_physical_path')->where(['afile_id' => $fileId])->first();
        if (isset($attachedRecord->afile_id) && !empty($attachedRecord->afile_id)) {
            self::deleteFile($attachedRecord->afile_physical_path);
            return true;
        } else {
            return false;
        }
    }
}
