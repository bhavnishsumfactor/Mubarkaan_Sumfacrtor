<template>
<div>
<div class="subheader" id="subheader">
      <div class="container">
          <div class="subheader__main">
              <h3 class="subheader__title">Admin</h3>
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

    <settings-sidebar :tab="type"></settings-sidebar>

    <!--Begin:: App Content-->
    <div class="grid-layout-content">

        <div class="row">
            <div class="col-xl-12">
                <div class="portlet">
                    <div class="portlet__head">
                        <div class="portlet__head-label">
                            <h3 class="portlet__head-title">{{ $t('General') }} <small>{{ $t('update general information') }}</small></h3>
                        </div>
                    </div>
                    <form class="form form--label-right" v-on:submit.prevent="updateSettings">
                        <div class="portlet__body">
                            <div class="section">
                                <div class="section__body">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{ $t('Enable Pick Up Setting') }}</label>
                                        <div class="col-lg-9">
											<div class="radio-inline">
												<label class="radio">
													<input type="radio" checked="checked" :value="1" v-model="adminsData.pickup_enable" name="pickup_enable"> {{$t('Yes')}}<span></span>
												</label>
												<label class="radio">
													<input type="radio" name="pickup_enable" :value="0" v-model="adminsData.pickup_enable"> {{$t('No')}}<span></span>
												</label>
											</div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{ $t('Return Shipping Enable') }}</label>
                                        <div class="col-lg-9">
											<div class="radio-inline">
												<label class="radio">
													<input type="radio" checked="checked" :value="1" v-model="adminsData.return_shipping_enable" name="return_shipping_enable"> {{$t('Yes')}}<span></span>
												</label>
												<label class="radio">
													<input type="radio" name="return_shipping_enable" :value="0" v-model="adminsData.return_shipping_enable"> {{$t('No')}}<span></span>
												</label>
											</div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{ $t('Discount calculate on shipping') }}</label>
                                        <div class="col-lg-9">
											<div class="radio-inline">
												<label class="radio">
													<input type="radio" checked="checked" :value="1" v-model="adminsData.is_discount_on_tax" name="is_discount_on_tax"> {{$t('Yes')}}<span></span>
												</label>
												<label class="radio">
													<input type="radio" name="is_discount_on_tax" :value="0" v-model="adminsData.is_discount_on_tax"> {{$t('No')}}<span></span>
												</label>
											</div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{ $t('Tax calculate on shipping') }}</label>
                                        <div class="col-lg-9">
											<div class="radio-inline">
												<label class="radio">
													<input type="radio" checked="checked" :value="1" v-model="adminsData.is_tax_on_shipping" name="is_tax_on_shipping"> {{$t('Yes')}}<span></span>
												</label>
												<label class="radio">
													<input type="radio" name="is_tax_on_shipping" :value="0" v-model="adminsData.is_tax_on_shipping"> {{$t('No')}}<span></span>
												</label>
											</div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{ $t('Set Brand as optional') }}</label>
                                        <div class="col-lg-9">
											<div class="radio-inline">
												<label class="radio">
													<input type="radio" checked="checked" :value="1" v-model="adminsData.is_brand_optional" name="is_brand_optional"> {{$t('Yes')}}<span></span>
												</label>
												<label class="radio">
													<input type="radio" name="is_brand_optional" :value="0" v-model="adminsData.is_brand_optional"> {{$t('No')}}<span></span>
												</label>
											</div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{ $t('Enable COD') }}</label>
                                        <div class="col-lg-9">
											<div class="radio-inline">
												<label class="radio">
													<input type="radio" checked="checked" :value="1" v-model="adminsData.cod_enable" name="cod_enable"> {{$t('Yes')}}<span></span>
												</label>
												<label class="radio">
													<input type="radio" name="cod_enable" :value="0" v-model="adminsData.cod_enable"> {{$t('No')}}<span></span>
												</label>
											</div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                   	 <label class="col-lg-3 col-form-label">{{ $t('Digital Products') }}</label>
                                        <div class="col-lg-9">
											<div class="checkbox-inline">
												<label class="checkbox">
													<input type="checkbox" :value="1" @click="enableDigital($event)" :checked="adminsData.PRODUCT_TYPES!=0" name="PRODUCT_TYPES"> {{$t('Enable')}}<span></span>
												</label>
											</div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{ $t('Weight Unit') }}</label>
                                        <div class="col-lg-9">
											<div class="radio-inline">
												<label class="radio" v-for="(unit, key) in units" :key="'weight'+key">
													<input type="radio" :value="key" v-model="adminsData.weight_unit" name="weight_unit"> {{$t(unit)}}<span></span>
												</label>
											</div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{ $t('Dimension Unit') }}</label>
                                        <div class="col-lg-9">
											<div class="radio-inline">
												<label class="radio" v-for="(unit, key) in units" :key="'dimension'+key">
													<input type="radio" :value="key" v-model="adminsData.dimension_unit" name="dimension_unit"> {{$t(unit)}}<span></span>
												</label>
											</div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{ $t('Accept Cookie Text') }}</label>
                                        <div class="col-lg-9">
                                            <textarea class="form-control" v-model="adminsData.accept_cookie_text" name="accept_cookie_text" v-validate="'required'" data-vv-validate-on="none" :data-vv-as="$t('Accept Cookie Text')">
                                            </textarea>
                                            <span v-if="errors.first('accept_cookie_text')" class="error text-danger">{{ errors.first('accept_cookie_text') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{ $t('Link Blogs with Products') }}</label>
                                        <div class="col-lg-9">
											<div class="checkbox-inline">
												<label class="checkbox">
													<input type="checkbox" :value="1" @click="linkBlogsWithProducts($event)" :checked="adminsData.link_blog_with_products!=0" name="link_blog_with_products"> {{$t('Yes')}}<span></span>
												</label>
											</div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{ $t('Maintenance Mode') }}</label>
                                        <div class="col-lg-9">
											<div class="radio-inline">
												<label class="radio">
													<input type="radio" checked="checked" :value="1" v-model="adminsData.maintenance_mode" name="maintenance_mode"> {{$t('Yes')}}<span></span>
												</label>
												<label class="radio">
													<input type="radio" name="maintenance_mode" :value="0" v-model="adminsData.maintenance_mode"> {{$t('No')}}<span></span>
												</label>
											</div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{ $t('Currency Cron frequency (in minutes)') }}</label>
                                        <div class="col-lg-9">
											<input type="text" v-model="adminsData.currency_cron_frequency" name="currency_cron_frequency" v-validate="{ rules: { required: true, regex:  /^([0-9]+)$/} }" data-vv-validate-on="none" :data-vv-as="$t('Cron frequency')" class="form-control" />
                                            <span v-if="errors.first('currency_cron_frequency')" class="error text-danger">{{ errors.first('currency_cron_frequency') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                          <div class="portlet__foot">
                            <div class="form__actions">
                                <div class="row">
                                    <div class="col-lg-3">
                                    </div>
                                    <div class="col-lg-9">
                                        <button type="submit" class="btn btn-brand btn-wide">Submit</button>&nbsp;
                                        <router-link :to="{name: 'dashboard'}" class="btn btn-secondary btn-wide ">{{ $t('Cancel')}}</router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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

import settingsSidebar from './settingsSidebar';
const tableFields = {
    'pickup_enable': 0,
    'accept_cookie_text': '',
    'return_shipping_enable': 0,
    'is_discount_on_tax': 0,
    'is_tax_on_shipping': 0,
    'is_brand_optional': 0,
    'PRODUCT_TYPES': 0,
    'weight_unit': 0,
    'dimension_unit': 0,
    'link_blog_with_products': 0,
    'cod_enable': 0,
    'maintenance_mode':0,
    'currency_cron_frequency':''
};
export default {
    components: {
        'settings-sidebar': settingsSidebar
    },
    data: function() {
        return {
            adminsData: tableFields,
            type: 'general',
            units: []
        }
    },
    methods: {
        enableDigital: function(event) {
            this.adminsData.digital_products_enable = event.target.checked;
        },
        linkBlogsWithProducts: function(event) {
            this.adminsData.link_blog_with_products = event.target.checked;
        },
        getSettings: function() {
            this.$http.get(adminBaseUrl + '/settings/'+this.type).then((response) => {
                if (response.data.status == false) {
                    toastr.error(response.data.message, '', toastr.options);
                    return;
                }
                   this.adminsData = response.data.data;
                   this.units = response.data.data.units;
            });
        },
        updateSettings: function(input) {
            this.$validator.validateAll().then(res => {
                if (res) {
                    let formData = this.$serializeData(this.adminsData);
                    this.$http.post(adminBaseUrl + '/settings/'+this.type, formData)
                        .then((response) => { //success
                            if (response.data.status == false) {
                              toastr.error(response.data.message, '', toastr.options);
                              return;
                            }
                            toastr.success(response.data.message, '', toastr.options);
                        }, (response) => { //error
                            this.validateErrors(response);
                        });
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
    },
    mounted: function() {
        this.getSettings();
    }
}
</script>
