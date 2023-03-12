<template>
    <b-modal id="brandPopup" centered title="BootstrapVue">
        <template v-slot:modal-header>
            <h5 class="modal-title" id="exampleModalLabel">
                <span >{{$t('LBL_ADD_BRAND') }}</span>
            </h5>
            <button type="button" class="close" @click="$bvModal.hide('brandPopup')"></button>
        </template>                
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{$t('LBL_BRAND_NAME') }} <span class="required">*</span></label>
                    <input type="text" class="form-control" v-model="brand_name" :placeholder="$t('LBL_BRAND_NAME')" name="brand_name" v-validate="'required'" data-vv-validate-on="none"  :data-vv-as="$t('LBL_BRAND_NAME')" >
                    <span v-if="errors.first('brand_name')" class="error text-danger">{{ errors.first('brand_name') }}</span>
                </div>
            </div>
        </div>
        <template v-slot:modal-footer>
            <button type="submit" class="btn btn-brand" @click="validateBrandRecord()" :disabled="!isComplete"><span>{{$t('BTN_CREATE') }}</span></button>
        </template>
    </b-modal>
</template>
<script>

export default {
    data: function () {
        return {
            brand_name : ''
        }
    },
    methods: {
        empty: function () {
            this.brand_name = '';
        },
        validateBrandRecord : function () {
            this.$validator.validateAll().then(res=>{
                if (res) {
                    this.save(this.brand_name);
                }
            });
        },
        save : function(input) {
            let formData = new FormData();
            formData.append('brand_name',input);
            this.$http.post(adminBaseUrl + '/brands', formData)
            .then((response) => { //success
                if (response.data.status == false) {
                    toastr.error(response.data.message, '', toastr.options);
                    return;
                }
                toastr.success(response.data.message, '', toastr.options);
                this.empty();
                this.$emit('setBrand',response.data.data.brand);
            }, (response) => { //error
                this.validateErrors(response);
            });
        },
        validateErrors: function (response) {
            let jsondata = response.data;
            Object.keys(jsondata.errors).forEach(key => {
                this.errors.add({field: key,msg: jsondata.errors[key][0]  });
            });
        },
    },
    mounted: function() {
		this.$root.$on('emptyBrandPopup', () => this.empty());
	},
    computed: {
        isComplete() {
            return this.brand_name;
        },
    },
}
</script>
