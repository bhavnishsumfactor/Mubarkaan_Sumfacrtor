<template>
  <div class="body bg-dashboard" id="body" data-yk >
    <section class="section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-10">
            <div class="account-area">
              <dasboard-header></dasboard-header>
              <left-sidebar :pageType="'reward-points'"></left-sidebar>
              <main class="main-content" data-yk role="yk-main-content">
                <div class="card">
                  <div
                    class="card-head"
                    v-if="
                      (rewards.length != 0 && pageLoad == true) ||
                        applyfilters == true
                    "
                  >
                    <h2 v-if="$mq === 'mobile'">
                      {{ $t("LBL_REWARDS_POINTS") }}
                    </h2>
                    <ul class="filters-ghost" v-if="$mq !== 'mobile'">
                      <li
                        v-for="(filter, fKey) in filters"
                        :key="fKey"
                        v-bind:class="[fKey == selectedFilter ? 'active' : '']"
                      >
                        <a
                          href="javascript:;"
                          @click="
                            applyfilters = true;
                            getListing(fKey);
                          "
                          >{{ filter }}</a
                        >
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
                        <span class="YK-filterBy" data-value="showall">{{
                          filters[selectedFilter]
                        }}</span>
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
                              @click="
                                applyfilters = true;
                                getListing(fKey);
                              "
                            >
                              <span class="nav__link-text">
                                {{ filter }}
                              </span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div
                    class="card-body p-0"
                    v-if="pageLoad == true"
                    v-bind:class="[
                      rewards.length == 0 ? 'no-data-found-wrap' : '',
                    ]"
                  >
                    <div
                      class="total-balance"
                      style="background-image: url(../dashboard/media/retina/rewards_graphic.svg)"
                       v-if="rewards.length != 0 && pageLoad == true"
                    >
                      <div class="total-balance_inner">
                        <span class="value">
                          {{ usablePoints }}
                        </span>
                        <p class="label">{{ $t("LBL_REWARDS_BALANCE") }}:</p>
                      </div>

                      <div class="total-balance_inner">
                        <span class="value">
                          {{ pointsWorth }}
                        </span>
                        <p class="label">{{ $t("LBL_REWARDS_WORTH") }}:</p>
                      </div>
                    </div>
                    <ul class="points-timeline" v-if="rewards.length != 0">
                      <listing
                        v-for="(reward, rIndex) in rewards"
                        :key="rIndex"
                        :reward="reward"
                        :requestId="reward.urp_orrequest_id"
                        :rewardTypes="rewardTypes"
                      ></listing>
                      <infinite-loading
                        :identifier="infiniteId"
                        @infinite="infiniteHandler"
                      ></infinite-loading>
                    </ul>
                    <div
                      v-bind:class="[
                        rewards.length == 0 ? 'no-data-found-wrap' : '',
                      ]"
                    >
                      <div
                        class="no-data-found no-data-found--sm"
                        v-if="rewards.length == 0 && pageLoad == true"
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
                          <h2>{{ $t("LBL_NO_REWARDS_FOUND") }}</h2>
                          <a
                            class="btn btn-outline-brand btn-wide"
                            :href="shopUrl"
                            >{{ $t("BTN_START_SHOPPING") }}</a
                          >
                        </div>
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
import Listing from "@/buyerDashboard/Activity/RewardPoints/Listing";

export default {
  components: {
    leftSidebar: leftSidebar,
    dasboardHeader: dasboardHeader,
    Listing: Listing,
  },
  props: ["usablePoints", "pointsWorth", "shopUrl", "rewardTypes"],
  data: function() {
    return {
      baseUrl: baseUrl,
      rewards: [],
      applyfilters: false,
      pageLoad: false,
      totalRewards: 0,
      filters: {
        showAll: this.$t("LNK_FILTER_SHOW_ALL"),
        earned: this.$t("LNK_FILTER_EARNED"),
        redeemed: this.$t("LNK_FILTER_REDEEMED"),
        expired: this.$t("LNK_FILTER_EXPIRED"),
      },
      page: 1,
      infiniteId: +new Date(),
      selectedFilter: "showAll",
    };
  },
  methods: {
    infiniteHandler($state) {
      let formData = this.$serializeData({
        filters: this.selectedFilter,
        total: this.rewards.length,
      });

      this.$axios
        .post(baseUrl + "/user/reward-points/listing", formData)
        .then((response) => {
          if (response.data.data.length) {
            this.page += 1;
            this.rewards.push(...response.data.data);
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
      this.selectedFilter = filterType;
      // this.rewards = [];
      this.$axios
        .post(baseUrl + "/user/reward-points/listing", formData)
        .then((response) => {
          this.pageLoad = true;
          this.totalRewards = response.data.data.total;
          this.rewards = response.data.data.data;
        });
    },
  },

  mounted: function() {
    this.getListing(this.selectedFilter);
  },
};
</script>
