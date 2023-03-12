<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_USERS') }}</h3>
          <div class="subheader__breadcrumbs">
            <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                <i class="flaticon2-shelter"></i>
            </router-link>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_USERS')}}</span>
          </div>
        </div>
        <div class="subheader__toolbar" v-if="$canWrite('USERS')">
          <button
            @click="userExport()"
            class="btn btn-white"
            href="javascript:void(0);"
            :disabled="records.length == 0"
          >
            <i class="fas fa-download"></i>
            {{ $t('BTN_EXPORT') }}
          </button>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-xl-12">
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
                        :readonly="records.length == 0 && search === ''"
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
            <div class="portlet__body portlet__body--fit">
              <!--begin::Section-->
              <div class="section">
                <div class="section__content">
                  <table class="table table-data table-justified">
                    <thead>
                      <tr>
                        <th>{{ '#' }}</th>
                        <th>{{ $t('LBL_USER_NAME') }}</th>
                        <th>{{ $t('LBL_EMAIL')}}</th>
                        <th>{{ $t('LBL_PHONE')}}</th>
                        <th>{{ $t('LBL_PUBLISH')}}</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody
                      v-if="loading==false && records.length == 0 && search == '' && showEmpty == 1 && pageLoaded==1"
                    >
                      <tr>
                        <td colspan="7">
                          <div class="no-data-found">
                            <div class="img">
                              <img
                                :src="activeThemeUrl+'/media/retina/empty/no-messages.svg'"
                                width="125px"
                                height="125px"
                              />
                            </div>
                            <div class="data">
                              <h3>{{ $t('LBL_NO_USERS_TO_SHOW')}}</h3>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                    <tbody
                      v-else-if="loading==false && records.length == 0 && search != '' && showEmpty == 0 && pageLoaded==1"
                    >
                      <tr>
                        <td colspan="7">
                          <noRecordsFound></noRecordsFound>
                        </td>
                      </tr>
                    </tbody>
                    <tbody v-else>
                      <tr v-for="(record, index) in records" :key="'listing'+index">
                        <td scope="row">{{ pagination.from+index }}</td>
                        <td>
                          <router-link
                            v-b-tooltip.hover
                            :title="$t('TTL_VIEW_USER')"
                            :to="{name: 'userDetails', params: {id: record.user_id}}"
                          >{{ record.user_name }}</router-link>
                        </td>
                        <td>
                          <router-link
                            v-b-tooltip.hover
                            :title="$t('TTL_VIEW_USER')"
                            :to="{name: 'userDetails', params: {id: record.user_id}}"
                          >{{ record.user_email }}</router-link>
                        </td>
                        <td>
                          {{ (record.user_phone != null && record.user_phone != '') ? ( record.country_phonecode != null ? '+' + record.country_phonecode +' '+record.user_phone : '' ) : '' }}
                        </td>
                        <td>
                          <span class="d-flex align-items-center">
                            <label
                              class="switch switch--sm m-0"
                              v-b-tooltip.hover
                              :title="record.user_publish == 1 ? $t('TTL_UNPUBLISH') : $t('TTL_PUBLISH') "
                              v-bind:class="[(checkUserVerification(record) == false || record.user_gdpr_approved == 1) ? 'disabledbutton' : '']"
                            >
                              <input
                                type="checkbox"
                                v-model="record.user_publish"
                                @change="onSwitchStatus($event, record.user_id)"
                                :disabled="!$canWrite('USERS')"
                              />
                              <span></span>
                            </label>
                            <i
                              class="fas fa-info-circle"
                              v-if="emailCheck == '1' && checkUserVerification(record) == false"
                              data-container="body"
                              v-b-tooltip.hover
                              :title="checkFailedVerification(record) == 'email' ? $t('TTL_USER_NOT_VERIFIED') : $t('TTL_USER_PHONE_NOT_VERIFIED')"
                            ></i>
                          </span>
                        </td>
                        <td>
                          <div class="actions">
                            <a
                              class="btn btn-light btn-sm btn-icon"
                              href="javascript:;"
                              v-b-tooltip.hover
                              :title="$t('TTL_SEND_EMAIL')"
                              v-if="record.user_email != ''"
                              @click="(record.user_gdpr_approved == 1) ? '' : openSendEmail(record.user_id)"
                              v-bind:class="[!$canWrite('USERS') ? 'disabled no-drop': '', (record.user_gdpr_approved == 1) ? 'disabledbutton' : '']"
                            >
                              <svg>   
                                  <use :xlink:href="adminBaseUrl+'/images/retina/sprite.svg#email-icon'" :href="adminBaseUrl+'/images/retina/sprite.svg#email-icon'"></use>                               
                              </svg>
                            </a>
                            <a
                              class="btn btn-light btn-sm btn-icon"
                              :href="adminBaseUrl + '/users/login/'+ record.user_id"
                              v-bind:class="[(record.user_gdpr_approved == 1) ? 'disabledbutton' : '', (!$canWrite('USERS')) ? 'disabled no-drop': '']"
                              target="_blank"
                              v-b-tooltip.hover
                              :title="$t('TTL_IMPERSONATE_USER')"
                            >
                              <svg>   
                                  <use :xlink:href="adminBaseUrl+'/images/retina/sprite.svg#impersonate-icon'" :href="adminBaseUrl+'/images/retina/sprite.svg#impersonate-icon'"></use>                               
                              </svg>
                            </a>
                            <router-link
                              class="btn btn-light btn-sm btn-icon"
                              v-b-tooltip.hover
                              :title="$t('TTL_EDIT')"
                              :to="{name: 'userDetails', params: {id: record.user_id}}"
                              v-bind:class="[!$canWrite('USERS') ? 'disabled no-drop': '']"
                            >
                              <svg>   
                                  <use :xlink:href="adminBaseUrl+'/images/retina/sprite.svg#edit-icon'" :href="adminBaseUrl+'/images/retina/sprite.svg#edit-icon'"></use>                               
                              </svg>
                            </router-link>

                            <a
                              class="btn btn-light btn-sm btn-icon"
                              href="javascript:;"
                              v-b-tooltip.hover
                              :title="$t('TTL_DELETE')"
                              @click="confirmDelete($event, record.user_id)"
                              v-bind:class="[!$canWrite('USERS') ? 'disabled no-drop': '']"
                            >
                             <svg>   
                                  <use :xlink:href="adminBaseUrl+'/images/retina/sprite.svg#delete-icon'" :href="adminBaseUrl+'/images/retina/sprite.svg#delete-icon'"></use>                               
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
            <loader v-if="loading"></loader>
            <div class="portlet__foot" v-if="records.length > 0">
              <pagination :pagination="pagination" @currentPage="currentPage"></pagination>
            </div>
            <DeleteModel
              :deletePopText="$t('LBL_USER_DELETE_CONFIRMATION')"
              :recordId="recordId"
              @deleted="deleteRecord(recordId)"
            ></DeleteModel>
            <exportPopup :groups="userGroups"> </exportPopup>
          </div>
          <sendMail :recordId="recordId" />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import sendMail from "./sendMail";
