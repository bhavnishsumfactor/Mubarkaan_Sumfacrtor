<template>
    <b-modal id="requestDataModel" centered title="BootstrapVue">
        <template v-slot:modal-header>
            <h5 class="modal-title">{{ $t('LBL_REQUEST_MY_DATA') }}</h5>
            <button type="button" class="close" @click="$bvModal.hide('requestDataModel')">
                <span aria-hidden="true">×</span>
            </button>
        </template>
        <form class="form form--login gdprrequest-form" id="gdprrequest-form" method="POST" action="javascript:void(0)">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-floating__group">
                        <textarea class="form-control form-floating__field" name="ureq_purpose" v-model="userData.ureq_purpose" style="resize: none;" v-bind:class="[ userData.ureq_purpose != '' ? 'filled' : '']"></textarea>
                        <label class="form-floating__label">{{ $t('LBL_PURPOSE_OF_REQUEST') }}</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <p class="text-muted text-center"><a class="link" target="_blank" :href="termsCondition"> {{ $t("LNK_CLICK_HERE")}} </a> {{ $t("LBL_TO_READ_POLICIES")}}</p>
                    </div>
                </div>
            </div>
        </form>
        <template v-slot:modal-footer>
             <div class="d-flex w-100 justify-content-center">
            <button class="btn btn-brand btn-wide gb-btn gb-btn-primary" :disabled="userData.ureq_purpose == '' || clicked==1" v-bind:class="clicked==1 && 'gb-is-loading'"  @click="requestMyData()" type="button">
                {{$t('BTN_SEND_REQUEST')}}
            </button>
             </div>
        </template>
    </b-modal>
</template>
<script>
const tableFields = {
    ureq_purpose: ''
};
export default {
    data: function() {
        return {
            baseUrl: baseUrl,
            userData: tableFields,
            clicked : 0,
            termsCondition:''
        }
    },
    methods: {
        requestMyData: function() {
            this.clicked = 1;
            let formData = this.$serializeData(this.userData);
            this.$axios.post(baseUrl + "/user/update-gdpr-data", formData)
                .then((response) => {
                this.clicked = 0;
                if (response.status == 200) {
                    toastr.success(response.data.message, "", toastr.options);
                    setTimeout(() => {
                        window.open(response.data.data);
                    }, 1000);
                }
                this.emptyData();
                this.$bvModal.hide("requestDataModel");
            }).catch(err => {
                this.clicked = 0;
                Object.keys(err.response.data.errors).forEach(function (key) {
                    toastr.error(err.response.data.errors[key][0], "", toastr.options);
                });
            });
        },
        emptyData: function() {
            this.userData.ureq_purpose = '';
        }
    },
    mounted: function() {
        this.termsCondition = window.termsCondition;
    }
}
</script>