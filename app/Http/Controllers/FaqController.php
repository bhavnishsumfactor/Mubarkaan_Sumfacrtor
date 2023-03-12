<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\YokartController;

class FaqController extends YokartController
{
    public function index()
    {
        return view('themes.'.config('theme').'.faqs');
    }

    public function ajaxSearch(Request $request)
    {
        $data = [];
        if (empty($request['search']) && empty($request['category_id'])) {
            $data['categories'] = FaqCategory::getActiveCategories();
        }
        $data['faqs'] = Faq::getFaqs($request);
        $data['faqsHtml'] = view('themes.'.config('theme').'.faqsAjax')->with('faqs', $data['faqs'])->with('search', ($request['search'] ?? ''))->render();
        return jsonresponse(true, '', $data);
    }
}
