<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_USER_GROUPS') }}</h3>
          <div class="subheader__breadcrumbs">
            <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                <i class="flaticon2-shelter"></i>
            </router-link>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_USERS')}}</span>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_USER_GROUPS')}}</span>
          </div>
        </div>
        <div class="subheader__toolbar" v-bind:class="[!$canWrite('USERS') ? 'disabledbutton': '']">
          <a @click="openAddPage" class="btn btn-white" href="javascript:void(0);">
            <i class="fas fa-plus"></i>
            {{ $t('BTN_ADD') }}
          </a>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class v-bind:class="[(showEmpty == 1) ? 'col-md-12': 'col-md-6']">
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
                        <th width="10%">{{ '#' }}</th>
                        <th width="55%">{{ $t('LBL_USER_GROUP_NAME') }}</th>
                        <th width="10%">{{ $t('LBL_USER_GROUP_MEMBERS') }}</th>
                        <th width="25%"></th>
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
                      <tr v-for="(record, index) in records" :key="index">
                        <td scope="row">{{ pagination.from+index }}</td>
                        <td>
                            <a href="javascript:;" @click.prevent="openEditPage(record)" v-if="!$canWrite('USERS')">
                                {{ record.ugroup_name }}
                            </a>
                            <div v-else>{{ record.ugroup_name }}</div>
                        </td>
                        <td>{{ record.members.length }}</td>
                        <td>
                          <div class="actions" v-bind:class="[!$canWrite('USERS') ? 'disabledbutton' : '']">
                          <a
                            class="btn btn-light btn-sm btn-icon"
                            v-bind:class="(record.members.length == 0)?'disabled':''"
                            href="javascript:;"
                            v-b-tooltip.hover
                            :title="$t('TTL_SEND_MAIL')"
                            @click.prevent="openSendEmail(record)"
                          >
                           <svg>   
                                  <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#email-icon'" :href="baseUrl+'/admin/images/retina/sprite.svg#email-icon'"></use>                               
                              </svg>
                          </a>
                          <a
                            class="btn btn-light btn-sm btn-icon"
                            href="javascript:;"
                            v-b-tooltip.hover
                            :title="$t('TTL_EDIT')"
                            @click.prevent="openEditPage(record)"
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
                            @click="confirmDelete($event, record.ugroup_id)"
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
                  <!-- Pagination -->
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
                    <a href="javascript:;" @click="openAddPage" v-bind:class="['btn btn-brand btn-small', !$canWrite('USERS') ? 'disabledbutton': '']">
                      <i class="fas fa-plus"></i> {{$t('BTN_ADD')}}
                    </a> {{$t('LBL_BUTTON_TO_CREATE_USER_GROUPS')}}
                  </p>
                </div>
                <div class="get-started-media">
                  <img :src="baseUrl+'/admin/images/get-started-graphic/get-started-brands.svg'" />
                </div>
              </div>
            </div>
            <div v-else>
              <loader v-if="loading"></loader>
            </div>
            <div class="portlet__foot" v-if="records.length > 0">
              <pagination :pagination="pagination" @currentPage="currentPage"></pagination>
            </div>
          </div>
        </div>

        <div class="col-md-6" v-if="showEmpty == 0" >
          <div class="portlet portlet--height-fluid" >
            <div class="portlet__head" v-if="selectedPage">
              <div class="portlet__head-label">
                <h3 class="portlet__head-title" v-if="selectedPage == 'editform'">
                  {{ $t('LBL_EDITING') }} -
                  <span class="editing-txt">{{editText}}</span>
                </h3>
                <h3
                  class="portlet__head-title"
                  v-if="selectedPage == 'addform'"
                >{{ $t('LBL_NEW_USER_GROUP_SETUP')}}</h3>
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

            <div class="portlet__body" v-bind:class="[(!$canWrite('USERS')) ? 'disabledbutton': '']" v-if="selectedPage">
              <input type="hidden" name="id" v-model="adminsData.ugroup_id" />
              <div class="row">
                <div class="col-md-12">
                  <label>
                    {{ $t('LBL_USER_GROUP_NAME') }}
                    <span class="required">*</span>
                  </label>
                  <div class="form-group">
                    <input
                      type="text"
                      v-model="adminsData.ugroup_name"
                      name="ugroup_name"
                      v-validate="'required'"
                      :data-vv-as="$t('LBL_USER_GROUP_NAME')"
                      data-vv-validate-on="none"
                      class="form-control"
                    />
                    <span
                      v-if="errors.first('ugroup_name')"
                      class="error text-danger"
                    >{{ errors.first('ugroup_name') }}</span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="list-box-wrapper" id="code" name="group_members">
                    <input type="hidden" id="sourcelist_row" v-model="sourceRow" />
                    <input type="hidden" id="sourcelist_all" v-model="sourceAll" />
                    <input type="hidden" id="destinationlist_row" v-model="destinationRow" />
                    <input type="hidden" id="destinationlist_all" v-model="destinationAll" />
                    <div class="list-box-item">
                      <div class="search-box">
                        <input
                          type="text"
                          :placeholder="$t('PLH_SEARCH')"
                          v-model="sourceSearch"
                          @keyup="sourceList"
                        />
                      </div>
                      <perfect-scrollbar :style="'max-height: 250px'">
                        <ul class="list-box sourceList">
                          <li
                            class="list-item"
                            v-for="item in source"
                            :key="item.user_id"
                            :data-id="item.user_id"
                          >{{item.user_name}}</li>
                        </ul>
                      </perfect-scrollbar>
                      <div class="p-2 text-center">
                        <a
                          class="loadSourceList"
                          href="javascript:void(0);"
                        >{{$t('LNK_CLICK_TO_LOAD_MORE')}}</a>
                      </div>
                      <div class="bulk-action">
                        <div class="select-all selectAllSource">{{$t('BTN_SELECT_ALL')}}</div>
                        <div class="deselect-all deselectAllSource">{{$t('BTN_UNSELECT_ALL')}}</div>
                      </div>
                    </div>
                    <div class="actions">
                      <div class="btn-action leftToRight">
                        <svg height="18" viewBox="0 0 256 512">
                          <path
                            fill="#ffffff"
                            d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"
                          />
                        </svg>
                      </div>
                      <div class="btn-action allleftToRight">
                        <svg height="18" viewBox="0 0 448 512">
                          <path
                            fill="#ffffff"
                            d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34zm192-34l-136-136c-9.4-9.4-24.6-9.4-33.9 0l-22.6 22.6c-9.4 9.4-9.4 24.6 0 33.9l96.4 96.4-96.4 96.4c-9.4 9.4-9.4 24.6 0 33.9l22.6 22.6c9.4 9.4 24.6 9.4 33.9 0l136-136c9.4-9.2 9.4-24.4 0-33.8z"
                          />
                        </svg>
                      </div>
                      <div class="btn-action rightToLeft">
                        <svg height="18" viewBox="0 0 256 512">
                          <path
                            fill="#ffffff"
                            d="M31.7 239l136-136c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9L127.9 256l96.4 96.4c9.4 9.4 9.4 24.6 0 33.9L201.7 409c-9.4 9.4-24.6 9.4-33.9 0l-136-136c-9.5-9.4-9.5-24.6-.1-34z"
                          />
                        </svg>
                      </div>
                      <div class="btn-action allrightToLeft">
                        <svg height="18" viewBox="0 0 448 512">
                          <path
                            fill="#ffffff"
                            d="M223.7 239l136-136c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9L319.9 256l96.4 96.4c9.4 9.4 9.4 24.6 0 33.9L393.7 409c-9.4 9.4-24.6 9.4-33.9 0l-136-136c-9.5-9.4-9.5-24.6-.1-34zm-192 34l136 136c9.4 9.4 24.6 9.4 33.9 0l22.6-22.6c9.4-9.4 9.4-24.6 0-33.9L127.9 256l96.4-96.4c9.4-9.4 9.4-24.6 0-33.9L201.7 103c-9.4-9.4-24.6-9.4-33.9 0l-136 136c-9.5 9.4-9.5 24.6-.1 34z"
                          />
                        </svg>
                      </div>
                    </div>
                    <div class="list-box-item">
                      <div class="search-box">
                        <input
                          type="text"
                          :placeholder="$t('PLH_SEARCH')"
                          v-model="destinationSearch"
                          @keyup="destinationList"
                        />
                      </div>
                      <perfect-scrollbar :style="'max-height: 250px'">
                        <ul class="list-group list-group-flush list-box destinationList">
                          <li
                            class="list-item"
                            v-for="item in destination"
                            :key="item.user_id"
                            :data-id="item.user_id"
                          >{{item.user_name}}</li>
                        </ul>
                      </perfect-scrollbar>
                      <div class="p-2 text-center">
                        <a
                          class="loadDestinationList"
                          href="javascript:void(0);"
                        >{{$t('LNK_CLICK_TO_LOAD_MORE')}}</a>
                      </div>
                      <div class="bulk-action">
                        <div class="select-all selectAllDestination">{{$t('BTN_SELECT_ALL')}}</div>
                        <div class="deselect-all deselectAllDestination">{{$t('BTN_UNSELECT_ALL')}}</div>
                      </div>
                    </div>
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
                    class="btn btn-secondary ml-2"
                    @click="cancel()"
                  >{{ $t('BTN_DISCARD')}}</button>
                </div>
                <div class="col-auto">
                  <button
                    type="submit"
                    class="btn btn-brand gb-btn gb-btn-primary"
                    @click="validateRecord()"
                    :disabled="!isComplete || clicked==1"
                    v-if="selectedPage == 'editform'"
                    v-bind:class="[clicked==1 && 'gb-is-loading', (!$canWrite('USERS')) ? 'disabledbutton': '']"
                  >{{ $t('BTN_USER_GROUP_UPDATE') }}</button>

                  <button
                    type="submit"
                    class="btn btn-brand gb-btn gb-btn-primary"
                    @click="validateRecord()"
                    :disabled="!isComplete || clicked==1"
                    v-if="selectedPage == 'addform'"
                    v-bind:class="clicked==1 && 'gb-is-loading'"
                  >{{ $t('BTN_USER_GROUP_CREATE') }}</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <DeleteModel
          :deletePopText="$t('LBL_GROUP_DELETE_CONFIRMATION')"
          :recordId="recordId"
          @deleted="deleteRecord(recordId)"
        ></DeleteModel>

        <b-modal id="modal_sendmail" centered title="BootstrapVue">
          <template v-slot:modal-header>
            <h5 class="modal-title" id="exampleModalLabel">{{$t('LBL_COMPOSE_MAIL')}}</h5>
            <button type="button" class="close" @click="$bvModal.hide('modal_sendmail')"></button>
          </template>
          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">
              {{$t('LBL_COMPOSE_MAIL_SUBJECT') }}
              <span class="required">*</span>
            </label>
            <div class="col-lg-9 col-xl-9">
              <input
                type="text"
                v-model="emailData.subject"
                name="subject"
                v-validate="'required'"
                :data-vv-as="$t('LBL_COMPOSE_MAIL_SUBJECT')"
                data-vv-validate-on="none"
                class="form-control"
              />
              <span
                v-if="errors.first('subject')"
                class="error text-danger"
              >{{ errors.first('subject') }}</span>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">
              {{$t('LBL_COMPOSE_MAIL_MESSAGE') }}
              <span class="required">*</span>
            </label>
            <div class="col-lg-9 col-xl-9">
              <textarea
                class="form-control"
                rows="5"
                v-model="emailData.message"
                name="message"
                v-validate="'required'"
                :data-vv-as="$t('LBL_COMPOSE_MAIL_MESSAGE')"
                data-vv-validate-on="none"
              ></textarea>
              <span
                v-if="errors.first('message')"
                class="error text-danger"
              >{{ errors.first('message') }}</span>
            </div>
          </div>
          <template v-slot:modal-footer>
            <button
              type="button"
              class="btn btn-brand sendMailBtn"
              data-dismiss="modal"
              :disabled="disabledBtn"
              @click="sendEmail(recordid)"
            >{{$t('BTN_COMPOSE_MAIL_SEND')}}</button>
          </template>
        </b-modal>
      </div>
    </div>
  </div>
