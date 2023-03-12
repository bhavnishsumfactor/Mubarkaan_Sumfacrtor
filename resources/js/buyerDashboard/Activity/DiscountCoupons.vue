<template>
  <div class="body bg-dashboard" id="body" data-yk >
    <section class="section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-10">
            <div class="account-area">
              <dasboard-header></dasboard-header>
              <left-sidebar :pageType="'coupons'"></left-sidebar>
              <main class="main-content" data-yk role="yk-main-content">
                <div class="card">
                  <div class="card-head" v-if="coupons.length != 0">
                    <h2 v-if="$mq === 'mobile'">
                      {{ $t("LBL_DISCOUNT_COUPONS") }}
                    </h2>
                  </div>
                  <div
                    class="card-body p-0"
                    v-bind:class="[
                      coupons.length == 0 ? 'no-data-found-wrap' : '',
                    ]"
                  >
                    <div
                      class="total-balance"
                      style="background-image: url(../dashboard/media/retina/savings_graphic.svg)"
                       v-if="pageLoad == true"
                    >
                      <div class="total-balance_inner">
                        <span class="value">
                          {{ $priceFormat(totalDiscount) }}
                        </span>
                        <p class="label">{{ $t("LBL_TOTAL_SAVINGS") }}</p>
                      </div>
                    </div>
                    <ul
                      class="list-group list-coupons YK-list-coupons"
                      v-if="coupons.length != 0"
                    >
                      <li
                        class="list-group-item"
                        v-for="(coupon, cKey) in coupons"
                        :key="cKey"
                      >
                        <div
                          class="coupon"
                          v-bind:class="[
                            pandingCoupons(coupon) == 0
                              ? 'coupon--notused'
                              : '',
                          ]"
                        >
                          <div class="coupon__code-img">
                            <div class="coupon__img">
                              <img
                                data-yk
                                :src="
                                  coupon.attached_file
                                    ? baseUrl +
                                      '/yokart/image/' +
                                      coupon.attached_file.afile_id +
                                      '/140-140?t='+$timestamp(coupon.attached_file.afile_updated_at)
                                    : $noImage('coupon-image.png')
                                "
                                data-aspect-ratio="1:1"
                              />
                            </div>
                            <span
                              class="coupon__tag"
                              v-if="pandingCoupons(coupon) == 0"
                            >
                              {{
                                coupon.orders.length >=
                                coupon.discountcpn_total_uses
                                  ? $t("LBL_EXPIRED")
                                  : $t("LBL_REDEEMED")
                              }}
                            </span>
                            <span class="coupon__tag copyToClipboard" v-else>
                              {{ coupon.discountcpn_code }}
                            </span>
                          </div>
                          <div class="coupon__detail">
                            <h6>
                              {{
                                coupon.orders.length != 0
                                  ? $t("LBL_SAVED_ADDITIONAL") +
                                    " " +
                                    $priceFormat(calTotalSaved(coupon.orders))
                                  : 
                                  $t("LBL_CAN_SAVE_UPTO") + " " + ((coupon.discountcpn_in_percent == 1) ? $priceFormat(coupon.discountcpn_maxamt_limit) : $priceFormat(coupon.discountcpn_amount))
                              }}
                            </h6>
                            <span class="coupon__highlight"></span>
                            <p v-if="coupon.discountcpn_type == 1">
                              {{
                                coupon.discountcpn_in_percent == 0
                                  ? $t("LBL_FLAT")
                                  : ""
                              }}
                              {{
                                coupon.discountcpn_in_percent == 1
                                  ? coupon.discountcpn_amount + "%"
                                  : " " +
                                    $priceFormat(coupon.discountcpn_amount)
                              }}
                              {{
                                (coupon.discountcpn_in_percent == 1) ? $t("LBL_OFF_UPTO") + " " + $priceFormat(coupon.discountcpn_maxamt_limit) : ''
                              }}
                              {{
                                coupon.discountcpn_minorderamt
                                  ? $t("LBL_ON_MIN_SHIPPING_OF") +
                                    " " +
                                    $priceFormat(
                                      coupon.discountcpn_minorderamt
                                    ) +
                                    "."
                                  : "."
                              }}
                              {{
                                $t("LBL_EXPIRES_ON") +
                                  " " +
                                  $dateTimeFormat(coupon.discountcpn_enddate)
                              }}
                            </p>
                            <p v-else>
                              {{
                                coupon.discountcpn_in_percent == 0
                                  ? $t("LBL_FLAT")
                                  : ""
                              }}
                              {{
                                coupon.discountcpn_in_percent == 1
                                  ? coupon.discountcpn_amount + "%"
                                  : " " +
                                    $priceFormat(coupon.discountcpn_amount)
                              }}
                              {{
                                (coupon.discountcpn_in_percent == 1) ? $t("LBL_OFF_UPTO") + " " + $priceFormat(coupon.discountcpn_maxamt_limit) : ''
                              }}
                              {{
                                coupon.discountcpn_minorderamt
                                  ? $t("LBL_ON_MIN_PURCHASE_OF") +
                                    " " +
                                    $priceFormat(
                                      coupon.discountcpn_minorderamt
                                    ) +
                                    "."
                                  : "."
                              }}
                              {{
                                $t("LBL_EXPIRES_ON") +
                                  " " +
                                  $dateTimeFormat(coupon.discountcpn_enddate)
                              }}
                            </p>
                            <div class="coupon_accounts">
                              <span
                                class="coupon__uses-left"
                                v-if="pandingCoupons(coupon) != 0"
                              >
                                {{
                                  pandingCoupons(coupon) +
                                    " " +
                                    $t("LBL_PENDING_USES")
                                }}
                              </span>
                              <a class="coupon__uses-right" href="javascript:;" @click="openCouponDetails($event, coupon)">{{$t('LNK_VIEW_DETAILS')}}</a>
                             <!-- {{ coupon.discountcpn_type == 1 ? "|" : "" }}
                              <div
                                class="coupon-label tag"
                                v-if="coupon.discountcpn_type == 1"
                              >
                                <svg class="svg">
                                  <use
                                    :xlink:href="
                                      baseUrl +
                                        '/dashboard/media/retina/sprite.svg#shipping-icon'
                                    "
                                  />
                                </svg>
                                Coupon
                              </div> -->                            
                            </div>
                            
                          </div>
                        </div>
                      </li>
                    </ul>
                    <infinite-loading
                      :identifier="infiniteId"
                      @infinite="infiniteHandler"
                    ></infinite-loading>
                    <div
                      class="no-data-found no-data-found--sm"
                      v-if="coupons.length == 0 && pageLoad == true"
                    >
                      <div class="img">
                        <img
                          data-yk
                          :src="
                            baseUrl +
                              '/dashboard/media/retina/no-reward-points.svg'
                          "
                          alt
                        />
                      </div>
                      <div class="data">
                        <h2>{{ $t("LBL_NO_COUPONS") }}</h2>
                        <a
                          class="btn btn-outline-brand btn-wide"
                          :href="shopUrl"
                          >{{ $t("BTN_START_SHOPPING") }}</a
                        >
                      </div>
                    </div>
                  </div>
                </div>
              </main>
            </div>
          </div>          
        </div>
      </div>
    </section>
    <b-modal id="couponDetails" centered title="BootstrapVue" header-class="border-0 pb-0"  hide-footer>
      <template v-slot:modal-header>
            <button type="button" class="close" @click="$bvModal.hide('couponDetails');">×</button>
      </template>
      <div class="p-1 coupon-info">
        <div class="coupon-info__header row">
            <div class="col">
                <div class="coupon-off">
                    <span class="coupon-off__count">
                      {{
                        coupon.discountcpn_in_percent == 0
                          ? $t("LBL_FLAT")
                          : ""
                      }}
                      {{
                        coupon.discountcpn_in_percent == 1
                          ? coupon.discountcpn_amount + "%"
                          : " " +
                            $priceFormat(coupon.discountcpn_amount)
                      }}
                      {{ $t("LBL_OFF") }}
                    </span>
                </div>
                <div class="coupon-code">
                    <span>{{ $t("LBL_USE_CODE") }}:</span>
                    <div class="coupon-code__text">{{ coupon.discountcpn_code }}</div>
                    <span class="coupon-code__valid">{{ $t("LBL_VALID_TILL") }}: {{ $dateTimeFormat(coupon.discountcpn_enddate) }}</span>
                </div>
            </div>
            <div class="col">
                <div class="coupon-info__img">
                      <img
                          data-yk
                          :src="
                            coupon.attached_file
                              ? baseUrl +
                                '/yokart/image/' +
                                coupon.attached_file.afile_id +
                                '/smallt='+$timestamp(coupon.attached_file.afile_updated_at)
                              : $noImage('1_1/250x250.png')
                          "
                          data-aspect-ratio="1:1"
                        />
                </div>
            </div>
        </div>
        <div class="coupon-info__sprtr">
        </div>
        <div class="coupon-info__body">
          <h5>
            {{
              $t("LBL_UPTO") + " " +
                ((coupon.discountcpn_in_percent == 1) ? $priceFormat(coupon.discountcpn_maxamt_limit) : $priceFormat(coupon.discountcpn_amount))
            }}
            {{
              coupon.discountcpn_minorderamt
                ? $t("LBL_ON_MIN_PURCHASE_OF") +
                  " " +
                  $priceFormat(
                    coupon.discountcpn_minorderamt
                  )
                : ""
            }}
            </h5>
          <h6 v-if="brands !='' ">
            {{ $t("LBL_VALID_ON_BRANDS") }}
          </h6>
          <p v-if="brands !='' ">{{ brands }}</p>
          <h6 v-if="categories !='' ">
            {{ $t("LBL_VALID_ON_CATEGORIES") }}
          </h6>
          <p v-if="categories !='' ">{{ categories }}</p>
          <h6 v-if="products !='' ">
            {{ $t("LBL_VALID_ON_PRODUCTS") }}
          </h6>         
          <p v-if="products !='' ">{{ products }}</p>

          <h6>{{ $t("LBL_TERMS_AND_CONDITIONS") }}</h6>
          <p>1- {{ $t("LBL_COUPON_TERMS_AND_CONDITIONS_CONTENT_LINE_1") }}.</p>
          <p>2- {{ $t("LBL_COUPON_TERMS_AND_CONDITIONS_CONTENT_LINE_2") }}.</p>
          <p>3- {{ $t("LBL_COUPON_TERMS_AND_CONDITIONS_CONTENT_LINE_3") }}.</p>
        </div>
        
      </div>
   </b-modal>
  </div>
