<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\YokartController;
use Illuminate\Http\Request;
use App\Models\FaqCategory;
use App\Models\Faq;
use Illuminate\Support\Facades\Validator;

class FaqController extends YokartController
{
    public function index(Request $request)
    {
        $records = FaqCategory::getRecordsForApp();
        return apiresponse(config('constants.SUCCESS'), '', ['records' => $records]);
    }
    public function detail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), ['errors' => $validator->errors()]);
        }
        $records = Faq::getRecordByIdForApp($request->input('id'));
        return apiresponse(config('constants.SUCCESS'), '', ['records' => $records]);
    }
}
