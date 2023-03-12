<?php

namespace App\Models;

use App\Models\YokartModel;

class ProductReviewHelpful extends YokartModel
{
    public $timestamps = false;
    public $table = 'product_review_helpful';
    protected $fillable = ['prh_preview_id', 'prh_user_id', 'prh_helpful'];

    public static function saveHelpful($previewId, $userId, $helpful)
    {
        ProductReviewHelpful::where('prh_preview_id', $previewId)
            ->where('prh_user_id', $userId)
            ->delete();
        ProductReviewHelpful::create([
            'prh_preview_id' => $previewId,
            'prh_user_id' => $userId,
            'prh_helpful' => $helpful
        ]);
    }

    public static function alreadyExists($previewId, $userId)
    {
        $count = ProductReviewHelpful::where('prh_preview_id', $previewId)
            ->where('prh_user_id', $userId)
            ->count();
        if ($count) {
            return true;
        }
        return false;
    }
}
