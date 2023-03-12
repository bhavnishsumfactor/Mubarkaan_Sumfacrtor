<template>
    <b-modal id="packagePopup" centered title="BootstrapVue">
        <template v-slot:modal-header>
            <h5 class="modal-title" id="exampleModalLabel">
                <span v-if="recordData.sbpkg_id != ''">{{$t('LBL_EDIT_PACKAGE') }}</span> <span v-else>{{$t('LBL_ADD_PACKAGE') }}</span>
            </h5>
            <button type="button" class="close" @click="$bvModal.hide('packagePopup')"></button>
        </template>

            <input v-if="recordData.sbpkg_id != ''" type="hidden" name="id" v-model="recordData.sbpkg_id" >
               <h6 class="mb-5">{{$t('LBL_SIZE_AND_WEIGHT') }}</h6>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{$t('LBL_LENGTH') }}</label>
                            <input type="text" class="form-control" v-model="recordData.sbpkg_length" placeholder="L" name="sbpkg_length" v-validate="'required'" data-vv-validate-on="none" :data-vv-as="$t('length')">
                            <span v-if="errors.first('sbpkg_length')" class="error text-danger">{{ errors.first('sbpkg_length') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{$t('Width') }}</label>
                            <input type="text" class="form-control" v-model="recordData.sbpkg_width" placeholder="W" name="sbpkg_width" v-validate="'required'" data-vv-validate-on="none" :data-vv-as="$t('width')">
                            <span v-if="errors.first('sbpkg_width')" class="error text-danger">{{ errors.first('sbpkg_width') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{$t('Height') }}</label>
                            <input type="text" class="form-control" v-model="recordData.sbpkg_heigth" placeholder="H" name="sbpkg_heigth" v-validate="'required'" data-vv-validate-on="none" :data-vv-as="$t('height') ">
                            <span v-if="errors.first('sbpkg_heigth')" class="error text-danger">{{ errors.first('sbpkg_heigth') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <select class="form-control" v-model="recordData.sbpkg_dimension_type" name="sbpkg_dimension_type" v-validate="'required'" data-vv-validate-on="none" :data-vv-as="$t('type')">
                                 <option disabled value="">{{$t('Select Type') }}</option>
                                <option v-for="(dimension, index) in dimensionsData" :Value="index"> {{dimension}}</option>
                             </select>
                             <span v-if="errors.first('sbpkg_dimension_type')" class="error text-danger">{{ errors.first('sbpkg_dimension_type') }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>{{$t('Name') }}</label>
                            <input type="text" class="form-control" v-model="recordData.sbpkg_name" placeholder="Custom box" name="sbpkg_name" v-validate="'required'" data-vv-validate-on="none"  :data-vv-as="$t('name')" >
                            <span v-if="errors.first('sbpkg_name')" class="error text-danger">{{ errors.first('sbpkg_name') }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="checkbox mb-0">
                                <input type="checkbox" v-model="recordData.sbpkg_default">{{$t('Set as default package') }}<span></span>
                            </label>
                            <span class="form-text text-muted">{{$t('Package size and weight is used to calculate shipping rates at checkout') }}.</span>
                        </div>
                    </div>
                </div>
        <template v-slot:modal-footer>
             <button type="submit" class="btn btn-brand" @click="validatePackageRecord()"><span v-if="recordData.sbpkg_id != ''">{{$t('Update package') }}</span> <span v-else>{{$t('Add Package') }}</span></button>
        </template>
    </b-modal>
    <!--end::Modal-->
</template>
<script>

export default {
    props:['dimensionsData', 'recordData'],
    inject: ['$validator'],
    data: function () {
        return {
        }
    },
    methods: {
        validatePackageRecord: function () {
            this.$validator.validateAll().then(res=>{
                if (res) {
                    this.saveAndUpdate(this.recordData);
                }
            });
        },
        saveAndUpdate: function (input) {
            this.$http.post(adminBaseUrl+'/shipping', this.$serializeData(input))
            .then((response) => { //success
                if (response.data.status == true) {
                    toastr.success(response.data.message, '', toastr.options);
                    this.$emit('closePopup');
                    this.$emit('pageRecords');

                }
                if (response.data.status == false) {
                    toastr.error(response.data.message, '', toastr.options);
                    return;
                }
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
    mounted() {
        // console.log('dimensionsData >>>', this.dimensionsData);
    }
}
</script>
