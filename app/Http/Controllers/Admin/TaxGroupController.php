<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Models\State;
use App\Models\TaxGroup;

use App\Models\TaxGroupType;
use App\Models\TaxGroupStatesData;
use App\Models\TaxGroupCombined;
use App\Models\Admin\AdminRole;
use App\Http\Controllers\Admin\AdminYokartController;
use Illuminate\Http\Request;
use App\Http\Requests\TaxRequest;
use DB;
use Auth;

class TaxGroupController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::TAX_SETTINGS)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getListing(Request $request)
    {
        $records['taxGroup'] = TaxGroup::getRecords($request->all());
        $records['stateTypes'] = TaxGroup::STATE_TYPES;
        $records['showEmpty'] = 0;
        if (empty($request['search']) && $records['taxGroup']->count() == 0) {
            $records['showEmpty'] = 1;
        }

        return jsonresponse(true, null, $records);
    }

    public function pageLoadData(Request $request)
    {
        $records['countries'] = Country::pluck('country_name', 'country_id');
        $records['stateTypes'] = TaxGroup::STATE_TYPES;
        $records['taxApplyOn'] = TaxGroup::TAX_APPLY_ON;
        $records['editInfo'] = $this->taxRecordById($request->input('id'));
        return jsonresponse(true, null, $records);
    }

    public function taxRecordById($id)
    {
        $records['taxGroup'] = TaxGroup::where('tax_groups.taxgrp_id', $id)->first();
        $records['taxTypeGroup'] = TaxGroupType::groupTypesById($id);
        $records['states'] = TaxGroupType::allStates($id);
        return $records;
    }
    public function getStatesById(Request $request)
    {
        $records = State::where('state_country_id', $request->input('country_Id'))->pluck('state_name', 'state_id');
        return jsonresponse(true, null, $records);
    }

    /**
        * Store a newly created resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @return \Illuminate\Http\Response
    */
    public function store(TaxRequest $request)
    {
        if (!canWrite(AdminRole::TAX_SETTINGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $taxDetails = json_decode($request->input('taxdetails'), true);
        // dd($taxDetails);
        $taxGroupArray = $request->only(['taxgrp_name', 'taxgrp_description']);
        $taxGroupArray['taxgrp_description'] = (!empty($taxGroupArray['taxgrp_description']) ? $taxGroupArray['taxgrp_description'] : '');
        if (!empty($request->input('taxgrp_id'))) {
            TaxGroup::where($request->only('taxgrp_id'))->update($taxGroupArray);
            $taxGroupInsertedId = $request->input('taxgrp_id');
        } else {
            $taxGroupInsertedId = TaxGroup::create($taxGroupArray)->id;
        }
        $this->destroy($taxGroupInsertedId, false);
        $this->saveUpdateGroupTypes($request, $taxGroupInsertedId);
        return jsonresponse(true, __('Tax Group saved.'), ['taxgrp_id' => $taxGroupInsertedId]);
    }
    
    public function saveUpdateGroupTypes($request, $taxGroupInsertedId)
    {
        if (!canWrite(AdminRole::TAX_SETTINGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $taxDetails = json_decode($request->input('taxdetails'), true);
        $groupTypeNames = explode(',', $request->input('tgrule_name'));
        $groupTypeRates = explode(',', $request->input('tgrule_rate'));
        $groupTypeStateTypes = explode(',', $request->input('tgrule_state_type'));
        $groupTypeCountryIds = explode(',', $request->input('tgrule_country_id'));
        $groupTypeStateids = json_decode($request->input('state_ids'), true);
        $groupTypeCombined = explode(',', $request->input('tgrule_combined'));
        $stateData = array();
        foreach ($groupTypeNames as $key => $groupTypeName) {
            $combined =  (!empty($groupTypeCombined[$key]) && filter_var($groupTypeCombined[$key], FILTER_VALIDATE_BOOLEAN) === true) ? 1 : 0;
            $groupTypeId =  TaxGroupType::create([
                'tgtype_tgroup_id' => $taxGroupInsertedId,
                'tgtype_name' => $groupTypeName,
                'tgtype_rate' => $groupTypeRates[$key],
                'tgtype_state_type' => $groupTypeStateTypes[$key],
                'tgtype_country_id' => $groupTypeCountryIds[$key],
                'tgtype_combined' => $combined,
            ])->tgtype_id;
            if (isset($groupTypeCombined[$key]) && $groupTypeCombined[$key] == true && isset($taxDetails[$key])) {
                $this->saveGroupCombinedRecords($taxDetails[$key], $groupTypeId);
            }
            if (empty($groupTypeStateids[$key])) {
                continue;
            }
            $stateData[$groupTypeId] = $groupTypeStateids[$key];
        }

        $this->saveGroupStateRecords($stateData);
    }
    public function saveGroupCombinedRecords($taxDetails, $groupTypeId)
    {
        if (!canWrite(AdminRole::TAX_SETTINGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        if (isset($taxDetails) && !empty($taxDetails)) {
            foreach ($taxDetails as $taxDetailData) {
                if (empty($taxDetailData['name']) && empty($taxDetailData['rate'])) {
                    continue;
                }
                TaxGroupCombined::create([
                'tgc_tgtype_id' => $groupTypeId,
                'tgc_name' => $taxDetailData['name'],
                'tgc_rate' => $taxDetailData['rate'],
                //'tgc_applied_on'=>$taxDetailData['applyOn']
                ]);
            }
        }
    }
    public function saveGroupStateRecords($stateData)
    {
        if (!canWrite(AdminRole::TAX_SETTINGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        foreach ($stateData as $key => $stateDataRecords) {
            foreach ($stateDataRecords as $groupTypeStateid) {
                TaxGroupStatesData::create([
                'tgsd_tgtype_id' => $key,
                'tgsd_state_id' => $groupTypeStateid,
                ]);
            }
        }
    }

    public function destroy($id, $taxGroupDelete = true)
    {
        if (!canWrite(AdminRole::TAX_SETTINGS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        DB::beginTransaction();
        try {
            TaxGroup::deleteTaxGroup($id, $taxGroupDelete);
            DB::commit();
            return jsonresponse(true, __('NOTI_TAX_GROUP_DELETED'));
        } catch (\Exception $e) {
            DB::rollBack();
            return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
        }
    }
}