import exportPopup from "./userExportPopup";

export default {
    components: {
        sendMail,
        exportPopup
    },
    data: function() {
        return {
            adminBaseUrl: adminBaseUrl,
            activeThemeUrl: activeThemeUrl,
            records: [],
            search: "",
            modalId: "deleteModel",
            pagination: [],
            loading: false,
            loadingRightPanel: false,
            recordId: "",
            emailCheck: 0,
            pageLoaded: 0,
            showEmpty: 1,
            userGroups:[]
        };
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
        .post(adminBaseUrl + "/users/list?page=" + pageNo, formData)
        .then(response => {
            this.records = response.data.data.records.data;
            this.pagination = response.data.data.records;
            this.emailCheck = response.data.data.emailCheck;
            this.loading = false;
            this.showEmpty = response.data.data.showEmpty;
            this.pageLoaded = 1;
            this.userGroups = response.data.data.groups;
        });
    },
    onSwitchStatus: function(event, id) {
      let formData = this.$serializeData({
        status: event.target.checked
      });
      this.$http
        .post(adminBaseUrl + "/users/status/" + id, formData)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
        });
    },
    deleteRecord: function(recordId) {
      this.$http.delete(adminBaseUrl + "/users/" + recordId).then(response => {
        if (response.data.status == false) {
          toastr.error(response.data.message, "", toastr.options);
          return;
        }
        this.pageRecords(this.pagination.current_page);
        this.$bvModal.hide(this.modalId);
        toastr.success(response.data.message, "", toastr.options);
      });
    },
    openSendEmail: function(userid) {
      this.recordId = userid;
      this.$bvModal.show("send_mail");
    },
    confirmDelete: function(event, dataid) {
      event.stopPropagation();
      this.recordId = dataid;
      this.$bvModal.show(this.modalId);
    },
    userExport: function() {
        this.$bvModal.show("exportDetailPopup");
    },
    checkUserVerification: function(userData) {
        if ( (userData.user_email != null && userData.user_email_verified == 1) || (userData.user_phone != null && userData.user_phone_verified == 1) ) {
            return true;
        }
        return false;
    },
    checkFailedVerification : function(userData) {
        if ( (userData.user_email != null && userData.user_email_verified == 0) ) {
            return "email";
        } else if( (userData.user_phone != null && userData.user_phone_verified == 0) ) {
            return "phone";
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
    exportUsersXls: function() {
      window.open(adminBaseUrl + "/users/export-users?search=" + this.search);
    }
  },
  mounted: function() {
      this.search = "";
    this.refreshedSearchData();
  }
};
</script>
