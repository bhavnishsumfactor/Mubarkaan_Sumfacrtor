<template>
    <div>

        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{ $t('LBL_EMAIL_TEMPLATES') }}</h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_CMS')}}</span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_EMAIL_TEMPLATES')}}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-xl-6">
                    <!--begin::Portlet-->
                    <div class="portlet portlet--height-fluid">
                        <div class="portlet__body pb-0 bg-gray flex-grow-0">
                            <div class="row">
                                <div class="col-lg-12">
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
                            </div>
                        </div>
                        <hr class="m-0">
                        <div class="portlet__body portlet__body--fit" v-if="records.length > 0">
                            <!--begin::Section-->
                            <div class="section">
                                <div class="section__content">
                                    <table class="table table-data table-justified">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ $t('LBL_EMAIL_TEMPLATE_NAME')}}</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(record, index) in records" :key="record.etpl_id" :data-id="record.etpl_id">
                                                <td scope="row">{{ pagination.from+index }}</td>
                                                <td>
                                                    <a href="javascript:;" @click.prevent="editRecord(record)" v-if="!$canWrite('EMAIL_TEMPLATES')">{{ record.etpl_name }}</a>
                                                    <div v-else>{{ record.etpl_name }}</div>
                                                </td>
                                                <td>
                                                    <div class="actions">
                                                    <a class="btn btn-sm btn-light btn-icon" href="javascript:;" v-b-tooltip.hover :title="$t('TTL_EDIT')" @click.prevent="editRecord(record)" v-bind:class="[!$canWrite('EMAIL_TEMPLATES') ? 'disabled no-drop': '']">
                                                        <svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                                                            </svg>
                                                    </a> 
                                                    </div>                                                   
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--end::Section-->
                        </div>
                        <loader v-if="loading"></loader>
                        <noRecordsFound v-if="loading==false && records.length == 0"></noRecordsFound>
                        <div class="portlet__foot" v-if="records.length > 0">
                            <pagination :pagination="pagination" @currentPage="currentPage"> </pagination>
                        </div>

                        <!--end::Form-->
                    </div>
                    <!--end::Portlet-->
                </div>
                <div class="col-md-6">
                    <div class="portlet portlet--height-fluid">
                        <div class="portlet__head" v-if="selectedPage">
                            <div class="portlet__head-label">
                                <h3 class="portlet__head-title" v-if="selectedPage == 'editform'">
                                   {{ $canWrite('EMAIL_TEMPLATES') ? $t('LBL_EDITING') + ' -' : '' }}
                                    <span class="editing-txt">{{editText}}</span></h3>
                            </div>
                            <div class="portlet__head-toolbar" v-if="selectedPage == 'editform'">
                                <div class="actions">
                                 <button type="reset" class="btn btn-light mr-2" @click.capture="openPreviewPopup()"><svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#view'" ></use>                               
                                                            </svg></button>
                                </div>

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
                        <div class="portlet__body" v-if="selectedPage" v-bind:class="[(!$canWrite('EMAIL_TEMPLATES')) ? 'disabledbutton': '']">
                            <input type="hidden" name="id" v-model="adminsData.etpl_id">
                            <div class="section">
                                <div class="section__body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{ $t('LBL_EMAIL_TEMPLATE_NAME') }} <span class="required">*</span></label>
                                                <input type="text" v-model="adminsData.etpl_name" name="etpl_name" v-validate="'required'" :data-vv-as="$t('LBL_EMAIL_TEMPLATE_NAME')" data-vv-validate-on="none" class="form-control" />
                                                <span v-if="errors.first('etpl_name')" class="error text-danger">{{ errors.first('etpl_name') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{ $t('LBL_EMAIL_SUBJECT') }} <span class="required">*</span></label>
                                                <input type="text" v-model="adminsData.etpl_subject" name="etpl_subject" v-validate="'required'" :data-vv-as="$t('LBL_EMAIL_SUBJECT')" data-vv-validate-on="none" class="form-control" />
                                                <span v-if="errors.first('etpl_subject')" class="error text-danger">{{ errors.first('etpl_subject') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                 <div class="d-flex justify-content-between">
                                                     <label>{{ $t('LBL_EMAIL_BODY')}}  <span class="required">*</span></label>
                                            <a href="javascript:;" @click="resetTemplate" class="links"> {{$t('LNK_RESET')}}</a></div>
                                                
                                                <textarea class="form-control" id="editor" v-model="adminsData.etpl_body" name="etpl_body" v-validate="'required'" :data-vv-as="$t('LBL_EMAIL_BODY')" data-vv-validate-on="none"></textarea>
                                                <span v-if="errors.first('etpl_body')" class="error text-danger">{{ errors.first('etpl_body') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>{{ $t('LBL_REPLACEMENT_VARIABLES') }}</label>
                                            <ul class="click-to-copy" v-if="adminsData.etpl_replacements">
                                                    <li v-for="(tags, index) in JSON.parse(adminsData.etpl_replacements)" :key="index" 
                                                        @mouseover="copied = false"
                                                        @click="copyToClipboard(index);"
                                                        @mousedown="$event.target.classList.add('bounceIn')"
                                                        @mouseup="$event.target.classList.remove('bounceIn')"
                                                        v-bind:title="[copied==true ? $t('LBL_COPIED_TO_CLIPBOARD'): $t('LBL_CLICK_TO_COPY')]"
                                                        v-b-tooltip.hover
                                                        :data-val="index">
                                                            <div class="text">{{tags}}</div>
                                                    </li>
                                             </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet__body" v-if="selectedPage == ''">
                            <div class="no-data-found">
                                <div class="img"><img :src="this.$noDataImage()" alt=""></div>
                                 <div class="data">
                                    <p>
                                        {{ $t('LBL_EMAIL_TEMPLATES_INFO') }}
                                    </p>                                    
                                </div>
                            </div>
                        </div>
                        <div class="portlet__foot" v-if="selectedPage != ''">
                            <div class="row">
                                <div class="col">
                                    <button type="reset" class="btn btn-secondary" @click="cancel()">{{ $t('BTN_DISCARD')}}</button>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-brand gb-btn gb-btn-primary" @click="updateRecord()" :disabled="!isComplete || clicked==1 || (!$canWrite('EMAIL_TEMPLATES'))" v-if="selectedPage == 'editform'" v-bind:class="clicked==1 && 'gb-is-loading'">{{ $t('BTN_EMAIL_TEMPLATES_UPDATE') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <preview-model :templateName="adminsData.etpl_subject" :footer="footer" :body="adminsData.etpl_body" :header="header"></preview-model>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import preview from './previewPopup';
    const tableFields = {
        'etpl_id': '',
        'etpl_name': '',
        'etpl_subject': '',
        'etpl_body': '',
        'etpl_replacements': '',
        'etpl_default_body': ''
    };
    const searchFields = {
        'search': ''
    };
    export default {
        components: {
            'preview-model': preview
        },
        data: function() {
            return {
                copied: false,
                activeThemeUrl: activeThemeUrl,
                adminBaseUrl: adminBaseUrl,
                baseUrl : baseUrl,
                ModalID: '#formModel',
                selectedPage: false,
                records: [],
                pagination: [],
                clicked: 0,
                loading: false,
                adminsData: tableFields,
                searchData: searchFields,
                search: '',
                footer: '',
                header: '',
                editText: '',
                myPlugins: [
                    'advlist autolink lists link image charmap',
                    'searchreplace visualblocks code fullscreen',
                    'print preview anchor insertdatetime media',
                    'paste code help wordcount table'
                ],
                toolBar: 'undo redo | formatselect | bold italic | \
                alignleft aligncenter alignright | \
                image | preview | fullscreen',
                updatedByUser:'',
                updatedAt:'',
            }
        },
        computed: {
            isComplete() {
                return this.adminsData.etpl_subject && this.adminsData.etpl_body;
            }
        },
        methods: {
            searchRecords: function() {
                this.pageRecords(this.pagination.current_page);
            },
            currentPage: function(page) {
                this.searchRecords();
            },
            pageRecords: function(pageNo, pageLoad = false) {
                this.loading = pageLoad;
                let formData = this.$serializeData(this.searchData);
                if (typeof this.pagination.per_page != 'undefined') {
                    formData.append('per_page', this.pagination.per_page);
                }
                this.$http.post(adminBaseUrl + '/email-templates/list?page=' + pageNo, formData).then((response) => {
                    this.records = response.data.data.data;
                    this.loading = false;
                    this.pagination = response.data.data;
                });
            },
            editRecord: function(record) {
                let thisObj = this;
                this.$http.get(adminBaseUrl + '/email-templates/' + record.etpl_id + '/record')
                    .then((response) => {
                        this.emptyFormValues();
                        this.emptyUpdatedFieldValue();
                        this.adminsData.etpl_id = response.data.data.etpl_id;
                        this.adminsData.etpl_subject = response.data.data.etpl_subject;
                        this.adminsData.etpl_name = response.data.data.etpl_name;
                        this.adminsData.etpl_body = response.data.data.etpl_body;
                        this.adminsData.etpl_default_body = response.data.data.etpl_default_body;
                        this.adminsData.etpl_replacements = response.data.data.etpl_replacements;
                        this.editText = response.data.data.etpl_subject;
                        this.footer = response.data.data.footer;
                        this.header = response.data.data.header;
                        this.selectedPage = 'editform';
                        if (response.data.data.updated_at != null && "admin_username" in response.data.data.updated_at) {
                            this.updatedByUser = response.data.data.updated_at.admin_username;
                        }
                        this.updatedAt = response.data.data.etpl_updated_at ? response.data.data.etpl_updated_at : '';
                        setTimeout(() => {
                            thisObj.initTinyMce(response.data.data.etpl_body);
                        }, 20);
                    });
            },
            updateRecord: function() {
                this.$validator.validateAll().then(res => {
                    if (res) {
                        let input = this.adminsData;
                        if (input.etpl_id != '') {
                            this.clicked = 1;
                            let formData = this.$serializeData(input);
                            formData.append('_method', 'put');
                            this.$http.post(adminBaseUrl + '/email-templates/' + input.etpl_id, formData)
                                .then((response) => {
                                    this.clicked = 0;
                                    if (response.data.status == false) {
                                        toastr.error(response.data.message, '', toastr.options);
                                        return;
                                    }
                                    toastr.success(response.data.message, '', toastr.options);
                                    this.pageRecords(this.pagination.current_page);
                                    this.cancel();
                                }, (response) => {
                                    this.validateErrors(response);
                                });
                        }
                    }
                });
            },
            cancel: function() {
                this.selectedPage = false;
            },
            openPreviewPopup: function() {
                event.stopPropagation();
                this.$bvModal.show('previewModel');
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
            resetTemplate: function() {
                this.adminsData.etpl_body = this.adminsData.etpl_default_body;
                this.initTinyMce(this.adminsData.etpl_body);
            },
            emptyFormValues: function() {
                this.adminsData = {
                    'etpl_id': '',
                    'etpl_name': '',
                    'etpl_subject': '',
                    'etpl_body': '',
                    'etpl_replacements': '',
                    'etpl_default_body': ''
                };
                this.editText = '';
                this.errors.clear();
            },
            initTinyMce: function(descriptionInitValue) {
                let thisObj = this;
                tinymce.remove();
                tinymce.init({
                    selector:'#editor',
                    height: 500,
                    menubar: true,
                    branding: false,
                    plugins: this.myPlugins,
                    toolbar: this.toolBar,
                    images_upload_url: adminBaseUrl + '/editor/images',
                    images_upload_credentials: true,
                    setup: function(editor) {
                        editor.on('init', function(e) {               
                            editor.setContent(descriptionInitValue);
                        });
                        editor.on('change', function(e) {  
                            thisObj.adminsData.etpl_body = editor.getContent();
                        });
                    }
                });
            },
            emptyUpdatedFieldValue : function() {
                this.updatedByUser = '';
                this.updatedAt = '';
            },
            copyToClipboard : function(copyText) {
                let $temp = $("<input>");
                $("body").append($temp);
                $temp.val(copyText).select();
                document.execCommand("copy");
                $temp.remove();
                this.copied = true;
            }
        },
        mounted: function() {
            let currentUrl = window.location.pathname;
            currentUrl = currentUrl.split('/');
            this.pageRecords(1, true);
            $('#preview').on('click', function() {
                $('#previewContent').html(app.adminsData.etpl_body);
            });
            $(document).on('click', '.modal-backdrop', function() {
                data.show = false;
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            });
        }
    }
</script>