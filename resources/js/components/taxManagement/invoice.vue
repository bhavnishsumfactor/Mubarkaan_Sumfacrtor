<template>
  <div>
    <div class="subheader" id="subheader">
      <div class="container">
        <div class="subheader__main">
          <h3 class="subheader__title">{{ $t('LBL_INVOICE_MANAGEMENT') }}</h3>
            <div class="subheader__breadcrumbs">
                <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home yk-home-icon">
                  <i class="flaticon2-shelter"></i>
                </router-link>
                <span class="subheader__breadcrumbs-separator"></span>
                <span class="subheader__breadcrumbs-link">{{$t('LBL_TAX_SETTING')}}</span>
                <span class="subheader__breadcrumbs-separator"></span>
                <span class="subheader__breadcrumbs-link">{{$t('LBL_INVOICE_MANAGEMENT')}}</span>
            </div>          
        </div>
        <div class="subheader__toolbar">
          
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">

          <div class="portlet portlet-no-min-height">
            <div class="portlet__body" v-bind:class="[(!$canWrite('INVOICE_MANAGEMENT')) ? 'disabledbutton': '']">
              <div class="section">
                <div class="section_body">
                  <div class="form-group row">
                    <label
                      for="example-text-input"
                      class="col-md-6 col-form-label"
                    >{{ $t('LBL_TAX_DISPLAY_NAME_ON_INVOICE') }}</label>
                    <div class="col-md-6">
                            <input
                        type="text"
                        v-model="taxData.TAX_NAME"
                        name="tax_name"
                        class="form-control"
                      />
                    </div>
                  </div>
                   <div class="form-group row">
                    <label
                      for="example-text-input"
                      class="col-md-6 col-form-label"
                    >{{ $t('LBL_GOVT_MANDATED_INFO_FOR_INVOICES') }}</label>
                    <div class="col-md-6">
                      <textarea 
                       v-model="taxData.TAX_GOV_INFO"
                        :placeholder="$t('PLH_ENTER_COMMA_SEPARATED_VALUES') + ' # SDFTRJ4564566SD'"
                        class="form-control"
                      ></textarea>
                    </div>
                   </div>
                      <div class="form-group row">
                    <label
                      for="example-text-input"
                      class="col-md-6 col-form-label"
                    >{{ $t('LBL_INVOICE_NO_STARTS_FROM') }}</label>
                    <div class="col-md-6">
                      <div class="row align-items-center no-gutters">
                        <div class="col-md-6 toolTip__wrap"> <input
                        type="text"
                        @change="validatetTaxNumberExist"
                        v-model="taxData.TAX_INVOICE_ALPHABET_NUMBER"
                        v-validate="'required|min:6'"
                        :data-vv-as="$t('LBL_INVOICE_ALPHABET_NUMBER')"
                        :placeholder="$t('PLH_ALPHANUMERIC_VALUE')"
                        name="invoice_alphabet_number"
                        class="form-control"
                      />
                      <a
                        class="toolTip font-danger"
                        href="javascript:;"
                        v-if="errors.first('invoice_alphabet_number')"
                        v-b-tooltip.hover.topleft
                        :title="errors.first('invoice_alphabet_number')"
                        >
                        <i class="fas fa-info-circle"></i>
                        </a>
                      <!-- <span
                        v-if="errors.first('invoice_alphabet_number')"
                        class="error text-danger"
                      >{{ errors.first('invoice_alphabet_number') }}</span>-->
                      </div>
                        <div class="col-md-1"> <div class="text-center">-</div></div>
                        <div class="col-md-5 toolTip__wrap"><input
                          type="text"
                          @keypress="$onlyNumber"
                          v-model="taxData.TAX_INVOICE_NUMERIC_NUMBER"
                          v-validate="'required|numeric|min:4'"
                          :data-vv-as="$t('LBL_INVOICE_NUMERIC_NUMBER')"
                          :placeholder="$t('PLH_INTEGER_VALUE')"
                          name="invoice_numeric_number"
                          class="form-control"
                        />
                          <a
                        class="toolTip font-danger"
                        href="javascript:;"
                        v-if="errors.first('invoice_numeric_number')"
                        v-b-tooltip.hover.topleft
                        :title="errors.first('invoice_numeric_number')"
                        >
                        <i class="fas fa-info-circle"></i>
                        </a>
                        <!-- <span
                          v-if="errors.first('invoice_numeric_number')"
                          class="error text-danger"
                        >{{ errors.first('invoice_numeric_number') }}</span> -->
                        </div>

                      </div>
                      </div> 
                    </div> 
                   <div class="form-group row">
                    <label
                      for="example-text-input"
                      class="col-md-6 col-form-label"
                    >{{ $t('LBL_ENTER_TAX_CODE_DISPLAY_ON_INVOICE') }}</label>
                    <div class="col-md-6">
                      <div class="radio-inline">
                        <label class="radio">
                          <input
                            type="radio"
                            name="enable"
                            value="1"
                            v-model="taxData.TAX_CODE"
                          />
                          {{ $t('LBL_YES') }}
                          <span></span>
                        </label>
                        <label class="radio">
                          <input
                            type="radio"
                            name="enable"
                            value="0"
                            v-model="taxData.TAX_CODE"
                          />
                          {{ $t('LBL_NO') }}
                          <span></span>
                        </label>
                      </div>
                    </div>
                    </div>
                    <div class="form-group row">
                    <label
                      for="example-text-input"
                      class="col-md-6 col-form-label"
                    >{{ $t('LBL_ADDITIONAL_INFO_DISPLAY_ON_INVOICE') }}</label>
                    <div class="col-md-6">
                     <input class="form-control mb-4"
                     type="text"
                       v-model="taxData.TAX_ADDITIONAL_INFO_TITLE"
                       :placeholder="$t('LBL_ADDITIONAL_INFO_TITLE')"
                        
                      >
                          <textarea 
                       v-model="taxData.TAX_ADDITIONAL_INFO_DESCRIPTION"
                       :placeholder="$t('LBL_ADDITIONAL_INFO_DESCRIPTION')"
                        class="form-control "
                        rows="5"
                      ></textarea>
                    </div>
                     
                   </div>                 


                </div>
              </div>
            </div>
            <div class="portlet__foot">
              <div class="form__actions text-center">
                <button
            type="button"
            class="btn btn-brand btn-wide gb-btn gb-btn-primary"
            @click="updateRecord()"
            :disabled="clicked==1 || !$canWrite('INVOICE_MANAGEMENT')"
            v-bind:class="[clicked==1 && 'gb-is-loading']"
          >{{ $t('LBL_INVOICE_SETTINGS_UPDATE') }}</button>
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
  TAX_INVOICE_ALPHABET_NUMBER: "",
  TAX_INVOICE_NUMERIC_NUMBER: "",
  TAX_NAME: "",
  TAX_CODE: "",
  TAX_GOV_INFO: "",
  TAX_ADDITIONAL_INFO_TITLE: "",
  TAX_ADDITIONAL_INFO_DESCRIPTION: ""
};
export default {
  data: function() {
    return {
      taxData: tableFields,
      pages: [],
      clicked: 0,
      oldInvoiceNumber: '',
      numberError: 0,
    };
  },
  methods: {
    getData: function() {
      let formData = this.$serializeData({'keys':['TAX_INVOICE_ALPHABET_NUMBER', 'TAX_INVOICE_NUMERIC_NUMBER', 'TAX_NAME', 'TAX_CODE', 'TAX_GOV_INFO', 'TAX_ADDITIONAL_INFO_TITLE', 'TAX_ADDITIONAL_INFO_DESCRIPTION']});
      this.$http.post(adminBaseUrl + "/settings/get", formData).then(response => {
        this.pages = response.data.data.pages;
        for (let [key, value] of Object.entries(response.data.data)) {
          if (typeof key != "undefined") {
            if (response.data.status == false) {
              toastr.error(response.data.message, "", toastr.options);
              return;
            }
            if(key == "TAX_INVOICE_ALPHABET_NUMBER" && value != ""){
                this.oldInvoiceNumber = value;
            }
            this.taxData[key] = value;
          }
        }
      });
    },
    updateRecord: function() {
        if(!this.$canWrite('INVOICE_MANAGEMENT')) {
            toastr.error('Unauthorized request', "", toastr.options);
            return;
        }   
        this.$validator.validateAll().then(res => {
            if (res) {
                if(this.numberError == 1){
                    this.errors.add({
                        field: 'invoice_alphabet_number',
                        msg: this.$t('NOTI_INVOICE_NUMBER_NOT_SAME')
                    });
                    return false;
                }
                this.clicked = 1;
                let formData = this.$serializeData(this.taxData);
                this.$http
                    .post(adminBaseUrl + "/settings", formData)
                    .then(response => {
                    //success
                    if (response.data.status == false) {
                        toastr.error(response.data.message, "", toastr.options);
                        return;
                    }
                    toastr.success(response.data.message, "", toastr.options);
                    this.clicked = 0;
                    });
            }
        });
    },
    validatetTaxNumberExist: function() {
      this.numberError = 0;
        if(this.taxData.TAX_INVOICE_ALPHABET_NUMBER != this.oldInvoiceNumber){
             let formData = this.$serializeData({'taxNumber': this.taxData.TAX_INVOICE_ALPHABET_NUMBER});
          this.$http
            .post(adminBaseUrl + "/orders/invoice-exist", formData)
            .then(response => {
              //success
              if (response.data.data != 0) {
                 this.errors.add({
                    field: 'invoice_alphabet_number',
                    msg: this.$t('NOTI_INVOICE_NUMBER_NOT_SAME')
                });
                 this.numberError = 1;
              }
            });
        }    
    }
  },
  mounted: function() {
    this.getData();
  }
};
</script>