<template>
  <div class="body bg-dashboard" id="body" data-yk >
    <section class="section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-10">
            <div class="account-area">
              <dasboard-header></dasboard-header>
              <left-sidebar :pageType="'address'"></left-sidebar>
              <main class="main-content" data-yk role="yk-main-content">
                <div class="card">
                  <form class="form form-floating fluid-height">
                    <div class="card-body">
                      <div class="row justify-content-center">
                        <div class="col-lg-8">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group form-floating__group">
                                <input
                                  type="text"
                                  class="form-control form-floating__field"
                                  v-model="userData.addr_first_name"
                                  v-bind:class="[
                                    userData.addr_first_name != ''
                                      ? 'filled'
                                      : '',
                                  ]"
                                />
                                <label class="form-floating__label">
                                  {{
                                  $t("LBL_FIRST_NAME")
                                  }}
                                </label>
                                <span class="text-danger form-text" v-if="errors.addr_first_name != ''" > {{errors.addr_first_name}}</span>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group form-floating__group">
                                <input
                                  type="text"
                                  class="form-control form-floating__field"
                                  v-model="userData.addr_last_name"
                                  v-bind:class="[
                                    userData.addr_last_name != ''
                                      ? 'filled'
                                      : '',
                                  ]"
                                />
                                <label class="form-floating__label">
                                  {{
                                  $t("LBL_LAST_NAME")
                                  }}
                                </label>
                                <span class="text-danger form-text" v-if="errors.addr_last_name != ''" > {{errors.addr_last_name}}</span>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group form-floating__group">
                                <input
                                  type="text"
                                  class="form-control form-floating__field"
                                  v-model="userData.addr_title"
                                  v-bind:class="[
                                    userData.addr_title != '' ? 'filled' : '',
                                  ]"
                                />
                                <label class="form-floating__label">
                                  {{
                                  $t("LBL_ADDRESS_TYPE")
                                  }}
                                </label>
                                <span class="text-danger form-text" v-if="errors.addr_title != ''" > {{errors.addr_title}}</span>
                              </div>
                            </div>

                            <div class="col-md-6">
                              <div class="form-group form-floating__group">
                                <vue-tel-input
                                  v-model="userData.addr_phone"
                                  :defaultCountry="defaultCountryCode"
                                  :enabledCountryCode="true"
                                  @country-changed="changeCountry"
                                  @input="onPhoneNumberChange"
                                  inputClasses="form-control"
                                  :validCharactersOnly="true"
                                  :maxLen="15"
                                  :placeholder="$t('PLH_ENTER_PHONE_NUMBER')"
                                  v-if="defaultCountryCode"
                                ></vue-tel-input>
                                <span class="text-danger form-text" v-if="errors.addr_phone != ''" > {{errors.addr_phone}}</span>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group form-floating__group">
                                <input
                                  type="text"
                                  class="form-control form-floating__field"
                                  v-model="userData.addr_address1"
                                  v-bind:class="[
                                    userData.addr_address1 != ''
                                      ? 'filled'
                                      : '',
                                  ]"
                                />
                                <label class="form-floating__label">
                                  {{
                                  $t("LBL_ADDRESS_LINE1")
                                  }}
                                </label>
                                <span class="text-danger form-text" v-if="errors.addr_address1 != ''" > {{errors.addr_address1}}</span>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group form-floating__group">
                                <input
                                  type="text"
                                  class="form-control form-floating__field"
                                  v-model="userData.addr_address2"
                                  v-bind:class="[
                                    userData.addr_address2 != ''
                                      ? 'filled'
                                      : '',
                                  ]"
                                />
                                <label class="form-floating__label">
                                  {{
                                  $t("LBL_ADDRESS_LINE2")
                                  }}
                                </label>
                                <span class="text-danger form-text" v-if="errors.addr_address2 != ''" > {{errors.addr_address2}}</span>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group form-floating__group">
                                <input
                                  type="text"
                                  class="form-control form-floating__field"
                                  v-model="userData.addr_city"
                                  v-bind:class="[
                                    userData.addr_city != '' ? 'filled' : '',
                                  ]"
                                />
                                <label class="form-floating__label">
                                  {{
                                  $t("LBL_CITY")
                                  }}
                                </label>
                                <span class="text-danger form-text" v-if="errors.addr_city != ''" > {{errors.addr_city}}</span>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group form-floating__group">
                                <select
                                  class="form-control form-floating__field filled"
                                  v-model="userData.addr_country_id"
                                  @change="getStates()"
                                >
                                  <option disabled value>{{ $t("LBL_SELECT_COUNTRY") }}</option>
                                  <option
                                    v-for="(country, cKey) in countries"
                                    :key="cKey"
                                    :value="country.country_id"
                                  >{{ country.country_name }}</option>
                                </select>
                                <label class="form-floating__label">
                                  {{
                                  $t("LBL_COUNTRY")
                                  }}
                                </label>
                                <span class="text-danger form-text" v-if="errors.addr_country_id != ''" > {{errors.addr_country_id}}</span>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group form-floating__group">
                                <select
                                  class="form-control form-floating__field filled"
                                  v-model="userData.addr_state_id"
                                >
                                  <option disabled selected value>{{ $t("LBL_SELECT_STATE") }}</option>
                                  <option
                                    v-for="(state, sKey) in catStates"
                                    :key="sKey"
                                    :value="sKey"
                                  >{{ state }}</option>
                                </select>
                                <label class="form-floating__label">
                                  {{
                                  $t("LBL_STATE")
                                  }}
                                </label>
                                <span class="text-danger form-text" v-if="errors.addr_state_id != ''" > {{errors.addr_state_id}}</span>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group form-floating__group">
                                <input type="text" class="form-control form-floating__field" v-model="userData.addr_postal_code" v-bind:class="[userData.addr_postal_code != '' ? 'filled': '']"/>
                                <label class="form-floating__label">
                                  {{ $t("LBL_POSTAL_CODE") }}
                                </label>
                                <span class="text-danger form-text" v-if="errors.addr_postal_code != ''" > {{errors.addr_postal_code}}</span>
                              </div>
                            </div>
                          </div>
                          <p class="default">
                            <label class="checkbox">
                              <input
                                type="checkbox"
                                value="1"
                                v-model="userData.addr_billing_default"
                              />
                              <i class="input-helper"></i>
                              <span>{{ $t("LBL_SET_BILLING_DEFAULT") }}</span>
                            </label>
                          </p>
                          <p class="default">
                            <label class="checkbox">
                              <input
                                type="checkbox"
                                value="1"
                                v-model="userData.addr_shipping_default"
                              />
                              <i class="input-helper"></i>
                              <span>{{ $t("LBL_SET_SHIPPING_DEFAULT") }}</span>
                            </label>
                          </p>
                        </div>
                      </div>
                    </div>
                  </form>
                  <div class="card-footer">
                    <div class="row justify-content-center">
                      <div class="col-lg-8">
                        <div class="footer-actions">
                          <inertia-link
                            class="btn btn-outline-gray btn-wide"
                            :href="baseUrl + '/user/address'"
                          >{{ $t("BTN_DISCARD") }}</inertia-link>

                          <button
                            class="btn btn-brand btn-wide gb-btn gb-btn-primary"
                            type="button"
                            v-bind:class="[
                            sending == true ? 'gb-is-loading' : '',isComplete == true ? 'disabled': ''
                          ]"
                            :disabled="isComplete"
                            @click="addressInfo(), (sending = true)"
                          >
                            {{
                            editData
                            ? $t("BTN_UPDATE_ADDRESS")
                            : $t("BTN_SAVE_ADDRESS")
                            }}
                          </button>
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
  addr_id: "",
  addr_first_name: "",
  addr_phone: "",
  addr_last_name: "",
  addr_title: "",
  addr_address1: "",
  addr_address2: "",
  addr_city: "",
  addr_country_id: "",
  addr_state_id: "",
  addr_postal_code: "",
  addr_billing_default: 0,
  addr_shipping_default: 0
};
export default {
  components: {
    leftSidebar: leftSidebar,
    dasboardHeader: dasboardHeader
  },
  props: [
    "countries",
    "states",
    "defaultCountryId",
    "editData",
    "defaultCountry",
    "errors"
  ],
  data: function() {
    return {
      sending: false,
      baseUrl: baseUrl,
      defaultCountryCode: "",
      userData: tableFields,
      catStates: []
    };
  },
  methods: {
    onPhoneNumberChange: function(data, obj) {
      this.defaultCountryCode = obj.country.iso2;
      this.userData.addr_phone = obj.number.significant;
    },
    changeCountry: function(data) {
      this.defaultCountryCode = data.iso2;
    },
    getStates: function() {
      this.$axios
        .post(baseUrl + "/checkout/get-states", {
          "country-id": this.userData.addr_country_id
        })
        .then(response => {
          this.catStates = response.data.data;
          this.userData.addr_state_id = "";
        });
    },
    addressInfo: function() {
      let formData = this.$serializeData(this.userData);
      formData.append("addr_phone_country_id", this.defaultCountryCode);
      this.$inertia.post(baseUrl + "/user/address", formData, {
        onFinish: () => (this.sending = false)
      });
    },
    emptyFormValues: function() {
      this.userData = {
        addr_id: "",
        addr_first_name: "",
        addr_phone: "",
        addr_last_name: "",
        addr_title: "",
        addr_address1: "",
        addr_address2: "",
        addr_city: "",
        addr_country_id: "",
        addr_state_id: "",
        addr_postal_code: "",
        addr_billing_default: 0,
        addr_shipping_default: 0
      };
    }
  },
  mounted: function() {
    if (this.editData) {
      this.catStates = this.states;
      this.userData = this.editData;
      this.defaultCountryCode = this.editData.phonecountry.country_code;
      return false;
    }
    this.defaultCountryCode = this.defaultCountry;
    this.userData.addr_country_id = this.defaultCountryId;
    this.emptyFormValues();
  },
  computed: {
    isComplete() {
      return (
        this.userData.addr_first_name == "" ||
        this.userData.addr_last_name == "" ||
        this.userData.addr_phone == "" ||
        this.userData.addr_title == "" ||
        this.userData.addr_address1 == "" ||
        this.userData.addr_city == "" ||
        this.userData.addr_country_id == "" ||
        this.userData.addr_state_id == "" ||
        this.userData.addr_postal_code == ""
      );
    }
  }
};
</script>