<template>
  <div class="body bg-dashboard" id="body" data-yk>
    <section class="section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-10">
            <div class="account-area">
              <dasboard-header></dasboard-header>
              <left-sidebar :pageType="'reviews'"></left-sidebar>
              <main class="main-content" data-yk role="yk-main-content">
                <div class="card">
                  <div class="card-head" v-if="allProductsCount > 0">
                    <h2 v-if="$mq === 'mobile'">{{ $t('LBL_REVIEWS') }}</h2>
                    <ul class="filters-ghost" v-if="$mq !== 'mobile'">
                      <li
                        v-for="(filter, fKey) in filters"
                        :key="fKey"
                        v-bind:class="[fKey == selectedFilter ? 'active' : '']"
                      >
                        <a href="javascript:;" @click="getListing(fKey)">
                          {{
                          filter
                          }}
                        </a>
                      </li>
                    </ul>
                    <div class="dropdown" v-else>
                      <a
                        class="account-filters-trigger"
                        data-toggle="dropdown"
                        href="javascript:;"
                        aria-expanded="false"
                      >
                        <i class="icn">
                          <svg class="svg">
                            <use
                              :xlink:href="
                                baseUrl +
                                  '/dashboard/media/retina/sprite.svg#filters'
                              "
                            />
                          </svg>
                        </i>
                        <span class="YK-filterBy" data-value="showall">{{ filters[selectedFilter] }}</span>
                      </a>

                      <div
                        class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim"
                        x-placement="bottom-start"
                      >
                        <ul class="nav nav--block YK-filterReviews">
                          <li
                            class="nav__item"
                            v-for="(filter, fKey) in filters"
                            :key="fKey"
                            v-if="fKey != selectedFilter"
                          >
                            <a
                              class="nav__link"
                              data-value="reviewed"
                              href="javascript:;"
                              @click="getListing(fKey)"
                            >
                              <span class="nav__link-text">
                                {{
                                filter
                                }}
                              </span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div
                    class="card-body p-0"
                    v-bind:class="[
                      products.length == 0 ? 'no-data-found-wrap' : '',
                    ]"
                  >
                    <ul
                      class="list-group list-reviews"
                      v-if="
                        (products.length != 0 && $mq === 'mobile') ||
                          $mq === 'tablet'
                      "
                    >
                      <li
                        class="list-group-item my-review"
                        v-for="(product, productKey) in products"
                        :key="productKey"
                      >
                        <div class="product-profile">
                          <div class="product-profile__thumbnail">
                            <a :href="$generateUrl(product.url_rewrite)">
                              <img
                                data-yk
                                class="img-fluid"
                                data-ratio="3:4"
                                :src="
                                  $productImage(
                                    product.op_product_id,
                                    product.op_pov_code, product.images ? product.images.afile_updated_at : '', '42-42'
                                  )
                                "
                                alt="..."
                              />
                            </a>
                          </div>
                          <div class="product-profile__data">
                            <div class="title">
                              <a
                                class
                                :href="$generateUrl(product.url_rewrite)"
                              >{{ product.op_product_name }}</a>
                            </div>
                            <div class="options text-capitalize">
                              <p v-if="product.op_product_variants">
                                <span
                                  v-if="
                                    JSON.parse(product.op_product_variants)
                                      .styles
                                  "
                                >
                                  {{
                                  Object.values(
                                  JSON.parse(product.op_product_variants)
                                  .styles
                                  ).join(" | ")
                                  }}
                                </span>
                              </p>
                            </div>
                            <div class="product-price">{{ $priceFormat(product.op_product_price) }}</div>
                            <div class="order-detail">
                              <p class="order-num">
                                <inertia-link
                                  class="link-text"
                                  :href="
                                    baseUrl +
                                      '/user/orders/' +
                                      product.op_order_id
                                  "
                                >#{{ product.op_order_id }}</inertia-link>

                                <span>{{ $dateTimeFormat(product.order_date_added, 'date') }}</span>
                              </p>
                            </div>
                            <span v-if="product.product_review">
                              <div class="rating-holder mt-1">
                                <rating-stars
                                  :rating="[
                                    product.product_review.prl_preview_id
                                      ? product.product_review
                                          .prl_preview_rating
                                      : product.product_review.preview_rating,
                                  ]"
                                ></rating-stars>
                              </div>
                              <span v-if="$configVal('DISPLAY_REVIEW_STATUS')">
                                <div
                                  class="status pending mt-1"
                                  v-if="product.product_review.prl_preview_id"
                                >
                                  <svg class="svg">
                                    <use
                                      :xlink:href="
                                      baseUrl +
                                        '/dashboard/media/retina/sprite.svg#pending-status'
                                    "
                                    />
                                  </svg>
                                  {{ $t("LBL_PENDING") }}
                                </div>
                                <span v-else>
                                  <div
                                    class="status pending mt-1"
                                    v-if="
                                      product.product_review.preview_status == 1
                                    "
                                  >
                                    <svg class="svg">
                                      <use
                                        :xlink:href="
                                        baseUrl +
                                          '/dashboard/media/retina/sprite.svg#pending-status'
                                      "
                                      />
                                    </svg>
                                    {{ $t("LBL_PENDING") }}
                                  </div>
                                  <div
                                    class="status approved mt-1"
                                    v-else-if="
                                      product.product_review.preview_status == 2
                                    "
                                  >
                                    <svg class="svg">
                                      <use
                                        :xlink:href="
                                        baseUrl +
                                          '/dashboard/media/retina/sprite.svg#approved-status'
                                      "
                                      />
                                    </svg>
                                    {{ $t("LBL_APPROVED") }}
                                  </div>
                                  <div
                                    class="status rejected mt-1"
                                    v-else-if="
                                      product.product_review.preview_status == 3
                                    "
                                  >
                                    <svg class="svg">
                                      <use
                                        :xlink:href="
                                        baseUrl +
                                          '/dashboard/media/retina/sprite.svg#rejected-status'
                                      "
                                      />
                                    </svg>
                                    {{ $t("LBL_REJECTED") }}
                                  </div>
                                </span>
                              </span>
                            </span>
                          </div>
                        </div>
                        <div class="actions">
                          <div class="action_links">
                            <a
                              class="link-text"
                              @click="
                                  $addToCart(
                                    product.op_product_id,
                                    product.op_pov_code
                                  )
                                "
                            >{{ $t("BTN_BUY_AGAIN") }}</a>
                          </div>
                          <div class="action_links">
                            <a
                              class="link-text"
                              href="javascript:;"
                              v-if="reviewActionType(product.product_review).show"
                              @click="postReview(productKey, product, reviewActionType(product.product_review).type, reviewActionType(product.product_review).preview)"
                            >{{reviewActionType(product.product_review).label}}</a>
                          </div>
                        </div>
                      </li>
                    </ul>
                    <ul class="list-group list-reviews" v-else>
                      <li
                        class="list-group-item my-review"
                        v-for="(product, productKey) in products"
                        :key="productKey"
                      >
                        <div class="product-profile">
                          <div class="product-profile__thumbnail">
                            <a :href="$generateUrl(product.url_rewrite)">
                              <img
                                data-yk
                                class="img-fluid"
                                data-ratio="3:4"
                                :src="
                                  $productImage(
                                    product.op_product_id,
                                    product.op_pov_code, product.images ? product.images.afile_updated_at : '', '42-42'
                                  )
                                "
                                alt="..."
                              />
                            </a>
                          </div>
                          <div class="product-profile__data">
                            <div class="title">
                              <a
                                class
                                :href="$generateUrl(product.url_rewrite)"
                              >{{ product.op_product_name }}</a>
                            </div>
                            <div class="options">
                              <p v-if="product.op_product_variants">
                                <span
                                  v-if="
                                    JSON.parse(product.op_product_variants)
                                      .styles
                                  "
                                >
                                  {{
                                  Object.values(
                                  JSON.parse(product.op_product_variants)
                                  .styles
                                  ).join(" | ")
                                  }}
                                </span>
                              </p>
                            </div>
                            <div class="rating-holder mt-1">
                              <rating-stars
                                :rating="[
                                  product.product_review ?
                                    (
                                      product.product_review.prl_preview_id
                                      ? product.product_review.prl_preview_rating
                                      : (product.product_review.preview_rating ? product.product_review.preview_rating : '0')
                                    ) : '0',
                                ]"
                              ></rating-stars>
                            </div>
                          </div>
                        </div>
                        <div class="order-detail">
                          <div class="actions">
                            <a
                              class="link-text"
                              @click="
                                  $addToCart(
                                    product.op_product_id,
                                    product.op_pov_code
                                  )
                                "
                            >{{ $t("BTN_BUY_AGAIN") }}</a>

                            <button
                              class="link-text"
                              :disabled="(product.cancel_request === null || (product.cancel_request != '' && product.cancel_request.orrequest_status == 0)) && ( product.order_shipping_status == 1 || product.order_shipping_status == 2)"
                              v-if="reviewActionType(product.product_review).show"
                              @click="postReview(productKey, product, reviewActionType(product.product_review).type, reviewActionType(product.product_review).preview)"
                            >{{reviewActionType(product.product_review).label}}</button>
                          </div>
                          <p class="order-num">
                            <!-- {{ $t("LBL_ORDER") }} -->
                            <inertia-link
                              class="link-text"
                              :href="
                                baseUrl + '/user/orders/' + product.op_order_id
                              "
                            >#{{ product.op_order_id }}</inertia-link>
                            <span>
                              {{
                              product.order_date_added > 1
                              ? $t("LBL_PART_OF")
                              : ""
                              }}
                              {{ $dateTimeFormat(product.order_date_added, 'date') }}
                            </span>
                          </p>
                        </div>
                        <div class="product-price">
                          {{ $priceFormat(product.op_product_price) }}
                          <div
                            class="status mt-1"
                            v-bind:class="$cleanString(reviewStatus(product.product_review).label)"
                            v-if="product.product_review && $configVal('DISPLAY_REVIEW_STATUS') == '1'"
                          >
                            <svg class="svg">
                              <use
                                :xlink:href="
                                baseUrl +
                                  '/dashboard/media/retina/sprite.svg#' +
                                  reviewStatus(product.product_review).img
                              "
                              />
                            </svg>
                            {{reviewStatus(product.product_review).label}}
                          </div>
                        </div>
                      </li>
                    </ul>
                    <infinite-loading :identifier="infiniteId" @infinite="infiniteHandler"></infinite-loading>
                    <div
                      class="no-data-found no-data-found--sm"
                      v-if="allProductsCount == 0 && pageLoad == true"
                    >
                      <div class="img">
                        <img
                          data-yk
                          :src="
                            baseUrl + '/dashboard/media/retina/no-reviews.svg'
                          "
                          alt
                        />
                      </div>
                      <div class="data">
                        <h2>{{ $t("LBL_PURCHASE_TO_GIVE_REVIEW") }}</h2>
                        <a
                          :href="shopUrl"
                          class="btn btn-outline-brand btn-wide"
                        >{{ $t("BTN_START_SHOPPING") }}</a>
                      </div>
                    </div>

                    <div
                      class="no-data-found no-data-found--sm"
                      v-else-if="products.length == 0 && pageLoad == true"
                    >
                      <div class="img">
                        <img
                          data-yk
                          :src="
                            baseUrl + '/dashboard/media/retina/no-reviews.svg'
                          "
                          alt
                        />
                      </div>
                      <div class="data">{{ $t("LBL_NO_RESULTS_FOUND") }}</div>
                    </div>
                  </div>
                </div>
              </main>
            </div>
          </div>
        </div>
      </div>
    </section>
    <review-form :product="reviewProduct" :reviewType="reviewType" @updateListing="updateListing"></review-form>
  </div>
