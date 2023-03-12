<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\DiscountCoupon;
use App\Models\DiscountCouponRecord;
use App\Models\Product;
use App\Models\Admin\AdminRole;
use App\Models\ProductCategory;
use App\Models\ProductOptionVarient;
use App\Models\Brand;
use App\Models\User;
use App\Models\Timezone;
use Carbon\Carbon;
use App\Http\Requests\DiscountCouponRequest;
use Illuminate\Support\Facades\Validator;
use DB;

class DiscountCouponController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::DISCOUNT_COUPONS)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }
    public function getListing(Request $request)
    {
        $data = [];
        $data['coupons'] = DiscountCoupon::getDiscountCoupons($request->all());
        $categories = ProductCategory::getParentList();
        if (!empty($categories) && count($categories) > 0) {
            $categories =  ProductCategory::buildTree($categories);
        }
        
        $data['categories'] = $categories;
        $data['operators'] = DiscountCoupon::OPERATOR_CONDITIONS;
        $data['available_coupons'] = DiscountCoupon::AVAILABLE_DISCOUNT;
        
        $data['showEmpty'] = 0;
        $request['type'] = 'all';
        if (empty($request['search']) && DiscountCoupon::getDiscountCoupons($request->all())->count() == 0) {
            $data['showEmpty'] = 1;
        }
        return jsonresponse(true, null, $data);
    }

    public function getRecords(Request $request)
    {
        $records = [];
        switch ($request->input('type')) {
            case 'products':
                $query = Product::with(['varients' => function ($sql) {
                    $sql->where('pov_publish', 1)
                    ->select('pov_code', 'pov_display_title', 'pov_prod_id');
                }])->select('prod_id as record_id', 'prod_id', 'prod_name as record_title');
                if (!empty($request['query'])) {
                    $query->where('prod_name', 'like', '%'.$request['query'].'%');
                }
                $records = $query->limit(5)->get();
                break;
            case 'categories':
                $query = ProductCategory::select('cat_id as record_id', 'cat_name as record_title');
                if (!empty($request['query'])) {
                    $query->where('cat_name', 'like', '%'.$request['query'].'%');
                }
                $records = $query->limit(5)->get();
                break;
            case 'brands':
                $query = Brand::select('brand_id as record_id', 'brand_name as record_title');
                if (!empty($request['query'])) {
                    $query->where('brand_name', 'like', '%'.$request['query'].'%');
                }
                $records = $query->limit(5)->get();
                break;
            case 'users':
                $query = User::select('user_id as record_id', DB::raw('CONCAT(user_fname, " ", user_lname) as record_title'));
                if (!empty($request['query'])) {
                    $query->where(DB::raw('CONCAT(user_fname, " ", user_lname)'), 'like', '%'.$request['query'].'%');
                }
                $records = $query->limit(5)->get();
                break;
            default:
                break;
        }
        return jsonresponse(true, null, $records);
    }

    public function getIncluded(Request $request)
    {
        $data = [];
        $data = DiscountCoupon::getIncluded($request->input('id'));  
        // $data['products'] = DiscountCouponRecord::select('prod_id as record_id', 'prod_name as record_title', 'pov_display_title', 'pov_code')
        // ->where('dcr_type', 1)->where('dcr_discountcpn_id', $request->input('id'))
        // ->leftJoin('products', 'products.prod_id', 'dcr_record_id')
        // ->leftJoin('product_option_varients as varients', 'varients.pov_code', 'dcr_subrecord_id')
        // ->get();
        // $data['categories'] = DiscountCouponRecord::select('cat_id as record_id', 'cat_name as record_title')
        // ->where('dcr_type', 2)->where('dcr_discountcpn_id', $request->input('id'))
        // ->leftJoin('product_categories', 'product_categories.cat_id', 'dcr_record_id')->get();
        // $data['brands'] = DiscountCouponRecord::select('brand_id as record_id', 'brand_name as record_title')
        // ->where('dcr_type', 3)->where('dcr_discountcpn_id', $request->input('id'))
        // ->leftJoin('brands', 'brands.brand_id', 'dcr_record_id')->get();
        // $data['users'] = DiscountCouponRecord::select('user_id as record_id', DB::raw('CONCAT(user_fname, " ", user_lname) as record_title'))
        // ->where('dcr_type', 4)->where('dcr_discountcpn_id', $request->input('id'))
        // ->leftJoin('users', 'users.user_id', 'dcr_record_id')->get();
        return jsonresponse(true, null, $data);
    }

    public function store(DiscountCouponRequest $request)
    {
        if (!canWrite(AdminRole::DISCOUNT_COUPONS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        return $this->saveAndUpdateRecords($request);
    }

    public function update(DiscountCouponRequest $request, $id)
    {
        if (!canWrite(AdminRole::DISCOUNT_COUPONS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        return $this->saveAndUpdateRecords($request, $id);
    }
    public function saveAndUpdateRecords($request, $id = '')
    {
        DB::beginTransaction();
        try {
            $requestData['discountcpn_code'] = $request->input('discountcpn_code');
            $requestData['discountcpn_in_percent'] = (int) $request->input('discountcpn_in_percent');
            $requestData['discountcpn_type'] = (int) $request->input('discountcpn_type');
            $requestData['discountcpn_total_uses'] = (int) $request->input('discountcpn_total_uses');
            $requestData['discountcpn_uses_per_user'] = (int) $request->input('discountcpn_uses_per_user');
            $requestData['discountcpn_maxamt_limit'] = $request->input('discountcpn_maxamt_limit');
            $requestData['discountcpn_minorderamt'] = (!empty($request->input('discountcpn_minorderamt')) || $request->input('discountcpn_minorderamt') == 0) ? $request->input('discountcpn_minorderamt') : '';
            $requestData['discountcpn_amount'] = $request->input('discountcpn_amount');
            $requestData['discountcpn_operator'] = $request->input('discountcpn_operator') ?? 1;
            $requestData['discountcpn_for_guest'] = $request->input('discountcpn_for_guest');
            $requestData['discountcpn_startdate'] = Timezone::getAdminUtcTime($request->input('discountcpn_startdate'));
            $requestData['discountcpn_enddate'] = Timezone::getAdminUtcTime($request->input('discountcpn_enddate'));
            $requestData['discountcpn_created_at']  =  $requestData['discountcpn_updated_at']  =  Carbon::now();
            if (!empty($id)) {
                $requestData['discountcpn_updated_at']  =  Carbon::now();
                $requestData['discountcpn_updated_by_id']  = $this->admin->admin_id;
                DiscountCoupon::where('discountcpn_id', $id)->update($requestData);
                DiscountCouponRecord::where('dcr_discountcpn_id', $id)->delete();
                $insertedId = $id;
                $message = __('NOTI_COUPON_UPDATED');
            } else {
                $requestData['discountcpn_created_at']     =  $requestData['discountcpn_updated_at']     =   Carbon::now();
                $requestData['discountcpn_created_by_id']  =  $requestData['discountcpn_updated_by_id']  = $this->admin->admin_id;
                $insertedId = DiscountCoupon::create($requestData)->discountcpn_id;
                $message = __('NOTI_COUPON_CREATED');
            }
         
            $productsData = json_decode($request->input('products'));
            if (!empty($productsData)) {
                foreach ($productsData as $productId) {
                    $variants = ProductOptionVarient::where('pov_code', (string) $productId)->first();
                    $subRecordId = 0;
                    if (!empty($variants)) {
                        $productId = $variants->pov_prod_id;
                        $subRecordId = $variants->pov_code;
                    }
                    DiscountCouponRecord::create([
                          'dcr_type' => 1,
                          'dcr_discountcpn_id' => $insertedId,
                          'dcr_record_id' => $productId,
                          'dcr_subrecord_id' => $subRecordId
                        ]);
                }
            }
            $categoriesData = json_decode($request->input('categories'));
            if (!empty($categoriesData)) {
                foreach ($categoriesData as $record) {
                    DiscountCouponRecord::create([
                        'dcr_type' => 2,
                        'dcr_discountcpn_id' => $insertedId,
                        'dcr_record_id' => $record,
                        'dcr_subrecord_id' => 0
                    ]);
                }
            }
            $brandsData = json_decode($request->input('brands'));
            if (!empty($brandsData)) {
                foreach ($brandsData as $record) {
                    DiscountCouponRecord::create([
                          'dcr_type' => 3,
                          'dcr_discountcpn_id' => $insertedId,
                          'dcr_record_id' => $record->id,
                          'dcr_subrecord_id' => 0
                        ]);
                }
            }
            $usersData = json_decode($request->input('users'));
            if (!empty($usersData)) {
                foreach ($usersData as $record) {
                    DiscountCouponRecord::create([
                          'dcr_type' => 4,
                          'dcr_discountcpn_id' => $insertedId,
                          'dcr_record_id' => $record->id,
                          'dcr_subrecord_id' => 0
                        ]);
                }
            }
            imageUpload($request, $insertedId, 'discountCouponImage');
            DB::commit();
            return jsonresponse(true, $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
        }
    }
    public function destroy($id)
    {
        if (!canWrite(AdminRole::DISCOUNT_COUPONS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        DiscountCoupon::find($id)->delete();
        DiscountCouponRecord::where('dcr_discountcpn_id', $id)->delete();
        return jsonresponse(true, __('NOTI_COUPON_DELETED'));
    }

    public function updateStatus(Request $request, $discountcpn_id)
    {
        if (!canWrite(AdminRole::DISCOUNT_COUPONS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $publishStatus = (!empty($request->input('status')) && $request->input('status') == 'true') ? 1 : 0;
        DiscountCoupon::where('discountcpn_id', $discountcpn_id)->update(['discountcpn_publish' => $publishStatus, 'discountcpn_updated_at' => Carbon::now(), 'discountcpn_updated_by_id' => $this->admin->admin_id ]);
        $message = (!empty($request->input('status')) && $request->input('status') == 'true') ? __('NOTI_COUPON_PUBLISHED') : __('NOTI_COUPON_UNPUBLISHED');
        return jsonresponse(true, $message);
    }

    public function checkDiscountCoupon(Request $request, $id = '')
    {
        $this->validation($request->all(), $id)->validate();
        return jsonresponse(true, 'no error');
    }

    protected function validation(array $data, $recordId)
    {
        $uniqueCodeRule = '';
        if (is_numeric($recordId)) {
            $uniqueCodeRule .= ','.$recordId.',discountcpn_id';
        }
        $validator = Validator::make($data, [
            'discountcpn_code' => 'required|max:15|unique:discount_coupons,discountcpn_code'.$uniqueCodeRule
        ]);
        return $validator->setAttributeNames([
            'discountcpn_code' => 'Code'
        ]);
    }
}
