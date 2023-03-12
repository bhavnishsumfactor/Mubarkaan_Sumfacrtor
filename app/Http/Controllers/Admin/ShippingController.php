<?php

namespace App\Http\Controllers\Admin;

use App\Models\ShippingProfile;
use App\Models\ShippingBoxPackage;
use App\Models\ShippingProfileZone;
use App\Models\ShippingToLocation;
use App\Models\ShippingProfileToProduct;
use App\Models\Admin\AdminRole;
use App\Models\ShippingRate;
use App\Models\Product;
use App\Models\Region;
use App\Models\Configuration;
use App\Models\State;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ShippingBoxPackageRequest;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Exports\ShippingPackageExport;
use App\Imports\ShippingPackagesImport;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Validation\Rule;
use App\Exports\ErrorExport;
use DB;
use Excel;
use App\Models\Package;


class ShippingController extends AdminYokartController
{
    private $exceptFunction = ['getTrackingApiInformation', 'updateTrackingApiInformation'];

    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
    }

    public function index()
    {
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::SHIPPING_FULFILMENT)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        })->except($this->exceptFunction);;
        return view('admin.shipping.index');
    }

    public function getListing(Request $request)
    {
        $records['packageRecords'] = ShippingBoxPackage::latest('sbpkg_id')->get();
        $records['dimensions'] = ShippingBoxPackage::getDimensionOptions();
        $records['profilesRecords'] = ShippingProfile::with('zones')->with('products')->get();
        $assignedProducts = ShippingProfileToProduct::count();
        $totalProducts = Product::count();
        $records['unAssignedProducts'] = $totalProducts - $assignedProducts;
        
        return jsonresponse(true, null, $records);
    }
    public function pageLoadData(Request $request)
    {
        $profileId = $request->input('sprofile_id');
        $records['profilesRecords'] = ShippingProfile::where('sprofile_id', $profileId)->first();
        // $records['counrtyStateRecords'] = Region::with('country.states')->get();
        $records['regions'] = Region::get();
        $records['zoneRecordsAll'] = ShippingProfileZone::with('rates')->with('locations.country')->with('locations.states')->where('spzone_sprofile_id', $profileId)->get();
        $records['zoneRecords'] = ShippingProfileZone::with('rates')->with('locationsGrouped.country')->where('spzone_sprofile_id', $profileId)->get();
        $records['selectedProducts'] = ShippingProfileToProduct::getProductsById($profileId);
        $records['selectedProductsIds'] = ShippingProfileToProduct::getProductsById($profileId)->pluck('prod_id');
        $records['rateConditions'] = ShippingRate::RATE_CONDITIONS;
        $configuration = Configuration::getKeyValues(['BUSINESS_NAME','BUSINESS_ADDRESS1', 'BUSINESS_ADDRESS2', 'WEIGHT_UNIT', 'BUSINESS_STATE', 'BUSINESS_COUNTRY']);
        $records['businessDetails'] = $configuration;
        $records['weightType'] = Product::PRODUCT_UNIT_SYSTEMS[$configuration['WEIGHT_UNIT']];
        if (isset($records['businessDetails']['BUSINESS_STATE']) && !empty($records['businessDetails']['BUSINESS_STATE'])) {
            $state = State::getRecordById($records['businessDetails']['BUSINESS_STATE']);
            $records['businessDetails']['BUSINESS_STATE'] = $state->state_name;
        }
        if (isset($records['businessDetails']['BUSINESS_COUNTRY']) && !empty($records['businessDetails']['BUSINESS_COUNTRY'])) {
            $state = Country::getRecordById($records['businessDetails']['BUSINESS_COUNTRY']);
            $records['businessDetails']['BUSINESS_COUNTRY'] = $state->country_name;
        }
        return jsonresponse(true, null, $records);
    }
    public function viewMoreLocations(Request $request, $spzone_id)
    {
        $regions = Region::pluck('region_name', 'region_id')->toArray();
        return jsonresponse(true, null, ['locations' => $this->getLocationDataByZone($spzone_id), 'regions' => $regions]);
    }
    
    public function getRegionsData(Request $request)
    {
        $regions = Region::get();
        return jsonresponse(true, null, $regions);
    }
    public function getCountries(Request $request)
    {
        $profileZones = ShippingProfileZone::where('spzone_sprofile_id', $request->input('sprofile_id'))
        ->pluck('spzone_id')->toArray();
        $countries = Country::where('country_region_id', $request->input('region_id'))
        ->withCount('states')
        ->withCount(['shippingToLocation' => function ($q) use ($profileZones) {
            $q->whereIn('stloc_spzone_id', $profileZones);
        }])
        ->havingRaw('states_count - shipping_to_location_count > 0')
        ->pluck('country_name', 'country_id')->toArray();
        return jsonresponse(true, null, $countries);
    }
    public function getCountriesData(Request $request)
    {
        $countries = Country::where('country_region_id', $request->input('region_id'))->select('country_name', 'country_code', 'country_id')->withCount('states')->get();
        return jsonresponse(true, null, $countries);
    }
    public function getStatesData(Request $request)
    {
        $states = State::where('state_country_id', $request->input('country_id'))->select('state_name', 'state_id')->get();
        return jsonresponse(true, null, $states);
    }
    public function getStates(Request $request)
    {
        $profileZones = ShippingProfileZone::where('spzone_sprofile_id', $request->input('sprofile_id'))
        ->pluck('spzone_id')->toArray();

        $statesIds = State::where('state_country_id', $request->input('country_id'))
        ->leftJoin('shipping_to_locations', 'stloc_state_id', 'state_id')
        ->whereIn('stloc_spzone_id', $profileZones)
        ->pluck('state_id')->toArray();

        $states = State::where('state_country_id', $request->input('country_id'))
        ->whereNotIn('state_id', $statesIds)
        ->pluck('state_name', 'state_id')->toArray();
        return jsonresponse(true, null, $states);
    }
    public function saveZoneLocation(Request $request)
    {
        if (!canWrite(AdminRole::SHIPPING_FULFILMENT)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $region_id = $request->input('region_id');
        $country_id = $request->input('country_id');
        $state_id = $request->input('state_id');
        $spzone_id = $request->input('spzone_id');
        
        if (empty($state_id)) {
            $states = State::where('state_country_id', $country_id)->pluck('state_id')->toArray();
        } else {
            $states = explode(",", $state_id);
        }
        if (!empty($states) && count($states) > 0) {
            foreach ($states as $state) {
                $alreadyExists = ShippingToLocation::where('stloc_spzone_id', $spzone_id)->where('stloc_country_id', $country_id)->where('stloc_state_id', $state)->count();
                if ($alreadyExists == 0) {
                    ShippingToLocation::insert([
                        'stloc_spzone_id' => $spzone_id,
                        'stloc_country_id' => $country_id,
                        'stloc_state_id' => $state
                    ]);
                }
            }
        }

        return jsonresponse(true, __('NOTI_ZONE_UPDATED'), $this->getLocationDataByZone($spzone_id));
    }

    protected function getLocationDataByZone($spzone_id)
    {
        $data = [];
        $locations = ShippingToLocation::where('stloc_spzone_id', $spzone_id)->with('country')->groupBy('stloc_country_id')->get();
        $data['zone'] = ShippingProfileZone::select('spzone_id', 'spzone_name')->where('spzone_id', $spzone_id)->first();
        foreach ($locations as $k=>$location) {
            $data['locations'][$k] = $location;
            $data['locations'][$k]->states = ShippingToLocation::where('stloc_spzone_id', $spzone_id)->where('stloc_country_id', $location->stloc_country_id)->with('states')->get();
        }
        return $data;
    }

    public function removeZoneLocation(Request $request)
    {
        if (!canWrite(AdminRole::SHIPPING_FULFILMENT)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $spzone_id = $request->input('spzone_id');
        $record_id = $request->input('record_id');

        if ($request->input('type') == 'country') {
            ShippingToLocation::where('stloc_spzone_id', $spzone_id)->where('stloc_country_id', $record_id)->delete();
        } else {
            ShippingToLocation::where('stloc_spzone_id', $spzone_id)->where('stloc_state_id', $record_id)->delete();
        }
        return jsonresponse(true, __('NOTI_ZONE_LOCATION_REMOVED'), $this->getLocationDataByZone($spzone_id));
    }

    public function removeProduct(Request $request)
    {
        if (!canWrite(AdminRole::SHIPPING_FULFILMENT)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        ShippingProfileToProduct::where('sprod_sprofile_id', $request->input('sprofile_id'))->where('sprod_prod_id', $request->input('prod_id'))->delete();
        return jsonresponse(true, __('NOTI_SHIPPING_PRODUCT_REMOVED'));
    }

    /**
        * Store a newly created resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @return \Illuminate\Http\Response
    */
    public function store(ShippingBoxPackageRequest $request)
    {
        if (!canWrite(AdminRole::SHIPPING_FULFILMENT)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $shippingArray = $request->all();
        $sbpkg_default = 0;
        if ($shippingArray['sbpkg_default'] && $shippingArray['sbpkg_default'] == 'true') {
            $sbpkg_default = 1;
            ShippingBoxPackage::where('sbpkg_default', 1)->update(['sbpkg_default' =>0]);
        }
        $shippingArray['sbpkg_default'] = $sbpkg_default;
        if ($shippingArray['sbpkg_id'] != '') {
            ShippingBoxPackage::where('sbpkg_id', $shippingArray['sbpkg_id'])->update($shippingArray);
        } else {
            ShippingBoxPackage::create($shippingArray);
        }
        return jsonresponse(true, __('NOTI_SHIPPING_PACKAGE_SAVED'));
    }

    /**
        * Display the specified resource.
        *
        * @param  \App\Shipping  $shipping
        * @return \Illuminate\Http\Response
    */
    public function save(Request $request, $type)
    {
        if (!canWrite(AdminRole::SHIPPING_FULFILMENT)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $message = '';
        switch ($type) {
            case 'profile':
                $response = $this->saveOrUpdateProfile($request);
                $message = __('NOTI_PROFILE_SAVED');
                break;
            case 'zone':
                $response = $this->saveOrUpdateZone($request);
                $message = __('NOTI_ZONE_SAVED');
                break;
            case 'rate':
                $response = $this->saveOrUpdateRates($request);
                $message = __('NOTI_RATE_SAVED');
                break;
            case 'products':
                $response = $this->saveProducts($request);
                $message = __('NOTI_PRODUCT_S_SAVED');
                break;
        }
        return jsonresponse(true, $message, $response);
    }



    protected function saveOrUpdateProfile($request)
    {
        $profileArray = $request->only(['sprofile_name']);
        $profileId = $request->input('sprofile_id');
        if (!empty($profileId)) {
            ShippingProfile::where('sprofile_id', $profileId)->update($profileArray);
        } else {
            $profileId = ShippingProfile::create($profileArray)->id;
        }
        return ['sprofile_id' => $profileId];
    }
    protected function zoneValidator(array $data)
    {
        $validator = Validator::make($data, [
        'spzone_name' => ['required', 'string', 'max:191'],
        ]);
        return $validator->setAttributeNames([
        'spzone_name' => 'name',
        ]);
    }
    protected function saveOrUpdateZone($request)
    {
        $this->zoneValidator($request->all())->validate();
        $profileId = $request->input('sprofile_id');
        $zoneArray = ['spzone_name' => $request->input('spzone_name'), 'spzone_sprofile_id' => $profileId];
        $zoneArray['spzone_shipping_type'] = ($request->input('spzone_shipping_type') == 'true') ? 1 : 0;
        
        $zoneId = ShippingProfileZone::create($zoneArray)->id;

        $selectedLocations =  ShippingToLocation::getSelectedLocations($profileId);

        $addedRegions = [];
        $addedCountries = [];
        if (!empty($request->input('regions'))) {
            $regions = explode(',', $request->input('regions'));
            foreach ($regions as $region) {
                $countries = Country::select('country_id')->where('country_region_id', $region)->get();
                foreach ($countries as $country) {
                    $states = State::select('state_id')->where('state_country_id', $country->country_id)->get();
                    foreach ($states as $state) {
                        if (empty($selectedLocations[$state->state_id])) {
                            ShippingToLocation::create([
                                'stloc_spzone_id' => $zoneId,
                                'stloc_country_id' => $country->country_id,
                                'stloc_state_id' => $state->state_id
                            ]);
                        }
                    }
                }
                $addedRegions[] = $region;
            }
        }

        if (!empty($request->input('countries'))) {
            $countries = explode(',', $request->input('countries'));
            foreach ($countries as $country) {
                $countrySplit = explode('-', $country);
                if (in_array($countrySplit[0], $addedRegions)) {
                    continue;
                }
                $states = State::select('state_id')->where('state_country_id', $countrySplit[1])->get();
                foreach ($states as $state) {
                    if (empty($selectedLocations[$state->state_id])) {
                        ShippingToLocation::create([
                            'stloc_spzone_id' => $zoneId,
                            'stloc_country_id' => $countrySplit[1],
                            'stloc_state_id' => $state->state_id
                        ]);
                    }
                }
                $addedCountries[] = $countrySplit[1];
            }
        }

        if (!empty($request->input('states'))) {
            $states = explode(',', $request->input('states'));
            foreach ($states as $state) {
                $stateSplit = explode('-', $state);
                if (in_array($stateSplit[0], $addedRegions) || in_array($stateSplit[0], $addedCountries)) {
                    continue;
                }
                if (empty($selectedLocations[$stateSplit[2]])) {
                    ShippingToLocation::create([
                    'stloc_spzone_id' => $zoneId,
                    'stloc_country_id' => $stateSplit[1],
                    'stloc_state_id' => $stateSplit[2]
                    ]);
                }
            }
        }

        return ['sprofile_id' => $profileId, 'spzone_id' => $zoneId];
    }
    protected function saveProducts($request)
    {
        $profileId = $request->input('sprofile_id');
        if ($request->input('products')) {
            $productIds = explode(',', $request->input('products'));
        }
        ShippingProfileToProduct::deleteRecords($profileId, $productIds);
        foreach ($productIds as $productId) {
            ShippingProfileToProduct::create([
            'sprod_sprofile_id' => $profileId,
            'sprod_prod_id' => $productId
            ]);
        }

        return ['sprofile_id' => $profileId];
    }
    protected function saveStates($request, $zoneId, $profileId)
    {
        if ($request->input('states')) {
            $selectedLocations =  ShippingToLocation::getSelectedLocations($profileId);
            $states = explode(',', $request->input('states'));
            foreach ($states as $state) {
                $stateSplit = explode('-', $state);
                if (empty($selectedLocations[$stateSplit[1]])) {
                    ShippingToLocation::create([
                    'stloc_spzone_id' => $zoneId,
                    'stloc_country_id' => $stateSplit[0],
                    'stloc_state_id' => $stateSplit[1]
                    ]);
                }
            }
        }
    }

    protected function ratesValidator(array $data)
    {
        $validator = Validator::make($data, [
        'srate_name' => ['required', 'string', 'max:191'],
        'srate_cost' => ['required', 'numeric'],
        ]);
        return $validator->setAttributeNames([
        'srate_name' => 'name',
        'srate_cost' => 'cost',
        ]);
    }
    protected function saveOrUpdateRates($request)
    {
        $this->ratesValidator($request->all())->validate();
        $rateArray = $request->except(['sprofile_id','shipRateCondistion', 'profile_type']);
        if ($request->input('shipRateCondistion') == 'false') {
            $rateArray['srate_type'] = 0;
        }
        if (!empty($rateArray['srate_id'])) {
            ShippingRate::where('srate_id', $rateArray['srate_id'])->update($rateArray);
        } else {
            ShippingRate::create($rateArray);
        }
        return ['sprofile_id' => $request->input('sprofile_id')];
    }

    public function destroy(Request $request, $type)
    {
        if (!canWrite(AdminRole::SHIPPING_FULFILMENT)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $message = '';
        switch ($type) {
            case 'profile':
                $response = $this->deleteProfile($request);
                $message = __('NOTI_PROFILE_DELETED');
                break;
            case 'zone':
                $response = $this->deleteZone($request);
                $message = __('NOTI_ZONE_DELETED');
                break;
            case 'rate':
                $response = $this->deleteRates($request);
                $message = __('NOTI_RATE_DELETED');
                break;
        }
        return jsonresponse(true, $message);
    }
    protected function deleteProfile($request)
    {
        $id = $request->input('id');
        ShippingProfile::where('sprofile_id', $id)->delete();
        ShippingProfileToProduct::where('sprod_sprofile_id', $id)->delete();

        $spzone_ids = ShippingProfileZone::where('spzone_sprofile_id', $id)->pluck('spzone_id')->toArray();
        ShippingProfileZone::where('spzone_sprofile_id', $id)->delete();

        ShippingToLocation::whereIn('stloc_spzone_id', $spzone_ids)->delete();
        ShippingRate::whereIn('srate_spzone_id', $spzone_ids)->delete();
    }
    protected function deleteRates($request)
    {
        return ShippingRate::where('srate_id', $request->input('id'))->delete();
    }
    protected function deleteZone($request)
    {
        $id = $request->input('id');
        ShippingProfileZone::where('spzone_id', $id)->delete();
        ShippingToLocation::where('stloc_spzone_id', $id)->delete();
    }
    protected function deleteStates($zoneId)
    {
        ShippingToLocation::where('stloc_spzone_id', $zoneId)->delete();
    }
    public function getProducts(Request $request)
    {
        $profileId = $request->input('sprofile_id');
        $request['per_page'] = 15;
        $records['products'] = Product::getProducts($request);
        return jsonresponse(true, null, $records);
    }
    public function getSelectedLocations(Request $request)
    {
        $profileId = $request->input('sprofile_id');
        $records['selectedLocations'] = ShippingToLocation::getSelectedLocations($profileId);
        $records['restWord'] = ShippingProfileZone::where('spzone_sprofile_id', $profileId)->where('spzone_shipping_type', ShippingProfileZone::ALL_WORLD_ZONE_TYPE)->count();
        return jsonresponse(true, null, $records);
    }

    /**
        * Show the form for editing the specified resource.
        *
        * @param  \App\Shipping  $shipping
        * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        return view('admin.shipping.addOrEdit', compact('id'));
    }

    /**
        * Update the specified resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @param  \App\Shipping  $shipping
        * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Shipping $shipping)
    {
        //
    }

    /** Shipping Package export */

    public function packageExport(Request $request)
    {
        return Excel::download(new ShippingPackageExport, 'shippingPackage.xlsx');
    }

    /** Shipping Package Import */
    
    public function packageImport(Request $request)
    {
        $shippingPackage = new ShippingPackagesImport();
        $sheetHeadings = (new HeadingRowImport)->toArray($request->file('file'));
        $shippingHeading = ['sno','package_name', 'length', 'width', 'height'];
        foreach ($shippingHeading as $heading) {
            if (!in_array($heading, $sheetHeadings[0][0])) {
                return jsonresponse(false, __('Please import the correct file'));
            }
        }
        Excel::import($shippingPackage, $request->file('file'));
        $error = $shippingPackage->getErrors();
        if (isset($error) && !empty($error)) {
            $export = new ErrorExport($error);
            $name = 'shipping-package-error-'.time().'.xls';
            Excel::store($export, 'public/excel/'.$name);
            return jsonresponse(true, __('NOTI_PACKAGE_IMPORTED'), url('storage/excel/'.$name));
        } else {
            return jsonresponse(true, __('NOTI_PACKAGE_IMPORTED'));
        }
    }

    public function packageForExcel(Request $request)
    {
        $shippingDimention = ShippingBoxPackage::getDimensionOptions();
        $shippingPackage = ShippingBoxPackage::select('sbpkg_id', 'sbpkg_name', 'sbpkg_length', 'sbpkg_width', 'sbpkg_heigth', 'sbpkg_dimension_type');
        if ($request->input('shipping')) {
            $shippingPackage->where('sbpkg_name', 'LIKE', '%' . trim($request->input('shipping') . '%'));
        }
        $data['shippingDimension'] = $shippingDimention;
        $data['maxId'] = ShippingBoxPackage::max('sbpkg_id');
        $data['shipping'] = $shippingPackage->get()->toArray();
        return jsonresponse(true, '', $data);
    }

    public function packageExcelUpdate(Request $request)
    {
        $shippingError = [];
        $shippingData  = [];

        if (!empty($request->all())) {
            $shippingDimention = ShippingBoxPackage::getDimensionOptions();
            foreach ($request->all() as $key => $shippingPackage) {
                if (empty($shippingPackage['sbpkg_name']) && empty($shippingPackage['sbpkg_length']) && empty($shippingPackage['sbpkg_width']) &&  empty($shippingPackage['sbpkg_heigth']) && empty($shippingPackage['sbpkg_dimension_type']) ) {
                    continue;
                }
                $validator = Validator::make(
                    $shippingPackage,
                    [
                        'sbpkg_name' => 'required|unique:shipping_box_packages,sbpkg_name,' . $shippingPackage['sbpkg_id'] . ',sbpkg_id',
                        'sbpkg_length' => 'required',
                        'sbpkg_width' => 'required',
                        'sbpkg_heigth' => 'required',
                        'sbpkg_dimension_type' => ['required', Rule::in($shippingDimention) ]
                    ]
                );
                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $message) {
                        $shippingError[$key]['key'] = $key + 1;
                        $shippingError[$key]['message'] = $message;
                    }
                    array_push($shippingData, $shippingPackage);
                    continue;
                }

                ShippingBoxPackage::firstOrCreate(
                    ['sbpkg_id' => $shippingPackage['sbpkg_id'] ],
                    [
                        'sbpkg_name' => trim($shippingPackage['sbpkg_name']),
                        'sbpkg_length' => $shippingPackage['sbpkg_length'],
                        'sbpkg_width' => $shippingPackage['sbpkg_width'],
                        'sbpkg_heigth' => $shippingPackage['sbpkg_heigth']
                    ]
                );
            }
            if (isset($shippingError) && !empty($shippingError)) {
                $export = new ErrorExport($shippingError);
                $name = 'shipping-error-' . time() . '.xls';
                Excel::store($export, 'public/excel/' . $name);
                $data['shippingError'] = url('storage/excel/' . $name);
            }
            $data['shippingData'] = $shippingData;
        }
        return jsonresponse(true, __('NOTI_PACKAGE_UPDATED'), $data);
    }
    public function getLastId(Request $request)
    {
        return jsonresponse(true, '', ShippingBoxPackage::max('sbpkg_id'));
    }

    public function getTrackingApiInformation(Request $request)
    {
        $data = Package::getTrackShippingPackage();
        if(!empty($data)) {
            return jsonresponse(true, '', $data);
        }
        return jsonresponse(true, '', []);
    }

    public function updateTrackingApiInformation(Request $request)
    {
        if (!canWrite(AdminRole::TRACKING_API)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        if (Package::updateTrackingApiInformation($request->all()) ) {
            return jsonresponse(true, __('NOTI_PACKAGE_UPDATED'));
        }
        return jsonresponse(true, __('NOTI_SOMETHING_WENT_WRONG'));
    }
}
