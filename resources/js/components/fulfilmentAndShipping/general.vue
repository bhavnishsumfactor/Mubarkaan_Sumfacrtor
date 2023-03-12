<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_GENERAL') }}</h3>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <!--begin::Portlet-->
          <div class="portlet portlet-no-min-height">
            <div class="portlet__body pb-0" v-bind:class="[(!$canWrite('SHIPPING_FULFILMENT')) ? 'disabledbutton': '']">
               <div class="form-group row">
                <label
                  class="col-md-6 col-form-label"
                >{{ $t('LBL_ENABLE_COMMENTS_WHEN_SHIPPING_CHANGED') }}</label>
                <div class="col-md-6">
                  <div class="radio-inline">
                    <label class="radio">
                      <input
                        type="radio"
                        checked="checked"
                        :value="1"
                        v-model="adminsData.SHIPPING_MESSAGES_ENABLE"
                        name="SHIPPING_MESSAGES_ENABLE"
                      />
                      {{$t('LBL_YES')}}
                      <span></span>
                    </label>
                    <label class="radio">
                      <input
                        type="radio"
                        name="SHIPPING_MESSAGES_ENABLE"
                        :value="0"
                        v-model="adminsData.SHIPPING_MESSAGES_ENABLE"
                      />
                      {{$t('LBL_NO')}}
                      <span></span>
                    </label>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label
                  class="col-md-6 col-form-label"
                >{{ $t('LBL_ENABLE_COD') }}</label>
                <div class="col-md-6">
                  <div class="radio-inline">
                    <label class="radio">
                      <input
                        type="radio"
                        checked="checked"
                        :value="1"
                        v-model="adminsData.COD_ENABLE"
                        name="COD_ENABLE"
                      />
                      {{$t('LBL_YES')}}
                      <span></span>
                    </label>
                    <label class="radio">
                      <input
                        type="radio"
                        name="COD_ENABLE"
                        :value="0"
                        v-model="adminsData.COD_ENABLE"
                      />
                      {{$t('LBL_NO')}}
                      <span></span>
                    </label>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label
                  class="col-md-6 col-form-label"
                >{{ $t("LBL_ENABLE_PICKUP_METHOD") }}</label>
                <div class="col-md-6">
                  <div class="radio-inline">
                    <label class="radio">
                      <input
                        type="radio"
                        checked="checked"
                        :value="1"
                        v-model="adminsData.PICK_UP_ENABLE"
                        name="PICK_UP_ENABLE"
                      />
                      {{$t('LBL_YES')}}
                      <span></span>
                    </label>
                    <label class="radio">
                      <input
                        type="radio"
                        name="PICK_UP_ENABLE"
                        :value="0"
                        v-model="adminsData.PICK_UP_ENABLE"
                      />
                      {{$t('LBL_NO')}}
                      <span></span>
                    </label>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label
                  class="col-md-6 col-form-label"
                >{{ $t('LBL_WANT_RETURN_SHIPPING_CHARGES') }}</label>
                <div class="col-md-6">
                  <div class="radio-inline">
                    <label class="radio">
                      <input
                        type="radio"
                        checked="checked"
                        :value="1"
                        v-model="adminsData.RETURN_SHIPPING_ENABLE"
                        name="RETURN_SHIPPING_ENABLE"
                      />
                      {{$t('LBL_YES')}}
                      <span></span>
                    </label>
                    <label class="radio">
                      <input
                        type="radio"
                        name="RETURN_SHIPPING_ENABLE"
                        :value="0"
                        v-model="adminsData.RETURN_SHIPPING_ENABLE"
                      />
                      {{$t('LBL_NO')}}
                      <span></span>
                    </label>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label
                  class="col-md-6 col-form-label"
                >{{ $t('LBL_DISCOUNT_APPLIED_AFTER_TAX') }}</label>
                <div class="col-md-6">
                  <div class="radio-inline">
                    <label class="radio">
                      <input
                        type="radio"
                        checked="checked"
                        :value="1"
                        v-model="adminsData.IS_DISCOUNT_ON_TAX"
                        name="IS_DISCOUNT_ON_TAX"
                      />
                      {{$t('LBL_YES')}}
                      <span></span>
                    </label>
                    <label class="radio">
                      <input
                        type="radio"
                        name="IS_DISCOUNT_ON_TAX"
                        :value="0"
                        v-model="adminsData.IS_DISCOUNT_ON_TAX"
                      />
                      {{$t('LBL_NO')}}
                      <span></span>
                    </label>
                  </div>
                </div>
              </div>
                <div class="form-group row">
                    <label
                    class="col-md-6 col-form-label"
                    >{{ $t('LBL_ADMIN_MODERATION_FOR_CANCELLATION') }}</label>
                    <div class="col-md-6">
                    <div class="radio-inline">
                        <label class="radio">
                        <input
                            type="radio"
                            checked="checked"
                            :value="1"
                            v-model="adminsData.APPROVAL_ON_CANCELLATION"
                            name="APPROVAL_ON_CANCELLATION"
                        />
                        {{$t('LBL_YES')}}
                        <span></span>
                        </label>
                        <label class="radio">
                        <input
                            type="radio"
                            name="APPROVAL_ON_CANCELLATION"
                            :value="0"
                            v-model="adminsData.APPROVAL_ON_CANCELLATION"
                        />
                        {{$t('LBL_NO')}}
                        <span></span>
                        </label>
                    </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6 col-form-label">{{ $t('LBL_PICKUP_AVAILABLE_AFTER_PRODUCT_DELIVERED') }}</label>
                    <div class="col-md-6">
                        <div class="input-group  w-50">
                            <input type="text" v-model="adminsData.PICKUP_AVAILABLE" name="PICKUP_AVAILABLE" class="form-control" v-validate="'decimal:0|min_value:0'" :data-vv-as="$t('LBL_PICKUP_AVAILABLE_HOURS')"/>
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{$t('Hours')}}</span>
                            </div>
                        </div>
                        <span v-if="errors.first('PICKUP_AVAILABLE')" class="error text-danger">{{ errors.first('PICKUP_AVAILABLE') }}</span>
                    </div>
                </div>
            </div>
            <div class="portlet__foot text-center">
              <button
                type="submit"
                class="btn btn-wide btn-brand gb-btn gb-btn-primary"
                @click="updateSettings"
                :disabled="clicked==1 || !$canWrite('SHIPPING_FULFILMENT')"
                v-bind:class="clicked==1 && 'gb-is-loading'"
              >{{$t('BTN_SHIPPING_GENERAL_UPDATE')}}</button>
            </div>
          </div>
          <!--end::Portlet-->
        </div>
      </div>
    </div>
  </div>
