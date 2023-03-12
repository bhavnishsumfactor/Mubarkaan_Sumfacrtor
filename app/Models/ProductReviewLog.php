<?php

namespace App\Models;

use App\Models\YokartModel;
use DB;

class ProductReviewLog extends YokartModel
{
    public $timestamps = false;
    protected $primaryKey = 'prl_id';
    protected $fillable = [
        'prl_id', 'prl_preview_id', 'prl_preview_rating', 'prl_preview_title', 'prl_preview_description', 'prl_preview_status', 'prl_preview_created_at'
    ];

    public function attachedFiles()
    {
        return $this->hasMany('App\Models\AttachedFile', 'afile_record_id', 'prl_id')
        ->where('afile_type', AttachedFile::SECTIONS['productReviewLogImage']);
    }

    public static function getReviewLogById($prlId)
    {
        $dbprefix = DB::getTablePrefix();
        $query = ProductReviewLog::select(
            'product_review_logs.prl_id',
            'product_review_logs.prl_preview_id as preview_id',
            'product_reviews.preview_prod_id',
            'product_review_logs.prl_preview_rating as preview_rating',
            'product_review_logs.prl_preview_title as preview_title',
            'product_review_logs.prl_preview_description as preview_description',
            // 'preview_created_at',
            'products.prod_name',
            DB::raw("(SELECT COUNT(*) FROM " . $dbprefix . "attached_files as af WHERE af.afile_record_id = " . $dbprefix . "product_review_logs.prl_id AND af.afile_type = '" . AttachedFile::SECTIONS['productReviewLogImage'] . "') as image_attached")
        );
        $query->join('product_reviews', 'product_reviews.preview_id', 'prl_preview_id');
        $query->join('products', 'products.prod_id', 'product_reviews.preview_prod_id');
        $query->where('product_review_logs.prl_id', $prlId);
        $query->with('attachedFiles:afile_id,afile_record_id');
        return $query->get();
    }
}
