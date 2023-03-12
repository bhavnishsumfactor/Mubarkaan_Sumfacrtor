<template>
    <div>

        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{ $t('LBL_PACKING_SLIP') }}</h3>
                </div>
                <div class="subheader__toolbar">               
                    <router-link :to="{name: 'shipping'}" class="btn btn-white"> {{$t('BTN_BACK')}}</router-link>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                
                <div class="col-md-12" >
                    <div class="portlet portlet--height-fluid">
                        <div class="portlet__head" >
                            <div class="portlet__head-label">
                                <h3 class="portlet__head-title">{{ $t('LBL_EDITING_PACKING_SLIP') }} </h3>
                            </div>
                        </div>
                        <div class="portlet__body">
                            <div class="section">
                                <div class="section__body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>{{ $t('LBL_PACKINGSLIP_BODY') }} <a href="javascript:;" @click="resetTemplate" class="btn btn-icon btn-sm btn-label-brand ml-2"><i class="fa fa-undo"></i></a></label>
                                            <div class="form-group">
                                                <textarea class="form-control" id="editor" v-model="adminsData.PACKING_SLIP_HTML" name="packing_slip" v-validate="'required'" :data-vv-as="$t('LBL_PACKINGSLIP_BODY')" data-vv-validate-on="none"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>{{ $t('LBL_PACKINGSLIP_REPLACEMENTS') }}</label>
                                                <div v-html="adminsData.PACKING_SLIP_HTML_REPLACEMENT"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="portlet__foot">
                            <div class="row">
                                <div class="col">
                                    <router-link :to="{name: 'shipping'}" class="btn btn-secondary">{{ $t('BTN_DISCARD')}}</router-link>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-brand gb-btn gb-btn-primary" @click="updateSettings()" v-bind:class="clicked==1 && 'gb-is-loading'">{{ $t('BTN_PACKINGSLIP_UPDATE') }}</button>
                                </div>
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    const tableFields = {
        'PACKING_SLIP_HTML':'',
        'PACKING_SLIP_HTML_ORIGINAL':'',
        'PACKING_SLIP_HTML_REPLACEMENT':''
    };    
    export default {
        data: function() {
            return {
                activeThemeUrl: activeThemeUrl,
                ModalID: '#formModel',
                selectedPage: false,
                records: [],
                pagination: [],
                clicked: 0,
                loading: false,
                adminsData: tableFields,
                search: '',
                footer: '',
                header: '',
                myPlugins: [
                    'advlist autolink lists link image charmap',
                    'searchreplace visualblocks code fullscreen',
                    'print preview anchor insertdatetime media',
                    'paste code help wordcount table'
                ],
                toolBar: 'undo redo | formatselect | bold italic | \
                alignleft aligncenter alignright | \
                image | preview | fullscreen',
            }
        },
        methods: {
            getSettings: function() {
                let formData = this.$serializeData({'keys':['PACKING_SLIP_HTML', 'PACKING_SLIP_HTML_ORIGINAL','PACKING_SLIP_HTML_REPLACEMENT']});
                this.$http.post(adminBaseUrl + '/settings/get', formData).then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    this.adminsData = response.data.data;
                    setTimeout(() => {
                        this.initTinyMce(this.adminsData.PACKING_SLIP_HTML);
                    }, 20);
                });
            },
            updateSettings: function(input) {
                this.$validator.validateAll().then(res => {
                    if (res) {
                        this.clicked = 1;
                        let formData = this.$serializeData(this.adminsData);
                        this.$http.post(adminBaseUrl + '/settings/basedonkeys', formData)
                            .then((response) => { //success
                                this.clicked = 0;
                                if (response.data.status == false) {
                                toastr.error(response.data.message, '', toastr.options);
                                return;
                                }
                                toastr.success(response.data.message, '', toastr.options);
                            }, (response) => { //error
                                this.clicked = 0;
                                this.validateErrors(response);
                            });
                    } else {
                        this.clicked = 0;
                    }
                });
            },            
            resetTemplate: function() {
                this.adminsData.PACKING_SLIP_HTML = this.adminsData.PACKING_SLIP_HTML_ORIGINAL;
                this.initTinyMce(this.adminsData.PACKING_SLIP_HTML);
            },
            initTinyMce: function(descriptionInitValue) {
                let thisObj = this;
                tinymce.remove();
                tinymce.init({
                    selector:'#editor',
                    height: 500,
                    menubar: true,
                    branding: false,
                    plugins: this.myPlugins,
                    toolbar: this.toolBar,
                    images_upload_url: adminBaseUrl + '/editor/images',
                    images_upload_credentials: true,
                    content_css : this.activeThemeUrl + '/css/main-ltr.css',
                    setup: function(editor) {
                        editor.on('init', function(e) {               
                            editor.setContent(descriptionInitValue);
                        });
                        editor.on('change', function(e) {  
                            thisObj.adminsData.PACKING_SLIP_HTML = editor.getContent();
                        });
                    }
                });
            },
        },
        mounted: function() {
            let currentUrl = window.location.pathname;
            this.getSettings();  
            $(document).on("click", ".copyToClipboard", function() {
                let $temp = $("<input>");
                $("body").append($temp);
                let copyText = $(this).attr("data-val");
                $temp.val(copyText).select();
                document.execCommand("copy");
                $temp.remove();
                $(this).tooltip('hide').attr('data-original-title', 'Copied to clipboard')
                    .tooltip('show');
            });
            $(document).on("mouseover", ".copyToClipboard", function() {
                $(this).tooltip('show');
            });
            $(document).on("mouseleave", ".copyToClipboard", function() {
                $(this).tooltip('hide');
            });
            $(document).on('click', '.modal-backdrop', function() {
                data.show = false;
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            });
        }
    }
</script>