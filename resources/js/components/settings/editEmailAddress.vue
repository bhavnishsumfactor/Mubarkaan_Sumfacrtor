<template>
<div>
    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_CHANGE_EMAIL') }}</h3>
                <div class="subheader__breadcrumbs">
                    <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                    <span class="subheader__breadcrumbs-separator"></span>
                    <span class="subheader__breadcrumbs-link">{{$t('LBL_CHANGE_EMAIL')}}</span>
                </div>
            </div>
        </div>
    </div>

  <div class="container">
<div class="grid-layout">
    <button class="grid-layout-close" id="user_profile_aside_close">
        <i class="la la-close"></i>
    </button>
    <profile-sidebar :tab="'editEmail'"></profile-sidebar>
    <div class="grid-layout-content">
        <div class="row">
            <div class="col-xl-12">
                <div class="portlet portlet--height-fluid">                    
                    <div class="portlet__body">
                        <div class="section">
                            <div class="section__body">
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">{{ $t('LBL_CURRENT_EMAIL') }}</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" v-model="admin_current_email" disabled/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">{{ $t('LBL_NEW_EMAIL') }}</label>
                                            <div class="col-lg-9">
                                                <input type="text" name="admin_new_email" :data-vv-as="$t('LBL_EMAIL_ADDRESS')" v-validate="'required|email'" data-vv-validate-on="none" class="form-control" v-model="admin_new_email" />
                                                <span v-if="errors.first('admin_new_email')" class="error text-danger">{{ errors.first('admin_new_email') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="portlet__foot">
                        <div class="row justify-content-center">
                            <div class="col-auto">
                                <button type="submit" @click="editEmail"  :disabled="!isComplete || clicked==1" v-bind:class="clicked==1 && 'gb-is-loading'" class="btn btn-brand btn-wide gb-btn gb-btn-primary">{{ $t('BTN_SUBMIT') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <Alert
            :alertPopText="$t('LBL_VERIFY_EMAIL_TITLE')"
            :subText="$t('LBL_VERIFY_EMAIL_SUB_TEXT')"
            ></Alert>
        </div>
    </div>
</div>
</div>
</div>
</template>
<script>


import profileSidebar from './profileSidebar';
import AlertModel from '../common/popups/emailAlert';

const tableFields = {
    'admin_password': '',
    'admin_name': '',
    'confirmpassword': '',
    'admin_OTP': '',
    'role_name': ''
};
export default {
    components: {
        'profile-sidebar': profileSidebar,
        'Alert':AlertModel
    },
    data: function() {
        return {
            adminsData: tableFields,
            uploadedImage: '',
            clicked: 0,
            admin_new_email: '',
            admin_current_email: '',
            modelId: "emailAlertModel",
        }
    },
    computed: {
        isComplete() {
            return this.admin_new_email;
        },
    },
    methods: {
        editEmail: function() {
            this.$validator.validateAll().then(res => {
                if (res) {
                    this.updateEmail(this.admin_new_email);
                }
            });
        },
        getUser: function() {
            this.$http.get(adminBaseUrl + '/get-user').then((response) => {
                let roleName = 'Super Admin';
                if (response.data.data.admin_role_id != null) {
                    roleName = response.data.data.admin_role.role_name;
                }
                this.appName = response.data.appName;
                this.noImage = response.data.noImage;
                this.adminsData.role_name = roleName;
                this.adminsData.admin_name = response.data.data.admin_name;
                this.admin_current_email = response.data.data.admin_email;
                this.uploadedImage = this.$getFileUrl('profileImage', response.data.data.admin_profile_image.afile_record_id, 0, 'thumb');
            });
        },
        updateEmail: function(adminEmail) {
            this.clicked = 1;
            let formData = this.$serializeData({'email': adminEmail});
            this.$http.post(adminBaseUrl + '/update-email', formData)
                .then((response) => { //success
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    this.admin_new_email = '';
                    this.clicked = 0;
                    this.$bvModal.show(this.modelId);
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
        this.getUser();
    }
}
</script>