</template>
<script>
const tableFields = {
    COD_ENABLE: 0,
    PICK_UP_ENABLE: 0,
    RETURN_SHIPPING_ENABLE: 0,
    APPROVAL_ON_CANCELLATION: 0,
    IS_DISCOUNT_ON_TAX: 0,
    IS_TAX_ON_SHIPPING: 0,
    SHIPPING_MESSAGES_ENABLE: 0,
    SHIPPING_TAX_CHARGES: "",
    PICKUP_AVAILABLE: ""
};
export default {
  data: function() {
    return {
      adminsData: tableFields,
      clicked: 0
    };
  },
  methods: {
    getSettings: function() {
      let formData = this.$serializeData({
        keys: [
          "COD_ENABLE",
          "PICK_UP_ENABLE",
          "RETURN_SHIPPING_ENABLE",
          "IS_DISCOUNT_ON_TAX",
          "IS_TAX_ON_SHIPPING",
          "APPROVAL_ON_CANCELLATION",
          "SHIPPING_MESSAGES_ENABLE",
          "SHIPPING_TAX_CHARGES",
          "PICKUP_AVAILABLE"
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
          this.units = response.data.data.units;
        });
    },
    updateSettings: function(input) {
        if(!this.$canWrite('SHIPPING_FULFILMENT')) {
            toastr.error('Unauthorized request', "", toastr.options);
            return;
        }
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
    }
  },
  mounted: function() {
    this.getSettings();
  }
};
</script>
