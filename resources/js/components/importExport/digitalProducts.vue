<template>
<div>
    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_IMPORT_EXPORT') }}</h3>
            </div>
            <div class="subheader__toolbar">
                <div class="subheader__wrapper">
                    <a href="javascript:void(0);" class="btn btn-subheader" @click="exportDigitalProducts"><i class="fas fa-cloud-download-alt"></i>{{ $t('BTN_EXPORT') }}</a>
                    <a href="javascript:void(0);" class="btn btn-white subheader__btn-options custom-file-btn"><i class="fas fa-cloud-upload-alt"></i>
                    <input type="file" name="productUpload" @change="importProducts" ref="digitalInputFile">{{ $t('BTN_IMPORT') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="grid-layout">
            <sidebar :tab="type"></sidebar>
            <div class="grid-layout-content">
                
                <div class="row">
                    <div class="col-lg-12">
                        <!--begin::Portlet-->
                        <div class="portlet portlet--height-fluid portlet--tabs" >
                            <div class="portlet__head">
                                <div class="portlet__head-toolbar">
                                    <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold" role="tablist">
                                        <li class="nav-item"> <a class="nav-link" v-bind:class="[productWithData == 2 ? 'active' : '']" data-toggle="tab" @click="changeProductData(2)" href="javascript:;" role="tab" >{{$t('LNK_WITHOUT_DATA')}}</a> </li>

                                        <li class="nav-item"> <a class="nav-link" v-bind:class="[productWithData == 1 ? 'active' : '']" data-toggle="tab" @click="changeProductData(1)" href="javascript:;" role="tab">{{$t('LNK_WITH_DATA')}}</a> </li>
                                    </ul>
                                </div>
                                <div class="portlet__head-toolbar align-items-center">
                                    <div class="portlet__head-actions">
                                        <!--<a href="javascript:;" class="btn btn-icon btn-outline-secondary" :title="$t('Full Screen')" onclick="var el = document.getElementById('element'); el.webkitRequestFullscreen();">
                                            <i class="fas fa-expand-arrows-alt"></i>
                                        </a>-->
                                        <a href="javascript:;" class="btn btn-icon btn-outline-secondary" :title="$t('TTL_ADD_10_ROWS')" @click="addRow()">
                                            <i class="fa fa-plus"></i>
                                        </a>                                            
                                    </div>
                                </div>
                            </div>
                            <div class="portlet__body pb-0 bg-gray flex-grow-0">
                                <div class="form-group">
                                    <div class="input-icon input-icon--left">
                                        <input type="text" :placeholder="$t('PLH_SEARCH')" id="generalSearch" :disabled="productWithData == 2" class="form-control" v-model="search" @keyup="searchRecords"> 
                                        <span class="input-icon__icon input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <hr class="m-0">
                            <div class="portlet__body" id="element">
                                <div class="table-wrapper">
                                <vue-excel-editor ref="grid" v-model="productData"  width="900px">
                                    <vue-excel-column field="prod_id" :label="$t('LBL_PRODUCTID')" />
                                    <vue-excel-column field="prod_name" :label="$t('LBL_PRODUCTTITLE')" />
                                    <vue-excel-column field="brand_name" :label="$t('LBL_BRANDNAME')" />
                                    <vue-excel-column field="cat_id" :label="$t('LBL_CATEGORYID')" />
                                    <vue-excel-column field="cat_name" :label="$t('LBL_CATEGORYNAME')" />
                                    <vue-excel-column field="taxgrp_name" :label="$t('LBL_TAXCATEGORY')" />
                                    <vue-excel-column field="prod_description" :label="$t('LBL_PRODUCTDESCRIPTION')" />
                                    <vue-excel-column field="prod_model" :label="$t('LBL_PRODUCTMODEL')" />
                                    <vue-excel-column field="pc_threshold_stock_level" :label="$t('LBL_TRACKINVENTORY')" type="select" :options="['Yes','No']"/>
                                    <vue-excel-column field="prod_min_order_qty" :label="$t('LBL_MINPURCHASEQUANTITY')" />
                                    <vue-excel-column field="prod_max_order_qty" :label="$t('LBL_MAXPURCHASEQUANTITY')" />
                                    <vue-excel-column field="prod_cost" :label="$t('LBL_COSTPRICE')" />
                                    <vue-excel-column field="price" :label="$t('LBL_SELLINGPRICE')" />
                                    <vue-excel-column field="available_quantity" :label="$t('LBL_AVAILABLEQUANTITY')" />
                                    <vue-excel-column field="sku" :label="$t('LBL_PRODUCTSKU')" />
                                    <vue-excel-column field="pc_isbn" :label="$t('LBL_PRODUCTISBN')" />
                                    <vue-excel-column field="pc_hsn" :label="$t('LBL_PRODUCTHSN')" />
                                    <vue-excel-column field="pc_sac" :label="$t('LBL_PRODUCTSAC')" />
                                    <vue-excel-column field="pc_upc" :label="$t('LBL_PRODUCTUPC')" />
                                    <vue-excel-column field="pc_video_link" :label="$t('LBL_PRODUCTVIDEOURL')" />
                                    <vue-excel-column field="publish" :label="$t('LBL_PRODUCTPUBLISH')" type="select" :options="['Yes','No']"/>
                                    <vue-excel-column field="prod_publish_from" :label="$t('LBL_PRODUCTPUBLISH_FROM')"/>
                                    <vue-excel-column field="pc_max_download_times" :label="$t('LBL_PRODUCTMAXDOWNLOADTIME')" type="number" />
                                    <vue-excel-column field="pc_download_validity_in_days" :label="$t('LBL_PRODUCTMAXVALIDITY')" type="number"/>
                                    <!--<vue-excel-column field="pov_default" :label="$t('LBL_PRODUCTDEFAULTVARIANT')" :options="['Yes','No']"/>-->
                                    <vue-excel-column field="inventory_management_level" :label="$t('LBL_PRODUCTTYPE')" />
                                    <!--<vue-excel-column field="option_id" :label="$t('LBL_OPTION_ID')"/>-->
                                    <div v-for="(item,key) in totalOption" :key="key">
                                        <vue-excel-column :field="'option_group_name_' + (parseInt(key) + parseInt(1))" :label="'Option_Group_'+ (parseInt(key))" />
                                        <vue-excel-column :field="'option_group_value_' + (parseInt(key) + parseInt(1))" :label="'Option_value_'+ (parseInt(key))" />
                                    </div>
                                </vue-excel-editor>
                                </div>
                            </div>
                            <div class="portlet__foot">
                                <div class="text-center">
                                    <button class="btn btn-wide btn-brand gb-btn gb-btn-primary" type="submit" v-bind:class="clicked==1 && 'gb-is-loading'" @click="save()">{{ $t('BTN_IMPORT_EXPORT_SAVE')}}</button>
                                </div>
                            </div>
                        </div>
                        <!--end::Portlet-->
                    </div>
                </div>
            </div>
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
            type: 'digitalProducts',
            productError:[],
            brandsOption:[],
            productWithData:2,
            rowNumbers:10,
            productData: [],
            lastId:'',
            lastProductId : '',
            clicked: 0,
            //type : 'digitalProduct',
            search:'',
            totalOption: [],
            increment:0
        }
    },
    methods: {
        exportDigitalProducts: function() {
            window.open(adminBaseUrl + '/products/export-digital?time='+ Math.random());
        },
        importProducts: function(event) {
            $(".custom-file-btn").addClass('spinner spinner--sm spinner--right spinner--light');
            let importFile = event.target.files || event.dataTransfer.files;        
            if (importFile.length == 1) {
                let formData = new FormData();
                formData.append('file', importFile[0]);
                this.$http.post(adminBaseUrl + '/products/import-digital', formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then((response) => { //success
                        this.$refs.digitalInputFile.value = '';
                        if (response.data.status == false) {
                            $(".custom-file-btn").removeClass('spinner spinner--sm spinner--right spinner--light');
                            toastr.error(response.data.message, '', toastr.options);
                            return;
                        }
                        toastr.success(response.data.message, '', toastr.options);
                        $(".custom-file-btn").removeClass('spinner spinner--sm spinner--right spinner--light');
                        if(response.data.data != '') {
                            window.open(response.data.data, '_blank');
                        }
                    }, (response) => { //error
                        this.$refs.digitalInputFile.value = '';
                        $(".custom-file-btn").removeClass('spinner spinner--sm spinner--right spinner--light');
                        if (Array.isArray(response.data.errors.file)) {
                            toastr.error(response.data.errors.file[0], '', toastr.options);
                        } else {
                            toastr.error(response.data.message, '', toastr.options);
                        }
                    });
            } else {
                this.$refs.digitalInputFile.value = '';
                $(".custom-file-btn").removeClass('spinner spinner--sm spinner--right spinner--light');
            }
        },        
        save: function() {
            this.clicked = 1;
            this.$http.post(adminBaseUrl + "/products/digital-product-excel-update", this.productData).then(response => {
                this.clicked = 0;
                if(response.data.status == false) {
                    toastr.error(response.data.message, '', toastr.options);
                    return
                }
                toastr.success(response.data.message, '', toastr.options);
                if (response.data.data.hasOwnProperty('productError')) {
                    window.open(response.data.data['productError'], '_blank');
                }
                this.productData = response.data.data['productData'];
            }, (response) => { //error
                toastr.error(response.data.message, '', toastr.options);
            });
        },
        addRow: function(){
            for(let i=0; i < this.rowNumbers; i++){
                this.newRow();
            }
        },
        newRow: function() {
            let productId = '';
            if (this.increment == 0) {
                productId = this.lastProductId + 1;
                this.lastProductId = this.lastId = productId;
                this.increment = 1;
            } 
            this.productData.push({
                prod_id: productId,
                prod_name : '',
                brand_name :'',
                cat_name : '',
                taxgrp_name : '',
                prod_description : '',
                pc_threshold_stock_level : '',
                prod_min_order_qty : '',
                prod_max_order_qty : '',
                prod_cost: '',
                price: '',
                available_quantity: '',
                sku: '',
                pc_isbn: '',
                pc_hsn: '',
                pc_sac: '',
                pc_upc: '',
                pc_video_link: '',
                publish: '',
                pc_max_download_times: '',
                pc_download_validity_in_days: '',
                pov_default: '',
                inventory_management_level : ''
            })
        },
        changeProductData: function(tab) {
            this.productWithData = tab;
            //this.lastProductId = this.lastId;
            if (this.productWithData == 1) {
                this.increment = 0;
                this.searchRecords();
            } else {
                this.search = '';
                this.productData = [{
                    prod_id: this.lastProductId,                   
                    prod_name : '',
                    brand_name :'',
                    cat_name : '',
                    taxgrp_name : '',
                    prod_description : '',
                    pc_threshold_stock_level : '',
                    prod_min_order_qty : '',
                    prod_max_order_qty : '',
                    prod_cost:'',
                    price:'',
                    available_quantity:'',
                    sku:'',
                    pc_isbn:'',
                    pc_hsn:'',
                    pc_sac:'',
                    pc_upc:'',
                    pc_video_link:'',
                    publish:'',
                    pc_max_download_times:'',
                    pc_download_validity_in_days:'',
                    pov_default: '',
                    inventory_management_level : ''
                }];
            }
        },
        searchRecords: function() {
            if (this.productWithData == 1) {
                let form = new FormData();
                form.append('product',this.search);
                this.$http.post(adminBaseUrl + "/products/digital-product-for-excel", form).then(response => {
                    if(response.data != '') {
                        this.productData = response.data.data;
                    }
                }, (response) => { //error
                    toastr.error("Something went wrong", '', toastr.options);
                });
            } else {
                toastr.error("Select with data option for search", '', toastr.options);
                return;
            }
        },
        getlastProductId: function() {
            this.$http.get(adminBaseUrl + '/products/get-last-id').then((response) => {
                this.lastProductId = response.data.data['last-id'];
                let totalOptionCount = response.data.data['total-option-count'];
                if (totalOptionCount > 1) {
                    for(let i=1; i<= totalOptionCount; i++) {
                       this.totalOption.push(i);
                    }
                }
                this.productData = [];
                this.addRow();                
            });
        },
        setInitialWithoutData: function() {
            for(let i = 0; i < this.rowNumbers; i++) {
                this.productData.push({
                    prod_id: '',
                    prod_name : '',
                    brand_name :'',
                    cat_name : '',
                    taxgrp_name : '',
                    prod_description : '',
                    pc_threshold_stock_level : '',
                    prod_min_order_qty : '',
                    prod_max_order_qty : '',
                    prod_cost: '',
                    price: '',
                    available_quantity: '',
                    sku: '',
                    pc_isbn: '',
                    pc_hsn: '',
                    pc_sac: '',
                    pc_upc: '',
                    pc_video_link: '',
                    publish: '',
                    pc_max_download_times: '',
                    pc_download_validity_in_days: '',
                    pov_default: '',
                    inventory_management_level : ''
                });
            }
        }
    },
    mounted: function() {
        this.setInitialWithoutData();
        this.getlastProductId();
    }
}
</script>
