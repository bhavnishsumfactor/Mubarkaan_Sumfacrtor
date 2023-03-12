<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{$t('LBL_PRODUCT_REVIEWS')}}</h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">
                            {{$t('LBL_PRODUCTS')}} </span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <router-link :to="{name: 'productReviewsListing'}" class="subheader__breadcrumbs-link">{{$t('LNK_PRODUCT_REVIEWS')}}</router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">
                            {{$t('LBL_REVIEW_DETAIL')}} </span>
                    </div>
                </div>
                <div class="subheader__toolbar"> 
                    <div class="subheader__wrapper"> 
                        <router-link :to="{name: 'productReviewsListing'}" class="btn btn-subheader"> {{$t('BTN_BACK')}}</router-link> 
                    </div>                  
                </div>                  
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="portlet product-reviews" id="page_portlet">
                        <div class="portlet__head">
                            <div class="portlet__head-label">
                                <h3 class="portlet__head-title" :title="adminsData.preview_title"> {{ adminsData.preview_title | truncate(80) }} </h3>
                            </div>
                            <div class="portlet__head-toolbar">
                            </div>
                        </div>                    
                        <div class="portlet__body">
                            <div class="section">
                                <div class="section__body">
                                    
                                    <div class="row mb-2">                                                        
                                        <div class="col-lg-1">
                                            <div class="profile-wrapper">
                                                <a
                                                    target="_blank"
                                                    :href="baseUrl+'/product/'+adminsData.preview_prod_id"
                                                >
                                                 
                                                    <div class="avatar">
                                                        <img v-if="adminsData.preview_prod_id" :src="$productImage(adminsData.preview_prod_id, (adminsData.op_pov_code != null ? adminsData.op_pov_code : '0'), adminsData.get_updated_image ? $timestamp(adminsData.get_updated_image.afile_updated_at) : '', '40-53')" v-b-tooltip.hover
                                                        :title="adminsData.prod_name"/>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <h6 class="text-muted mb-2">{{ $t('LBL_CUSTOMER') }}:</h6>
                                            <p class="">
                                                <router-link :to="{name: 'users', params: {id: adminsData.preview_user_id }}">{{adminsData.username}}</router-link>
                                            </p>
                                        </div>
                                        <div class="col-lg-2">
                                            <h6 class="text-muted mb-2">{{$t('LBL_ORDER')}}:</h6>
                                            <p class="">
                                                <router-link :to="{name: 'orderDetail', params: {id: adminsData.preview_order_id}}">#{{adminsData.preview_order_id }}</router-link>
                                            </p>
                                        </div>
                                        <div class="col-lg-2">
                                            <h6 class="text-muted mb-2">{{$t('LBL_ORDER_DATE')}}:</h6>
                                            <p class="">
                                                {{adminsData.order_date_added | formatDate }}
                                            </p>
                                        </div>
                                        <div class="col-lg-3">
                                            <h6 class="text-muted mb-2">{{$t('LBL_REVIEW_POSTED_ON')}}:</h6>
                                            <p class="">
                                                {{adminsData.preview_created_at | formatDate }}
                                            </p>
                                        </div>
                                        <div class="col-lg-2">
                                            <h6 class="text-muted mb-2">{{$t('LBL_RATING')}}:</h6>
                                            <div class="rating-holder">
                                                <ratingStars :rating="adminsData.preview_rating"></ratingStars>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-light">
                                        <div class="list-text">
                                            <span class="lable">{{ $t('LBL_DESCRIPTION')}}</span> 
                                            <p>{{adminsData.preview_description}}</p>
                                        </div>
                                        <p class="list-text" v-if="adminsData.reply != null">
                                            <span class="lable">{{ $t('LBL_REVIEW_ADMIN_REPLY')}}<br>
                                                <small>{{adminsData.reply.prc_created_at | formatDateTime}}</small>
                                            </span> <span v-html="$nl2br(adminsData.reply.prc_comments)"></span></p>
                                    </div>

                                    <hr>
                                    
                                    <div class="media-group my-5" v-if="adminsData.attached_files.length > 0">
                                        <a href="javascript:;"  @click="openGallery(index)" class="media media--circle" v-for="(image, index) in adminsData.attached_files.slice(0, 3)" v-bind:key="index">
                                            <img :src="'yokart/image/'+image.afile_id+'/small?t='+$timestamp(image.afile_updated_at)+''" alt="image">
                                        </a>
                                        <a href="javascript:;" class="media media--circle" v-if="adminsData.attached_files.length > 3"  @click="openGallery(3)" data-toggle="tooltip" data-skin="brand" data-placement="top" title="" data-original-title="">
                                            <span>{{adminsData.attached_files.length - 3}}+</span>
                                        </a>
                                        <LightBox ref="lightbox" :media="media" :showLightBox="false"></LightBox>
                                    </div>

                                    <hr v-if="adminsData.product_review_log != null">

                                    <div class="card-body bg-light" v-if="adminsData.product_review_log != null">
                                        <p class="list-text"><span class="lable">{{ $t('LBL_POSTED_ON')}}</span>   {{adminsData.product_review_log.prl_preview_created_at | formatDate }}</p>
                                        <div class="list-text"><span class="lable">{{ $t('LBL_RATING')}}</span>   
                                            <div class="rating-holder">
                                                <ratingStars :rating="adminsData.product_review_log.prl_preview_rating"></ratingStars>
                                            </div>
                                        </div>
                                        <p class="list-text"><span class="lable">{{ $t('LBL_PRODUCT_REVIEW_TITLE')}}</span>   {{adminsData.product_review_log.prl_preview_title}}</p>
                                        <p class="list-text"><span class="lable">{{ $t('LBL_DESCRIPTION')}}</span> {{adminsData.product_review_log.prl_preview_description}}</p>                                                    
                                    </div>
                                    <div class="media-group my-5" v-if="adminsData.product_review_log != null && adminsData.product_review_log.attached_files.length > 0">
                                        <a href="javascript:;"  @click="openEditedReviewGallery(index)" class="media media--circle" v-for="(image, index) in adminsData.product_review_log.attached_files.slice(0, 3)" v-bind:key="index">
                                            <img :src="'yokart/image/'+image.afile_id+'/small?t='+$timestamp(image.afile_updated_at)+''" alt="image">
                                        </a>
                                        <a href="javascript:;" class="media media--circle" v-if="adminsData.product_review_log.attached_files.length > 3"  @click="openEditedReviewGallery(3)" data-toggle="tooltip" data-skin="brand" data-placement="top" title="" data-original-title="">
                                            <span>{{adminsData.product_review_log.attached_files.length - 3}}+</span>
                                        </a>
                                        <LightBox ref="lightbox2" :media="media2" :showLightBox="false"></LightBox>
                                    </div>

                                    <div class="bg-light" v-if="adminsData.preview_approved_at">
                                        <div class="list-text">
                                            <span class="lable">{{ $t('LBL_PUBLISHED_DATE') }}</span> 
                                            <p>{{adminsData.preview_approved_at | formatDate }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="form form-reply">
                                        <div class="form-group" v-if="adminsData.reply == null">
                                            <label >{{ $t('LBL_REVIEW_REPLY')}}</label>   
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <textarea class="form-control" rows="5" cols="15" v-model="reply" name="reply" v-validate="'required'" :data-vv-as="$t('LBL_REVIEW_REPLY') " data-vv-validate-on="none">
                                                    </textarea>
                                                    <span v-if="errors.first('reply')" class="error text-danger">{{ errors.first('reply') }}</span>
                                                </div>
                                                <div class="col-md-2">
                                                    <button class="btn btn-wide btn-brand gb-btn gb-btn-primary" type="submit" value="Submit" :disabled='!isComplete || clicked==1' v-bind:class="clicked==1 && 'gb-is-loading'" @click="submitReply(adminsData.preview_id)">
                                                        <i class="fab fa-telegram-plane"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row" v-if="adminsData.product_review_log != null">
                                        <label class="col-lg-3 col-form-label">{{ $t('LBL_CHANGE_REVIEW_STATUS') }}</label>
                                        <div class="col-lg-9">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" checked="checked" @click="updateEditedReviewStatus(1, adminsData.product_review_log.prl_id)" :value="1" v-model="adminsData.product_review_log.prl_preview_status" name="preview_status"> {{$t('LBL_REVIEW_PENDING')}}<span></span>
                                                </label>
                                                <label class="radio">
                                                    <input type="radio" name="preview_status" @click="updateEditedReviewStatus(2, adminsData.product_review_log.prl_id)" :value="2" v-model="adminsData.product_review_log.prl_preview_status"> {{$t('LBL_REVIEW_APPROVED')}}<span></span>
                                                </label>
                                                <label class="radio">
                                                    <input type="radio" name="preview_status" @click="updateEditedReviewStatus(3, adminsData.product_review_log.prl_id)" :value="3" v-model="adminsData.product_review_log.prl_preview_status"> {{$t('LBL_REVIEW_REJECTED')}}<span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row" v-else-if="adminsData.product_review_log == null && adminsData.preview_status == 1">
                                        <label class="col-lg-3 col-form-label">{{ $t('LBL_CHANGE_REVIEW_STATUS') }}</label>
                                        <div class="col-lg-9">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" checked="checked" @click="updateReviewStatus(1, adminsData.preview_id)" :value="1" v-model="adminsData.preview_status" name="preview_status"> {{$t('LBL_REVIEW_PENDING')}}<span></span>
                                                </label>
                                                <label class="radio">
                                                    <input type="radio" name="preview_status" @click="updateReviewStatus(2, adminsData.preview_id)" :value="2" v-model="adminsData.preview_status"> {{$t('LBL_REVIEW_APPROVED')}}<span></span>
                                                </label>
                                                <label class="radio">
                                                    <input type="radio" name="preview_status" @click="updateReviewStatus(3, adminsData.preview_id)" :value="3" v-model="adminsData.preview_status"> {{$t('LBL_REVIEW_REJECTED')}}<span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

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
    import VueLazyLoad from 'vue-lazyload';
    Vue.use(VueLazyLoad);
    import LightBox from 'vue-image-lightbox';
    import 'vue-image-lightbox/dist/vue-image-lightbox.min.css';
    export default {
        components: {
            LightBox,
        },
        data: function() {
            return {
                baseUrl: baseUrl,
                adminsData: {
                    prod_name: '',
                    reply: '',
                    attached_files: []
                },
                media: [],
                media2: [],
                reply: '',
                clicked: 0
            }
        },
        computed: {
            isComplete() {
                return this.reply;
            }
        },
        methods: {
            pageLoadData: function() {
                this.$http.get(adminBaseUrl + '/product-reviews/detail/' + this.$route.params.id)
                    .then((response) => {
                        this.adminsData = response.data.data;
                        for (let [key, image] of Object.entries(this.adminsData.attached_files)) {
                            this.media.push({
                                src: 'yokart/image/' + image.afile_id + '/original?t='+$timestamp(image.afile_updated_at),
                                thumb: 'yokart/image/' + image.afile_id + '/small?t='+$timestamp(image.afile_updated_at)
                            });
                        }
                        if (this.adminsData.product_review_log != null) {
                            for (let [key, image] of Object.entries(this.adminsData.product_review_log.attached_files)) {
                                this.media2.push({
                                    src: 'yokart/image/' + image.afile_id + '/original?t='+$timestamp(image.afile_updated_at),
                                    thumb: 'yokart/image/' + image.afile_id + '/small?t='+$timestamp(image.afile_updated_at)
                                });
                            }
                        }
                    });
            },
            openGallery(t) {
                this.$refs.lightbox.showImage(t)
            },
            openEditedReviewGallery(t) {
                this.$refs.lightbox2.showImage(t)
            },
            submitReply: function(previewId) {
                this.$validator.validateAll().then(res => {
                    if (res) {
                        this.clicked = 1;
                        let formData = this.$serializeData({
                            'id': previewId,
                            'reply': this.reply
                        });
                        this.$http.post(adminBaseUrl + '/product-reviews/reply', formData)
                            .then((response) => {
                                if (response.data.status == false) {
                                    toastr.error(response.data.message, '', toastr.options);
                                    return;
                                }
                                this.clicked = 0;
                                this.pageLoadData();
                            }, (response) => {
                                this.clicked = 0;
                                this.validateErrors(response);
                            });
                    } else {
                        this.clicked = 0;
                    }
                });
            },
            updateReviewStatus: function(status, reviewId) {
                let formData = this.$serializeData({
                    'id': reviewId,
                    'status': status
                });
                this.$http.post(adminBaseUrl + '/product-reviews/moderate', formData)
                    .then((response) => { //success
                        if (response.data.status == false) {
                            toastr.error(response.data.message, '', toastr.options);
                            return;
                        }
                        toastr.success(response.data.message, '', toastr.options);
                    });
            },
            updateEditedReviewStatus: function(status, reviewId) {
                let formData = this.$serializeData({
                    'id': reviewId,
                    'status': status
                });
                this.$http.post(adminBaseUrl + '/product-reviews/moderate-edited', formData)
                    .then((response) => { //success
                        if (response.data.status == false) {
                            toastr.error(response.data.message, '', toastr.options);
                            return;
                        }
                        this.pageLoadData();
                        toastr.success(response.data.message, '', toastr.options);
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
            }
        },
        mounted: function() {
            this.pageLoadData();
        },
    }
</script>