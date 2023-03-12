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
    <!--Begin:: App Aside Mobile Toggle-->
    <button class="grid-layout-close" id="user_profile_aside_close">
        <i class="la la-close"></i>
    </button>
    <!--End:: App Aside Mobile Toggle-->

    <sidebar :tab="type"></sidebar>

    <!--Begin:: App Content-->
    <div class="grid-layout-content">

        <div class="row">
            <div class="col-xl-12">
                <div class="portlet">
                    <form class="form form--label-right">
                        <div class="portlet__body">
                            <div class="section">
                                <div class="section__body upload--media">
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                           <h3 class="upload__title">{{ $t('LBL_STEP') }} 1</h3>
                                        </div>
                                        <div class="col-lg-9">
                                            <p class="upload__text">{{ $t('LBL_STEP1_INFO') }}</p>
                                            <div class="row align-items-center">
                                                <div class="col-md-6">
                                                    
                                                    <vue-dropzone id="dropzone_2" ref="uploadZipDropzone" v-on:vdropzone-success="zipUploadSuccess" :options="dropzoneOptionsZip" :useCustomSlot=true  class="dropzone dropzone-default dropzone-success dz-clickable">
                                                        <div class="dropzone-msg">
                                                            <i class="fas fa-upload fa-5x mb-4"></i>                                      
                                                            <h3 class="dropzone-msg-title">{{$t('LBL_UPLOAD_ZIP_FILE_FOR_IMAGES')}}</h3>                                                
                                                        </div> 
                                                    </vue-dropzone>
                                                    <span v-if="errors.first('file')" class="error text-danger">{{ errors.first('file') }}</span>
                                                </div>
                                                <div class="col-md-6 ">
                                                    <div class="dropzone dropzone-default dropzone-brand dz-clickable vue-dropzone " v-bind:class="[excel_file != '' ? '': 'disabledbutton']" data-ratio=""  @click.prevent="downloadSampleSheet">
                                                        <div class="dz-message">                                         
                                                        <div class="dropzone-msg">                                         
                                                            <i class="fas fa-download fa-5x mb-4"></i>
                                                            <h3 class="dropzone-msg-title">{{$t('LBL_DOWNLOAD_SAMPLE_CSV')}}</h3>                                               
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                           <h3 class="upload__title">{{ $t('LBL_STEP') }} 2</h3>
                                        </div>
                                        <div class="col-lg-9">
                                            <p class="upload__text">{{ $t('LBL_STEP2_INFO') }}</p>
                                            <div class="row align-items-center">
                                                <div class="col-md-6">
                                                    <div class="radio-block">
                                                        <label class="radio">
                                                        <input
                                                            type="radio"
                                                            checked="checked"
                                                            value="brands"
                                                           v-model="download_module_type"
                                                            name="moduletype"
                                                        />
                                                        {{$t('LBL_BRANDS')}}
                                                        <span></span>
                                                        </label>
                                                        <label class="radio">
                                                        <input
                                                            type="radio"
                                                            name="moduletype"
                                                            v-model="download_module_type"
                                                            value="categories"
                                                        />
                                                        {{$t('LBL_CATEGORIES')}}
                                                        <span></span>
                                                        </label>
                                                        <label class="radio">
                                                        <input
                                                            type="radio"
                                                            name="moduletype"
                                                            v-model="download_module_type"
                                                            value="products"
                                                        />
                                                        {{$t('LBL_PRODUCTS')}}
                                                        <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="dropzone dropzone-default dropzone-brand dz-clickable vue-dropzone" v-bind:class="[download_module_type != '' ? '': 'disabledbutton']" data-ratio=""  @click.prevent="exportSampleSheet">
                                                        <div class="dz-message">                                         
                                                        <div class="dropzone-msg">                                         
                                                            <i class="fas fa-download fa-5x mb-4"></i>
                                                            <h3 class="dropzone-msg-title">{{$t('LBL_DOWNLOAD_SAMPLE_CSV')}}</h3>                                               
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-3">
                                           <h3 class="upload__title">{{ $t('LBL_STEP') }} 3</h3>
                                        </div>
                                        <div class="col-lg-9">
                                            <p class="upload__text">{{ $t('LBL_STEP3_INFO') }}</p>
                                            <div class="row align-items-center">
                                                <div class="col-md-6">
                                                    <div class="radio-block">
                                                        <label class="radio">
                                                        <input
                                                            type="radio"
                                                           value="brands"
                                                           v-model="upload_module_type"
                                                            name="upload_type"
                                                            @click="selectModule('brands')"
                                                        />
                                                        {{$t('LBL_BRANDS')}}
                                                        <span></span>
                                                        </label>
                                                        <label class="radio">
                                                        <input
                                                            type="radio"
                                                            name="upload_type"
                                                            v-model="upload_module_type"
                                                            value="categories"
                                                            @click="selectModule('categories')"

                                                        />
                                                        {{$t('LBL_CATEGORIES')}}
                                                        <span></span>
                                                        </label>
                                                        <label class="radio">
                                                        <input
                                                            type="radio"
                                                            name="upload_type"
                                                            v-model="upload_module_type"
                                                            value="products"
                                                            @click="selectModule('products')"
                                                        />
                                                        {{$t('LBL_PRODUCTS')}}
                                                        <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <vue-dropzone id="dropzone_2" ref="importMediaDropzone"  v-bind:class="[upload_module_type != '' ? '': 'disabledbutton']" v-on:vdropzone-success="excelUploadSuccess" :options="dropzoneOptionsExcel" :useCustomSlot=true  class="dropzone dropzone-default dropzone-success dz-clickable ">
                                                        <div class="dz-message">  
                                                        <div class="dropzone-msg">
                                                            <i class="fas fa-upload fa-5x mb-4"></i>                                      
                                                            <h3 class="dropzone-msg-title">{{$t('LBL_UPLOAD_CSV_WITH_IMAGE_URLS')}}</h3>                                                
                                                        </div>
                                                        </div>
                                                    </vue-dropzone>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!--End:: App Content-->
