<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{ $t('LBL_IMPORT_EXPORT') }}</h3>
                </div>
                <div class="subheader__toolbar">
                    <div class="subheader__wrapper">
                        <a href="javascript:void(0);" class="btn btn-subheader" @click="exportBrands"><i class="fas fa-cloud-download-alt"></i>{{ $t('BTN_EXPORT') }}</a>
                        <a href="javascript:void(0);" class="btn btn-white subheader__btn-options custom-file-btn"><i class="fas fa-cloud-upload-alt"></i>
                        <input type="file" name="brandUpload" @change="importBrands" ref="brandInputFile">{{ $t('BTN_IMPORT') }}</a>
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
                                            <li class="nav-item"> <a class="nav-link" v-bind:class="[brandWithData == 2 ? 'active' : '']" data-toggle="tab" @click="brandWithData==1 ? changeBrandData(2) : ''" href="javascript:;" role="tab" >{{$t('LNK_WITHOUT_DATA')}}</a> </li>
                                            <li class="nav-item"> <a class="nav-link" v-bind:class="[brandWithData == 1 ? 'active' : '']" data-toggle="tab" @click="brandWithData==2 ? changeBrandData(1) : ''" href="javascript:;" role="tab">{{ $t('LNK_WITH_DATA') }}</a> </li>
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
                                            <input type="text" :placeholder="$t('PLH_SEARCH')" id="generalSearch" :disabled="brandWithData == 2" class="form-control" v-model="search" @keyup="searchRecords"> 
                                            <span class="input-icon__icon input-icon__icon--left">
                                                <span><i class="la la-search"></i></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <hr class="m-0">
                                <div class="portlet__body element" >
                                    <div class="table-wrapper">
                                        <vue-excel-editor ref="grid" v-model="branddata" width="100%">
                                            <vue-excel-column width="150px" field="brand_id" :label="$t('LBL_BRANDID')" :sticky="true" type="number"  readonly />
                                            <vue-excel-column field="brand_name" :label="$t('LBL_BRANDNAME')" type="string" :sticky="true"/>
                                            <vue-excel-column field="brand_publish" :label="$t('LBL_BRANDPUBLISH')" type="select" :options="['Yes','No']" :sticky="true"/>
                                        </vue-excel-editor>
                                    </div>
                                </div>
                                <div class="portlet__foot">
                                    <div class="text-center">
                                        <button class="btn btn-wide btn-brand gb-btn gb-btn-primary" type="submit" v-bind:class="clicked==1 && 'gb-is-loading'" @click="save()">{{ $t('BTN_IMPORT_EXPORT_SAVE')}}</button>
                                    </div>
                                </div>
                            </div>
                            <!-- end::Portlet -->
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
            type : 'brandImport',
            brandError:[],
            brandWithData:2,
            rowNumbers:10,
            branddata: [],
            lastId:'',
            lastBrandId : '',
            clicked: 0,
            search:''
        }
    },
    methods: {
        exportBrands: function() {
            window.open(adminBaseUrl + '/brands/export?time='+ Math.random());
        },               
        importBrands: function(event) {
            $(".custom-file-btn").addClass('spinner spinner--sm spinner--right spinner--light');            
            let importFile = event.target.files || e.dataTransfer.files;
            if (importFile.length == 1) {
                let formData = new FormData();
                formData.append('file', importFile[0]);
                this.$http.post(adminBaseUrl + '/brands/import', formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then((response) => { //success
                        this.$refs.brandInputFile.value = '';
                        if (response.data.status == false) {
                            $(".custom-file-btn").removeClass('spinner spinner--sm spinner--right spinner--light');
                            toastr.error(response.data.message, '', toastr.options);
                            return;
                        }
                        toastr.success(response.data.message, '', toastr.options);
                        $(".custom-file-btn").removeClass('spinner spinner--sm spinner--right spinner--light');
                        if (response.data.data != '') {
                            window.open(response.data.data, '_blank');
                        }
                    }, (response) => { //error
                        this.$refs.brandInputFile.value = '';
                        $(".custom-file-btn").removeClass('spinner spinner--sm spinner--right spinner--light');
                        if (Array.isArray(response.data.errors.file)) {
                            toastr.error(response.data.errors.file[0], '', toastr.options);
                        } else {
                            toastr.error(response.data.message, '', toastr.options);
                        }
                    });
            } else {
                $(".custom-file-btn").removeClass('spinner spinner--sm spinner--right spinner--light');
                /*this.errors.add({
                    field: 'file',
                    msg: 'Please upload the file first'
                });*/
            }
        },
        save:function() {
            this.clicked = 1;
            this.$http.post(adminBaseUrl + "/brand/excel-update", this.branddata).then(response => {
                this.clicked = 0;
                if(response.data.status == false) {
                    toastr.error(response.data.message, '', toastr.options);
                    return
                }
                toastr.success(response.data.message,'',toastr.options);                
                let branddata = response.data.data['brandData'];
                if (branddata.length != 0) {
                    window.open(response.data.data['brandError'], '_blank');
                } else {
                   this.searchRecords();
                }
            }, (response) => { //error
                toastr.error(response.data.message, '', toastr.options);
            });
            
        },
        addRow: function(){
            for(let i = 0; i < this.rowNumbers; i++){
                this.newRow();
            }
        },
        newRow:function() {
            let brandId = this.lastBrandId + 1;
            this.lastBrandId = brandId;
            this.branddata.push({
                brand_id: brandId,
                brand_name: '',
                brand_publish :''
            });            
        },
        changeBrandData: function(tab) {
            this.brandWithData = tab;
            this.lastBrandId = this.lastId;
            if(this.brandWithData == 1) {
                this.branddata = []
                this.searchRecords();
            }else{   
                this.branddata = []
                this.addRow();
            }
        },
        searchRecords: function() {
            let thisObj = this;
            if (this.brandWithData == 1) {
                let form = new FormData();
                form.append('brand',this.search);
                this.$http.post(adminBaseUrl + "/brand/brands-for-excel", form).then(response => {
                    if(response.data != '') {
                        let branddataTemp = response.data.data;
                        let brandEmptyCells = [];
                        thisObj.branddata = branddataTemp //$.merge( $.merge( [], branddataTemp ), brandEmptyCells );
                    }
                }, (response) => { //error
                });
            } else {
                return;
            }
        },
        getLastBrandId: function() {
            this.$http.get(adminBaseUrl + '/brand/get-last-id').then((response) => {
                this.lastBrandId = response.data.data;
                let brandId = this.lastBrandId + 1;
                this.lastBrandId = this.lastId = brandId;
                this.branddata = [];
                this.addRow();
            });
        },
        setInitialWithoutData: function() {

            for(let i = 0; i < this.rowNumbers; i++) {
                this.branddata.push({
                    brand_id: '',
                    brand_name: '',
                    brand_publish :''
                });
            }
        }
    },
    mounted: function() {
        this.setInitialWithoutData();
        this.getLastBrandId();
    },
}
</script>
