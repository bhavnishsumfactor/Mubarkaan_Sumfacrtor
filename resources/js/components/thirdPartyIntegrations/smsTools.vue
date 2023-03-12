<template>
<div>
<div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_THIRD_PARTY_INTEGRATIONS') }}</h3>
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

    <sidebar :tab="type"></sidebar>

    <!--Begin:: App Content-->
    <div class="grid-layout-content">
        <div class="portlet portlet--height-fluid" id="packageConfForm">        
            <div class="portlet__body">
                <div class="section">
                    <div class="section__body pt-10">
                        <div class="form-group row" v-if="packagesLength > 1">
                            <label class="col-lg-3 col-form-label">{{ $t('LBL_SELECT_PACKAGE') }}</label>
                            <div class="col-lg-9">
                                <select name="selected_package" class="form-control" v-model="selected_package" @change="getConfigurations($event)" v-validate="'required'" data-vv-as="" data-vv-validate-on="none">
                                    <option v-for="(smsPackage, key) in packages" :value="key" :key="key">{{smsPackage}}</option>
                                </select>
                                <span v-if="errors.first('selected_package')" class="error text-danger">{{ errors.first('selected_package') }}</span>
                            </div>
                        </div>
                        <div class="separator separator--border-dashed separator--space-lg" v-if='packagesLength > 1 && selectedPackageHtml != ""'></div>
                        <div v-if='selectedPackageHtml != ""' v-html='selectedPackageHtml' :class="'row justify-content-center'"></div>                                    
                    </div>
                </div>
            </div>
            <div class="portlet__foot">
                <div class="form__actions text-center">
                    <button type="button" class="btn btn-brand btn-wide gb-btn gb-btn-primary" @click="saveSettings" :disabled='clicked==1' v-bind:class="clicked==1 && 'gb-is-loading'">{{$t('BTN_SETTINGS_UPDATE')}}</button>
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

import sidebar from './sidebar';
export default {
    components: {
        'sidebar': sidebar
    },
    data: function() {
        return {
            type: 'smstools',
            packages: [],
            packagesLength: 0,
            configurations: [],
            selected_package: '',
            selectedPackageHtml: '',
            selectedPackageName: '',
            clicked: 0
        }
    },
    methods: {
        getSettings: function() {
            this.$http.get(adminBaseUrl + '/sms-templates/packages').then((response) => {
                this.packages = response.data.data.packages;
                this.packagesLength = Object.keys(this.packages).length;
                if (this.packagesLength == 1 && response.data.data.packageHtml != '') {
                    for (let [key, value] of Object.entries(this.packages)) {
                        this.selected_package = key;
                        this.selectedPackageName = value;
                    }
                    this.selectedPackageHtml = response.data.data.packageHtml;
                        setTimeout(() => {
                            if ( $('#packageConfForm input[name="twilio_status"]:checked').val() == 0 || $('#packageConfForm input[name="twilio_status"]:checked').val() === undefined) {
                                $("#packageConfForm").find("input[type='text']").prop("disabled", true);
                                $("#packageConfForm").find("button").prop("disabled", true);
                                $(".img__wrapper").addClass('disabled');
                            } else {
                                $("#packageConfForm").find("input[type='text']").prop("disabled", false);
                                $("#packageConfForm").find("button").prop("disabled", false);
                                $(".img__wrapper").removeClass('disabled');
                            }
                    }, 100);
                    
                }
            });
        },
        getConfigurations: function(event) {
            this.selectedPackageHtml = '';
            for (let [key, value] of Object.entries(this.packages)) {
                if (key == event.target.value) {
                    this.selectedPackageName = value;
                }
            }
            this.$http.get(adminBaseUrl + '/sms-templates/configurations/' + event.target.value).then((response) => {
                this.selectedPackageHtml = response.data;
            });
        },
        saveSettings: function(input) {
            this.clicked = 1;
            var obj = {};
            $("#packageConfForm").find('input, radio').each(function() {
                obj[$(this).attr("name")] = $(this).val();
            });
            let formData = this.$serializeData(obj);
            this.$http.post(adminBaseUrl + '/sms-templates/configurations/' + this.selected_package, formData)
                .then((response) => {
                    this.clicked = 0;
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    toastr.success(response.data.message, '', toastr.options);
                }, (response) => {
                    this.clicked = 0;
                    let jsondata = response.data;
                    Object.keys(jsondata.errors).forEach(key => {
                        $('[name="' + key + '"]').parent().find('span.error').text(jsondata.errors[key][0]).removeAttr('hidden');
                    });
                });
        },
        changeStatus:function(value) {
            var obj = {};
            obj['twilio_status'] = value;
            let formData = this.$serializeData(obj);
            this.$http.post(adminBaseUrl + '/sms-templates/configurations/' + this.selected_package, formData)
                .then((response) => {
                    this.clicked = 0;
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    toastr.success(response.data.message, '', toastr.options);
                }, (response) => {
                    this.clicked = 0;
                    let jsondata = response.data;
                    Object.keys(jsondata.errors).forEach(key => {
                        $('[name="' + key + '"]').parent().find('span.error').text(jsondata.errors[key][0]).removeAttr('hidden');
                    });
            });
        }
    },
    mounted: function() {
        this.getSettings();
        let thisVal = this;
        $(document).on('change','.YK-twilioStatus', function(e) {
            let status = e.target.checked ? parseInt(1) : parseInt(0);
            if (status == 0) {
                $("#packageConfForm").find("input[type='text']").prop("disabled", true);
                $("#packageConfForm").find("button").prop("disabled", true);
                $(".img__wrapper").addClass('disabled');
                thisVal.changeStatus(status);
            } else if (status == 1) {
                $("#packageConfForm :input").prop("disabled", false);
                $("#packageConfForm").find("button").prop("disabled", false);
                thisVal.changeStatus(status);
                 $(".img__wrapper").removeClass('disabled');
            }
        });
    }
}
</script>
