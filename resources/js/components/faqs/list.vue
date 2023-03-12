<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_FAQS') }}</h3>
          <div class="subheader__breadcrumbs">
            <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                <i class="flaticon2-shelter"></i>
            </router-link>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_CMS')}}</span>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_FAQS')}}</span>
          </div>
        </div>
        <div class="subheader__toolbar">
          <a
            @click="openAddPage"
            class="btn btn-white"
            href="javascript:void(0);"
            v-bind:class="[(!$canWrite('FAQ')) ? 'disabledbutton no-drop': '']"
          >
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
              <div class="row mb-4">
                <div class="col-lg-12">
                  <div class="input-icon input-icon--left">
                    <input
                      type="text"
                      class="form-control"
                      :placeholder="$t('PLH_SEARCH')"
                      id="generalSearch"
                      :readonly="records.length == 0 && searchData.search === ''"
                      v-model="searchData.search"
                      @keyup="searchRecords"
                      :maxlength="maxCharacterLimit"
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
            </div>
            <hr class="m-0" />
            <div class="portlet__body portlet__body--fit" v-if="showEmpty == 0 && pageLoaded==1">
              <div class="section">
                <div class="section__content">
                  <table class="table table-data table-justified">
                    <thead>
                      <tr>
                        <th>{{ '#' }}</th>
                        <th>{{ $t('LBL_FAQS_TITLE') }}</th>
                        <th>{{ $t('LBL_FAQS_CATEGORY') }}</th>
                        <th>{{ $t('LBL_PUBLISH') }}</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody
                      v-if="loading==false && records.length == 0 && searchData.search != '' && showEmpty == 0 && pageLoaded==1"
                    >
                      <tr>
                        <td colspan="4">
                          <noRecordsFound></noRecordsFound>
                        </td>
                      </tr>
                    </tbody>
                    <tbody v-else>
                      <tr
                        v-for="(record, index) in records"
                        :key="record.faq_id"
                        :data-id="record.faq_id"
                      >
                        <td scope="row">{{ pagination.from+index }}</td>
                        <td width="50%">
                          <a
                            href="javascript:;"
                            @click.prevent="editRecord(record)"
                            v-if="!$canWrite('FAQ')"
                          >{{ record.faq_title }}</a>
                          <div v-else>{{ record.faq_title }}</div>
                        </td>
                        <td width="15%">{{ record.category.faqcat_name }}</td>
                        <td>
                          <label class="switch switch--sm">
                            <input
                              type="checkbox"
                              name="faq_publish"
                              :disabled="!$canWrite('FAQ')"
                              v-model="record.faq_publish"
                              @change="onSwitchStatus($event, record.faq_id)"
                            />
                            <span
                              v-b-tooltip.hover
                              :title="record.faq_publish == 1 ? $t('TTL_UNPUBLISH') : $t('TTL_PUBLISH')"
                            ></span>
                          </label>
                        </td>
                        <td>
                          <div class="actions">
                            <a
                              href="javascript:;"
                              class="btn btn-light btn-sm btn-icon"
                              v-b-tooltip.hover
                              :title="$t('TTL_EDIT')"
                              @click="editRecord(record)"
                              v-bind:class="[!$canWrite('FAQ') ? 'disabled no-drop': '']"
                            >
                              <svg>   
                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                            </svg>
                            </a>
                            <a
                              class="btn btn-light btn-sm btn-icon"
                              href="javascript:;"
                              v-b-tooltip.hover
                              :title="$t('TTL_DELETE')"
                              @click.capture="confirmDelete($event, record.faq_id)"
                              v-bind:class="[!$canWrite('FAQ') ? 'disabled no-drop': '']"
                            >
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
                    >
                      <i class="fas fa-plus"></i>
                      {{ $t('BTN_ADD') }}
                    </a>
                    {{ $t('LBL_BUTTON_TO_CREATE_FAQ') }}
                  </p>
                </div>
                <div class="get-started-media">
                  <img :src="baseUrl+'/admin/images/get-started-graphic/get-started-faq.svg'" />
                </div>
              </div>
            </div>
            <loader v-if="loading"></loader>
            <div class="portlet__foot" v-if="records.length > 0">
              <!-- Pagination -->
              <pagination :pagination="pagination" @currentPage="currentPage"></pagination>
            </div>
          </div>
        </div>

        <div class="col-md-4" v-if="showEmpty == 0">
          <div class="portlet portlet--height-fluid">
            <div class="portlet__head" v-if="selectedPage">
              <div class="portlet__head-label">
                <h3 class="portlet__head-title" v-if="selectedPage == 'editform'">
                  {{ $canWrite('FAQ') ? $t('LBL_EDITING') + ' -' : ''}}
                  <span class="editing-txt">{{editText}}</span>
                </h3>
                <h3
                  class="portlet__head-title"
                  v-if="selectedPage == 'addform'"
                >{{ $t('LBL_NEW_FAQ_SETUP')}}</h3>
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
              v-bind:class="[(!$canWrite('FAQ')) ? 'disabledbutton': '']"
            >
              <input
                v-if="adminsData.faq_id != ''"
                type="hidden"
                name="id"
                v-model="adminsData.faq_id"
              />
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>
                      {{ $t('LBL_CATEGORY')}}
                      <span class="required">*</span>
                    </label>
                    <vue-bootstrap-typeahead
                      v-model="adminsData.faq_category"
                      :data="faqArray"
                      @hit="onSelectCategory"
                      ref="categoryTypeahead"
                      :placeholder="$t('LBL_START_TYPING_CATEGORY_NAME')"
                      :max-matches="5"
                      :min-matching-chars="1"
                    />
                    <span
                      v-if="errors.first('faq_category')"
                      class="error text-danger"
                    >{{ errors.first('faq_category') }}</span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>
                      {{ $t('LBL_FAQS_TITLE')}}
                      <span class="required">*</span>
                    </label>
                    <input
                      type="text"
                      v-model="adminsData.faq_title"
                      name="faq_title"
                      v-validate="'required'"
                      :data-vv-as="$t('LBL_FAQS_TITLE') "
                      data-vv-validate-on="none"
                      class="form-control"
                    />
                    <span
                      v-if="errors.first('faq_title')"
                      class="error text-danger"
                    >{{ errors.first('faq_title') }}</span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>
                      {{ $t('LBL_FAQS_CONTENT')}}
                      <span class="required">*</span>
                    </label>
                    <textarea
                      class="form-control"
                      rows="5"
                      cols="15"
                      v-model="adminsData.faq_content"
                      name="faq_content"
                      v-validate="'required'"
                      :data-vv-as="$t('LBL_FAQS_CONTENT') "
                      data-vv-validate-on="none"
                    ></textarea>
                    <span
                      v-if="errors.first('faq_content')"
                      class="error text-danger"
                    >{{ errors.first('faq_content') }}</span>
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
                  <p>{{ $t('LBL_USE_ICONS_FOR_ADVANCED_ACTIONS') }}</p>
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
                    :disabled="!isComplete || clicked==1 || (!$canWrite('FAQ'))"
                    v-if="selectedPage == 'addform'"
                    v-bind:class="clicked==1 && 'gb-is-loading'"
                  >{{ $t('BTN_FAQS_CREATE') }}</button>
                  <button
                    type="submit"
                    class="btn btn-brand gb-btn gb-btn-primary"
                    @click="validateRecord()"
                    :disabled="!isComplete || clicked==1 || (!$canWrite('FAQ'))"
                    v-if="selectedPage == 'editform'"
                    v-bind:class="clicked==1 && 'gb-is-loading'"
                  >{{ $t('BTN_FAQS_UPDATE') }}</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <DeleteModel
          :deletePopText="$t('LBL_FAQS_DELETE_CONFIRMATION')"
          :recordId="recordId"
          @deleted="deleteRecord(recordId)"
        ></DeleteModel>
      </div>
    </div>
  </div>
