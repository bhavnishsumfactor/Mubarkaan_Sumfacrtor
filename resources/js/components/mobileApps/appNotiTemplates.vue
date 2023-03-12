<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_MOBILE_APPS') }}</h3>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="portlet">
        <div class="portlet__body portlet__body--fit">
          <div class="app-page">
            <sidebar :tab="type"></sidebar>

            <div class="app-main">
              <div class="row justify-content-center no-gutters">
                <div class="col-md-7">
                  <div class="app__collections info__collections">
                    <div class="app__collections-head d-flex justify-content-between">
                    <h3>Notification Templates</h3>                   
                  </div>
                    <div class="app__collections-wrapper p-0 ">
                      <div class="portlet portlet--height-full portlet--no-shadow mb-0">
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
                        <div class="portlet__body portlet__body--fit " v-if="records.length > 0">
                            <div class="section mb-0">
                                <div class="section__content">
                                    <table class="table table-data table-justified">
                                        <thead>
                                            <tr>
                                                <th>{{ '#' }}</th>
                                                <th>{{ $t('LBL_NOTIFICATION_TEMPLATE_NAME') }}</th>
                                                <th ></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(record, index) in records" :key="record.antpl_id" :data-id="record.antpl_id">
                                                <td scope="row">{{ pagination.from+index }}</td>
                                                <td>
                                                    <a href="javascript:;" @click.prevent="editRecord(record)" v-if="!$canWrite('NOTIFICATION_TEMPLATES')">{{ record.antpl_name }}</a>
                                                    <div v-else>{{ record.antpl_name }}</div>
                                                </td>
                                                <td>
                                                    <div class="actions">
                                                    <a href="javascript:;" v-b-tooltip.hover :title="$t('TTL_EDIT')" class="btn btn-light btn-sm btn-icon" @click="editRecord(record)" v-bind:class="[!$canWrite('NOTIFICATION_TEMPLATES') ? 'disabled no-drop': '']"><svg>   
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
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="portlet portlet--height-full portlet--no-shadow mb-0">
                        <div class="portlet__head" v-if="recordData.antpl_id">
                            <div class="portlet__head-label">
                                <h3 class="portlet__head-title">{{ $canWrite('NOTIFICATION_TEMPLATES') ? $t('LBL_EDITING') + ' -' : ''}} <span class="editing-txt">{{editText}}</span></h3>
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

                        <div class="portlet__body" v-if="recordData.antpl_id != ''" v-bind:class="[(!$canWrite('NOTIFICATION_TEMPLATES')) ? 'disabledbutton': '']">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                    <label>{{ $t('LBL_NOTIFICATION_TEMPLATE_NAME')}} <span class="required">*</span></label>
                                        <input type="text" v-model="recordData.antpl_name" name="antpl_name" v-validate="'required'" data-vv-as="$t('LBL_NOTIFICATION_TEMPLATE_NAME') " data-vv-validate-on="none" class="form-control" />
                                        <span v-if="errors.first('antpl_name')" class="error text-danger">@{{ errors.first('antpl_name') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between"><a href="javascript:;" @click="resetBody" class="links"> {{$t('Reset')}}</a></div>
                                    <div class="form-group">
                                        <label>{{ $t('LBL_NOTIFICATION_BODY')}}  <span class="required">*</span></label>
                                        <textarea class="form-control" rows="5" cols="15" v-model="recordData.antpl_body" name="antpl_body" @keyup="showLength" v-validate="'required'" data-vv-as="$t('LBL_NOTIFICATION_BODY') " data-vv-validate-on="none">
                                    </textarea>
                                        <span class="small">{{currentLength}}/160</span>
                                        <span v-if="errors.first('antpl_body')" class="error text-danger">@{{ errors.first('antpl_body') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>{{ $t('LBL_REPLACEMENT_VARIABLES')}}</label>
                                    <ul class="click-to-copy" v-if="recordData.antpl_replacements">
                                        <li v-for="(tags, index) in JSON.parse(recordData.antpl_replacements)" :key="index"
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
                            
                        <div class="portlet__body" v-if="recordData.antpl_id == ''">
                            <div class="no-data-found">
                                <div class="img"><img :src="this.$noDataImage()" alt=""></div>
                                <div class="data">
                                    <ul>
                                        <li>{{ $t('LBL_NOTIFICATION_TEMPLATES_INFO1') }}</li>
                                        <li>{{ $t('LBL_NOTIFICATION_TEMPLATES_INFO2') }}</li>
                                        <li>{{ $t('LBL_NOTIFICATION_TEMPLATES_INFO3') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="portlet__foot" v-if="recordData.antpl_id != ''">
                            <div class="row">
                                <div class="col">
                                    <button type="reset" class="btn btn-secondary" @click='emptyFormValues'>{{ $t('BTN_DISCARD')}}</button>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-brand gb-btn gb-btn-primary" @click="validateRecord()" :disabled="!isComplete || clicked==1 || (!$canWrite('NOTIFICATION_TEMPLATES'))" v-bind:class="clicked==1 && 'gb-is-loading'">{{ $t('BTN_NOTIFICATION_TEMPLATES_UPDATE') }}</button>
                                </div>
                            </div>
                        </div>
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
import VueTagsInput from "@johmun/vue-tags-input";
import sidebar from "./sidebar";
const tableFields = {
  'antpl_id': '',
  'antpl_name': '',
  'antpl_body': '',
  'antpl_replacements': '',
  'antpl_default_body': ''
};
const searchFields = {
  'search': ''
};
export default { 
  components: {
    sidebar: sidebar,
  },
  data: function() {
    return {
        baseUrl: baseUrl,
        records: [],
        type: "app-noti-templates",
        pagination: [],
        copied: false,
        clicked: 0,
        loading: false,
        recordData: tableFields,
        searchData: searchFields,
        search: '',
        currentLength: 0,
        editText: '',
        updatedByUser:'',
        updatedAt:'',
    }
  },
  computed: {
    isComplete () {
        return this.recordData.antpl_name && this.recordData.antpl_body;
    }
  },
  methods: {
    emptyFormValues: function() {
        this.recordData = {
            'antpl_id': '',
            'antpl_name': '',
            'antpl_body': '',
            'antpl_replacements': '',
            'antpl_default_body': ''
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
        this.$http.post(adminBaseUrl + '/app-notification-templates/list?page=' + pageNo, formData).then((response) => {
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
        this.currentLength = this.recordData.antpl_body.replace(/\{([^}]+)\}/g, "").length;
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
                if (input.antpl_id != '') {
                    this.updateRecord(input.antpl_id, input);
                }
            } else {
                this.clicked = 0;
            }
        });
    },
    editRecord: function(input) {
        this.emptyUpdatedFieldValue();
        this.recordData.antpl_id = input.antpl_id;
        this.recordData.antpl_name = input.antpl_name;
        this.recordData.antpl_body = input.antpl_body;
        this.recordData.antpl_default_body = input.antpl_default_body;
        this.recordData.antpl_replacements = input.antpl_replacements;
        this.editText = input.antpl_name;
        this.currentLength = this.recordData.antpl_body.replace(/\{([^}]+)\}/g, "").length;
        if (input.updated_at != null && "admin_username" in input.updated_at) {
            this.updatedByUser = input.updated_at.admin_username;
        }
        this.updatedAt = input.antpl_updated_at ? input.antpl_updated_at : '';
    },
    updateRecord: function(id, input) {
        let formData = this.$serializeData({
            'antpl_id': input.antpl_id,
            'antpl_name': input.antpl_name,
            'antpl_body': input.antpl_body
        });
        formData.append('_method', 'put');
        this.$http.post(adminBaseUrl + '/app-notification-templates/' + id, formData)
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
        this.recordData.antpl_body = this.recordData.antpl_default_body;
    },
    onSwitchStatus: function(event, id) {
        let formData = this.$serializeData({
            'status': event.target.checked
        });
        this.$http.post(adminBaseUrl + '/app-notification-templates/status/' + id, formData).then((response) => {
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
};
</script>
