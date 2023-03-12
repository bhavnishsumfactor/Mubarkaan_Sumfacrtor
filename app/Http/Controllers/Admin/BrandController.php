<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\UrlRewrite;
use App\Models\Admin\AdminRole;
use App\Http\Controllers\Admin\AdminYokartController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests\BrandRequest;
use App\Http\Requests\BrandImportRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\DiscountCouponRecord;
use App\Models\DiscountCoupon;
use App\Exports\BrandsExport;
use App\Imports\BrandsImport;
use App\Exports\ErrorExport;
use Maatwebsite\Excel\HeadingRowImport;
use Excel;
use Auth;
use DB;

class BrandController extends AdminYokartController
{
    private $exceptFunction = ['export', 'import', 'getBrandForExcel', 'brandExcelUpdate', 'getLastId'];

    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::BRANDS)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        })->except($this->exceptFunction);
    }

    public function getListing(Request $request)
    {
        $data = [];
        $data['brands'] = Brand::getBrands($request->all());
        $data['showEmpty'] = 0;
        if (empty($request['search']) && $data['brands']->count() == 0) {
            $data['showEmpty'] = 1;
        }
        return jsonresponse(true, null, $data);
    }

    public function store(BrandRequest $request)
    {
        if (!canWrite(AdminRole::BRANDS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $insertedId = Brand::create([
            'brand_name' => $request->input('brand_name'),
            'brand_publish' => config('constants.YES'),
            'brand_created_by_id' => $this->admin->admin_id,
            'brand_updated_by_id' => $this->admin->admin_id,
            'brand_created_at' => Carbon::now(),
            'brand_updated_at' => Carbon::now()
        ])->brand_id;
        $brandData[0]['id'] = $insertedId;
        $brandData[0]['label'] = $request->input('brand_name');
        imageUpload($request, $insertedId, 'brandLogo');
        UrlRewrite::saveUrl('brands', $insertedId);
        return jsonresponse(true, __('NOTI_BRAND_CREATED'), ['id' => $insertedId, 'brand' => $brandData ]);
    }

    public function update(BrandRequest $request, $id)
    {
        if (!canWrite(AdminRole::BRANDS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        Brand::find($id)->update([
            'brand_name' => $request->input('brand_name'),
            'brand_updated_by_id' => $this->admin->admin_id,
            'brand_updated_at' => Carbon::now()
        ]);
        imageUpload($request, $id, 'brandLogo');
        return jsonresponse(true, __('NOTI_BRAND_UPDATED'));
    }

    public function updateStatus(Request $request, $id)
    {
        if (!canWrite(AdminRole::BRANDS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        Brand::find($id)->update(['brand_publish' => ($request->input('status') == 'true') ? 1 : 0, 'brand_updated_by_id' => $this->admin->admin_id, 'brand_updated_at'=> Carbon::now() ]);
        return jsonresponse(true, ($request->input('status') =='true') ? __('NOTI_BRAND_PUBLISHED') : __('NOTI_BRAND_UNPUBLISHED'));
    }

    public function destroy($id)
    {
        if (!canWrite(AdminRole::BRANDS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        Brand::destroy($id);
        UrlRewrite::removeUrl('brands', $id);
        $discountCoupanId = DiscountCouponRecord::select('dcr_discountcpn_id')->where('dcr_type', DiscountCouponRecord::DISCOUNT_RECORD_BRAND_TYPE)->where('dcr_record_id', $id)->get()->first();
        if (!empty($discountCoupanId)) {
            if (DiscountCouponRecord::where('dcr_discountcpn_id', $discountCoupanId->dcr_discountcpn_id)->count() == 1) {
                DiscountCoupon::where('discountcpn_id', $discountCoupanId->dcr_discountcpn_id)
                ->update(['discountcpn_publish' => 0]);
                $discountCoupanId->delete();
            }
        }
        return jsonresponse(true, __('NOTI_BRAND_DELETED'));
    }

    public function export(Request $request)
    {
        if (!canRead(AdminRole::IMPORT_EXPORT)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        return Excel::download(new BrandsExport, 'brands-' . time() . '.xlsx');
    }

    public function import(BrandImportRequest $request)
    {
        if (!canWrite(AdminRole::IMPORT_EXPORT)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        clearStorageFolder('public/excel/');
        $brand = new BrandsImport();
        $sheetHeadings = (new HeadingRowImport)->toArray($request->file('file'));
        $brandsHeading = ['sno', 'brand_name', 'publish'];
        foreach ($brandsHeading as $heading) {
            if (!in_array($heading, $sheetHeadings[0][0])) {
                return jsonresponse(false, __('NOTI_IMPORT_CORRECT_FILE'));
            }
        }
        Excel::import($brand, $request->file('file'));
        $error = $brand->getErrors();
        if (isset($error) && !empty($error)) {
            $export = new ErrorExport($error);
            $name = 'brand-error-' . time() . '.xls';
            Excel::store($export, 'public/excel/' . $name);
            return jsonresponse(true, __('NOTI_BRANDS_IMPORTED'), url('storage/excel/' . $name));
        } else {
            return jsonresponse(true, __('NOTI_BRANDS_IMPORTED'));
        }
    }

    public function getBrandForExcel(Request $request)
    {
        if (!canRead(AdminRole::IMPORT_EXPORT)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $brand = Brand::select('brand_id', 'brand_name', DB::raw("(CASE WHEN (brand_publish = 1) THEN 'Yes' ELSE 'No' END) as brand_publish"));
        if (!empty($request->input('brand'))) {
            $brand->where('brand_name', 'LIKE', '%' . trim($request->input('brand')) . '%');
        }
        return jsonresponse(true, '', $brand->get()->toArray());
    }

    public function brandExcelUpdate(Request $request)
    {
        if (!canWrite(AdminRole::IMPORT_EXPORT)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $brandError = [];
        $brandData = [];
        if (!empty($request->all())) {
            foreach ($request->all() as $key => $brand) {
                if (empty($brand['brand_name']) && empty($brand['brand_publish'])) {
                    continue;
                }
                $validator = Validator::make($brand, [ 'brand_name' => 'required|unique:brands,brand_name,'. $brand['brand_id'] . ',brand_id' ]);
                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $message) {
                        $brandError[$key]['key'] = $brand['brand_id'];
                        $brandError[$key]['message'] = $message;
                    }
                    array_push($brandData, $brand);
                    continue;
                }
                if (isset($brand['brand_id']) && !empty($brand['brand_id'])) {
                    Brand::updateOrCreate(
                        ['brand_id' => $brand['brand_id'] ],
                        ['brand_publish' => ($brand['brand_publish'] == 'Yes' || $brand['brand_publish'] == 'yes') ? 1 : 0,
                        'brand_updated_at' => Carbon::now(), 'brand_name'=> $brand['brand_name'] ]
                    );
                    if (UrlRewrite::checkUrlExists(UrlRewrite::BRAND_TYPE, $brand['brand_id']) == 0) {
                        UrlRewrite::saveUrl('brands', $brand['brand_id']);
                    }
                }
            }
            if (isset($brandError) && !empty($brandError)) {
                $export = new ErrorExport($brandError);
                $name = 'brand-error-' . time() . '.xls';
                Excel::store($export, 'public/excel/' . $name);
                $data['brandError'] = url('storage/excel/'.$name);
            }
            $data['brandData'] = $brandData;
        }
        return jsonresponse(true, __('NOTI_BRANDS_UPDATED'), $data);
    }

    public function getLastId(Request $request)
    {
        return jsonresponse(true, '', Brand::max('brand_id'));
    }
}
