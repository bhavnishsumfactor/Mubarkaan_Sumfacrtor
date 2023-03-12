<template>
<div>
    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_IMPORT_EXPORT') }}</h3>
            </div>
            <div class="subheader__toolbar">
                <div class="subheader__wrapper">
                    <a href="javascript:void(0);" class="btn btn-subheader" @click="exportCategories"><i class="fas fa-cloud-download-alt"></i>{{ $t('BTN_EXPORT') }}</a>
                    <a href="javascript:void(0);" class="btn btn-white subheader__btn-options custom-file-btn"><i class="fas fa-cloud-upload-alt"></i>
                    <input type="file" name="brandUpload" @change="importCategories" ref="categoryInputFile">{{ $t('BTN_IMPORT') }}</a>
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
                                        <li class="nav-item"> <a class="nav-link" v-bind:class="[categoryWithData == 2 ? 'active' : '']" data-toggle="tab" @click="categoryWithData == 1 ? changeCategory(2) : ''" href="javascript:;" role="tab" >{{$t('LNK_WITHOUT_DATA')}}</a> </li>

                                        <li class="nav-item"> <a class="nav-link" v-bind:class="[categoryWithData == 1 ? 'active' : '']" data-toggle="tab" @click="categoryWithData == 2 ? changeCategory(1) : ''" href="javascript:;" role="tab">{{$t('LNK_WITH_DATA')}}</a> </li>
                                    </ul>
                                </div>
                                <div class="portlet__head-toolbar align-items-center">
                                    <div class="portlet__head-actions">
                                        <a href="javascript:;" class="btn btn-icon btn-outline-secondary" :title="$t('TTL_ADD_10_ROWS')" @click="addRow()">
                                            <i class="fa fa-plus"></i>
                                        </a>                                            
                                    </div>
                                </div>
                            </div>
                            <div class="portlet__body pb-0 bg-gray flex-grow-0">
                                <div class="form-group">
                                    <div class="input-icon input-icon--left">
                                        <input type="text" :placeholder="$t('PLH_SEARCH')" id="generalSearch" :disabled="categoryWithData == 2" class="form-control" v-model="search" @keyup="searchRecords"> 
                                        <span class="input-icon__icon input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>                            
                            <hr class="m-0">
                            <div class="portlet__body" id="element">
                                <div class="table-wrapper">
                                <vue-excel-editor ref="grid" v-model="categoryData" width="100%">
                                    <vue-excel-column field="cat_id" :label="$t('LBL_CATEGORYID')"  width="105px"  readonly/>
                                    <vue-excel-column field="cat_name" :label="$t('LBL_CATEGORYNAME')" type="string"   width=400px />
                                    <vue-excel-column field="parent_id" :label="$t('LBL_CATEGORYPARENTID')" type="string" width=150px />
                                    <vue-excel-column field="parent_name" :label="$t('LBL_CATEGORYPARENT')" type="string" width=150px />
                                    <vue-excel-column field="cat_tax_code" :label="$t('LBL_CATEGORY_TAX_CODE')" type="string" width=150px />
                                    <vue-excel-column field="cat_publish" :label="$t('LBL_CATEGORYPUBLISH')" type="select" width=150px :options="['Yes','No']" sticky/>                                    
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
            type: 'categories',
            categoryError:[],
            categoryWithData:2,
            rowNumbers:10,
            categoryData: [],
            lastId: '',
            lastCategoryId : '',
            clicked: 0,
            displayTax: false,
            search:'',
        }
    },
    methods: {
        exportCategories: function() {
            window.open(adminBaseUrl + '/categories/export?time='+ Math.random());
        },        
        save: function(){
            this.clicked = 1;
            this.$http.post(adminBaseUrl + "/categories/excel-update", this.categoryData).then(response => {
                this.clicked = 0;
                if(response.data.status == false){
                    toastr.error(response.data.message, '', toastr.options);
                    return
                }
                toastr.success(response.data.message,'',toastr.options);
                this.categoryData = response.data.data['categoryData'];
                if (this.categoryData.length != 0) {
                    window.open(response.data.data['categoryError'], '_blank');
                } else {
                    this.categoryWithData = 1;
                    this.searchRecords();
                }
            }, (response) => { //error
                this.clicked = 0;
                toastr.error(response.data.message, '', toastr.options);
            });
        },
        addRow:function(){
            for(let i=0; i < this.rowNumbers; i++) {
                this.newRow();
            }
        },
        newRow: function() {
            let categoryId = this.lastCategoryId + 1;
            this.lastCategoryId = categoryId;
            this.categoryData.push({
                cat_id: categoryId,
                cat_name: '',
                cat_parent :'',
                cat_parent :'',
                cat_publish :''
            });           
        },
        changeCategory: function(tab) {
            this.categoryWithData = tab;
            this.lastCategoryId = this.lastId;
            if(this.categoryWithData == 1) {
                this.searchRecords();
            }else{
                this.search = '';
                this.categoryData = [];
                this.addRow();                
            }
        },
        searchRecords: function() {
            if (this.categoryWithData == 1) {
                let form = new FormData();
                form.append('category',this.search);
                this.$http.post(adminBaseUrl + "/categories/category-for-excel", form).then(response => {
                    if(response.data != '') {
                        this.categoryData = response.data.data.category;
                        this.lastCategoryId = response.data.data.maxId;
                        this.newRow();
                    }
                }, (response) => { //error
                    toastr.error("Something went wrong", '', toastr.options);
                });
            } else {
                toastr.error("Select with data option for search", '', toastr.options);
                return;
            }
        },
        getLastCategoryId: function() {
            let thisObj  = this;
            this.$http.get(adminBaseUrl + '/categories/get-last-id').then((response) => {
                thisObj.lastCategoryId = response.data.data.lastId;
                thisObj.displayTax = (response.data.data.taxCode == "1") ? false : true;
                thisObj.$refs.grid.fields.forEach((field) => {
                    if (field.name === 'cat_tax_code') field.invisible = thisObj.displayTax
                })
                thisObj.$forceUpdate();
                let categoryId = thisObj.lastCategoryId + 1;
                thisObj.lastCategoryId = thisObj.lastId = categoryId;
                thisObj.categoryData = [];
                thisObj.addRow();                
            });
        },
        importCategories: function(event) {
            $(".custom-file-btn").addClass('spinner spinner--sm spinner--right spinner--light');
            let importFile = event.target.files || e.dataTransfer.files;          
            if (importFile.length == 1) {
                let formData = new FormData();
                formData.append('file', importFile[0]);
                this.$http.post(adminBaseUrl + '/categories/import', formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then((response) => { //success
                        this.$refs.categoryInputFile.value = '';
                        if (response.data.status == false) {
                            $(".custom-file-btn").removeClass('spinner spinner--sm spinner--right spinner--light');
                            toastr.error(response.data.message, '', toastr.options);
                            return;
                        }
                        toastr.success(response.data.message, '', toastr.options);
                        $(".custom-file-btn").removeClass('spinner spinner--sm spinner--right spinner--light');
                        if(response.data.data != ''){
                            window.open(response.data.data, '_blank');
                        }
                    }, (response) => { //error
                        this.$refs.categoryInputFile.value = '';
                        $(".custom-file-btn").removeClass('spinner spinner--sm spinner--right spinner--light');
                        if (Array.isArray(response.data.errors.file)) {
                            toastr.error(response.data.errors.file[0], '', toastr.options);
                        } else {
                            toastr.error(response.data.message, '', toastr.options);
                        }
                    });
            } else {
                this.$refs.categoryInputFile.value = '';
                $(".custom-file-btn").removeClass('spinner spinner--sm spinner--right spinner--light');
            }
        },
        setInitialWithoutData: function() {
            for(let i = 0; i < this.rowNumbers; i++) {
                this.categoryData.push({
                    cat_id: '',
                    cat_name: '',
                    cat_parent :'',
                    cat_parent :'',
                    cat_publish :''
                });
            }
        }
    },
    mounted: function() {
        this.setInitialWithoutData();
        this.getLastCategoryId();
    }
}
</script>
<style scoped>
.error-color{
    color:white;
}
</style>