</template>
<script>
const tableFields = {
  ugroup_id: "",
  ugroup_name: ""
};
const searchFields = {
  search: ""
};
export default {
  data: function() {
    return {
      adminsData: tableFields,
      selectedPage: false,
      baseUrl: baseUrl,
      records: [],
      recordId: "",
      modelId: "deleteModel",
      search: "",
      addFrom: false,
      searchData: searchFields,
      sourceSearch: "",
      destinationSearch: "",
      sourceRow: 0,
      destinationRow: 0,
      sourceAll: "",
      destinationAll: "",
      source: [{}],
      destination: [{}],
      sourceOriginal: [],
      emailData: [],
      disabledBtn: false,
      pagination: [],
      clicked: 0,
      loading: false,
      userMove: 1,
      leftToRight: 1,
      rightToLeft: 2,
      deleteDestination: [],
      createdByUser: "",
      updatedByUser: "",
      updatedAt: "",
      createdAt: "",
      editText: "",
      pageLoaded: 0,
      showEmpty: 1
    };
  },
  computed: {
    isComplete() {
      return this.adminsData.ugroup_name;
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
      this.pageRecords(this.pagination.current_page);
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
        .post(adminBaseUrl + "/groups/list?page=" + pageNo, formData)
        .then(response => {
          this.records = response.data.data.usergroups.data;
          this.pagination = response.data.data.usergroups;
          this.loading = false;
          this.showEmpty = response.data.data.showEmpty;
          this.pageLoaded = 1;
        });
    },

    openAddPage: function() {
      this.emptyFormValues();
      this.destination = [];
      this.source = this.sourceOriginal;
      this.$http.post(adminBaseUrl + "/groups/records").then(response => {
        this.source = response.data.data.sourceList;
        this.sourceAll = response.data.data.sourceAll;
        this.destination = [];
        this.destinationAll = 0;
        this.sourceRow = 0;
        this.destinationRow = 0;
        $(".loadDestinationList").hide();
      });
      this.selectedPage = "addform";
      if (this.searchData.search != "") {
        this.searchData.search = "";
        this.pageRecords();
      }
      this.showEmpty = 0;
    },
    openEditPage: function(record) {
      this.emptyFormValues();
      this.emptyUpdatedFieldValue();
      this.adminsData = record;
      this.editText = record.ugroup_name;
      this.$http
        .post(adminBaseUrl + "/groups/records/" + record.ugroup_id)
        .then(response => {
          this.source = response.data.data.sourceList;
          this.sourceAll = response.data.data.sourceAll;
          this.destination = response.data.data.destinationList;
          this.destinationAll = response.data.data.destinationAll;
          this.sourceRow = 0;
          this.destinationRow = 0;
          if (this.sourceAll <= response.data.data.pageLength) {
            $(".loadSourceList").hide();
          }
          if (this.destinationAll <= response.data.data.pageLength) {
            $(".loadDestinationList").hide();
          }

          if (
            response.data.data.userGroups.created_at != null &&
            "admin_username" in response.data.data.userGroups.created_at
          ) {
            this.createdByUser =
              response.data.data.userGroups.created_at.admin_username;
          }
          if (
            response.data.data.userGroups.updated_at != null &&
            "admin_username" in response.data.data.userGroups.updated_at
          ) {
            this.updatedByUser =
              response.data.data.userGroups.updated_at.admin_username;
          }
          this.updatedAt = response.data.data.userGroups.ugroup_updated_at
            ? response.data.data.userGroups.ugroup_updated_at
            : "";
          this.createdAt = response.data.data.userGroups.ugroup_created_at
            ? response.data.data.userGroups.ugroup_created_at
            : "";
        });
      this.selectedPage = "editform";
    },
    sourceList: function() {
      let groupId = $('[name="id"]').val();

      let formData = this.$serializeData({
        search: this.sourceSearch
      });
      this.source = [];
      this.$http
        .post(adminBaseUrl + "/groups/sourcelist/" + groupId, formData)
        .then(response => {
          this.source = response.data.data;
          if (this.source.length >= 10) {
            $(".loadSourceList").show();
          } else {
            $(".loadSourceList").hide();
          }
        });
    },
    destinationList: function() {
      let groupId = $('[name="id"]').val();
      let formData = this.$serializeData({
        search: this.destinationSearch
      });
      this.destination = [];
      this.$http
        .post(adminBaseUrl + "/groups/destinationlist/" + groupId, formData)
        .then(response => {
          this.destination = response.data.data;
          if (this.destination.length >= 10) {
            $(".loadDestinationList").show();
          } else {
            $(".loadDestinationList").hide();
          }
        });
    },
    emptyFormValues: function() {
      this.adminsData = {
        ugroup_id: "",
        ugroup_name: ""
      };
      this.errors.clear();
    },
    validateRecord: function() {
      this.$validator.validateAll().then(res => {
        if (res) {
          this.clicked = 1;
          let input = this.adminsData;
          if (input.ugroup_id != "") {
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
      let formData = this.$serializeData({
        ugroup_name: input.ugroup_name
      });
      formData.append("_method", "put");
      this.$http
        .post(adminBaseUrl + "/groups/" + input.ugroup_id, formData)
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
            this.clicked = 0;
            this.cancel();
          },
          response => {
            //error
            this.clicked = 0;
            this.validateErrors(response);
          }
        );
    },
    confirmDelete: function(event, dataid) {
      event.stopPropagation();
      this.recordId = dataid;
      this.$bvModal.show(this.modelId);
    },
    deleteRecord: function(recordId) {
      this.$http.delete(adminBaseUrl + "/groups/" + recordId).then(response => {
        if (response.data.status == false) {
          toastr.error(response.data.message, "", toastr.options);
          return;
        }
        toastr.success(response.data.message, "", toastr.options);
        this.pageRecords(this.pagination.current_page);
        this.$bvModal.hide(this.modelId);
      });
    },
    discardGroup: function(recordId) {
      this.$http.delete(adminBaseUrl + "/groups/" + recordId).then(response => {
        this.pageRecords(this.pagination.current_page);
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
      let formData = this.$serializeData(input);
      formData.append("destination", JSON.stringify(this.destination));
      this.$http.post(adminBaseUrl + "/groups", formData).then(
        response => {
          if (response.data.status == false) {
            this.clicked = 0;
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", {
            timeOut: 5000
          });
          this.pageRecords(this.pagination.current_page);
          this.selectedPage = "";
          this.clicked = 0;
          this.emptyFormValues();
        },
        response => {
          this.clicked = 0;
          this.validateErrors(response);
        }
      );
    },

    cancel: function() {
      this.selectedPage = false;
      if (this.records.length == 0) {
        this.showEmpty = 1;
      }
    },
    validateGroup: function() {
      let ugroup_id = $('[name="id"]').val();
      let ugroup_name = $('[name="ugroup_name"]').val();
      if (
        (typeof ugroup_id == "undefined" && ugroup_name == "") ||
        ugroup_name == ""
      ) {
        toastr.error(this.$t("NOTI_PLEASE_ENTER_GROUP_NAME"), "", toastr.options);
        return false;
      }
      return true;
    },
    updateMembers: function(checkMoveAll = 0, checkSource = 0) {
      let formData = this.$serializeData({});
      let ugroup_id = $('[name="id"]').val();
      let ugroup_name = $('[name="ugroup_name"]').val();
      formData.append("ugroup_id", ugroup_id);
      formData.append("ugroup_name", ugroup_name);
      formData.append("destination", JSON.stringify(this.destination));
      formData.append("deleted", JSON.stringify(this.deleteDestination));
      this.$http.post(adminBaseUrl + "/groups/update-members", formData).then(
        response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          this.source = response.data.data.sourceList;
          this.sourceAll = response.data.data.sourceAll;
          this.destination = response.data.data.destinationList;
          this.destinationAll = response.data.data.destinationAll;
          this.adminsData.ugroup_id = response.data.data.id;
          this.sourceRow = 0;
          this.destinationRow = 0;
          this.selectedPage = "editform";
          if (checkMoveAll == 1 && checkSource == 1) {
            $(".loadSourceList").hide();
            $(".loadDestinationList").show();
          } else if (checkMoveAll == 1 && checkSource == 2) {
            $(".loadSourceList").show();
            $(".loadDestinationList").hide();
          } else {
            $(".loadSourceList").show();
            $(".loadDestinationList").show();
          }

          if (this.sourceAll <= response.data.data.pageLength) {
            $(".loadSourceList").hide();
          }
          if (this.destinationRow <= response.data.data.pageLength) {
            $(".loadDestinationList").hide();
          }
          this.pageRecords(this.pagination.current_page);
        },
        response => {
          this.validateErrors(response);
        }
      );
    },
    openSendEmail: function(record) {
      if (record.members.length == 0) {
        toastr.error(
          this.$t("NOTI_GROUP_REQUIRE_ATLEAST_1MEMBER"),
          "",
          toastr.options
        );
        return;
      }
      this.recordid = record.ugroup_id;
      this.emailData = [];
      this.$bvModal.show("modal_sendmail");
    },
    moveAllUserLeftToRight: function() {
      let groupId = $('[name="id"]').val();
      let formData = this.$serializeData({
        search: this.sourceSearch
      });
      this.$http
        .post(
          adminBaseUrl + "/groups/moveAll-user-todestination/" + groupId,
          formData
        )
        .then(response => {
          response.data.data.forEach((value, index) => {
            this.source.splice(value[index], 1);
            this.destination.push({
              user_id: value.user_id,
              user_name: value.user_name
            });
          });
          this.updateMembers(this.userMove, this.leftToRight);
        });
    },
    moveAllUserRightToLeft: function() {
      let groupId = $('[name="id"]').val();
      let formData = this.$serializeData({
        search: this.sourceSearch
      });
      this.$http
        .post(
          adminBaseUrl + "/groups/moveAll-user-tosource/" + groupId,
          formData
        )
        .then(response => {
          if (response.data.status == true) {
            this.destination = [];
            this.updateMembers(this.userMove, this.rightToLeft);
          }
        });
    },
    sendEmail: function(recordId) {
      this.$validator.validateAll().then(res => {
        if (res) {
          this.disabledBtn = true;
          $(".sendMailBtn").addClass(
            "spinner spinner--right spinner--md spinner--light"
          );
          let formData = this.$serializeData(this.emailData);
          this.$http
            .post(adminBaseUrl + "/groups/sendmail/" + recordId, formData)
            .then(response => {
              $(".sendMailBtn").removeClass(
                "spinner spinner--right spinner--md spinner--light"
              );
              this.disabledBtn = false;
              if (response.data.status == false) {
                toastr.error(response.data.message, "", toastr.options);
                return;
              }
              this.$bvModal.hide("modal_sendmail");
              toastr.success(response.data.message, "", {
                timeOut: 5000
              });
            });
        }
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
    let thisVal = this;

    $(document).on("click", ".list-box li.list-item", function(e) {
      if ($(this).hasClass("active")) {
        $(this).removeClass("active");
      } else {
        $(this).addClass("active");
      }
    });

    $(document).on("click", "a.loadSourceList", function() {
      let row = Number(thisVal.sourceRow);

      let allcount = Number(thisVal.sourceAll);

      let rowperpage = 10;
      row = row + rowperpage;
      let groupId = $('[name="id"]').val();
      if (row <= allcount) {
        thisVal.sourceRow = row;
        $.ajax({
          url: adminBaseUrl + "/groups/sourcelist/" + groupId,
          type: "post",
          data: {
            row: row,
            search: this.sourceSearch,
            _token: $("#token").attr("value")
          },
          beforeSend: function() {
            $(".loadSourceList").text("Loading...");
          },
          success: function(response) {
            for (var i = 0; i < response.data.length; i++) {
              thisVal.source.push({
                user_id: response.data[i].user_id,
                user_name: response.data[i].user_name
              });
            }
            let rowno = row + rowperpage;
            if (response.data.length == 0) {
              $(".loadSourceList").hide();
            } else if (rowno > allcount) {
              $(".loadSourceList").hide();
            } else {
              $(".loadSourceList").text("click to load more");
            }
          }
        });
      } else {
        $(".loadSourceList").hide();
      }
    });

    $(document).on("click", "a.loadDestinationList", function() {
      let row = Number(thisVal.destinationRow);
      let allcount = Number(thisVal.destinationAll);
      let rowperpage = 10;
      row = row + rowperpage;
      let groupId = $('[name="id"]').val();
      if (row <= allcount) {
        thisVal.destinationRow = row;
        $.ajax({
          url: adminBaseUrl + "/groups/destinationlist/" + groupId,
          type: "post",
          data: {
            row: row,
            search: this.destinationSearch,
            _token: $("#token").attr("value")
          },
          beforeSend: function() {
            $(".loadDestinationList").text("Loading...");
          },
          success: function(response) {
            for (var i = 0; i < response.data.length; i++) {
              thisVal.destination.push({
                user_id: response.data[i].user_id,
                user_name: response.data[i].user_name
              });
            }
            let rowno = row + rowperpage;
            if (rowno > allcount) {
              $(".loadDestinationList").hide();
            } else {
              $(".loadDestinationList").text("click to load more");
            }
          }
        });
      } else {
        $(".loadDestinationList").hide();
      }
    });

    $(document).on("click", ".leftToRight", function(e) {
      //move selected to right
      if (!thisVal.validateGroup()) {
        return;
      }
      let elementsToDelete = [];
      $(".sourceList li").each(function(index, value) {
        if (!$(this).hasClass("active")) {
          return;
        }
        let name = $(this).text();
        let id = $(this).attr("data-id");
        elementsToDelete.push(index);
        thisVal.destination.push({
          user_id: id,
          user_name: name
        });
      });
      for (var i = elementsToDelete.length - 1; i >= 0; i--) {
        thisVal.source.splice(elementsToDelete[i], 1);
      }
      thisVal.updateMembers();
    });

    $(document).on("click", ".allleftToRight", function(e) {
      //move all to right
      if (!thisVal.validateGroup()) {
        return;
      }
      thisVal.moveAllUserLeftToRight();
    });

    $(document).on("click", ".rightToLeft", function(e) {
      //move selected to left
      if (!thisVal.validateGroup()) {
        return;
      }
      let elementsToDelete = [];
      $(".destinationList li").each(function(index, value) {
        if (!$(this).hasClass("active")) {
          return;
        }
        let id = $(this).attr("data-id");
        elementsToDelete.push(index);
        thisVal.deleteDestination.push(id);
      });
      for (var i = elementsToDelete.length - 1; i >= 0; i--) {
        thisVal.destination.splice(elementsToDelete[i], 1);
      }
      thisVal.updateMembers();
    });

    $(document).on("click", ".allrightToLeft", function(e) {
      //move all to left
      if (!thisVal.validateGroup()) {
        return;
      }
      thisVal.moveAllUserRightToLeft();
    });

    $(document).on("click", ".selectAllSource", function(e) {
      //select all in source
      $(this)
        .closest("div.list-box-item")
        .find("ul li")
        .addClass("active");
    });

    $(document).on("click", ".deselectAllSource", function(e) {
      //deselect all in source
      $(this)
        .closest("div.list-box-item")
        .find("ul li")
        .removeClass("active");
    });

    $(document).on("click", ".selectAllDestination", function(e) {
      //select all in destination
      $(this)
        .closest("div.list-box-item")
        .find("ul li")
        .addClass("active");
    });

    $(document).on("click", ".deselectAllDestination", function(e) {
      //deselect all in destination
      $(this)
        .closest("div.list-box-item")
        .find("ul li")
        .removeClass("active");
    });
  }
};
</script>
<style scoped>
*,
:after,
:before {
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
}
.list-box-wrapper {
  font-family: sans-serif;
  width: 100%;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}
.list-box-wrapper > div {
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
}
.list-box-wrapper .list-box-item {
  border: 1px solid #ccc;
  border-radius: 3px;
}
.list-box-wrapper .list-box-item .search-box {
  border-bottom: 1px solid #ccc;
  position: relative;
}
.list-box-wrapper .list-box-item .search-box input {
  border: none;
  width: 100%;
  padding: 0.5rem 1rem;
}
.list-box-wrapper .list-box-item .search-box .clear-search {
  position: absolute;
  padding: 0.5rem;
  right: 0;
  top: 0;
  cursor: pointer;
  font-weight: 700;
  color: #e74c3c;
}
.list-box-wrapper .list-box-item .list-box {
  height: 250px;
  list-style: none;
  padding: 0;
  margin: 0;
}
.list-box-wrapper .list-box-item .list-box .list-item {
  padding: 0.5rem 1rem;
  border-bottom: 1px solid #ccc;
  cursor: pointer;
}
.list-box-wrapper .list-box-item .list-box .list-item.active {
  background-color: #eee;
}
.list-box-wrapper .list-box-item .bulk-action {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  border-top: 1px solid #ccc;
  text-align: center;
  cursor: pointer;
}
.list-box-wrapper .list-box-item .bulk-action .select-all {
  width: 100%;
  padding: 0.8rem;
  background-color: #007bff;
  color: #fff;
}
.list-box-wrapper .list-box-item .bulk-action .deselect-all {
  width: 100%;
  padding: 0.8rem;
  background-color: #6c757d;
  color: #fff;
}
.list-box-wrapper .actions {
  -webkit-box-flex: 0;
  -ms-flex-positive: 0;
  flex-grow: 0;
  padding: 0 1rem;
}
.list-box-wrapper .actions .btn-action {
  margin-bottom: 0.5rem;
}
.btn-action {
  display: inline-block;
  font-weight: 400;
  color: #212529;
  text-align: center;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  background-color: transparent;
  border: 1px solid transparent;
  padding: 0.375rem 0.75rem;
  font-size: 1rem;
  line-height: 1.5;
  border-radius: 0.25rem;
  -webkit-transition: color 0.15s ease-in-out,
    background-color 0.15s ease-in-out, border-color 0.15s ease-in-out,
    -webkit-box-shadow 0.15s ease-in-out;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
    border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
    border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
    border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out,
    -webkit-box-shadow 0.15s ease-in-out;
  display: block;
  width: 100%;
  color: #fff;
  background-color: #007bff;
  border-color: #007bff;
  cursor: pointer;
}
.btn-action,
.btn-action svg {
  vertical-align: middle;
}
</style>