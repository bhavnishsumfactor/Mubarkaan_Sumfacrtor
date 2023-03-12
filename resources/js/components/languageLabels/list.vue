<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{ $t('LBL_LANGUAGE_LABELS') }}</h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_CMS')}}</span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_LANGUAGE_LABELS')}}</span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{ $route.params.type == "admin" ? $t('LNK_ADMIN') : $t('LNK_FRONTEND') }}</span>
                    </div>
                </div>
                <div class="subheader__toolbar">
                    <div class="subheader__wrapper">           
                        <a href="javascript:void(0);" class="btn btn-subheader" @click="exportLanguage" v-bind:class="[!$canWrite('LANGUAGE_LABELS') ? 'disabledbutton no-drop': '']"><i class="fas fa-cloud-download-alt"></i>{{ $t('BTN_EXPORT') }}</a>
                        <a href="javascript:void(0);" class="btn btn-white subheader__btn-options custom-file-btn" v-bind:class="[!$canWrite('LANGUAGE_LABELS') ? 'disabledbutton no-drop': '']">
                            <i class="fas fa-cloud-upload-alt"></i> 
                            <input type="file" name="languageUpload" @change="importLanguage" ref="languageInputFile"/> {{ $t('BTN_IMPORT') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="portlet portlet--height-fluid">
                        <div class="portlet__body pb-0 bg-gray flex-grow-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="input-group input-icon input-icon--left">
                                            <input type="text" class="form-control" :placeholder="$t('PLH_SEARCH')" id="generalSearch" v-model="search" @keyup="searchRecords" :maxlength="maxCharacterLimit" />
                                            <span class="input-icon__icon input-icon__icon--left">
                                                <span><i class="la la-search"></i></span>
                                            </span>
                                            <div class="input-group-append">
                                                <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle" aria-expanded="true">
                                                    {{selected_page | camelCase}}
                                                </button>
                                                <div class="dropdown-menu" x-placement="top-start">
                                                    <perfect-scrollbar style="max-height:300px">
                                                        <a class="dropdown-item" href="javascript:;" v-for="page in pages" v-bind:key="page" @click="selectPage(page)">{{page}}</a>
                                                    </perfect-scrollbar>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <hr class="m-0">
                        <div class="portlet__body portlet__body--fit" v-if="records.length > 0">
                            <div class="section mb-0">
                                <div class="section__content scroll-x">
                                    <table class="table table-data table-justified">
                                        <thead>
                                            <tr>
                                                <th>{{ '#' }}</th>
                                                <th>{{ $t('LBL_LANGUAGE_LABEL_IDENTIFIER') }}</th>
                                                <th>{{ $t(this.default_lang_name) }}</th>
                                                <th>{{ $t('LBL_PAGES') }}</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(record, index) in records" v-bind:key="index">
                                                <td width="5%" scope="row">{{ pagination.from+index }}</td>
                                                <td width="20%">
                                                    <a href="javascript:;" @click.prevent="editRecord(record)" v-if="!$canWrite('LANGUAGE_LABELS')">{{ record.key }}</a>
                                                    <div v-else>{{ record.key }}</div>
                                                </td>
                                                <td width="25%">{{ record[default_lang_code] }}</td>
                                                <td width="45%">
                                                    <div class="badge-group">
                                                        <span class="badge badge--brand badge--inline badge--pill" v-for="(page, pageKey) in record.pages" :key="pageKey">{{page}}</span>
                                                    </div>
                                                </td>
                                                <td width="5%">
                                                    <div class="actions">
                                                    <a href="javascript:;" v-b-tooltip.hover :title="$t('TTL_EDIT')" class="btn btn-light btn-sm btn-icon " @click="editRecord(record)" v-bind:class="[!$canWrite('LANGUAGE_LABELS') ? 'disabled no-drop': '']"><svg>   
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
                            <!-- Pagination -->
                            <pagination :pagination="pagination" @currentPage="currentPage"> </pagination>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="portlet portlet--height-fluid">
                        <div class="portlet__head" v-if="selectedPage == 'editform'">
                            <div class="portlet__head-label">
                                <h3 class="portlet__head-title" >
                                    {{ $canWrite('LANGUAGE_LABELS') ? $t('LBL_EDITING') + ' -' : ''}} {{ adminsData.key }}</h3>
                            </div>
                        </div>
                        <div class="portlet__body" v-if="selectedPage" v-bind:class="[!$canWrite('LANGUAGE_LABELS') ? 'disabled no-drop': '']">
                            <input v-if="adminsData.key != ''" type="hidden" name="key" v-model="adminsData.key">
                            <div class="row" v-for="(language, index) in adminsData.lang" v-bind:key="index">
                                <div class="col-md-12">
                                    <label>{{ $t(adminsData.lang[index].lang_name) }}</label>
                                    <div class="form-group">
                                        <input type="text" v-model="adminsData.lang[index].value" :name="index" v-validate="'required'" :data-vv-as="$t(language.lang_name) " data-vv-validate-on="none" class="form-control" />
                                        <span v-if="errors.first(index)" class="error text-danger">{{ errors.first(index) }}</span>
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
                                    <button type="reset" class="btn btn-secondary" @click="cancel()">{{ $t('BTN_DISCARD') }} </button>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-brand gb-btn gb-btn-primary" @click="validateRecord()" :disabled="clicked==1 || (!$canWrite('LANGUAGE_LABELS'))" v-if="selectedPage == 'editform'" v-bind:class="clicked==1 && 'gb-is-loading'">{{ $t('BTN_LANGUAGE_LABEL_UPDATE') }}</button>
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
        'key': ''
    };
    const searchFields = {
        'key': ''
    };
    export default {
        data: function() {
            return {
                baseUrl: baseUrl,
                records: [],
                pages: [],
                pagination: [],
                clicked: 0,
                loading: false,
                adminsData: tableFields,
                searchData: searchFields,
                search: '',
                selectedPage: '',
                maxCharacterLimit:"30",
                default_lang_code: '',
                default_lang_name: '',
                selected_page: '',
                dropzoneOptions: {
                    url: baseUrl + '/dropzone',
                    thumbnailWidth: 150,
                    maxFilesize: 2,
                    addRemoveLinks: true,
                    maxFiles: 1,
                }
            }
        },
        watch: {
            $route: "refreshedData"
        },
        methods: {
            refreshedData() {
                this.selected_page = '';
                this.pageRecords(1);
            },
            exportLanguage: function() {
                window.open(adminBaseUrl + '/languages/export');
            },
            importLanguage: function(event) {            
                $(".custom-file-btn").addClass('spinner spinner--sm spinner--right spinner--light');
                var importFile = event.target.files || e.dataTransfer.files;           
                if (importFile.length == 1) {
                    let formData = new FormData();
                    formData.append('file', importFile[0]);
                    this.$http.post(adminBaseUrl + '/languages/import', formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then((response) => { //success
                        this.$refs.languageInputFile.value = '';
                            if (response.data.status == false) {
                                toastr.error(response.data.message, '', toastr.options);
                                return;
                            }
                            toastr.success(response.data.message, '', toastr.options);
                            $(".custom-file-btn").removeClass('spinner spinner--sm spinner--right spinner--light');
                        }, (response) => { //error
                        this.$refs.languageInputFile.value = '';
                            $(".custom-file-btn").removeClass('spinner spinner--sm spinner--right spinner--light');
                            if (Array.isArray(response.data.errors.file)) {
                                toastr.error(response.data.errors.file[0], '', toastr.options);
                            } else {
                                toastr.error(response.data.message, '', toastr.options);
                            }
                        });
                } else {
                    this.$refs.languageInputFile.value = '';
                    $(".custom-file-btn").removeClass('spinner spinner--sm spinner--right spinner--light');
                    this.errors.add({
                        field: 'file',
                        msg: this.$t('LBL_PLEASE_UPLOAD_FILE_FIRST')
                    });
                }
            },
            selectPage: function(type) {
                this.selected_page = type;
                this.pageRecords(1);
                this.cancel();
                this.emptyFormValues();
            },
            emptyFormValues: function() {
                this.adminsData = {
                    'key': ''
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
                if (this.selected_page != '') {
                    formData.append('filter', this.selected_page);
                }
                formData.append('type', this.$route.params.type);
                this.$http.post(adminBaseUrl + '/languageslabels/list?page=' + pageNo, formData).then((response) => {                    
                    this.records = response.data.data.data.data;
                    this.records = $.map(this.records, function(value, index) {
                        return [value];
                    });
                    this.pagination = response.data.data.data;
                    this.default_lang_code = response.data.data.default_lang_code;
                    this.default_lang_name = response.data.data.default_lang_name;
                    this.pages = response.data.data.pages;
                    this.selected_page = response.data.data.selected_page;
                    this.loading = false;
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
                    'label_title': ''
                };
                this.pageRecords(this.pagination.current_page);
            },
            validateRecord: function() {
                this.$validator.validateAll().then(res => {
                    if (res) {
                        this.clicked = 1;
                        this.updateRecord(this.adminsData);
                    } else {
                        this.clicked = 0;
                    }
                });
            },
            editRecord: function(input) {
                this.adminsData = input;
                this.selectedPage = 'editform';
                let formData = this.$serializeData({'key': input.key});
                this.$http.post(adminBaseUrl + '/languageslabels/get', formData).then((response) => {         
                    this.adminsData = response.data.data;
                });
            },
            updateRecord: function(input) {
                input.lang = JSON.stringify(input.lang);
                let formData = this.$serializeData(input);
                this.$http.post(adminBaseUrl + '/languageslabels/save', formData)
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
            cancel: function() {
                this.selectedPage = false;
            },
            validateErrors: function(response) {
                let jsondata = response.data;
                Object.keys(jsondata.errors).forEach(key => {
                    this.errors.add({
                        field: key,
                        msg: jsondata.errors[key][0]
                    });
                });
            }
        },
        mounted: function() {
            this.pageRecords(1, true);
        }
    }
</script>