</template>
<script>
import leftSidebar from "@/buyerDashboard/Sidebar";
import dasboardHeader from "@/buyerDashboard/Header";
import ratingStars from "@/common/ratingStars";
import reviewForm from "@/common/popups/reviewForm";

export default {
  props: ["shopUrl"],
  components: {
    leftSidebar: leftSidebar,
    dasboardHeader: dasboardHeader,
    ratingStars: ratingStars,
    reviewForm: reviewForm
  },
  data: function() {
    return {
      baseUrl: baseUrl,
      allProductsCount: 0,
      products: [],
      reviewProduct: {},
      reviewType: {},
      pageLoad: false,
      filters: {
        showAll: this.$t("LNK_FILTER_SHOW_ALL"),
        showReviewed: this.$t("LNK_FILTER_REVIEWED"),
        showPending: this.$t("LNK_FILTER_PENDING")
      },
      page: 1,
      infiniteId: +new Date(),
      selectedFilter: "showAll"
    };
  },
  methods: {
    postReview: function(index, product, type, logId = "") {
      this.reviewProduct = product;
      this.reviewType = { type: type, logId: logId, index: index };
      this.$bvModal.show("reviewModel");
    },
    reviewActionType: function(review) {
      if (review && review.prl_preview_id) {
        return {
          label: this.$t("BTN_VIEW"),
          type: "view",
          show: true,
          preview: review.prl_preview_id
        };
      }
      if (review && review.preview_status == 1) {
        return {
          label: this.$t("BTN_VIEW"),
          type: "view",
          show: true,
          preview: ""
        };
      } else if (review && review.preview_status != 1) {
        if (this.$configVal("ENABLE_EDIT_REVIEW") == 0) {
          return {
            label: this.$t("BTN_VIEW"),
            type: "view",
            show: true,
            preview: review.prl_preview_id
          };
        }
        return {
          label: this.$t("BTN_EDIT"),
          type: "edit",
          show: true,
          preview: ""
        };
      } else {
        return {
          label: this.$t("BTN_WRITE_A_REVIEW"),
          type: "new",
          show: true,
          preview: ""
        };
      }
    },
    reviewStatus: function(review) {
      if (review.prl_preview_id) {
        return { img: "pending-status", label: this.$t("LBL_PENDING") };
      }
      if (review.preview_status == 1) {
        return { img: "pending-status", label: this.$t("LBL_PENDING") };
      } else if (review.preview_status == 2) {
        return { img: "approved-status", label: this.$t("LBL_APPROVED") };
      } else if (review.preview_status == 3) {
        return { img: "rejected-status", label: this.$t("LBL_REJECTED") };
      }
    },
    updateListing: function(opId) {
      let formData = this.$serializeData({
        orderProductId: opId
      });
      this.$axios
        .post(baseUrl + "/user/review/listing", formData)
        .then(response => {
          this.products[this.reviewType.index] = response.data.data;
          this.$forceUpdate();
        });
    },
    infiniteHandler($state) {
      let formData = this.$serializeData({
        filters: this.selectedFilter,
        total: this.products.length
      });

      this.$axios
        .post(baseUrl + "/user/reviews/load-records", formData)
        .then(response => {
          if (response.data.data.length) {
            this.page += 1;
            this.products.push(...response.data.data);
            $state.loaded();
          } else {
            $state.complete();
          }
        });
    },
    getListing: function(filterType) {
      this.pageLoad = false;
      this.page = 1;
      this.infiniteId += 1;
      let formData = this.$serializeData({ filters: filterType });
      this.$axios
        .post(baseUrl + "/user/review/listing", formData)
        .then(response => {
          this.pageLoad = true;
          this.selectedFilter = filterType;
          this.products = response.data.data.data;
          if (filterType == "showAll") {
            this.allProductsCount = this.products.length;
          }
        });
    }
  },

  mounted: function() {
    this.getListing(this.selectedFilter);
  }
};
</script>