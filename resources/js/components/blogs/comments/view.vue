<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{ $t('LBL_COMMENT_DETAIL') }}</h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_CMS')}}</span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_BLOGS')}}</span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <router-link :to="{name: 'blogComment'}" class="subheader__breadcrumbs-link">{{ $t('LBL_COMMENTS')}}</router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_DETAIL')}}</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- begin:: Content -->
        <div class="container">
            <!--Begin::App-->
            <div class="row justify-content-center">
                <!--Begin:: App Content-->
                <div class="col-lg-10">
                    <div class="portlet">
                        <div class="portlet__body">
                            <table class="table">
                                <thead>                  
                                     <th width="12%">{{$t('LBL_AUTHOR_NAME')}}</th>
                                     <th>{{$t('LBL_AUTHOR_EMAIL')}}</th>
                                      <th width="12%">{{$t('LBL_POSTED_ON')}}</th>
                                      <th>{{$t('LBL_USER_IP')}}</th>
                                      <th>{{$t('LBL_USER_AGENT')}}</th>
                                      <th>{{$t('LBL_ARTICLE_TITLE')}}</th>
                                      <th>{{$t('LBL_PUBLISHED_ON')}}</th>
                                </thead>
                                <tbody>
                                    <tr>                                       
                                        <td>{{record.user_fname+ ' ' +record.user_lname}}</td>                                    
                                        <td>{{record.user_email}}</td>                                   
                                        <td>{{ record.bpcomment_added_on | formatDate}}</td>
                                        <td>{{ record.bpcomment_user_ip }}</td>                                
                                        <td>{{ record.bpcomment_user_agent }}</td>                                                                          
                                        <td>{{ record.post_title }}</td>
                                        <td>
                                            <label class="switch switch--sm" v-bind:class="[(!$canWrite('BLOGS')) ? 'disabledbutton no-drop': '']" v-b-tooltip.hover :title="record.bpcomment_approved == 1 ? $t('TTL_UNPUBLISH') : $t('TTL_PUBLISH')">
                                            <input type="checkbox" v-model="record.bpcomment_approved" @change="onSwitchStatus($event, record.bpcomment_id)">
                                            <span></span></label></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="portlet__body">
                            <div class="pb-4">
                                <div class="mb-4">
                                    <div class="">
                                        <a href="javascript:;" class="">{{record.user_fname+ ' ' +record.user_lname}}</a>
                                    </div>
                                    <div class="bg-gray p-4">
                                        {{ record.bpcomment_content }}
                                    </div>
                                </div>
                                <div class=" mb-4" v-for="comment in comments" :key="comment.bpcomment_id">
                                    <div class="">
                                        <a href="javascript:;" class="">{{ businessName }}</a>
                                    </div>
                                    <div class="bg-gray  p-4">
                                        {{ comment.bpcomment_content }}
                                    </div>
                                    <span class=" ">{{ comment.bpcomment_created_at | formatDateTime }} </span>
                                </div>
                            </div>

                            <div class="">
                                <div class="form-group">
                                    <label for="exampleTextarea">{{ $t('LBL_COMMENT') }}</label>
                                    <textarea class="form-control" v-model="bpcomment_content" name="bpcomment_content" rows="3" v-validate="'required'" :data-vv-as="$t('LBL_COMMENT')" data-vv-validate-on="none"></textarea>
                                    <span v-if="errors.first('bpcomment_content')" class="error text-danger">{{errors.first('bpcomment_content')}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="portlet__foot">
                            <div class="row">
                                <div class="col">
                                        <router-link :to="{name: 'blogComment'}" class="btn btn-secondary btn-wide">{{ $t('BTN_DISCARD')}}
                                    </router-link>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-wide btn-brand gb-btn gb-btn-primary" @click="validateRecord()" :disabled="!isComplete || clicked==1 || !$canWrite('BLOGS')" v-bind:class="clicked==1 && 'gb-is-loading'" >{{$t('BTN_BLOG_COMMENT_REPLY_SUBMIT')}}</button>
                                </div>
                            </div>    
                        </div>
                        
                    </div>
                </div>
                <!--End:: App Content-->
            </div>
            <!--End::App-->
        </div>
        <!-- end:: Content -->
    </div>
</template>
<script>
    const searchFields = {
        'post_title': ''
    };
    export default {
        data: function() {
            return {
                bpcomment_content: '',
                record: {'user_fname':'', 'user_lname': ''},
                comments: [],
                clicked: 0,
                businessName: '',
                createdByUser:'',
                updatedByUser:'',
                updatedAt:'',
                createdAt:''
            }
        },
        computed: {
            isComplete() {
                return this.bpcomment_content;
            }
        },
        methods: {
            pageLoadData: function() {
                this.bpcomment_content = '';
                let id = this.$route.params.id;
                this.$http.get(adminBaseUrl + '/blog/comments/' + id)
                    .then((response) => { //
                        this.record = response.data.data.postDetail;
                        this.comments = response.data.data.postComments;
                        this.businessName = response.data.data.businessName;
                        if (response.data.data.lastCommentedAdmin.created_at != null && "admin_username" in response.data.data.lastCommentedAdmin.created_at) {
                            this.createdByUser = response.data.data.lastCommentedAdmin.created_at.admin_username;
                        }
                        if (response.data.data.lastCommentedAdmin.updated_at != null && "admin_username" in response.data.data.lastCommentedAdmin.updated_at) {
                            this.updatedByUser = response.data.data.lastCommentedAdmin.updated_at.admin_username;
                        }
                        this.updatedAt = response.data.data.lastCommentedAdmin.bpcomment_updated_at ? response.data.data.lastCommentedAdmin.bpcomment_updated_at : '';
                        this.createdAt = response.data.data.lastCommentedAdmin.bpcomment_created_at ? response.data.data.lastCommentedAdmin.bpcomment_created_at : '';
                    });
            },
            validateRecord: function() {
                this.$validator.validateAll().then(res => {
                    if (res) {
                        this.clicked = 1;
                        this.saveRecord();
                    } else {
                        this.clicked = 0;
                    }
                });
            },
            saveRecord: function() {
                let id = this.$route.params.id;
                let formData = this.$serializeData({
                    'bpcomment_content': this.bpcomment_content
                });
                formData.append('comment_id', id);
                this.$http.post(adminBaseUrl + '/blog/comments', formData)
                    .then((response) => { //success
                        if (response.data.status == false) {
                            toastr.error(response.data.message, '', toastr.options);
                            return;
                        }
                        toastr.success(response.data.message, '', toastr.options);
                        this.clicked = 0;
                        this.$router.push({
                            name: 'blogComment'
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
            onSwitchStatus: function(event, id) {
                let formData = this.$serializeData({
                    'status': event.target.checked
                });
                this.$http.post(adminBaseUrl + '/blog/comments/status/' + id, formData).then((response) => {
                    toastr.success(response.data.message, '', toastr.options);
                });
            }
        },
        mounted() {
            this.pageLoadData();
        }
    }
</script>