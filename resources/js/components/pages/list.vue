<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_PAGES') }}</h3>
          <div class="subheader__breadcrumbs">
            <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                <i class="flaticon2-shelter"></i>
            </router-link>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_CMS')}}</span>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_PAGES')}}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-xl-8">
          <!--begin::Portlet-->
          <div class="portlet portlet--height-fluid">
            <div class="portlet__body pb-0 bg-gray flex-grow-0">
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <div class="input-icon input-icon--left">
                      <input
                        type="text"
                        class="form-control"
                        :placeholder="$t('PLH_SEARCH') + '...'"
                        id="generalSearch"
                        @keyup="searchRecords"
                        v-model="search"
                      />
                      <span class="input-icon__icon input-icon__icon--left">
                        <span>
                          <i class="la la-search"></i>
                        </span>
                      </span>
                      <span
                        class="input-icon__icon input-icon__icon--right"
                        v-if="search!=''"
                        @click="search='';pageRecords(1);"
                      >
                        <span>
                          <i class="fas fa-times"></i>
                        </span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr class="m-0" />
            <div class="portlet__body portlet__body--fit" v-if="records.length > 0">
              <!--begin::Section-->
              <div class="section">
                <div class="section__content">
                  <table class="table table-data table-justified">
                    <thead>
                      <tr>
                        <th>{{'#' }}</th>
                        <th>{{ $t('LBL_PAGES_NAME')}}</th>
                        <th>{{ $t('LBL_PUBLISH')}}</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(record, index) in records"
                        :key="record.page_id"
                        :data-id="record.page_id"
                      >
                        <td scope="row">{{ pagination.from+index }}</td>
                        <td>{{ record.page_name }}</td>
                        <td>
                          <label class="switch switch--sm">
                            <input
                              type="checkbox"
                              name="page_publish"
                              v-model="record.page_publish"
                              v-if="record.page_default == 0"
                              @change="onSwitchStatus($event, record.page_id)"
                              :disabled="!$canWrite('PAGES')"
                            />
                            <input
                              type="checkbox"
                              name="page_publish"
                              v-model="record.page_publish"
                              v-if="record.page_default == 1"
                              readonly="readonly"
                              disabled="disabled"
                            />
                            <span
                              v-b-tooltip.hover
                              :title="record.page_publish == 1 ? $t('TTL_UNPUBLISH') : $t('TTL_PUBLISH') "
                            ></span>
                          </label>
                        </td>
                        <td>
                          <div class="actions">
                          <a
                            class="btn btn-light btn-sm btn-icon"
                            v-b-tooltip.hover
                            :title="$t('TTL_EDIT')"
                            href="javascript:;"
                            @click.prevent="openEditPage(record)"
                            v-bind:class="[!$canWrite('PAGES') ? 'disabled no-drop': '']"
                          >
                            <svg>   
                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                            </svg>
                          </a>

                          <a
                            class="btn btn-light btn-sm btn-icon"
                            v-b-tooltip.hover
                            :title="$t('TTL_PREVIEW')"
                            v-if="record.page_type != 3 && record.page_type != 4"
                            :href="pageUrl(record.url_rewrite)"
                            target="_blank"
                            v-bind:class="[!$canRead('PAGES') ? 'disabled no-drop': '']"
                          >
                            <svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#view'" ></use>                               
                                                            </svg>
                          </a>

                          <a
                            class="btn btn-light btn-sm btn-icon"
                            v-b-tooltip.hover
                            :title="$t('TTL_COPY_LINK')"
                            v-if="record.page_type != 3 && record.page_type != 4"
                            href="javascript:;"
                            @click="copyLinkToClipboard(record.url_rewrite)"
                            v-bind:class="[!$canWrite('PAGES') ? 'disabled no-drop': '']"
                          >
                            <svg>   
                              <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#links'" ></use>                               
                            </svg>
                          </a>

                          <a
                            class="btn btn-light btn-sm btn-icon"
                            v-b-tooltip.hover
                            :title="$t('TTL_CLONE')"
                            v-if="types[record.page_id] == 'content'"
                            href="javascript:;"
                            @click="promptClonePage(record.page_id)"
                            v-bind:class="[!$canWrite('PAGES') ? 'disabled no-drop': '']"
                          >
                            <i class="flaticon2-copy"></i>
                          </a>
                          <span v-if="record.page_default == 0">
                            <a
                              class="btn btn-light btn-sm btn-icon"
                              v-b-tooltip.hover
                              :title="$t('TTL_DELETE')"
                              href="javascript:;"
                              @click="confirmDelete(record.page_id)"
                              v-bind:class="[!$canWrite('PAGES') ? 'disabled no-drop': '']"
                            >
                              <svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'" ></use>                               
                                                            </svg>
                            </a>
                          </span>
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
              <pagination :pagination="pagination" @currentPage="currentPage"></pagination>
            </div>

            <DeleteModel
              :deletePopText="$t('LBL_DELETE_PAGE_CONFIRM_TEXT')"
              :recordId="recordId"
              @deleted="deleteRecord(recordId)"
            ></DeleteModel>

            <b-modal id="cloneModel" centered title="BootstrapVue">
              <template v-slot:modal-header>
                <h5 class="modal-title" id="exampleModalLabel">{{ $t('LBL_CLONE_PAGE') }}</h5>
                <button type="button" class="close" @click="$bvModal.hide('cloneModel')"></button>
              </template>
              <div class="form-group row">
                <label class="col-xl-6 col-lg-6 col-form-label">{{ $t('LBL_PAGE_NAME') }}</label>
                <div class="col-lg-6 col-xl-6">
                  <input
                    type="text"
                    v-model="pageName"
                    name="page_name"
                    v-validate="'required'"
                    :data-vv-as="$t('LBL_PAGE_NAME')"
                    data-vv-validate-on="none"
                    class="form-control"
                  />
                  <span
                    v-if="errors.first('page_name')"
                    class="error text-danger"
                  >{{ errors.first('page_name') }}</span>
                </div>
              </div>
              <template v-slot:modal-footer>
                <button
                  type="button"
                  class="btn btn-brand gb-btn gb-btn-primary"
                  data-dismiss="modal"
                  @click="clonePage"
                  :disabled="!isComplete || clicked==1"
                  v-bind:class="clicked==1 && 'gb-is-loading'"
                >{{ $t('BTN_CLONE')}}</button>
              </template>
            </b-modal>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
