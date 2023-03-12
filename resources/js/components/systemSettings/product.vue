<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_PRODUCT_SETTINGS') }}</h3>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <!--begin::Portlet-->
          <div class="portlet portlet--height-fluid">
            <div class="portlet__body pb-0">
                <div class="form-group row">
                    <label class="col-md-6">{{ $t('LBL_PLATFORM_HAS_PRODUCT_TYPES') }}</label>
                    <div class="col-md-6">
                        <div class="radio-inline">
                            <label class="radio">
                            <input
                                type="radio"
                                :value="1"
                                @click="enableProductType($event)"
                                :checked="adminsData.PRODUCT_TYPES==1"
                                name="PRODUCT_TYPES"
                            />
                            {{$t('LBL_PHYSICAL')}}
                            <span></span>
                            </label>
                            <label class="radio">
                            <input
                                type="radio"
                                :value="2"
                                @click="enableProductType($event)"
                                :checked="adminsData.PRODUCT_TYPES==2"
                                name="PRODUCT_TYPES"
                            />
                            {{$t('LBL_DIGITAL')}}
                            <span></span>
                            </label>
                            <label class="radio">
                            <input
                                type="radio"
                                :value="3"
                                @click="enableProductType($event)"
                                :checked="adminsData.PRODUCT_TYPES==3"
                                name="PRODUCT_TYPES"
                            />
                            {{$t('LBL_BOTH_TYPE')}}
                            <span></span>
                            </label>
                        </div>
                    </div>
                </div>
                <!--<div class="form-group row">
                    <label class="col-md-6">{{ $t('LBL_ASPECT_RATIO_FOR_PRODUCT_IMAGES') }}</label>
                    <div class="col-md-6">
                        <div class="radio-inline">
                            <label class="radio">
                            <input
                                type="radio"
                                :value="1"
                                :checked="adminsData.MEDIA_TYPES==1"
                                v-model="adminsData.MEDIA_TYPES"
                                name="MEDIA_TYPES"
                            />
                            {{$t('1:1')}}
                            <span></span>
                            </label>
                            <label class="radio">
                            <input
                                type="radio"
                                :value="2"
                                v-model="adminsData.MEDIA_TYPES"
                                :checked="adminsData.MEDIA_TYPES==2"
                                name="MEDIA_TYPES"
                            />
                            {{$t('16:9')}}
                            <span></span>
                            </label>
                            <label class="radio">
                            <input
                                type="radio"
                                :value="3"
                                :checked="adminsData.MEDIA_TYPES==3"
                                v-model="adminsData.MEDIA_TYPES"
                                name="MEDIA_TYPES"
                            />
                            {{$t('3:4')}}
                            <span></span>
                            </label>
                           
                        </div>
                    </div>
                </div>-->
                <div class="form-group row">
                    <label class="col-md-6">{{ $t("LBL_ALLOW_DECIMAL_VALUES") }}</label>
                    <div class="col-md-6">
                        <div class="radio-inline">
                            <label class="radio">
                            <input
                                type="radio"
                                checked="checked"
                                :value="1"
                                v-model="adminsData.DECIMAL_VALUES"
                                name="DECIMAL_VALUES"
                            />
                            {{$t('LBL_YES')}}
                            <span></span>
                            </label>
                            <label class="radio">
                            <input
                                type="radio"
                                name="DECIMAL_VALUES"
                                :value="0"
                                v-model="adminsData.DECIMAL_VALUES"
                            />
                            {{$t('LBL_NO')}}
                            <span></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6">{{ $t("LBL_WILL_PRICE_INCLUDE_TAX") }}</label>
                    <div class="col-md-6">
                        <div class="radio-inline">
                            <label class="radio">
                            <input
                                type="radio"
                                checked="checked"
                                :value="1"
                                v-model="adminsData.PRICE_INCLUDING_TAX"
                                name="PRICE_INCLUDING_TAX"
                            />
                            {{$t('LBL_YES')}}
                            <span></span>
                            </label>
                            <label class="radio">
                            <input
                                type="radio"
                                name="PRICE_INCLUDING_TAX"
                                :value="0"
                                v-model="adminsData.PRICE_INCLUDING_TAX"
                            />
                            {{$t('LBL_NO')}}
                            <span></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6">{{ $t('LBL_BRANDS_OPTIONAL_FOR_PRODUCTS') }}</label>
                    <div class="col-md-6">
                    <div class="radio-inline">
                        <label class="radio">
                        <input
                            type="radio"
                            checked="checked"
                            :value="1"
                            v-model="adminsData.BRAND_IS_OPTIONAL"
                            name="BRAND_IS_OPTIONAL"
                        />
                        {{$t('LBL_YES')}}
                        <span></span>
                        </label>
                        <label class="radio">
                        <input
                            type="radio"
                            name="BRAND_IS_OPTIONAL"
                            :value="0"
                            v-model="adminsData.BRAND_IS_OPTIONAL"
                        />
                        {{$t('LBL_NO')}}
                        <span></span>
                        </label>
                    </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-6">{{ $t('LBL_ENABLE_GIFT_WRAPPING') }}</label>
                    <div class="col-md-6">
                        <div class="radio-inline">
                            <label class="radio">
                            <input
                                type="radio"
                                checked="checked"
                                :value="1"
                                v-model="adminsData.PRODUCT_GIFT_WRAP_ENABLE"
                                name="PRODUCT_GIFT_WRAP_ENABLE"
                            />
                            {{$t('LBL_YES')}}
                            <span></span>
                            </label>
                            <label class="radio">
                            <input
                                type="radio"
                                name="PRODUCT_GIFT_WRAP_ENABLE"
                                :value="0"
                                v-model="adminsData.PRODUCT_GIFT_WRAP_ENABLE"
                            />
                            {{$t('LBL_NO')}}
                            <span></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6 col-form-label">{{ $t('LBL_GIFT_WRAPPING_CHARGES') }}</label>
                    <div class="col-md-6">
                        <div class="input-group  w-50">
                            <div class="input-group-prepend">
                              <span class="input-group-text">{{ currencySymbol }}</span>
                            </div>
                            <input
                                type="text"
                                v-model="adminsData.PRODUCT_GIFT_WRAP_PRICE"
                                name="PRODUCT_GIFT_WRAP_PRICE"
                                class="form-control"
                                :data-vv-as="$t('LBL_GIFT_WRAPPING_PRICE')"
                                data-vv-validate-on="none" :disabled="adminsData.PRODUCT_GIFT_WRAP_ENABLE != 1"
                                v-validate="'decimal:0|min_value:0'"
                            />
                        </div>
                    <span
                        v-if="errors.first('PRODUCT_GIFT_WRAP_PRICE')"
                        class="error text-danger"
                    >{{ errors.first('PRODUCT_GIFT_WRAP_PRICE') }}</span>
                    </div>
                </div>
              <!--<div class="form-group row">
                <label
                  class="col-md-6"
                >{{ $t('LBL_LINK_BLOGS_WITH_PRODUCTS') }}</label>
                <div class="col-md-6">
                    <div class="radio-inline">
                        <label class="radio">
                            <input type="radio" checked="checked" :value="1" v-model="adminsData.LINK_BLOG_WITH_PRODUCTS" name="LINK_BLOG_WITH_PRODUCTS"/>
                            {{$t('LBL_YES')}}
                            <span></span>
                            </label>
                            <label class="radio">
                            <input type="radio" name="PRODUCT_GIFT_WRAP_ENABLE" :value="0" v-model="adminsData.LINK_BLOG_WITH_PRODUCTS"
                            />
                            {{$t('LBL_NO')}}
                            <span></span>
                        </label>
                    </div>
                </div>
              </div>-->
              <div class="form-group row">
                <label
                  class="col-md-6"
                >{{ $t("LBL_PRODUCT_REVIEW_AUTOMATICALLY_APPROVED") }}</label>
                <div class="col-md-6">
                  <div class="radio-inline">
                    <label class="radio">
                      <input
                        type="radio"
                        checked="checked"
                        :value="1"
                        v-model="adminsData.PRODUCT_REVIEW_APPROVE_STATUS"
                        name="PRODUCT_REVIEW_APPROVE_STATUS"
                      />
                      {{$t('LBL_YES')}}
                      <span></span>
                    </label>
                    <label class="radio">
                      <input
                        type="radio"
                        name="PRODUCT_REVIEW_APPROVE_STATUS"
                        :value="0"
                        v-model="adminsData.PRODUCT_REVIEW_APPROVE_STATUS"
                      />
                      {{$t('LBL_NO')}}
                      <span></span>
                    </label>
                  </div>
                </div>
              </div>
              
              
                <div class="form-group row">
                    <label class="col-md-6">{{ $t('LBL_CUSTOMERS_CAN_EDIT_REVIEWS') }}</label>
                        <div class="col-md-6">
                            <div class="radio-inline">
                                <label class="radio">
                                <input type="radio" checked="checked" :value="1" v-model="adminsData.ENABLE_EDIT_REVIEW" 
                                    name="ENABLE_EDIT_REVIEW"
                                />
                                {{$t('LBL_YES')}}
                                <span></span>
                                </label>
                                <label class="radio">
                                <input type="radio" name="ENABLE_EDIT_REVIEW" :value="0"
                                    v-model="adminsData.ENABLE_EDIT_REVIEW"
                                />
                                {{$t('LBL_NO')}}
                                <span></span>
                                </label>
                            </div>
                        </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6">{{ $t('LBL_DISPLAY_REVIEW_STATUS_ON_DASHBOARD') }}</label>
                    <div class="col-md-6">
                        <div class="radio-inline">
                            <label class="radio">
                                <input type="radio" checked="checked" :value="1" v-model="adminsData.DISPLAY_REVIEW_STATUS" 
                                    name="DISPLAY_REVIEW_STATUS"
                                /> {{$t('LBL_YES')}}
                                <span></span>
                            </label>
                            <label class="radio">
                                <input type="radio" name="DISPLAY_REVIEW_STATUS" :value="0"
                                    v-model="adminsData.DISPLAY_REVIEW_STATUS"
                                />{{$t('LBL_NO')}}
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6">{{ $t('LBL_DISPLAY_REVIEW_ON_DETAIL_PAGE_IF_NO_REVIEWS') }}</label>
                    <div class="col-md-6">
                        <div class="radio-inline">
                            <label class="radio">
                                <input type="radio" checked="checked" :value="1" v-model="adminsData.DISPLAY_REVIEW_SECTION_IF_NO_REVIEW" 
                                    name="DISPLAY_REVIEW_SECTION_IF_NO_REVIEW"
                                /> {{$t('LBL_YES')}}
                                <span></span>
                            </label>
                            <label class="radio">
                                <input type="radio" name="DISPLAY_REVIEW_SECTION_IF_NO_REVIEW" :value="0"
                                    v-model="adminsData.DISPLAY_REVIEW_SECTION_IF_NO_REVIEW"
                                />{{$t('LBL_NO')}}
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>

                
            </div>
            <div class="portlet__foot">
              <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-6">
                  <button
                    type="submit"
                    class="btn btn-brand btn-wide gb-btn gb-btn-primary"
                    @click="updateSettings"
                    :disabled="clicked==1"
                    v-bind:class="clicked==1 && 'gb-is-loading'"
                  >{{$t('BTN_PRODUCT_SETTINGS_UPDATE')}}</button>
                </div>
              </div>
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
  BRAND_IS_OPTIONAL: 0,
  PRODUCT_TYPES: '',
  LINK_BLOG_WITH_PRODUCTS: 0,
  PRODUCT_REVIEW_APPROVE_STATUS: 0,
  PRODUCT_GIFT_WRAP_PRICE: "",
  PRODUCT_GIFT_WRAP_ENABLE: 0,
  PRICE_INCLUDING_TAX: 0,
  ENABLE_EDIT_REVIEW: 0,
  DISPLAY_REVIEW_STATUS: 0,
  //MEDIA_TYPES: 1,
  DISPLAY_REVIEW_SECTION_IF_NO_REVIEW: 0,
  DECIMAL_VALUES: 0
};
export default {
  data: function() {
    return {
      adminsData: tableFields,
      clicked: 0,
      currencySymbol:currencySymbol
    };
  },
  methods: {
    enableProductType: function(event) {
      this.adminsData.PRODUCT_TYPES = event.target.value;
    },
    linkBlogsWithProducts: function(event) {
      this.adminsData.LINK_BLOG_WITH_PRODUCTS =
        event.target.checked == true ? 1 : 0;
    },
    enableEditReview: function(event) {
      this.adminsData.ENABLE_EDIT_REVIEW =
        event.target.checked == true ? 1 : 0;
    },
    displayReviewStatus: function(event) {
      this.adminsData.DISPLAY_REVIEW_STATUS =
        event.target.checked == true ? 1 : 0;
    },
    getSettings: function() {
      let formData = this.$serializeData({
        keys: [
          "BRAND_IS_OPTIONAL",
          "PRODUCT_TYPES",
          "LINK_BLOG_WITH_PRODUCTS",
          "PRICE_INCLUDING_TAX",
          "PRODUCT_GIFT_WRAP_ENABLE",
          "PRODUCT_GIFT_WRAP_PRICE",
          "PRODUCT_REVIEW_APPROVE_STATUS",
          "ENABLE_EDIT_REVIEW",
          "DISPLAY_REVIEW_STATUS",
          //"MEDIA_TYPES",
          "DISPLAY_REVIEW_SECTION_IF_NO_REVIEW",
          "DECIMAL_VALUES"
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
    }
  },
  mounted: function() {
    this.getSettings();
  }
};
</script>
