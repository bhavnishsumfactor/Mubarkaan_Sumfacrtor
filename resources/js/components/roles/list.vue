<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_ROLES') }}</h3>
          <div class="subheader__breadcrumbs">
            <a href="#" class="subheader__breadcrumbs-home">
              <i class="flaticon2-shelter"></i>
            </a>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_USERS')}}</span>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_ROLES')}}</span>
          </div>
        </div>
        <div class="subheader__toolbar" v-bind:class="[!$canWrite('ADMIN_USERS') ? 'disabledbutton': '']">
          <router-link :to="{name: 'rolesAdd'}" class="btn btn-white">
            <i class="fas fa-plus"></i>
            {{ $t('BTN_ADD')}}
          </router-link>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col"></div>
      </div>

      <div class="row justify-content-center">
        <div class="col-xl-8">
          <!--begin::Portlet-->
          <div class="portlet portlet--height-fluid">
            <div class="portlet__body pb-0 bg-gray flex-grow-0" v-if="showEmpty == 0">
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
            <div class="portlet__body portlet__body--fit" v-if="showEmpty == 0 && pageLoaded==1">
              <!--begin::Section-->
              <div class="section">
                <div class="section__content">
                  <table class="table table-data table-justified">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>{{ $t('LBL_ROLE_NAME') }}</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody
                      v-if="loading==false && records.length == 0 && showEmpty == 0 && pageLoaded==1"
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
                            v-if="!$canWrite('ADMIN_USERS')"
                          >{{ record.role_name }}</a>
                          <div v-else>{{ record.role_name }}</div>
                        </td>
                        <td>
                          <div class="actions">
                            <router-link
                              :to="{name: 'rolesEdit', params: {id: record.role_id}}"
                              v-b-tooltip.hover
                              :title="$t('TTL_EDIT')"
                              class="btn btn-light btn-sm btn-icon"
                              v-bind:class="[!$canWrite('ADMIN_USERS') ? 'disabled no-drop': '']"
                            >
                              <svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                                                            </svg>
                            </router-link>
                            <a
                              class="btn btn-light btn-sm btn-icon"
                              v-b-tooltip.hover
                              :title="$t('TTL_DELETE')"
                              href="javascript:;"
                              @click.capture="confirmDelete($event, record.role_id)"
                              v-bind:class="[!$canWrite('ADMIN_USERS') ? 'disabled no-drop': '']"
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
                    {{$t('LBL_CLICK_THE')}}
                    <router-link :to="{name: 'rolesAdd'}" class="btn btn-brand btn-small">
                      <i class="fas fa-plus"></i>
                      {{ $t('BTN_ADD')}}
                    </router-link>{{$t('LBL_BUTTON_TO_CREATE_ROLES')}}
                  </p>
                </div>
                <div class="get-started-media">
                  <img
                    :src="baseUrl+'/admin/images/get-started-graphic/get-started-blog-articles.svg'"
                  />
                </div>
              </div>
            </div>
            <loader v-if="loading"></loader>
            <div class="portlet__foot" v-if="records.length > 0">
              <pagination :pagination="pagination" @currentPage="currentPage"></pagination>
            </div>
            <DeleteModel
              :deletePopText="$t('LBL_ROLE_DELETE_CONFIRMATION_TEXT')"
              :recordId="recordId"
              @deleted="deleteRecord(recordId)"
            ></DeleteModel>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
const tableFields = {
  role_id: "",
  role_name: "",
  permission: []
};
const searchFields = {
  role_name: ""
};
export default {
  data: function() {
    return {
      records: [],
      search: "",
      modalId: "deleteModel",
      recordId: "",
      pagination: [],
      loading: false,
      searchData: searchFields,
      permissionsData: [],
      adminsData: tableFields,
      baseUrl: baseUrl,
      pageLoaded: 0,
      showEmpty: 1
    };
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
    searchRecords: function() {
      this.pageRecords(this.pagination.current_page);
    },
    currentPage: function(page) {
      this.pageRecords(page);
    },
    pageRecords: function(pageNo, pageLoad = false) {
      this.loading = pageLoad;
      let formData = this.$serializeData({
        search: this.search
      });
      if (typeof this.pagination.per_page != "undefined") {
        formData.append("per_page", this.pagination.per_page);
      }
      this.$http
        .post(adminBaseUrl + "/roles/list?page=" + pageNo, formData)
        .then(response => {
          this.records = response.data.data.roles.data;
          this.pagination = response.data.data.roles;
          this.loading = false;
          this.showEmpty = response.data.data.showEmpty;
          this.pageLoaded = 1;
        });
    },
    confirmDelete: function(event, dataid) {
      event.stopPropagation();
      this.recordId = dataid;
      this.$bvModal.show(this.modalId);
    },
    deleteRecord: function(recordId) {
      this.$http.delete(adminBaseUrl + "/roles/" + recordId).then(response => {
        if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            this.$bvModal.hide(this.modalId);
            return;
        }
        toastr.success(response.data.message, "", toastr.options);
        this.$bvModal.hide(this.modalId);
        this.pageRecords(this.pagination.current_page);
      });
    },
    openViewMode: function(record) {
      this.$router.push({ name: "rolesEdit", params: { id: record.role_id } });
    }
  },
  mounted() {
    this.search = "";
    this.refreshedSearchData();
  }
};
</script>