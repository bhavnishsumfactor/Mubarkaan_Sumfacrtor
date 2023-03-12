<template>
  <div class="body bg-dashboard" id="body" data-yk >
    <section class="section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-10">
            <div class="account-area">
              <dasboard-header></dasboard-header>
              <left-sidebar :pageType="'localization'"></left-sidebar>
              <main class="main-content" data-yk role="yk-main-content">
                <div class="card">
                  <div class="card-head">
                    <h2 v-if="$mq === 'mobile'">{{ $t('LBL_LOCALIZATION') }}</h2>
                  </div>

                  <div class="card-body">
                    <div class="form">
                      <div class="row justify-content-center">
                        <div class="col-lg-6">
                          <div class="mb-5">
                            <div class="form-group form-floating__group mb-0">
                              <label>
                                {{
                                $t("LBL_SELECT_REGION_CAPTION")
                                }}
                              </label>
                              <select class="custom-select" v-model="userData.user_country_id">
                                <option value="">{{ $t("LBL_SELECT") }}</option>
                                <option
                                  v-for="(countory, countoryKey) in countries"
                                  :value="countoryKey"
                                  :key="countoryKey"
                                >{{ countory }}</option>
                              </select>
                            </div>
                          </div>

                          <div class="mb-5">
                            <div class="form-group form-floating__group mb-0">
                              <label>
                                {{
                                $t("LBL_SELECT_TIMEZONE_CAPTION")
                                }}
                              </label>
                              <select class="custom-select" v-model="userData.user_timezone">
                                <option value="">{{ $t("LBL_SELECT") }}</option>
                                <option
                                  v-for="(timezone, timezoneKey) in timezones"
                                  :value="timezone"
                                  :key="timezoneKey"
                                >{{ timezone }}</option>
                              </select>
                            </div>
                          </div>
                          <div class="mb-5" v-if="Object.keys(languages).length > 1">
                            <div class="form-group form-floating__group">
                              <label>
                                {{
                                $t("LBL_SELECT_LANGUAGE_CAPTION")
                                }}
                              </label>

                              <select
                                class="custom-select notranslate"
                                v-model="userData.user_language_id"
                              >
                                <option value="">{{ $t("LBL_SELECT") }}</option>
                                <option
                                  v-for="(language, languageKey) in languages"
                                  :value="languageKey"
                                  :key="languageKey"
                                >{{ language }}</option>
                              </select>
                            </div>
                          </div>
                          <div class="mb-5" v-if="currencies.length > 1">
                            <div class="form-group form-floating__group">
                              <label>
                                {{
                                $t("LBL_SELECT_CURRENCY_CAPTION")
                                }}
                              </label>
                              <select class="custom-select" v-model="userData.user_currency_id">
                                <option value="">{{ $t("LBL_SELECT") }}</option>
                                <option
                                  v-for="(currency, currencyKey) in currencies"
                                  :value="currency.currency_id"
                                  :key="currencyKey"
                                >{{ currency.currency_name }}</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-footer">
                    <div class="text-center">
                      <div class="row justify-content-center">
                        <div class="col-lg-6">
                          <button
                            type="button"
                            class="btn btn-brand btn-wide gb-btn gb-btn-primary"
                            v-bind:class="[
                              sending == true ? 'gb-is-loading' : '',
                            ]"
                            @click="updateLocalizationInfo(), (sending = true)"
                            :disabled="
                              (userData.user_currency_id == '' && currencies.length > 1) ||
                                (userData.user_language_id == '' && Object.keys(languages).length > 1) ||
                                userData.user_timezone == '' ||
                                userData.user_country_id == ''
                            "
                          >{{ $t("BTN_SAVE") }}</button>
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
const tableFields = {
  user_currency_id: window.auth.user_currency_id,
  user_language_id: (window.auth.user_language_id != null) ? window.auth.user_language_id : '',
  user_timezone: window.auth.user_timezone,
  user_country_id: window.auth.user_country_id
};
export default {
  components: {
    leftSidebar: leftSidebar,
    dasboardHeader: dasboardHeader
  },
  props: ["languages", "currencies", "countries", "timezones"],
  data: function() {
    return {
      sending: false,
      baseUrl: baseUrl,
      userData: tableFields
    };
  },
  methods: {
    updateLocalizationInfo: function() {
      let formData = this.$serializeData(this.userData);
      this.$axios
        .post(baseUrl + "/user/localization", formData)
        .then(response => {
          this.sending = false;
          toastr.success(response.data.message, "", toastr.options);
          setTimeout(function() {
            window.location.reload();
          }, 1000);
        });
    }
  }
};
</script>
