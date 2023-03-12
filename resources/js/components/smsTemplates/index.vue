<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{ $t('LBL_SMS_TEMPLATES') }}</h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_CMS')}}</span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_SMS_TEMPLATES')}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
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
                            <div class="section mb-0">
                                <div class="section__content">
                                    <table class="table table-data table-justified">
                                        <thead>
                                            <tr>
                                                <th>{{ '#' }}</th>
                                                <th>{{ $t('LBL_SMS_TEMPLATE_NAME') }}</th>
                                                <th>{{ $t('LBL_PUBLISH') }}</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(record, index) in records" :key="record.stpl_id" :data-id="record.stpl_id">
                                                <td scope="row">{{ pagination.from+index }}</td>
                                                <td><a href="javascript:;" @click.prevent="editRecord(record)" v-if="!$canWrite('SMS_TEMPLATES')">{{ record.stpl_name }}</a>
                                                    <div v-else>{{ record.stpl_name }}</div>
                                                </td>
                                                <td>
                                                    <label class="switch switch--sm">
                                                        <input type="checkbox" name="stpl_publish" v-model="record.stpl_publish" @change="onSwitchStatus($event, record.stpl_id)" :disabled ="!$canWrite('SMS_TEMPLATES')">
                                                        <span v-b-tooltip.hover :title="record.stpl_publish == 1 ? $t('TTL_UNPUBLISH') : $t('TTL_PUBLISH')"></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <div class="actions">
                                                        <a href="javascript:;" v-b-tooltip.hover :title="$t('TTL_EDIT')" class="btn btn-light btn-sm btn-icon" @click="editRecord(record)" v-bind:class="[!$canWrite('SMS_TEMPLATES') ? 'disabled no-drop': '']"><svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                                                            </svg></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!-- Pagination -->
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

                <div class="col-md-6">
                    <div class="portlet portlet--height-fluid">
                        <div class="portlet__head" v-if="recordData.stpl_id">
                            <div class="portlet__head-label">
                                <h3 class="portlet__head-title">
                                    {{ $canWrite('SMS_TEMPLATES') ? $t('LBL_EDITING') + ' -' : ''}}
                                     <span class="editing-txt">{{editText}}</span></h3>
                            </div>
                            <div class="portlet__head-toolbar">
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

                        <div class="portlet__body" v-if="recordData.stpl_id != ''" v-bind:class="[(!$canWrite('SMS_TEMPLATES')) ? 'disabledbutton': '']">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ $t('LBL_SMS_TEMPLATE_NAME')}} <span class="required">*</span></label>
                                        <input type="text" v-model="recordData.stpl_name" name="stpl_name" v-validate="'required'" data-vv-as="$t('LBL_SMS_TEMPLATE_NAME') " data-vv-validate-on="none" class="form-control" />
                                        <span v-if="errors.first('stpl_name')" class="error text-danger">@{{ errors.first('stpl_name') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between">
                                    <a href="javascript:;" @click="resetBody" class="links"> {{$t('LNK_RESET')}}</a></div>
                                    <div class="form-group">
                                        <label>{{ $t('LBL_SMS_BODY')}}  <span class="required">*</span></label>
                                        <textarea class="form-control" rows="5" cols="15" v-model="recordData.stpl_body" name="stpl_body" @keyup="showLength" v-validate="'required'" data-vv-as="$t('LBL_SMS_BODY') " data-vv-validate-on="none">
                                    </textarea>
                                        <span class="small">{{currentLength}}/160</span>
                                        <span v-if="errors.first('stpl_body')" class="error text-danger">@{{ errors.first('stpl_body') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ $t('LBL_REPLACEMENT_VARIABLES')}}</label>                  
                                        <ul class="click-to-copy" v-if="recordData.stpl_replacements">
                                            <li v-for="(tags, index) in JSON.parse(recordData.stpl_replacements)" :key="index"
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
                            
                        <div class="portlet__body" v-if="recordData.stpl_id == ''">
                            <div class="no-data-found">
                                <div class="img"><img :src="this.$noDataImage()" alt=""></div>
                                <div class="data">
                                    <p>
                                        {{ $t('LBL_EMAIL_TEMPLATES_INFO') }} 
                                     </p>
                                </div>
                            </div>
                        </div>
                        <div class="portlet__foot" v-if="recordData.stpl_id != ''">
                            <div class="row">
                                <div class="col">
                                    <button type="reset" class="btn btn-secondary" @click='emptyFormValues'>{{ $t('BTN_DISCARD')}}</button>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-brand gb-btn gb-btn-primary" @click="validateRecord()" :disabled="!isComplete || clicked==1 || (!$canWrite('SMS_TEMPLATES'))" v-bind:class="clicked==1 && 'gb-is-loading'">{{ $t('BTN_SMS_TEMPLATES_UPDATE') }}</button>
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
        'stpl_id': '',
        'stpl_name': '',
        'stpl_body': '',
        'stpl_replacements': '',
        'stpl_default_body': ''
    };
    const searchFields = {
        'search': ''
    };
    export default {
        data: function() {
            return {
                ModalID: 'sms_settings',
                records: [],
                pagination: [],
                copied: false,
                clicked: 0,
                loading: false,
                recordData: tableFields,
                searchData: searchFields,
                baseUrl: baseUrl,
                search: '',
                currentLength: 0,
                editText: '',
                updatedByUser:'',
                updatedAt:'',
            }
        },
        computed: {
            isComplete () {
                return this.recordData.stpl_name && this.recordData.stpl_body;
            }
        },
        methods: {
            emptyFormValues: function() {
                this.recordData = {
                    'stpl_id': '',
                    'stpl_name': '',
                    'stpl_body': '',
                    'stpl_replacements': '',
                    'stpl_default_body': ''
                };
                this.editText = '';
                this.errors.clear();
            },
            pageRecords: function(pageNo, pageLoad = false) {
                this.loading = pageLoad;
                let formData = this.$serializeData(this.searchData);
                if (typeof this.pagination.per_page != 'undefined') {
                    formData.append('per_page', this.pagination.per_page);
                }
                this.$http.post(adminBaseUrl + '/sms-templates/list?page=' + pageNo, formData).then((response) => {
                    this.records = response.data.data.data;
                    this.loading = false;
                    this.pagination = response.data.data;
                });
            },
            searchRecords: function() {
                this.pageRecords(this.pagination.current_page);
            },
            currentPage: function(page) {
                this.searchRecords();
            },
            showLength: function() {
                this.currentLength = this.recordData.stpl_body.replace(/\{([^}]+)\}/g, "").length;
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
                        let input = this.recordData;
                        this.clicked = 1;
                        if (input.stpl_id != '') {
                            this.updateRecord(input.stpl_id, input);
                        }
                    } else {
                        this.clicked = 0;
                    }
                });
            },
            editRecord: function(input) {
                this.emptyUpdatedFieldValue();
                this.recordData.stpl_id = input.stpl_id;
                this.recordData.stpl_name = input.stpl_name;
                this.recordData.stpl_body = input.stpl_body;
                this.recordData.stpl_default_body = input.stpl_default_body;
                this.recordData.stpl_replacements = input.stpl_replacements;
                this.editText = input.stpl_name;
                if (input.updated_at != null && "admin_username" in input.updated_at) {
                    this.updatedByUser = input.updated_at.admin_username;
                }
                this.updatedAt = input.stpl_updated_at ? input.stpl_updated_at : '';
                this.currentLength = this.recordData.stpl_body.replace(/\{([^}]+)\}/g, "").length;
            },
            updateRecord: function(id, input) {
                let formData = this.$serializeData({
                    'stpl_id': input.stpl_id,
                    'stpl_name': input.stpl_name,
                    'stpl_body': input.stpl_body
                });
                formData.append('_method', 'put');
                this.$http.post(adminBaseUrl + '/sms-templates/' + id, formData)
                    .then((response) => {
                        this.clicked = 0;
                        if (response.data.status == false) {
                            toastr.error(response.data.message, '', toastr.options);
                            return;
                        }
                        toastr.success(response.data.message, '', toastr.options);
                        this.emptyFormValues();
                        this.pageRecords(this.pagination.current_page);
                    }, (response) => {
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
            changePage: function(pageNo) {
                this.pagination.current_page = pageNo;
                this.pageRecords(pageNo, null);
            },
            changeRecords: function(event) {
                this.pageRecords(this.pagination.current_page, event.target.value);
            },
            resetBody: function() {
                this.recordData.stpl_body = this.recordData.stpl_default_body;
            },
            onSwitchStatus: function(event, id) {
                let formData = this.$serializeData({
                    'status': event.target.checked
                });
                this.$http.post(adminBaseUrl + '/sms-templates/status/' + id, formData).then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    toastr.success(response.data.message, '', toastr.options);
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
            this.searchData = {'search': ''};
            this.pageRecords(1, true);
        }
    }
</script>