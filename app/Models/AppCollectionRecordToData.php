<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\AdminYokartModel;
use App\Models\AttachedFile;

class AppCollectionRecordToData extends AdminYokartModel
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'acrd_id';
    protected $fillable = [
        'acrd_actr_id', 'acrd_record_id', 'acrd_subrecord_id', 'acrd_display_order', 'acrd_description'
    ];
    
    public function image()
    {
        return $this->hasOne('App\Models\AttachedFile', 'afile_record_id', 'acrd_id');
    }
    public static function getTagsCollectionById($collectionId)
    {
        return AppCollectionRecordToData::with(['image' => function ($sql) {
            $sql->select('afile_id', 'afile_updated_at', 'afile_physical_path', 'afile_record_id')->where('afile_type', AttachedFile::getConstantVal('appImageCollection'));
        }])->where('acrd_actr_id', $collectionId)->get();
    }
}
