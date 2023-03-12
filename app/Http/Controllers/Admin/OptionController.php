<?php

namespace App\Http\Controllers\Admin;

use App\Models\Option;
use App\Models\Admin\AdminRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Http\Requests\OptionRequest;
use App\Http\Requests\OptionImportRequest;
use App\Exports\OptionGroupExport;
use App\Exports\ErrorExport;
use App\Imports\OptionGroupImport;
use App\Models\ProductOption;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Excel;
use DB;

class OptionController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::OPTIONS)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getListing(Request $request)
    {
        $data = [];
        $data['options'] = Option::getOptions($request->all());

        $data['showEmpty'] = 0;
        if (empty($request['search']) && $data['options']->count() == 0) {
            $data['showEmpty'] = 1;
        }
        return jsonresponse(true, null, $data);
    }

    public function store(OptionRequest $request)
    {
        if (!canWrite(AdminRole::OPTIONS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $requestData = $request->all();
        $requestData['option_type'] = !empty($requestData['option_type']) ? $requestData['option_type'] : '0';
        $requestData['option_has_sizechart'] = !empty($requestData['option_has_sizechart'])? 1 :'0';
        $requestData['option_created_by_id'] = $requestData['option_updated_by_id'] = $this->admin->admin_id;
        $requestData['option_created_at'] = $requestData['option_updated_at'] = Carbon::now();
        $status = Option::insert($requestData);
        return jsonresponse($status, __('NOTI_OPTION_GROUP_CREATED'));
    }

    public function update(OptionRequest $request, $id)
    {
        if (!canWrite(AdminRole::OPTIONS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $requestData = $request->except(['_method']);
        $requestData['option_type'] = !empty($requestData['option_type'])? $requestData['option_type'] :'0';
        $requestData['option_has_sizechart'] = !empty($requestData['option_has_sizechart'])? 1 :'0';
        $requestData['option_updated_by_id'] = $this->admin->admin_id;
        $requestData['option_updated_at'] = Carbon::now();
        Option::where('option_id', $id)->update($requestData);
        return jsonresponse(true, __('NOTI_OPTION_GROUP_UPDATED'));
    }

    public function destroy($id)
    {
        if (!canWrite(AdminRole::OPTIONS, 0, true)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        if(ProductOption::where('poption_option_id', $id)->count() > 0) {
            return jsonresponse(false, __('NOTI_OPTION_GROUP_NOT_DELETED'), []);
        }
        Option::where('option_id', $id)->delete();
        return jsonresponse(true, __('NOTI_OPTION_GROUP_DELETED'));
    }

    public function export(Request $request)
    {
        return Excel::download(new OptionGroupExport, 'optionGroup.xlsx');
    }

    public function import(OptionImportRequest $request)
    {
        clearStorageFolder('public/excel/');
        $option = new OptionGroupImport();
        $sheetHeadings = (new HeadingRowImport)->toArray($request->file('file'));
        $optionHeading = ['sno','option_name', 'option_is_color', 'option_has_image', 'option_has_sizechart'];
        foreach ($optionHeading as $heading) {
            if (!in_array($heading, $sheetHeadings[0][0])) {
                return jsonresponse(false, __('NOTI_IMPORT_CORRECT_FILE'));
            }
        }
        Excel::import($option, $request->file('file'));
        $error = $option->getErrors();
        if (isset($error) && !empty($error)) {
            $export = new ErrorExport($error);
            $name = 'option-error-' . time() . '.xls';
            Excel::store($export, 'public/excel/'.$name);
            return jsonresponse(true, __('NOTI_OPTIONS_IMPORTED'), url('storage/excel/'.$name));
        } else {
            return jsonresponse(true, __('NOTI_OPTIONS_IMPORTED'));
        }
    }
    
    /**
     * Option Export to the excel sheet
     *
     **/

    public function optionForExcel(Request $request)
    {
        $option = Option::select('option_id', 'option_name', DB::raw("(CASE WHEN (option_type = 1) THEN 'Yes' ELSE 'No' END) as option_type"), DB::raw("(CASE WHEN (option_has_image = 1) THEN 'Yes' ELSE 'No' END) as option_has_image"), DB::raw("(CASE WHEN (option_has_sizechart = 1) THEN 'Yes' ELSE 'No' END) as option_has_sizechart"));

        if (!empty($request->input('option'))) {
            $option->where('option_name', 'LIKE', '%' . trim($request->input('option')) . '%');
        }

        $data['option'] = $option->get()->toArray();
        $data['maxId'] = Option::max('option_id');
        return jsonresponse(true, '', $data);
    }

    public function optionExcelUpdate(Request $request)
    {
        $optionError = [];
        $optionData  = [];
        if (!empty($request->all())) {
            foreach ($request->all() as $key => $option) {
                if ( empty($option['option_name'])  && empty($option['option_type']) && empty($option['option_has_image']) && empty($option['option_has_sizechart']) ) {
                    continue;
                }
                $validator = Validator::make($option, [ 'option_name' => 'required|unique:options,option_name,'. $option['option_id'] . ',option_id']);
                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $message) {
                        $optionError[$key]['key'] = $key + 1;
                        $optionError[$key]['message'] = $message;
                    }
                    array_push($optionData, $option);
                    continue;
                }
                if (isset($option['option_id']) && !empty($option['option_id'])) {
                    Option::updateOrCreate(
                        [
                            'option_name' => $option['option_name'],
                            'option_type' => (Arr::exists($option, 'option_type') &&  Str::lower($option['option_type']) == 'yes') ? 1: 0,
                            'option_has_image' => (Arr::exists($option, 'option_has_image') &&  Str::lower($option['option_has_image']) == 'yes') ? 1: 0,
                            'option_has_sizechart' => (Arr::exists($option, 'option_has_sizechart') &&  Str::lower($option['option_has_sizechart']) == 'yes') ? 1: 0
                        ]
                    );
                }
            }
            if (isset($optionError) && !empty($optionError)) {
                $export = new ErrorExport($optionError);
                $name = 'option-error-' . time() . '.xls';
                Excel::store($export, 'public/excel/' . $name);
                $data['optionError'] = url('storage/excel/'.$name);
            }
            $data['optionData'] = $optionData;
        }
        return jsonresponse(true, __('NOTI_OPTIONS_UPDATED'), $data);
    }
    
    public function getLastId(Request $request)
    {
        return jsonresponse(true, '', Option::max('option_id'));
    }
}
