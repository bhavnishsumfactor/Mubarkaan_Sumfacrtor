<template>
  <div class="body bg-dashboard" id="body" data-yk >
    <section class="section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-10">
            <div class="account-area">
              <dasboard-header></dasboard-header>
              <left-sidebar :pageType="'cookie-preferences'"></left-sidebar>
              <main class="main-content" data-yk role="yk-main-content">
                <div class="card">
                  <div class="card-head">
                    <h2 v-if="$mq === 'mobile'">{{ $t('LBL_COOKIE_PREFERENCES') }}</h2>
                  </div>

                  <div class="card-body">
                    <div class="form">
                      <div class="row justify-content-center">
                        <div class="col-lg-10">  
                          <div class="personalisation">
                            <p v-html="$configVal('ADVANCED_PREFERENCES_COOKIE_TEXT')"></p>
                            <ul>
                              <li><div class="YK-fieldCookie">
                                <label class="yk-label col-form-label checkbox" for="yk-field-functional">
                                    <input value="1" type="checkbox" :checked="settings.functional==1" class="yk-choice checkbox disabledbutton" id="yk-field-functional" name="yk-ac-functional" rel="functional" disabled="disabled">
                                    <strong>{{$t('LBL_FUNCTIONAL_COOKIES')}}</strong>
                                </label>
                                <div class="yk-pseudolabel"><p v-html="$configVal('FUNCTIONAL_COOKIE_TEXT')"></p></div>
                            </div>
                            </li>
                              <li><div class="YK-fieldCookie">
                                <label class="yk-label col-form-label checkbox" for="yk-field-analytics">
                                    <input value="1" type="checkbox" :checked="settings.analytics==1" class="yk-choice checkbox" id="yk-field-analytics" rel="analytics" name="yk-ac-analytics" @change="onSwitchStatus($event, 'analytics')">
                                    <strong>{{$t('LBL_STATISTICAL_ANALYSIS_COOKIE')}}</strong>
                                </label>
                                <div class="yk-pseudolabel"><p v-html="$configVal('STATISTICAL_ANALYSIS_COOKIE_TEXT')"></p></div>
                            </div> </li>
                              <li><div class="YK-fieldCookie">
                                <label class="yk-label col-form-label checkbox" for="yk-field-customization">
                                    <input value="1" type="checkbox" :checked="settings.personalized==1" class="yk-choice checkbox" id="yk-field-customization" rel="customization" name="yk-ac-personalized" @change="onSwitchStatus($event, 'personalized')">
                                    <strong>{{$t('LBL_PERSONALISE_COOKIE')}}</strong>
                                </label>
                                <div class="yk-pseudolabel"><p v-html="$configVal('PERSONALIZE_COOKIE_TEXT')"></p></div>
                            </div> </li>
                              <li><div class="YK-fieldCookie">
                                <label class="yk-label col-form-label checkbox" for="yk-field-advertising">
                                    <input value="1" type="checkbox" :checked="settings.advertising==1" class="yk-choice checkbox" id="yk-field-advertising" rel="advertising" name="yk-ac-advertising" @change="onSwitchStatus($event, 'advertising')">
                                    <strong>{{$t('LBL_ADVERTISING_SOCIAL_MEDIA_COOKIE')}}</strong>
                                </label>
                                <div class="yk-pseudolabel"><p v-html="$configVal('ADVERTISING_SOCIAL_MEDIA_COOKIE_TEXT')"></p></div>
                            </div> </li>
                              </ul>
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
                            @click="updateInfo(), (sending = true)"                            
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
export default {
  components: {
    leftSidebar: leftSidebar,
    dasboardHeader: dasboardHeader
  },
  props: ["settings"],
  data: function() {
    return {
      sending: false,
      baseUrl: baseUrl
    };
  },
  methods: {
    onSwitchStatus: function(event, type) {
      if (event.target.checked === false) {
        this.settings[type] = 0;
      } else {
        this.settings[type] = 1;
      }      
    },
    updateInfo: function() {
      let formData = this.$serializeData(this.settings);
      this.$axios
        .post(baseUrl + "/user/cookie-preferences", formData)
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
