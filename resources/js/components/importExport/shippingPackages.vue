<template>
<div>
    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_IMPORT_EXPORT') }}</h3>
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
                                            <li class="nav-item"> 
                                                <a class="nav-link" v-bind:class="[shippingWithData == 2 ? 'active' : '']" data-toggle="tab" @click="shippingWithData == 1 ? changeShipping(2) : ''" href="javascript:;" role="tab" >{{$t('LNK_WITHOUT_DATA')}}</a> 
                                            </li>
                                            <li class="nav-item"> 
                                                <a class="nav-link" v-bind:class="[shippingWithData == 1 ? 'active' : '']" data-toggle="tab" @click="shippingWithData == 2 ? changeShipping(1) : ''" href="javascript:;" role="tab">{{$t('LNK_WITH_DATA')}}</a> 
                                            </li>
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
                                            <input type="text" :placeholder="$t('PLH_SEARCH')" id="generalSearch" :disabled="shippingWithData == 2" class="form-control" v-model="search" @keyup="searchRecords"> 
                                            <span class="input-icon__icon input-icon__icon--left">
                                                <span><i class="la la-search"></i></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <hr class="m-0">
                                <div class="portlet__body" id="element">
                                    <div class="table-wrapper">
                                    <vue-excel-editor ref="grid" v-model="shippingData">
                                        <vue-excel-column field="sbpkg_id" :label="$t('LBL_PACKAGEID')" width="75px"  readonly/>
                                        <vue-excel-column field="sbpkg_name" :label="$t('LBL_PACKAGENAME')" type="string"  width=200px />
                                        <vue-excel-column field="sbpkg_length" :label="$t('LBL_PACKAGELENGTH')" type="number" width="125px" />
                                        <vue-excel-column field="sbpkg_width" :label="$t('LBL_PACKAGEWIDTH')" type="number" width="125px" />
                                        <vue-excel-column field="sbpkg_heigth" :label="$t('LBL_PACKAGEHEIGHT')" type="number" width="125px" /> 
                                        <vue-excel-column field="sbpkg_dimension_type" :label="$t('LBL_DIMENSIONTYPE')" type="string" width="125px" sticky/>                                   
                                    </vue-excel-editor>
                                    </div>
                                </div>
                                <div class="portlet__foot">
                                    <div class="text-center">
                                        <button class="btn btn-wide btn-brand gb-btn gb-btn-primary" type="submit" v-bind:class="clicked == 1 && 'gb-is-loading'" @click="save()">{{ $t('BTN_IMPORT_EXPORT_SAVE') }}</button>
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
            type: 'shippingPackages',           
            shippingError:[],
            shippingWithData:2,
            rowNumbers:10,
            shippingData: [],
            shippingDimension: [],
            lastId:'',
            lastShippingPackageId : '',
            clicked: 0,            
            search:'',
        }
    },
    methods: {
        exportShippingPackages: function() {
            window.open(adminBaseUrl + '/shipping-packages/export');
        },
        /*fileAdded: function( file) {
            let alreadyUploaded = this.$refs.importDropzone.getAcceptedFiles();      
            if (alreadyUploaded.length > 0) {
                this.$refs.importDropzone.removeFile(alreadyUploaded[0]);
            }
        },
        fileUploadSuccess: function( file, response) {
            $('.YK-importLangBtn').removeClass('disabled').removeAttr('disabled');
        },
        importShippingPackage: function(event) {
            $(event.target).addClass('spinner spinner--sm spinner--right spinner--light');
            //this.errors.clear();
            //let importFile = this.$refs.importDropzone.getAcceptedFiles();     
            let importFile = event.target.files || event.dataTransfer.files;       
            if (importFile.length == 1) {
                let formData = new FormData();
                formData.append('file', importFile[0]);
                this.$http.post(adminBaseUrl + '/shipping-packages/import', formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then((response) => { //success
                        if (response.data.status == false) {
                            $(event.target).removeClass('spinner spinner--sm spinner--right spinner--light');
                            toastr.error(response.data.message, '', toastr.options);
                            //this.$refs.importDropzone.removeAllFiles();
                            return;
                        }
                        toastr.success(response.data.message, '', toastr.options);
                        //this.$refs.importDropzone.removeAllFiles();
                        $(event.target).removeClass('spinner spinner--sm spinner--right spinner--light');
                        if(response.data.data != ''){
                            window.open(response.data.data, '_blank');
                        }
                    }, (response) => { //error
                        $(event.target).removeClass('spinner spinner--sm spinner--right spinner--light');
                        //this.validateErrors(response);
                        if (Array.isArray(response.data.errors.file)) {
                            toastr.error(response.data.errors.file[0], '', toastr.options);
                        } else {
                            toastr.error(response.data.message, '', toastr.options);
                        }
                    });
            } else {
                $(event.target).removeClass('spinner spinner--sm spinner--right spinner--light');
                this.errors.add({
                    field: 'file',
                    msg: 'Please upload the file first'
                });
            }
        },*/
        /*validateErrors: function(response) {
            let jsondata = response.data;
            Object.keys(jsondata.errors).forEach(key => {
                this.errors.add({
                    field: key,
                    msg: jsondata.errors[key][0]
                });
            });
        },*/
        save: function() {
            this.clicked = 1;
            this.$http.post(adminBaseUrl + "/shipping-packages/excel-update", this.shippingData).then(response => {
                this.clicked = 0;
                if(response.data.status == false){
                    toastr.error(response.data.message, '', toastr.options);
                    return
                }
                toastr.success(response.data.message,'',toastr.options);
                if (response.data.data['shippingError'] != '') {
                    window.open(response.data.data['shippingError'], '_blank');
                }
                this.shippingData = response.data.data['shippingData'];
            }, (response) => { //error
                toastr.error(response.data.message, '', toastr.options);
            });
        },
        addRow:function(){
            for(let i=0; i < this.rowNumbers; i++){
                this.newRow();
            }
        },
        newRow:function() {
            let packageId = parseInt(this.lastShippingId) + 1;
            this.lastShippingId = packageId;
            this.$refs.grid.newRecord({
                sbpkg_id: packageId,
                sbpkg_name: '',
                sbpkg_length :'',
                sbpkg_width :'',
                sbpkg_heigth :''
            })
        },
        changeShipping: function(tab) {
            this.shippingWithData = tab;
            this.lastShippingId = this.lastId;
            if (this.shippingWithData == 1) {
                this.searchRecords();
            }else {
                this.search = '';
                /*this.shippingData = [{            
                    sbpkg_id: this.lastId,
                    sbpkg_name: '',
                    sbpkg_length :'',
                    sbpkg_width :'',
                    sbpkg_heigth :''
                }];*/
                this.addRow();
            }
        },
        searchRecords() {
            if (this.shippingWithData == 1) {
                let form = new FormData();
                form.append('shipping',this.search);
                this.$http.post(adminBaseUrl + "/shipping-packages/get-data-excel", form).then(response => {
                    if(response.data.data != '') {
                        this.shippingData = response.data.data['shipping'];
                        this.shippingDimension = response.data.data['shippingDimension'];
                        this.lastShippingId = response.data.data['maxId'];
                        for(let i=0; i<= this.shippingData.length; i++) {
                            this.shippingData[i]['sbpkg_dimension_type'] = this.shippingDimension[this.shippingData[i]['sbpkg_dimension_type']];
                        }
                    }
                }, (response) => { //error
                    toastr.error("Something went wrong", '', toastr.options);
                });
            } else {
                toastr.error("Select with data option for search", '', toastr.options);
                return;
            }
        },
        getLastShippingId: function() {
            this.$http.get(adminBaseUrl + '/shipping-packages/get-last-id').then((response) => {
                this.lastShippingId = response.data.data;
                this.addRow();
            });
        }
    },
    mounted: function() {
        this.getLastShippingId();
    }
}
</script>
