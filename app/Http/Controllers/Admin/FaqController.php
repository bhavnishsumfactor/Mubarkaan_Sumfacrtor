<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\Admin\AdminRole;
use App\Http\Requests\FaqRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use Carbon\Carbon;

class FaqController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::FAQ)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getListing(Request $request)
    {
        $data = [];

        $data['faqs'] = Faq::getAllFaqs($request->all());
        $data['categories'] = FaqCategory::getActiveCategories();
        $status = ($data['faqs']->count() > 0) ? true : false;

        $data['showEmpty'] = 0;
        if (empty($request['search']) && $data['faqs']->count() == 0) {
            $data['showEmpty'] = 1;
        }

        return jsonresponse($status, '', $data);
    }

    public function store(FaqRequest $request)
    {
        if (!canWrite(AdminRole::FAQ)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }

        $request['faq_faqcat_id'] = FaqCategory::generateFaqCatId($request['faq_category']);
        $request['faq_created_by_id'] = $request['faq_updated_by_id'] = $this->admin->admin_id;
        $request['faq_created_at'] = $request['faq_updated_at'] = Carbon::now();
        Faq::create($request->all());
        \Cache::forget('faq-section1');
        return jsonresponse(true, __('NOTI_FAQS_ADDED'));
    }

    public function update(FaqRequest $request, $faq_id)
    {
        if (!canWrite(AdminRole::FAQ)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }

        $request['faq_faqcat_id'] = FaqCategory::generateFaqCatId($request['faq_category']);
        Faq::where('faq_id', $faq_id)->update([
            'faq_faqcat_id' => $request['faq_faqcat_id'],
            'faq_title' => $request->input('faq_title'),
            'faq_content' => $request->input('faq_content'),
            'faq_display_order' => $request->input('faq_display_order'),
            'faq_updated_at' => Carbon::now(),
            'faq_updated_by_id' => $this->admin->admin_id
        ]);
        \Cache::forget('faq-section1');
        return jsonresponse(true, __('NOTI_FAQS_UPDATED'));
    }

    public function updateStatus(Request $request, $faq_id)
    {
        if (!canWrite(AdminRole::FAQ)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }

        $publishStatus = (!empty($request->input('status')) && $request->input('status') == 'true')?1:0;
        Faq::where('faq_id', $faq_id)->update(['faq_publish' => $publishStatus, 'faq_updated_at' => Carbon::now(), 'faq_updated_by_id' => $this->admin->admin_id ]);
        $message = (!empty($request->input('status')) && $request->input('status') == 'true')?__('Faq published'):__('Faq unpublished');
        return jsonresponse(true, $message);
    }

    public function destroy($id)
    {
        if (!canWrite(AdminRole::FAQ)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $faq = Faq::where('faq_id', $id)->first();
        FaqCategory::deactivateFaqCatId($faq->faq_faqcat_id);
        \Cache::forget('faq-section1');
        $faq->delete();
        return jsonresponse(true, __('NOTI_FAQS_DELETED'));
    }
}
