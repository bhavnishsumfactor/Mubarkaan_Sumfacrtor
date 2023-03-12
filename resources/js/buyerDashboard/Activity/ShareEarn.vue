<template>
  <div class="body bg-dashboard" id="body" data-yk >
    <section class="section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-10">
            <div class="account-area">
              <dasboard-header></dasboard-header>
              <left-sidebar :pageType="'share-earn'"></left-sidebar>
              <main class="main-content" data-yk role="yk-main-content">
                <div class="card">
                  <div class="card-head">
                    <h2 v-if="$mq === 'mobile'">{{ $t('LBL_SHARE_AND_EARN') }}</h2>
                     <div class="ml-auto">
                      <a
                        class="btn btn-outline-brand btn-sm" style=" min-width:110px;"
                        href="javascript:;"
                        v-if="!displayListing && totalInvites != 0" 
                        @click="displayListing = !displayListing;"
                      >{{ $t("BTN_VIEW_DETAILS") }}</a>
                      <a
                        class="btn btn-outline-brand btn-sm"  style=" min-width:110px;"
                        href="javascript:;"
                        v-if="displayListing"
                        @click="displayListing = !displayListing;"
                      >{{ $t("BTN_BACK") }}</a>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="invite-box" v-if="!displayListing">
                      <div class="share-earn">
                        <img :src="baseUrl + '/dashboard/media/share-earn.png'" alt />
                        <h2>
                          {{
                          $t("LBL_INVITE_GET") +
                          " " +
                          $configVal(
                          "REWARD_POINTS_SOCIAL_SHARING_POINTS_ONSIGNUP"
                          ) +
                          " " +
                          $t("LBL_POINTS")
                          }}
                        </h2>
                        <p>
                          {{$t('LBL_INVITE_FRIEND_EARN_ON_SIGNUP')}}
                        </p>
                        <ul class="points">
                          <li class>
                            <span class="badge yellow"></span>
                            {{ $t("LBL_TOTAL_INVITE_SENT") }} :
                            {{ totalInvites }}
                          </li>
                          <li class>
                            <span class="badge green"></span>
                            {{ $t("LBL_TOTAL_POINTS_EARNED") }} :
                            {{ totalEarnPoints }}
                          </li>
                        </ul>
                      </div>
                      <div class="invite-by-email">
                        <form class="form form--inline form--fly" id="YK-shareAndEarnForm">
                          <div class="form-group">
                            <input
                              class="form-control"
                              type="text"
                              v-model="shareEmail"
                              @keyup="validateEmail()"
                              :placeholder="$t('PLH_EMAIL_ADDRESSES')"
                            />
                          </div>
                          <button
                            class="btn-fly"
                            type="button"
                            @click="sendReferralCode(), sending = true"
                            :disabled="isEmailValid"
                          >
                            <svg class="svg">
                              <use
                                :xlink:href="
                                  baseUrl +
                                    '/dashboard/media/retina/sprite.svg#submitfly'
                                "
                              />
                            </svg>
                          </button>
                        </form>
                      </div>
                      <ul class="social-invites ">
                        <li>
                          <a
                            class="btn"
                            href="javascript:;"
                            data-url
                            data-val
                            data-code
                            @click="copyCode()"
                            v-b-tooltip.hover
                            :title="$t('BTN_COPY_LINK')"
                          >
                            <span class="icon">
                              <i class="svg--icon">
                              <svg class="svg">
                                  <use
                                    :xlink:href="
                                      baseUrl +
                                  '/dashboard/media/retina/sprite.svg#icon_link'
                                "
                              />
                              </svg>
                              </i>
                            </span>
                          </a>
                        </li>
                        <li
                          v-for="(socialShare, socialKey) in socialShares"
                          :key="socialKey"
                        >
                          <ShareNetwork
                            :network="getSocialType(socialKey)"
                            :url="uniqueUrl"
                            v-b-tooltip="$camelCase(getSocialType(socialKey))"
                            :title="$t('LBL_INVITE_FRIENDS') + '' + $configVal('BUSINESS_NAME')"
                          >
                            <span class="icon">
                              <i class="svg--icon">
                                <svg class="svg">
                                  <use
                                    :xlink:href="
                                      baseUrl +
                                      '/dashboard/media/retina/sprite.svg#share-' + getSocialType(socialKey)
                                    "
                                    :href="
                                      baseUrl +
                                      '/dashboard/media/retina/sprite.svg#share-' + getSocialType(socialKey)
                                    "
                                  />
                                </svg>
                              </i>
                            </span>
                          </ShareNetwork>
                        </li>
                      </ul>
                    </div>
                    <div class="row justify-content-center" v-else>
                      <div class="col-md-10">
                        <div class="tbl-wrap">
                        <table class="tbl-invite">
                          <thead class>
                            <tr>
                              <th>{{ $t("LBL_INVITE_TYPE") }}</th>
                              <th>{{ $t("LBL_INVITED_USER") }}</th>
                              <th>{{ $t("LBL_INVITE_DATE") }}</th>
                              <th>{{ $t("LBL_INVITE_STATUS") }}</th>
                            </tr>
                          </thead>
                          <tbody v-if="totalInvites != 0">
                            <tr v-for="(invite, iKey) in invites" :key="iKey">
                              <td>{{ referralTypes[invite.ser_type] }}</td>
                              <td>
                                <span v-if="invite.ser_user_email != ''">{{ invite.ser_user_email }}</span>
                                <span v-else>{{ invite.ser_user_phone_code + ' ' + invite.ser_user_phone }}</span>
                              </td>
                              <td>{{ $dateTimeFormat(invite.ser_created_at, 'date') }}</td>
                              <td>
                                {{
                                invite.user
                                ? $t("LBL_REGISTERED")
                                : $t("LBL_INVITATION_SENT")
                                }}
                              </td>
                            </tr>                           
                          </tbody>
                        </table>
                        </div>
                        <infinite-loading :identifier="infiniteId" @infinite="infiniteHandler"></infinite-loading>
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
const emailFormat = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
export default {
  components: {
    leftSidebar: leftSidebar,
    dasboardHeader: dasboardHeader
  },
  props: ["totalEarnPoints", "socialShares", "referralCode"],
  data: function() {
    return {
      sending: false,
      page: 1,
      infiniteId: +new Date(),
      baseUrl: baseUrl,
      totalInvites: 0,
      invites: [],
      shareEmail: "",
      referralTypes: [],
      isEmailValid: true,
      displayListing: false,
      uniqueUrl: ""
    };
  },
  methods: {
    getListing: function() {
      this.page = 1;
      this.infiniteId += 1;
      let formData = this.$serializeData({
        total: this.invites.length
      });
      this.$axios
        .post(baseUrl + "/user/shared-invites/listing", formData)
        .then(response => {
          this.totalInvites = response.data.data.invites.total;
          this.invites = response.data.data.invites.data;
          this.referralTypes = response.data.data.referralTypes;
        });
    },
    infiniteHandler($state) {
      let formData = this.$serializeData({
        total: this.invites.length
      });
      this.$axios
        .post(baseUrl + "/user/shared-invites/load-records", formData)
        .then(response => {
          if (response.data.data.length) {
            this.page += 1;
            this.invites.push(...response.data.data);
            $state.loaded();
          } else {
            $state.complete();
          }
        });
    },
    validateEmail: function() {
      if (this.shareEmail) {
        let referralEmails = this.shareEmail.split(",");
        let emailError = false;
        $.each(referralEmails, function(index, val) {
          if (emailFormat.test(val.trim()) == false) {
            emailError = true;
          }
        });
        this.isEmailValid = emailError;
      }
    },
    getSocialType: function(type) {
      return type.replace("SHARETHIS_", "").toLowerCase();
    },
    sendReferralCode: function() {
      let formData = this.$serializeData({
        userEmail: this.shareEmail
      });
      this.$axios
        .post(baseUrl + "/user/send-referral", formData)
        .then(response => {
          if (response.data.status == true) {
            toastr.success(response.data.message, "", toastr.options);
            this.shareEmail = "";
            this.sending = false;
            this.isEmailValid = true;
            this.getListing();
          }
        });
    },
    copyCode: function() {
      let $temp = $("<input>");
      $("body").append($temp);
      let copyText = this.uniqueUrl;
      $temp.val(copyText).select();
      document.execCommand("copy");
      $temp.remove();
      toastr.success("Copied to clipboard");
    }
  },
  mounted: function() {
    this.uniqueUrl = baseUrl + "/register?referralcode=" + this.referralCode;
    this.getListing();
  }
};
</script>