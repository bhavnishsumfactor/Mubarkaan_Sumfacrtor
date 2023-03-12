<template>
  <div class="body bg-dashboard" id="body" data-yk >
    <section class="section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-10">
            <div class="account-area">
              <dasboard-header></dasboard-header>
              <left-sidebar :pageType="'favorite'"></left-sidebar>
              <main class="main-content" data-yk role="yk-main-content">
                <div class="card">
                  <div class="card-head" v-if="products.length != 0">
                    <h2 v-if="$mq === 'mobile'">{{ $t('LBL_FAVORITES') }}</h2>
                    <ul class="filters-ghost" v-if="$mq !== 'mobile'">
                      <li
                        v-for="(filter, fKey) in filters"
                        :key="fKey"
                        v-bind:class="[fKey == selectedFilter ? 'active' : '']"
                      >
                        <a href="javascript:;" @click="getListing(fKey)">
                          <svg class="svg" v-if="fKey == 'priceDesc'">
                            <use
                              :xlink:href="
                                baseUrl +
                                  '/dashboard/media/retina/sprite.svg#hightolow'
                              "
                            />
                          </svg>
                          <svg class="svg" v-if="fKey == 'priceAsc'">
                            <use
                              :xlink:href="
                                baseUrl +
                                  '/dashboard/media/retina/sprite.svg#lowtohigh'
                              "
                            />
                          </svg>
                          {{filter }}
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
                             <svg class="svg mr-2" width="14px" height="14px" v-if="fKey == 'priceDesc'">
                            <use
                              :xlink:href="
                                baseUrl +
                                  '/dashboard/media/retina/sprite.svg#hightolow'
                              "
                            />
                          </svg>
                          <svg class="svg mr-2" width="14px" height="14px" v-if="fKey == 'priceAsc'">
                            <use
                              :xlink:href="
                                baseUrl +
                                  '/dashboard/media/retina/sprite.svg#lowtohigh'
                              "
                            />
                          </svg>
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
                    v-bind:class="[
                      products.length == 0 ? 'no-data-found-wrap' : '',
                    ]"
                  >
                    <ul
                      id="collection-products"
                      class="collection-favourites"
                      data-view="3"
                      v-if="products.length != 0"
                    >
                      <product-card
                        v-for="(product, pKey) in products"
                        :key="pKey"
                        :product="product"
                        :imageTime="product.get_updated_image ? product.get_updated_image.afile_updated_at:''"
                        :aspectRatio="aspectRatio"
                        :index="pKey"
                        @updateFavourite="updateFavourite"
                      ></product-card>
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
                            baseUrl + '/dashboard/media/retina/no-favourite.svg'
                          "
                          alt
                        />
                      </div>
                      <div class="data">
                        <h2>{{ $t("LBL_NO_FAVORITES_FOUND") }}</h2>
                        <a
                          :href="shopUrl"
                          class="btn btn-outline-brand btn-wide"
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
import productCard from "@/common/productCard";
export default {
  components: {
    leftSidebar: leftSidebar,
    dasboardHeader: dasboardHeader,
    productCard: productCard
  },
  props: ["shopUrl", "aspectRatio"],
  data: function() {
    return {
      products: [],
      pageLoad: false,
      baseUrl: baseUrl,
      filters: {
        latest: this.$t("LNK_FILTER_DATE_NEW_TO_OLD"),
        oldest: this.$t("LNK_FILTER_DATE_OLD_TO_NEW"),
        bestSelling: this.$t("LNK_FILTER_BEST_SELLING"),
        mostDiscounted: this.$t("LNK_FILTER_MOST_DISCOUNTED"),
        alphaAsc: this.$t("LNK_FILTER_A_TO_Z"),
        alphaDesc: this.$t("LNK_FILTER_Z_TO_A"),
        priceDesc: this.$t("LNK_FILTER_PRICE"),
        priceAsc: this.$t("LNK_FILTER_PRICE")
      },
      page: 1,
      infiniteId: +new Date(),
      selectedFilter: "latest"
    };
  },
  methods: {
    getListing: function(filterType) {
      this.page = 1;
      this.infiniteId += 1;
      let formData = this.$serializeData({ filters: filterType, total: 0 });
      this.$axios
        .post(baseUrl + "/user/favorite/listing", formData)
        .then(response => {
          this.pageLoad = true;
          this.selectedFilter = filterType;
          this.products = response.data.data.data;
        });
    },
    infiniteHandler($state) {
      let formData = this.$serializeData({
        filters: this.selectedFilter,
        total: this.products.length
      });

      this.$axios
        .post(baseUrl + "/user/favorite/listing", formData)
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
    updateFavourite: function(index) {
      let formData = this.$serializeData({
        id: this.products[index].prod_id,
        code: this.products[index].pov_code
      });
      this.$axios
        .post(baseUrl + "/product/favourite", formData)
        .then(response => {
          this.getListing(this.selectedFilter);
        });
    }
  },
  mounted: function() {
    this.pageLoad = false;
    this.getListing(this.selectedFilter);
  }
};
</script>
