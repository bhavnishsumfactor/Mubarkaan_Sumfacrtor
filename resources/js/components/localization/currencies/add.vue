<template>
<div>
    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_ADD_CURRENCY') }}</h3>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <!--begin::Portlet-->
                <div class="portlet portlet--height-fluid">
                    <div class="portlet__body pb-0">
                        <div class="section">
							<div class="section_body">
                                <div class="row justify-content-center">
									<div class="col-md-8">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ $t('LBL_CURRENCY') }}</label>
                                            <div class="col-md-9">
                                                <vue-bootstrap-typeahead v-model="adminsData.currency_name" :data="currenciesArray" @hit="onSelectRecord" ref="townTypeahead" :placeholder="$t('Start typing currency name')" :max-matches=5
                                                    :min-matching-chars=1 />
                                                <span v-if="errors.first('currency_name')" class="error text-danger">{{ errors.first('currency_name') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ $t('LBL_CURRENCY_SYMBOL') }}</label>
                                            <div class="col-md-9">
                                                <input type="text" v-bind:disabled="disableSymbol==1" v-model="adminsData.currency_symbol" name="currency_symbol" v-validate="'required'" :data-vv-as="$t('Symbol')" data-vv-validate-on="none"
                                                    class="form-control" />
                                                <span v-if="errors.first('currency_symbol')" class="error text-danger">{{ errors.first('currency_symbol') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ $t('LBL_CURRENCY_POSITION') }}</label>
                                            <div class="col-md-9">
                                                <select name="currency_position" class="form-control" v-model="adminsData.currency_position" v-validate="'required'" :data-vv-as="$t('Position')" data-vv-validate-on="none">
                                                    <option value="0">{{ $t('LBL_LEFT') }}</option>
                                                    <option value="1">{{ $t('LBL_RIGHT') }}</option>
                                                </select>
                                                <span v-if="errors.first('currency_position')" class="error text-danger">{{ errors.first('currency_position') }}</span>
                                            </div>
                                        </div>
									</div>
                                </div>
							</div>
                        </div>
                    </div>
                    <div class="portlet__foot">
                        <div class="row">
                            <div class="col">
                                <button type="reset" class="btn btn-secondary btn-wide " @click="$router.push({ name: 'currencies'} )">{{ $t('BTN_DISCARD')}}</button>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-wide btn-brand gb-btn gb-btn-primary" @click="createRecord" :disabled='!isComplete || clicked==1' v-bind:class="clicked==1 && 'gb-is-loading'">{{$t('BTN_CURRENCY_CREATE')}}</button>
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


import VueBootstrapTypeahead from 'vue-bootstrap-typeahead';

const tableFields = {
    'currency_id': '',
    'currency_name': '',
    'currency_code': '',
    'currency_symbol': '',
    'currency_position': '',
    'currency_value': '',
    'currency_is_default': 0
};
export default {
  components: {'vue-bootstrap-typeahead': VueBootstrapTypeahead},
    data: function() {
        return {
            records: [],
            adminsData: tableFields,
            currencies :[],
            currenciesArray :[],
            disableSymbol: 0,
            clicked: 0
        }
    },
    computed: {
        isComplete () {
            return this.adminsData.currency_name && this.adminsData.currency_code && this.adminsData.currency_position && this.adminsData.currency_symbol;
        }
    },
    methods: {
        createRecord: function() {
            this.$validator.validateAll().then(res => {
                if (res) {
                    let input = this.adminsData;
                    input.currency_code = '';
                    Object.keys(this.currencies).forEach(key => {
                        if (this.currencies[key].currencyName == input.currency_name) {
                            input.currency_name = this.currencies[key].currencyName;
                            input.currency_code = this.currencies[key].id;
                            if (typeof this.currencies[key].currencySymbol != "undefined") {
                                input.currency_symbol = this.currencies[key].currencySymbol;
                            }
                        }
                    });
                    if (typeof input.currency_code == "undefined" || input.currency_code == "") {
                        toastr.error('Please select a valid currency.', '', toastr.options);
                        return;
                    }                    
                    this.clicked = 1;
                    let formdata = this.$serializeData(input);
                    this.$http.post(adminBaseUrl + '/currencies', formdata)
                        .then((response) => {
                            if (response.data.status == false) {
                                this.clicked = 0;
                                toastr.error(response.data.message, '', toastr.options);
                                return;
                            }
                            this.currencies = [];
                            toastr.success(response.data.message, '', toastr.options);
                            this.clicked = 0;
                            this.$router.push({ name: 'currencies'});
                        }, (response) => {
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
        onSelectRecord: function(currencyName) {
            Object.keys(this.currencies).forEach(key => {
                let currencyArr = this.currencies[key];
                if (currencyArr.currencyName == currencyName) {
                    this.adminsData.currency_name = currencyArr.currencyName;
                    this.adminsData.currency_code = currencyArr.id;
                    if (typeof currencyArr.currencySymbol != "undefined") {
                        this.adminsData.currency_symbol = currencyArr.currencySymbol;
                        this.disableSymbol = 1;
                    } else {
                        this.disableSymbol = 0;
                        this.adminsData.currency_symbol = '';
                    }
                }
            });
        },
        getCurrencies: function() {
            this.$http.get(adminBaseUrl + '/currencies/fetchall').then((response) => {
                this.currencies = response.data.data.currencies;
                var currenciesArr = response.data.data.currenciesArray;
                this.currenciesArray = Object.values(currenciesArr);
            });
        },
        emptyFormValues: function() {
            this.adminsData = {
                'currency_id': '',
                'currency_name': '',
                'currency_code': '',
                'currency_symbol': '',
                'currency_position': '',
                'currency_value': '',
                'currency_is_default': 0
            };
            this.errors.clear();
        },
    },
    mounted: function() {
        this.emptyFormValues();
        this.getCurrencies();
    },
}
</script>
