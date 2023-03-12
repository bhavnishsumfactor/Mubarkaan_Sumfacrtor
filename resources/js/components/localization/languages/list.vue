<template>
<div>
   
    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_LANGUAGES') }}</h3>
            </div>
            <div class="subheader__toolbar">
                <a  class="btn btn-white" href="javascript:void(0);" @click="openLanguagePopup();">
                    <i class="fas fa-plus"></i>{{ $t('BTN_ADD') }}
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col">
                <div class="alert alert-light alert-elevate fade show" role="alert">
                    <div class="alert-icon"><i class="flaticon-signs-2 font-brand"></i></div>
                     <div class="alert-text">{{ $t('LBL_LANGUAGE_INFO1') }}. <br>{{ $t('LBL_LANGUAGE_INFO2') }}.
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="portlet portlet--height-fluid">
                    <div class="portlet__body pb-0 bg-gray flex-grow-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="input-icon input-icon--left">
                                        <input type="text" class="form-control" :placeholder="$t('PLH_SEARCH')" id="generalSearch" v-model="search" @keyup="searchRecords">
                                        <span class="input-icon__icon input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                        <span class="input-icon__icon input-icon__icon--right" v-if="search!=''" @click="search='';pageRecords(1);">
                                            <span><i class="fas fa-times"></i></span>
                                        </span>
                                    </div>  
                                </div>  
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
                                            <th>{{ $t('LBL_LANGUAGE_NAME') }}</th>
                                            <th>{{ $t('LBL_LANGUAGE_CODE') }}</th>
                                            <th>{{ $t('LBL_LANGUAGE_DIRECTION') }}</th>
                                            <th>{{ $t('LBL_PUBLISH') }}</th>
                                            <th>{{ $t('LBL_WEBSITE_DEFAULT') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(record, index) in records" :key="record.lang_id" :data-id="record.lang_id">
                                            <td scope="row">{{ pagination.from+index }}</td>
                                            <td>{{ record.lang_name }}</td>
                                            <td>{{ record.lang_code | upperCase }}</td>
                                            <td>
                                                <span v-if="record.lang_direction == 'ltr'"> {{ $t('LBL_LEFT_TO_RIGHT') }} </span>
                                                <span v-if="record.lang_direction == 'rtl'"> {{ $t('LBL_RIGHT_TO_LEFT') }} </span>
                                            </td>
                                            <td>
                                                <label class="switch switch--sm remember-me">
                                                    <input type="checkbox" name="lang_publish" :disabled="record.lang_default == 1" :readonly="record.lang_default == 1" v-model="record.lang_publish" @change="onSwitchStatus($event, record.lang_id)">
                                                    <span v-b-tooltip.hover :title="record.lang_publish == 1 ? $t('TTL_UNPUBLISH') : $t('TTL_PUBLISH') "></span>
                                                </label>
                                            </td>
                                            <td>
                                                <a class="btn btn-outline-brand btn-mark" @click="markDefault(record.lang_id)" href="javascript:;" v-if="record.lang_view_default != 1">
                                                    <span >{{$t('BTN_MARK_AS_DEFAULT')}}</span>
                                                </a>
                                                <a class="btn btn-brand btn-mark" href="javascript:;" v-if="record.lang_view_default == 1">
                                                    <span >{{$t('BTN_DEFAULT')}}</span>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="actions">
                                                    <a class="btn btn-light btn-sm btn-icon " @click.capture="confirmDelete($event, record)" v-if="record.lang_default != 1" href="javascript:;" v-b-tooltip.hover :title="$t('TTL_DELETE')">
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
                    <div class="portlet__foot" v-if="records.length > 0">
                        <pagination :pagination="pagination" @currentPage="currentPage"> </pagination>
                    </div>
                </div>
            </div>
        </div>
        <addLanguagePopup :languages="languages" :languagesArray="languagesArray"></addLanguagePopup>
        <DeleteModel :deletePopText="$t('LBL_LANGUAGE_DELETE_CONFIRMATION')" :recordId="recordId" @deleted="deleteRecord(recordId)"></DeleteModel>
    </div>
</div>
</template>
<script>
import addLanguagePopup from './add';
const searchFields = {
    'record_title': ''
};
export default {
    components: {'addLanguagePopup': addLanguagePopup},
    data: function() {
        return {
            baseUrl: baseUrl,
            records: [],
            pagination: [],
            loading: false,
            adminsData: {'lang_name':''},
            searchData: searchFields,
            search: '',
            recordId: '',
            languages: [],
            languagesArray: [],
            languagePopup:'languagePopup',
        }
    },
    
    methods: {
        pageRecords: function(pageNo, pageLoad = false) {
                this.loading = pageLoad; 
            let formData = this.$serializeData({
                'search': this.search
            });
            if (typeof this.pagination.per_page != 'undefined') {
                formData.append('per_page', this.pagination.per_page);
            }
            this.$http.post(adminBaseUrl + '/languages/list?page=' + pageNo, formData).then((response) => {
                this.adminsData = {'lang_name':''};
                this.records = response.data.data.records.data;
                this.pagination = response.data.data.records;
                this.languagesArray = [];
                this.languages = [];
                this.languages = response.data.data.languages;
                for (let [key, value] of Object.entries(response.data.data.languages)) {
                    this.languagesArray.push(value.name);
                }
                this.loading = false;
            });
        },
        searchRecords: function() {
            this.pageRecords(this.pagination.current_page);
        },
        currentPage: function(page) {
            this.pageRecords(page);
        },
        onSwitchStatus: function(event, id) {
            let formData = this.$serializeData({
                'status': event.target.checked
            });
            this.$http.post(adminBaseUrl + '/languages/status/' + id, formData).then((response) => {
                toastr.success(response.data.message, '', toastr.options);
            });
        },
        markDefault: function(id) {
            this.$http.post(adminBaseUrl + '/languages/markasdefault/' + id).then((response) => {
                toastr.success(response.data.message, '', toastr.options);
                this.pageRecords(this.pagination.current_page);
            });
        },
        confirmDelete: function(event, data) {
            if (data.lang_default == 1) {
                return false;
            }
            event.stopPropagation();
            this.recordId = data.lang_id;
            this.$bvModal.show('deleteModel');
        },
        deleteRecord: function(recordId) {
            this.$http.delete(adminBaseUrl + '/languages/' + recordId).then((response) => {
                if (response.data.status == false) {
                    toastr.error(response.data.message, '', toastr.options);
                    return;
                }
                toastr.success(response.data.message, '', toastr.options);
                this.$bvModal.hide('deleteModel');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                
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
        openLanguagePopup: function() {
            this.$bvModal.show(this.languagePopup);
        }
    },
    mounted: function() {
        this.pageRecords(1, true);
    }
}
</script>
