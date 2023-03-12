<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_BLOG_ARTICLES') }}</h3>
          <div class="subheader__breadcrumbs">
              <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_CMS')}}</span>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_BLOGS')}}</span>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_ARTICLES')}}</span>
          </div>
        </div>
        <div class="subheader__toolbar">
          <router-link
            :to="{name: 'createBlog'}"
            class="btn btn-white"
            v-bind:class="[(!$canWrite('BLOGS')) ? 'disabledbutton no-drop': '']"
          >
            <i class="fas fa-plus"></i>
            {{ $t('BTN_ADD')}}
          </router-link>
        </div>
      </div>
    </div>
    <div class="container">
      <div class id="manage-admins">
        <div class="row">
          <div class="col-xl-12">
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
              <!--begin::Section-->
              <div class="portlet__body portlet__body--fit" v-if="showEmpty == 0 && pageLoaded==1">
                <div class="section">
                  <div class="section__content">
                    <table class="table table-data table-justified">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>{{ $t('LBL_ARTICLE_TITLE') }}</th>
                          <th>{{ $t('LBL_ARTICLE_AUTHOR') }}</th>
                          <th>{{ $t('LBL_TOTAL_VIEWS') }}</th>
                          <th>{{ $t('LBL_PUBLISH') }}</th>
                          <th>{{ $t('LBL_FEATURED') }}</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody
                        v-if="loading==false && records.length == 0 && showEmpty == 0 && pageLoaded==1"
                      >
                        <tr>
                          <td colspan="7">
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
                              @click.prevent="openViewMode(record.post_id)"
                              v-if="!$canWrite('BLOGS')"
                            >{{ record.post_title }}</a>
                            <div v-else>{{ record.post_title }}</div>
                          </td>
                          <td>{{ record.post_author_name }}</td>
                          <td>{{ record.post_view_count }}</td>
                          <td>
                            <label class="switch switch--sm">
                              <input
                                type="checkbox"
                                v-model="record.post_publish"
                                @change="onSwitchStatus($event, record.post_id, 'post_publish')"
                                :disabled="!$canWrite('BLOGS')"
                              />
                              <span v-b-tooltip.hover :title="record.post_publish == 1 ? $t('TTL_UNPUBLISH') : $t('TTL_PUBLISH') "></span>
                            </label>
                          </td>
                          <td>
                            <label class="switch switch--sm">
                              <input
                                type="checkbox"
                                v-model="record.post_featured"
                                @change="onSwitchStatus($event, record.post_id, 'post_featured')"
                                :disabled="!$canWrite('BLOGS')"
                              />
                              <span v-b-tooltip.hover :title="record.post_featured == 1 ? $t('TTL_UNFEATURED') : $t('TTL_FEATURED') "></span>
                            </label>
                          </td>
                          <td>
                            <div class="actions">
                              <router-link
                                :to="{name: 'editBlog', params: {id: record.post_id}}"
                                v-b-tooltip.hover
                                :title="$t('TTL_EDIT')"
                                class="btn btn-light btn-sm btn-icon"
                                v-bind:class="[!$canWrite('BLOGS') ? 'disabled no-drop': '']"
                              >
                                <svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                                                            </svg>
                              </router-link>

                              <a
                                class="btn btn-light btn-sm btn-icon"
                                href="javascipt:;"
                                v-b-tooltip.hover
                                :title="$t('TTL_DELETE')"
                                @click.capture="confirmDelete($event, record.post_id)"
                                v-bind:class="[!$canWrite('BLOGS') ? 'disabled no-drop': '']"
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
                      <router-link :to="{name: 'createBlog'}" class="btn btn-brand btn-small">
                        <i class="fas fa-plus"></i>
                        {{ $t('BTN_ADD')}}
                      </router-link>
                      {{ $t('LBL_BUTTON_TO_CREATE_BLOG') }}
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
              <!--end::Section-->
              <!--end::Form-->
            </div>
            <!--end::Portlet-->
          </div>
        </div>
        <DeleteModel
          :deletePopText="$t('LBL_DELETE_BLOG_CONFIRMATION_TEXT')"
          :recordId="recordId"
          @deleted="deleteRecord(recordId)"
        ></DeleteModel>
      </div>
    </div>
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
      productLink: 0,
      settingId: "blogSettings",
      searchData: searchFields,
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
        .post(adminBaseUrl + "/blogs/list?page=" + pageNo, formData)
        .then(response => {
          this.records = response.data.data.blogs.data;
          this.pagination = response.data.data.blogs;
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
      this.$http.delete(adminBaseUrl + "/blogs/" + recordId).then(response => {
        if (response.data.status == false) {
          toastr.error(response.data.message, "", toastr.options);
          return;
        }
        toastr.success(response.data.message, "", toastr.options);
        this.pageRecords(this.pagination.current_page);
        this.$bvModal.hide(this.modelId);
      });
    },
    onSwitchStatus: function(event, id, type) {
      let formData = this.$serializeData({
        status: event.target.checked,
        type: type
      });
      this.$http
        .post(adminBaseUrl + "/blogs/status/" + id, formData)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            this.currentPage();
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
        });
    },
    openViewMode: function(id) {
      this.$router.push({ name: "editBlog", params: { id: id } });
    }
  },

  mounted() {
    this.search = "";
    this.refreshedSearchData();
  }
};
</script>