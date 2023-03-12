<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_TAX_MANAGEMENT') }}</h3>
        </div>
        <div class="subheader__toolbar">
          <router-link
            :to="{name: 'taxCreate'}"
            class="btn btn-white"
            v-bind:class="[(!$canWrite('TAX_SETTINGS')) ? 'disabledbutton no-drop': '']"
          >
            <i class="fas fa-plus"></i>
            {{ $t('BTN_ADD')}}
          </router-link>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-xl-9">
          <div class="alert alert-light alert-elevate fade show" role="alert">
            <div class="alert-icon">
              <i class="flaticon-signs-2 font-brand"></i>
            </div>
            <div
              class="alert-text"
            >{{ $t('LBL_TAX_INFO') }}.</div>
          </div>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-xl-9">
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
                        :placeholder="$t('PLH_SEARCH') +  '...' "
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
              <div class="section">
                <div class="section__content">
                  <table class="table table-data table-justified">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>{{$t('LBL_TAX_NAME')}}</th>
                        <th>{{$t('LBL_TAX_SUMMARY')}}</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody
                      v-if="loading==false && records.length == 0 && showEmpty == 0 && pageLoaded==1"
                    >
                      <tr>
                        <td colspan="4">
                          <noRecordsFound></noRecordsFound>
                        </td>
                      </tr>
                    </tbody>
                    <tbody v-else>
                      <tr v-for="(record, index) in records" :key="'records'+index">
                        <td scope="row">{{ pagination.from+index }}</td>
                        <td>
                          <a
                            href="javascript:;"
                            @click.prevent="openViewMode(record)"
                            v-if="!$canWrite('TAX_SETTINGS')"
                          >{{ record.taxgrp_name }}</a>
                          <div v-else>{{ record.taxgrp_name }}</div>
                        </td>
                        <td>
                          <ul class="list-group tax-list-wrap">
                            <li class="list-group-item" v-for="(group_type, index) in record.group_types" :key="'group'+index">
                                 {{group_type.tgtype_name}} ({{group_type.tgtype_rate}}%) {{stateTypes[group_type.tgtype_state_type]}}
                            </li>
                          </ul>
                        </td>
                        <td>
                          <div class="actions">
                            <router-link
                              :to="{name: 'taxEdit', params: {id: record.taxgrp_id}}"
                              v-b-tooltip.hover
                              :title="$t('TTL_EDIT')"
                              class="btn btn-light btn-sm btn-icon"
                              v-bind:class="[!$canWrite('TAX_SETTINGS') ? 'disabled no-drop': '']"
                            >
                              <svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                                                            </svg>
                            </router-link>

                            <a
                              class="btn btn-light btn-sm btn-icon"
                              href="javascript:;"
                              @click.capture="confirmDelete($event, record.taxgrp_id)"
                              v-b-tooltip.hover
                              :title="$t('TTL_DELETE')"
                              v-bind:class="[!$canWrite('TAX_SETTINGS') ? 'disabled no-drop': '']"
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
                    <router-link :to="{name: 'taxCreate'}" class="btn btn-brand btn-small">
                      <i class="fas fa-plus"></i>
                      {{ $t('BTN_ADD')}}
                    </router-link>
                    {{ $t('LBL_BUTTON_TO_CREATE_TAX') }}
                  </p>
                </div>
                <div class="get-started-media">
                  <img
                    :src="baseUrl+'/admin/images/get-started-graphic/get-started-pickup-address.svg'"
                  />
                </div>
              </div>
            </div>
            <loader v-if="loading"></loader>
            <div class="portlet__foot" v-if="records.length > 0">
              <pagination :pagination="pagination" @currentPage="currentPage"></pagination>
            </div>
          </div>
          <!--end::Form-->
        </div>
        <!--end::Portlet-->
      </div>
    </div>
    <DeleteModel
      :deletePopText="$t('LBL_TAX_DELETE_CONFIRMATION')"
      :recordId="recordId"
      @deleted="deleteRecord(recordId)"
    ></DeleteModel>
  </div>
</template>
<script>
const searchFields = {
  post_title: ""
};
export default {
  data: function() {
    return {
      records: [],
      recordId: "",
      search: "",
      pagination: [],
      loading: false,
      modelId: "deleteModel",
      searchData: searchFields,
      stateTypes: [],
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
    currentPage: function(page) {
      this.pageRecords(page);
    },
    searchRecords: function() {
      this.pageRecords(this.pagination.current_page);
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
        .post(adminBaseUrl + "/tax-group/list?page=" + pageNo, formData)
        .then(response => {
          this.records = response.data.data.taxGroup.data;
          this.pagination = response.data.data.taxGroup;
          this.stateTypes = response.data.data.stateTypes;
          this.loading = false;
          this.showEmpty = response.data.data.showEmpty;
          this.pageLoaded = 1;
        });
    },
    confirmDelete: function(event, dataid) {
      event.stopPropagation();
      this.recordId = dataid;
      this.$bvModal.show(this.modelId);
    },
    deleteRecord: function(recordId) {
      this.$http
        .delete(adminBaseUrl + "/tax-group/" + recordId)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
          this.pageRecords(this.pagination.current_page);
          this.$bvModal.hide(this.modelId);
        });
    },
    openViewMode: function(record) {
      this.$router.push({ name: "taxEdit", params: { id: record.taxgrp_id } });
    }
  },
  mounted() {
    this.search = "";
    this.refreshedSearchData();
  }
};
</script>
