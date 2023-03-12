<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\SpecialPrice;
use App\Models\SpecialPriceInclude;
use App\Models\SpecialPriceExclude;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Brand;
use App\Models\Timezone;
use App\Models\ProductOptionVarient;
use App\Models\Admin\AdminRole;
use Carbon\Carbon;
use App\Http\Requests\SpecialPriceRequest;
use DB;

class SpecialPriceController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::SPECIAL_PRICES)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getListing(Request $request)
    {
        $data = [];
        $data['specialprices'] = SpecialPrice::getSpecialprices($request->all());
        $data['types'] = SpecialPrice::getSpecialpriceTypes();
        $data['includes'] = SpecialPrice::getSpecialpriceIncludes();
        $categories = ProductCategory::getParentList();
        if (!empty($categories) && count($categories) > 0) {
            $categories =  ProductCategory::buildTree($categories);
        }
        
        $data['showEmpty'] = 0;
        $request['type'] = 'all';
        if (empty($request['search']) && SpecialPrice::getSpecialprices($request->all())->count() == 0) {
            $data['showEmpty'] = 1;
        }

        $data['categories'] = $categories;
        return jsonresponse(true, null, $data);
    }

    public function getRecords(Request $request)
    {
        $records = [];
        switch ($request->input('include')) {
            case 1:
                $query = Product::with(['varients' => function ($sql) {
                    $sql->where('pov_publish', 1)
                    ->select('pov_code', 'pov_display_title', 'pov_prod_id');
                }])->selectRaw("prod_id as record_id,prod_id, prod_name as record_title");
                if (!empty($request['query'])) {
                    $query->whereRaw("REPLACE(prod_name, ' ', '') LIKE ?", ['%' . cleanSpaces($request['query']) . '%']);
                }
                
                if (!empty($request['type']) && !empty($request['selected'])) {
                    // restrict data for exclude field based on selected categories/brands
                    $selectedIncludes = json_decode($request->input('selected'));
                    // dd($selectedIncludes);
                    $includes = array_map(function ($key, $value) {
                        return $value;
                    }, array_keys($selectedIncludes), $selectedIncludes);
                    
                    if (!empty($includes)) {
                        if ($request['type'] == 2) {
                            $query->whereIn('ptc.ptc_cat_id', $includes)
                            ->join('product_to_categories as ptc', 'ptc.ptc_prod_id', 'prod_id');
                        } elseif ($request['type'] == 3) {
                            $query->whereIn('prod_brand_id', $includes);
                        }
                    }
                }
                $records = $query->limit(50)->get();
                break;
            case 2:
                // $query = ProductCategory::select('cat_id as record_id', 'cat_name as record_title');
                // if (!empty($request['query'])) {
                //     $query->where('cat_name', 'like', '%' . $request['query'] . '%');
                // }
                // $records = $query->limit(5)->get();
                break;
            case 3:
                $query = Brand::select('brand_id as record_id', 'brand_name as record_title');
                if (!empty($request['query'])) {
                    $query->where('brand_name', 'like', '%' . $request['query'] . '%');
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
        $query = SpecialPriceInclude::where('spi_splprice_id', $request->input('id'));
        if ($request->input('type') == 1) {
            $included = $query->select('prod_id as record_id', 'prod_name as record_title', 'pov_display_title', 'pov_code')
            ->leftJoin('products', 'products.prod_id', 'spi_record_id')
            ->leftJoin('product_option_varients as varients', 'varients.pov_code', 'spi_subrecord_id')->get();
        } elseif ($request->input('type') == 2) {
            $included = $query->leftJoin('product_categories', 'product_categories.cat_id', 'spi_record_id')->get()->pluck('cat_id')->toArray();
        } else {
            $included = $query->select('brand_id as record_id', 'brand_name as record_title')
            ->leftJoin('brands', 'brands.brand_id', 'spi_record_id')->get();
        }
        return jsonresponse(true, null, $included);
    }

    public function store(SpecialPriceRequest $request)
    {
        if (!canWrite(AdminRole::SPECIAL_PRICES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }

        return $this->saveAndUpdateRecords($request);
    }
    
    public function saveAndUpdateRecords($request, $id = '')
    {
        DB::beginTransaction();
        try {
            $requestData['splprice_name'] = $request->input('splprice_name');
            $requestData['splprice_type'] = (int) $request->input('splprice_type');
            $requestData['splprice_amount'] = $request->input('splprice_amount');
            if($requestData['splprice_type'] == 2 && $requestData['splprice_amount'] > 100){
                 return jsonresponse(false, __('In case of percentage, Amount must be less than 100'));
            }
           
            $requestData['splprice_startdate'] = Timezone::getUtcDateTimeForAdmin($request->input('splprice_startdate'));
            $requestData['splprice_enddate'] = Timezone::getUtcDateTimeForAdmin($request->input('splprice_enddate'));
            $requestData['splprice_include'] = (int) $request->input('splprice_include');
            if (!empty($id)) {
                $requestData['splprice_updated_at']  =  Carbon::now();
                $requestData['splprice_updated_by_id']  = $this->admin->admin_id;
                SpecialPrice::where('splprice_id', $id)->update($requestData);
                
                SpecialPriceInclude::where('spi_splprice_id', $id)->delete();
                SpecialPriceExclude::where('spe_splprice_id', $id)->delete();
                $insertedId = $id;
            } else {
                $requestData['splprice_created_at']  =  $requestData['splprice_updated_at']  =  Carbon::now();
                $requestData['splprice_created_by_id']  =  $requestData['splprice_updated_by_id']  = $this->admin->admin_id;
                $insertedId = SpecialPrice::create($requestData)->splprice_id;
            }

            $includesData = json_decode($request->input('splprice_includes'));
            
            if (!empty($includesData) && $request->input('splprice_include') != 1) {
                foreach ($includesData as $include) {
                    $saveData = [
                        'spi_splprice_id' => $insertedId,
                        'spi_subrecord_id' => config('constants.NO'),
                    ];
                    $saveData['spi_record_id'] = ($request->input('splprice_include') == 2) ? $include : $include->id;
                    SpecialPriceInclude::create($saveData);
                }
            }

            $productData = json_decode($request->input('productValues'));

            if (!empty($productData)) {
                foreach ($productData as $productId) {
                    $variants = ProductOptionVarient::where('pov_code', (string) $productId)->first();
                    $subRecordId = 0;
                    if (!empty($variants)) {
                        $productId = $variants->pov_prod_id;
                        $subRecordId = $variants->pov_code;
                    }
                 
                    if ($request->input('splprice_include') == 1) {
                        SpecialPriceInclude::create([
                            'spi_splprice_id' => $insertedId,
                            'spi_record_id' => $productId,
                            'spi_subrecord_id' => $subRecordId
                        ]);
                    } else {
                        SpecialPriceExclude::create([
                            'spe_splprice_id' => $insertedId,
                            'spe_record_id' => $productId,
                            'spe_subrecord_id' => $subRecordId
                        ]);
                    }
                }
            }
            DB::commit();
            return jsonresponse(true, __('NOTI_SPECIALPRICE_CREATED'));
        } catch (\Exception $e) {
            DB::rollBack();
            return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
        }
    }
    public function update(SpecialPriceRequest $request, $id)
    {
        if (!canWrite(AdminRole::SPECIAL_PRICES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        return $this->saveAndUpdateRecords($request, $id);
    }

    public function destroy($id)
    {
        if (!canWrite(AdminRole::SPECIAL_PRICES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        SpecialPrice::find($id)->delete();
        SpecialPriceInclude::where('spi_splprice_id', $id)->delete();
        SpecialPriceExclude::where('spe_splprice_id', $id)->delete();
        return jsonresponse(true, __('NOTI_SPECIALPRICE_DELETED'));
    }

    public function updateStatus(Request $request, $splprice_id)
    {
        if (!canWrite(AdminRole::SPECIAL_PRICES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $publishStatus = (!empty($request->input('status')) && $request->input('status') == 'true') ? 1 : 0;
        SpecialPrice::where('splprice_id', $splprice_id)->update(['splprice_publish' => $publishStatus, 'splprice_updated_at' => Carbon::now(), 'splprice_updated_by_id' => $this->admin->admin_id ]);
        $message = (!empty($request->input('status')) && $request->input('status') == 'true') ?
                    __('NOTI_SPECIALPRICE_PUBLISHED') : __('NOTI_SPECIALPRICE_UNPUBLISHED');
        return jsonresponse(true, $message);
    }
}
