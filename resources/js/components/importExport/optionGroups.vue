<template>
<div>
    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_IMPORT_EXPORT') }}</h3>
            </div>
            <div class="subheader__toolbar">
                <div class="subheader__wrapper">
                    <a href="javascript:void(0);" class="btn btn-subheader" @click="exportOptionGroup"><i class="fas fa-cloud-download-alt"></i>{{ $t('BTN_EXPORT') }}</a>
                    <a href="javascript:void(0);" class="btn btn-white subheader__btn-options custom-file-btn"><i class="fas fa-cloud-upload-alt"></i>
                    <input type="file" name="optionUpload" @change="importOptions" ref="optionInputFile">{{ $t('BTN_IMPORT') }}</a>
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
                                        <li class="nav-item"> <a class="nav-link" v-bind:class="[optionWithData == 2 ? 'active' : '']" data-toggle="tab" @click="optionWithData == 1 ? changeOptionData(2) : ''" href="javascript:;" role="tab" >{{ $t('LNK_WITHOUT_DATA') }}</a> </li>

                                        <li class="nav-item"> <a class="nav-link" v-bind:class="[optionWithData == 1 ? 'active' : '']" data-toggle="tab" @click="optionWithData == 2 ? changeOptionData(1) : ''" href="javascript:;" role="tab">{{$t('LNK_WITH_DATA')}}</a> </li>
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
                                        <input type="text" :placeholder="$t('PLH_SEARCH')" id="generalSearch" :disabled="optionWithData == 2" class="form-control" v-model="search" @keyup="searchRecords"> 
                                        <span class="input-icon__icon input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <hr class="m-0">
                            <div class="portlet__body" id="element">
                                <div class="table-wrapper">
                                <vue-excel-editor ref="grid" v-model="optionData">
                                    <vue-excel-column field="option_id" :label="$t('LBL_OPTIONID')"  width="105px"  readonly/>
                                    <vue-excel-column field="option_name" :label="$t('LBL_OPTIONNAME')" type="string" width="300px"/>
                                    <vue-excel-column field="option_type" :label="$t('LBL_OPTIONISCOLOR')" type="select" width="150px" :options="['Yes','No']"/>
                                    <vue-excel-column field="option_has_image" :label="$t('LBL_OPTIONHASIMAGE')" type="select" width="150px" :options="['Yes','No']"/>
                                    <vue-excel-column field="option_has_sizechart" :label="$t('LBL_OPTIONHASSIZECHART')" type="select" width="150px" :options="['Yes','No']"/>
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
            type: 'optionGroups',
            optionError:[],
            optionWithData:2,
            rowNumbers:10,
            optionData: [],
            lastId:'',
            lastOptionId : '',
            clicked: 0,
            search:'',
        }
    },
    methods: {
        exportOptionGroup: function() {
            window.open(adminBaseUrl + '/options/export?time='+ Math.random());
        },
        importOptions: function(event) {
            $(".custom-file-btn").addClass('spinner spinner--sm spinner--right spinner--light');            
            let importFile = event.target.files || e.dataTransfer.files;     
            if (importFile.length == 1) {
                let formData = new FormData();
                formData.append('file', importFile[0]);
                this.$http.post(adminBaseUrl + '/options/import', formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then((response) => { //success
                        this.$refs.optionInputFile.value = '';
                        if (response.data.status == false) {
                            $(event.target).removeClass('spinner spinner--sm spinner--right spinner--light');
                            toastr.error(response.data.message, '', toastr.options);
                            return;
                        }
                        toastr.success(response.data.message, '', toastr.options);
                        $(".custom-file-btn").removeClass('spinner spinner--sm spinner--right spinner--light');
                        if(response.data.data != '') {
                            window.open(response.data.data, '_blank');
                        }
                    }, (response) => { //error
                        this.$refs.optionInputFile.value = '';
                        $(".custom-file-btn").removeClass('spinner spinner--sm spinner--right spinner--light');
                        if (Array.isArray(response.data.errors.file)) {
                            toastr.error(response.data.errors.file[0], '', toastr.options);
                        } else {
                            toastr.error(response.data.message, '', toastr.options);
                        }
                    });
            } else {
                $(".custom-file-btn").removeClass('spinner spinner--sm spinner--right spinner--light');
            }
        },
        save:function(){
            this.clicked = 1;
            this.$http.post(adminBaseUrl + "/options/excel-update", this.optionData).then(response => {
                this.clicked = 0;
                if(response.data.status == false){
                    toastr.error(response.data.message, '', toastr.options);
                    return
                }
                toastr.success(response.data.message,'',toastr.options);
                this.optionData = response.data.data['optionData'];
                if (this.optionData.length != 0) {
                    window.open(response.data.data['optionError'], '_blank');
                }
            }, (response) => { //error
                toastr.error(response.data.message, '', toastr.options);
            });
            
        },
        addRow:function(){
            for(let i=0; i < this.rowNumbers; i++){
                this.newRow();
            }
        },
        newRow: function() {
            let optionId = this.lastOptionId + 1;
            this.lastOptionId = optionId;
            this.optionData.push({
                option_id: optionId,
                option_name: '',
                option_is_color :'',
                option_has_image :'',
                option_has_sizechart :''
            });
        },
        changeOptionData: function(tab) {
            this.optionWithData = tab;
            this.lastOptionId = this.lastId;
            if(this.optionWithData == 1) {
                this.searchRecords();
            } else {
                this.search = '';
                this.optionData = [];
                this.addRow();                
            }
        },
        searchRecords: function() {
            if (this.optionWithData == 1) {
                let form = new FormData();
                form.append('option',this.search);
                this.$http.post(adminBaseUrl + "/options/options-for-excel", form).then(response => {
                    if (response.data != '') {
                        this.lastOptionId = response.data.data.maxId;
                        this.optionData = response.data.data.option;                        
                    }
                }, (response) => { //error
                    toastr.error("Something went wrong", '', toastr.options);
                });
            } else {
                toastr.error("Select with data option for search", '', toastr.options);
                return;
            }
        },
        setInitialWithoutData: function() {
            for(let i = 0; i < this.rowNumbers; i++) {
                this.optionData.push({
                    option_id: '',
                    option_name: '',
                    option_is_color :'',
                    option_has_image :'',
                    option_has_sizechart :''
                });
            }
        },
        getlastOptionId() {
            this.$http.get(adminBaseUrl + '/options/get-last-id').then((response) => {
                this.lastOptionId = response.data.data;
                let optionId = this.lastOptionId + 1;
                this.lastOptionId = this.lastId = optionId;
                this.optionData = [];
                this.addRow();
            });
        }
    },
    mounted: function() {
        this.setInitialWithoutData();
        this.getlastOptionId();        
    },
}
</script>