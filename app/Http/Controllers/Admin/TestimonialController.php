<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\Testimonial;
use App\Models\AttachedFile;
use App\Models\Admin\AdminRole;
use Carbon\Carbon;
use App\Http\Requests\TestimonialRequest;

class TestimonialController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::TESTIMONIALS)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getListing(Request $request)
    {
        $data = [];

        $data['testimonials'] = Testimonial::getTestimonials($request->all());

        $data['showEmpty'] = 0;
        if (empty($request['search']) && $data['testimonials']->count() == 0) {
            $data['showEmpty'] = 1;
        }
        return jsonresponse(true, null, $data);
    }

    public function store(TestimonialRequest $request)
    {
        if (!canWrite(AdminRole::TESTIMONIALS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $requestData['testimonial_user_name'] = $request->input('testimonial_user_name');
        $requestData['testimonial_designation'] = $request->input('testimonial_designation');
        $requestData['testimonial_title'] = $request->input('testimonial_title');
        $requestData['testimonial_description'] = $request->input('testimonial_description');
        $requestData['testimonial_created_by_id']  =  $requestData['testimonial_updated_by_id']  =  $this->admin->admin_id;
        $requestData['testimonial_created_at']  =  $requestData['testimonial_updated_at']  =  Carbon::now();
        $insertedId = Testimonial::create($requestData)->testimonial_id;
        imageUpload($request, $insertedId, 'testimonialMedia');
        \Cache::forget('testimonial-section1');
        \Cache::forget('testimonial-section2');
        return jsonresponse(true, __('NOTI_TESTIMONIAL_CREATED'));
    }

    public function update(TestimonialRequest $request, $id)
    {
        if (!canWrite(AdminRole::TESTIMONIALS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $requestData['testimonial_user_name'] = $request->input('testimonial_user_name');
        $requestData['testimonial_designation'] = $request->input('testimonial_designation') == 'null' ? "" : $request->input('testimonial_designation');
        $requestData['testimonial_title'] = $request->input('testimonial_title');
        $requestData['testimonial_description'] = $request->input('testimonial_description');
        $requestData['testimonial_updated_at']  =  Carbon::now();
        $requestData['testimonial_updated_by_id']  =  $this->admin->admin_id;
        Testimonial::where('testimonial_id', $id)->update($requestData);
        \Cache::forget('testimonial-section1');
        \Cache::forget('testimonial-section2');
        imageUpload($request, $id, 'testimonialMedia');
        return jsonresponse(true, __('NOTI_TESTIMONIAL_UPDATED'));
    }

    public function destroy($id)
    {
        if (!canWrite(AdminRole::TESTIMONIALS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        \Cache::forget('testimonial-section1');
        \Cache::forget('testimonial-section2');
        Testimonial::find($id)->delete();
        return jsonresponse(true, __('NOTI_TESTIMONIAL_DELETED'));
    }

    public function updateStatus(Request $request, $testimonial_id)
    {
        if (!canWrite(AdminRole::TESTIMONIALS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $publishStatus = (!empty($request->input('status')) && $request->input('status') == 'true') ? 1 : 0;
        Testimonial::where('testimonial_id', $testimonial_id)->update(['testimonial_publish' => $publishStatus, 'testimonial_updated_at' => Carbon::now(), 'testimonial_updated_by_id' => $this->admin->admin_id ]);
        $message = (!empty($request->input('status')) && $request->input('status') == 'true') ? __('NOTI_TESTIMONIAL_PUBLISHED') : __('NOTI_TESTIMONIAL_UNPUBLISHED');
        return jsonresponse(true, $message);
    }

    public function deleteImage(Request $request, $afile_id)
    {
        if (!canWrite(AdminRole::TESTIMONIALS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        if (!empty($afile_id)) {
            AttachedFile::deleteFileByAfileId($afile_id);
        }
        return jsonresponse(true, __('NOTI_TESTIMONIAL_IMAGE_DELETED'));
    }
}
