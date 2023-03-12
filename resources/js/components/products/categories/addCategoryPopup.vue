<template>
    <b-modal id="categoryPopup" centered title="BootstrapVue">
        <template v-slot:modal-header>
            <h5 class="modal-title" id="exampleModalLabel">
                <span >{{$t('LBL_ADD_CATEGORY') }}</span>
            </h5>
            <button type="button" class="close" @click="$bvModal.hide('categoryPopup')"></button>
        </template>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{$t('LBL_CATEGORY_NAME') }}</label>
                    <input type="text" class="form-control" v-model="cat_name" placeholder="Name" name="cat_name" v-validate="'required'" data-vv-validate-on="none"  :data-vv-as="$t('LBL_CATEGORY_NAME')" >
                    <span v-if="errors.first('cat_name')" class="error text-danger">{{ errors.first('cat_name') }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{$t('LBL_PARENT_CATEGORY') }}</label>
                    <treeselect
                        name="cat_parent"
                        v-if="prodCat.length > 0"
                        v-model="cat_parent"
                        :defaultExpandLevel="Infinity"
                        :isDefaultExpanded="true"
                        :options="prodCat"
                        :searchable="true"
                        :open-on-click="true"
                        :close-on-select="true"
                        :placeholder="$t('PLH_SELECT_ONE')"
                        v-validate="'required'"
                        :data-vv-as="$t('LBL_CATEGORY')"
                        data-vv-validate-on="none"
                    />
                    
                </div>
            </div>
        </div>
        <template v-slot:modal-footer>
            <button type="submit" class="btn btn-brand" @click="validateCategoryRecord()" :disabled="!isComplete"><span>{{ $t('BTN_CREATE') }}</span></button>
        </template>
    </b-modal>
</template>
<script>

import Treeselect from "@riophae/vue-treeselect";
export default {
    props: ["prodCat"],
    components: {
        Treeselect
    },
    data: function () {
        return {
            cat_name : '',
            cat_parent : 0
        }
    },
    methods: {
        empty: function () {
            this.cat_name = '';
            this.cat_parent = 0;
        },
        validateCategoryRecord : function () {
            this.$validator.validateAll().then(res=>{
                if (res) {
                    this.save();
                }
            });
        },
        save : function() {
            let formData = new FormData();
            formData.append('cat_name',this.cat_name);
            formData.append('cat_parent',this.cat_parent);
            this.$http.post(adminBaseUrl + '/product/categories', formData)
            .then((response) => { //success
                if (response.data.status == false) {
                    toastr.error(response.data.message, '', toastr.options);
                    return;
                }
                toastr.success(response.data.message, '', toastr.options);                
                this.empty();
                this.$emit('setCategory',response.data.data);
            }, (response) => { //error
                this.validateErrors(response);
            });
        },
        validateErrors: function (response) {
            let jsondata = response.data;
            Object.keys(jsondata.errors).forEach(key => {
                this.errors.add({field: key,msg: jsondata.errors[key][0]  });
            });
        }
    },
    mounted: function() {
		this.$root.$on('emptyCategoryPopup', () => this.empty());
	},
    computed: {
        isComplete() {
            return this.cat_name;
        },
    }
}
</script>
