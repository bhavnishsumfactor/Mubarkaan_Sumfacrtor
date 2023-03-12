<template>
  <div>
    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_REWARD_POINTS') }}</h3>
                <div class="subheader__breadcrumbs">
                    <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                        <i class="flaticon2-shelter"></i>
                    </router-link>
                    <span class="subheader__breadcrumbs-separator"></span>
                    <span class="subheader__breadcrumbs-link">{{$t('LBL_PROMOTIONS')}}</span>
                    <span class="subheader__breadcrumbs-separator"></span>
                    <span class="subheader__breadcrumbs-link">{{$t('LBL_REWARD_POINTS')}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
               <div class="portlet">
                    <div class="portlet__body p-0">
                        <div class="tax-rules">
                            <div class="tax-head">
                                <div class="row align-items-center ">
                                    <div class="col">
                                        <div class="heading heading--md">{{ $t('LBL_GENERAL_CONFIGURATION') }}</div>
                                    </div> 
                                </div>
                            </div>
                            <div class="tax-body">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-md-6 col-form-label">{{ $t('LBL_ENABLE') }}</label>
                                        <div class="col-md-6">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="enable" value="1" v-model="rewardsData.REWARD_POINTS_ENABLE" @change="rewardsOnOff" />
                                                    {{ $t('LBL_YES') }}
                                                    <span></span>
                                                </label>
                                                <label class="radio">
                                                    <input type="radio" name="enable" value="0" v-model="rewardsData.REWARD_POINTS_ENABLE" @change="rewardsOnOff" />
                                                    {{ $t('LBL_NO') }}
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-md-6 col-form-label">{{ $t('LBL_EARN_REWARD_ON_REDEEMED_ORDER') }}</label>
                                        <div class="col-md-6">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="earn-reward" value="1" v-model="rewardsData.REWARD_POINTS_EARN_ON_REDEEMED_ORDER" />
                                                    {{ $t('LBL_YES') }}
                                                    <span></span>
                                                </label>
                                                <label class="radio">
                                                    <input type="radio" name="earn-reward" value="0" v-model="rewardsData.REWARD_POINTS_EARN_ON_REDEEMED_ORDER" />
                                                    {{ $t('LBL_NO') }}
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group row">
                                        <label for="example-text-input" class="col-md-6 col-form-label">{{ $t('LBL_REWARD_POINTS_POLICY_PAGE') }}</label>
                                        <div class="col-md-6">
                                            <select v-model="rewardsData.REWARD_POINTS_POLICY_PAGE" name="policy_page" v-validate="'required'" :data-vv-as="$t('Policy page')" data-vv-validate-on="none" class="form-control">
                                                <option value disabled>{{ $t('LBL_SELECT')}}</option>
                                                <option v-for="(pageTitle, pageId) in pages" :key="pageId" :value="pageId">{{pageTitle}}</option>
                                            </select>
                                            <span v-if="errors.first('policy_page')" class="error text-danger">{{ errors.first('policy_page') }}</span>
                                        </div>
                                    </div> -->
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-md-6 col-form-label">{{ $t('LBL_EARN_POINTS_ROUNDING_MODE') }}</label>
                                        <div class="col-md-6">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="rounding_mode" value="1" v-model="rewardsData.REWARD_POINTS_ROUNDING_MODE" />
                                                    {{ $t('LBL_ALWAYS_ROUND_UP') }}
                                                    <span></span>
                                                </label>
                                                <label class="radio">
                                                    <input type="radio" name="rounding_mode" value="0" v-model="rewardsData.REWARD_POINTS_ROUNDING_MODE" />
                                                    {{ $t('LBL_ALWAYS_ROUND_DOWN') }}
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-md-6 col-form-label">{{ $t('LBL_MINIMUM_REWARD_POINTS_TO_USE') }}</label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="rewardsData.REWARD_POINTS_MINIMUM_POINTS_TO_USE" @keypress="$onlyNumber"  v-validate="requireValidation + 'decimal:'+decimalValues+'|min_value:1'" :data-vv-as="$t('LBL_MINIMUM_REWARD_POINTS')" name="minimum_points_to_use" class="form-control" maxlength="6"/>
                                            <span v-if="errors.first('minimum_points_to_use')" class="error text-danger">{{ errors.first('minimum_points_to_use') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-md-6 col-form-label">{{ $t('LBL_MAXIMUM_REWARD_POINTS_TO_USE_PER_ORDER') }}</label>
                                        <div class="col-md-6">
                                            <input
                                                type="text"
                                                v-model="rewardsData.REWARD_POINTS_MAXIMUM_POINTS_TO_USE_PER_ORDER"
                                                v-validate="requireValidation + 'numeric|min_value:1'"
                                                @keypress="$onlyNumber"
                                                :data-vv-as="$t('LBL_MAXIMUM_REWARD_POINTS_PER_ORDER')"
                                                name="maximum_points_to_use_per_order"
                                                class="form-control"
                                                maxlength="6"
                                            />
                                            <span v-if="errors.first('maximum_points_to_use_per_order')" class="error text-danger">{{ errors.first('maximum_points_to_use_per_order') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-md-6 col-form-label">{{ $t('LBL_POINTS_VALIDITY') }}</label>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input type="text" v-model="rewardsData.REWARD_POINTS_PURCHASE_POINTS_VALIDITY"  @keypress="$onlyNumber"  v-validate="requireValidation + 'decimal:'+decimalValues+'|min_value:1'" :data-vv-as="$t('LBL_POINTS_VALIDITY')" name="purchase_points_validity" class="form-control" maxlength="3"/>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">{{$t('LBL_DAYS')}}</span>
                                                </div>
                                            </div>
                                            <span v-if="errors.first('purchase_points_validity')" class="error text-danger">{{ errors.first('purchase_points_validity') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tax-rules">
                            <div class="tax-head">
                                <div class="row align-items-center ">
                                    <div class="col">
                                        <div class="heading heading--md">{{ $t('LBL_EARN_POINTS_CONFIGURATION') }}</div>
                                    </div> 
                                </div>
                            </div>
                            <div class="tax-body">
                                <div class="card-body">
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-md-6 col-form-label">{{ $t('LBL_POINTS_TO_PURCHASE') }}</label>
                                    <div class="col-md-6">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <label class="mr-2">{{$t('LBL_EACH')}}</label>
                                            </div>
                                            <div class="col">
                                                <input type="text" v-model="rewardsData.REWARD_POINTS_PURCHASE_POINTS_AMOUNT" @keypress="$onlyNumber"  v-validate="requireValidation + 'decimal:'+decimalValues+'|min_value:0'" :data-vv-as="$t('LBL_POINTS_TO_PURCHASE')" name="purchase_points_amount" class="form-control" maxlength="5"/>
                                            </div>
                                            <div class="col-auto">
                                                <label class="mx-2">{{currencyCode}} {{$t('LBL_SPENT_WILL_EARN')}}</label>
                                            </div>
                                            <div class="col">
                                                <input type="text" v-model="rewardsData.REWARD_POINTS_PURCHASE_POINTS_EARNINGS" @keypress="$onlyNumber"  v-validate="requireValidation + 'decimal:'+decimalValues+'|min_value:0'" :data-vv-as="$t('LBL_SPENT_WILL_EARN')" name="purchase_points_earnings" class="form-control" maxlength="5" />
                                            </div>
                                            <div class="col-auto">
                                                <label class="ml-2">{{$t('LBL_POINTS')}}</label>
                                            </div>
                                            <span v-if="errors.first('purchase_points_amount')" class="error text-danger">{{ errors.first('purchase_points_amount') }}</span>
                                            <span v-if="errors.first('purchase_points_earnings')" class="error text-danger">{{ errors.first('purchase_points_earnings') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-md-6 col-form-label">{{ $t('LBL_MINIMUM_ORDER_TOTAL') }}</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="rewardsData.REWARD_POINTS_PURCHASE_POINTS_MINIMUM_ORDER_TOTAL" @keypress="$onlyNumber"  v-validate="requireValidation + 'decimal:'+decimalValues+'|min_value:0'" :data-vv-as="$t('LBL_MINIMUM_ORDER_TOTAL')" name="purchase_points_minimum_order_total" class="form-control" maxlength="6"/>
                                        <span v-if="errors.first('purchase_points_minimum_order_total')" class="error text-danger">{{ errors.first('purchase_points_minimum_order_total') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-md-6 col-form-label">{{ $t('LBL_POINTS_ACTIVATE_IMMEDIATELY') }}</label>
                                    <div class="col-md-6">
                                        <div class="radio-inline">
                                            <label class="radio">
                                                <input type="radio" name="purchase_points_activated_immediately" value="1" v-model="rewardsData.REWARD_POINTS_PURCHASE_POINTS_ACTIVATED_IMMEDIATELY" />
                                                {{ $t('LBL_YES') }}
                                                <span></span>
                                            </label>
                                            <label class="radio">
                                                <input type="radio" name="purchase_points_activated_immediately" value="0" v-model="rewardsData.REWARD_POINTS_PURCHASE_POINTS_ACTIVATED_IMMEDIATELY" />
                                                {{ $t('LBL_NO') }}
                                                <span></span>
                                            </label>
                                        </div>
                                        <span v-if="errors.first('purchase_points_activated_immediately')" class="error text-danger">{{ errors.first('purchase_points_activated_immediately') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-text-input" class="col-md-6 col-form-label">{{ $t('LBL_DISPLAYED_EARNED_POINT') }}</label>
                                    <div class="col-md-6">
                                        <div class="radio-inline">
                                            <label class="radio">
                                                <input type="radio" name="purchase_points_display_earned" value="1" v-model="rewardsData.REWARD_POINTS_PURCHASE_POINTS_DISPLAY_EARNED" />
                                                {{ $t('LBL_YES') }}
                                                <span></span>
                                            </label>
                                            <label class="radio">
                                                <input type="radio" name="purchase_points_display_earned" value="0" v-model="rewardsData.REWARD_POINTS_PURCHASE_POINTS_DISPLAY_EARNED" />
                                                {{ $t('LBL_NO') }}
                                                <span></span>
                                            </label>
                                        </div>
                                        <span v-if="errors.first('purchase_points_display_earned')" class="error text-danger">{{ errors.first('purchase_points_display_earned') }}</span>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="tax-rules">
                            <div class="tax-head">
                                <div class="row align-items-center ">
                                    <div class="col">
                                        <div class="heading heading--md">{{ $t('LBL_SPENDING_POINTS_CONFIGURATION') }}</div>
                                    </div> 
                                </div>
                            </div>
                            <div class="tax-body">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-md-6 col-form-label">{{ $t('LBL_POINTS_FOR_REDEEM') }}</label>
                                        <div class="col-md-6">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <label class="mr-2">{{$t('LBL_EACH')}}</label>
                                                </div>
                                                <div class="col">
                                                    <input type="text" v-model="rewardsData.REWARD_POINTS_SPENDING_POINTS_EARNINGS" @keypress="$onlyNumber" v-validate="requireValidation + 'decimal:'+decimalValues+'|min_value:0'"  :data-vv-as="$t('LBL_AMOUNT_SPEND')" name="spending_points_earnings" class="form-control" maxlength="5"/>                                                
                                                </div>
                                                <div class="col-auto">
                                                    <label class="mx-2">{{$t('LBL_POINTS_SPENT_WILL_EARN')}}</label>
                                                </div>
                                                <div class="col">
                                                    <input type="text" v-model="rewardsData.REWARD_POINTS_SPENDING_POINTS_AMOUNT" @keypress="$onlyNumber" v-validate="requireValidation + 'decimal:'+decimalValues+'|min_value:0'" :data-vv-as="$t('LBL_POINTS_TO_EARNED')" name="spending_points_amount" class="form-control" maxlength="5"/>
                                                </div>
                                                <div class="col-auto">
                                                    <label class="ml-2">{{currencyCode}}</label>
                                                </div>
                                                <span v-if="errors.first('spending_points_earnings')" class="error text-danger">{{ errors.first('spending_points_earnings') }}</span>
                                                <span v-if="errors.first('spending_points_amount')" class="error text-danger">{{ errors.first('spending_points_amount') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-md-6 col-form-label">{{ $t('LBL_MINIMUM_ORDER_TOTAL') }}</label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="rewardsData.REWARD_POINTS_SPENDING_POINTS_MINIMUM_ORDER_TOTAL" @keypress="$onlyNumber" name="spending_points_minimum_order_total" v-validate="requireValidation + 'decimal:'+decimalValues+'|min_value:0'" :data-vv-as="$t('LBL_MINIMUM_ORDER_TOTAL')" class="form-control" maxlength="6"/>
                                            <span v-if="errors.first('spending_points_minimum_order_total')" class="error text-danger">{{ errors.first('spending_points_minimum_order_total') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tax-rules">
                            <div class="tax-head">
                                <div class="row align-items-center ">
                                    <div class="col">
                                        <div class="heading heading--md">{{ $t('LBL_BIRTHDAY_POINTS_CONFIGURATION') }}</div>
                                    </div> 
                                </div>
                            </div>
                            <div class="tax-body">
                                <div class="card-body">                                    
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-md-6 col-form-label">{{ $t('LBL_ENABLE') }}</label>
                                        <div class="col-md-6">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="birthday_points_enable" value="1" v-model="rewardsData.REWARD_POINTS_BIRTHDAY_POINTS_ENABLE" />
                                                    {{ $t('LBL_YES') }}
                                                    <span></span>
                                                </label>
                                                <label class="radio">
                                                    <input type="radio" name="birthday_points_enable" value="0" v-model="rewardsData.REWARD_POINTS_BIRTHDAY_POINTS_ENABLE" />
                                                    {{ $t('LBL_NO') }}
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-md-6 col-form-label">{{ $t('LBL_BIRTHDAY_POINTS') }}</label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="rewardsData.REWARD_POINTS_BIRTHDAY_POINTS" @keypress="$onlyNumber" v-validate="'numeric|min_value:0'" :data-vv-as="$t('LBL_BIRTHDAY_POINTS')" name="birthday_points" class="form-control" maxlength="6"/>
                                            <span v-if="errors.first('birthday_points')" class="error text-danger">{{ errors.first('birthday_points') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-md-6 col-form-label">{{ $t('LBL_POINTS_VALIDITY') }}</label>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input type="text" v-model="rewardsData.REWARD_POINTS_BIRTHDAY_POINTS_VALIDITY" @keypress="$onlyNumber" v-validate="'numeric|min_value:0'" name="birthday_points_validity" :data-vv-as="$t('LBL_POINTS_VALIDITY')" class="form-control" maxlength="3"/>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">{{$t('LBL_DAYS')}}</span>
                                                </div>
                                            </div>
                                            <span v-if="errors.first('birthday_points_validity')" class="error text-danger">{{ errors.first('birthday_points_validity') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tax-rules">
                            <div class="tax-head">
                                <div class="row align-items-center ">
                                    <div class="col">
                                        <div class="heading heading--md">{{ $t('LBL_SOCIAL_SHARING_POINTS_CONFIGURATION') }}</div>
                                    </div> 
                                </div>
                            </div>
                            <div class="tax-body">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-md-6 col-form-label">{{ $t('LBL_ENABLE') }}</label>
                                        <div class="col-md-6">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="social_sharing_points_enable" value="1" v-model="rewardsData.REWARD_POINTS_SOCIAL_SHARING_POINTS_ENABLE" />
                                                    {{ $t('LBL_YES') }}
                                                    <span></span>
                                                </label>
                                                <label class="radio">
                                                    <input type="radio" name="social_sharing_points_enable" value="0" v-model="rewardsData.REWARD_POINTS_SOCIAL_SHARING_POINTS_ENABLE" />
                                                    {{ $t('LBL_NO') }}
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-md-6 col-form-label">{{ $t('LBL_POINTS_AWARDED_ON_SIGNUP') }}</label>
                                        <div class="col-md-6">
                                            <input
                                                type="text"
                                                v-model="rewardsData.REWARD_POINTS_SOCIAL_SHARING_POINTS_ONSIGNUP"
                                                @keypress="$onlyNumber"
                                                name="social_sharing_points_onsignup"
                                                class="form-control"
                                                v-validate="rewardsData.REWARD_POINTS_SOCIAL_SHARING_POINTS_ENABLE == 1 ? 'required|numeric|min_value:0|max:7' : '' "
                                                :data-vv-as="$t('LBL_POINTS_AWARDED_ON_SIGNUP')"
                                                maxlength="6"
                                            />
                                            <span v-if="errors.first('social_sharing_points_onsignup')" class="error text-danger">{{ errors.first('social_sharing_points_onsignup') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-md-6 col-form-label">{{ $t('LBL_REFERRAL_LINK_VALIDITY') }}</label>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input
                                                    type="text"
                                                    v-model="rewardsData.REWARD_POINTS_SOCIAL_SHARING_POINTS_VALIDITY"
                                                    @keypress="$onlyNumber"
                                                    name="social_sharing_points_validity"
                                                    class="form-control"
                                                    v-validate="rewardsData.REWARD_POINTS_SOCIAL_SHARING_POINTS_ENABLE == 1 ? 'required|numeric|min_value:0' : '' "
                                                    :data-vv-as="$t('LBL_REFERRAL_LINK_VALIDITY')"
                                                    maxlength="3"
                                                />
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">{{$t('LBL_DAYS')}}</span>
                                                </div>
                                            </div>
                                            <span v-if="errors.first('social_sharing_points_validity')" class="error text-danger">{{ errors.first('social_sharing_points_validity') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="portlet__foot">
                        <div class="form__actions text-center">
                            <button type="button" class="btn btn-brand btn-wide gb-btn gb-btn-primary" @click="updateRecord()" :disabled="clicked==1 || ( !$canWrite('REWARDS_POINTS'))" v-bind:class="clicked==1 && 'gb-is-loading'">{{ $t('BTN_REWARD_POINTS_UPDATE') }}</button></div>
                    </div>
               </div>
            </div>
        </div>
    </div>
  </div>
</template>
<script>
const tableFields = {
  REWARD_POINTS_ENABLE: "",
  REWARD_POINTS_POLICY_PAGE: "",
  REWARD_POINTS_ROUNDING_MODE: "",
  REWARD_POINTS_MINIMUM_POINTS_TO_USE: "",
  REWARD_POINTS_MAXIMUM_POINTS_TO_USE_PER_ORDER: "",
  REWARD_POINTS_PURCHASE_POINTS_AMOUNT: "",
  REWARD_POINTS_PURCHASE_POINTS_EARNINGS: "",
  REWARD_POINTS_PURCHASE_POINTS_VALIDITY: "",
  REWARD_POINTS_PURCHASE_POINTS_MINIMUM_ORDER_TOTAL: "",
  REWARD_POINTS_PURCHASE_POINTS_ACTIVATED_IMMEDIATELY: "",
  REWARD_POINTS_PURCHASE_POINTS_DISPLAY_EARNED: "",
  REWARD_POINTS_SPENDING_POINTS_EARNINGS: "",
  REWARD_POINTS_SPENDING_POINTS_AMOUNT: "",
  REWARD_POINTS_SPENDING_POINTS_MINIMUM_ORDER_TOTAL: "",
  REWARD_POINTS_BIRTHDAY_POINTS_ENABLE: "",
  REWARD_POINTS_BIRTHDAY_POINTS: "",
  REWARD_POINTS_BIRTHDAY_POINTS_VALIDITY: "",
  REWARD_POINTS_SOCIAL_SHARING_POINTS_ENABLE: "",
  REWARD_POINTS_SOCIAL_SHARING_POINTS_ONSHARING: "",
  REWARD_POINTS_EARN_ON_REDEEMED_ORDER: "",
  REWARD_POINTS_SOCIAL_SHARING_POINTS_ONSIGNUP: "",
  REWARD_POINTS_SOCIAL_SHARING_POINTS_VALIDITY: ""
};
export default {
  data: function() {
    return {
      decimalValues: decimalValues,
      rewardsData: tableFields,
      currencyCode: currencyCode,
      clicked: 0,
      requireValidation: ""
    };
  },
  methods: {
    getData: function() {
      this.$http.get(adminBaseUrl + "/reward-points/get").then(response => {
        for (let [key, value] of Object.entries(response.data.data.settings)) {
          if (typeof key != "undefined") {
            if (response.data.status == false) {
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            this.rewardsData[key] = value;                     
          }
        }
        if (this.rewardsData.REWARD_POINTS_ENABLE == "1") {
            this.requireValidation = "required|";
        }   
      });
    },
    rewardsOnOff: function() {
        if (this.rewardsData.REWARD_POINTS_ENABLE == "1") {
            this.requireValidation = "required|";
        } else {
            this.requireValidation = "";
        }
    },
    updateRecord: function() {
      this.$validator.validateAll().then(res => {
        if (res) {
          this.clicked = 1;
          let formData = this.$serializeData(this.rewardsData);
          this.$http
            .post(adminBaseUrl + "/reward-points/update", formData)
            .then(response => {
              //success
              this.clicked = 0;
              if (response.data.status == false) {
                toastr.error(response.data.message, "", toastr.options);
                return;
              }
              toastr.success(response.data.message, "", toastr.options);
            });
        } else {
          this.clicked = 0;
        }
      });
    }
  },
  mounted: function() {
    this.getData();
  }
};
</script>