</template>
<script>
import leftSidebar from "@/buyerDashboard/Sidebar";
import dasboardHeader from "@/buyerDashboard/Header";
export default {
  components: {
    leftSidebar: leftSidebar,
    dasboardHeader: dasboardHeader,
  },
  data: function() {
    return {
      baseUrl: baseUrl,
      coupons: [],
      page: 1,
      infiniteId: +new Date(),
      pageLoad: false,
      couponModalId: "couponDetailsModal",
      coupon: '',
      products: '',
      categories: '',
      brands: '',
      users:''
    };
  },
  props: ["totalDiscount", "shopUrl"],
  methods: {
    openCouponDetails: function(event, coupon) {
      this.$axios.get(
        baseUrl + "/user/discount-coupons/included?id=" +coupon.discountcpn_id
      )
      .then(response => {
        console.log(response);
        this.products = response.data.data.products;
        this.categories = response.data.data.categories;
        this.brands = response.data.data.brands;
        this.users = response.data.data.users;          
        this.coupon = coupon;
        this.$bvModal.show(this.couponModalId);
      });  
      this.$bvModal.show("couponDetails");
    },
    calTotalSaved: function(orders) {
      return orders.reduce(
        (a, b) => parseFloat(a) + parseFloat(b.order_discount_amount),
        0
      );
    },
    pandingCoupons: function(coupon) {
      if (
        coupon.orders.length < coupon.discountcpn_total_uses &&
        coupon.orders.length < coupon.discountcpn_uses_per_user
      ) {
        return coupon.discountcpn_uses_per_user - coupon.orders.length;
      }
      return 0;
    },
    infiniteHandler($state) {
      let formData = this.$serializeData({
        total: this.coupons.length,
      });

      this.$axios
        .post(baseUrl + "/user/coupons/load-records", formData)
        .then((response) => {
          if (response.data.data.length) {
            this.page += 1;
            this.coupons.push(...response.data.data);
            $state.loaded();
          } else {
            $state.complete();
          }
        });
    },
    getListing: function() {
      this.page = 1;
      this.infiniteId += 1;
      this.$axios.get(baseUrl + "/user/coupons/listing").then((response) => {
        this.pageLoad = true;
        this.coupons = response.data.data.data;
      });
    }    
  },
  mounted: function() {
    this.getListing();
  },
};
</script>
