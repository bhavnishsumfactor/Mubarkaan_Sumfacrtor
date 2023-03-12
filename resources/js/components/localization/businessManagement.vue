<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_BUSINESS_INFORMATION') }}</h3>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <form v-on:submit.prevent="updateSettings">
            <div class="portlet portlet--height-fluid">
              <div class="portlet__body">
                <div class="section">
                  <div class="section__body">
                    <div class="row justify-content-center">
                      <div class="col-md-9">
                        <div class="form-group row">
                          <label class="col-lg-3 col-form-label">
                            {{ $t('LBL_BUSINESS_NAME') }}
                            <span class="required">*</span>
                          </label>
                          <div class="col-lg-9">
                            <input
                              type="text"
                              v-model="adminsData.BUSINESS_NAME"
                              name="name"
                              v-validate="'required'"
                              :data-vv-as="$t('LBL_BUSINESS_NAME')"
                              data-vv-validate-on="none"
                              class="form-control"
                            />
                            <span
                              v-if="errors.first('name')"
                              class="error text-danger"
                            >{{ errors.first('name') }}</span>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-lg-3 col-form-label">
                            {{ $t('LBL_BUSINESS_EMAIL') }}
                            <span class="required">*</span>
                          </label>
                          <div class="col-lg-9">
                            <input
                              type="text"
                              v-model="adminsData.BUSINESS_EMAIL"
                              v-validate="'required'"
                              name="email"
                              :data-vv-as="$t('LBL_BUSINESS_EMAIL')"
                              data-vv-validate-on="none"
                              class="form-control"
                            />
                            <span
                              v-if="errors.first('email')"
                              class="error text-danger"
                            >{{ errors.first('email') }}</span>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-lg-3 col-form-label">
                            {{ $t('LBL_ADDRESS_LINE1') }}
                            <span class="required">*</span>
                          </label>
                          <div class="col-lg-9">
                            <input
                              type="text"
                              v-model="adminsData.BUSINESS_ADDRESS1"
                              data-vv-validate-on="none"
                              class="form-control"
                            />
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-lg-3 col-form-label">{{ $t('LBL_ADDRESS_LINE2') }}</label>
                          <div class="col-lg-9">
                            <input
                              type="text"
                              v-model="adminsData.BUSINESS_ADDRESS2"
                              data-vv-validate-on="none"
                              class="form-control"
                            />
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-lg-3 col-form-label">{{ $t('Phone Number') }}</label>
                          <div class="col-lg-9">
                            <vue-tel-input
                              v-if="defaultCountryCode"
                              v-model="adminsData.BUSINESS_PHONE_NUMBER"
                              :defaultCountry="defaultCountryCode"
                              :enabledCountryCode="true"
                              @country-changed="changeCountry"
                              @input="onPhoneNumberChange"
                              inputClasses="form-control"
                              :validCharactersOnly="true"
                              :placeholder="$t('PLH_ENTER_PHONE_NUMBER')"
                            ></vue-tel-input>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-lg-3 col-form-label">
                            {{ $t('LBL_COUNTRY') }}
                            <span class="required">*</span>
                          </label>
                          <div class="col-lg-9">
                            <select
                              v-model="adminsData.BUSINESS_COUNTRY"
                              @change="getStates()"
                              data-vv-validate-on="none"
                              class="form-control"
                            >
                              <option value disabled>{{ $t('LBL_SELECT_COUNTRY')}}</option>
                              <option
                                v-for="(country, cindex) in countries"
                                :value="country.country_id"
                              >{{country.country_name}}</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-lg-3 col-form-label">
                            {{ $t('LBL_STATE') }}
                            <span class="required">*</span>
                          </label>
                          <div class="col-lg-9">
                            <select v-model="adminsData.BUSINESS_STATE" class="form-control">
                              <option value disabled>{{ $t('LBL_SELECT_STATE')}}</option>
                              <option v-for="(state, sindex) in states" :value="sindex">{{state}}</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-lg-3 col-form-label">
                            {{ $t('LBL_CITY') }}
                            <span class="required">*</span>
                          </label>
                          <div class="col-lg-9">
                            <input
                              type="text"
                              v-model="adminsData.BUSINESS_CITY"
                              data-vv-validate-on="none"
                              class="form-control"
                            />
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-lg-3 col-form-label">
                            {{ $t('LBL_POSTAL_CODE') }}
                            <span class="required">*</span>
                          </label>
                          <div class="col-lg-9">
                            <input
                              type="number"
                              v-model="adminsData.BUSINESS_PINCODE"
                              data-vv-validate-on="none"
                              class="form-control"
                            />
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
                    class="btn btn-wide btn-brand gb-btn gb-btn-primary"
                    :disabled="clicked==1 || isComplete"
                    v-bind:class="clicked==1 && 'gb-is-loading'"
                  >{{$t('BTN_UPDATE_BUSINESS_INFO')}}</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
 <style scoped>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type="number"] {
  -moz-appearance: textfield;
}
</style>
<script>
const tableFields = {
  BUSINESS_NAME: "",
  BUSINESS_EMAIL: "",
  BUSINESS_ADDRESS1: "",
  BUSINESS_ADDRESS2: "",
  BUSINESS_CITY: "",
  BUSINESS_STATE: "",
  BUSINESS_COUNTRY: "",
  BUSINESS_PINCODE: "",
  BUSINESS_PHONE_COUNTRY_CODE: "",
  BUSINESS_PHONE_NUMBER: ""
};
export default {
  data: function() {
    return {
      adminsData: tableFields,
      type: "businessManagement",
      verifyClicked: 0,
      clicked: 0,
      countries: [],
      states: [],
      defaultCountryCode: "",
      countryCode: "",
      apiResponse: false
    };
  },
  methods: {
    getSettings: function() {
      let formData = this.$serializeData({
        keys: [
          "BUSINESS_NAME",
          "BUSINESS_EMAIL",
          "BUSINESS_ADDRESS1",
          "BUSINESS_ADDRESS2",
          "BUSINESS_CITY",
          "BUSINESS_STATE",
          "BUSINESS_COUNTRY",
          "BUSINESS_PINCODE",
          "BUSINESS_PHONE_COUNTRY_CODE",
          "BUSINESS_PHONE_NUMBER"
        ]
      });

      /* this.$http.post(adminBaseUrl + '/settings/get', formData).then((response) => {
                if (response.data.status == false) {
                    toastr.error(response.data.message, '', toastr.options);
                    return;
                }
                this.adminsData = response.data.data;
                
                this.apiResponse = true;
            });*/

      this.$http
        .get(adminBaseUrl + "/setting/get-business-settings")
        .then(response => {
          if (response.data.status == false) {
            toastr.error(response.data.message, "", toastr.options);
            return;
          }
          this.adminsData = response.data.data.bussniessInfo;
          this.defaultCountryCode = response.data.data.phoneCode;
          this.states = response.data.data.states;
          this.countries = response.data.data.countries;
        });
    },
    getStates: function() {
      let formData = this.$serializeData({
        "coutry-id": this.adminsData.BUSINESS_COUNTRY
      });
      this.$http
        .post(adminBaseUrl + "/store-address/get-states", formData)
        .then(response => {
          this.states = response.data.data;
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
    changeCountry: function(data) {
      this.countryCode = data.iso2;
      this.adminsData.BUSINESS_PHONE_COUNTRY_CODE = this.countryCode;
    },
    onPhoneNumberChange: function(data, obj) {
      this.countryCode = obj.country.iso2;
      this.adminsData.BUSINESS_PHONE_NUMBER = obj.number.significant;
      this.adminsData.BUSINESS_PHONE_COUNTRY_CODE = this.countryCode;
    }
  },
  mounted: function() {
    this.getSettings();
  },
  computed: {
    isComplete() {
      return (
        this.adminsData.BUSINESS_NAME == "" ||
        this.adminsData.BUSINESS_EMAIL == "" ||
        this.adminsData.BUSINESS_ADDRESS1 == "" ||
        this.adminsData.BUSINESS_COUNTRY == "" ||
        this.adminsData.BUSINESS_STATE == "" ||
        this.adminsData.BUSINESS_CITY == "" ||
        this.adminsData.BUSINESS_PINCODE == "" ||
        this.adminsData.BUSINESS_PHONE_NUMBER == ""
      );
    }
  }
};
</script>
