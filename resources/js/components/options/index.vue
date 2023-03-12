<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_OPTION_GROUPS') }}</h3>
          <div class="subheader__breadcrumbs">
            <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                <i class="flaticon2-shelter"></i>
            </router-link>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_PRODUCTS')}}</span>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_OPTION_GROUPS')}}</span>
          </div>
        </div>
        <div class="subheader__toolbar" v-if="$canWrite('OPTIONS')">
          <a @click="openAddPage" class="btn btn-white" href="javascript:void(0);">
            <i class="fas fa-plus"></i>
            {{ $t('BTN_ADD') }}
          </a>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class v-bind:class="[(showEmpty == 1) ? 'col-md-12': 'col-md-8']">
          <div class="portlet portlet--height-fluid">
            <div class="portlet__body pb-0 bg-gray flex-grow-0" v-if="showEmpty == 0">
              <div class="form-group">
                <div class="input-icon input-icon--left">
                  <input
                    type="text"
                    class="form-control"
                    :placeholder="$t('PLH_SEARCH')"
                    id="generalSearch"
                    :readonly="records.length == 0 && searchData.search === ''"
                    v-model="searchData.search"
                    @keyup="searchRecords"
                  />
                  <span class="input-icon__icon input-icon__icon--left">
                    <span>
                      <i class="la la-search"></i>
                    </span>
                  </span>
                  <span
                    class="input-icon__icon input-icon__icon--right"
                    v-if="searchData.search!=''"
                    @click="searchData.search='';selectedPage=false;pageRecords(1);"
                  >
                    <span>
                      <i class="fas fa-times"></i>
                    </span>
                  </span>
                </div>
              </div>
            </div>
            <hr class="m-0" />
            <div class="portlet__body portlet__body--fit" v-if="showEmpty == 0 && pageLoaded==1">
              <div class="section mb-0">
                <div class="section__content">
                  <table class="table table-data table-justified">
                    <thead>
                      <tr>
                        <th>{{ '#' }}</th>
                        <th>{{ $t('LBL_OPTION_GROUP_NAME') }}</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody
                      v-if="loading==false && records.length == 0 && searchData.search != '' && showEmpty == 0 && pageLoaded==1"
                    >
                      <tr>
                        <td colspan="3">
                          <noRecordsFound></noRecordsFound>
                        </td>
                      </tr>
                    </tbody>
                    <tbody v-else>
                      <tr v-for="(record, index) in records" :key="index">
                        <td scope="row">{{ pagination.from+index }}</td>
                        <td>
                          <a
                            href="javascript:;"
                            @click.prevent="openViewMode(record)"
                            v-if="!$canWrite('OPTIONS')"
                          >{{ record.option_name }}</a>
                          <div v-else>{{ record.option_name }}</div>
                        </td>
                        <td style="white-space:nowrap;"> 
                          <div class="actions">
                            <a
                              class="btn btn-light btn-sm btn-icon"
                              href="javascript:;"
                              v-bind:class="[!$canWrite('OPTIONS') ? 'disabled no-drop': '']"
                              v-b-tooltip.hover
                              :title="$t('TTL_EDIT')"
                              @click.prevent="editRecord(record)"
                            >
                              <svg >    
                                  <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" :href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'"></use>                               
                              </svg>
                            </a>
                            <a
                              class="btn btn-light btn-sm btn-icon"
                              href="javascript:;"
                              @click.capture="confirmDelete($event, record.option_id)"
                              v-b-tooltip.hover
                              :title="$t('TTL_DELETE')"
                              v-bind:class="[!$canWrite('OPTIONS') ? 'disabled no-drop': '']"
                            >
                              <svg >    
                                  <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'" :href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'"></use>                               
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
            <div class="portlet__body" v-if="showEmpty == 1 && pageLoaded==1">
              <div class="get-started">
                <div class="get-started-arrow d-flex justify-content-end mb-5">
                  <img
                    :src="baseUrl+'/admin/images/get-started-graphic/get-started-arrow-head.svg'"
                  />
                </div>
                <div class="no-content d-flex text-center flex-column mb-7">
                  <p>
                    {{ $t('LBL_CLICK_THE') }}
                    <a
                      href="javascript:;"
                      @click="openAddPage"
                      class="btn btn-brand btn-small"
                      v-bind:class="[(!$canWrite('OPTIONS')) ? 'disabledbutton no-drop': '']"
                    >
                      <i class="fas fa-plus"></i> {{ $t('BTN_ADD') }}
                    </a> {{ $t('LBL_BUTTON_TO_CREATE_OPTION_GROUP') }}
                  </p>
                </div>
                <div class="get-started-media">
                  <img :src="baseUrl+'/admin/images/get-started-graphic/get-started-options.svg'" />
                </div>
              </div>
            </div>
            <loader v-if="loading"></loader>
            <div class="portlet__foot" v-if="records.length > 0">
              <pagination :pagination="pagination" @currentPage="currentPage"></pagination>
            </div>
          </div>
        </div>

        <div class="col-md-4" v-if="showEmpty == 0">
          <div class="portlet portlet--height-fluid">
            <div class="portlet__head" v-if="selectedPage">
              <div class="portlet__head-label">
                <h3 class="portlet__head-title" v-if="selectedPage == 'editform'">
                  {{ $canWrite('OPTIONS') ? $t('LBL_EDITING') + ' -' : ''}}
                  <span class="editing-txt">{{editText}}</span>
                </h3>
                <h3
                  class="portlet__head-title"
                  v-if="selectedPage == 'addform'"
                >{{ $t('LBL_NEW_OPTION_GROUP_SETUP')}}</h3>
              </div>
              <div class="portlet__head-toolbar" v-if="selectedPage == 'editform'">
                <div class="portlet__head-actions" id="tooltip-target-1">
                  <i class="fas fa-info-circle"></i>
                </div>
                <b-popover
                  target="tooltip-target-1"
                  triggers="hover"
                  placement="top"
                  class="popover-cover"
                >
                  <div class="list-stats">
                    <div class="lable">
                      <span class="stats_title">{{ $t('LBL_CREATED') }}:</span>
                      <span
                        class="time"
                      >{{ createdByUser ? createdByUser+ ' |' : '' }} {{ createdAt | formatDateTime}}</span>
                    </div>
                    <div class="lable">
                      <span class="stats_title">{{ $t('LBL_LAST_UPDATED') }}:</span>
                      <span
                        class="time"
                      >{{ updatedByUser ? updatedByUser+ ' |' : ''}} {{ updatedAt | formatDateTime}}</span>
                    </div>
                  </div>
                </b-popover>
              </div>
            </div>

            <div
              class="portlet__body"
              v-if="selectedPage"
              v-bind:class="[(!$canWrite('OPTIONS')) ? 'disabledbutton': '']"
            >
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>
                      {{ $t('LBL_OPTION_GROUP_NAME') }}
                      <span class="required">*</span>
                    </label>
                    <input
                      v-if="adminsData.option_id != ''"
                      type="hidden"
                      name="id"
                      v-model="adminsData.option_id"
                    />
                    <input
                      type="text"
                      v-model="adminsData.option_name"
                      name="option_name"
                      v-validate="'required'"
                      :data-vv-as="$t('LBL_OPTION_GROUP_NAME')"
                      data-vv-validate-on="none"
                      class="form-control"
                    />
                    <span
                      v-if="errors.first('option_name')"
                      class="error text-danger"
                    >{{ errors.first('option_name') }}</span>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label>{{ $t('LBL_OPTION_GROUP_TYPE') }}</label>
                    <select
                      name="option_type"
                      v-model="adminsData.option_type"
                      class="form-control"
                    >
                      <option value="0">{{ $t('LBL_NONE') }}</option>
                      <option v-if="adminsData.option_id == 1" value="1">{{ $t('LBL_COLOR') }}</option>
                      <option value="2">{{ $t('LBL_SIZE') }}</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div 
                  v-if="adminsData.option_id == 1"
                  class="col-md-6"
                 
                >
                  <div class="form-group">
                    <label>{{ $t('LBL_OPTION_GROUP_SEPARATE_IMAGES') }}</label>
                    <select
                      name="option_has_image"
                      v-model="adminsData.option_has_image"
                      v-validate="'required'"
                      :data-vv-as="$t('LBL_SEPARATE_IMAGE_OPTION')"
                      data-vv-validate-on="none"
                      class="form-control"
                    >
                      <option value="1">{{ $t('LBL_YES') }}</option>
                      <option value="0">{{ $t('LBL_NO') }}</option>
                    </select>
                    <span
                      v-if="errors.first('option_has_image')"
                      class="error text-danger"
                    >{{ errors.first('option_has_image') }}</span>
                  </div>
                </div>

                <div
                  class="col-md-6"
                  v-bind:class="[adminsData.option_type != 2 ? 'disabledbutton' : '']"
                >
                  <div class="form-group">
                    <label>{{ $t('LBL_OPTION_GROUP_HAS_SIZE_CHART') }}</label>
                    <select
                      name="option_has_sizechart"
                      v-model="adminsData.option_has_sizechart"
                      class="form-control"
                    >
                      <option value="0">{{ $t('LBL_NO') }}</option>
                      <option value="1">{{ $t('LBL_YES') }}</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="portlet__body" v-if="selectedPage == ''">
              <div class="no-data-found">
                <div class="img">
                  <img :src="this.$noDataImage()" alt />
                </div>
                <div class="data">
                  <p>{{ $t ('LBL_USE_ICONS_FOR_ADVANCED_ACTIONS') }}</p>
                </div>
              </div>
            </div>

            <div class="portlet__foot" v-if="selectedPage != ''">
              <div class="row">
                <div class="col">
                  <button
                    type="reset"
                    class="btn btn-secondary"
                    @click="cancel()"
                  >{{ $t('BTN_DISCARD')}}</button>
                </div>
                <div class="col-auto">
                  <button
                    type="submit"
                    class="btn btn-brand gb-btn gb-btn-primary"
                    @click="validateRecord()"
                    :disabled="!isComplete || clicked==1 || (!$canWrite('OPTIONS'))"
                    v-if="selectedPage == 'addform'"
                    v-bind:class="clicked==1 && 'gb-is-loading'"
                  >{{ $t('BTN_CREATE') }}</button>
                  <button
                    type="submit"
                    class="btn btn-brand gb-btn gb-btn-primary"
                    @click="validateRecord()"
                    :disabled="!isComplete || clicked==1 || (!$canWrite('OPTIONS'))"
                    v-if="selectedPage == 'editform'"
                    v-bind:class="clicked==1 && 'gb-is-loading'"
                  >{{ $t('BTN_ADMIN_UPDATE') }}</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <DeleteModel
          :deletePopText="$t('LBL_DELETE_OPTION_GROUP_TEXT')"
          :recordId="recordId"
          @deleted="deleteRecord(recordId)"
        ></DeleteModel>
      </div>
    </div>
  </div>
