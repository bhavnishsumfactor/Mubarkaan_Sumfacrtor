<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{ $t('LBL_URL_REWRITING') }}</h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_SEO')}}</span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_URL_REWRITING')}}</span>
                    </div>
                </div>
                <div class="subheader__toolbar">
                    <label class="switch switch--sm">
                        <input type="checkbox" name="redirection" v-model="redirection" @change="enable301($event)" :disabled="(!$canWrite('URL_REWRITE'))">
                        <span></span> {{$t('LBL_ENABLE_301_REDIRECTION')}}
                    </label>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet portlet--height-fluid">
                        <div class="portlet__body pb-0 bg-gray flex-grow-0">
                            <div class="row justify-content-between">
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
                                                    <a class="dropdown-item" href="javascript:;" @click="selectModule('pages')">{{$t("LBL_PAGES")}}</a>
                                                    <a class="dropdown-item" href="javascript:;" @click="selectModule('categories')">{{$t("LBL_CATEGORIES")}}</a>
                                                    <a class="dropdown-item" href="javascript:;" @click="selectModule('blogs')">{{$t("LBL_BLOGS")}}</a>
                                                    <a class="dropdown-item" href="javascript:;" @click="selectModule('brands')">{{$t("LBL_BRANDS")}}</a>
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
                                    <table class="table table-data">
                                        <thead>
                                            <tr>
                                                <th>{{ '#' }}</th>
                                                <th>{{ $t('LBL_URL_REWRITING_TITLE') }}</th>
                                                <th>{{ $t('LBL_URL_REWRITING_ORIGINAL') }}</th>
                                                <th>{{ $t('LBL_URL_REWRITING_CUSTOM') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                           
                                            <tr v-for="(record, index) in records" :key="record.record_id" :data-id="record.record_id">
                                                <td style="width:5%" scope="row">{{ pagination.from+index }}</td>
                                                <td style="width:30%">{{ record.record_title }}</td>
                                                <td style="width:20%">
                                                    <input type="text" class="form-control" disabled="" name="original_url" :value="record.urlrewrite_original">
                                                </td>
                                                <td style="width:45%">
                                                    <input type="text" :disabled="record.page_type=='terms' || record.page_type=='privacy' || (!$canWrite('URL_REWRITE'))" class="form-control" name="custom_url" v-model="record.urlrewrite_custom" @change="updateUrl(record)">
                                                    <span class="form-text text-muted" style="word-break: break-all;">{{baseUrl}}/{{record.urlrewrite_custom}}</span>
                                                    <span v-if="errors.first('meta_title')" class="error text-danger">{{ errors.first('meta_title') }}</span>
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
        </div>
    </div>
</template>
<script>
    const searchFields = {
        'record_title': ''
    };
    export default {
        data: function() {
            return {
                baseUrl: baseUrl,
                records: [],
                redirection: '',
                pagination: [],
                loading: false,
                searchData: searchFields,
                search: '',
                module_type: 'products'
            }
        },
        computed: {
            isActived: function() {
                return this.pagination.current_page;
            },
            pagesNumber: function() {
                return this.$pagesNumber(this.pagination);
            }
        },
        methods: {
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
                this.$http.post(adminBaseUrl + '/urlrewrites/modulelist?page=' + pageNo, formData).then((response) => {
                    this.records = response.data.data.records.data;
                    this.pagination = response.data.data.records;
                    this.redirection = parseInt(response.data.data.redirection);
                    this.loading = false;
                });
            },
            enable301: function(event) {
                let formData = this.$serializeData({
                    'status': event.target.checked
                });
                this.$http.post(adminBaseUrl + '/urlrewrites/enable301', formData).then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, "", toastr.options);
                        return;
                    }
                    toastr.success(response.data.message, '', toastr.options);
                });
            },
            selectModule: function(type) {
                this.module_type = type;
                this.pageRecords(1);
            },
            searchRecords: function() {
                this.pageRecords(this.pagination.current_page);
            },
            currentPage: function(page) {
                this.pageRecords(page);
            },
            updateUrl: function(data) {
                if (data.urlrewrite_custom == null) {
                    data.urlrewrite_custom = '';
                }
                let formData = this.$serializeData(data);
                this.$http.post(adminBaseUrl + '/urlrewrites/update', formData)
                    .then((response) => {
                        if (response.data.status == false) {
                            toastr.error(response.data.message, '', toastr.options);
                            return;
                        }
                        toastr.success(response.data.message, '', toastr.options);
                    }, (response) => {
                        toastr.error(response.data.errors.urlrewrite_custom[0], '', {
                            timeOut: 5000
                        });
                    });
            },
        },
        mounted: function() {
            this.pageRecords(1, true);
        }
    }
</script>