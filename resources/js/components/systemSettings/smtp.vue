<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_SYSTEM_SETTINGS') }}</h3>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="grid-layout">
        <!--Begin:: App Aside Mobile Toggle-->
        <button class="grid-layout-close" id="user_profile_aside_close">
          <i class="la la-close"></i>
        </button>
        <!--End:: App Aside Mobile Toggle-->

        <sidebar :tab="type"></sidebar>

        <!--Begin:: App Content-->
        <div class="grid-layout-content">
          <div class="portlet portlet--height-fluid">
            <div class="portlet__body">
              <div class="section">
                <div class="section__body">
                  <div class="row justify-content-center">
                    <div class="col-xl-8">
                      <div class="form-group row">
                        <label
                          class="col-lg-4 col-form-label"
                        >{{ $t('LBL_ENABLE_EMAIL_VERIFICATION') }}</label>
                        <div class="col-lg-8">
                          <div class="radio-inline">
                            <label class="radio">
                              <input
                                type="radio"
                                checked="checked"
                                :value="1"
                                v-model="adminsData.VERIFICATION_EMAIL_STATUS"
                                name="VERIFICATION_EMAIL_STATUS"
                              />
                              {{$t('LBL_YES')}}
                              <span></span>
                            </label>
                            <label class="radio">
                              <input
                                type="radio"
                                name="VERIFICATION_EMAIL_STATUS"
                                :value="0"
                                @change="changeStatus"
                                v-model="adminsData.VERIFICATION_EMAIL_STATUS"
                              />
                              {{$t('LBL_NO')}}
                              <span></span>
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-4 col-form-label">
                          {{ $t('LBL_FROM_EMAIL') }}
                          <span class="required">*</span>
                        </label>
                        <div class="col-lg-8">
                          <input
                            type="text"
                            v-model="adminsData.FROM_EMAIL"
                            name="from_email"
                            v-validate="adminsData.VERIFICATION_EMAIL_STATUS == 1 ? 'required' : ''"
                            :data-vv-as="$t('LBL_FROM_EMAIL')"
                            :disabled="adminsData.VERIFICATION_EMAIL_STATUS == 0"
                            data-vv-validate-on="none"
                            class="form-control"
                          />
                          <span
                            v-if="errors.first('from_email')"
                            class="error text-danger"
                          >{{ errors.first('from_email') }}</span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-4 col-form-label">
                          {{ $t('LBL_REPLY_TO_EMAIL') }}
                          <span class="required">*</span>
                        </label>
                        <div class="col-lg-8">
                          <input
                            type="text"
                            v-model="adminsData.REPLY_TO_EMAIL"
                            :disabled="adminsData.VERIFICATION_EMAIL_STATUS == 0"
                            name="reply_to_email"
                            v-validate="adminsData.VERIFICATION_EMAIL_STATUS == 1 ? 'required' : ''"
                            :data-vv-as="$t('LBL_REPLY_TO_EMAIL')"
                            data-vv-validate-on="none"
                            class="form-control"
                          />
                          <span
                            v-if="errors.first('reply_to_email')"
                            class="error text-danger"
                          >{{ errors.first('reply_to_email') }}</span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-4 col-form-label">{{ $t('LBL_ENABLE_SMTP') }}</label>
                        <div class="col-lg-8">
                          <div class="radio-inline">
                            <label class="radio">
                              <input
                                type="radio"
                                name="adminsData.send_smtp_email"
                                :disabled="adminsData.VERIFICATION_EMAIL_STATUS == 0"
                                value="1"
                                v-model="adminsData.SEND_SMTP_EMAIL"
                              />
                              {{$t('LBL_YES')}}
                              <span></span>
                            </label>
                            <label class="radio">
                              <input
                                type="radio"
                                name="adminsData.send_smtp_email"
                                :disabled="adminsData.VERIFICATION_EMAIL_STATUS == 0"
                                value="0"
                                v-model="adminsData.SEND_SMTP_EMAIL"
                              />
                              {{$t('LBL_NO')}}
                              <span></span>
                            </label>
                          </div>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-lg-4 col-form-label">
                          {{ $t('LBL_SMTP_HOST') }}
                          <span class="required">*</span>
                        </label>
                        <div class="col-lg-8">
                          <input
                            type="text"
                            v-model="adminsData.SMTP_HOST"
                            :disabled="adminsData.VERIFICATION_EMAIL_STATUS == 0 || adminsData.SEND_SMTP_EMAIL == 0"
                            name="smtp_host"
                            v-validate="'required'"
                            :data-vv-as="$t('LBL_SMTP_HOST')"
                            data-vv-validate-on="none"
                            class="form-control"
                          />
                          <span
                            v-if="errors.first('smtp_host')"
                            class="error text-danger"
                          >{{ errors.first('smtp_host') }}</span>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-lg-4 col-form-label">
                          {{ $t('LBL_SMTP_PORT') }}
                          <span class="required">*</span>
                        </label>
                        <div class="col-lg-8">
                          <input
                            type="number"
                            v-model="adminsData.SMTP_PORT"
                            :disabled="adminsData.VERIFICATION_EMAIL_STATUS == 0 || adminsData.SEND_SMTP_EMAIL == 0"
                            name="smtp_port"
                            v-validate="'required'"
                            :data-vv-as="$t('LBL_SMTP_PORT')"
                            data-vv-validate-on="none"
                            class="form-control"
                          />
                          <span
                            v-if="errors.first('smtp_port')"
                            class="error text-danger"
                          >{{ errors.first('smtp_port') }}</span>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-lg-4 col-form-label">
                          {{ $t('LBL_SMTP_USERNAME') }}
                          <span class="required">*</span>
                        </label>
                        <div class="col-lg-8">
                          <input
                            type="text"
                            v-model="adminsData.SMTP_USERNAME"
                            :disabled="adminsData.VERIFICATION_EMAIL_STATUS == 0 || adminsData.SEND_SMTP_EMAIL == 0"
                            name="smtp_username"
                            v-validate="'required'"
                            :data-vv-as="$t('LBL_SMTP_USERNAME')"
                            data-vv-validate-on="none"
                            class="form-control"
                          />
                          <span
                            v-if="errors.first('smtp_username')"
                            class="error text-danger"
                          >{{ errors.first('smtp_username') }}</span>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-lg-4 col-form-label">
                          {{ $t('LBL_SMTP_PASSWORD') }}
                          <span class="required">*</span>
                        </label>
                        <div class="col-lg-8">
                          <div class="input-group">
                            <input
                              :type="passwordFieldType"
                              v-model="adminsData.SMTP_PASSWORD"
                              :disabled="adminsData.VERIFICATION_EMAIL_STATUS == 0 || adminsData.SEND_SMTP_EMAIL == 0"
                              name="smtp_password"
                              v-validate="'required'"
                              :data-vv-as="$t('LBL_SMTP_PASSWORD')"
                              data-vv-validate-on="none"
                              class="form-control"
                            />
                            <div class="input-group-append">
                              <span class="input-group-text" @click="switchVisibility">
                                <i
                                  class="far fa-eye password-toggleX"
                                  v-bind:class="[passwordFieldType =='password' ? '' : 'fa-eye-slash']"
                                ></i>
                              </span>
                            </div>
                          </div>
                          <span
                            v-if="errors.first('smtp_password')"
                            class="error text-danger"
                          >{{ errors.first('smtp_password') }}</span>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-lg-4 col-form-label">{{ $t('LBL_SMTP_SECURE') }}</label>
                        <div class="col-lg-8">
                          <div class="radio-inline">
                            <label class="radio">
                              <input
                                type="radio"
                                name="adminsData.smtp_secure"
                                :disabled="adminsData.VERIFICATION_EMAIL_STATUS == 0 || adminsData.SEND_SMTP_EMAIL == 0"
                                value="tls"
                                v-model="adminsData.SMTP_SECURE"
                              />
                              {{$t('LBL_TLS')}}
                              <span></span>
                            </label>
                            <label class="radio">
                              <input
                                type="radio"
                                name="adminsData.smtp_secure"
                                :disabled="adminsData.VERIFICATION_EMAIL_STATUS == 0 || adminsData.SEND_SMTP_EMAIL == 0"
                                value="ssl"
                                v-model="adminsData.SMTP_SECURE"
                              />
                              {{$t('LBL_SSL')}}
                              <span></span>
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-4 col-form-label">{{ $t('LBL_ADDITIONAL_ALERT_EMAILS') }}</label>
                        <div class="col-lg-8">
                          <div class="radio-inline">
                            <textarea
                              class="form-control"
                              v-model="adminsData.ADDITIONAL_EMAIL_ALERTS"
                              :disabled="adminsData.VERIFICATION_EMAIL_STATUS == 0 "
                            ></textarea>
                            <span
                              class="form-text text-muted"
                            >{{$t('LBL_ADDITIONAL_EMAILS_INFO')}}. ({{$t('LBL_COMMA_SEPARATED')}}).</span>
                          </div>
                        </div>
                      </div>

                      <div class="form-group row">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-8">
                          <a
                            href="javascript:void(0);"
                            @click="verifyEmail($event)"
                            class="btn btn-secondary"
                            v-bind:class="verifyClicked==1 && 'disabled gb-is-loading'"
                            :disabled="verifyClicked==1 || adminsData.VERIFICATION_EMAIL_STATUS == 0 || adminsData.SEND_SMTP_EMAIL == 0"
                          >
                            <svg
                              class="btn__svg"
                              xmlns="http://www.w3.org/2000/svg"
                              viewBox="0 0 512 512"
                              width="16px"
                              height="16px"
                            >
                              <path
                                fill="currentColor"
                                d="M440 6.5L24 246.4c-34.4 19.9-31.1 70.8 5.7 85.9L144 379.6V464c0 46.4 59.2 65.5 86.6 28.6l43.8-59.1 111.9 46.2c5.9 2.4 12.1 3.6 18.3 3.6 8.2 0 16.3-2.1 23.6-6.2 12.8-7.2 21.6-20 23.9-34.5l59.4-387.2c6.1-40.1-36.9-68.8-71.5-48.9zM192 464v-64.6l36.6 15.1L192 464zm212.6-28.7l-153.8-63.5L391 169.5c10.7-15.5-9.5-33.5-23.7-21.2L155.8 332.6 48 288 464 48l-59.4 387.3z"
                              />
                            </svg>
                            {{ $t('BTN_SEND_TEST_EMAIL') }}
                          </a>
                          <span
                            class="form-text text-muted"
                          >{{$t('LBL_TEST_EMAIL_INFO')}}.</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="portlet__foot">
              <div class="form__actions text-center">
                <button
                  type="submit"
                  class="btn btn-brand btn-wide gb-btn gb-btn-primary"
                  @click="updateSettings"
                  :disabled="clicked==1 || adminsData.VERIFICATION_EMAIL_STATUS == 0 || isComplete"
                  v-bind:class="clicked==1 && 'gb-is-loading'"
                >{{$t('BTN_SETTINGS_UPDATE')}}</button>
              </div>
            </div>
          </div>
        </div>
        <!--End:: App Content-->
      </div>
    </div>
  </div>