</template>
<script>
const tableFields = {
  option_id: "",
  option_name: "",
  option_has_image: 0,
  option_type: 0,
  option_has_sizechart: 0
};
const searchFields = {
  option_name: ""
};
export default {
  data: function() {
    return {
      baseUrl: baseUrl,
      adminsData: tableFields,
      selectedPage: false,
      records: [],
      recordId: "",
      modelId: "deleteModel",
      search: "",
      editText: "",
      addFrom: false,
      searchData: searchFields,
      pagination: [],
      clicked: 0,
      loading: false,
      pageLoaded: 0,
      showEmpty: 1
    };
  },
  computed: {
    isComplete() {
      return this.adminsData.option_name != "";
    }
  },
   watch: {
    $route: "refreshedSearchData"
  },    
  methods: {
    refreshedSearchData() {
      if (this.$route.query.s) {
        this.searchData.search = this.$route.query.s;
      }
      this.pageRecords(1, true);
    },
    searchRecords: function() {
      if (this.selectedPage !== false) {
        this.selectedPage = false;
      }
      this.pageRecords(0);
    },
    currentPage: function(page) {
      this.pageRecords(page);
    },
    pageRecords: function(pageNo, pageLoad = false) {
      this.loading = pageLoad;
      let formData = this.$serializeData(this.searchData);
      if (typeof this.pagination.per_page != "undefined") {
        formData.append("per_page", this.pagination.per_page);
      }
      this.$http
        .post(adminBaseUrl + "/options/list?page=" + pageNo, formData)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            this.$router.push({ name: "unAuthorised" });
            return;
          }
          this.records = response.data.data.options.data;
          this.showEmpty = response.data.data.showEmpty;
          this.loading = false;
          this.pagination = response.data.data.options;
          this.pageLoaded = 1;
        });
    },
    openAddPage: function() {
      this.emptyFormValues();
      this.selectedPage = "addform";
      if (this.searchData.search != "") {
        this.searchData.search = "";
        this.pageRecords();
      }
      this.showEmpty = 0;
    },
    editRecord: function(record) {
      this.emptyUpdatedFieldValue();
      this.adminsData.option_id = record.option_id;
      this.adminsData.option_name = record.option_name;
      this.editText = record.option_name;
      this.adminsData.option_has_image = record.option_has_image;
      this.adminsData.option_type = record.option_type;
      this.adminsData.option_has_sizechart = record.option_has_sizechart;
      if (record.created_at != null && "admin_username" in record.created_at) {
        this.createdByUser = record.created_at.admin_username;
      }
      if (record.updated_at != null && "admin_username" in record.updated_at) {
        this.updatedByUser = record.updated_at.admin_username;
      }
      this.updatedAt = record.option_created_at ? record.option_created_at : "";
      this.createdAt = record.option_updated_at ? record.option_updated_at : "";
      this.selectedPage = "editform";
    },
    emptyFormValues: function() {
      this.adminsData = {
        option_id: "",
        option_name: "",
        option_has_image: 0,
        option_type: 0,
        option_has_sizechart: 0
      };
      this.editText = "";
      this.errors.clear();
    },
    validateRecord: function() {
      this.$validator.validateAll().then(res => {
        if (res) {
          let input = this.adminsData;
          this.clicked = 1;
          if (input.option_id != "") {
            this.updateRecord(this.adminsData);
          } else {
            this.saveRecord(input);
          }
        } else {
          this.clicked = 0;
        }
      });
    },

    updateRecord: function(input) {
      let formData = this.$serializeData(input);
      formData.append("_method", "put");
      this.$http
        .post(adminBaseUrl + "/options/" + input.option_id, formData)
        .then(
          response => {
            //success
            if (response.data.status == false) {
              this.clicked = 0;
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            toastr.success(response.data.message, "", toastr.options);
            this.pageRecords(this.pagination.current_page);
            this.cancel();
            this.clicked = 0;
          },
          response => {
            //error
            this.validateErrors(response);
            this.clicked = 0;
          }
        );
    },
    confirmDelete: function(event, dataid) {
      event.stopPropagation();
      this.recordId = dataid;
      this.$bvModal.show(this.modelId);
    },
    deleteRecord: function(recordId) {
      this.$http
        .delete(adminBaseUrl + "/options/" + recordId)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.pageRecords(this.pagination.current_page);
          this.$bvModal.hide(this.modelId);
          this.selectedPage = "";
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
    saveRecord: function(input) {
      this.$http.post(adminBaseUrl + "/options", input).then(
        response => {
          //success
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.pageRecords(this.pagination.current_page);
          this.selectedPage = false;
          this.clicked = 0;
        },
        response => {
          //error
          this.validateErrors(response);
          this.clicked = 0;
        }
      );
    },
    cancel: function() {
      this.selectedPage = false;
      if (this.records.length == 0) {
        this.showEmpty = 1;
      }
    },
    emptyUpdatedFieldValue: function() {
      this.createdByUser = "";
      this.updatedByUser = "";
      this.updatedAt = "";
      this.createdAt = "";
    },
    openViewMode: function(record) {
      this.emptyUpdatedFieldValue();
      this.adminsData.option_id = record.option_id;
      this.adminsData.option_name = record.option_name;
      this.editText = record.option_name;
      this.adminsData.option_has_image = record.option_has_image;
      this.adminsData.option_type = record.option_type;
      this.adminsData.option_has_sizechart = record.option_has_sizechart;
      if (record.created_at != null && "admin_username" in record.created_at) {
        this.createdByUser = record.created_at.admin_username;
      }
      if (record.updated_at != null && "admin_username" in record.updated_at) {
        this.updatedByUser = record.updated_at.admin_username;
      }
      this.updatedAt = record.option_created_at ? record.option_created_at : "";
      this.createdAt = record.option_updated_at ? record.option_updated_at : "";
      this.selectedPage = "editform";
    }
  },
  mounted: function() {
    this.searchData = { search: "" };
   this.refreshedSearchData();
  }
};
</script>