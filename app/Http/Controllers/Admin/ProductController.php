<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\ProductToCategory;
use App\Models\ProductContent;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\Brand;
use App\Models\Country;
use App\Models\TaxGroup;
use App\Models\ShippingBoxPackage;
use App\Models\Option;
use App\Models\ProductSpecification;
use App\Models\ProductOption;
use App\Models\ProductOptionVarient;
use App\Models\Admin\AdminRole;
use Carbon\Carbon;
use App\Helpers\FileHelper;
use App\Models\AttachedFile;
use App\Models\DigitalFileRecord;
use App\Models\OptionToVarient;
use App\Models\UrlRewrite;
use App\Models\Configuration;
use App\Models\DiscountCouponRecord;
use App\Models\DiscountCoupon;
use App\Models\ProductOptionName;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Str;
use App\Exports\ProductExport;
use App\Imports\ProductImport;
use App\Exports\DigitalProductExport;
use App\Imports\DigitalProductImport;
use Illuminate\Validation\Rule;
use App\Exports\ErrorExport;
use Excel;
use DB;
use Auth;

class ProductController extends AdminYokartController
{
    private $exceptFunction = ['export', 'import', 'exportDigitalProduct', 'importDigitalProduct', 'digitalProductExcelUpdate', 'getDigitalProductForExcel', 'getPhysicalProductForExcel', 'physicalProductExcelUpdate', 'getLastId'];

    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::PRODUCTS)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        })->except($this->exceptFunction);
    }

    public function getListing(Request $request)
    {
        $configuration = getConfigValues(['PRODUCT_TYPES', 'BRAND_IS_OPTIONAL']);
        $digitalEnable = $configuration['PRODUCT_TYPES'];
        if ($request->input('loaded-page') == 0) {
            $alertRecords = Product::getOutOfStockProducts();
            $errorRecords = Product::getIncompleteProducts($digitalEnable);
        } else {
            $alertRecords = $request->input('alert-records');
            $errorRecords = $request->input('error-records');
        }

        if ($request->input('records-type') == 1 && $alertRecords != 0) {
            $records = Product::getAlertProducts($request->all());
        } elseif ($request->input('records-type') == 2 && $errorRecords != 0) {
            $records = Product::getErrorsProducts($request->all(), $digitalEnable);
        } else {
            $records = Product::getProducts($request->all());
        }
        $searchName = '';
        if (!empty($request['category_id']) && count($records) == 0) {
            $searchName = ProductCategory::select('cat_name as record_name')->where('cat_id', $request['category_id'])->first();
        }
        if (!empty($request['brand_id']) && count($records) == 0) {
            $searchName = Brand::select('brand_name as record_name')->where('brand_id', $request['brand_id'])->first();
        }
        if(!empty( $searchName)){
            $searchName =  $searchName->record_name;
        }
        $types = Product::PRODUCT_TYPES;
        $brandRequired =  $configuration['BRAND_IS_OPTIONAL'];
        return jsonresponse(true, null, compact('records', 'types', 'alertRecords', 'brandRequired', 'errorRecords', 'digitalEnable','searchName'));
    }

    public function pageLoadData(Request $request)
    {
        $tab = $request->input('tab');
        $prodId = $request->input('prod_id');
        switch ($tab) {
            case 'general':
                $records['taxGroups'] = TaxGroup::get();
                //$brands = Brand::getActiveBrandDropdown();
                $records['productTypes'] = Product::getTypes();
                $records['brandOption'] = getConfigValueByName('BRAND_IS_OPTIONAL');
                $records['productConditions'] = Product::PRODUCT_CONDITIONS;
                $categories = ProductCategory::getParentList();
                if (!empty($categories) && count($categories) > 0) {
                    $categories =  ProductCategory::buildTree($categories);
                }
                $allCategoriesWithRoot = $categories;
                //array_unshift($brands, ['id' => 0, 'label' => 'Select one', 'isDisabled' => true]);
                array_unshift($categories, ['id' => 0, 'label' => 'Select one', 'isDisabled' => true]);
                array_unshift($allCategoriesWithRoot, ['id' => 0, 'label' => 'root']);
                //$records['brands'] = $brands;
                $records['prodCategories'] = $categories;
                $records['allCategories'] = $allCategoriesWithRoot;
                $records['editInfo'] = Product::generalInfoById($prodId);
                $brands = [];
                if (!empty($records['editInfo'])) {
                    $brands = Product::getBrand($records['editInfo']['prod_brand_id']);
                }
                $records['brand'] = $brands;
                $records['top_brands'] = Brand::getBrandData(['brand_name as label', 'brand_id as id'], [], 5);
                break;
            case 'price':
                $configRecords = Configuration::getKeyValues(['PICK_UP_ENABLE', 'COD_ENABLE', 'PRODUCT_GIFT_WRAP_ENABLE']);
                $records['pickupStatus'] = $configRecords['PICK_UP_ENABLE'];
                $records['codStatus'] = $configRecords['COD_ENABLE'];
                $records['giftWrapStatus'] = $configRecords['PRODUCT_GIFT_WRAP_ENABLE'];

                $productArray = ['prod_id', 'pc_gift_wrap_available', 'prod_type', 'prod_cost', 'prod_self_pickup', 'prod_stock_out_sellable', 'prod_cod_available', 'prod_min_order_qty', 'prod_max_order_qty', 'pc_threshold_stock_level', 'pc_substract_stock', 'pc_prod_id', 'prod_created_by_id', 'prod_updated_by_id', 'prod_created_on', 'prod_updated_on'];
                $records['editInfo'] = Product::getRecordById($prodId, $productArray);

                break;
            case 'options':
                $records['priceIncTax'] = Configuration::getValue('PRICE_INCLUDING_TAX');
                $records['editInfo'] = $this->optionsRecords($prodId);
                break;
            case 'attributes':
                $records['productWeightTypes'] = ProductContent::getWeightOptions();
                $records['countries'] = Country::pluck('country_name', 'country_id');
                $records['shipping_dimensions'] = ShippingBoxPackage::getDimensionOptions();
                $records['shipping_packages'] = ShippingBoxPackage::all();
                $records['editInfo'] = $this->attributesRecords($prodId);
                $records['enableSizeChart'] = $this->checkProductOptionHasSizeChart($prodId);
                $records['sizeChartUrl'] = $this->getSizeChartUrl($prodId);
                break;
            case 'media':
                $records['published'] = config('constants.YES_OR_NO');
                $records['backgroundOption'] = getConfigValueByName('BACKGROUND_COLOR_ENABLED');
                $records['productMedia'] = Product::getProductMedia($prodId);
                
                $mediaType = getConfigValueByName('MEDIA_TYPES');
                $records['aspectRatio'] = (isset(Product::PRODUCT_MEDIA_SIZE[$mediaType])) ? Product::PRODUCT_MEDIA_SIZE[$mediaType] : 1;
                $records['enableOptions'] = $this->checkProductOptions($prodId);
                $records['ratio'] = (isset(Product::PRODUCT_MEDIA_TYPE[$mediaType])) ? Product::PRODUCT_MEDIA_TYPE[$mediaType] : "1:1";
                $records['pixels'] = (isset(Product::PRODUCT_MEDIA_PIXELS[$mediaType])) ? Product::PRODUCT_MEDIA_PIXELS[$mediaType] : "800x800";
                if ($mediaType == 3) {
                    $width = 800;
                    $height = 1067;
                } elseif ($mediaType == 2) {
                    $width = 800;
                    $height = 450;
                } else {
                    $width = 800;
                    $height = 800;
                }
                $records['width'] = $width;
                $records['height'] = $height;
                $records['editInfo'] = $this->mediaRecords($prodId);
                
                break;
            case 'digital':

                $records['fileTypes'] = DigitalFileRecord::FILE_TYPES;
                $records['varients'] = ProductOptionVarient::getActiveRecordsByProductId($prodId);
                $records['editInfo'] = $this->digitalRecords($prodId);
                break;
        }
        return jsonresponse(true, null, $records);
    }

    public function mediaRecords($prodId)
    {
        $records['productImages'] = AttachedFile::getBulkFiles(AttachedFile::getConstantVal('productImages'), $prodId, 0, true, false);
        $productArray = ['prod_published', 'prod_publish_from', 'pc_video_link', 'pc_prod_id',  'prod_type'];
        $records['product'] = Product::getRecordById($prodId, $productArray);
        return $records;
    }
    public function digitalRecords($prodId)
    {
        $productArray = ['prod_id', 'prod_type', 'pc_max_download_times', 'pc_download_validity_in_days', 'pc_upload_additional_files'];
        $records['product'] = Product::getRecordById($prodId, $productArray);
        $records['uploads'] = DigitalFileRecord::getByRecordId($prodId, DigitalFileRecord::PRODUCT_RECORD_TYPE);
        return $records;
    }
    public function attributesRecords($prodId)
    {
        $productArray = ['prod_from_origin_country_id', 'prod_sbpkg_id', 'pc_isbn', 'pc_hsn', 'pc_sac', 'pc_upc', 'pc_weight', 'pc_weight_unit', 'pc_file_title', 'prod_type'];
        $records['product'] = Product::getRecordById($prodId, $productArray);
        $records['specification'] = ProductSpecification::where('ps_product_id', $prodId)->get();
        return $records;
    }

    public function optionsRecords($prodId)
    {
        $records['options'] = ProductOption::join('product_option_names', 'opn_id', 'poption_opn_id')
            ->select(DB::raw("GROUP_CONCAT(opn_value) as poption_name, poption_option_id"))
            ->where('poption_prod_id', $prodId)->orderBy('poption_id', 'ASC')->groupBy('poption_option_id')->get();
        $product = Product::getRecordById($prodId, ['prod_price', 'prod_type',  'prod_stock', 'pc_sku', 'prod_min_order_qty']);
        if ($product->prod_type == Product::DIGITAL_PRODUCT) {
            $options = Option::where('option_type', '!=', 1)->get();
        } else {
            $options = Option::get();
        }
        $records['optionColor'] = ProductOption::join('product_option_names', 'opn_id', 'poption_opn_id')
            ->where('poption_prod_id', $prodId)->select('opn_color_code', 'opn_value')->whereNotNull('opn_color_code')->get();
        $records['alloptions'] = $options;
        $records['product'] = $product;
        $records['optionVarients'] = ProductOptionVarient::where('pov_prod_id', $prodId)->get();
        return $records;
    }

    public function checkProductOptions($prodId)
    {
        $options = ProductOption::where('poption_prod_id', $prodId)
            ->join('options', 'options.option_id', 'poption_option_id')
            ->where('options.option_has_image', 1)
            ->get();
        if ($options->count() > 0) {
            return true;
        }
        return false;
    }

    public function checkProductOptionHasSizeChart($prodId)
    {
        $options = ProductOption::where('poption_prod_id', $prodId)
            ->join('options', 'options.option_id', 'poption_option_id')
            ->where('options.option_has_sizechart', 1)
            ->get();
        if ($options->count() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $tab)
    {
        if (!canWrite(AdminRole::PRODUCTS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }

        switch ($tab) {
            case 'general':
                $response = $this->generalInfo($request);
                break;
            case 'price':
                $response = $this->priceInfo($request);
                break;
            case 'options':
                $response = $this->optionsInfo($request);
                break;
            case 'attributes':
                $response = $this->attributesInfo($request);
                break;
            case 'media':
                $response = $this->mediaInfo($request);
                break;
            case 'digital':
                $response = $this->digitalInfo($request);
                break;
        }
        return jsonresponse(true, __('NOTI_PRODUCT_SAVED'), $response);
    }


    protected function generalInfoValidate(array $data)
    {
        $bandRequired = '';
        $required = 'required|';
        $integer = 'integer';
        if ($data['prod_type'] == Product::DIGITAL_PRODUCT) {
            $required = '';
            $integer = '';
        }
        if (getConfigValueByName('BRAND_IS_OPTIONAL') == 1) {
            $bandRequired = 'required';
        }


        $validator = Validator::make($data, [
            'prod_type' => 'required',
            'prod_name' => 'required|string|max:191',
            'prod_brand_id' => $bandRequired,
            'prod_cat_id' => 'required',
            'prod_taxcat_id' => 'required',
            'prod_condition' => $required,
            'pc_warranty_age' => $required, $integer,
            'pc_return_age' => $required, $integer
        ]);
        return $validator->setAttributeNames([
            'prod_type' => 'product type',
            'prod_name' => 'product title',
            'prod_brand_id' => 'brand',
            'prod_cat_id' => 'category',
            'prod_taxcat_id' => 'tax',
            'pc_warranty_age' => 'warranty',
            'prod_condition' => 'condition',
            'pc_return_age' => 'return'
        ]);
    }

    protected function generalInfo($request)
    {
        $this->generalInfoValidate($request->all())->validate();
        $productContentArray = $request->only(['pc_warranty_age', 'pc_return_age']);
        $productArray = $request->only(['prod_name', 'prod_type', 'prod_brand_id', 'prod_description', 'prod_model', 'prod_condition', 'prod_taxcat_id']);

        if (is_null($request->input('prod_id'))) {
            $productArray['prod_created_on'] = Carbon::now();
            $productArray['prod_created_by_id'] = $this->admin->admin_id;
        }
        $productArray['prod_updated_on'] = Carbon::now();
        $productArray['prod_updated_by_id'] = $this->admin->admin_id;
        $productArray['prod_model'] = !empty($productArray['prod_model']) ? $productArray['prod_model'] : '';
        $productArray['prod_description'] = !empty($productArray['prod_description']) ? $productArray['prod_description'] : '';
        $productId = $request->input('prod_id');
        if (!empty($productId)) {
            Product::where('prod_id', $productId)->update($productArray);
            ProductContent::where('pc_prod_id', $productId)->update($productContentArray);
            $this->categorySave($productId, $request->input('prod_cat_id'));
            return ['prod_id' => $productId];
        } else {
            $productId = Product::create($productArray)->prod_id;
            $productContentArray['pc_prod_id'] = $productId;
            ProductContent::create($productContentArray);
            $this->categorySave($productId, $request->input('prod_cat_id'));
            UrlRewrite::saveUrl('products', $productId);
            return ['prod_id' => $productId];
        }
    }
    protected function categorySave($productId, $catId)
    {
        $catExist = ProductToCategory::where('ptc_prod_id', $productId)->first();
        if ($catExist) {
            ProductToCategory::where('ptc_prod_id', $productId)->update(['ptc_cat_id' => $catId]);
        } else {
            ProductToCategory::create(['ptc_prod_id' => $productId, 'ptc_cat_id' => $catId]);
        }
    }
    protected function priceInfoValidate(array $data)
    {
        $validator = Validator::make($data, [
            'prod_min_order_qty' => ['required', 'integer'],
        ]);
        return $validator->setAttributeNames([
            'prod_min_order_qty' => 'minimum purchase quantity',
        ]);
    }
    protected function priceInfo($request)
    {
        $this->priceInfoValidate($request->all())->validate();
        $productId = $request->input('prod_id');
        $productArray = $request->only(['prod_cost', 'prod_self_pickup', 'prod_stock_out_sellable', 'prod_cod_available', 'prod_min_order_qty', 'prod_max_order_qty']);
        if (empty($request->input('prod_max_order_qty'))) {
            $productArray['prod_max_order_qty'] = $productArray['prod_cod_available'];
        }
        //$productArray['prod_created_on'] = (!$request->input('prod_id') ?? Carbon::now());
        //$productArray['prod_created_by_id'] = (!$request->input('prod_id') ?? $this->admin->admin_id);
        $productArray['prod_updated_by_id'] = $this->admin->admin_id;
        $productArray['prod_updated_on'] = Carbon::now();
        Product::where('prod_id', $productId)->update($productArray);
        $productContentArray = $request->only(['pc_threshold_stock_level', 'pc_substract_stock', 'pc_gift_wrap_available']);
        ProductContent::where('pc_prod_id', $productId)->update($productContentArray);
        Product::checkIfProductLowStock($productId);

        return ['prod_id' => $productId];
    }
    protected function optionsInfo($request)
    {
        $productId =  $request->input('prod_id');
        $variants = ProductOptionVarient::where('pov_prod_id', $productId)->pluck('pov_id');
        OptionToVarient::whereIn('otv_pov_id', $variants)->delete();
        ProductOptionVarient::whereIn('pov_id', $variants)->delete();

        \Cache::forget('img-' . $productId . '-*');
        if ($request->input('optionEnable') == 0) {
            ProductOption::where('poption_prod_id', $productId)->delete();
            Product::where('prod_id', $productId)->update(['prod_price' => $request->input('prod_price'), 'prod_stock' => $request->input('prod_stock')]);
            $request['pc_sku'] = !empty($request->input('pc_sku')) ? $request->input('pc_sku') : '';
            ProductContent::where('pc_prod_id', $productId)->update(['pc_sku' => $request['pc_sku']]);
            return ['prod_id' => $productId];
        }

        $variantCodes =  json_decode($request->input('variantCode'), true);
        $optionsValue =  json_decode($request->input('optionsValue'), true);
        $optionsColors =  json_decode($request->input('optionsColors'), true);

        $optionSelect =  explode(',', $request->input('optionSelect'));

        $variantPrice =  explode(',', $request->input('variantPrice'));
        $variantQuantity =  explode(',', $request->input('variantQuantity'));
        $variantSKU =  explode(',', $request->input('variantSKU'));
        $variantPublish =  explode(',', $request->input('variantPublish'));
        $variantDefault =  $request->input('variantDefault');

       
        $variantDisplayCode = [];
        $optionIds = [];
        $variantDisplayTitle = array();
        ProductOption::whereNotIn('poption_option_id', $optionSelect)->where('poption_prod_id', $productId)->delete();
        foreach ($optionSelect as $key => $selectedVal) {
            foreach ($optionsValue[$key] as $codeval) {
                $text = Str::lower($codeval['text']);
                $textArray = ['opn_value' => $text];
                $colorCode = '';
                if (count($optionsColors) > 0) {
                    $colorCode = $this->optionColorCode($optionsColors, $text);
                }
                $optionName = ProductOptionName::where($textArray)->first();
                if (!empty($optionName)) {
                    $opnId = $optionName->opn_id;
                    if ($colorCode) {
                        ProductOptionName::where('opn_id', $optionName->opn_id)->update(['opn_color_code' => $colorCode]);
                    }
                } else {
                    if ($colorCode) {
                        $textArray['opn_color_code'] = $colorCode;
                    }
                    $opnId = ProductOptionName::create($textArray)->opn_id;
                }
                $array = [
                    'poption_prod_id' => $productId,
                    'poption_option_id' => $selectedVal,
                    'poption_opn_id' => $opnId
                ];
                $record = ProductOption::where($array)->first();
                if (!empty($record)) {
                    $optionId = $record->poption_id;
                } else {
                    $optionId = ProductOption::create($array)->id;
                }
                $optionIds[] =  $optionId;

                $variantDisplayCode[$selectedVal][] = $opnId;
                $variantDisplayTitle[$selectedVal][] = $codeval['text'];
            }
        }
     
        $variantPovCode = cartesianArray($variantDisplayCode);
       
        $variantTitle = cartesianArray($variantDisplayTitle);
        ProductOption::whereNotIn('poption_id', $optionIds)->where('poption_prod_id', $productId)->delete();

        if ($request->input('prod_id') == '') {
            $productArray['prod_created_by_id'] = $this->admin->admin_id;
            $productArray['prod_created_on'] = Carbon::now();
        }
        $productArray['prod_updated_by_id'] = $this->admin->admin_id;
        $productArray['prod_updated_on'] = Carbon::now();

        foreach ($variantCodes as $codeKey => $variantCode) {
            if (empty($variantPrice[$codeKey]) || empty($variantPovCode[$codeKey])) {
                continue;
            }
            sort($variantPovCode[$codeKey]);
            $povCode = $productId . '|' . implode('|', $variantPovCode[$codeKey]) . '|';
            $publishStatus = config('constants.NO');
            if (isset($variantPublish[$codeKey]) && $variantPublish[$codeKey] == 'true') {
                $publishStatus = config('constants.YES');
            }
            $id = ProductOptionVarient::create([
                'pov_code' => $povCode,
                'pov_display_title' => implode('_', $variantTitle[$codeKey]),
                'pov_price' => $variantPrice[$codeKey],
                'pov_quantity' => $variantQuantity[$codeKey],
                'pov_sku' => $variantSKU[$codeKey],
                'pov_prod_id' => $productId,
                'pov_publish' => $publishStatus,
                'pov_default' => ($variantDefault == $codeKey) ? config('constants.YES') : config('constants.NO'),
            ])->pov_id;

            foreach ($variantPovCode[$codeKey] as $optionId) {
                OptionToVarient::create([
                    'otv_pov_id' =>  $id,
                    'otv_opn_id' => $optionId
                ]);
            }
        }
        Product::checkIfProductLowStock($productId);
        return ['prod_id' => $productId];
    }
    protected function optionColorCode($colourOptions, $option)
    {
        foreach ($colourOptions as $colourOption) {
            if (Str::lower($colourOption['text']) == $option) {
                return $colourOption['color'];
            }
        }
        return null;
    }
    protected function attributesInfoValidate(array $data)
    {
        $validator = Validator::make($data, [
            'prod_from_origin_country_id' => ['required', 'integer'],
            'pc_weight_unit' => ['required', 'integer'],
            'pc_weight' => ['required', 'integer'],
            'prod_sbpkg_id' => ['required', 'integer'],
        ]);
        return $validator->setAttributeNames([
            'prod_from_origin_country_id' => 'country of origin',
            'pc_weight_unit' => 'weight type',
            'pc_weight' => 'weight value',
            'prod_sbpkg_id' => 'package',
        ]);
    }
    protected function attributesInfo($request)
    {
        if ($request['prod_type'] != Product::DIGITAL_PRODUCT) {
            $this->attributesInfoValidate($request->all())->validate();
        }

        $productId =  $request->input('prod_id');
        $productArray = $request->only(['prod_from_origin_country_id', 'prod_sbpkg_id']);
        $productArray['prod_created_on'] =  Carbon::now();
        if ($request->input('prod_id') == '') {
            $productArray['prod_created_by_id'] = $this->admin->admin_id;
            $productArray['prod_created_on'] = Carbon::now();
        }
        $productArray['prod_updated_by_id'] = $this->admin->admin_id;
        $productArray['prod_updated_on'] = Carbon::now();

        $productContentArray = $request->only(['pc_isbn', 'pc_hsn', 'pc_sac', 'pc_upc', 'pc_weight', 'pc_weight_unit', 'pc_file_title']);
        $productVal = json_decode($request->input('ps_product_value'));
        $productGroup = explode(',', $request->input('ps_product_group'));
        Product::where('prod_id', $productId)->update($productArray);
        ProductSpecification::where('ps_product_id', $productId)->delete();
        ProductContent::where('pc_prod_id', $productId)->update($productContentArray);

        if (!empty($request->input('ps_product_key'))) {
            $productKeys = json_decode($request->input('ps_product_key'));
            foreach ($productKeys as $key => $productKey) {
                ProductSpecification::create([
                    'ps_product_id' => $productId,
                    'ps_product_key' => $productKey,
                    'ps_product_value' => $productVal[$key],
                    'ps_product_group' => $productGroup[$key]
                ]);
            }
        }
        return ['prod_id' => $productId];
    }
    protected function mediaInfo($request)
    {
        $productId = $request->input('prod_id');
        $productArray = $request->only(['prod_published', 'prod_publish_from']);
        if (!empty($request->input('prod_publish_from'))) {
            $productArray['prod_publish_from'] = date('Y-m-d H:i:s', strtotime($productArray['prod_publish_from']));
        } else {
            $productArray['prod_publish_from'] =  Carbon::now();
        }
        $productContentArray = $request->only(['pc_video_link']);
        Product::where('prod_id', $productId)->update($productArray);
        ProductContent::where('pc_prod_id', $productId)->update($productContentArray);
        return ['prod_id' => $productId];
    }
    protected function digitalInfo($request)
    {
        $productId = $request->input('prod_id');
        $productContentArray = $request->only(['pc_max_download_times', 'pc_download_validity_in_days', 'pc_upload_additional_files']);
        ProductContent::where('pc_prod_id', $productId)->update($productContentArray);
        DigitalFileRecord::where('dfr_record_id', $productId)->where('dfr_record_type', DigitalFileRecord::PRODUCT_RECORD_TYPE)->delete();

        $fileTypes = explode(',', $request->input('dfr_file_type'));
        $fileName = explode(',', $request->input('dfr_name'));
        $fileUrl = explode(',', $request->input('dfr_url'));
        $fileId = explode(',', $request->input('dfr_afile_id'));
        $type = explode(',', $request->input('type'));
        $varients = explode(',', $request->input('varient'));
        foreach ($fileTypes as $key => $fileType) {
            if (!empty($type[$key]) && $type[$key] == 2 && !empty($fileId[$key])) {
                $this->deleteFile($request, $fileId[$key]);
                $fileId[$key] = 0;
            }
            $attachedId = isset($fileId[$key]) ? $fileId[$key] : 0;
            $varientId = isset($varients[$key]) ? $varients[$key] : 0;
            DigitalFileRecord::create([
                'dfr_file_type' => $fileType,
                'dfr_record_id' => $productId,
                'dfr_record_type' => DigitalFileRecord::PRODUCT_RECORD_TYPE,
                'dfr_afile_id' => $attachedId,
                'dfr_name' => $fileName[$key],
                'dfr_url' => (isset($fileUrl[$key]) ? $fileUrl[$key] : ''),
                'dfr_pov_id' => $varientId,
            ]);
            if ($attachedId != 0 && $varientId != 0) {
                AttachedFile::where('afile_id', $attachedId)->update(['afile_record_subid' => $varientId]);
            }
        }
        return ['prod_id' => $productId];
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function uploadFiles(Request $request)
    {
        $uploadedFileName = FileHelper::uploadFile($request->file('file'));
        $response = AttachedFile::saveOrUpdateFile(AttachedFile::getConstantVal($request->input('attachedType')), $request->input('prod_id'), $uploadedFileName, $request->file('file')->getClientOriginalName());
        return jsonresponse(true, __('NOTI_FILE_UPLOADED'), $response);
    }
    public function uploadDigitalFile(Request $request)
    {
        $uploadedFileName = FileHelper::uploadDigitalFiles($request->file('file'));
        $response = AttachedFile::saveFile(AttachedFile::getConstantVal('digitalFiles'), $request->input('prod_id'), $uploadedFileName, $request->file('file')->getClientOriginalName());
        return jsonresponse(true, __('NOTI_FILE_UPLOADED'), $response);
    }

    public function uploadProductImages(Request $request, $productId, $optionId = 0)
    {
        $subRecordId = 0;
        if ($request->input('sub-record-id')) {
            $subRecordId = $request->input('sub-record-id');
        }
        if ($request->hasFile('actualimage')) {
            $uploadedFileName = FileHelper::uploadFile($request->file('actualimage'));
            $afile_name = $request->file('actualimage')->getClientOriginalName();
        } else {
            $recordData = AttachedFile::getAttachedFile(AttachedFile::getConstantVal('productImages'), $productId, $subRecordId);
            $uploadedFileName = $recordData->afile_physical_path;
            $afile_name = $recordData->afile_name;
        }
        if ($request->hasFile('cropimage')) {
            if (strpos($uploadedFileName, '-thumb') == true) {
                $uploadedFileName = str_replace('-thumb', '', $uploadedFileName);
            }
            $uploadedFileName = FileHelper::uploadThumbFile($uploadedFileName . '-thumb', $request->file('cropimage'));
            if (!empty($request->input('attached-id'))) {
              
                AttachedFile::updateAttachedFiles($request->input('attached-id'), $uploadedFileName, $afile_name, $request->input('addbackground'));
            } elseif ($request->hasFile('actualimage')) {
                AttachedFile::saveFile(AttachedFile::getConstantVal('productImages'), $productId, $uploadedFileName, $request->file('actualimage')->getClientOriginalName(), $subRecordId, $request->input('addbackground'));
            }
        }
        Product::where('prod_id', $productId)->first()->touch();

        $images = AttachedFile::getBulkFiles(AttachedFile::getConstantVal('productImages'), $productId, 0, true, false);
        return jsonresponse(true, __('NOTI_FILE_UPLOADED'), $images);
    }
 
    public function deleteFile(Request $request, $id = 0)
    {
        if ($id == 0) {
            $id = $request['id'];
        }

        if (!canWrite(AdminRole::PRODUCTS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $record = AttachedFile::where('afile_id', $id)->first();
        if ($record) {
            AttachedFile::deleteFile($record->afile_physical_path);
        }
        return jsonresponse(true, __('NOTI_FILE_DELETED'));
    }


    public function updateStatus(Request $request, $productId)
    {
        if (!canWrite(AdminRole::PRODUCTS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $publishStatus = ($request->input('status') == 'true') ? 1 : 0;
        $updateData = ['prod_published' => $publishStatus, 'prod_updated_by_id' => $this->admin->admin_id, 'prod_updated_on' => Carbon::now()];
        if ($publishStatus) {
            $updateData['prod_publish_from'] =  Carbon::now();
        }
        Product::where('prod_id', $productId)->update($updateData);
        $message = ($request->input('status') == 'true') ? __('NOTI_PRODUCT_PUBLISHED') : __('NOTI_PRODUCT_UNPUBLISHED');
        return jsonresponse(true, $message);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */

    public function updateRecords(Request $request)
    {
        if (!canWrite(AdminRole::PRODUCTS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $productIds = explode(',', $request->input('record-ids'));
        $type = $request->input('type');
        if ($type == 'publish') {
            Product::whereIn('prod_id', $productIds)->update(['prod_published' => 1]);
        } elseif ($type == 'unpublish') {
            Product::whereIn('prod_id', $productIds)->update(['prod_published' => 0]);
        } elseif ($type == 'delete') {
            foreach ($productIds as $productId) {
                $this->destroy($productId);
            }
        }
        return jsonresponse(true, __('NOTI_PRODUCTS_UPDATED'));
    }
    public function destroy($id)
    {
        if (!canWrite(AdminRole::PRODUCTS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        DB::beginTransaction();
        try {
            Product::deleteProducts($id);
            UrlRewrite::removeUrl('products', $id);
            $discountCoupon = DiscountCouponRecord::select('dcr_discountcpn_id')->where('dcr_type', DiscountCouponRecord::DISCOUNT_RECORD_PRODUCT_TYPE)->where('dcr_record_id', $id)->first();
            if (!empty($discountCoupon)) {
                if (DiscountCouponRecord::where('dcr_discountcpn_id', $discountCoupon->dcr_discountcpn_id)->count() == 1) {
                    DiscountCoupon::where('discountcpn_id', $discountCoupon->dcr_discountcpn_id)->update(['discountcpn_publish' => 0]);
                }
                $discountCoupon->delete();
            }
            DB::commit();
            return jsonresponse(true, __('NOTI_PRODUCTS_DELETED'));
        } catch (\Exception $e) {
            DB::rollBack();
            return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
        }
    }

    public function productDetail(Request $request)
    {
        $id = $request->input('product-id');
        $variants = ProductOptionVarient::where('pov_code', (string) $id)->first();
        $code = 0;
        if ($variants) {
            $code = $variants->pov_code;
            $id = $variants->pov_prod_id;
        }
        $fields =  'prod_id, prod_name, prod_type, prod_stock_out_sellable,brand_id,cat_id, prod_taxcat_id,pc_gift_wrap_available,pov_code, pov_display_title,prod_min_order_qty, prod_self_pickup, prod_cod_available,
         prod_max_order_qty,' . Product::getPrice();
        $product = Product::productById($id, $fields, [], $code);
        $stock = productStockVerify($product);
        $qty = 1;
        /* $maxQty = ($product->prod_max_order_qty < $product->totalstock) ?
            $product->prod_max_order_qty : $product->totalstock;*/

        $name = Str::limit($product->prod_name, 50, $end = '...');
        $option = '';
        if ($product->pov_display_title) {
            $option =  str_replace("_", " | ", $product->pov_display_title);
        }
        $record['stock'] =  $stock;
        $record['selectedqty'] =  $qty;
        $record['minQty'] =  $qty;
        $record['maxQty'] =  $product->totalstock;
        $record['id'] = $product->prod_id;
        $record['name'] =   $name;
        $record['option'] =   $option;
        $record['shipType'] = 'shipping';
        $record['pov_code'] = $product->pov_code;
        $record['brand_id'] = $product->brand_id;
        $record['cat_id'] = $product->cat_id;
        $record['tax'] = 0;
        $record['giftWrapEnable'] = $product->pc_gift_wrap_available;
        $record['giftWrap'] = 0;
        $record['includeTax'] = getConfigValueByName('PRICE_INCLUDING_TAX');
        $record['price'] =   $product->finalprice;
        $record['prod_type'] =   $product->prod_type;
        $record['prod_taxcat_id'] = $product->prod_taxcat_id;

        return jsonresponse(true, null, $record);
    }
    public function searchProduct(Request $request)
    {
        $productids = [];
        if (!empty($request->input('prod_id'))) {
            $productids = explode(',', $request->input('prod_id'));
        }
        return jsonresponse(true, null, Product::getProductsByName($request->input('query'), $productids, 0, false, false));
    }
    public function searchBykeyWords(Request $request)
    {
        $keywords = [];
        if (!empty($request->input('search'))) {
            $keywords = explode(',', $request->input('search'));
        }
        return jsonresponse(true, null, Product::searchBykeyWords($keywords));
    }
    public function updateItems(Request $request)
    {
        $itemsType = $request->input('type');
        $itemsData = json_decode($request->input('items'), true);

        foreach ($itemsData as $value) {
            $productId = 0;
            if ($value['type'] == 'product') {
                $obj =  Product::where('prod_id', $value['code']);
                $pricefield = 'prod_price';
                $qtyfield = 'prod_stock';
                $productId = $value['code'];
            } else {
                $obj =  ProductOptionVarient::where('pov_code', $value['code']);
                $pricefield = 'pov_price';
                $qtyfield = 'pov_quantity';
                $explodedCode = explode("|", $value['code']);
                $productId = !empty($explodedCode[0]) ? $explodedCode[0] : 0;
            }

            if ($itemsType == 'price') {
                $obj->update([$pricefield => $value['value']]);
            } else {
                $obj->update([$qtyfield => $value['value']]);
                if (!empty($productId) && isset($value['default']) && $value['default'] == 1) {
                    Product::checkIfProductLowStock($productId);
                }
            }
        }
        return jsonresponse(true, __('NOTI_PRODUCTS_UPDATED'));
    }

    public function export(Request $request)
    {
        return Excel::download(new ProductExport, 'products.xlsx');
    }

    public function import(Request $request)
    {
        $product = new ProductImport();
        $sheetHeadings = (new HeadingRowImport)->toArray($request->file('file'));
        $productHeadingRows = physicalProductHeadingRows();
        $countHeadings = array_search("inventory_level", $sheetHeadings[0][0]);
        if (($countHeadings + 1) != count($productHeadingRows)) {
            return jsonresponse(false, __('NOTI_PELASE_IMPORT_CORRECT_FILE'));
        }
        Excel::import($product, $request->file('file'));
        $error = $product->getErrors();
        if (isset($error) && !empty($error)) {
            $export = new ErrorExport($error);
            $name = 'product-error-' . time() . '.xls';
            Excel::store($export, 'public/excel/' . $name);
            return jsonresponse(true, __('NOTI_PHYSICALPRODUCT_IMPORTED'), url('storage/excel/' . $name));
        } else {
            return jsonresponse(true, __('NOTI_PHYSICALPRODUCT_IMPORTED'));
        }
    }
    public function exportDigitalProduct(Request $request)
    {
        return Excel::download(new DigitalProductExport, 'digitalProducts.xlsx');
    }

    public function importDigitalProduct(Request $request)
    {
        clearStorageFolder('public/excel/');
        $digitalProduct = new DigitalProductImport();
        $sheetHeadings = (new HeadingRowImport)->toArray($request->file('file'));
        if (!in_array('id', $sheetHeadings[0][0]) && !in_array('product_title', $sheetHeadings[0][0]) && !in_array('model_no', $sheetHeadings[0][0])) {
            return jsonresponse(false, __('NOTI_PELASE_IMPORT_CORRECT_FILE'));
        }
        Excel::import($digitalProduct, $request->file('file'));
        $error = $digitalProduct->getErrors();
        if (isset($error) && !empty($error)) {
            $export = new ErrorExport($error);
            $name = 'product-error-' . time() . '.xls';
            Excel::store($export, 'public/excel/' . $name);
            return jsonresponse(true, __('NOTI_DIGITALPRODUCT_IMPORTED'), url('storage/excel/' . $name));
        } else {
            return jsonresponse(true, __('NOTI_DIGITALPRODUCT_IMPORTED'));
        }
    }

    public function getBrands(Request $request)
    {
        $brands = Brand::getActiveBrandDropdown();
        array_unshift($brands, ['id' => 0, 'label' => __('PLH_SELECT_ONE'), 'isDisabled' => true]);
        $records['brands'] = $brands;
        return jsonresponse(true, null, $records);
    }

    public function getCategories(Request $request)
    {
        $categories = ProductCategory::getParentList();
        if (!empty($categories) && count($categories) > 0) {
            $categories =  ProductCategory::buildTree($categories);
        }
        array_unshift($categories, ['id' => 0, 'label' => __('PLH_SELECT_ONE'), 'isDisabled' => true]);
        $records['prodCategories'] = $categories;
        return jsonresponse(true, null, $records);
    }

    public function getLastId(Request $request)
    {
        $data['last-id'] = Product::max('prod_id');
        $data['total-option-count'] = max(ProductOption::select(DB::raw("count(distinct poption_option_id) as option_count"))->groupBy('poption_prod_id')->pluck('option_count')->toArray());
        return jsonresponse(true, '', $data);
    }

    public function digitalProductExcelUpdate(Request $request)
    {
        $data = [];
        $productError = [];
        $productData = [];
        $tempProductId = 0;
        $productId = 0;
        $required = '';
        $brandMessage = '';
        $configuration = Configuration::getKeyValues(['BRAND_IS_OPTIONAL', 'DECIMAL_VALUES']);
        if ($configuration['BRAND_IS_OPTIONAL'] != 1) {
            $required = 'required';
            $brandMessage = 'Brand name';
        }
        if ($configuration['DECIMAL_VALUES'] ==  1) {
            $decimalValue = '|max:22|regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/';
        } else {
            $decimalValue = '|numeric';
        }
        $tempRows = '';
        if (!empty($request->all())) {
            foreach ($request->all() as $key => $product) {
                $totalRows = array_slice($product, array_search('$id', array_keys($product)) + 1);
                if ($tempProductId != $product['prod_id'] && !empty($product['prod_id'])) {
                    $tempProductId = $product['prod_id'];
                    $validator = $this->digitalProductValidation($product, $required, $brandMessage, $decimalValue);
                    if ($validator->fails()) {
                        foreach ($validator->errors()->all() as $message) {
                            //$productError[$key]['key'] = $key + 1;
                            $productError[$key]['key'] = $product['prod_id'];
                            $productError[$key]['message'] = $message;
                        }
                        array_push($productData, $product);
                        continue;
                    } else {
                        $brand = [];
                        if (!empty(trim($product['brand_name']))) {
                            $brand = Brand::whereRaw('LOWER(`brand_name`) = ?', [trim(strtolower($product['brand_name']))])->first();
                        }
                        if ($configuration['BRAND_IS_OPTIONAL'] == 0) {
                            if (!isset($brand->brand_id) && empty($brand->brand_id)) {
                                //$productError[$key]['key'] = $key + 1;
                                $this->productImportError($productError, $product['prod_id'], "brand name not found");
                                //$productError[$key]['key'] = $product['prod_id'];
                                //$productError[$key]['message'] = "brand name not found";
                                array_push($productData, $product);
                                continue;
                            }
                        }
                        $taxGroup = TaxGroup::whereRaw('LOWER(`taxgrp_name`) = ? ', [trim(Str::lower($product['taxgrp_name']))])->first();
                        if (!isset($taxGroup->taxgrp_id) && empty($taxGroup->taxgrp_id)) {
                            //$productError[$key]['key'] = $key + 1;
                            //$productError[$key]['key'] = $product['prod_id'];
                            //$productError[$key]['message'] = "tax group not found";
                            $this->productImportError($productError, $product['prod_id'], "tax group not found");
                            array_push($productData, $product);
                            continue;
                        }
                        DB::beginTransaction();
                        try {
                            $productUpdateData = [
                                'prod_name' => trim($product['prod_name']),
                                'prod_type' =>  Product::DIGITAL_PRODUCT,
                                'prod_brand_id' => isset($brand->brand_id) ? $brand->brand_id : 0,
                                'prod_taxcat_id' => $taxGroup->taxgrp_id,
                                'prod_cost' => isset($product['prod_cost']) ? trim($product['prod_cost']) : 0,
                                'prod_price' => isset($product['price']) ? trim($product['price']) : 0,
                                'prod_stock' => isset($product['available_quantity']) ? trim($product['available_quantity']) : 0,
                                'prod_description' => trim($product['prod_description']),
                                'prod_model' => isset($product['prod_model']) ? trim($product['prod_model']) : '',
                                'prod_published' => (isset($product['publish']) && strtolower($product['publish']) == "yes")  ?  Product::PRODUCT_PUBLISHED : Product::PRODUCT_UNPUBLISHED,
                                'prod_publish_from' => isset($product['product_published_from']) ? $product['product_published_from'] : '',
                                'prod_min_order_qty' => isset($product['prod_min_order_qty']) ? $product['prod_min_order_qty'] : '',
                                'prod_max_order_qty' => isset($product['prod_max_order_qty']) ? $product['prod_max_order_qty'] : '',
                                'prod_updated_on' => Carbon::now(),
                                'prod_updated_by_id' => Auth::guard('admin')->user()->admin_id
                            ];
                            $checkProductExists = Product::where('prod_id', trim($product['prod_id']))->where('prod_type', Product::DIGITAL_PRODUCT);
                            if ($checkProductExists->count() > 0) {
                                $checkProductExists->update($productUpdateData);
                                $productId = $product['prod_id'];
                            } else {
                                $productId = Product::create($productUpdateData)->prod_id;
                            }

                            $productContentData = [
                                'pc_prod_id' => $productId,
                                'pc_sku' => isset($product['sku']) ? trim($product['sku']) : "",
                                'pc_isbn' => isset($product['pc_isbn']) ? trim($product['pc_isbn']) : "",
                                'pc_hsn' => isset($product['pc_hsn']) ? trim($product['pc_hsn']) : "",
                                'pc_sac' => isset($product['pc_sac']) ? trim($product['pc_sac']) : "",
                                'pc_upc' => isset($product['pc_upc']) ? trim($product['pc_upc']) : "",
                                'pc_video_link' =>  isset($product['pc_video_link']) ? trim($product['pc_video_link']) : "",
                                'pc_max_download_times' => isset($product['pc_max_download_times']) ? trim($product['pc_max_download_times']) : 0,
                                'pc_download_validity_in_days' => isset($product['pc_download_validity_in_days']) ? trim($product['pc_download_validity_in_days']) : 0
                            ];

                            $productContent = ProductContent::where('pc_prod_id', $productId);
                            if ($productContent->count() > 0) {
                                $productContent->update($productContentData);
                            } else {
                                ProductContent::create($productContentData);
                            }
                            $productCategory = ProductToCategory::where('ptc_prod_id', $productId);
                            if ($productCategory->count() > 0) {
                                ProductToCategory::where('ptc_prod_id', $productId)->update(['ptc_cat_id' => $product['cat_id']]);
                            } else {
                                ProductToCategory::Create([
                                    'ptc_prod_id' => $productId,
                                    'ptc_cat_id' => $product['cat_id']
                                ]);
                            }

                            if (strtolower(trim($product['inventory_management_level'])) == "variant") {
                                if ($totalRows >= 1) {
                                    $productDefaultVariant = ProductOptionVarient::where('pov_prod_id', $productId)->where('pov_default', config('constants.YES'))->get()->count();
                                    $variantDefault = ($productDefaultVariant == 0) ? 1 : 0;
                                    $tempRows = $totalRows;
                                    $this->saveVariantInformation($totalRows, $productId, $product, $variantDefault, true, $tempRows);
                                }
                            }
                            UrlRewrite::saveUrl('products', $productId);
                            DB::commit();
                        } catch (\Exception $e) {
                            $this->productImportError($productError, $product['prod_id'], "tax group not found");
                            array_push($productData, $product);
                            DB::rollBack();
                            continue;
                        }
                    }
                } elseif (Product::where('prod_id', $productId)->count() > 0) {
                    DB::beginTransaction();
                    try {
                        if ($totalRows >= 1) {
                            $validator = $this->digitalProductVariantValidation($product, $decimalValue);
                            if ($validator->fails()) {
                                foreach ($validator->errors()->all() as $message) {
                                    $this->productImportError($productError, $productId, $message);
                                }
                            } else {
                                $variantErrorMessage = $this->saveVariantInformation($totalRows, $productId, $product, 0, false, $tempRows);
                                if (isset($variantErrorMessage['message']) && !empty($variantErrorMessage['message'])) {
                                    $this->productImportError($productError, $product['prod_id'], $variantErrorMessage['message']);
                                }
                            }
                        }
                        DB::commit();
                    } catch (\Exception $e) {
                        $this->productImportError($productError, $productId, $e->getMessage());
                        DB::rollBack();
                    }
                }
            }
        }
        if (isset($productError) && !empty($productError)) {
            $export = new ErrorExport($productError);
            $name = 'product-error-' . time() . '.xls';
            Excel::store($export, 'public/excel/' . $name);
            $data['productError'] = url('storage/excel/' . $name);
        }
        $data['productData'] = $productData;
        return jsonresponse(true, __('NOTI_DIGITALPRODUCT_UPDATED'), $data);
    }

    public function getDigitalProductForExcel(Request $request)
    {
        $fields = 'prod_id, prod_name, brand_name, cat_id, cat_name, prod_description, prod_model, taxgrp_name,pc_threshold_stock_level,prod_min_order_qty,prod_max_order_qty,prod_cost,' . Product::getPrice() . ',COALESCE(`pov_sku`,`pc_sku`) as sku, pc_isbn,pc_hsn,pc_sac,pc_upc,pc_video_link,prod_published, prod_publish_from,pc_max_download_times,pc_download_validity_in_days,pov_display_title, pov_code, pov_default';
        $query =  Product::getListingObj([], $fields, false, [], false);
        $query->addCondition(Product::DIGITAL_PRODUCT, 'prod_type');
        $products =  $query->getRecords();
        if (isset($products) && !empty($products)) {
            $tempProductId = 0;
            foreach ($products as $key => $prod) {
                $prod->publish = ($prod->prod_published == 1) ? 'Yes' : 'No';
                $prod->pc_threshold_stock_level = ($prod->pc_threshold_stock_level == 1) ? 'Yes' : 'No';
                $prod->pov_default = ($prod->pov_default == 1) ? 'Yes' : 'No';
                $prod->pc_isbn = (!empty($prod->pc_isbn) && ($prod->pc_isbn != 'null')) ? $prod->pc_isbn : '';
                $prod->pc_hsn = (!empty($prod->pc_hsn) && ($prod->pc_hsn != 'null')) ? $prod->pc_hsn : '';
                $prod->pc_sac = (!empty($prod->pc_sac) && ($prod->pc_sac != 'null')) ? $prod->pc_sac : '';
                $prod->pc_upc = (!empty($prod->pc_upc) && ($prod->pc_upc != 'null')) ? $prod->pc_upc : '';
                $prod->pc_video_link = (!empty($prod->pc_video_link) && ($prod->pc_video_link != 'null')) ? $prod->pc_video_link : '';
                if ($tempProductId != $prod->prod_id) {
                    $tempProductId = $prod->prod_id;
                    $tempCounter = 0;
                }
                if ($tempCounter >= 1) {
                    $prod = $this->emptyDigitalProductFields($prod);
                }
                if (isset($prod->pov_code) && !empty($prod->pov_code)) {
                    $totalVariant = array_filter(explode('|', $prod->pov_code));
                    array_splice($totalVariant, 0, 1);
                    if (count($totalVariant) > 0) {
                        foreach ($totalVariant as $key1 => $variant) {
                            $GroupName = ProductOption::with(['options'])->where(['poption_opn_id' => $variant, 'poption_prod_id' => $prod->prod_id])->first();
                            //$prod['option_id'] = isset($GroupName->poption_id) ? $GroupName->poption_id : '';
                            $prod['option_group_name_' . ($key1 + 1)] = isset($GroupName->options->option_name) ? $GroupName->options->option_name : '';
                            $prod['option_group_value_' . ($key1 + 1)] = isset($GroupName->getOptionNames->opn_value) ? $GroupName->getOptionNames->opn_value : '';
                        }
                    }
                    if ($tempCounter == 0) {
                        $prod->inventory_management_level = "Variant";
                    }
                } else {
                    $prod->inventory_management_level = "Product";
                }
                unset($prod['pov_display_title']);
                unset($prod['pov_code']);
                //unset($prod['pov_default']);
                $tempCounter++;
            }
        }
        return jsonresponse(true, '', $products);
    }

    public function getPhysicalProductForExcel(Request $request)
    {
        $weightUnit = ProductContent::getWeightOptions();
        $fields = 'prod_id, prod_name, brand_name, cat_id, cat_name, prod_description, prod_model, taxgrp_name,prod_condition,pc_warranty_age,pc_return_age,
        pc_threshold_stock_level,pc_substract_stock,prod_self_pickup,prod_stock_out_sellable,prod_cod_available,pc_gift_wrap_available,
        prod_min_order_qty,prod_max_order_qty,prod_cost,' . Product::getPrice() . ',COALESCE(`pov_sku`,`pc_sku`) as sku, prod_from_origin_country_id,pc_weight,pc_weight_unit,
        pc_isbn,pc_hsn,pc_sac,pc_upc,pc_video_link,prod_published, prod_publish_from,pov_code';
        $query =  Product::getListingObj([], $fields, false, [], false);
        $query->addCondition(Product::PHYSICAL_PRODUCT, 'prod_type');
        $products =  $query->getRecords();
        if (isset($products) && !empty($products)) {
            $tempProductId = 0;
            foreach ($products as $key => $prod) {
                $prod->publish = ($prod->prod_published == 1) ? 'Yes' : 'No';
                $prod->pc_threshold_stock_level = ($prod->pc_threshold_stock_level == 1) ? 'Yes' : 'No';
                $prod->pov_default = ($prod->pov_default == 1) ? 'Yes' : 'No';
                $prod->prod_condition = Product::PRODUCT_CONDITIONS[$prod->prod_condition];
                $prod->prod_cod_available = ($prod->prod_cod_available == 1) ? 'Yes' : 'No';
                $prod->prod_stock_out_sellable = ($prod->prod_stock_out_sellable == 1) ? 'Yes' : 'No';
                $prod->pc_gift_wrap_available = ($prod->pc_gift_wrap_available == 1) ? 'Yes' : 'No';
                $prod->pc_isbn = (!empty($prod->pc_isbn) && ($prod->pc_isbn != 'null')) ? $prod->pc_isbn : '';
                $prod->pc_hsn = (!empty($prod->pc_hsn) && ($prod->pc_hsn != 'null')) ? $prod->pc_hsn : '';
                $prod->pc_sac = (!empty($prod->pc_sac) && ($prod->pc_sac != 'null')) ? $prod->pc_sac : '';
                $prod->pc_upc = (!empty($prod->pc_upc) && ($prod->pc_upc != 'null')) ? $prod->pc_upc : '';
                $prod->pc_video_link = (!empty($prod->pc_video_link) && ($prod->pc_video_link != 'null')) ? $prod->pc_video_link : '';
                $prod->prod_description = strip_tags(trim($prod->prod_description));
                if ($tempProductId != $prod->prod_id) {
                    $tempProductId = $prod->prod_id;
                    $tempCounter = 0;
                }
                if ($tempCounter >= 1) {
                    $prod = $this->emptyPhysicalProductField($prod);
                }
                if (isset($prod->pc_weight_unit) && isset($weightUnit[$prod->pc_weight_unit])) {
                    $prod->pc_weight_unit =  $weightUnit[$prod->pc_weight_unit];
                } else {
                    $prod->pc_weight_unit = '';
                }
                if (isset($prod->pov_code) && !empty($prod->pov_code)) {
                    if ($tempCounter >= 1) {
                        $prod->inventory_management_level = "";
                    } else {
                        $prod->inventory_management_level = "Variant";
                    }

                    $totalVariant = array_filter(explode('|', $prod->pov_code));
                    array_splice($totalVariant, 0, 1);
                    if (count($totalVariant) > 0) {
                        foreach ($totalVariant as $key1 => $variant) {
                            $GroupName = ProductOption::with(['options'])->where(['poption_opn_id' => $variant, 'poption_prod_id' => $prod->prod_id])->first();
                            //$product['option_id'] = isset($GroupName->poption_id) ? $GroupName->poption_id : '';
                            $prod['option_group_name_' . ($key1 + 1)] = isset($GroupName->options->option_name) ? $GroupName->options->option_name : '';
                            $prod['option_group_value_' . ($key1 + 1)] = isset($GroupName->getOptionNames->opn_value) ? $GroupName->getOptionNames->opn_value : '';
                        }
                    }
                } else {
                    $prod->inventory_management_level = "Product";
                }
                unset($prod['pov_display_title']);
                unset($prod['pov_code']);
                $tempCounter++;
            }
        }
        return jsonresponse(true, '', $products);
    }

    public function physicalProductExcelUpdate(Request $request)
    {
        $weightUnit = ProductContent::getWeightOptions();
        $weightUnitValue = array_flip($weightUnit);
        $data = [];
        $productError = [];
        $productData = [];
        $brand = '';
        $tempProductId = 0;
        $productId = 0;
        $configuration = Configuration::getKeyValues(['BRAND_IS_OPTIONAL', 'DECIMAL_VALUES', 'PICK_UP_ENABLE']);
        $tempRows = '';
        $required = '';
        $brandMessage = '';
        if ($configuration['BRAND_IS_OPTIONAL'] != 1) {
            $required = 'required';
            $brandMessage = 'Brand name';
        }

        if ($configuration['DECIMAL_VALUES'] ==  1) {
            $decimalValue = '|max:22|regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/';
        } else {
            $decimalValue = '|numeric';
        }
        if (!empty($request->all())) {
            foreach ($request->all() as $key => $product) {
                $totalRows = array_slice($product, array_search('$id', array_keys($product)) + 1);
                if ($tempProductId != $product['prod_id'] && !empty($product['prod_id'])) {
                    $tempProductId = $product['prod_id'];
                    $validator = $this->physicalProductValidation($product, $required, $brandMessage, $weightUnit, $decimalValue);
                    if ($validator->fails()) {
                        foreach ($validator->errors()->all() as $validationKey => $message) {
                            $this->productImportError($productError, $product['prod_id'], $message);
                            //$productError[$key]['key'] = $key + 1;
                            //$productError[$key]['message'] =  $message;
                        }
                        array_push($productData, $product);
                        continue;
                    }

                    $brand = [];
                    if (!empty(trim($product['brand_name']))) {
                        $brand = Brand::whereRaw('LOWER(`brand_name`) = ?', [trim(strtolower($product['brand_name']))])->first();
                    }
                    if ($configuration['BRAND_IS_OPTIONAL'] == 0) {
                        if (!isset($brand->brand_id) && empty($brand->brand_id)) {
                            //$productError[$key]['key'] = $key + 1;
                            //$productError[$key]['key'] = $product['prod_id'];
                            //$productError[$key]['message'] = __("MSG_BRAND_NOT_FOUND");
                            $this->productImportError($productError, $product['prod_id'], "brand name not found");
                            array_push($productData, $product);
                            continue;
                        }
                    }
                    $taxGroup = TaxGroup::whereRaw('LOWER(`taxgrp_name`) = ? ', [trim(Str::lower($product['taxgrp_name']))])->first();
                    if (!isset($taxGroup->taxgrp_id) && empty($taxGroup->taxgrp_id)) {
                        //$productError[$key]['key'] = $key + 1;
                        //$productError[$key]['key'] = $product['prod_id'];
                        //$productError[$key]['message'] =  __("MSG_TAX_NOT_FOUND");
                        $this->productImportError($productError, $product['prod_id'], "tax group not found");
                        array_push($productData, $product);
                        continue;
                    }

                    if (isset($product['country_name']) && !empty($product['country_name'])) {
                        $country = Country::whereRaw('LOWER(`country_name`) = ?', [trim(Str::lower($product['country_name']))])->first();
                        if (!isset($country->country_id) && empty($country->country_id)) {
                            //$productError[$key]['key'] = $key + 1;
                            //$productError[$key]['key'] = $product['prod_id'];
                            //$productError[$key]['message'] =  __("MSG_COUNTRY_NAME_NOT_FOUND");
                            $this->productImportError($productError, $product['prod_id'], __("MSG_COUNTRY_NAME_NOT_FOUND"));
                            array_push($productData, $product);
                            continue;
                        }
                    }
                    try {
                        DB::beginTransaction();
                        $deliveryMethod = '';
                        if (isset($product['prod_self_pickup']) && !empty($product['prod_self_pickup'])) {
                            if (strtolower(trim($product['prod_self_pickup'])) == 'shipping only') {
                                $deliveryMethod = Product::SHIPPING_ONLY;
                            } elseif (strtolower(trim($product['prod_self_pickup'])) == 'pickup only' && $configuration['PICK_UP_ENABLE'] == 1) {
                                $deliveryMethod = Product::PICKUP_ONLY;
                            } elseif (strtolower(trim($product['prod_self_pickup'])) == 'both' && $configuration['PICK_UP_ENABLE'] == 1) {
                                $deliveryMethod = Product::BOTH;
                            } else {
                                $this->productImportError($productError, $product['prod_id'], "Invalid delivery method");
                                continue;
                            }
                        }

                        $productUpdateData = [
                            'prod_name' => trim($product['prod_name']),
                            'prod_type' =>  Product::PHYSICAL_PRODUCT,
                            'prod_brand_id' => isset($brand->brand_id) ? $brand->brand_id : '',
                            'prod_taxcat_id' => $taxGroup->taxgrp_id,
                            'prod_price' => isset($product['price']) ? trim($product['price']) : '',
                            'prod_cost' => trim($product['prod_cost']),
                            'prod_self_pickup' => $deliveryMethod,
                            'prod_stock' => @trim($product['quantity']),
                            'prod_model' => trim($product['prod_model']),
                            'prod_condition' => isset($product['prod_condition']) ? Product::PRODUCT_CONDITIONS_VALUES[Str::lower(trim($product['prod_condition']))] : '',
                            'prod_stock_out_sellable' => (isset($product['prod_stock_out_sellable']) && (Str::lower($product['prod_stock_out_sellable']) == 'yes')) ? config('constants.YES') : config('constants.NO'),
                            'prod_published' => (isset($product['publish']) && Str::lower($product['publish']) == "yes")  ?  Product::PRODUCT_PUBLISHED : Product::PRODUCT_UNPUBLISHED,
                            'prod_description' => trim($product['prod_description']),
                            'prod_cod_available' => (Str::lower($product['prod_cod_available']) == "yes") ? config('constants.YES') : config('constants.NO'),
                            'prod_min_order_qty' => isset($product['prod_min_order_qty']) ? $product['prod_min_order_qty'] : '',
                            'prod_max_order_qty' => isset($product['prod_max_order_qty']) ? $product['prod_max_order_qty'] : '',
                            'prod_from_origin_country_id' => isset($country->country_id) ? $country->country_id : config('constants.NO'),
                            'prod_publish_from' => isset($product['prod_publish_from']) ? $product['prod_publish_from'] : '',
                            'prod_updated_on' => Carbon::now(),
                            'prod_updated_by_id' => Auth::guard('admin')->user()->admin_id
                            //'prod_sbpkg_id' => isset($shippingPackages->sbpkg_id) ? $shippingPackages->sbpkg_id : config('constants.NO'),
                        ];

                        $productUpdate = Product::where('prod_id', trim($product['prod_id']))->where('prod_type', Product::PHYSICAL_PRODUCT);
                        if ($productUpdate->count() > 0) {
                            $productUpdate->update($productUpdateData);
                            $productId = $product['prod_id'];
                        } else {
                            $productId = Product::create($productUpdateData)->prod_id;
                        }
                        $productContentData = [
                            'pc_prod_id' => $productId,
                            'pc_sku'     => isset($product['sku']) ? trim($product['sku']) : '',
                            'pc_threshold_stock_level' => (Str::lower($product['pc_gift_wrap_available']) == "yes") ? config('constants.YES') : config('constants.NO'),
                            'pc_substract_stock' => isset($product['pc_substract_stock']) ? $product['pc_substract_stock'] : config('constants.NO'),
                            'pc_gift_wrap_available' => (Str::lower($product['pc_gift_wrap_available']) == "yes") ? config('constants.YES') : config('constants.NO'),
                            'pc_weight'  => trim($product['pc_weight']),
                            'pc_weight_unit' => isset($weightUnitValue[trim($product['pc_weight_unit'])]) ? $weightUnitValue[$product['pc_weight_unit']] : '',
                            'pc_return_age' => isset($product['pc_return_age']) ? $product['pc_return_age'] : config('constants.NO'),
                            'pc_isbn'    => isset($product['pc_isbn']) ?  trim($product['pc_isbn']) : '',
                            'pc_hsn'     => isset($product['pc_hsn'])  ? trim($product['pc_hsn']) : '',
                            'pc_sac'     => isset($product['pc_sac'])  ? trim($product['pc_sac']) : '',
                            'pc_upc'     => isset($product['pc_upc'])  ? trim($product['pc_upc']) : '',
                            'pc_video_link' => trim($product['pc_video_link']),
                            'pc_warranty_age' => isset($product['pc_warranty_age']) ? $product['pc_warranty_age'] : config('constants.NO'),
                            'pc_return_age' => isset($product['pc_return_age']) ? $product['pc_return_age'] : config('constants.NO')
                        ];

                        $productContentFound = ProductContent::where('pc_prod_id', $productId)->count();
                        if ($productContentFound > 0) {
                            ProductContent::where('pc_prod_id', $productId)->update($productContentData);
                        } else {
                            ProductContent::create($productContentData);
                        }

                        if (ProductToCategory::where('ptc_prod_id', $productId)->count() > 0) {
                            ProductToCategory::where('ptc_prod_id', $productId)->update(['ptc_cat_id' => $product['cat_id']]);
                        } else {
                            ProductToCategory::Create([
                                'ptc_prod_id' => $productId,
                                'ptc_cat_id' => $product['cat_id']
                            ]);
                        }
                        if (strtolower(trim($product['inventory_management_level'])) == "variant") {
                            if ($totalRows >= 1) {
                                $productDefaultVariant = ProductOptionVarient::where('pov_prod_id', $productId)->where('pov_default', config('constants.YES'))->get()->count();
                                $variantDefault = ($productDefaultVariant == 0) ? 1 : 0;
                                $tempRows = $totalRows;
                                $this->saveVariantInformation($totalRows, $productId, $product, $variantDefault, true, $tempRows);
                            }
                        }
                        UrlRewrite::saveUrl('products', $productId);
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        $this->productImportError($productError, $product['prod_id'], __("MSG_COUNTRY_NAME_NOT_FOUND"));
                        array_push($productData, $product);
                        //$productError[$key]['key'] = $key + 1;
                        //$productError[$key]['key'] = $product['prod_id'];
                        //$productError[$key]['message'] =  $e->getMessage();
                        $this->displayError("Invalid delivery method", $product['prod_id']);
                    }
                } elseif (Product::where('prod_id', $productId)->count() > 0 && !empty($product['prod_id'])) {
                    DB::beginTransaction();
                    try {
                        if ($totalRows >= 1) {
                            $this->saveVariantInformation($totalRows, $productId, $product, 0, true, $tempRows);
                        }
                        DB::commit();
                    } catch (\Exception $e) {
                        //$productError[$key]['key'] = $product['prod_id'];
                        $productError[$key]['key'] = $product['prod_id'];
                        $productError[$key + 1] = $e->getMessage();
                        DB::rollBack();
                    }
                }
            }
        }
        if (isset($productError) && !empty($productError)) {
            $export = new ErrorExport($productError);
            $name = 'product-error-' . time() . '.xls';
            Excel::store($export, 'public/excel/' . $name);
            $data['productError'] = url('storage/excel/' . $name);
        }
        $data['productData'] = $productData;
        return jsonresponse(true, __('NOTI_PHYSICALPRODUCT_UPDATED'), $data);
    }

    public function searchBrands(Request $request)
    {
        $brands = [];
        $brands = Brand::getBrandData(['brand_name as label', 'brand_id as id'], [['brand_name', 'LIKE', '%' . $request->input('brand') . '%'], ['brand_publish', config('constants.YES')]], 5);
        return jsonresponse(true, null, $brands);
    }

    private function emptyDigitalProductFields($prod)
    {
        $prod->prod_name  = '';
        $prod->brand_name = '';
        $prod->cat_id = '';
        $prod->cat_name = '';
        $prod->prod_description = '';
        $prod->prod_model = '';
        $prod->taxgrp_name = '';
        $prod->prod_cost = '';
        $prod->pc_isbn = '';
        $prod->pc_hsn = '';
        $prod->pc_sac = '';
        $prod->pc_upc = '';
        $prod->pc_video_link = '';
        $prod->prod_publish_from = '';
        $prod->prod_min_order_qty = '';
        $prod->prod_max_order_qty = '';
        $prod->pc_max_download_times = '';
        $prod->pc_download_validity_in_days = '';
        return $prod;
    }

    private function digitalProductValidation($product, $required, $brandMessage, $decimalValue)
    {
        $validator = Validator::make($product, [
            'prod_name' => 'required',
            'brand_name' => $required,
            'cat_id'  => 'required',
            'cat_name'  => 'required|exists:product_categories,cat_name',
            'taxgrp_name' => 'required|exists:tax_groups,taxgrp_name',
            'prod_description' => 'required',
            'prod_model' => '',
            'pc_threshold_stock_level' => '',
            'prod_min_order_qty' => 'required',
            'prod_max_order_qty' => '',
            'prod_cost' => 'required' . $decimalValue,
            'price' => 'required' . $decimalValue,
            'available_quantity' => 'required', 
            'sku' => '',
            'pc_isbn' => '',
            'pc_hsn' => '',
            'pc_sac' => '',
            'pc_upc' => '',
            'pc_video_link' => '',
            'publish' => ['required', Rule::in(['yes', 'no', 'Yes', 'No'])],
            'pc_max_download_times' => '',
            'pc_download_validity_in_days' => ''
        ]);

        $validator->setAttributeNames([
            'prod_name' => 'Product name',
            'brand_name' => $brandMessage,
            'cat_id' => 'Category id',
            'cat_name' => 'Category name',
            'taxgrp_name' => 'tax category',
            'prod_description' => 'Description',
            'prod_model' => '',
            'pc_threshold_stock_level' => '',
            'prod_min_order_qty' => 'Min Purchase Qty',
            'prod_max_order_qty' => '',
            'prod_cost' => 'Product Cost',
            'price' => 'Selling Price',
            'available_quantity' => 'Available Quantity',
            'sku' => 'sku',
            'pc_isbn' => '',
            'pc_hsn' => '',
            'pc_sac' => '',
            'pc_upc' => '',
            'pc_video_link' => '',
            'publish' => 'publish',
            'prod_published_from' => '',
            'pc_max_download_times' => 'Max Download',
            'validitydays' => 'validity'
        ]);
        return $validator;
    }

    private function digitalProductVariantValidation($row, $decimalValue)
    {
        $validator = Validator::make($row, [
            'price' => 'required' . $decimalValue,
            'available_quantity' => 'required|numeric',
            'publish' => ['required', Rule::in(['yes', 'no', 'Yes', 'No', 'YES', 'NO'])]
        ]);

        $validator->setAttributeNames([
            'price' => 'Selling Price',
            'available_quantity' => 'Available Quantity',
            'publish' => 'publish'
        ]);
        return $validator;
    }

    private function saveVariantInformation($totalRows, $productId, $allRows, $default = 0, $isFirstRow = false, $tempRows)
    {
        $x = 1;
        $y = 0;
        foreach ($totalRows as $optionkey => $optionRow) {
            //if(($y % 2) == 0) {
            if (isset($totalRows['option_group_name_' . $x]) && !empty($totalRows['option_group_name_' . $x])) {
                if ((!$isFirstRow) && $totalRows['option_group_' . $x] != $tempRows['option_group_' . $x]) {
                    return ['message' => 'Invalid Option Group'];
                }
                $options = Option::select('option_id')->whereRaw('LOWER(`option_name`) = ? ', [trim(strtolower($totalRows['option_group_name_' . $x]))])->first();
                if (!empty($options)) {
                    $optionId = $options->option_id;
                } else {
                    $optionId = Option::create([
                        'option_name' => $totalRows['option_group_' . $x],
                    ])->option_id;
                }
                $optionName = ProductOptionName::select('opn_id')->where('opn_value', trim(strtolower($totalRows['option_group_value_' . $x])))->first();
                if (!empty($optionName)) {
                    $optionNameId = $optionName->opn_id;
                } else {
                    $optionNameId = ProductOptionName::create([
                        'opn_value' => trim(strtolower($totalRows['option_group_value_' . $x])),
                    ])->opn_id;
                }
                $productOptionRecords = [
                    'poption_prod_id' => $productId,
                    'poption_option_id' => $optionId,
                    'poption_opn_id' => $optionNameId
                ];

                $options = ProductOption::select('poption_id')->where($productOptionRecords)->first();
                if (!empty($options)) {
                    $optionId = $options->poption_id;
                } else {
                    $optionId = ProductOption::create($productOptionRecords)->poption_id;
                }
                $optionIds[] =  $optionId;
                $optionNameIds[] =  $optionNameId;
                $variantDisplayCode[$optionId][] = $optionNameId;
                $variantDisplayTitle[$optionId][] = trim(strtolower($totalRows['option_group_value_' . $x]));
                $x++;
            }
            //}
            $y++;
        }
        if (isset($variantDisplayCode) && isset($variantDisplayTitle)) {
            $variantPovCode = cartesianArray($variantDisplayCode);
            $variantTitle = cartesianArray($variantDisplayTitle);
            $povCode = $productId . '|' . implode('|', $variantPovCode[0]) . '|';
            $varientRecords = [
                'pov_display_title' => implode('_', $variantTitle[0]),
                'pov_code' => $povCode,
                'pov_price' => isset($allRows['price']) ? $allRows['price'] : 0,
                'pov_quantity' => isset($allRows['available_quantity']) ? $allRows['available_quantity'] : 0,
                'pov_prod_id' => $productId,
                'pov_sku' => isset($allRows['sku']) ? $allRows['sku'] : 0,
                'pov_publish' => (isset($allRows['publish']) && strtolower($allRows['publish']) == "yes")  ?  Product::PRODUCT_PUBLISHED : Product::PRODUCT_UNPUBLISHED,
            ];
            if ($default == 1) {
                $varientRecords['pov_default'] = $default;
            }
            $varientExist  = ProductOptionVarient::where('pov_code', $povCode)->first();
            if (!empty($varientExist)) {
                $povId = $varientExist->pov_code;
                ProductOptionVarient::where('pov_id', $varientExist->pov_id)->update($varientRecords);
            } else {
                $povId = ProductOptionVarient::create($varientRecords)->pov_id;
            }

            foreach ($optionNameIds as $optionValueId) {
                $optionToVariant = OptionToVarient::where('otv_pov_id', $povId)->get()->count();
                if ($optionToVariant == 0) {
                    OptionToVarient::create([
                        'otv_pov_id' => $povId,
                        'otv_opn_id' => $optionValueId
                    ]);
                }
            }
        }
    }

    private function emptyPhysicalProductField($prod)
    {
        $prod->prod_name  = '';
        $prod->brand_name = '';
        $prod->cat_id = '';
        $prod->cat_name = '';
        $prod->prod_description = '';
        $prod->prod_condition = '';
        $prod->pc_warranty_age = '';
        $prod->pc_return_age = '';
        //$prod->pc_cancellation_age = '';
        $prod->prod_cod_available = '';
        $prod->prod_min_order_qty = '';
        $prod->prod_max_order_qty = '';
        $prod->pc_gift_wrap_available = '';
        $prod->prod_model = '';
        $prod->taxgrp_name = '';
        $prod->prod_cost = '';
        $prod->pc_isbn = '';
        $prod->pc_hsn = '';
        $prod->pc_sac = '';
        $prod->pc_upc = '';
        $prod->pc_video_link = '';
        $prod->prod_publish_from = '';
        $prod->prod_stock_out_sellable = '';
        $prod->pc_weight_unit = '';
        return $prod;
    }

    private function physicalProductValidation($product, $required, $brandMessage, $weightUnit, $decimalValue)
    {
        $validator = Validator::make($product, [
            'prod_name' => 'required',
            'brand_name' => $required,
            'cat_id'  => 'required',
            'cat_name'  => 'required|exists:product_categories,cat_name',
            'prod_description' => '',
            'prod_model' => '',
            'taxgrp_name' => 'required|exists:tax_groups,taxgrp_name',
            'prod_condition' => 'required',
            'pc_warranty_age' => 'required',
            'pc_return_age' => 'required',
            'pc_gift_wrap_available' => '',
            'prod_cod_available' => '',
            'pc_threshold_stock_level' => '',
            'prod_min_order_qty' => 'required',
            'prod_max_order_qty' => '',
            'prod_cost' => 'required' . $decimalValue,
            'price' => 'required' . $decimalValue,
            'available_quantity' => '',
            'sku' => '',
            'pc_isbn' => '',
            'pc_hsn' => '',
            'pc_sac' => '',
            'pc_upc' => '',
            'pc_video_link' => '',
            'pc_weight_unit' => Rule::in($weightUnit),
            'publish' => ['required', Rule::in(['yes', 'no', 'Yes', 'No'])]
        ]);

        $validator->setAttributeNames([
            'prod_name' => 'Product name',
            'brand_name' => $brandMessage,
            'cat_id' => 'Category id',
            'cat_name' => 'Category name',
            'taxgrp_name' => 'tax category',
            'prod_condition' => 'product condition',
            'pc_warranty_age' => 'warranty',
            'pc_return_age' =>  'return',
            'prod_description' => 'Description',
            'prod_model' => '',
            'pc_threshold_stock_level' => '',
            'prod_min_order_qty' => 'Min Purchase Qty',
            'prod_max_order_qty' => '',
            'prod_cost' => 'Product Cost',
            'price' => 'Selling Price',
            'available_quantity' => 'Available Quantity',
            'sku' => 'sku',
            'pc_isbn' => '',
            'pc_hsn' => '',
            'pc_sac' => '',
            'pc_upc' => '',
            'pc_video_link' => '',
            'publish' => 'publish',
            'prod_published_from' => '',
            'pc_weight_unit' => 'weight unit',
            'pc_max_download_times' => 'Max Download',
            'validitydays' => 'validity'
        ]);

        return $validator;
    }

    private function productImportError($productError, $key, $message)
    {
        $messages = [];
        array_push($messages, $key);
        array_push($messages, $message);
        array_push($productError, $messages);
    }

    private function getSizeChartUrl($productId)
    {
        $attachedFile = [];
        $attachedFile = AttachedFile::select('afile_id', DB::raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '/thumb?t=', UNIX_TIMESTAMP(afile_updated_at)) as size_chart"))->where('afile_record_id',$productId)->where('afile_type', AttachedFile::SECTIONS['productChartUpload'])->first();
            return $attachedFile;
    }
}
