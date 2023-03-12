<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{ $t('LBL_BLOG_ARTICLES') }}</h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_CMS')}}</span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_BLOGS')}}</span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <router-link :to="{name: 'blogs'}" class="subheader__breadcrumbs-link">{{ $t('LBL_ARTICLES')}}</router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_EDIT')}}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <!--begin::Portlet-->
                    <div class="portlet" id="page_portlet">
                        <form class="form form--label-right">
                            <div class="portlet__head">
                                <div class="portlet__head-label">
                                    <h3 class="portlet__head-title">
                                         {{ $canWrite('BLOGS') ? $t('LBL_EDITING') + ' -' : '' }} {{ adminsData.post_title }}
                                    </h3>
                                </div>
                                <div class="portlet__head-toolbar">
                                    <div class="portlet__head-actions" id="tooltip-target-1"><i class="fas fa-info-circle"></i></div>
                                    <b-popover target="tooltip-target-1" triggers="hover" placement="top" class="popover-cover">
                                        <div class="list-stats">
                                            <div class="lable">
                                                <span class="stats_title">{{ $t('LBL_CREATED') }}:</span> 
                                                <span class="time">{{ createdByUser ? createdByUser+ ' |' : '' }} {{ createdAt | formatDateTime}}</span>
                                            </div>
                                            <div class="lable">
                                                <span class="stats_title">{{ $t('LBL_LAST_UPDATED') }}:</span>
                                                <span class="time">{{ updatedByUser ? updatedByUser+ ' |' : ''}} {{ updatedAt | formatDateTime}}</span>
                                            </div>
                                        </div>
                                    </b-popover>
                                </div>
                            </div>
                            <div class="portlet__body" v-bind:class="[(!$canWrite('BLOGS')) ? 'disabledbutton': '']">
                                <input type="hidden" id="id" value="">
                                <div class="section">
                                    <div class="section__body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{{ $t('LBL_BLOG_TITLE') }} <span class="required">*</span></label>
                                                    <input type="text" v-model="adminsData.post_title" name="post_title" v-validate="'required'" :data-vv-as="$t('LBL_BLOG_TITLE')" data-vv-validate-on="none" class="form-control" />
                                                    <span v-if="errors.first('post_title')" class="error text-danger">{{errors.first('post_title')}}</span>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{{ $t('LBL_BLOG_CATEGORY') }} <span class="required">*</span></label>
                                                    <treeselect name="ptc_bpcat_id" v-model="adminsData.ptc_bpcat_id" :multiple="true" :value-consists-of="'LEAF_PRIORITY'" :defaultExpandLevel="Infinity" :clearable="false" :isDefaultExpanded="true" :options="categories" :searchable="true" :open-on-click="true" :close-on-select="true" :placeholder="$t('PLH_SELECT_SOME')" v-validate="'required'" :data-vv-as="$t('LBL_BLOG_CATEGORY')" data-vv-validate-on="none" />
                                                    <span v-if="errors.first('ptc_bpcat_id')" class="error text-danger">{{errors.first('ptc_bpcat_id')}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{{ $t('LBL_ARTICLE_AUTHOR') }} <span class="required">*</span></label>
                                                    <input type="text" v-model="adminsData.post_author_name" name="post_author_name" v-validate="'required'" :data-vv-as="$t('LBL_ARTICLE_AUTHOR')" data-vv-validate-on="none" class="form-control" />
                                                    <span v-if="errors.first('post_author_name')" class="error text-danger">{{errors.first('post_author_name')}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{{ $t('LBL_BLOG_SHORT_DESCRIPTION') }} <span class="required">*</span></label>
                                                    <textarea class="form-control" type="text" v-model="adminsData.bpc_short_description" name="bpc_short_description" v-validate="'required'" :data-vv-as="$t('LBL_BLOG_SHORT_DESCRIPTION')" data-vv-validate-on="none">
                                                    </textarea>
                                                    <span v-if="errors.first('bpc_short_description')" class="error text-danger">{{errors.first('bpc_short_description')}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{{ $t('LBL_BLOG_DESCRIPTION') }} <span class="required">*</span></label>
                                                    <textarea class="form-control" id="editor" v-model="adminsData.bpc_description" name="bpc_description" v-validate="'required'" :data-vv-as="$t('LBL_BLOG_DESCRIPTION')" data-vv-validate-on="none"></textarea>
                                                    <span v-if="errors.first('bpc_description')" class="error text-danger">{{errors.first('bpc_description')}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{{ $t('LBL_IMAGE') }}</label>
                                                    <div
                                                        data-ratio="16:9"
                                                        class="dropzone dropzone-default dropzone-brand dz-clickable ratio-16by9 "
                                                        @click="(croppedImageView == '') ? [$refs.cropperRef.openCropper(), fileUploadClass = true]  : ''"
                                                        @mouseover="fileUploadClass = true"
                                                        @mouseleave="fileUploadClass = false"
                                                    >
                                                        <div class="upload_cover">
                                                            <div class="img--container uploded__img" v-if="croppedImageView != ''"><img :src="croppedImageView" />
                                                                <div class="upload__action">
                                                                    <button type="button" @click="removeImage($event, attachedFile)" v-if="croppedImageView != ''">
                                                                        <svg>   
                                                                            <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'"></use>                               
                                                                        </svg>
                                                                    </button>
                                                                    <button type="button" @click="$refs.cropperRef.openCropper()" v-if="croppedImageView != ''">
                                                                        <svg>   
                                                                            <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'"></use>                               
                                                                        </svg>    
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div clas="img--container  ">
                                                                <div class="file-upload" v-bind:class="{ isactive: fileUploadClass, fileVisiblity: fileVisiblity}">
                                                                    <img :src="baseUrl+'/admin/images/upload/upload_img.png'" />
                                                                </div>
                                                                
                                                            </div>
                                                            <div class="dropzone-msg dz-message needsclick" v-if="croppedImageView == ''">
                                                                <h3 class="dropzone-msg-title">{{ $t('LBL_CLICK_HERE_TO_UPLOAD')}}</h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <cropper ref="cropperRef" :title="adminsData.post_title" :icon="false" :aspectRatio="1.33" :width="1000" :height="750" v-on:childToParent="setImage" v-on:actualImageToParent="setActualImage"></cropper>
                                                    <img :src="originalImage" id="originalImage" style="display: none;" />
                                                    <p class="img-disclaimer py-2"><strong>{{ $t('LBL_IMAGE_DISCLAIMER') }}: </strong> {{ $t('LBL_IMAGE_RESTRICTIONS') + ' 1000x750 ' + $t('LBL_IN') + ' 4:3 ' + $t('LBL_ASPECT_RATIO') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>{{ $t('LBL_COMMENT') }}</label>
                                                    <div class="field-set ">
                                                        <label class="switch switch--sm">
                                                            <input type="checkbox" name="post_comment_opened" id="post_comment_opened" v-model="adminsData.post_comment_opened">
                                                            <span></span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>{{ $t('LBL_PUBLISH') }}</label>
                                                    <div class="field-set ">
                                                        <label class="switch switch--sm">
                                                            <input type="checkbox" name="post_publish" id="post_publish" v-model="adminsData.post_publish">
                                                            <span></span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ $t('LBL_PUBLISH_FROM') }}</label>
                                                    <date-pick v-model="adminsData.post_published_on" :parseDate="parseDate" :formatDate="formatDate"
                                                        :format="$dateTimeFormat(false)" :isDateDisabled="isPastDate" :inputAttributes="{class: 'form-control', readonly: true}" class="d-block">
                                                    </date-pick>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" v-if="products.length != 0">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{{ $t('LBL_PRODUCTS') }}</label>
                                                    <treeselect name="ptbp_prod_id" v-on:deselect="clearValue" v-model="adminsData.ptbp_prod_id" :multiple="true" :value-consists-of="'LEAF_PRIORITY'" :defaultExpandLevel="Infinity" :clearable="false" :clearOnSelect ="true" :isDefaultExpanded="true" :options="products" :searchable="true" :open-on-click="true" :close-on-select="true" :placeholder="$t('PLH_SELECT_SOME')" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="portlet__foot">
                                <div class="form__actions">
                                    <div class="row" >
                                        <div class="col">
                                            <router-link :to="{name: 'blogs'}" class="btn btn-secondary btn-wide">{{ $t('BTN_DISCARD')}}
                                            </router-link>
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-wide btn-brand gb-btn gb-btn-primary" @click="validateRecord()" :disabled='!isComplete || clicked==1 || !$canWrite("BLOGS")' v-bind:class="clicked==1 && 'gb-is-loading'">{{ $t('BTN_BLOG_UPDATE')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <!--end::Portlet-->
            <DeleteModel
                :deletePopText="deleteStatus.message"
                :subText="deleteStatus.subMessage"
                :recordId="deleteStatus.id"
                @deleted="deleteImage(deleteStatus.id)"
            ></DeleteModel>
        </div>
    </div>
</template>
<script>
    import DatePick from 'vue-date-pick';
    import 'vue-date-pick/dist/vueDatePick.css';
    import fecha from 'fecha';
    
    import Treeselect from '@riophae/vue-treeselect';
    import '@riophae/vue-treeselect/dist/vue-treeselect.css';

    const tableFields = {
        'post_id': '',
        'post_title': '',
        'post_publish': '',
        'post_author_name': '',
        'post_comment_opened': '',
        'post_published_on': '',
        'bpc_short_description': '',
        'bpc_description': '',
        'ptc_bpcat_id': [],
        'ptbp_prod_id': [],
    };
    export default {
        components: {
            DatePick,
            Treeselect
        },
        data: function() {
            return {
                activeThemeUrl: activeThemeUrl,
                adminBaseUrl: adminBaseUrl,
                baseUrl: baseUrl,
                records: [],
                croppedImage: '',
                croppedImageView: '',
                originalImage: '',
                uploadedFile: '',
                clicked: 0,
                adminsData: tableFields,
                categories: [],
                products: [],
                myPlugins: [
                    'advlist autolink lists link image charmap',
                    'searchreplace visualblocks code fullscreen',
                    'print preview anchor insertdatetime media',
                    'paste code help wordcount table'
                ],
                toolBar: 'undo redo | formatselect | bold italic | \
                alignleft aligncenter alignright | \
                bullist numlist outdent indent | image | preview | fullscreen',
                createdByUser: '',
                updatedByUser: '',
                updatedAt: '',
                createdAt: '',
                fileUploadClass: false,
                fileVisiblity: false,
                deleteStatus: {},
                modelId: "deleteModel",
                attachedFile : ''
            }
        },
        computed: {
            isComplete () {
                return this.adminsData.post_title && this.adminsData.post_author_name && this.adminsData.bpc_short_description
                && this.adminsData.bpc_description && this.adminsData.ptc_bpcat_id.length>0;
            }
        },
        methods: {
            parseDate(dateString, format) {
                return fecha.parse(dateString, format);
            },
            formatDate(dateObj, format) {
                return fecha.format(dateObj, format);
            },
            isPastDate: function(date) {
                const currentDate = new Date().setDate(new Date().getDate() - 1);
                return date < currentDate;
            },
            setImage: function(cropUrl) {
                this.croppedImage = cropUrl;
                this.croppedImageView = URL.createObjectURL(cropUrl);
            },
            setActualImage: function(actualImage) {
                this.fileVisiblity = true;
                this.fileUploadClass = false;
                if (typeof actualImage != 'string') {
                    this.originalImage = URL.createObjectURL(actualImage);
                    this.uploadedFile = actualImage;
                } else {
                    this.uploadedFile = this.originalImage = actualImage;
                }
            },
            validateRecord: function() {
                this.$validator.validateAll().then(res => {
                    if (res) {
                        this.clicked = 1;
                        let input = this.adminsData;
                        this.updateRecord(input);
                    } else {
                        this.clicked = 0;
                    }
                });
            },
            updateRecord: function(input) {                
                let data = input;
                let formData = this.$serializeData(data);
                formData.set('ptc_bpcat_id', JSON.stringify(input.ptc_bpcat_id));
                formData.set('ptbp_prod_id', JSON.stringify(input.ptbp_prod_id));
                formData.append('actualImage', this.uploadedFile);
                formData.append('cropImage', this.croppedImage);
                formData.append('_method', 'PUT');
                this.$http.post(adminBaseUrl + '/blogs/' + data.post_id, formData)
                    .then((response) => { //success
                        this.clicked = 0;
                        if (response.data.status == false) {
                            toastr.error(response.data.message, '', toastr.options);
                            return;
                        }
                        toastr.success(response.data.message, '', toastr.options);
                        this.$router.push({
                            name: 'blogs'
                        });
                    }, (response) => { //error
                        this.clicked = 0;
                        this.validateErrors(response);
                    });
            },
            validateErrors: function(response) {
                let jsondata = response.data;
                Object.keys(jsondata.errors).forEach(key => {
                    this.errors.add({
                        field: key,
                        msg: jsondata.errors[key][0]
                    });
                });
            },
            pageLoadData: function() {
                let id = this.$route.params.id;
                this.$http.get(adminBaseUrl + '/blogs/' + id + '/edit')
                    .then((response) => { 
                        this.categories = response.data.data.categories;
                        Object.keys(response.data.data.products).forEach(key => {
                            this.products.push({
                                id: key,
                                label: response.data.data.products[key]
                            });
                        });
                        this.assignValues(response.data.data.blog);
                    });
            },
            assignValues: function(response) {
                this.adminsData.post_id = response.post_id;
                this.adminsData.post_title = response.post_title;
                this.adminsData.post_publish = response.post_publish;
                this.adminsData.post_author_name = response.post_author_name;
                this.adminsData.post_comment_opened = response.post_comment_opened;
                this.adminsData.post_published_on = moment(response.post_published_on).format(this.$dateTimeFormat(false));
                this.adminsData.bpc_short_description = response.content.bpc_short_description;
                this.adminsData.bpc_description = response.content.bpc_description;
                this.initTinyMce(this.adminsData.bpc_description);     
                this.croppedImage = this.croppedImageView = this.originalImage = this.uploadedFile = '';
                if (response.afile_id != null) {
                    this.croppedImage = this.croppedImageView = this.$getFileUrl('blogImage', response.post_id, 0, 'thumb', this.$timestamp(response.post_updated_at));
                    this.originalImage = this.$getFileUrl('blogImage', response.post_id, 0, 'original', this.$timestamp(response.post_updated_at));
                    this.fileVisiblity = true;
                    this.fileUploadClass = false;
                    this.attachedFile = response.afile_id
                }       
                let catId = '';
                Object.keys(response.category).forEach(key => {
                    catId = response.category[key].ptc_bpcat_id;
                    this.adminsData.ptc_bpcat_id.push(catId);
                });
                if (this.products.length != 0) {
                    let productId = '';
                    Object.keys(response.product).forEach(key => {
                        productId = response.product[key].ptbp_prod_id;
                        this.adminsData.ptbp_prod_id.push(productId);
                    });
                }
                if (response.created_at != null && "admin_username" in response.created_at) {
                    this.createdByUser = response.created_at.admin_username;
                }
                if (response.updated_at != null && "admin_username" in response.updated_at) {
                    this.updatedByUser = response.updated_at.admin_username;
                }
                this.updatedAt = response.post_updated_at ? response.post_updated_at : '';
                this.createdAt = response.post_created_at ? response.post_created_at : '';   
                      
            },
            emptyFormValues: function() {
                this.adminsData = {
                    'post_id': '',
                    'post_title': '',
                    'post_publish': '',
                    'post_author_name': '',
                    'post_comment_opened': '',
                    'post_published_on': '',
                    'bpc_short_description': '',
                    'bpc_description': '',
                    'ptc_bpcat_id': [],
                    'ptbp_prod_id': [],
                };
                this.uploadedFile = this.croppedImage = this.croppedImageView = this.originalImage = '';
                this.errors.clear();
            },
            initTinyMce: function(descriptionInitValue) {
                let thisObj = this;
                tinymce.remove();
                tinymce.init({
                    selector:'#editor',
                    height: 500,
                    branding: false,
                    menubar: true,
                    plugins: this.myPlugins,
                    toolbar: this.toolBar,
                    images_upload_url: this.adminBaseUrl + '/editor/images',
                    images_upload_credentials: true,
                    content_css : this.activeThemeUrl + '/css/main-ltr.css',
                    setup: function(editor) {
                        editor.on('init', function(e) {                        
                            editor.setContent(descriptionInitValue);
                        });
                        editor.on('change', function(e) {
                            thisObj.adminsData.bpc_description = editor.getContent();
                        });
                    }
                });
            },
            emptyUpdatedFieldValue : function() {
                this.createdByUser = '';
                this.updatedByUser = '';
                this.updatedAt = '';
                this.createdAt = '';
            },
            emptyImageData: function() {
                this.croppedImage = '';
                this.croppedImageView = '';
                this.originalImage = '';
                this.uploadedFile = '';
                this.fileUploadClass = false;
                this.fileVisiblity = false;
            },
            removeImage : function(event, afileId) {
                event.stopPropagation();
                this.deleteStatus.id = afileId;
                this.deleteStatus.message = this.$t('LBL_DELETE_IMAGE_TEXT');
                this.deleteStatus.subMessage = '';
                if (afileId != '') {
                    this.deleteStatus.type = 1;
                    this.recordId = afileId;
                } else {
                    this.deleteStatus.type = 0;
                }
                this.$bvModal.show(this.modelId);
            },
            deleteImage: function (recordId) {
                if (this.attachedFile != "") {
                    this.$http.delete(adminBaseUrl + "/remove-attached-files/" + recordId).then((response) => {
                        if (response.data.status == false) {
                            toastr.error(response.data.message, "", toastr.options);
                            return;
                        }
                        this.emptyImageData();
                        this.deleteStatus.type = 0;
                        this.attachedFile = '';
                        toastr.success(response.data.message, "", toastr.options);
                        this.pageRecords(this.pagination.current_page);
                    });
                } else {
                    this.emptyImageData();
                }
                this.$bvModal.hide(this.modelId);
            },
            clearValue: function(node) {
                this.adminsData.ptbp_prod_id.splice(this.adminsData.ptbp_prod_id.indexOf(parseInt(node.id)), 1);
                this.adminsData.ptbp_prod_id = [...this.adminsData.ptbp_prod_id];
            }
        },
        mounted: function() {
            this.emptyFormValues();
            this.pageLoadData();            
        },
    }
</script>