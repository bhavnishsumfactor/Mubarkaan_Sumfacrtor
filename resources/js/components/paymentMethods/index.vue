<template>
<div>
    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_PAYMENT_METHODS') }}</h3>
            </div>
        </div>
    </div>

  <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="portlet portlet--height-fluid">
                    <div class="portlet__body pb-0 bg-gray flex-grow-0">
                        <div class="form-group">
                            <div class="input-icon input-icon--left">
                                <input type="text" class="form-control" :placeholder="$t('PLH_SEARCH')" id="generalSearch" v-model="searchData.search" @keyup="searchRecords">
                                <span class="input-icon__icon input-icon__icon--left">
                                    <span><i class="la la-search"></i></span>
                                </span>
                                <span class="input-icon__icon input-icon__icon--right" v-if="searchData.search!=''" @click="searchData.search='';pageRecords(1);">
                                    <span><i class="fas fa-times"></i></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="portlet__body portlet__body--fit" v-if="records.length > 0">
                        <div class="section">
                            <div class="section__content">
                                <table class="table table-data table-justified">
                                    <thead>
                                        <tr>
                                            <th>{{ '#' }}</th>
                                            <th>{{ $t('LBL_PAYMENTMETHOD_NAME') }}</th>
                                            <th>{{ $t('LBL_PUBLISH') }}</th>
                                            <th>{{ $t('LBL_ENVIRONMENT') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(record, index) in records" :key="index">
                                            <td scope="row">{{ pagination.from+index }}</td>
                                            <td>{{ record.pkg_name }}</td>
                                            <td>
                                                <label class="switch switch--sm">
                                                    <input type="checkbox" v-model="record.pkg_publish" @change="onSwitchStatus($event, record.pkg_id, record.pkg_card_type)">
                                                    <span v-b-tooltip.hover :title="record.pkg_publish == 1 ? $t('TTL_UNPUBLISH') : $t('TTL_PUBLISH') "></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch switch--sm">
                                                    <input type="checkbox" v-model="record.pkg_environment" @change="switchEnvironment($event, record.pkg_id, record.pkg_environment)">
                                                    <span v-b-tooltip.hover :title="record.pkg_environment == 1 ? $t('TTL_SANDBOX') : $t('TTL_PRODUCTION') "></span>
                                                </label>
                                            </td>
                                            <td>
                                                <div class="actions">
                                                    <a class="btn btn-light btn-sm btn-icon " href="javascript:;" v-b-tooltip.hover :title="$t('TTL_EDIT')" @click.prevent="editRecord(record)">
                                                        <svg>   
                                                            <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#system-settings'"></use>                               
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
                    <loader v-if="loading"></loader>        
                    <noRecordsFound v-if="loading==false && records.length == 0"></noRecordsFound> 
                    <div class="portlet__foot" v-if="records.length > 0">
                        <pagination :pagination="pagination" @currentPage="currentPage"> </pagination>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="portlet portlet--height-fluid">
                    <div class="portlet__head" v-if="selectedPage">
                        <div class="portlet__head-label">
                            <h3 class="portlet__head-title" v-if="selectedPage == 'editform'">{{ $t('LBL_SETTING') }} - {{adminsData.pkg_name}}</h3>
                        </div>
                    </div>

                    <div class="portlet__body" v-if="selectedPage">
                        <div class="row">
                            <div class="col-md-12">
                                <label>{{ $t('LBL_PAYMENTMETHOD_NAME') }} <span class="required">*</span></label>
                                <div class="form-group">
                                    <input type="text" v-model="adminsData.pkg_name" name="pkg_name" v-validate="'required'" :data-vv-as="$t('LBL_PAYMENTMETHOD_NAME')" data-vv-validate-on="none" class="form-control" />
                                    <span v-if="errors.first('pkg_name')" class="error text-danger">{{ errors.first('pkg_name') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row" v-for="(configuration, index) in configurations" :key="'config'+index">
                            <div class="col-md-12">
                                <label>{{ configuration.name.replace('_',' ') }} </label>
                                <div class="form-group">
                                    <input type="text" v-model="configuration.value" :name="configuration.key" v-validate="'required'" :data-vv-as="$t(configuration.name.replace('_',' '))" data-vv-validate-on="none" class="form-control" />
                                    <span v-if="errors.first(configuration.key)" class="error text-danger">{{ errors.first(configuration.key) }}</span>
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
                                <button type="reset" class="btn btn-secondary" @click="cancel()">{{ $t('BTN_DISCARD')}}</button>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-brand gb-btn gb-btn-primary" @click="validateRecord()" :disabled='!isComplete || clicked==1' v-if="selectedPage == 'editform'" v-bind:class="clicked==1 && 'gb-is-loading'">{{ $t('BTN_PAYMENTMETHOD_UPDATE') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ConfirmModel :confirmPopText="confirmText" :recordId="recordId" @confirm="confirm(recordId)"></ConfirmModel>
        </div>
    </div>
</div>
</template>
<script>

const tableFields = {
    'pkg_id': '',
    'pkg_name': '',
};
const searchFields = {
    'pkg_name': ''
};
export default {
    data: function() {
        return {
            adminsData: tableFields,
            baseUrl:baseUrl,
            selectedPage: false,
            records: [],
            configurations: [],
            search: '',
            addFrom: false,
            searchData: searchFields,
            pagination: [],
            clicked: 0,
            recordId:'',
            confirmText:'',
            loading: false,
            modelId: 'ConfirmModel',
            cardType:0,
            checkBoxValue:0
        }
    },
    computed: {
        isComplete () {
            return this.adminsData.pkg_name;
        }
    },
    methods: {
        currentPage: function(page) {
            this.pageRecords(page);
        },
        onSwitchStatus: function(event, id,cardType = 0) {
            let formData = this.$serializeData({
                'status': event.target.checked
            });
            if(1 == cardType && event.target.checked == 1) {
                this.checkBoxValue = event.target.checked;
                this.cardType = cardType;
                if(event.target.checked == 0) {
                    this.confirmText = this.$t("LBL_PAYMENTMETHOD_INACTIVATE_TEXT");
                }
                if(event.target.checked == 1) {
                    this.confirmText = this.$t("LBL_PAYMENTMETHOD_ACTIVATE_TEXT");
                }
                this.recordId = id;
                this.$bvModal.show(this.modelId);
            }else{
                this.$http.post(adminBaseUrl + '/payment-methods/status/' + id, formData).then((response) => {
                    toastr.success(response.data.message, '', toastr.options);
                });
            }
        },
        confirm:function() {
             let formData = this.$serializeData({
                'status': this.checkBoxValue,
                'cardType':this.cardType,
            });
            this.$http.post(adminBaseUrl + '/payment-methods/status/' + this.recordId, formData).then((response) => {
                this.cardType = 0;
                this.checkBoxValue = 0;
                this.$bvModal.hide(this.modelId);
                this.pageRecords();
                toastr.success(response.data.message, '', toastr.options);
            });
        },
       
        searchRecords: function() {
            this.pageRecords(this.pagination.current_page);
        },
        pageRecords: function(pageNo, pageLoad = false) {
                this.loading = pageLoad; 
           let formData = this.$serializeData(this.searchData);
            if (typeof this.pagination.per_page != 'undefined') {
                formData.append('per_page', this.pagination.per_page);
            }
            this.$http.post(adminBaseUrl + '/payment-methods/list?page=' + pageNo, formData).then((response) => {
                this.records = response.data.data.data;
                this.pagination = response.data.data;
                this.loading = false;
            });
        },
        addPaymenyGateways:function(configurations){
            Object.keys(configurations).forEach(key => {
                    this.configurations.push({
                    'name': configurations[key].pconf_key,
                    'value': configurations[key].pconf_value,
                    'key': configurations[key].pconf_key,
                });
            });
        },
        editRecord: function(record) {
            this.emptyFormValues();
            this.adminsData.pkg_id = record.pkg_id;
            this.adminsData.pkg_name = record.pkg_name;
            this.addPaymenyGateways(record.configurations);
            this.selectedPage = 'editform';
        },
        emptyFormValues: function() {
            this.adminsData = {
                'pkg_id': '',
                'pkg_name': '',
            };
            this.configurations=[];
            this.errors.clear();
        },
        validateRecord: function() {
            this.$validator.validateAll().then(res => {
                if (res) {
                    this.clicked = 1;
                    let input = this.adminsData;
                    this.updateRecord(this.adminsData);
                } else {
                    this.clicked = 0;
                }
            });
        },

        updateRecord: function(input) {
            let formData = this.$serializeData(input);
            formData.append('configurations', JSON.stringify(this.configurations));
            formData.append('_method', 'PUT');
            this.$http.post(adminBaseUrl + '/payment-methods/' + input.pkg_id, formData)
                .then((response) => { //success
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    toastr.success(response.data.message, '', toastr.options);
                    this.pageRecords(this.pagination.current_page);
                    this.cancel();
                    this.clicked = 0;
                    this.emptyFormValues();
                }, (response) => { //error
                    this.clicked = 0;
                    this.validateErrors(response);
                });
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
        cancel: function() {
            this.selectedPage = false;
        },
        switchEnvironment : function(event, id) {
            let formData = this.$serializeData({
                'pkg_environment': event.target.checked
            });
            this.$http.post(adminBaseUrl + '/payment-methods/status/' + id, formData).then((response) => {
                toastr.success(response.data.message, '', toastr.options);
            });
        }
    },
    mounted: function() {
        this.searchData = {'search': ''};
        this.pageRecords(1, true); 
    }
}
</script>
