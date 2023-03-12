<template>
<div>
    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_ADD_PICKUP_ADDRESSES') }}</h3> </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!--begin::Portlet-->
                <form class="form">
                    <div class="portlet" id="page_portlet">                 
                        <div class="portlet__body" v-bind:class="[(!$canWrite('PICKUP_ADDRESS')) ? 'disabledbutton': '']">
                            <div class="section">
                                <div class="section__body">
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">{{ $t('LBL_STORE_NAME')}} <span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input type="text" v-model="adminsData.saddr_name" name="saddr_name" v-validate="'required'" :data-vv-as="$t('LBL_STORE_NAME')" data-vv-validate-on="none" class="form-control" /> <span v-if="errors.first('saddr_name')" class="error text-danger">{{errors.first('saddr_name')}}</span> </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">{{ $t('LBL_COUNTRY')}} <span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <select v-model="adminsData.saddr_country_id" name="saddr_country_id" v-validate="'required'" :data-vv-as="$t('LBL_COUNTRY')" @change="getStates()" data-vv-validate-on="none" class="form-control">
                                                <option value="" disabled>{{ $t('LBL_SELECT_COUNTRY')}}</option>
                                                <option v-for="(country, cindex) in countries" :value="cindex" :key="cindex">{{country}}</option>
                                            </select> <span v-if="errors.first('saddr_country_id')" class="error text-danger">{{errors.first('saddr_country_id')}}</span> </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">{{ $t('LBL_STATE')}} <span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <select v-model="adminsData.saddr_state_id" name="saddr_state_id" v-validate="'required'" :data-vv-as="$t('LBL_STATE')" data-vv-validate-on="none" class="form-control">
                                                <option value="" disabled>{{ $t('LBL_SELECT_STATE')}}</option>
                                                <option v-for="(state, sindex) in states" :value="sindex" :key="sindex">{{state}}</option>
                                            </select> <span v-if="errors.first('saddr_state_id')" class="error text-danger">{{errors.first('saddr_state_id')}}</span> </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">{{ $t('LBL_CITY')}} <span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input type="text" v-model="adminsData.saddr_city" name="saddr_city" v-validate="'required'" :data-vv-as="$t('LBL_CITY')" data-vv-validate-on="none" class="form-control" /> <span v-if="errors.first('saddr_city')" class="error text-danger">{{errors.first('saddr_city')}}</span> </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">{{ $t('LBL_ADDRESS')}} <span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <textarea class="form-control" type="text" v-model="adminsData.saddr_address" name="saddr_address" v-validate="'required'" :data-vv-as="$t('LBL_ADDRESS')" data-vv-validate-on="none" /> </textarea> <span v-if="errors.first('saddr_address')" class="error text-danger">{{errors.first('saddr_address')}}</span> </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">{{ $t('LBL_POSTAL_CODE')}} <span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <input type="text" v-model="adminsData.saddr_postal_code" name="saddr_postal_code" v-validate="'required'" :data-vv-as="$t('LBL_POSTAL_CODE')" data-vv-validate-on="none" class="form-control" /> <span v-if="errors.first('saddr_postal_code')" class="error text-danger">{{errors.first('saddr_postal_code')}}</span> </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">{{ $t('LBL_PHONE')}} <span class="required">*</span></label>
                                        <div class="col-lg-8">
                                            <vue-tel-input v-model="adminsData.saddr_phone" :defaultCountry="defaultCountryCode" :enabledCountryCode="true" @country-changed="changeCountry" @input="onPhoneNumberChange" inputClasses="form-control" placeholder="Enter a phone number" :validCharactersOnly="true" :maxLen="15"></vue-tel-input>
                                            <span v-if="errors.first('saddr_phone')" class="error text-danger">{{errors.first('saddr_phone')}}</span>
                                        </div>                                        
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">{{ $t('LBL_SELECT_SHOP_TIMINGS')}}</label>
                                        <div class="col-auto">
                                            <div class="form-group">
                                                <div class="radio-inline">
                                                    <label class="radio">
                                                        <input type="radio" name="radiopickup"  checked="checked" :value="2" v-model="pickupStatus" @change="changePickupFormat()" /> {{ $t('LBL_OPEN_ALL_DAYS') }} <span></span> 
                                                    </label>
                                                    <label class="radio">
                                                        <input type="radio" :value="1" v-model="pickupStatus" name="radiopickup" @change="changePickupFormat()"/> {{ $t('LBL_SELECT_SHOP_OPEN_TIMINGS') }} <span></span> 
                                                    </label>
                                                    <label class="radio">
                                                        <input type="radio" name="radiopickup" :value="3" v-model="pickupStatus" @change="changePickupFormat()" /> {{ $t('LBL_OPEN_EXCEPT_WEEKENDS') }} <span></span> </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <business-hours v-if="pickupStatus == 1" :days="days" type="select" :time-increment="30" color="#4b0082" :localization="localization" :hourFormat24="(timeFormat == true ? false : true)"></business-hours>
                                    <div class="form-group row" v-if="pickupStatus == 2 || pickupStatus == 3">
                                        <label class="col-lg-4 col-form-label">{{ $t('LBL_SELECT_TIMINGS') }}</label>
                                        <div class="row">
                                            <div class="col-lg-6 col-xl-6">
                                                <select @change="fetchEndTimes" class="form-control" v-model="adminsData.start_time" name="start_time" v-validate="'required'" :data-vv-as="$t('LBL_START_TIME')" data-vv-validate-on="none">
                                                    <option disabled value="">{{ $t('LBL_SELECT_TIMING')}}</option>
                                                    <option v-for="(time, timeIndex) in startTimes" :key="timeIndex" :value="time">
                                                        {{ time }}
                                                    </option>
                                                </select> 
                                                    <span v-if="errors.first('start_time')" class="error text-danger">{{ errors.first('start_time') }}</span> 
                                            </div>
                                            <div class="col-lg-6 col-xl-6">
                                                <select class="form-control" v-model="adminsData.end_time" name="end_time" v-validate="'required'" :data-vv-as="$t('LBL_END_TIME')" data-vv-validate-on="none">
                                                    <option disabled value="">{{ $t('LBL_SELECT_TIMING')}}</option>
                                                    <option v-for="(time, timeIndex) in endTimes" :key="timeIndex" :value="time">
                                                        {{ time }}
                                                    </option>
                                                </select> <span v-if="errors.first('end_time')" class="error text-danger">{{ errors.first('end_time') }}</span> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet__foot">
                            <div class="row">
                                <div class="col">
                                    <router-link :to="{name: 'pickups'}" class="btn btn-secondary btn-wide">{{ $t('BTN_DISCARD')}} </router-link>
                                </div>
                                <div class="col-auto"> 
                                    <button type="button" class="btn btn-wide btn-brand gb-btn gb-btn-primary" @click="validateRecord" :disabled="clicked==1 || isComplete || (!$canWrite('PICKUP_ADDRESS'))" v-bind:class="clicked==1 && 'gb-is-loading'">{{ $t('BTN_PICKUP_CREATE')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!--end::Portlet-->
            </div>
        </div>
    </div>
</div>
</template>
<script>


import BusinessHours from 'vue-business-hours';

const tableFields = {
    'saddr_country_id': '',
    'saddr_state_id': '',
    'saddr_name': '',
    'saddr_city': '',
    'saddr_address': '',
    'saddr_phone': '',
    'saddr_postal_code': '',
    'days':'',
    'start_time': '',
    'end_time': '',
};
export default {
    components: {
        BusinessHours
    },
    data: function() {
        return {
            records: [],
            defaultCountryCode : '',
            days:{
                sunday: [{
                    open: '',
                    close: '',
                    id: '',
                    isOpen: false
                }],
                monday: [{
                    open: '',
                    close: '',
                    id: '',
                    isOpen: false
                }],
                tuesday: [{
                    open: '',
                    close: '',
                    id: '',
                    isOpen: false
                }],
                wednesday: [{
                    open: '',
                    close: '',
                    id: '',
                    isOpen: false
                }],
                thursday: [{
                    open: '',
                    close: '',
                    id: '',
                    isOpen: false
                }],
                friday: [{
                    open: '',
                    close: '',
                    id: '',
                    isOpen: false
                }],
                saturday: [{
                    open: '',
                    close: '',
                    id: '',
                    isOpen: false
                }]
            },
            errored: true,
            countries: [],
            states: [],
            times: [],
            timings : [],
            adminsData: tableFields,
            pickupStatus:2,
            clicked: 0,
            endTimes: [],
            localization: {
                open: {},
                close: {},
                days: {}
            },
            timeFormat: true

        }
    },
    methods: {
        validateRecord: function() {
            this.$validator.validateAll().then(res => {
                if (res) {  
                    this.clicked = 1;      
                    if (this.pickupStatus == 1) {
                        this.adminsData['days'] = JSON.stringify(this.days);
                    }
                    this.adminsData['saddr_shop_open_status'] = this.pickupStatus;
                    let input = this.adminsData;
                    this.saveRecord(input);
                } else {
                    this.clicked = 0;
                }
            });
        },
        saveRecord: function(input) {
            let formData = this.$serializeData(input);
            formData.append('saddr_phone_country_id', this.countryCode);
            this.$http.post(adminBaseUrl + '/store-address', formData)
                .then((response) => { //success
                    this.clicked = 0;
                    if (response.data.status == false) {
                        this.isLoading = false
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    toastr.success(response.data.message, '', toastr.options);
                    this.$router.push({
                        name: 'pickups'
                    })
                }, (response) => { //error
                    this.clicked = 0;
                    this.validateErrors(response);
                });
        },
        changePickupFormat: function() {
            this.adminsData.start_time ='';
            this.adminsData.end_time ='';
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
        pageLoadData: async function() {
            await this.$http.post(adminBaseUrl + '/store-address/page-load-data')
                .then((response) => { //
                    this.countries = response.data.data.countries;
                    this.timeFormat = (response.data.data.timeFormat == 1 ? true : false);
                }); 
        },
        getStates: function() {
            let formData = this.$serializeData({'coutry-id':this.adminsData.saddr_country_id});
            this.$http.post(adminBaseUrl + '/store-address/get-states',formData)
                .then((response) => { //
                    this.states = response.data.data;
                 });
        },
        emptyFormValues: function() {
            this.adminsData = {
                'saddr_name': '',
                'saddr_country_id': '',
                'saddr_state_id': '',
                'saddr_city': '',
                'saddr_address': '',
                'saddr_phone': '',
                'saddr_postal_code': '',
            };
        },
        changeCountry: function(data) {
            this.countryCode = data.iso2;
        },
        onPhoneNumberChange: function(data,obj) {
            this.countryCode = obj.country.iso2;
            this.adminsData.saddr_phone = obj.number.significant;
        },
        timeInterval: function() {
            let x = 30; 
            let times = []; 
            let tt = 0; 
            let ap = ['AM', 'PM']; 
            for (let i=0; tt < 24*60; i++) {
                let hh = Math.floor(tt/60); 
                let mm = (tt % 60);
                times[i] = ("0" + (hh)).slice(-2) + ':' + ("0" + mm).slice(-2);
                tt = tt + x;
            }
            this.times = times;
        },
        fetchEndTimes: function() {
            this.endTimes = this.times
                .map((element) => {
                    let startDate = moment(this.adminsData.start_time, ["hh:mm A"]).format("HH:mm");
                    if (this.timeFormat) {
                        if (element > startDate) {
                            return moment(element, ["HH:mm"]).format("hh:mm A");
                        }
                    }
                    if (element > startDate) {
                        return element;
                    }
                    return 0;
                }).filter((elem) => elem != 0);
        }
    },
    mounted: async function() {
        this.emptyFormValues();
        await this.pageLoadData();
        this.timeInterval();
        this.changePickupFormat();
        this.localization = {
            switchOpen: this.$t('LBL_OPEN'),
            switchClosed: this.$t('LBL_CLOSED'),
            placeholderOpens: this.$t('LBL_OPENS'),
            placeholderCloses: this.$t('LBL_CLOSES'),
            addHours: this.$t('LBL_ADD_SLOTS'),
            open: {
                invalidInput: 'Please enter an opening time in the 12 hour format (ie. 08:00 AM). You may also enter "24 hours".',
                greaterThanNext: 'Please enter an opening time that is before the closing time.',
                lessThanPrevious: 'Please enter an opening time that is after the previous closing time.',
                midnightNotLast: "Midnight can only be selected for the day's last closing time."
            },
            close: {
                invalidInput: 'Please enter a closing time in the 12 hour format (ie. 05:00 PM). You may also enter "24 hours" or "Midnight".',
                greaterThanNext: 'Please enter a closing time that is after the opening time.',
                lessThanPrevious: 'Please enter a closing time that is before the next opening time.',
                midnightNotLast: "Midnight can only be selected for the day's last closing time."
            },
            t24hours: this.$t('LBL_24HOURS'),
            midnight: this.$t('LBL_MIDNIGHT'),
            days: {
                monday: this.$t('LBL_MONDAY'),
                tuesday: this.$t('LBL_TUESDAY'),
                wednesday: this.$t('LBL_WEDNESDAY'),
                thursday: this.$t('LBL_THURSDAY'),
                friday: this.$t('LBL_FRIDAY'),
                saturday: this.$t('LBL_SATURDAY'),
                sunday: this.$t('LBL_SUNDAY'),
                newYearsEve: 'New Year\'s Eve', // prettier-ignore
                newYearsDay: 'New Year\'s Day', // prettier-ignore
                martinLutherKingJrDay: 'Martin Luther King, Jr. Day',
                presidentsDay: 'Presidents\' Day', // prettier-ignore
                easter: 'Easter',
                memorialDay: 'Memorial Day',
                independenceDay: 'Independence Day',
                fourthOfJuly: '4th of July',
                laborDay: 'Labor Day',
                columbusDay: 'Columbus Day',
                veteransDay: 'Veterans Day',
                thanksgivingDay: 'Thanksgiving Day',
                christmasEve: 'Christmas Eve',
                christmas: 'Christmas',
            }
        };
    },
    computed: {
        isComplete() {
            return (
                this.adminsData.saddr_name == '' || this.adminsData.saddr_country_id == '' || this.adminsData.saddr_state_id == '' || this.adminsData.saddr_city == '' || this.adminsData.saddr_address == '' || this.adminsData.saddr_postal_code == '' || this.adminsData.saddr_phone == ''
            );
        },
        startTimes() {
            return this.times.map((element) => {
                if (this.timeFormat) {
                    return moment(element, ["HH:mm"]).format("hh:mm A")
                }
                return element;
            })
        }
    }
}
</script>