</template>
<script>
import sidebar from "./sidebar";
const tableFields = {
  FROM_EMAIL: "",
  REPLY_TO_EMAIL: "",
  SMTP_HOST: "",
  SMTP_PORT: "",
  SMTP_USERNAME: "",
  SMTP_PASSWORD: "",
  SMTP_SECURE: "",
  SEND_SMTP_EMAIL: "",
  VERIFICATION_EMAIL_STATUS: "",
  ADDITIONAL_EMAIL_ALERTS: ""
};
export default {
  components: {
    sidebar: sidebar
  },
  data: function() {
    return {
      adminsData: tableFields,
      type: "smtp",
      verifyClicked: 0,
      passwordFieldType: "password",
      clicked: 0
    };
  },
  methods: {
    switchVisibility() {
      this.passwordFieldType =
        this.passwordFieldType === "password" ? "text" : "password";
    },
    getSettings: function() {
      let formData = this.$serializeData({
        keys: [
          "FROM_EMAIL",
          "REPLY_TO_EMAIL",
          "SMTP_HOST",
          "SMTP_PORT",
          "SMTP_USERNAME",
          "SMTP_PASSWORD",
          "SMTP_SECURE",
          "SEND_SMTP_EMAIL",
          "VERIFICATION_EMAIL_STATUS",
          "ADDITIONAL_EMAIL_ALERTS"
        ]
      });

      this.$http
        .post(adminBaseUrl + "/settings/get", formData)
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          this.adminsData = response.data.data;
        });
    },
    updateSettings: function(input) {
      this.$validator.validateAll().then(res => {
        if (res) {
          this.clicked = 1;
          let formData = this.$serializeData(this.adminsData);
          this.$http
            .post(adminBaseUrl + "/settings/basedonkeys", formData)
            .then(
              response => {
                //success
                this.clicked = 0;
                if (response.data.status == false) {
                  toastr.error(response.data.message, "", toastr.options);
                  return;
                }
                toastr.success(response.data.message, "", toastr.options);
              },
              response => {
                //error
                this.clicked = 0;
                this.validateErrors(response);
              }
            );
        } else {
          this.clicked = 0;
        }
      });
    },
    validateErrors: function(response) {
      let jsondata = response.data;
      Object.keys(jsondata.errors).forEach(key => {
        this.errors.add({
          field: key,
          msg: jsondata.errors[key][0]
        });
      });
    },
    verifyEmail: function(event) {
      this.verifyClicked = 1;
      this.$http.get(baseUrl + "/testEmail/").then(response => {
        this.verifyClicked = 0;
        if (response.data.status == false) {
          toastr.error(response.data.message, "", toastr.options);
          return;
        }
        toastr.success(response.data.message, "", toastr.options);
      });
    },
    changeStatus:function() {
        let formData = this.$serializeData({VERIFICATION_EMAIL_STATUS :this.adminsData.VERIFICATION_EMAIL_STATUS});
        this.$http
        .post(adminBaseUrl + "/settings/basedonkeys", formData)
        .then(
            response => {
            //success
            this.clicked = 0;
            if (response.data.status == false) {
                toastr.error(response.data.message, "", toastr.options);
                return;
            }
            toastr.success(response.data.message, "", toastr.options);
            },
            response => {
            //error
            this.clicked = 0;
            this.validateErrors(response);
            }
        );
    }
  },
  mounted: function() {
    this.getSettings();
  },
  computed: {
    isComplete() {
      return (
        this.adminsData.FROM_EMAIL == "" ||
        this.adminsData.REPLY_TO_EMAIL == "" ||
        this.adminsData.SMTP_HOST == "" ||
        this.adminsData.SMTP_PORT == "" ||
        this.adminsData.SMTP_USERNAME == "" ||
        this.adminsData.SMTP_PASSWORD == ""
      );
    }
  }
};
</script>
