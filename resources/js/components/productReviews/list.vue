<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{$t('LBL_PRODUCT_REVIEWS')}}</h3>
          <div class="subheader__breadcrumbs">
              <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                  <i class="flaticon2-shelter"></i>
              </router-link>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_PRODUCTS')}}</span>
            <span class="subheader__breadcrumbs-separator"></span>
            <span class="subheader__breadcrumbs-link">{{$t('LBL_REVIEWS')}}</span>
          </div>
        </div>
        <div class="subheader__toolbar">
          <div class="subheader__wrapper" v-if="showEmpty == 0">
            <h5 class="m-0">{{$t('LBL_STORE_RATING')}}</h5>
            <div class="rating-holder ml-3">
              <ratingStars :rating="rating"></ratingStars>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="portlet portlet--height-fluid">
            <div class="portlet__head" v-if="productName != ''">
              <div class="portlet__head-label">
                <h3
                  class="portlet__head-title"
                >{{ productName }}</h3>
              </div>
              <div v-if="this.$route.params.id" class="portlet__head-toolbar">
                <router-link
                  :to="{name: 'products', query:{page: $route.query.page}}"
                  class="btn btn-outline-brand"
                >
                  <i class="la la-arrow-left"></i>
                  <span class="hidden-mobile">{{$t('BTN_BACK')}}</span>
                </router-link>
              </div>
            </div>

            <div class="portlet__body pb-0 bg-gray flex-grow-0">
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <div class="input-icon input-icon--left">
                      <input
                        type="text"
                        class="form-control"
                        :placeholder="$t('PLH_SEARCH_REVIEW')"
                        id="generalSearch"
                        :readonly="records.length == 0 && search === ''"
                        v-model="search"
                        @keyup="searchRecords"
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
              <div class="section">
                <div class="section__content">
                  <table class="table table-data table-justified">
                    <thead class="datatable__head">
                      <tr class="datatable__row">
                        <th>{{ '#' }}</th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('username')">
                            <span>{{ $t('LBL_CUSTOMER') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'username'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('preview_order_id')">
                            <span>{{ $t('LBL_ORDER') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'preview_order_id'"></i>
                            </span>
                        </th>
                        <th>{{ $t('LBL_RATING') }}</th>
                        <th width="15%" class="datatable__cell datatable__cell--sort" @click="sortBy('preview_title')">
                            <span>{{ $t('LBL_PRODUCT_REVIEW_TITLE') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'preview_title'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('preview_created_at')">
                            <span>{{ $t('LBL_POSTED_ON') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'preview_created_at'"></i>
                            </span>
                        </th>
                        <th>{{ $t('LBL_PUBLISHED_ON') }}</th>
                        <th>{{ $t('LBL_PUBLISH') }}</th>
                        <th>{{ $t('LBL_REVIEW_STATUS') }}</th>
                        <th width="15%"></th>
                      </tr>
                    </thead>
                    <tbody
                      v-if="loading==false && records.length == 0 && search == '' && showEmpty == 1 && pageLoaded==1"
                    >
                      <tr>
                        <td colspan="10">
                          <div class="no-data-found">
                            <div class="img">
                              <img
                                :src="activeThemeUrl+'/media/retina/empty/no-reviews.svg'"
                                width="125px"
                                height="125px"
                              />
                            </div>
                            <div class="data">
                              <h3>{{ $t('LBL_NO_REVIEWS_TO_SHOW') }}</h3>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                    <tbody
                      v-else-if="loading==false && records.length == 0 && search != '' && showEmpty == 0 && pageLoaded==1"
                    >
                      <tr>
                        <td colspan="10">
                          <noRecordsFound></noRecordsFound>
                        </td>
                      </tr>
                    </tbody>
                    <tbody v-else>
                      <tr
                        v-for="(record, index) in records"
                        :key="record.preview_id"
                        :data-id="record.preview_id"
                      >
                        <td scope="row">{{ pagination.from+index }}</td>
                        <td>
                          <router-link
                            :to="{name: 'userDetails', params: {id: record.preview_user_id}}"
                            target="_blank"
                          >{{record.username}}</router-link>
                        </td>
                        <td>
                          <router-link
                            :to="{name: 'orderDetail', params: {id: record.preview_order_id}}"
                            target="_blank"
                          >#{{record.preview_order_id}}</router-link>
                        </td>
                        <td>
                          <div class="rating-holder">                           
                            <ratingStars v-if="record.product_review_log != null" :rating="record.product_review_log.prl_preview_rating"></ratingStars>
                            <ratingStars v-else :rating="record.preview_rating"></ratingStars>                          
                          </div>
                        </td>
                        <td>
                          <span
                            v-if="record.product_review_log != null"
                          >{{ record.product_review_log.prl_preview_title }}</span>
                          <span v-else>{{ record.preview_title }}</span>
                        </td>
                        <td>
                          <span
                            v-if="record.product_review_log != null"
                          >{{ record.product_review_log.prl_preview_created_at | formatDate }}</span>
                          <span v-else>{{ record.preview_created_at | formatDate }}</span>
                        </td>
                        <td><span v-if="record.preview_approved_at != null">{{ record.preview_approved_at | formatDate }}</span></td>
                        <td>
                          <label
                            class="switch switch--sm"
                            v-b-tooltip.hover
                            :title="record.preview_publish == 1 ? $t('TTL_UNPUBLISH') : $t('TTL_PUBLISH') "
                          >
                            <input
                              type="checkbox"
                              name="preview_publish"
                              v-model="record.preview_publish"
                              @change="onSwitchStatus($event, record.preview_id )"
                              :disabled="!$canWrite('PRODUCT_REVIEWS')"
                            />
                            <span></span>
                          </label>
                        </td>
                        <td v-if="record.product_review_log != null">
                          <span
                            class="badge badge--warning badge--inline "
                            v-if="record.product_review_log.prl_preview_status==1"
                          >{{ $t('LBL_REVIEW_PENDING') }}</span>
                        </td>
                        <td v-else>
                          <span
                            class="badge badge--warning badge--inline"
                            v-if="record.preview_status==1"
                          >{{ $t('LBL_REVIEW_PENDING') }}</span>
                          <span
                            class="badge badge--success badge--inline"
                            v-if="record.preview_status==2"
                          >{{ $t('LBL_REVIEW_APPROVED') }}</span>
                          <span
                            class="badge badge--danger badge--inline"
                            v-if="record.preview_status==3"
                          >{{ $t('LBL_REVIEW_REJECTED') }}</span>
                        </td>
                        <td>
                          <div class="actions">
                            <div class="btn btn-light btn-sm btn-icon">
                                <span class="rate-item" :data-count="record.helped_count" v-b-tooltip.hover
                                  :title="$t('LBL_REVIEW_USERS_LIKE')">
                                <svg>   
                                      <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#thumb-up'" ></use>                               
                                  </svg>
                                  {{ record.helped_count }}
                                </span>
                                <span class="rate-item" :data-count="record.nothelped_count" v-b-tooltip.hover
                                  :title="$t('LBL_REVIEW_USERS_DISLIKE')">
                                <svg>   
                                      <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#thumb-down'"></use>                               
                                  </svg>
                                  {{ record.nothelped_count }}
                                </span>
                              </div>
                              <router-link
                                :to="{name: 'productReviewsDetail', params: {id: record.preview_id}}"
                                v-b-tooltip.hover
                                :title="$t('LNK_VIEW_REVIEW')"
                                class="btn btn-light btn-sm btn-icon"
                              >
                              <svg>   
                                        <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#view'" ></use>                               
                                    </svg>
                              </router-link>
                              <a
                                v-if="record.preview_status == 2"
                                v-b-tooltip.hover
                                :title="$t('LNK_REVIEW_PREVIEW')"
                                target="_blank"
                                :href="baseUrl+'/product/'+record.preview_prod_id+'?review='+record.preview_id+'#reviews'"
                                class="btn btn-light btn-sm btn-icon"
                              >
                                <svg>   
                                    <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#links'" ></use>                               
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
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style scoped>
.form-control[readonly] {
  background-color: #f9f9fc;
}
</style>
<script>
export default {
  data: function() {
    return {
      baseUrl: baseUrl,
      activeThemeUrl: activeThemeUrl,
      records: [],
      pagination: [],
      searchData: {},
      search: "",
      productName: "",
      noOfReviews: "",
      rating: "",
      ratingPercent: "",
      loading: false,
      pageLoaded: 0,
      showEmpty: 1,
      currentSort:'',
      sortingType:''
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
    pageRecords: function(pageNo, pageLoad = false) {
      this.loading = pageLoad;
      Object.assign(this.searchData, {
        search: this.search
      });
        let formData = this.$serializeData(this.searchData);
        formData.append("sorting-by", this.currentSort);
        formData.append("sorting-type", this.sortingType);
        if (typeof this.pagination.per_page != "undefined") {
            formData.append("per_page", this.pagination.per_page);
        }
      let prod_id = 0;
      if (
        typeof this.$route.params.id != "undefined" &&
        this.$route.params.id != ""
      ) {
        prod_id = this.$route.params.id;
      }
      this.$http
        .post(
          adminBaseUrl + "/product-reviews/list/" + prod_id + "?page=" + pageNo,
          formData
        )
        .then(response => {
          this.records = response.data.data.reviews.data;
          this.pagination = response.data.data.reviews;
          this.productName =
            typeof response.data.data.product != "undefined"
              ? response.data.data.product.prod_name
              : "";
          this.showEmpty = response.data.data.showEmpty;
          this.noOfReviews = response.data.data.summary.noOfReviews;
          this.rating = Math.round(response.data.data.summary.rating);
          this.ratingPercent = response.data.data.summary.ratingPercent;
          this.loading = false;
          this.pageLoaded = 1;
        });
    },
    sortBy: function(field) {
        if (this.sortingType == "" || this.sortingType == "asc" || this.currentSort != field) {
            this.sortingType = "desc";
        } else {
            this.sortingType = "asc";
        }
        this.currentSort = field;
        this.pageRecords(this.pagination.current_page);
    },
    searchRecords: function() {
      this.pageRecords(this.pagination.current_page);
    },
    onSwitchStatus: function(event, id) {
      let formData = this.$serializeData({
        status: event.target.checked
      });
      this.$http
        .post(adminBaseUrl + "/product-reviews/status/" + id, formData)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          toastr.success(response.data.message, "", toastr.options);
        });
    },
    currentPage: function(page) {
      this.pageRecords(page);
    }
  },
  mounted: function() {
      this.search = '';
    this.refreshedSearchData();
  }
};
</script>