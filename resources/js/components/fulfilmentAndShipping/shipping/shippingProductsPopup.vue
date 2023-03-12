<template>
    <b-modal id="shippingProductsPopup" centered title="BootstrapVue" size="lg">
        <template v-slot:modal-header>
            <h5 class="modal-title" id="exampleModalLabel">
            <span>{{$t('LBL_PRODUCTS') }}</span>
        </h5>
        <button type="button" class="close" @click="$bvModal.hide('shippingProductsPopup')"></button>
    </template>

    <div class="">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="input-icon input-icon--left">
                        <input type="text" class="form-control YK-search" :placeholder="$t('PLH_SEARCH_PRODUCT_FOR_THIS_PROFILE')" @keyup="searchRecords">
                        <span class="input-icon__icon input-icon__icon--left">
                            <span><i class="la la-search"></i></span>
                        </span>
                    </div>
                </div>
            </div>
        </div> 
        <div class="shipping-products" v-if="productsRecords.length > 0">
            <perfect-scrollbar :options="{suppressScrollX: true}" style="height:400px">
                <ul  class="list-group list-group-triple">
                    <li class="list-group-item" v-for="(productsRecord, key) in productsRecords">
                        <label class="shipping-label" :for="'prod'+key">                      

                            <div class="product-profile">
                                <div class="product-profile__thumbnail">
                                <img :src="baseUrl+'/yokart/image/productImages/'+productsRecord.prod_id+'/0/50-67'" alt="" width="50" :title="productsRecord.prod_name">
                                    </div>
                                    <div class="product-profile__data">
                                        <div class="title"><span class="text-body" :title="productsRecord.prod_name">{{productsRecord.prod_name}}</span></div></div></div>
                                        <label class="checkbox checkbox--brand">
                                <input type="checkbox" :id="'prod'+key" :value="productsRecord.prod_id" v-model="updatedProductsIds"> 
                                <span></span>
                            </label>
                        
                        </label>                         
                    </li>
                </ul>     
            </perfect-scrollbar>     
        </div> 
        <div class="text-center"> 
            <button type="submit" class="btn btn-brand" @click="saveProductsRecords()" :disabled="updatedProductsIds.length == 0">{{ $t('BTN_ADD_PRODUCTS') }}</button>
        </div>
    </div>
    <template v-slot:modal-footer>
                <pagination :pagination="pagination" :hideRecordsSelection="true" @currentPage="currentPage" v-if="productsRecords.length > 0"> </pagination>

</template>
</b-modal>
<!--end::Modal-->
</template>
<script>
  	export default{
        props:['productsRecords', 'baseUrl', 'pagination', 'selectedProductsIds'],
		inject: ['$validator'],
        data: function () {
            return {
               updatedProductsIds : [],
               loaded : false
            }
        },
        methods: {
            validateErrors: function (response) {
                let jsondata = response.data;
                Object.keys(jsondata.errors).forEach(key => {
                    this.errors.add({field: key,msg: jsondata.errors[key][0]  });
                });
            },
            currentPage: function (page) {
               this.searchRecords();
            },
            searchRecords: function () {
                let search =  $('.YK-search').val();
                this.$emit('searchProducts',this.pagination.current_page, search);
            },
            saveProductsRecords: function () {
                let formData = this.$serializeData({'products':this.updatedProductsIds});
                this.$emit('saveProfileData', formData,'products');
            }
        },
        updated: function() {
            if(this.loaded === false){
                this.updatedProductsIds = this.selectedProductsIds;
                this.loaded = true;
            }             
        }
    }
</script>
