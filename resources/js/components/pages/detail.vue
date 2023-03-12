<template>
  <div>
    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_PRODUCT_DETAIL') }}</h3>
                <div class="subheader__breadcrumbs">
                    <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                      <i class="flaticon2-shelter"></i>
                  </router-link>
                    <span class="subheader__breadcrumbs-separator"></span>
                    <span class="subheader__breadcrumbs-link">{{$t('LBL_CMS')}}</span>
                    <span class="subheader__breadcrumbs-separator"></span>
                    <router-link :to="{name: 'pages'}" class="subheader__breadcrumbs-link">{{$t('LBL_PAGES')}}</router-link>
                    <span class="subheader__breadcrumbs-separator"></span>
                    <span class="subheader__breadcrumbs-link">{{$t('LBL_PRODUCT_DETAIL')}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <div class="portlet portlet--height-fluid">
            <div class="portlet__body pb-0">
              <!--<div class="form-group row">
                  <label class="col-md-9 col-form-label">{{ $t('LBL_DISPLAY_CANCELLATION_ON_DETAIL') }}</label>
                  <div class="col-md-3">
                    <label class="switch switch--sm">
                      <input type="checkbox" name="PRODUCT_DETAIL_DISPLAY_CANCELLATION" checked="checked" v-model="adminsData.PRODUCT_DETAIL_DISPLAY_CANCELLATION" @change="onSwitchStatus($event, 'PRODUCT_DETAIL_DISPLAY_CANCELLATION')">
                      <span></span>
                    </label>
                  </div>
              </div>-->
              <div class="form-group row">
                  <label class="col-md-9 col-form-label">{{ $t('LBL_DISPLAY_RETURN_ON_DETAIL') }}</label>
                  <div class="col-md-3">
                    <label class="switch switch--sm">
                      <input type="checkbox" name="PRODUCT_DETAIL_DISPLAY_RETURN" v-model="adminsData.PRODUCT_DETAIL_DISPLAY_RETURN" @change="onSwitchStatus($event, 'PRODUCT_DETAIL_DISPLAY_RETURN')">
                      <span></span>
                    </label>
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-md-9 col-form-label">{{ $t('LBL_DISPLAY_SKU_ON_DETAIL') }}</label>
                  <div class="col-md-3">
                    <label class="switch switch--sm">
                      <input type="checkbox" name="PRODUCT_DETAIL_DISPLAY_SKU" v-model="adminsData.PRODUCT_DETAIL_DISPLAY_SKU" @change="onSwitchStatus($event, 'PRODUCT_DETAIL_DISPLAY_SKU')">
                      <span></span>
                    </label>
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-md-9 col-form-label">{{ $t('LBL_DISPLAY_MODEL_ON_DETAIL') }}</label>
                  <div class="col-md-3">
                    <label class="switch switch--sm">
                      <input type="checkbox" name="PRODUCT_DETAIL_DISPLAY_MODEL" v-model="adminsData.PRODUCT_DETAIL_DISPLAY_MODEL" @change="onSwitchStatus($event, 'PRODUCT_DETAIL_DISPLAY_MODEL')">
                      <span></span>
                    </label>
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-md-9 col-form-label">{{ $t('LBL_DISPLAY_WARRANTY_ON_DETAIL') }}</label>
                  <div class="col-md-3">
                    <label class="switch switch--sm">
                      <input type="checkbox" name="PRODUCT_DETAIL_DISPLAY_WARRANTY" v-model="adminsData.PRODUCT_DETAIL_DISPLAY_WARRANTY" @change="onSwitchStatus($event, 'PRODUCT_DETAIL_DISPLAY_WARRANTY')">
                      <span></span>
                    </label>
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-md-9 col-form-label">{{ $t('LBL_DISPLAY_BRAND_ON_DETAIL') }}</label>
                  <div class="col-md-3">
                    <label class="switch switch--sm">
                      <input type="checkbox" name="PRODUCT_DETAIL_DISPLAY_BRAND" v-model="adminsData.PRODUCT_DETAIL_DISPLAY_BRAND" @change="onSwitchStatus($event, 'PRODUCT_DETAIL_DISPLAY_BRAND')">
                      <span></span>
                    </label>
                  </div>
              </div>
            </div>
            <div class="portlet__foot">
              <div class="row">
                <div class="col">
                    <div class="portlet__head-toolbar">
                        <a href="javascript:;" @click="$router.go(-1)" class="btn btn-secondary btn-icon">{{$t('BTN_BACK')}} </a>
                    </div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-wide btn-brand gb-btn gb-btn-primary" @click="updateSettings" :disabled='clicked==1' v-bind:class="clicked==1 && 'gb-is-loading'">{{$t('BTN_UPDATE')}}</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>

const tableFields = {
  PRODUCT_DETAIL_DISPLAY_CANCELLATION: '',
  PRODUCT_DETAIL_DISPLAY_RETURN: '',
  PRODUCT_DETAIL_DISPLAY_SKU: '',
  PRODUCT_DETAIL_DISPLAY_MODEL: '',
  PRODUCT_DETAIL_DISPLAY_WARRANTY: '',
  PRODUCT_DETAIL_DISPLAY_BRAND: '',
};
export default {
  data: function() {
    return {
      adminsData: tableFields,
      clicked: 0
    };
  },
  methods: {
    onSwitchStatus: function(event, model) {
        this.adminsData[model] = event.target.checked==false ? 0: 1;
    },
    getSettings: function() {
        let formData = this.$serializeData({'keys':[
          'PRODUCT_DETAIL_DISPLAY_CANCELLATION',
          'PRODUCT_DETAIL_DISPLAY_RETURN',
          'PRODUCT_DETAIL_DISPLAY_SKU',
          'PRODUCT_DETAIL_DISPLAY_MODEL',
          'PRODUCT_DETAIL_DISPLAY_WARRANTY',
          'PRODUCT_DETAIL_DISPLAY_BRAND',
        ]});
        this.$http.post(adminBaseUrl + '/settings/get', formData).then((response) => {
            if (response.data.status == false) {
                toastr.error(response.data.message, '', toastr.options);
                return;
            }
            let thisObj = this;
            $.each( response.data.data, function( key, value ) {
              thisObj.adminsData[key] = parseInt(value);
            });
        });
    },
    updateSettings: function() {
        this.$validator.validateAll().then(res => {
            if (res) {
                this.clicked = 1;
                let formData = this.$serializeData(this.adminsData);
                this.$http.post(adminBaseUrl + '/settings/basedonkeys', formData)
                    .then((response) => { //success
                        this.clicked = 0;
                        if (response.data.status == false) {
                          toastr.error(response.data.message, '', toastr.options);
                          return;
                        }
                        toastr.success(response.data.message, '', toastr.options);
                    }, (response) => { //error
                        this.clicked = 0;
                        this.validateErrors(response);
                    });
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
  },
  mounted: function() {
    this.getSettings();
  }
};
</script>
