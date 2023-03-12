<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title" v-if="typeof $route.params.id == 'undefined'">{{ $t('LBL_NEW_TAX_GROUP_SETUP') }}</h3>
                    <h3 class="subheader__title" v-if="typeof $route.params.id != 'undefined'">
                        {{ $canWrite('TAX_SETTINGS') ? $t('LBL_EDITING') + ' -' : ''}} {{adminsData.taxgrp_name}}
                    </h3>
                </div>
            </div>
        </div>

        <div class="container">
            <!--Begin::App-->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <!--begin::Portlet-->
                    <div class="portlet" >
                          <form class="form" id="taxForm">

                            <div class="portlet__body p-0" v-bind:class="[(!$canWrite('TAX_SETTINGS')) ? 'disabledbutton': '']">
                                <div class="row justify-content-center">
                                    <div class="col-lg-12">
                                        <div class="section">
                                            <div class="section__body">
                                                <div class="bg-gray p-4 rounded">
                                                    <div class="form-group">
                                                        <label>{{ $t('LBL_TAX_GROUP_NAME')}} <span class="required">*</span></label>
                                                        <input type="text" v-model="adminsData.taxgrp_name" name="taxgrp_name" class="form-control" :placeholder="$t('LBL_TAX_GROUP_NAME')" v-validate="'required'" :data-vv-as="$t('group name')" data-vv-validate-on="none">
                                                        <span v-if="errors.first('taxgrp_name')" class="error text-danger">{{ errors.first('taxgrp_name') }}</span>
                                                    </div>
                                                </div>
                                                
                                                 <span v-if="errors.first('tgrule_state_type')" class="error text-danger">{{ errors.first('tgtype_state_type') }}</span>
                                        
                                                <div class="tax-rules" :key="taxRuleIndex" v-for="(taxRule, taxRuleIndex) in taxRules">
                                                    <div class="tax-head">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <div class="heading heading--md">{{ $t('LBL_TAX_RULE') }}</div>
                                                            </div>
                                                            <div class="col-auto"  v-if="taxRuleIndex != 0">
                                                                <div class="">
                                                                    <a href="javascript:;" class="btn btn-outline-secondary" @click="deleteRules(taxRuleIndex)">{{ $t('BTN_DELETE_TAX_RULE')}}</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="tax-body">
                                                        <div class="tax-rules YK-taxRule px-3">
                                                            <div class="row">
                                                                <div class="col-lg-4">
                                                                    <div class="border rounded p-4  h-100">
                                                                        <div class="form-group">
                                                                            <label for="example-text-input" class="">{{ $t('LBL_TAX_NAME') }}<span class="required">*</span></label>
                                                                            <input class="form-control" type="text" id="example-text-input" v-model="adminsData.tgrule_name[taxRuleIndex]" :name="'tgrule_name'+taxRuleIndex" v-validate="'required'" :data-vv-as="$t('LBL_TAX_NAME')" data-vv-validate-on="none">
                                                                        <span v-if="errors.first('tgrule_name'+taxRuleIndex)" class="error text-danger">{{ errors.first('tgrule_name'+taxRuleIndex) }}</span>
                                                
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="example-text-input" class="">{{ $t('LBL_TAX_RATE') }}(%) <span class="required">*</span></label>
                                                                            <input class="form-control" type="text" value="" id="example-text-input" v-model="adminsData.tgrule_rate[taxRuleIndex]" :name="'tgrule_rate'+taxRuleIndex" v-validate="'required|decimal:2|between:0,100'" :data-vv-as="$t('LBL_TAX_RATE')" data-vv-validate-on="none">
                                                                            <!--  @keypress="$onlyNumber" -->
                                                                            <span v-if="errors.first('tgrule_rate'+taxRuleIndex)" class="error text-danger">{{ errors.first('tgrule_rate'+taxRuleIndex) }}</span>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="example-text-input" class="">{{ $t('LBL_COUNTRY') }} <span class="required">*</span></label>
                                                                            <select v-model="adminsData.tgrule_country_id[taxRuleIndex]" @change="getStates(taxRuleIndex)" class="input-edited form-control" :name="'tgrule_country_id'+taxRuleIndex" v-validate="'required'" :data-vv-as="$t('country')" data-vv-validate-on="none">
                                                                                <option disabled value>{{ $t('LBL_SELECT_COUNTRY') }}</option>
                                                                                <option v-for="(country, key) in taxRule.countries" :value="key" :key="key">
                                                                                    {{country}}
                                                                                </option>
                                                                            </select>
                                                                            <span v-if="errors.first('tgrule_country_id'+taxRuleIndex)" class="error text-danger">{{ errors.first('tgrule_country_id'+taxRuleIndex) }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-8">
                                                                <div class="border rounded p-4  h-100">
                                                                    <div class="form-group">
                                                                        <label for="example-text-input" class="">{{ $t('LBL_STATE') }}<span class="required">*</span></label>
                                                                        <select class="form-control" v-model="adminsData.tgrule_state_type[taxRuleIndex]"  :name="'tgrule_state_type'+taxRuleIndex" v-validate="'required'" :data-vv-as="$t('states specific')" data-vv-validate-on="none">
                                                                            <option v-for="(stateType, stateTypeKey) in taxRule.stateTypes" :value="stateTypeKey" :key="stateTypeKey">
                                                                                {{stateType}}
                                                                            </option>
                                                                        </select>
                                                                    <span v-if="errors.first('tgrule_state_type'+taxRuleIndex)" class="error text-danger">{{ errors.first('tgrule_state_type'+taxRuleIndex) }}</span>
                                                                    </div>
                                                                    <div class="form-group" v-bind:class="[adminsData.tgrule_state_type[taxRuleIndex] == 0 ?'disabled':'']" > 
                                                                        <label for="example-text-input" class="">{{ $t('LBL_SELECT_STATE') }}</label>
                                                                        <treeselect name="ptbp_prod_id" v-on:deselect="clearValue"  :disabled="adminsData.tgrule_state_type[taxRuleIndex] == 0 || taxRule.states.length==0" v-model="adminsData.tgrule_state_id[taxRuleIndex]" :multiple="true" :value-consists-of="'LEAF_PRIORITY'"  :isDefaultExpanded="true" :options="taxRule.states" :searchable="true" :open-on-click="true" :close-on-select="true" placeholder="Select some" v-validate="{ required: (adminsData.tgrule_state_type[taxRuleIndex] != 0 && taxRule.states.length!=0) ? true : false }" :data-vv-as="$t('LBL_STATE')" data-vv-validate-on="none"/>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label class="checkbox">
                                                                                <input type="checkbox" value="1" class="YK-combinedTax" v-model="adminsData.tgrule_combined[taxRuleIndex]"> {{ $t('LBL_COMBINED_TAX') }}
                                                                                <span></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <table class="table table-bordered table-hover table-edited mt-4 taxdetails YK-taxTable" :class="[`taxdetail-${taxRuleIndex}`]" :data-id="taxRuleIndex" v-if="adminsData.tgrule_combined[taxRuleIndex] == 1">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="55%">{{ $t('LBL_TAX_NAME') }}</th>
                                                                        <th width="30%">{{ $t('LBL_TAX_RATE') }}</th>
                                                                        <th width="15%"></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr class="YK-taxRuleRateRow" v-bind:key="index" v-for="(subtaxRule, index) in taxRule.subTaxRules">
                                                                        <td scope="row">
                                                                            <input type="text" class="input-edited name YK-taxRuleName" name="name">
                                                                        </td>
                                                                        <td><input type="text" class="input-edited rate YK-taxRuleRate" :id="'taxrate_' + taxRuleIndex + '_' + index" name="rate" @keypress="$onlyNumberAndLimit(this, '#taxrate_' + taxRuleIndex + '_' + index)"></td>
                                                                        <td>
                                                                            <div class="actions">
                                                                            <a href="javascript:;" :data-key="taxRuleIndex" class="btn btn-sm btn-light btn-icon YK-taxRuleDeleteBtn" v-bind:class="[index == 0 ? 'disabled' : '']" :disabled="index == 0"><svg>   
                                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'" ></use>                               
                                                                            </svg></a>
                                                                            
                                                                            <a href="javascript:;" 
                                                                                :data-key="taxRuleIndex"
                                                                                class="btn btn-sm btn-light btn-icon YK-taxRuleAddBtn"
                                                                                v-if="index == (taxRule.subTaxRules.length - 1)"
                                                                                >
                                                                                <i class="fas fa-plus"></i>
                                                                            </a>
                                                                        </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row text-center">
                                                    <div class="col-lg-12 mb-4 mt-4">
                                                        <a href="javascript:;" class="btn btn-outline-brand" @click="addTaxRules" v-bind:class="[!$canWrite('TAX_SETTINGS') ? 'disabled no-drop': '']" ><i class="fas fa-plus"></i> {{ $t('BTN_ADD_TAX') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="portlet__foot">
                                <div class="row">
                                    <div class="col">
                                        <router-link :to="{name: 'tax'}" class="btn btn-secondary btn-wide"> {{ $t('BTN_DISCARD')}}</router-link>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-brand btn-wide gb-btn gb-btn-primary" :disabled="!isComplete || clicked==1 || !$canWrite('TAX_SETTINGS')" v-bind:class="[clicked==1 && 'gb-is-loading']" @click="validateRecord"> {{ (adminsData.taxgrp_id == '') ? $t('BTN_TAX_CREATE') : $t('BTN_TAX_UPDATE') }} </button>
                                       
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                    <!--end::Portlet-->
                </div>
            </div>
        </div>
    </div>
    <!--End::App-->
</template>
<script>
    import Treeselect from '@riophae/vue-treeselect';
    import '@riophae/vue-treeselect/dist/vue-treeselect.css';
    const tableFields = {
        'taxgrp_id': '',
        'taxgrp_name': '',
        'taxgrp_description': '',
        'tgrule_name': [],
        'tgrule_rate': [],
        'tgrule_country_id': [],
        'tgrule_state_type': [],
        'tgrule_state_id': [],
        'tgrule_combined': [],
        'taxrulesub_name': [{}],
        'taxrulesub_rate': [{}]
    };

    export default {
        components: {
            Treeselect
        },
        data: function() {
            return {
                baseUrl:baseUrl,
                countries: [],
                stateTypes: [],
                statebyId: [],
                applyOn: [],
                taxRules: [{
                    countries: [],
                    subTaxRules: [],
                    stateTypes: [],
                    states: []
                }],
                adminsData: tableFields,
                taxRuleCount:1,
                clicked: 0
            }
        },
        computed: {
            isComplete () {
                return this.adminsData.taxgrp_name;
            }
        },
        methods: {
            addTaxRules: function() {
                this.taxRules.push({
                    countries: this.countries,
                    stateTypes: this.stateTypes,
                    states: [],
                    subTaxRules: []
                });
                this.addSubTaxRule(this.taxRuleCount);
                this.taxRuleCount += 1;
                this.adminsData.tgrule_country_id[this.taxRuleCount-1] = '';
                this.adminsData.tgrule_state_type[this.taxRuleCount-1] = 0;
            },
            addSubTaxRulesData: function() {
                this.taxRules[0].subTaxRules.push({});
                this.taxRules[0].countries = this.countries;
                this.taxRules[0].stateTypes = this.stateTypes;
                this.adminsData.tgrule_country_id[this.taxRuleCount-1] = '';
                this.adminsData.tgrule_state_type[this.taxRuleCount-1] = 0;
            },
            addSubTaxRule: function(index) {
                if (this.taxRules[index] != 'undefined') {
                    this.taxRules[index].subTaxRules.push({});
                }                
            },
            addTaxDetails: function(index) {
                let clonedRow = $('.taxdetails tbody tr:nth-child(1)').html();
                $(".taxdetails[data-id=" + index + "]").find('tbody').append('<tr>' + clonedRow + '</tr>');
            },
            pageLoadData: function() {
                let formData = this.$serializeData({
                    'id': this.adminsData.taxgrp_id
                });
                this.$http.post(adminBaseUrl + '/tax-group/page-load-data', formData)
                    .then((response) => { //success
                        if (response.data.status == true) {
                            this.countries = response.data.data.countries;
                            this.stateTypes = response.data.data.stateTypes;
                            if (response.data.data['editInfo'].taxTypeGroup.length != 0) {
                                this.addSubTaxRulesData();
                                this.assignValues(response.data.data['editInfo']);
                            } else {
                                this.addSubTaxRulesData();
                            }
                        }
                    });
            },
            assignValues: function(response) {
                this.adminsData.taxgrp_name = response.taxGroup.taxgrp_name;
                this.adminsData.taxgrp_description = response.taxGroup.taxgrp_description;
                let taxDetailArray = [];
                Object.keys(response.taxTypeGroup).forEach(key => {
                    taxDetailArray[key] = response.taxTypeGroup[key].combined_data;
                    if (key != 0) {
                        this.addTaxRules();
                    }
                    this.adminsData.tgrule_name[key] = response.taxTypeGroup[key].tgtype_name;
                    this.adminsData.tgrule_rate[key] = response.taxTypeGroup[key].tgtype_rate;
                    this.adminsData.tgrule_state_type[key] = response.taxTypeGroup[key].tgtype_state_type;
                    this.adminsData.tgrule_country_id[key] = response.taxTypeGroup[key].tgtype_country_id;
                    this.adminsData.tgrule_combined[key] = response.taxTypeGroup[key].tgtype_combined;
                    //this.adminsData.tgrule_state_id[key] = response.taxTypeGroup[key].state_data;
                    //this.getStates(key);
                    this.taxRules[key].states = [];
                    this.adminsData.tgrule_state_id[key] = [];
                    this.statebyId[key] = response.taxTypeGroup[key].totalState;
                    Object.keys(response.taxTypeGroup[key].totalState).forEach(key1 => {
                        this.taxRules[key].states.push({
                            id: key1,
                            label: response.taxTypeGroup[key].totalState[key1]
                        });
                    });
                    let stateId;
                    let stateName;
                    let thisObj = this;
                    Object.keys(response.taxTypeGroup[key].state_data).forEach(statekey => {
                        stateId = response.taxTypeGroup[key].state_data[statekey].tgsd_state_id;
                        thisObj.adminsData.tgrule_state_id[key].push(stateId);
                    });
                    this.assignDetailKeys(key, taxDetailArray[key]);
                    this.assignDetailValues(key, taxDetailArray);
                });
            },
            assignDetailKeys: function(index, taxDetailArray) {
                Object.keys(taxDetailArray).forEach(key => {
                    if (key != 0) {
                        this.addSubTaxRule(index);
                    }
                });
            },
            assignDetailValues: function(index, taxDetailArray) {
                setTimeout(function() {
                    Object.keys(taxDetailArray).forEach(key => {
                        let Localclass = ".taxdetail-" + index;
                        let count = 0;
                        $(Localclass).find('tbody tr').each(function() {
                            var tgc_name = typeof taxDetailArray[index][count] != 'undefined' ? taxDetailArray[index][count].tgc_name : '';
                            var tgc_rate = typeof taxDetailArray[index][count] != 'undefined' ? taxDetailArray[index][count].tgc_rate : '';
                            $(this).find('.name').val(tgc_name);
                            $(this).find('.rate').val(tgc_rate);
                            count++;
                        });
                    });
                }, 200);
            },
            getStates:  function(index) {
                let country_id = this.adminsData.tgrule_country_id[index];
                this.adminsData.tgrule_state_id[index] = [];
                this.statebyId[index] = [];
                let formData = this.$serializeData({
                    'country_Id': country_id
                });
                this.$http.post(adminBaseUrl + '/tax-group/get-states', formData)
                    .then((response) => { //success
                        if (response.data.status == true) {
                            this.taxRules[index].states = [];
                            this.statebyId[index] = response.data.data;
                            Object.keys(response.data.data).forEach(key => {
                                this.taxRules[index].states.push({
                                    id: key,
                                    label: response.data.data[key]
                                });
                            });
                        }
                    });
            },
            deleteRules: function(index) {
                this.$delete(this.adminsData.tgrule_name, index);
                this.$delete(this.adminsData.tgrule_rate, index);
                this.$delete(this.adminsData.tgrule_state_type, index);
                this.$delete(this.adminsData.tgrule_country_id, index);
                this.$delete(this.adminsData.tgrule_state_id, index);
                this.$delete(this.adminsData.tgrule_combined, index);
                this.$delete(this.taxRules, index);
            },
            validateRecord: function() {
                this.$validator.validateAll().then(res=> {
                    if(res) {
                        this.clicked = 1;
                        let input = this.adminsData;
                        this.saveRecord(input);
                    } else {
                        this.clicked = 0;
                    }
                })
            },
            taxDetailArray: function() {
                let detailsArray = [];
                let dataId = '';
                $('.taxdetails').each(function() {
                    dataId = $(this).data('id');
                    detailsArray[dataId] = [];
                    $(".taxdetail-" + dataId).find('tbody tr').each(function() {
                        detailsArray[dataId].push({
                            name: $(this).find('.name').val(),
                            rate: $(this).find('.rate').val()
                        });
                    });
                });
                return JSON.stringify(detailsArray);
            },
            saveRecord: function(input) {
                var taxrateErrors = 0;
                $( ".YK-taxRuleRateRow" ).each(function( index ) {
                    $( this ).find('span.error').remove();

                    var nameField = $( this ).find('.YK-taxRuleName');
                    var rateField = $( this ).find('.YK-taxRuleRate');

                    if (nameField.val() == '' || nameField.val() == 0) {
                        nameField.parent().append('<span class="error text-danger">The name field is required</span>');
                        taxrateErrors += 1;
                    }
                    if (rateField.val() == '' || rateField.val() == 0) {
                        if (rateField.val() == 0) {
                            rateField.parent().append('<span class="error text-danger">The rate field should not be zero</span>');
                        } else { 
                            rateField.parent().append('<span class="error text-danger">The rate field is required</span>');
                        }
                        
                        taxrateErrors += 1;
                    }
                });

                if(taxrateErrors > 0){
                    this.clicked = 0;
                    return false;
                }

                input['taxdetails'] = this.taxDetailArray();
                input['state_ids'] = JSON.stringify(input.tgrule_state_id);
                this.$http.post(adminBaseUrl+'/tax-group', this.$serializeData(input))
                .then((response) => { //success
                    this.clicked = 0;
                    if (response.data.status == true) {
                        this.$router.push({
                            name: 'tax'
                        });
                        toastr.success(response.data.message, '', toastr.options);
                    }
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    }, (response) => { //error
                        this.clicked = 0;
                    this.validateErrors(response);
                });
            },
            emptyFormValues: function() {
                this.adminsData = {
                    'taxgrp_id': '',
                    'taxgrp_name': '',
                    'taxgrp_description': '',
                    'tgrule_name': [],
                    'tgrule_rate': [],
                    'tgrule_country_id': [],
                    'tgrule_state_type': [],
                    'tgrule_state_id': [],
                    'tgrule_combined': [],
                    'taxrulesub_name': [
                        []
                    ],
                    'taxrulesub_rate': [
                        []
                    ]
                };
                this.errors.clear();
            },
            clearValue : function(node, instanceId) {
                let instance = instanceId.substr(0, 1)
                this.adminsData.tgrule_state_id[instance].splice(this.adminsData.tgrule_state_id[instance].indexOf(parseInt(node.id)), 1);
                this.adminsData.tgrule_state_id[instance] = [...this.adminsData.tgrule_state_id[instance]];
            },
        },
        mounted() {
            let self = this;
            $(document).on('click', '.YK-taxRuleAddBtn', function(e){
                let thisObj = $(this);
                var taxRuleRow = thisObj.closest(".YK-taxRuleRateRow");
                if(thisObj.hasClass('disabled') || typeof thisObj.attr('disabled') != 'undefined' || taxRuleRow.find('.YK-taxRuleName').val() == '' || taxRuleRow.find('.YK-taxRuleRate').val() == ''){
                    return false;
                }
                let key = $(this).data('key');
                self.addSubTaxRule(key);
                setTimeout(() => {   
                    $('.YK-taxTable[data-id="'+key+'"]').find('tr.YK-taxRuleRateRow:last td:last .YK-taxRuleAddBtn').attr('disabled', true).addClass('disabled');
                }, 50);
            });
            $(document).on('click', '.YK-taxRuleDeleteBtn', function(e){
                let thisObj = $(this);
                if(thisObj.hasClass('disabled') || typeof thisObj.attr('disabled') != 'undefined'){
                    return false;
                }
                let key = $(this).data('key');
                setTimeout(() => {
                    let totalRows = $('.YK-taxTable[data-id="'+ key +'"]').find('tr.YK-taxRuleRateRow').length;
                    thisObj.next().appendTo($('.YK-taxTable[data-id="'+ key +'"]').find('tr.YK-taxRuleRateRow:nth-child(' + (totalRows - 1) + ') td:last .actions'));
                    thisObj.attr('disabled', true).addClass('disabled');
                    thisObj.closest('tr').remove();
                    let prevRow = $('.YK-taxTable[data-id="'+ key +'"]').find('tr.YK-taxRuleRateRow:nth-child(' + (totalRows - 1) + ')');
                    let name = prevRow.find('.YK-taxRuleName').val();
                    let rate = prevRow.find('.YK-taxRuleRate').val();
                    let btn = prevRow.find('.YK-taxRuleAddBtn');
                    if(name != "" && rate != ""){
                        btn.removeAttr('disabled').removeClass('disabled');
                    }else{
                        btn.attr('disabled', true).addClass('disabled');
                    }
                }, 50);
            });
            $(document).on('keyup', '.YK-taxRuleRateRow input', function(e){
                let name = $( this ).closest('.YK-taxRuleRateRow').find('.YK-taxRuleName').val();
                let rate = $( this ).closest('.YK-taxRuleRateRow').find('.YK-taxRuleRate').val();
                let btn = $( this ).closest('.YK-taxRuleRateRow').find('.YK-taxRuleAddBtn');
                if(name != "" && rate != ""){
                    btn.removeAttr('disabled').removeClass('disabled');
                }else{
                    btn.attr('disabled', true).addClass('disabled');
                }
            });
            $( ".YK-taxRuleRateRow" ).each(function( index ) {
                let name = $( this ).find('.YK-taxRuleName').val();
                let rate = $( this ).find('.YK-taxRuleRate').val();
                let btn = $( this ).find('.YK-taxRuleAddBtn');
                if(name != "" && rate != ""){
                    btn.removeAttr('disabled').removeClass('disabled');
                }else{
                    btn.attr('disabled', true).addClass('disabled');
                }
            });
            $(document).on('click', '.YK-combinedTax', function(e){
                let thisObj = $(this);
                setTimeout(() => {
                    if(thisObj.prop("checked") == true){
                        thisObj.closest('.YK-taxRule').find('.YK-taxRuleName:first').focus();
                        let btn = thisObj.closest('.YK-taxRule').find('.YK-taxRuleAddBtn');
                        btn.attr('disabled', true).addClass('disabled');
                    }    
                    thisObj.closest('.YK-taxRule').find('.YK-taxRuleRateRow').each(function( index, element ) {
                        if(index > 0){
                            $(this).remove();
                        }                  
                    });   
                }, 100);   
            });
            this.emptyFormValues();
            let id = this.$route.params.id;
            if (id) {
                this.adminsData.taxgrp_id = id;
            }
            this.pageLoadData();
        }
    }
</script>