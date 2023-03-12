<template>
<div class="">
  <div class="sidebar account-menu" id="account-menu" data-close-on-click-outside="account-menu">
    <!--<button class="" data-target-close="account-menu"  v-if="$mq !== 'desktop'">Close</button>-->
    <div class="sidebar_body">
      <div class="user-card" v-if="$mq === 'mobile' || $mq === 'tablet'">
        <div class="user-card_inner">
          <span class="user-card_photo">
            <img class="YK-userAvatar" :src="profileImage" alt />
          </span>
          <div clss>
            <h4 class="user-card_name">
              <strong>Hi</strong>
              <br />
              {{ auth.user_fname + " " + auth.user_lname }}
            </h4>
          </div>
        </div>
      </div>
      <ul class="menu__nav">
        <li class="menu__head">{{ $t("LBL_ORDERS") }}</li>
        <li class="menu__item" v-bind:class="[isUrl('orders') ? 'active' : '']">
          <inertia-link class="menu__link" :href="isUrl('orders') == false ? baseUrl + '/user/orders' : 'javascript:;'">
            <i class="menu__link-icn">
              <svg class="svg">
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#order-return'
                  "
                />
              </svg>
            </i>
            <span class="menu__link-text">{{ $t("LBL_ORDERS_AND_RETURNS") }}</span>
          </inertia-link>
        </li>

        <li
          class="menu__item"
          v-if="
            this.$configVal('PRODUCT_TYPES') == 3 ||
            this.$configVal('PRODUCT_TYPES') == 2
          "
          v-bind:class="[isUrl('download') ? 'active' : '']"
        >
          <inertia-link class="menu__link" :href="isUrl('download')  == false ? baseUrl + '/user/orders/download' : 'javascript:;'">
            <i class="menu__link-icn">
              <svg class="svg">
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#downloads'
                  "
                />
              </svg>
            </i>
            <span class="menu__link-text">
              {{
              $t("LBL_DOWNLOADS")
              }}
            </span>
          </inertia-link>
        </li>

        <li class="menu__head">{{ $t("LBL_ACTIVITY") }}</li>
        <li class="menu__item" v-bind:class="[isUrl('favorite') ? 'active' : '']">
          <inertia-link class="menu__link" :href="isUrl('favorite')  == false ? baseUrl + '/user/favorite' : 'javascript:;'">
            <i class="menu__link-icn">
              <svg class="svg">
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#favorite'
                  "
                />
              </svg>
            </i>
            <span class="menu__link-text">
              {{
              $t("LBL_FAVORITES")
              }}
            </span>
          </inertia-link>
        </li>

        <li
          class="menu__item"
          v-if="
            this.$configVal('REWARD_POINTS_ENABLE') == 1 &&
            this.$configVal('REWARD_POINTS_SOCIAL_SHARING_POINTS_ENABLE') == 1
          "
          v-bind:class="[isUrl('share-earn') ? 'active' : '']"
        >
          <inertia-link class="menu__link" :href="isUrl('share-earn')  == false ? baseUrl + '/user/share-earn' : 'javascript:;'">
            <i class="menu__link-icn">
              <svg class="svg">
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#share-earn'
                  "
                />
              </svg>
            </i>
            <span class="menu__link-text">
              {{
              $t("LBL_SHARE_AND_EARN")
              }}
            </span>
          </inertia-link>
        </li>

        <li class="menu__item" v-bind:class="[isUrl('reviews') ? 'active' : '']">
          <inertia-link class="menu__link" :href="isUrl('reviews')  == false ? baseUrl + '/user/reviews' : 'javascript:;'">
            <i class="menu__link-icn">
              <svg class="svg">
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#reviews'
                  "
                />
              </svg>
            </i>
            <span class="menu__link-text">
              {{
              $t("LBL_REVIEWS")
              }}
            </span>
          </inertia-link>
        </li>

        <li
          class="menu__item"
          v-if="this.$configVal('REWARD_POINTS_ENABLE') == 1"
          v-bind:class="[isUrl('reward-points') ? 'active' : '']"
        >
          <inertia-link class="menu__link" :href="isUrl('reward-points')  == false ? baseUrl + '/user/reward-points' : 'javascript:;'">
            <i class="menu__link-icn">
              <svg class="svg">
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#reward-points'
                  "
                />
              </svg>
            </i>
            <span class="menu__link-text">
              {{
              $t("LBL_REWARD_POINTS")
              }}
            </span>
          </inertia-link>
        </li>

        <li class="menu__item" v-bind:class="[isUrl('coupons') ? 'active' : '']">
          <inertia-link class="menu__link" :href="isUrl('coupons')  == false ? baseUrl + '/user/coupons' : 'javascript:;'">
            <i class="menu__link-icn">
              <svg class="svg">
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#coupons'
                  "
                />
              </svg>
            </i>
            <span class="menu__link-text">
              {{
              $t("LBL_DISCOUNT_COUPONS")
              }}
            </span>
          </inertia-link>
        </li>
        <li class="menu__item" v-bind:class="[isUrl('messages') ? 'active' : '']">
          <a class="menu__link" :href="'javascript:;'" v-if="messageNav"  @click="$emit('switchLayout', false)">
            <i class="menu__link-icn">
              <svg class="svg">
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#messages'
                  "
                />
              </svg>
            </i>
            <span class="menu__link-text">{{ $t("LBL_MESSAGES") }}</span>
            <span class="badge badge--brand yk-messageCount" id="yk-messageCount"></span>
          </a>
          <inertia-link class="menu__link" :href="isUrl('messages')  == false ? baseUrl + '/user/messages' : 'javascript:;'" v-else>
            <i class="menu__link-icn">
              <svg class="svg">
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#messages'
                  "
                />
              </svg>
            </i>
            <span class="menu__link-text">{{ $t("LBL_MESSAGES") }}</span>
            <span class="badge badge--brand yk-messageCount" id="yk-messageCount"></span>
          </inertia-link>
        </li>

        <li class="menu__head">{{ $t("LBL_ACCOUNT") }}</li>
        <li class="menu__item" v-bind:class="[isUrl('profile') ? 'active' : '']">
          <inertia-link class="menu__link" :href="isUrl('profile')  == false ? baseUrl + '/user/profile' : 'javascript:;'">
            <i class="menu__link-icn">
              <svg class="svg">
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#profile'
                  "
                />
              </svg>
            </i>
            <span class="menu__link-text">
              {{
              $t("LBL_PROFILE")
              }}
            </span>
          </inertia-link>
        </li>
        <li class="menu__item" v-bind:class="[isUrl('address') ? 'active' : '']">
          <inertia-link class="menu__link"  :href="isUrl('address')  == false ? baseUrl + '/user/address' : 'javascript:;'" >
            <i class="menu__link-icn">
              <svg class="svg">
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#address-book'
                  "
                />
              </svg>
            </i>
            <span class="menu__link-text">
              {{
              $t("LBL_ADDRESS_BOOK")
              }}
            </span>
          </inertia-link>
        </li>

        <li class="menu__item" v-if="activeCardTab" v-bind:class="[isUrl('cards') ? 'active' : '']">
          <inertia-link class="menu__link"  :href="isUrl('cards')  == false ? baseUrl + '/user/cards' : 'javascript:;'">
            <i class="menu__link-icn">
              <svg class="svg">
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#cards'
                  "
                />
              </svg>
            </i>
            <span class="menu__link-text">
              {{
              $t("LBL_SAVED_CARDS")
              }}
            </span>
          </inertia-link>
        </li>

        <li class="menu__item" v-bind:class="[isUrl('localization') ? 'active' : '']">
          <inertia-link class="menu__link"  :href="isUrl('localization')  == false ? baseUrl + '/user/localization' : 'javascript:;'">
            <i class="menu__link-icn">
              <svg class="svg">
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#localization'
                  "
                />
              </svg>
            </i>
            <span class="menu__link-text">{{ $t("LBL_LOCALIZATION") }}</span>
          </inertia-link>
        </li>
        <li class="menu__item"         
        v-if="this.$configVal('ACCEPT_COOKIE_ENABLE') == 1"
        v-bind:class="[isUrl('cookie-preferences') ? 'active' : '']">
          <inertia-link class="menu__link"  :href="isUrl('cookie-preferences')  == false ? baseUrl + '/user/cookie-preferences' : 'javascript:;'">
            <i class="menu__link-icn">
              <svg class="svg">
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#localization'
                  "
                />
              </svg>
            </i>
            <span class="menu__link-text">{{ $t("LBL_COOKIE_PREFERENCES") }}</span>
          </inertia-link>
        </li>
        <li class="menu__item">
          <a class="menu__link logout" :href="baseUrl + '/logout'">
            <i class="menu__link-icn icn">
              <svg class="svg">
                <use
                  :xlink:href="
                    baseUrl + '/dashboard/media/retina/sprite.svg#logout'
                  "
                />
              </svg>
            </i>
            <span class="menu__link-text">{{ $t("LNK_LOGOUT") }}</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="sidebar_foot">
      <div class="powered-by text-center">
       
      </div>

    </div>
  </div>
    <div class="back-overlay"></div> 
    </div>
