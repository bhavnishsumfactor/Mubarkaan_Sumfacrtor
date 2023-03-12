<template>
<div>

    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_CURRENCIES') }}</h3>
            </div>
            <div class="subheader__toolbar">
                <router-link :to="{name: 'createCurrency'}" class="btn btn-white" v-if="currencyApiKey != ''"><i class="fas fa-plus"></i>{{ $t('BTN_ADD')}}</router-link>
                <router-link :to="{name: 'thirdpartyCurrency'}" class="btn btn-white" v-else><i class="fas fa-plus"></i>{{ $t('BTN_ADD')}}</router-link>
            </div>
        </div>
    </div>

  <div class="container">
        <div class="row">
            <div class="col">
                <div class="alert alert-light alert-elevate fade show" role="alert">
                   <div class="alert-icon"><i class="flaticon-signs-2 font-brand"></i></div>
                    <div class="alert-text">{{ $t('LBL_CURRENCY_INFO1') }}. <br>{{ $t('LBL_CURRENCY_INFO2') }}.
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="portlet">
                    <div class="portlet__body portlet__body--fit" v-if="records.length > 0">
                        <div class="section">
                            <div class="section__content">
                                <table class="table table-data table-justified">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ $t('LBL_CURRENCY_NAME') }}</th>
                                            <th>{{ $t('LBL_CURRENCY_CODE') }}</th>
                                            <th>{{ $t('LBL_CURRENCY_SYMBOL') }}</th>
                                            <th>{{ $t('LBL_CURRENCY_POSITION') }}</th>
                                            <th>{{ $t('LBL_CURRENCY_VALUE') }}</th>
                                            <th>{{ $t('LBL_PUBLISH') }}</th>
                                            <th>{{ $t('LBL_WEBSITE_DEFAULT') }}</th>
                                            <th v-if="$canWrite('CURRENCY_MANAGEMENT')"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(record, index) in records" :key="record.currency_id" :data-id="record.currency_id">
                                            <td scope="row">{{ index+1 }}</td>
                                            <td>{{ record.currency_name }}</td>
                                            <td>{{ record.currency_code }}</td>
                                            <td>{{ record.currency_symbol }}</td>
                                            <td><span v-if="record.currency_position == 1">{{ $t('LBL_RIGHT') }}</span> <span v-else>{{ $t('LBL_LEFT') }}</span></td>
                                            <td>
                                                <a href="javascript:;" v-b-tooltip.hover :title="record.currency_value" variant="success">
                                                    {{ record.currency_value_round }}
                                                </a>
                                            </td>
                                            <td>
                                                <label class="switch switch--sm">
                                                    <input type="checkbox" name="currency_publish" :disabled="record.currency_default == 1 || record.currency_view_default == 1" :readonly="record.currency_default == 1 || record.currency_view_default == 1" v-model="record.currency_publish" @change="onSwitchStatus($event, record.currency_id)">
                                                    <span v-b-tooltip.hover :title="record.currency_publish == 1 ? $t('TTL_UNPUBLISH') : $t('TTL_PUBLISH') "></span>
                                                </label>
                                            </td>
                                            <td>
                                                <a class="btn btn-outline-brand btn-mark" @click="markDefault(record.currency_id)" v-bind:class="[record.currency_publish == 0 ? 'disabled' : '']" href="javascript:;" v-if="record.currency_view_default != 1">
                                                    <span>{{ $t('BTN_MARK_AS_DEFAULT') }}</span>
                                                </a>
                                                <a class="btn btn-brand btn-mark" href="javascript:;" v-if="record.currency_view_default == 1">
                                                    <span >{{$t('BTN_DEFAULT')}}</span>
                                                </a>
                                            </td>
                                            <td v-if="$canWrite('CURRENCY_MANAGEMENT')">
                                                <div class="actions">
                                                <a class="btn btn-light btn-sm btn-icon deleteRecord" @click.capture="confirmDelete($event, record)" v-if="record.currency_default != 1"
                                                  :attr-id="(record.currency_default != 1) ? record.currency_id:''" href="javascript:;" v-b-tooltip.hover :title="$t('TTL_DELETE')">
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
                    <loader v-if="loading"></loader>        
                    <noRecordsFound v-if="loading==false && records.length == 0"></noRecordsFound> 
                </div>
            </div>
        </div>
        <DeleteModel :deletePopText="$t('LBL_CURRENCY_DELETE_CONFIRMATION')" :recordId="recordId" @deleted="deleteRecord(recordId)"></DeleteModel>
    </div>
</div>
</template>
<script>


export default {
    data: function() {
        return {
            baseUrl: baseUrl,
            records: [],
            recordId: '',
            loading: false,
            currencyApiKey:''
        }
    },
    methods: {
        pageRecords: function() {
            this.records = [];
            this.loading = true;
            this.$http.post(adminBaseUrl + '/currencies/list').then((response) => {
                this.records = response.data.data.data;
                this.currencyApiKey = response.data.data.currencyApiKey;
                this.loading = false;
            });
        },
        confirmDelete: function(event, data) {
            if (data.currency_default == 1) {
                return false;
            }
            event.stopPropagation();
            this.recordId = data.currency_id;
            this.$bvModal.show('deleteModel');
        },
        deleteRecord: function(recordId) {
            this.$http.delete(adminBaseUrl + '/currencies/' + recordId).then((response) => {
                if (response.data.status == false) {
                    toastr.error(response.data.message, '', toastr.options);
                    return;
                }
                toastr.success(response.data.message, '', toastr.options);
                this.pageRecords();
                this.$bvModal.hide('deleteModel');
            });
        },
        onSwitchStatus: function(event, id) {
            let formData = this.$serializeData({
                'status': event.target.checked
            });
            this.$http.post(adminBaseUrl + '/currencies/status/' + id, formData).then((response) => {
                toastr.success(response.data.message, '', toastr.options);
            });
        },
        markDefault: function(id) {
            this.$http.post(adminBaseUrl + '/currencies/markasdefault/' + id).then((response) => {
                toastr.success(response.data.message, '', toastr.options);
                this.pageRecords();
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
    },
    mounted() {
        this.pageRecords();
    }
}
</script>
