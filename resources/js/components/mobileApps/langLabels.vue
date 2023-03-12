<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">Mobile Apps</h3>                    
                </div>
            </div>
        </div>

        <div class="container">
            <div class="portlet">
                <div class="portlet__body portlet__body--fit">
                    <div class="app-page">
                        <sidebar :tab="type"></sidebar>
                        <div class="app-main">
                            <div class="app-lang">
                                    <div class="portlet portlet--height-fluid">
                                        <div class="portlet__body pb-0 bg-gray flex-grow-0">
                                            <div class="form-group">
                                                <div class="input-group input-icon input-icon--left">
                                                    <input type="text" class="form-control" :placeholder="$t('PLH_SEARCH')" id="generalSearch" v-model="search" @keyup="searchRecords" :maxlength="maxCharacterLimit" />
                                                    <span class="input-icon__icon input-icon__icon--left">
                                                        <span><i class="la la-search"></i></span>
                                                    </span>                                            
                                                </div>
                                            </div>
                                        </div>  
                                        <div class="portlet__body p-0">
                                            <div class="section__content scroll-x">
                                                <table class="table table-data">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ '#' }}</th>
                                                            <th>{{ $t('LBL_LANGUAGE_LABEL_IDENTIFIER') }}</th>
                                                            <th>{{ $t('LBL_DEFAULT_LANG_LABEL') }}</th>
                                                            <th>{{ $t('LBL_PAGES') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(record, index) in records" v-bind:key="index">
                                                            <td width="5%" scope="row">{{ pagination.from+index }}</td>
                                                            <td width="30%">
                                                               {{record.key}}
                                                            </td>
                                                            <td width="30%">
                                                                <span class="field-value" v-show="!showField(record.key)" @click="focusField(record.key)">{{record[default_lang_code]}}</span>
                                                                <input v-model="record[default_lang_code]" :name="index" v-validate="'required'" v-show="showField(record.key)" type="text" class="field-value form-control" @focus="focusField(record.key)" @blur="blurField" @keydown.enter="updateRecord(record)">
                                                            </td>                                               
                                                            <td width="30%">
                                                                Home
                                                                <!-- <div class="badge-group">
                                                                    <span class="badge badge--brand badge--inline badge--pill" v-for="(page, pageKey) in record.pages" :key="pageKey">{{page}}</span>
                                                                </div> -->
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <loader v-if="loading"></loader>
                                            <noRecordsFound v-if="loading==false && records.length == 0"></noRecordsFound>
                                        </div>
                                        <div class="portlet__foot" v-if="records.length > 0">
                                                <!-- Pagination -->
                                                <pagination :pagination="pagination" @currentPage="currentPage"> </pagination>
                                            </div>
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

import sidebar from "./sidebar";
    export default {
        components: {
            sidebar: sidebar,
        },
        data: function() {
            return {
                baseUrl: baseUrl,
                records: [],
                pages: [],
                pagination: [],
                clicked: 0,
                loading: false,
                sidebar: sidebar,
                adminsData: tableFields,
                searchData: searchFields,
                search: '',
                selectedPage: '',
                maxCharacterLimit:"30",
                default_lang_code: '',
                default_lang_name: '',
                type: "langlabels",
                selected_page: '',
                dropzoneOptions: {
                    url: baseUrl + '/dropzone',
                    thumbnailWidth: 150,
                    maxFilesize: 2,
                    addRemoveLinks: true,
                    maxFiles: 1,
                },
                editField: ''
            }
        },
        watch: {
            $route: "refreshedData"
        },
        methods: {
            focusField(name){
                this.editField = name;
            },
            blurField(){
                this.editField = '';
            },
            showField(name){
                return this.editField == name;
            },
            refreshedData() {
                this.selected_page = '';
                this.pageRecords(1);
            },
            exportLanguage: function() {
                window.open(adminBaseUrl + '/languages/mobile/export');
            },
            importLanguage: function(event) {            
                $(".custom-file-btn").addClass('spinner spinner--sm spinner--right spinner--light');
                var importFile = event.target.files || e.dataTransfer.files;           
                if (importFile.length == 1) {
                    let formData = new FormData();
                    formData.append('file', importFile[0]);
                    this.$http.post(adminBaseUrl + '/languages/mobile/import', formData,
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
                //formData.append('type', this.$route.params.type);
                this.$http.post(adminBaseUrl + '/languageslabels/mobile/list?page=' + pageNo, formData).then((response) => {      
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
                this.$http.post(adminBaseUrl + '/languageslabels/mobile/get', formData).then((response) => {         
                    this.adminsData = response.data.data;
                });
            },
            updateRecord: function(input) {
                let formData = this.$serializeData(input);
                this.$http.post(adminBaseUrl + '/languageslabels/mobile/save', formData)
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
                        this.editField = '';
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