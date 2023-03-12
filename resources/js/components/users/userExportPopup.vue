<template>
    <b-modal  id="exportDetailPopup" centered title="BootstrapVue" @hidden="closeModal" >
        <template v-slot:modal-header="{ close }">
            <h5 class="modal-title"><span></span>
                {{ $t('LBL_USERS_EXPORT') }}
            </h5>
            <button type="button" class="close" @click="close()"></button>
        </template>

        <div class="export-wrapper">
            <div class="form-group row">
                <label class="col-md-5 col-form-label">{{ $t('LBL_SIGNUP_DATE') }}</label>
                <div class="col-md-7">
                    <date-picker v-model="dateRange" type="date" range value-type="format" format="YYYY-MM-DD" :placeholder="$t('PLH_SELECT_DATE')" class="w-100" :input-class="'form-control'" :disabled-date="(date) => date >= new Date()"></date-picker>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-5 col-form-label">{{ $t('LBL_USER_STATUS') }}</label>
                <div class="col-md-7">
                    <div class="dropdown">
                        <button class="btn btn-default  btn-block dropdown-toggle" type="button" id="dropdownUserStatus" v-model="user_status" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ user_status == '' ? $t('BTN_ALL') : user_status }}</button>
                        <div class="dropdown-menu" aria-labelledby="dropdownUserStatus">
                            <a class="dropdown-item" href="javascript:;" @click="user_status = 'Active'">{{ $t('LBL_USER_ACTIVE') }}</a>
                            <a class="dropdown-item" href="javascript:;" @click="user_status = 'InActive'">{{ $t('LBL_USER_INACTIVE') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-5 col-form-label">{{ $t('LBL_PHONE_EMAIL_VERIFIED') }}</label>
                <div class="col-md-7">
                    <div class="dropdown">
                        <button class="btn btn-default  btn-block dropdown-toggle" type="button" id="dropdownUserVerified" v-model="user_verified" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ user_verified == '' ? $t('BTN_ALL') : user_verified }}</button>
                        <div class="dropdown-menu" aria-labelledby="dropdownUserVerifiedStatus">
                            <a class="dropdown-item" href="javascript:;" @click="user_verified = 'Yes'">{{ $t('LBL_YES') }}</a>
                            <a class="dropdown-item" href="javascript:;" @click="user_verified = 'No'">{{ $t('LBL_NO') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-5 col-form-label">{{ $t('LBL_GROUPS') }}</label>
                <div class="col-md-7">
                    <div class="dropdown">
                        <button class="btn btn-default  btn-block dropdown-toggle" type="button" id="dropdownUserGroup" v-model="user_group" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ user_group == '' ? $t('BTN_ALL') : user_group }}</button>
                        <div class="dropdown-menu" aria-labelledby="dropdownUserVerifiedStatus">
                            <a class="dropdown-item" href="javascript:;" @click="user_group = group.ugroup_name" v-for="group in groups" :key="group.ugroup_id">
                                {{ group.ugroup_name }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <template  v-slot:modal-footer>
            <button type="button" class="btn btn-brand mx-auto" @click="exportData">{{ $t('BTN_EXPORT')}}</button>
        </template>
    </b-modal>
</template>
<script>
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
export default {  
    data: function() {
        return {      
            dateRange: [],
            user_status: 'All',
            user_verified: 'All',
            user_group: 'All'
        };
    },
    props: ['groups'],
    methods: {
        exportData: function() {            
            let formData = this.$serializeData({
                date: this.dateRange,
                userStatus: this.user_status,
                userVerified: this.user_verified,
                user_group: this.user_group,
            });
            this.$http.post(adminBaseUrl + "/users/export-users", formData).then(response => {
                if (response.data.status == false) {
                    toastr.error(response.data.message, "", toastr.options);
                    return;
                }
                toastr.success(response.data.message, "", toastr.options);
                this.$bvModal.hide("exportDetailPopup");
                window.open(response.data.data, "_blank");
            });
        },
        closeModal: function() {
        },
        exportInvoice: function() {
        }
    }
};
</script>
