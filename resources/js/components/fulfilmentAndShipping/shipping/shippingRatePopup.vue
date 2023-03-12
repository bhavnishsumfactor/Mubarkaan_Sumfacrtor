<template>
  <b-modal id="shippingRatePopup" centered title="BootstrapVue">
    <template v-slot:modal-header>
        <h5 class="modal-title">
            <span v-if="rateTableData.srate_id != ''">{{ $t('LBL_EDIT_RATE') }}</span> <span v-else>{{ $t('LBL_CREATE_RATE') }}</span>
        </h5>
        <button type="button" class="close" @click="$bvModal.hide('shippingRatePopup')"></button>
    </template>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>{{ $t('LBL_RATE_NAME') }}</label>
                <input type="text" class="form-control" v-model="rateTableData.srate_name" name="srate_name" v-validate="'required'" data-vv-validate-on="none" :data-vv-as="$t('LBL_RATE_NAME')">
                <span v-if="errors.first('srate_name')" class="error text-danger">{{ errors.first('srate_name') }}</span>
                <span class="form-text text-muted">{{ $t('Customers will see this at checkout') }}.</span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>{{ $t('LBL_RATE_COST') }}</label>
                <input type="text" v-model="rateTableData.srate_cost" class="form-control" name="srate_cost" v-validate="'required'" data-vv-validate-on="none" :data-vv-as="$t('LBL_RATE_COST')">
                <span v-if="errors.first('srate_cost')" class="error text-danger">{{ errors.first('srate_cost') }}</span>
            </div>
        </div>
    </div>
     <div class="mt-3"><a href="javascript:;" class="link font-bolder" @click="rateTableData.shipRateCondistion = true" v-if="rateTableData.shipRateCondistion == false">{{ $t('LNK_ADD_CONDITIONS') }}</a><a href="javascript:;" class="link font-bolder" @click="rateTableData.shipRateCondistion = false" v-else>{{ $t('LNK_REMOVE_CONDITIONS') }}</a></div>
    <div v-if="rateTableData.shipRateCondistion == true" class="mt-3">
        <div class="row mb-3">
            <div class="col">
                <div class="radio-list">
                     <label class="radio" v-for="(rateCondition,index) in rateConditions" :key="'ratecondition'+index">
                        <input type="radio" name="radio1" :value="index" v-model="rateTableData.srate_type">
                        <p v-if="rateTableData.profile_type == 0">{{ $t('LBL_BASED_ON_ITEM') }} {{rateCondition}}</p>
                        <p v-else>{{ $t('LBL_BASED_ON_ORDER_ITEM_S') }} {{rateCondition}}</p>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group"><!--weightType-->
                    <label>{{ $t('LBL_MINIMUM') }} {{rateConditions[rateTableData.srate_type]}} ({{rateTableData.srate_type == 1 ? weightType : currencySymbol}})</label>
                    <input type="text" class="form-control" v-model="rateTableData.srate_min_value" name="srate_min_value" v-validate="'required|decimal'" data-vv-validate-on="none" :data-vv-as="$t('LBL_MINIMUM')">
                    <span v-if="errors.first('srate_min_value')" class="error text-danger">{{ errors.first('srate_min_value') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ $t('LBL_MAXIMUM') }} {{rateConditions[rateTableData.srate_type]}} ({{rateTableData.srate_type == 1 ? weightType : currencySymbol}})</label>
                    <input type="text" v-model="rateTableData.srate_max_value" class="form-control" name="srate_max_value" v-validate="'required|decimal'" data-vv-validate-on="none" :data-vv-as="$t('LBL_MAXIMUM')">
                    <span v-if="errors.first('srate_max_value')" class="error text-danger">{{ errors.first('srate_max_value') }}</span>
                </div>
            </div>
        </div>
    </div>
    <template v-slot:modal-footer>
        <button type="submit" class="btn btn-brand gb-btn gb-btn-primary" @click="validateRateRecord()" :disabled='!isComplete || clicked==1' v-bind:class="clicked==1 && 'gb-is-loading'"><span v-if="rateTableData.srate_id != ''">{{ $t('BTN_RATES_UPDATE') }}</span> <span v-else>{{ $t('BTN_RATES_ADD') }}</span></button>
    </template>
</b-modal>
<!--end::Modal-->

</template>

<script>
  	export default{
        props:['rateTableData', 'rateConditions', 'validateErrors', 'weightType', 'currencySymbol', 'clicked'],
        inject: ['$validator'],
        data: function () {
            return {
               
            }
        },
        computed: {
            isComplete () {
                return this.rateTableData.srate_name && this.rateTableData.srate_cost
                && (this.rateTableData.shipRateCondistion == true ? (this.rateTableData.srate_min_value && this.rateTableData.srate_max_value): true);
            }
        },
        methods: {
            validateRateRecord: function () {
                
                this.$validator.validateAll().then(res=>{
                    if (res) {
                        this.clicked = 1;
                        let formData  = this.$serializeData(this.rateTableData);
                        this.$emit('saveProfileData', formData,'rate');
                    }
                });
            }
        }
    }
</script>