const tableFields = {
  page_id: "",
  page_name: "",
  page_content: "",
  page_publish: ""
};
const searchFields = {
  page_name: ""
};
export default {
  data: function() {
    return {
      baseUrl: baseUrl,
      records: [],
      types: [],
      ModalID: "deleteModel",
      pagination: [],
      clicked: 0,
      loading: false,
      recordData: tableFields,
      searchData: searchFields,
      search: "",
      recordId: "",
      pageId: "",
      pageName: ""
    };
  },
  computed: {
    isActived: function() {
      return this.pagination.current_page;
    },
    pagesNumber: function() {
      return this.$pagesNumber(this.pagination);
    },
    isComplete() {
      return this.pageName;
    }
  },
  watch: {
    $route: "refreshedSearchData"
  },
  methods: {
    refreshedSearchData() {
      if (this.$route.query.s) {
        this.search = this.$route.query.s;
      }
      this.pageRecords(1, true);
    },
    emptyFormValues: function() {
      this.recordData = {
        page_id: "",
        page_name: "",
        page_content: "",
        page_publish: ""
      };
      this.errors.clear();
    },
    pageRecords: function(pageNo, pageLoad = false, showLastPage) {
      this.loading = pageLoad;
      Object.assign(this.searchData, {
        search: this.search
      });
      let formdata = this.$serializeData(this.searchData);
      if (typeof showLastPage !== "undefined") {
        formdata.append("showLastPage", 1);
      }
      this.$http
        .post(adminBaseUrl + "/pages/list?page=" + pageNo, formdata)
        .then(response => {
          this.records = response.data.data.pages.data;
          this.types = response.data.data.types;
          this.loading = false;
          this.pagination = response.data.data.pages;
        });
    },
    currentPage: function(page) {
      this.pageRecords(page);
    },
    searchRecords: function() {
      this.pageRecords(this.pagination.current_page);
    },
    clearSearch: function() {
      this.searchData = {
        page_name: ""
      };
      this.pageRecords(this.pagination.current_page);
    },
    onSwitchStatus: function(event, id) {
      let formdata = this.$serializeData({
        status: event.target.checked
      });
      this.$http
        .post(adminBaseUrl + "/pages/status/" + id, formdata)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
        });
    },
    openEditPage: function(record) {
      if (record.page_type == 3) {
        this.$router.push({ name: "settingsProductListing" });
      } else if (record.page_type == 4) {
        this.$router.push({ name: "settingsProductDetail" });
      } else {
        if (this.$canWrite("PAGES")) {
          window.location.replace(
            adminBaseUrl + "/pages/" + record.page_id + "/edit"
          );
        } else {
          toastr.error("Un-autorized Access", "", toastr.options);
        }
      }
    },
    confirmDelete: function(dataid) {
      this.recordId = dataid;
      this.$bvModal.show(this.ModalID);
    },
    deleteRecord: function(recordId) {
      this.$http.delete(adminBaseUrl + "/pages/" + recordId).then(response => {
        if (response.data.status == false) {
          toastr.error(response.data.message, "", toastr.options);
          return;
        }
        toastr.success(response.data.message, "", toastr.options);
        this.pageRecords(this.pagination.current_page);
        this.$bvModal.hide(this.ModalID);
      });
    },
    copyLinkToClipboard: function(copyLink) {
      if (copyLink && copyLink.urlrewrite_custom) {
        copyLink = baseUrl + "/" + copyLink.urlrewrite_custom;
      } else if (copyLink && copyLink.urlrewrite_original) {
        copyLink = baseUrl + "/" + copyLink.urlrewrite_original;
      }
      let $temp = $("<input>");
      $("body").append($temp);
      $temp.val(copyLink).select();
      document.execCommand("copy");
      $temp.remove();
      toastr.success(this.$t("NOTI_PAGE_URL_COPIED"), "", toastr.options);
    },
    pageUrl: function(url) {
      if (url && url.urlrewrite_custom) {
        url = baseUrl + "/" + url.urlrewrite_custom;
      } else if (url && url.urlrewrite_original) {
        url = baseUrl + "/" + url.urlrewrite_original;
      }
      return url;
    },
    promptClonePage: function(pageid) {
      this.pageId = pageid;
      this.$bvModal.show("cloneModel");
    },
    clonePage: function() {
      this.clicked = 1;
      let formdata = this.$serializeData({
        page_id: this.pageId,
        page_name: this.pageName
      });
      this.$http.post(adminBaseUrl + "/pages/duplicate", formdata).then(
        response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.pageRecords(this.pagination.current_page, false, 1);
          this.$bvModal.hide("cloneModel");
          this.pageId = "";
          this.pageName = "";
          this.clicked = 0;
        },
        response => {
          this.clicked = 0;
          this.validateErrors(response);
        }
      );
    },
    validateErrors: function(response) {
      this.errors.clear();
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
    this.search = "";
    this.refreshedSearchData();
  }
};
</script>