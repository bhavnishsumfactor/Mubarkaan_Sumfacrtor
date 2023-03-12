<?php

namespace App\Models;

use App\Models\Faq;
use App\Models\YokartModel;

class FaqCategory extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'faqcat_id';

    protected $fillable = [
      'faqcat_name', 'faqcat_publish', 'faqcat_display_order'
    ];

    public function faqs()
    {
        return $this->hasMany('App\Models\Faq', 'faq_faqcat_id', 'faqcat_id')->where('faq_publish', 1);
    }

    public static function getActiveCategories()
    {
        return FaqCategory::where('faqcat_publish', 1)
        ->withCount('faqs')->has('faqs', '>', 0)
        ->oldest('faqcat_display_order')->get()->pluck('faqcat_name', 'faqcat_id')->toArray();
    }

    public static function generateFaqCatId($faqcat_name)
    {
        $faqCat = FaqCategory::select('faqcat_id')->where('faqcat_name', $faqcat_name)->first();
        if (!empty($faqCat)) {
            return $faqCat->faqcat_id;
        };
        return FaqCategory::create([
            'faqcat_name' =>$faqcat_name
        ])->faqcat_id;
    }

    public static function deactivateFaqCatId($faqCatId)
    {
        $categoryFaqCount = Faq::where('faq_faqcat_id', $faqCatId)->count();
        if ($categoryFaqCount == 1) {
            FaqCategory::where('faqcat_id', $faqCatId)->update(['faqcat_publish' => config('constants.NO')]);
        }
    }
}