</template>
<script>
export default {
  props: ["pageType", "messageNav"],
  data: function() {
    return {
      baseUrl: baseUrl,
      activeCardTab: window.activeCardTab,
      languages: [],
      currencies: [],
      selectedLanguage: {},
      selectedCurrency: {},
      defaultFlag: "",
      auth: window.auth,
      profileImage: ""
    };
  },
  methods: {
    isUrl: function(url) {
      if (url == this.pageType) {
        return true;
      }
      return false;
    },
    switchLanguage: function(language) {
      this.$axios
        .post(baseUrl + "/user/switch-language", { language: language })
        .then(response => {
          if (response.data.status == true) {
            window.location.href =
              window.location.href +
              "?language=" +
              language +
              "#googtrans(auto|" +
              language +
              ")";
          }
        });
    },
    getUserUnreadMessage: function() {
        this.$axios.get(baseUrl + "/user/unread-message-count").then(response => {
            if (response.data.status == true) {
                if(response.data.data == 0) {
                   document.getElementById("yk-messageCount").innerHTML = '';
                } else {
                  document.getElementById("yk-messageCount").innerHTML = response.data.data;
                }
                
            }
        })
    }
  },
  mounted: function() {
    this.profileImage =
      this.baseUrl +
      "/" +
      this.$getFileUrl("userProfileImage", auth.user_id, 0, "50-50");

    this.$root.$on("updateProfileImage", data => {
      this.profileImage = data;
    });
    let self = this;
    this.languages = window.languages;
    this.currencies = window.currencies;
    this.selectedLanguage = window.selectedLanguage;
    this.selectedCurrency = window.selectedCurrency;
    this.defaultFlag = window.defaultFlag;
    this.getUserUnreadMessage();
    setInterval(function () {
        self.getUserUnreadMessage();
    }, 60000);
  }
};
</script>