</template>
<script>
import VueBootstrapTypeahead from "vue-bootstrap-typeahead";
const tableFields = {
  faq_id: "",
  faq_faqcat_id: "",
  faq_category: "",
  faq_title: "",
  faq_content: "",
  faq_publish: "",
  faq_display_order: ""
};
const searchFields = {
  search: ""
};
export default {
  components: {
    "vue-bootstrap-typeahead": VueBootstrapTypeahead
  },
  data: function() {
    return {
      records: [],
      baseUrl: baseUrl,
      pagination: [],
      clicked: 0,
      loading: false,
      adminsData: tableFields,
      searchData: searchFields,
      search: "",
      selectedPage: "",
      categories: [],
      recordId: "",
      faqArray: [],
      editText: "",
      maxCharacterLimit: "30",
      createdByUser: "",
      updatedByUser: "",
      updatedAt: "",
      createdAt: "",
      pageLoaded: 0,
      showEmpty: 1
    };
  },
  computed: {
    isComplete() {
      return (
        this.adminsData.faq_title &&
        this.adminsData.faq_content &&
        this.adminsData.faq_category
      );
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
    onSelectCategory: function(faqName) {
      this.adminsData.faq_category = faqName;
    },
    emptyFormValues: function() {
      this.adminsData = {
        faq_id: "",
        faq_faqcat_id: "",
        faq_category: "",
        faq_title: "",
        faq_content: "",
        faq_publish: "",
        faq_display_order: ""
      };
      this.editText = "";
      this.errors.clear();
    },
    pageRecords: function(pageNo, pageLoad = false) {
      this.loading = pageLoad;
      let formData = this.$serializeData(this.searchData);
      if (typeof this.pagination.per_page != "undefined") {
        formData.append("per_page", this.pagination.per_page);
      }
      this.$http
        .post(adminBaseUrl + "/faqs/list?page=" + pageNo, formData)
        .then(response => {
          this.records = response.data.data.faqs.data;
          this.pagination = response.data.data.faqs;
          this.categories = response.data.data.categories;
          this.faqArray = Object.values(this.categories);
          this.loading = false;
          this.showEmpty = response.data.data.showEmpty;
          this.pageLoaded = 1;
        });
    },
    searchRecords: function() {
      if (this.selectedPage !== false) {
        this.selectedPage = false;
      }
      this.pageRecords(this.pagination.current_page);
    },
    currentPage: function(page) {
      this.pageRecords(page);
    },
    clearSearch: function() {
      this.searchData = {
        search: ""
      };
      this.pageRecords(this.pagination.current_page);
    },
    validateRecord: function() {
      this.$validator.validateAll().then(res => {
        if (res) {
          let input = this.adminsData;
          this.clicked = 1;
          if (input.faq_id != "") {
            this.updateRecord(input.faq_id, input);
          } else {
            this.saveRecord(input);
          }
        } else {
          this.clicked = 0;
        }
      });
    },
    saveRecord: function(input) {
      input.faq_display_order = 0;
      let formData = this.$serializeData(input);
      this.$http.post(adminBaseUrl + "/faqs", formData).then(
        response => {
          this.clicked = 0;
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.pageRecords(this.pagination.current_page);
          this.selectedPage = "";
          this.emptyFormValues();
        },
        response => {
          this.clicked = 0;
          this.validateErrors(response);
        }
      );
    },
    editRecord: function(input) {
      this.emptyUpdatedFieldValue();
      this.adminsData.faq_id = input.faq_id;
      this.adminsData.faq_faqcat_id = input.faq_faqcat_id;
      this.adminsData.faq_category = input.category.faqcat_name;
      this.adminsData.faq_title = input.faq_title;
      this.adminsData.faq_content = input.faq_content;
      this.adminsData.faq_publish = input.faq_publish;
      this.adminsData.faq_display_order = input.faq_display_order;
      this.editText = input.faq_title;
      this.selectedPage = "editform";
      if (input.created_at != null && "admin_username" in input.created_at) {
        this.createdByUser = input.created_at.admin_username;
      }
      if (input.updated_at != null && "admin_username" in input.updated_at) {
        this.updatedByUser = input.updated_at.admin_username;
      }
      this.updatedAt = input.faq_updated_at ? input.faq_updated_at : "";
      this.createdAt = input.faq_created_at ? input.faq_created_at : "";
      let thisObj = this;
      setTimeout(function() {
        thisObj.$refs.categoryTypeahead.inputValue = input.category.faqcat_name;
      }, 5);
    },
    updateRecord: function(id, input) {
      let formData = this.$serializeData({
        faq_category: input.faq_category,
        faq_title: input.faq_title,
        faq_content: input.faq_content,
        faq_display_order: input.faq_display_order
      });
      formData.append("_method", "put");
      this.$http.post(adminBaseUrl + "/faqs/" + id, formData).then(
        response => {
          this.clicked = 0;
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.emptyFormValues();
          this.pageRecords(this.pagination.current_page);
          this.selectedPage = "";
        },
        response => {
          this.clicked = 0;
          this.validateErrors(response);
        }
      );
    },
    openAddPage: function() {
      this.emptyFormValues();
      this.selectedPage = "addform";
      if (this.searchData.search != "") {
        this.searchData.search = "";
        this.pageRecords();
      }
      if (typeof this.$refs.categoryTypeahead != "undefined") {
        this.$refs.categoryTypeahead.inputValue = "";
      }
      this.showEmpty = 0;
    },
    cancel: function() {
      this.selectedPage = false;
      if (this.records.length == 0) {
        this.showEmpty = 1;
      }
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
    onSwitchStatus: function(event, id) {
      let formData = this.$serializeData({
        status: event.target.checked
      });
      this.$http
        .post(adminBaseUrl + "/faqs/status/" + id, formData)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
        });
    },
    confirmDelete: function(event, dataid) {
      event.stopPropagation();
      this.recordId = dataid;
      this.$bvModal.show("deleteModel");
    },
    deleteRecord: function(recordId) {
      this.$http.delete(adminBaseUrl + "/faqs/" + recordId).then(response => {
        if (response.data.status == false) {
          toastr.error(response.data.message, "", toastr.options);
          return;
        }
        toastr.success(response.data.message, "", toastr.options);
        this.pageRecords(this.pagination.current_page);
        this.$bvModal.hide("deleteModel");
        this.selectedPage = "";
      });
    },
    emptyUpdatedFieldValue: function() {
      this.createdByUser = "";
      this.updatedByUser = "";
      this.updatedAt = "";
      this.createdAt = "";
    }
  },
  mounted: function() {
    this.searchData.search = "";
    this.refreshedSearchData();
  }
};
</script>