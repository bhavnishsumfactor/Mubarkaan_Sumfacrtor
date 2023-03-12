<template>
  <div class="body bg-dashboard" id="body" data-yk >
    <section class="section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-10">
            <div class="account-area">
              <dasboard-header></dasboard-header>
              <left-sidebar :pageType="'download'"></left-sidebar>
              <main class="main-content" data-yk role="yk-main-content">
                <div class="card">
                  <div class="card-head" v-if="products.length != 0">
                    <h2 v-if="$mq === 'mobile'">Downloads</h2>

                    <ul class="filters-ghost" v-if="$mq !== 'mobile'">
                      <li
                        v-for="(filter, fKey) in filters"
                        :key="fKey"
                        v-bind:class="[fKey == selectedFilter ? 'active' : '']"
                      >
                        <a href="javascript:;" @click="getListing(fKey)">{{filter }}</a>
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
                        <ul class="nav nav--block">
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
                    class="card-body"
                    v-bind:class="[products.length == 0 ? 'no-data-found-wrap' : '']"
                  >
                    <ul class="downloads" v-if="products.length > 0">
                      <li
                        class="downloads_card"
                        v-for="(product, pKey) in products"
                        :key="pKey"
                        v-bind:class="[
                          product.expired ? 'disable' : '',
                          orderId == product.order_id &&
                          productId == product.op_product_id
                            ? 'selected'
                            : '',
                        ]"
                      >
                        <div class="downloads_card_body">
                          <div class="downloads_card_media">
                            <div class="downloads_card_media_type" :title="product.maxTypeImage">
                              <svg class="svg">
                                <use
                                  :xlink:href="
                                    baseUrl +
                                    '/dashboard/media/retina/sprite.svg#' +
                                    product.maxTypeImage
                                  "
                                  :href="
                                    baseUrl +
                                    '/dashboard/media/retina/sprite.svg#' +
                                    product.maxTypeImage
                                  "
                                />
                              </svg>

                              <span
                                class="file-size"
                                v-if="product.totalSize > 0"
                              >{{ $formatSizeUnits(product.totalSize) }}</span>
                            </div>
                          </div>
                          <ul class="media-more">
                            <li
                              v-for="(
                                image, index
                              ) in product.attachments.slice(0, 3)"
                              :key="index"
                            >
                              <span class="circle" v-b-tooltip.hover :title="fileTypes[image]">
                                <svg class="svg">
                                  <use
                                    :xlink:href="
                                      baseUrl +
                                      '/dashboard/media/retina/sprite.svg#' +
                                      $cleanString(fileTypes[image])
                                    "
                                    :href="
                                      baseUrl +
                                      '/dashboard/media/retina/sprite.svg#' +
                                      $cleanString(fileTypes[image])
                                    "
                                  />
                                </svg>
                              </span>
                            </li>
                            <li>
                              <span
                                class="plus-more"
                                v-if="product.attachments.length > 3"
                              >+{{ product.attachments.length - 3 }}</span>
                            </li>
                          </ul>
                        </div>
                        <div class="downloads_card_foot">
                          <div class="downloads_card_title">
                            <h3>{{ product.op_product_name }}</h3>
                            <div class="action">
                              <a
                                class="download-link"
                                href="javascript:;"
                                @click="downloadFiles(pKey, product)"
                                v-b-tooltip.hover
                                :title="downloadTooltipTitle(product)"
                              >
                                <svg class="svg" width="22px" height="22px">
                                  <use
                                    :xlink:href="
                                      baseUrl +
                                      '/dashboard/media/retina/sprite.svg#downloads'
                                    "
                                    :href="
                                      baseUrl +
                                      '/dashboard/media/retina/sprite.svg#downloads'
                                    "
                                  />
                                </svg>
                              </a>
                            </div>
                          </div>
                          <ul class="list-group list-downloads">
                            <li class="list-group-item">
                              {{ $t("LBL_ORDER_NUMBER")
                              }}
                              <span
                                class="value ml-auto"
                              >
                                <inertia-link :href="product.orderPageUrl">#{{ product.order_id }}</inertia-link>
                              </span>
                            </li>
                            <li class="list-group-item">
                              {{ $t("LBL_DOWNLOAD_COUNT") }}
                              <span class="value ml-auto">
                                {{ product.totaldownload }}/{{
                                product.maxdownloadlimit
                                }}
                              </span>
                            </li>
                            <li class="list-group-item">
                              {{ $t("LBL_LINK_EXPIRES_ON") }}
                              <span class="value ml-auto">
                                {{
                                $dateTimeFormat(product.op_expired_on, "date")
                                }}
                              </span>
                            </li>
                          </ul>
                        </div>
                      </li>
                    </ul>
                    <infinite-loading :identifier="infiniteId" @infinite="infiniteHandler"></infinite-loading>

                    <div
                      class="no-data-found no-data-found--sm"
                      v-if="products.length == 0 && pageLoad == true"
                    >
                      <div class="img">
                        <img
                          data-yk
                          :src="
                            baseUrl +
                            '/dashboard/media/retina/no-downloads.svg'
                          "
                          alt
                        />
                      </div>
                      <div class="data">
                        <h2>{{ $t("LBL_NO_DIGITAL_DOWNLOADS") }}</h2>
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
    </section>
  </div>
</template>
<script>
import leftSidebar from "@/buyerDashboard/Sidebar";
import dasboardHeader from "@/buyerDashboard/Header";

export default {
  components: {
    leftSidebar: leftSidebar,
    dasboardHeader: dasboardHeader
  },
  props: ["fileTypes", "orderId", "productId", "shopUrl"],
  data: function() {
    return {
      page: 1,
      infiniteId: +new Date(),
      baseUrl: baseUrl,
      products: [],
      pageLoad: false,
      filters: {
        dateAsc: this.$t("LNK_FILTER_DATE_NEW_TO_OLD"),
        dateDesc: this.$t("LNK_FILTER_DATE_OLD_TO_NEW"),
        alphabet: this.$t("LNK_FILTER_A_TO_Z")
      },
      selectedFilter: "dateAsc"
    };
  },
  methods: {
    downloadTooltipTitle: function(product) {
      if (product.expired == true) {
        return "Download Link Expired";
      } else if (product.totaldownload > product.maxdownloadlimit) {
        return this.$t("LBL_DOWNLOAD_LIMIT_REACHED");
      } else {
        return this.$t("LBL_CLICK_TO_DOWNLOAD");
      }
    },
    downloadFiles: function(index, product) {
      if (
        product.totaldownload < product.maxdownloadlimit &&
        product.expired == true
      ) {
        return false;
      }
      let formData = this.$serializeData({
        "order-id": product.order_id,
        "product-id": product.op_product_id
      });
      this.$axios
        .post(baseUrl + "/product/download/url", formData)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          this.products[index] = response.data.data.product;
          this.$forceUpdate();
          window.open(response.data.data.url, "_self");
        });
    },
    infiniteHandler($state) {
      let formData = this.$serializeData({
        filters: this.selectedFilter,
        total: this.products.length
      });

      this.$axios
        .post(baseUrl + "/user/get-digtal-orders", formData)
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
      this.page = 1;
      this.infiniteId += 1;
      let formData = this.$serializeData({ filters: filterType });
      this.$axios
        .post(baseUrl + "/user/get-digtal-orders", formData)
        .then(response => {
          this.pageLoad = true;
          this.selectedFilter = filterType;
          this.products = response.data.data.data;
          this.$forceUpdate();
        });
    }
  },

  mounted: function() {
    this.getListing(this.selectedFilter);
  }
};
</script>