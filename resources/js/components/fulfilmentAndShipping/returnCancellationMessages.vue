<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main"> 
                    <h3 class="subheader__title">{{ $t('LBL_RETURN_CANCELLATION_REASONS') }}</h3>                    
                </div>
                <div class="subheader__toolbar">
                    <a @click="openAddPage" class="btn btn-white" href="javascript:void(0);" v-bind:class="[(!$canWrite('RETURN_AND_CANCELLATION')) ? 'disabledbutton': '']">
                        <i class="fas fa-plus"></i>{{ $t('BTN_ADD') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                 <div class="" v-bind:class="[(showEmpty == 1 && reason_type == '') ? 'col-md-12': 'col-md-7']">
                    <div class="portlet portlet--height-fluid">
                        <div class="portlet__body pb-0 bg-gray flex-grow-0" v-if="showEmpty == 0 || reason_type != ''">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="input-group input-icon input-icon--left">
                                            <input type="text" class="form-control" :placeholder="$t('PLH_SEARCH')" id="generalSearch"  :readonly="records.length == 0 && search === '' && reason_type == ''" v-model="search" @keyup="searchRecords" :maxlength="maxCharacterLimit" />
                                            <span class="input-icon__icon input-icon__icon--left">
                                                <span><i class="la la-search"></i></span>
                                            </span>
                                            <div class="input-group-append">
                                                <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle" aria-expanded="true">
                                                    {{reason_type_label | camelCase}}
                                                </button>
                                                <div class="dropdown-menu" x-placement="top-start">
                                                    <a class="dropdown-item" href="javascript:;" @click="selectType('All', 'show-all')" :disabled="records.length == 0 && reason_type == ''">{{$t("LBL_ALL")}}</a>
                                                    <a class="dropdown-item" href="javascript:;" v-for="(type, key) in types" v-bind:key="key" @click="selectType(type, key)" :disabled="records.length == 0 && reason_type == ''">{{ $t('LBL_'+type.toUpperCase())}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>                            
                        </div>
                        <hr class="m-0">
                        <div class="portlet__body portlet__body--fit" v-if="(showEmpty == 0 && pageLoaded==1) || reason_type != ''">
                            <div class="section mb-0">
                                <div class="section__content">
                                    <table class="table table-data table-justified">
                                        <thead>
                                            <tr>
                                                <th>{{ '#' }}</th>
                                                <th>{{ $t('LBL_REASON_TITLE') }}</th>
                                                <th>{{ $t('LBL_REASON_TYPE') }}</th>
                                                <th>{{ $t('LBL_PUBLISH') }}</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="loading==false && records.length == 0 && (search != '' || reason_type != '' ) && pageLoaded==1">
                                            <tr>
                                                <td colspan="5">  
                                                    <noRecordsFound></noRecordsFound> 
                                                </td>
                                            </tr>
                                        </tbody>  
                                        <tbody v-else>
                                            <tr v-for="(record, index) in records" :key="record.reason_id" :data-id="record.reason_id">
                                                <td scope="row">{{ pagination.from+index }}</td>
                                                <td width="55%">
                                                    <a href="javascript:;" @click.prevent="editRecord(record)" v-if="!$canWrite('RETURN_AND_CANCELLATION')">{{ record.reason_title }}</a>
                                                    <div v-else>{{ record.reason_title }}</div>
                                                </td>
                                                <td>{{ record.reason_type_label }}</td>
                                                <td>
                                                    <label class="switch switch--sm" v-b-tooltip.hover :title="record.reason_publish == 1 ? $t('TTL_UNPUBLISH') : $t('TTL_PUBLISH') ">
                                                        <input type="checkbox" name="reason_publish" v-model="record.reason_publish" @change="onSwitchStatus($event, record.reason_id)" v-bind:class="[(!$canWrite('RETURN_AND_CANCELLATION')) ? 'disabledbutton': '']">
                                                        <span></span>
                                                    </label>
                                                </td>
                                                <td width="15%">
                                                    <div class="actions">
                                                        <a href="javascript:;" class="btn btn-light btn-sm btn-icon" @click="editRecord(record)" v-b-tooltip.hover :title="$t('TTL_EDIT')" v-bind:class="[(!$canWrite('RETURN_AND_CANCELLATION')) ? 'disabledbutton': '']"><svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                                                            </svg></a>
                                                        <a class="btn btn-light btn-sm btn-icon " href="javascript:;" @click.capture="confirmDelete($event, record.reason_id)" v-b-tooltip.hover :title="$t('TTL_DELETE')" v-bind:class="[(!$canWrite('RETURN_AND_CANCELLATION')) ? 'disabledbutton': '']">
                                                            <svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'" ></use>                               
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="portlet__body" v-if="showEmpty == 1 && pageLoaded==1 && reason_type == '' && search == ''">
                            <div class="get-started ">
                                <div class="get-started-arrow d-flex justify-content-end mb-5">
                                    <img :src="baseUrl+'/admin/images/get-started-graphic/get-started-arrow-head.svg'"/>
                                </div>
                                <div class="no-content d-flex text-center flex-column mb-7">
                                    <p>{{ $t('LBL_CLICK_THE') }} <a href="javascript:;" @click="openAddPage" class="btn btn-brand btn-small"><i class="fas fa-plus"></i> {{ $t('BTN_ADD') }} </a> {{ $t('LBL_BUTTON_TO_CREATE_REASONS') }}</p>
                                </div>
                                <div class="get-started-media">
                                    <img :src="baseUrl+'/admin/images/get-started-graphic/get-started-faq.svg'"/>
                                </div>
                            </div>
                        </div>
                        <loader v-if="loading"></loader>    
                        <div class="portlet__foot" v-if="records.length > 0">
                            <!-- Pagination -->
                            <pagination :pagination="pagination" @currentPage="currentPage"> </pagination>
                        </div>
                    </div>
                </div>

                <div class="col-md-5" v-if="showEmpty == 0 || reason_type != ''">
                    <div class="portlet portlet--height-fluid">
                        <div class="portlet__head">
                            <div class="portlet__head-label">
                                <h3 class="portlet__head-title" v-if="selectedPage == 'editform'">
                                    {{ $canWrite('RETURN_AND_CANCELLATION') ? $t('LBL_EDITING') + ' -' : ''}}
                                    <span class="editing-txt">{{editText}}</span>
                                </h3>
                                <h3 class="portlet__head-title" v-if="selectedPage == 'addform'">{{ $t('LBL_NEW_REASON_SETUP')}}</h3>
                            </div>
                        </div>
                        <div class="portlet__body" v-if="selectedPage"  v-bind:class="[(!$canWrite('RETURN_AND_CANCELLATION')) ? 'disabledbutton': '']">
                            <input type="hidden" v-if="adminsData.reason_id != ''"  name="id" v-model="adminsData.reason_id">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-6 col-form-label">{{ $t('LBL_REASON_TYPE')}}</label> 
                                        <div class="col-md-6">
                                            <div class="radio-inline">
                                                <label class="radio" v-for="(type, key) in types" v-bind:key="key">
                                                    <input type="radio" :value="key" v-model="adminsData.reason_type" name="reason_type">{{ $t('LBL_'+type.toUpperCase())}}
                                                    <span></span>
                                                </label> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>{{ $t('LBL_REASON_TITLE')}} <span class="required">*</span></label>
                                    <div class="form-group">
                                        <input type="text" v-model="adminsData.reason_title" name="reason_title" v-validate="'required'" :data-vv-as="$t('LBL_REASON_TITLE') " data-vv-validate-on="none" class="form-control" />
                                        <span v-if="errors.first('reason_title')" class="error text-danger">{{ errors.first('reason_title') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet__body" v-if="selectedPage == ''">
                            <div class="no-data-found">
                                <div class="img"><img :src="this.$noDataImage()" alt=""></div>
                                <div class="data">
                                    <p> {{ $t ('LBL_USE_ICONS_FOR_ADVANCED_ACTIONS') }}</p>         
                                </div>
                            </div>
                        </div>

                        <div class="portlet__foot" v-if="selectedPage != ''">
                            <div class="row">
                                <div class="col">
                                    <button type="reset" class="btn btn-secondary" @click="cancel()"> {{ $t('BTN_DISCARD')}} </button>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-brand gb-btn gb-btn-primary" @click="validateRecord()" :disabled="!isComplete || clicked==1 || !$canWrite('RETURN_AND_CANCELLATION')" v-if="selectedPage == 'addform'" v-bind:class="clicked==1 && 'gb-is-loading'">{{ $t('BTN_REASON_CREATE') }}</button>
                                    <button type="submit" class="btn btn-brand gb-btn gb-btn-primary" @click="validateRecord()" :disabled="!isComplete || clicked==1 || !$canWrite('RETURN_AND_CANCELLATION')" v-if="selectedPage == 'editform'" v-bind:class="clicked==1 && 'gb-is-loading'">{{ $t('BTN_REASON_UPDATE') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <DeleteModel :deletePopText="$t('LBL_REASON_DELETE_TEXT')" :recordId="recordId" @deleted="deleteRecord(recordId)"></DeleteModel>
            </div>
        </div>
    </div>
</template>
<script>
    const tableFields = {
        'reason_id': '',
        'reason_type': 1,
        'reason_type_label': '',
        'reason_title': '',
        'reason_publish': ''
    };
    const searchFields = {
        'search': ''
    };
    export default {
        data: function() {
            return {
                records: [],
                baseUrl: baseUrl,
                pagination: [],
                clicked: 0,
                loading: false,
                adminsData: tableFields,
                searchData: searchFields,
                search: '',
                selectedPage: '',
                recordId: '',
                editText: '',
                maxCharacterLimit:"30",
                types: [],
                reason_type: 'show-all',  
                reason_type_label: 'All',  
                pageLoaded: 0,          
                showEmpty: 1
            }
        },
        computed: {
            isComplete() {
                return this.adminsData.reason_title;
            }
        },
        methods: {
            selectType: function(label, key) {
                this.reason_type_label = label;
                this.reason_type = key;
                this.pageRecords(1);
                this.cancel();
                this.emptyFormValues();
            },
            emptyFormValues: function() {
                this.adminsData = {
                    'reason_id': '',
                    'reason_type': 1,
                    'reason_type_label': '',
                    'reason_title': '',
                    'reason_publish': ''
                };
                this.errors.clear();
            },
            pageRecords: function(pageNo, pageLoad = false) {
                this.loading = pageLoad;
                if (typeof this.pagination.per_page !== 'undefined') {
                    Object.assign(this.searchData, {
                        'per_page': this.pagination.per_page
                    });
                }
                Object.assign(this.searchData, {
                    'search': this.search
                });
                let formData = this.$serializeData(this.searchData);
                formData.append('reason_type', (this.reason_type != 'show-all' ? this.reason_type : '' ));
                this.$http.post(adminBaseUrl + '/reasons/list?page=' + pageNo, formData).then((response) => {
                    this.records = response.data.data.reasons.data;
                    this.pagination = response.data.data.reasons;
                    this.types = response.data.data.types;
                    this.loading = false;
                    this.showEmpty = response.data.data.showEmpty;   
                    this.pageLoaded = 1;
                });
            },
            searchRecords: function() {
                this.pageRecords(this.pagination.current_page);
            },
            currentPage: function(page) {
                this.pageRecords(page);
            },
            clearSearch: function() {
                this.searchData = {
                    'search': ''
                };
                this.pageRecords(this.pagination.current_page);
            },
            validateRecord: function() {
                this.$validator.validateAll().then(res => {
                    if (res) {
                        let input = this.adminsData;
                        this.clicked = 1;
                        if (input.reason_id != '') {
                            this.updateRecord(input.reason_id, input);
                        } else {
                            this.saveRecord(input);
                        }
                    } else {
                        this.clicked = 0;
                    }
                });
            },
            saveRecord: function(input) {
                let formData = this.$serializeData(input);
                this.$http.post(adminBaseUrl + '/reasons', formData)
                    .then((response) => {
                        if (response.data.status == false) {
                            toastr.error(response.data.message, '', toastr.options);
                            return;
                        }
                        toastr.success(response.data.message, '', toastr.options);
                        this.pageRecords(this.pagination.current_page);
                        this.selectedPage = '';
                        this.emptyFormValues();
                        this.clicked = 0;
                    }, (response) => {
                        this.clicked = 0;
                        this.validateErrors(response);
                    });
            },
            editRecord: function(input) {
                this.adminsData = input;
                this.selectedPage = 'editform';
                this.editText = input.reason_title;
            },
            updateRecord: function(id, input) {
                let formData = this.$serializeData({
                    'reason_type': input.reason_type,
                    'reason_title': input.reason_title
                });
                formData.append('_method', 'put');
                this.$http.post(adminBaseUrl + '/reasons/' + id, formData)
                    .then((response) => {
                        if (response.data.status == false) {
                            toastr.error(response.data.message, '', toastr.options);
                            return;
                        }
                        toastr.success(response.data.message, '', toastr.options);
                        this.emptyFormValues();
                        this.pageRecords(this.pagination.current_page);
                        this.selectedPage = '';
                        this.clicked = 0;
                    }, (response) => {
                        this.clicked = 0;
                        this.validateErrors(response);
                    });
            },
            openAddPage: function() {
                this.emptyFormValues();
                this.selectedPage = 'addform';
                if(this.search != ""){
                    this.search = "";
                    this.pageRecords();
                }    
                this.showEmpty = 0;
            },
            cancel: function() {
                this.selectedPage = false;
                if(this.records.length == 0){
                    this.showEmpty = 1;
                }    
            },
            validateErrors: function(response) {
                let jsondata = response.data;
                Object.keys(jsondata.errors).forEach(key => {
                    this.errors.add({
                        field: key,
                        msg: jsondata.errors[key][0]
                    });
                });
            },
            onSwitchStatus: function(event, id) {
                let formData = this.$serializeData({
                    'status': event.target.checked
                });
                this.$http.post(adminBaseUrl + '/reasons/status/' + id, formData).then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, "", toastr.options);
                        return;
                    }
                    toastr.success(response.data.message, '', toastr.options);
                });
            },
            confirmDelete: function(event, dataid) {
                event.stopPropagation();
                this.recordId = dataid;
                this.$bvModal.show('deleteModel');
            },
            deleteRecord: function(recordId) {
                this.$http.delete(adminBaseUrl + '/reasons/' + recordId).then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    toastr.success(response.data.message, '', toastr.options);
                    this.pageRecords(this.pagination.current_page);
                    this.$bvModal.hide('deleteModel');
                    this.selectedPage = '';
                });
            },
        },
        mounted: function() {
            this.pageRecords(1, true);
        }
    }
</script>