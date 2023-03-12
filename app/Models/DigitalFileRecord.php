<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\AttachedFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DigitalFileRecord extends YokartModel
{
    use HasFactory;

    public $timestamps = false;

    const FILE_TYPES = [
        1 => 'Image',
        2 => 'Video',
        3 => 'Audio',
        4 => 'Software',
    ];

    const PRODUCT_RECORD_TYPE = 1;
    const ORDER_RECORD_TYPE = 2;
    const ORDERED_PRODUCT_RECORD_TYPE = 3;
    protected $fillable = [
        'dfr_record_id', 'dfr_subrecord_id', 'dfr_record_type', 'dfr_afile_id', 'dfr_file_type', 'dfr_name', 'dfr_url', 'dfr_pov_id'
    ];

    public static function getByRecordId($recordId, $recordType)
    {
        return DigitalFileRecord::leftJoin('attached_files as afiles', 'afiles.afile_id', 'digital_file_records.dfr_afile_id')
            ->select('digital_file_records.*', 'afiles.afile_physical_path')
            ->where('dfr_record_id', $recordId)->where('dfr_record_type', $recordType)->get();
    }
    public function attachment()
    {
        return $this->belongsTo('App\Models\AttachedFile', 'dfr_afile_id', 'afile_id');
    }
    public static function getRecordsByOrderId($orderId, $prodId, $povId = 0)
    {
        $query =  DigitalFileRecord::with('attachment:afile_physical_path,afile_id')
            ->select('dfr_name', 'dfr_file_type', 'dfr_url', 'dfr_afile_id')
            ->where('dfr_record_id', $orderId);
        if (!empty($povId)) {
            die('s');
            $query->where('dfr_pov_id', $povId);
        }
        return $query->where('dfr_subrecord_id', $prodId)->where('dfr_record_type', self::ORDER_RECORD_TYPE)->get();
    }

    public static function saveAttachedFiles($prodId, $orderId, $subId = 0)
    {
        $subId = $subId ?? 0;
        $records =  AttachedFile::where([
            'afile_record_id' => $prodId,
            'afile_record_subid' => $subId,
            'afile_type' => AttachedFile::getConstantVal('digitalFiles')
        ])->get();
       
        $attachedIds = [];
        foreach ($records as $record) {
            $duplicate = $record->replicate();
            $duplicate->afile_record_id  = $orderId;
            $duplicate->afile_record_subid  = $prodId;
            $duplicate->save();
            $attachedIds[$record->afile_id] = $duplicate->afile_id;
        }
        $digitalRecords = DigitalFileRecord::where([
            'dfr_record_id' => $prodId,
            'dfr_pov_id' => $subId,
            'dfr_record_type' => DigitalFileRecord::PRODUCT_RECORD_TYPE
        ])->get();
        foreach ($digitalRecords as $digital) {
            $duplicateDigital = $digital->replicate();
            $duplicateDigital->dfr_record_id = $orderId;
            $duplicateDigital->dfr_subrecord_id = $prodId;
            $duplicateDigital->dfr_afile_id = !empty($attachedIds[$digital->dfr_afile_id]) ? $attachedIds[$digital->dfr_afile_id] : 0;
            $duplicateDigital->dfr_record_type = DigitalFileRecord::ORDER_RECORD_TYPE;
            $duplicateDigital->save();
        }
    }
}
