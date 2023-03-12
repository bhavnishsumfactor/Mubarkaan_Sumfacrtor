<template>
    <div class="card">
        <div class="card_body">
            <div class="steps-data">
                <div class="data form form-floating active">                    
                    <h6>{{ $t('Business Information') }}</h6>
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-floating__group">
                                        <input v-bind:class="[errors.first('business_name') ? 'is-invalid' : '', 'form-control form-floating__field', installerData.config.BUSINESS_NAME != '' ? 'filled' : '']" type="text" name="business_name" id="business_name" v-model="installerData.config.BUSINESS_NAME" v-validate="'required'" :data-vv-as="'business name'" data-vv-validate-on="none">
                                        <label class="form-floating__label" for="business_name">
                                            {{ $t('Bussiness Name') }}
                                            <span class="required">*</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group form-floating__group">
                                        <input v-bind:class="[errors.first('address') ? 'is-invalid' : '', 'form-control form-floating__field', installerData.config.BUSINESS_ADDRESS1 != '' ? 'filled' : '']"  type="text" name="address" id="address" v-model="installerData.config.BUSINESS_ADDRESS1" v-validate="'required'" :data-vv-as="'address'" data-vv-validate-on="none">
                                        <label class="form-floating__label" for="address">
                                            {{ $t('Address') }}
                                            <span class="required">*</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-floating__group">
                                        <input class="form-control form-floating__field" type="text" name="address2" id="address2" v-model="installerData.config.BUSINESS_ADDRESS2" :class="installerData.config.BUSINESS_ADDRESS2 != '' ? 'filled' : ''">
                                        <label class="form-floating__label" for="address2">
                                            {{ $t('Address2') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group form-floating__group">
                                        <select v-bind:class="[errors.first('country') ? 'is-invalid' : '', 'form-control form-floating__field filled']" name="country" v-model="installerData.config.BUSINESS_COUNTRY" v-validate="'required'" :data-vv-as="'country'" data-vv-validate-on="none" @change="getStates()" id="businessCountry">
                                            <option v-for="(record, index) in countries" :key="index" :value="record[0]">{{ record[1] }}</option>
                                        </select>
                                        <label class="form-floating__label">
                                            {{ $t('Country') }}
                                            <span class="required">*</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-floating__group">
                                        <select v-bind:class="[errors.first('State') ? 'is-invalid' : '', 'form-control form-floating__field filled']" name="" id="state"  v-model="installerData.config.BUSINESS_STATE" @change="stateChange()">
                                            <option v-for="(record, index) in states" :key="index" :value="record[0]">{{ record[1] }}</option>
                                        </select>
                                        <label class="form-floating__label">
                                            {{ $t('State') }}
                                            <span class="required">*</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group form-floating__group">
                                        <input v-bind:class="[errors.first('city') ? 'is-invalid' : '', 'form-control form-floating__field', installerData.config.BUSINESS_CITY != '' ? 'filled' : '']" type="text" name="city" id="city" v-model="installerData.config.BUSINESS_CITY" v-validate="'required'" :data-vv-as="'city'" data-vv-validate-on="none" >
                                        <label class="form-floating__label" for="city">
                                            {{ $t('City') }}
                                            <span class="required">*</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-floating__group">
                                        <input v-bind:class="[errors.first('pincode') ? 'is-invalid' : '', 'form-control form-floating__field', installerData.config.BUSINESS_PINCODE != '' ? 'filled' : '']" type="text" name="pincode" id="pincode" v-model="installerData.config.BUSINESS_PINCODE" v-validate="'required'" :data-vv-as="'pincode'" data-vv-validate-on="none">
                                        <label class="form-floating__label" for="pincode">
                                            {{ $t('Pincode') }}
                                            <span class="required">*</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group form-floating__group">
                                        <input v-bind:class="[errors.first('business_email') ? 'is-invalid' : '', 'form-control form-floating__field', installerData.config.BUSINESS_EMAIL != '' ? 'filled' : '']" type="email" name="business_email" id="business_email" v-model="installerData.config.BUSINESS_EMAIL" v-validate="'required'" :data-vv-as="'business email'" data-vv-validate-on="none">
                                        <label class="form-floating__label" for="business_email"> 
                                            {{ $t('Business Email') }}
                                            <span class="required">*</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <vue-tel-input v-model="installerData.config.BUSINESS_PHONE_NUMBER" :defaultCountry="defaultCountryCode" :enabledCountryCode="true" @country-changed="changeCountry" @input="onPhoneNumberChange" inputClasses="form-control" :placeholder="$t('Phone number')">
                                    </vue-tel-input>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <h6>{{ $t('Admin Details') }}</h6>
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="tab" id="tab3content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-floating__group">
                                            <input v-bind:class="[errors.first('admin_username') ? 'is-invalid' : '', 'form-control form-floating__field', installerData.admin_name != '' ? 'filled' : '']" type="text" name="admin_username" id="admin_username" v-model="installerData.admin_name" v-validate="'required'" :data-vv-as="'admin name'" data-vv-validate-on="none">

                                            <label class="form-floating__label" for="admin_username">
                                                {{ $t('Admin Username') }}
                                                <span class="required">*</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group form-floating__group">
                                            <input v-bind:class="[errors.first('admin_email') ? 'is-invalid' : '', 'form-control form-floating__field', 
                                            installerData.admin_email != '' ? 'filled' : '']" type="text" name="admin_email" id="admin_email" v-model="installerData.admin_email" v-validate="'required'" :data-vv-as="'admin email'" data-vv-validate-on="none">

                                            <label class="form-floating__label" for="admin_email">
                                                {{ $t('Admin Email') }}
                                                <span class="required">*</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-floating__group">
                                            <input v-bind:class="[errors.first('admin_password') ? 'is-invalid' : '', 'form-control form-floating__field', 
                                            installerData.admin_password != '' ? 'filled' : '']" type="password" name="admin_password" id="admin_password" v-model="installerData.admin_password">
                                                                                        
                                            <label class="form-floating__label" for="admin_password">
                                                {{ $t('Admin Password') }}
                                                <span class="required">*</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-floating__group">
                                            <input v-bind:class="
                                            [ (errors.first('confirm_password') || ( (this.installerData.admin_password != '') && (this.installerData.confirm_password != this.installerData.admin_password) ) ) ? 'is-invalid' : '', 'form-control form-floating__field', 
                                            installerData.confirm_password != '' ? 'filled' : '', ]" 

                                            type="password" name="confirm_password" id="admin_confirm_password" v-model="installerData.confirm_password">
                                            <label class="form-floating__label" for="confirm_password">
                                                {{ $t('Confirm Password') }}
                                                <span class="required">*</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-floating__group">
                                            <select v-bind:class="[errors.first('admin_default_currency') ? 'is-invalid' : '', 'form-control form-floating__field', 
                                            installerData.admin_currency != '' ? 'filled' : '']" v-model="installerData.admin_currency">
                                                <option v-for="(record, index) in currencies" :key="index" :value="record.currency_code">{{ record.currency_name }}</option>
                                            </select>
                                            <label class="form-floating__label" for="admin_default_currency">
                                                {{ $t('Select Default Currency') }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-floating__group">
                                            <select class="form-control form-floating__field" v-model="installerData.admin_language" :class="installerData.admin_language != '' ? 'filled' : ''">
                                                <option v-for="(record, index) in languages" :key="index" :value="record.lang_code">{{ record.lang_name }}</option>
                                            </select>
                                            <label class="form-floating__label" for="admin_default_lang">
                                                {{ $t('Select Default Lang') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card_foot">
            <button class="btn btn-outline-secondary btn-wide" @click="previous()">{{ $t('Back') }}</button>
            <button class="btn btn-brand btn-wide" @click="next()" :disabled="checkingFieldValue">{{ $t('Next') }}</button>
        </div>
    </div>
</template>
<script>
    export default {
        props: ["installerData"],
        data: function() {
            return { 
                baseUrl: baseUrl,
                countries:[],
                currencies:[],
                languages:[],
                states:[],
                defaultCountryCode:'',
            }
        },
        methods: {
            next: function() {
                this.$validator.validateAll().then(res => {
                    if (res) {
                        this.$emit('next', 6 , this.installerData);
                    } else {
                        toastr.error("Please fill the fields", "", toastr.options);
                    }
                });
            },
            previous: function() {
                this.$emit('previous', 6, this.installerData);
            },
            getCountries: function() {
                this.$http.get(baseUrl + "/installer/countries")
                    .then(response => {                
                        this.countries = response.data.data;
                });
            },
            getStates: function() {
                this.installerData.country_name = $("#businessCountry option:selected").text();
                let formData = new FormData();
                formData.append('country', this.installerData.config.BUSINESS_COUNTRY);
                this.$http.post(baseUrl + "/installer/states", formData)
                    .then(response => {                
                        this.states = response.data.data;
                });
            },
            stateChange: function() {
                this.installerData.state_name = $("#state option:selected").text();
            },
            getCurrencyWithLanguage: function() {
                this.$http.get(baseUrl + "/installer/currency-and-languages")
                    .then(response => {                
                    this.currencies = response.data.data['currency'];
                    this.languages = response.data.data['language'];
                });
            },
            changeCountry: function(data) {
                this.defaultCountryCode = data.iso2;
                this.installerData.config.BUSINESS_PHONE_COUNTRY_CODE = this.defaultCountryCode;
            },
            onPhoneNumberChange: function(data,obj) {
                this.countryCode = obj.country.iso2;
                this.installerData.config.BUSINESS_PHONE_NUMBER = obj.number.significant;
                this.installerData.config.BUSINESS_PHONE_COUNTRY_CODE = this.defaultCountryCode;
            }
        },
        mounted: function() {
            this.getCountries();
            this.getCurrencyWithLanguage();
        },

        computed: {
            checkingFieldValue: function() {
                return (this.installerData.config.BUSINESS_NAME == '' || this.installerData.business_email == '' || this.installerData.config.BUSINESS_ADDRESS1 == '' || this.installerData.config.BUSINESS_COUNTRY == '' || this.installerData.config.BUSINESS_CITY =='' || this.installerData.config.BUSINESS_PINCODE == '' || this.installerData.config.BUSINESS_PHONE_NUMBER == '' || this.installerData.admin_name == '' || this.installerData.admin_email == '' || this.installerData.admin_password == '' || this.installerData.confirm_password == '' || (this.installerData.admin_password != this.installerData.confirm_password) );
            }
        }
    }
</script>