</div>
</div>
</div>
</template>
<script>

import sidebar from './sidebar';
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
export default {
    components: {
        vueDropzone: vue2Dropzone,
        'sidebar': sidebar
    },
    data: function() {
        return {
            type: 'media',
            adminBaseUrl: adminBaseUrl,
            dropzoneOptionsZip: {
                url: adminBaseUrl + '/importexport/media-zip',
                thumbnailWidth: 150,
                addRemoveLinks: true,
                maxFiles: 1,
                acceptedFiles: '.zip',
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('value')
                }
            },            
            download_module_type: '',
            excel_file: '',
            upload_module_type: '',
            dropzoneOptionsExcel: {
                url: adminBaseUrl + '/importexport/upload-sheet/0',
                thumbnailWidth: 150,
                addRemoveLinks: true,
                maxFiles: 1,
                acceptedFiles: '.xls',
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('value')
                }
            },
        }
    },
    methods: {
        selectModule: function(type) {
            this.$refs.importMediaDropzone.setOption('url', this.adminBaseUrl + '/importexport/upload-sheet/' + type);
        },
        zipUploadSuccess: function( file, response) {
            if (response.status == false) {
                toastr.error(response.message, '', toastr.options);
                return;
            }
            toastr.success(response.message, '', toastr.options);
            this.$refs.uploadZipDropzone.removeAllFiles( true );
            this.excel_file = response.data;
        },
        exportSampleSheet: function() {
            if (this.download_module_type == '') {
                toastr.error('Please select a module first', '', toastr.options);
                return;
            }
            window.open(adminBaseUrl + '/importexport/download-sheet/' + this.download_module_type);
            toastr.success('Sample sheet downloaded', '', toastr.options);
        },
        excelUploadSuccess: function( file, response) {
            this.$refs.importMediaDropzone.removeAllFiles( true );
            if (response.status == false) {
                toastr.error(response.message, '', toastr.options);
                return;
            }
            toastr.success(response.message, '', toastr.options);
            if(response.data != ''){
                window.open(response.data, "_blank"); 
            }
        },
        downloadSampleSheet: function(){
            if(this.excel_file == ""){
                return false;
            }
           window.open(this.excel_file, "_blank"); 
        }
    }
}
</script>
