<template>
  <div class="body bg-dashboard" id="body" data-yk >
    <section class="section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-10">
            <div class="account-area">
              <dasboard-header></dasboard-header>
              <left-sidebar :pageType="'orders'"></left-sidebar>
              <main class="main-content" data-yk role="yk-main-content">
                <div class="card card-orders">
                  <div class="card-head">
                    <h2 v-if="$mq === 'mobile'">{{ $t('LBL_ORDERS_AND_RETURNS') }}</h2>

                    <ul class="filters-ghost" v-if="$mq !== 'mobile'">
                      <li
                        v-for="(tab, tKey) in tabs"
                        :key="tKey"
                        v-bind:class="[tKey == selectedTabs ? 'active' : '']"
                      >
                        <a href="javascript:;" @click="getListing(tKey)">{{tab }}</a>
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
                        <span class="YK-filterBy" data-value="showall">{{ tabs[selectedTabs] }}</span>
                      </a>

                      <div
                        class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim"
                        x-placement="bottom-start"
                      >
                        <ul class="nav nav--block YK-filterReviews">
                          <li
                            class="nav__item"
                            v-for="(tab, tKey) in tabs"
                            :key="tKey"
                            v-if="tKey != selectedTabs"
                          >
                            <a
                              class="nav__link"
                              data-value="reviewed"
                              href="javascript:;"
                              @click="getListing(tKey)"
                            >
                              <span class="nav__link-text">
                                {{
                                tab
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
                    v-bind:class="[orders.length == 0 ? 'no-data-found-wrap' : '']"
                  >
                    <div>
                      <ul
                        class="orders-list"
                        id="orders-list"
                        v-if="
                          selectedTabs == 'active' || selectedTabs == 'history'
                        "
                      >
                        <listing
                          v-for="(order, oKey) in orders"
                          :key="oKey"
                          :order="order"
                          :index="oKey"
                          :shippingTypes="shippingTypes"
                          :shippedStatus="shippedStatus"
                          :pickupStatus="pickupStatus"
                          :digitalStatus="digitalStatus"
                          :pickedStatus="pickedStatus"
                          :selectedTabs="selectedTabs"
                          :stateName="stateName"
                          :returnStatus="returnStatus"
                          :addressTypes="addressTypes"
                          :trackingEnabled="trackingEnabled"
                          @writeReview="writeReview"
                          @displayGiftMessage="displayGiftMessage"
                        ></listing>
                      </ul>
                      <ul class="orders-list" id="orders-list" v-else>
                        <request-listing
                          v-for="(order, oKey) in orders"
                          :key="oKey"
                          :order="order"
                          :index="oKey"
                          :shippingTypes="shippingTypes"
                          :selectedTabs="selectedTabs"
                          :stateName="stateName"
                          :countryName="countryName"
                          :returnStatus="returnStatus"
                          :addressTypes="addressTypes"
                          :trackingEnabled="trackingEnabled"
                        ></request-listing>
                      </ul>
                      <infinite-loading :identifier="infiniteId" @infinite="infiniteHandler"></infinite-loading>
                    </div>

                    <div
                      class="no-data-found no-data-found--sm"
                      v-if="orders.length == 0 && pageLoad == true"
                    >
                      <div class="img">
                        <img data-yk :src="baseUrl + '/dashboard/media/retina/no-order.svg'"/>
                      </div>
                      <div class="data">
                        <h2>{{ $t("LBL_NO_ORDERS_FOUND") }}</h2>
                        <a
                          class="btn btn-outline-brand btn-wide"
                          :href="shopUrl"
                        >{{ $t("BTN_START_SHOPPING") }}</a>
                      </div>
                    </div>
                  </div>
                </div>
              </main>
            </div>
          </div>
        </div>
      </div>
      <b-modal
        id="giftModal"
        centered
        title="BootstrapVue"
        header-class="border-0"
        hide-header
        hide-footer
      >
        <button type="button" class="close" @click="$bvModal.hide('giftModal')">
          <span aria-hidden="true">×</span>
        </button>

        <div class="gift-details" v-if="Object.keys(giftMessage).length > 0">
          <div class="gift-details__img">
            <img data-yk :src="baseUrl + '/dashboard/media/retina/gift.svg'" alt />
          </div>
          <div class="row mb-4">
            <div class="col-md-6">
              <h6>
                <strong>{{$t('LBL_FROM')}}:</strong>
              </h6>
              <p>{{giftMessage.message.from}}</p>
            </div>
            <div class="col-md-6">
              <h6>
                <strong>{{$t('LBL_TO')}}:</strong>
              </h6>
              <p>{{giftMessage.message.to}}</p>
            </div>
          </div>
          <h6>
            <strong>{{$t('LBL_MESSAGE')}}:</strong>
          </h6>
          <div class="gift-details__msg">
            <p>{{giftMessage.message.message}}</p>
          </div>
        </div>
      </b-modal>
      <review-form :product="reviewProduct" :reviewType="reviewType" @updateListing="updateListing"></review-form>
    </section>
  </div>
</template>
<script>
import leftSidebar from "@/buyerDashboard/Sidebar";
import dasboardHeader from "@/buyerDashboard/Header";
import Listing from "@/buyerDashboard/Orders/Listing";
import RequestListing from "@/buyerDashboard/Orders/RequestListing";
import reviewForm from "@/common/popups/reviewForm";
export default {
  components: {
    leftSidebar: leftSidebar,
    dasboardHeader: dasboardHeader,
    Listing: Listing,
    RequestListing: RequestListing,
    reviewForm: reviewForm
  },
  props: [
    "id",
    "shippingTypes",
    "pickupStatus",
    "digitalStatus",
    "stateName",
    "countryName",
    "returnStatus",
    "addressTypes",
    "shopUrl",
    "trackingEnabled",
    "type",
    "shippedStatus",
    "pickedStatus",
  ],
  data: function() {
    return {
      sending: false,
      page: 1,
      infiniteId: +new Date(),
      baseUrl: baseUrl,
      orders: [],
      totalorders: 0,
      pageLoad: false,
      reviewType: {},
      reviewProduct: {},
      giftMessage: {},
      tabs: {
        active: this.$t("LNK_ACTIVE_ORDERS"),
        cancellation: this.$t("LNK_CANCELLATION"),
        return: this.$t("LNK_RETURNS"),
        history: this.$t("LNK_ORDER_HISTORY")
      },
      selectedTabs: "active",
      height: "800px"
    };
  },
  methods: {
    displayGiftMessage: function(message) {
      this.giftMessage = message;
      this.$bvModal.show("giftModal");
    },
    writeReview: function(orderIndex, prodIndex) {
      this.reviewProduct = this.orders[orderIndex].products[prodIndex];
      this.reviewType = {
        type: "new",
        logId: "",
        orderIndex: orderIndex,
        prodIndex: prodIndex
      };
      this.$bvModal.show("reviewModel");
    },
    updateListing: function(productId, selectedRating) {
      this.orders[this.reviewType.orderIndex].products[
        this.reviewType.prodIndex
      ].product_review = { preview_rating: selectedRating };
    },
    infiniteHandler($state) {
      let formData = this.$serializeData({
        total: this.orders.length
      });

      this.$axios
        .post(baseUrl + "/user/get-orders/" + this.selectedTabs, formData)
        .then(response => {
          if (response.data.data.length) {
            this.page += 1;
            this.orders.push(...response.data.data);
            $state.loaded();
          } else {
            $state.complete();
          }
        });
    },
    getListing: function(type, id = 0) {
      this.pageLoad = false;
      this.page = 1;
      this.infiniteId += 1;
      let formData = this.$serializeData({
        id: id
      });
      this.orders = [];
      this.totalorders = 0;
      this.$axios
        .post(baseUrl + "/user/get-orders/" + type, formData)
        .then(response => {
          this.pageLoad = true;
          this.selectedTabs = type;
          this.totalorders = response.data.data.total;
          this.orders = response.data.data.data;
          this.$forceUpdate();
        });
    }
  },

  mounted: function() {
    if (this.type) {
      this.selectedTabs = this.type;
    }
    this.getListing(this.selectedTabs, this.id);
  }
};
</script>