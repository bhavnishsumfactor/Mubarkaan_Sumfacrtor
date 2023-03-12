<?php

namespace App\Models;

use App\Models\YokartModel;

class Slide extends YokartModel
{
    public $timestamps = false;
    protected $primaryKey = 'slide_id';
    protected $fillable = ['slide_collection_id', 'slide_url', 'slide_target'];

    public static function recordExists($collection_id)
    {
        return Slide::where('slide_collection_id', $collection_id)
        ->count();
    }
    public function images()
    {
        return $this->hasMany('App\Models\AttachedFile', 'afile_record_subid', 'slide_id')->where('attached_files.afile_type', '=', AttachedFile::SECTIONS['bannerSlider'])->orderBy('afile_device', 'DESC');
    }
    public static function getSlides($collectionId)
    {

        return Slide::with(['images' => function ($sql) use ($collectionId) {
            $sql->where('afile_record_id', $collectionId);
        }])->where('slide_collection_id', $collectionId)
        ->get();
    }

    public static function saveOrUpdate($collection_id, $slide_url = null, $slide_target = '_self', $slide_id = null)
    {
        if (empty($slide_id)) {
            $slide = Slide::create([
            'slide_collection_id' => $collection_id,
            'slide_url' => $slide_url,
            'slide_target' => $slide_target
            ]);
            $slide_id = $slide->slide_id;
        } else {
            Slide::where('slide_id', $slide_id)
            ->update(['slide_url' => $slide_url, 'slide_target' => $slide_target]);
        }
        return $slide_id;
    }

    public static function deleteSlide($slide_id)
    {
        return Slide::where('slide_id', $slide_id)
        ->delete();
    }
}
