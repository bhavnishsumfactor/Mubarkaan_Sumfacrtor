<template>
<div>
    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_ALT_IMAGES') }}</h3>
                <div class="subheader__breadcrumbs">
                    <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                        <i class="flaticon2-shelter"></i>
                    </router-link>
                    <span class="subheader__breadcrumbs-separator"></span>
                    <span class="subheader__breadcrumbs-link">{{$t('LBL_SEO')}}</span>
                    <span class="subheader__breadcrumbs-separator"></span>
                    <span class="subheader__breadcrumbs-link">{{$t('LBL_ALT_IMAGES')}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <div class="portlet portlet--height-fluid">
                    <div class="portlet__body pb-0 bg-gray flex-grow-0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="input-group input-icon input-icon--left">
                                        <input type="text" class="form-control" :placeholder="$t('PLH_SEARCH')" id="generalSearch" v-model="search" @keyup="searchRecords" />
                                        <span class="input-icon__icon input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                        <div class="input-group-append">
                                            <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle" aria-expanded="true">
                                                {{module_type | camelCase}}
                                            </button>
                                            <div class="dropdown-menu" x-placement="top-start">
                                                <a class="dropdown-item" href="javascript:;" @click="selectModule('products')">{{$t("LBL_PRODUCTS")}}</a>
                                                <a class="dropdown-item" href="javascript:;" @click="selectModule('brands')">{{$t("LBL_BRANDS")}}</a>
                                                <a class="dropdown-item" href="javascript:;" @click="selectModule('categories')">{{$t("LBL_CATEGORIES")}}</a>
                                                <a class="dropdown-item" href="javascript:;" @click="selectModule('blogs')">{{$t("LBL_BLOGS")}}</a>
                                            </div>
                                        </div>
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
                                            <th>{{ $t('LBL_ALT_IMAGES_RECORD_NAME') }}</th>
                                            <th ></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(record, index) in records" :key="'listing'+index" :data-id="record.record_id">
                                            <td scope="row">{{ pagination.from+index }}</td>
                                            <td>
                                                <a href="javascript:;" @click.prevent="editRecord(record)" v-if="!$canWrite('ALT_IMAGES')">{{ record.record_title }}</a>
                                                <div v-else>{{ record.record_title }}</div></td>
                                            <td>
                                                <div class="actions">
                                                <a href="javascript:;" v-b-tooltip.hover :title="$t('TTL_EDIT')" class="btn btn-light btn-sm btn-icon" @click="editRecord(record)" v-bind:class="[!$canWrite('ALT_IMAGES') ? 'disabled no-drop': '']"><svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                                                            </svg></a>
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
        <div class="col-md-5">
            <div class="portlet portlet--height-fluid">
                <div class="portlet__head" v-if="selectedPage">
                    <div class="portlet__head-label">
                        <h3 class="portlet__head-title" v-if="selectedPage == 'editform'">
                            {{ $canWrite('ALT_IMAGES') ? $t('LBL_EDITING') + ' -' : ''}} {{ adminsData.record_title }}
                        </h3>
                    </div>
                    <div class="portlet__head-toolbar" v-if="selectedPage == 'editform'">
                        <div class="portlet__head-actions" id="tooltip-target-1"><i class="fas fa-info-circle"></i></div>
                        <b-popover target="tooltip-target-1" triggers="hover" placement="top" class="popover-cover">
                            <div class="list-stats">
                                <div class="lable">
                                    <span class="stats_title">{{ $t('LBL_LAST_UPDATED') }}:</span>
                                    <span class="time">{{ updatedByUser ? updatedByUser+ ' |' : ''}} {{ updatedAt | formatDateTime}}</span>
                                </div>
                            </div>
                        </b-popover>
                    </div>
                </div>
                <div class="portlet__body" v-if="selectedPage" v-bind:class="[(!$canWrite('ALT_IMAGES')) ? 'disabledbutton': '']">
                    <perfect-scrollbar :style="'max-height: 480px; overflow-x: hidden;'">
                        <ul class="list-group">
                            <li v-for="(image, index) in adminsData.images" :key="'records'+index">
                                <div class="row mb-3 ml-0 mr-0">
                                    <div class="col-md-2"><div class="rounded border imgthumb"><img  :src="'yokart/image/'+image.afile_id+'/thumb?t='+$timestamp(image.afile_updated_at)+''"></div>
                                    <small v-if="module_type == 'products' && image.variant_name != ''">({{image.variant_name}})</small> </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>{{ $t('LBL_ALT_IMAGE_TITLE')}}</label>
                                            <input type="text" v-model="image.afile_attribute_title" name="afile_attribute_title" class="form-control" :disabled="!$canWrite('ALT_IMAGES')"/>
                                            <span v-if="errors.first('afile_attribute_title')" class="error text-danger">{{ errors.first('afile_attribute_title') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>{{ $t('LBL_ALT_IMAGE_ALT')}} </label>
                                            <input type="text" v-model="image.afile_attribute_alt" name="afile_attribute_alt" class="form-control" :disabled="!$canWrite('ALT_IMAGES')"/>
                                            <span v-if="errors.first('afile_attribute_alt')" class="error text-danger">{{ errors.first('afile_attribute_alt') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </perfect-scrollbar>
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
                            <button type="submit" class="btn btn-brand gb-btn gb-btn-primary" @click="updateRecord()" :disabled="clicked==1 || (!$canWrite('ALT_IMAGES'))" v-if="selectedPage == 'editform' && adminsData.images.length>0" v-bind:class="clicked==1 && 'gb-is-loading'">{{ $t('BTN_ALT_IMAGES_UPDATE') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        </div> 
    </div>
</div>
</template>
<script>

const tableFields = {
    'afile_type': '',
    'record_id': '',
    'record_title': '',
    'images': []
};
const searchFields = {
    'record_title': ''
};
export default {
    data: function() {
        return {
            baseUrl: baseUrl,
            records: [],
            pagination: [],
            clicked: 0,
            loading: false,
            adminsData: tableFields,
            searchData: searchFields,
            search: '',
            selectedPage: '',
            recordId: '',
            module_type: 'products',
            afile_type: 'products',
            updatedByUser:'',
            updatedAt:''
        }
    },
    methods: {
        emptyFormValues: function() {
            this.adminsData = {
                'afile_type': '',
                'record_id': '',
                'record_title': '',
                'images': []
            };
            this.errors.clear();
        },
        pageRecords: function(pageNo, pageLoad = false) {
            this.loading = pageLoad; 
            Object.assign(this.searchData, {
                'search': this.search
            });
            let formData = this.$serializeData(this.searchData);
            formData.append('module_type', this.module_type);
            if (typeof this.pagination.per_page != 'undefined') {
                formData.append('per_page', this.pagination.per_page);
            }
            this.$http.post(adminBaseUrl + '/imagetags/modulelist?page=' + pageNo, formData).then((response) => {
                this.records = response.data.data.records.data;
                this.afile_type = response.data.data.afile_type;
                this.pagination = response.data.data.records;
                this.loading = false;
            });
        },
        selectModule: function(type) {
            this.module_type = type;
            this.pageRecords(1);
            this.cancel();
            this.emptyFormValues();
        },
        searchRecords: function() {
            this.pageRecords(this.pagination.current_page);
        },
        currentPage: function(page) {
            this.pageRecords(page);
        },
        editRecord: function(record) {
            this.emptyUpdatedFieldValue();
            let formData = this.$serializeData({
                'afile_type': this.afile_type,
                'record_id': record.record_id
            });
            this.$http.post(adminBaseUrl + '/imagetags/get', formData)
                .then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    this.emptyFormValues();
                    this.adminsData.afile_type = this.afile_type;
                    this.adminsData.record_id = record.record_id;
                    this.adminsData.record_title = record.record_title;
                    if (response.data.data.length > 0) {
                        this.adminsData.images = response.data.data;
                        if (response.data.data[0].updated_at != null && "admin_username" in response.data.data[0].updated_at) {
                            this.updatedByUser = response.data.data[0].updated_at.admin_username;
                        }
                        this.updatedAt = response.data.data[0].afile_meta_updated_at ? response.data.data[0].afile_meta_updated_at : '';
                    }
                    /**/
                }, (response) => {
                });
            this.selectedPage = 'editform';
        },
        updateRecord: function() {
            this.$validator.validateAll().then(res => {
                if (res) {
                    this.clicked = 1;
                    let images = JSON.stringify(this.adminsData.images);
                    let formData = this.$serializeData({
                        'images': images
                    });
                    this.$http.post(adminBaseUrl + '/imagetags/update', formData)
                        .then((response) => {
                            this.clicked = 0;
                            if (response.data.status == false) {
                                toastr.error(response.data.message, '', toastr.options);
                                return;
                            }
                            toastr.success(response.data.message, '', toastr.options);
                            this.emptyFormValues();
                            this.pageRecords(this.pagination.current_page);
                            this.selectedPage = '';
                        }, (response) => {
                            this.clicked = 0;
                            this.validateErrors(response);
                        });
                } else {
                    this.clicked = 0;
                }
            });
        },
        cancel: function() {
            this.selectedPage = false;
        },
        emptyUpdatedFieldValue : function() {
            this.updatedByUser = '';
            this.updatedAt = '';
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
    mounted: function() {
        this.pageRecords(1, true);
    }
}
</script>