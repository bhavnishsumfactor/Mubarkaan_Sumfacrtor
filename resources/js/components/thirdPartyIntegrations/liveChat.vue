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
        <div class="portlet portlet--height-fluid">
            <div class="portlet__body">
                <div class="section">
                    <div class="section__body pt-10">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="info__wrapper">
                                            <div class="info__media">
                                                <div class="img__wrapper" v-bind:class="[adminsData.LIVE_CHAT_STATUS == 0 ? 'disabled' : '']">
                                                    <img :src="baseUrl+'/admin/images/third-party/live-chat-api.svg'" alt="" />
                                                </div>
                                            </div>
                                            <div class="info__content">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <h3>{{$t('LBL_LIVE_CHAT')}}</h3>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <span class="switch switch--sm">
                                                            <label>
                                                                <input type="checkbox" name="live_chat_status" v-model="adminsData.LIVE_CHAT_STATUS" @change="updateStatus($event)">
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="card-body">                                       

                                <div class="form-group row mb-0">
                                    <label class="col-lg-3 col-form-label">{{ $t('LBL_LIVE_CHAT_SCRIPT') }} <span class="required">*</span></label>
                                    <div class="col-lg-9">
                                        <textarea rows="10" class="form-control" v-model="adminsData.LIVE_CHAT_CODE" name="liveChatCode" v-validate="adminsData.LIVE_CHAT_STATUS == 1 ? 'required' : ''" data-vv-validate-on="none" :data-vv-as="$t('LBL_LIVE_CHAT_SCRIPT')" :disabled='adminsData.LIVE_CHAT_STATUS == 0'></textarea>
                                        <span v-if="errors.first('liveChatCode')" class="error text-danger">{{ errors.first('liveChatCode') }}</span>
                                    </div>
                                </div>
                                            </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="portlet__foot">
                <div class="form__actions text-center">
                    <button type="button" class="btn btn-brand btn-wide gb-btn gb-btn-primary" @click="updateSettings()" :disabled='!isComplete || clicked==1' v-bind:class="clicked==1 && 'gb-is-loading'">{{$t('BTN_SETTINGS_UPDATE')}}</button>
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
const tableFields = {
    'LIVE_CHAT_CODE': '',
    'LIVE_CHAT_STATUS': ''
};
export default {
    components: {
        'sidebar': sidebar
    },
    data: function() {
        return {
            adminsData: tableFields,
            type: 'livechat',
            clicked: 0,
            baseUrl:baseUrl
        }
    },
    methods: {
        getSettings: function() {
            let formData = this.$serializeData({'keys':['LIVE_CHAT_CODE', 'LIVE_CHAT_STATUS']});
            this.$http.post(adminBaseUrl + '/settings/get', formData).then((response) => {
                if (response.data.status == false) {
                    toastr.error(response.data.message, '', toastr.options);
                    return;
                }
                this.adminsData = response.data.data;
                this.adminsData.LIVE_CHAT_STATUS = parseInt(response.data.data.LIVE_CHAT_STATUS);
            });
        },
        updateSettings: function() {
            this.$validator.validateAll().then(res => {
                if (res) {
                    this.clicked = 1;
                    this.saveRecords();
                } else {
                    this.clicked = 0;
                }
            });
        },
        updateStatus: function(e) {
            this.adminsData.LIVE_CHAT_STATUS = e.target.checked ? parseInt(1) : parseInt(0);
            if(!this.adminsData.LIVE_CHAT_STATUS){
                this.saveRecords();
            }
        },
        saveRecords: function(){
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
    },
    computed: {
        isComplete() {
            return (
                this.adminsData.LIVE_CHAT_STATUS != 0 && (this.adminsData.LIVE_CHAT_CODE != '' ? true : false)
            );
        }
    }
}
</script>
