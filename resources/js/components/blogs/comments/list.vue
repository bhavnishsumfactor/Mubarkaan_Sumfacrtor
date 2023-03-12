<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{ $t('LBL_BLOG_COMMENTS') }}</h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_CMS')}}</span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_BLOGS')}}</span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_COMMENTS')}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="" id="manage-admins">
                <div class="row">
                    <div class="col-xl-12">
                        <!--begin::Portlet-->
                        <div class="portlet portlet--height-fluid">
                            <div class="portlet__body pb-0 bg-gray flex-grow-0">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="input-icon input-icon--left">
                                                <input type="text" class="form-control" :placeholder="$t('PLH_SEARCH') + '...'" :readonly="records.length == 0 && search === ''" id="generalSearch" @keyup="searchRecords" v-model="search">
                                                <span class="input-icon__icon input-icon__icon--left">
                                                    <span><i class="la la-search"></i></span>
                                                </span>
                                                <span class="input-icon__icon input-icon__icon--right" v-if="search!=''" @click="search='';pageRecords(1);">
                                                    <span><i class="fas fa-times"></i></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="m-0">
                            <div class="portlet__body portlet__body--fit">
                                <!--begin::Section-->
                                <div class="section"> 
                                    <div class="section__content">
                                        <table class="table table-data table-justified">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{ $t('LBL_AUTHOR_NAME') }}</th>
                                                    <th>{{ $t('LBL_ARTICLE_TITLE') }}</th>
                                                    <th>{{ $t('LBL_PUBLISH') }}</th>
                                                    <th>{{ $t('LBL_PUBLISHED_ON') }}</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody v-if="loading==false && records.length == 0 && search == '' && showEmpty == 1 && pageLoaded==1">
                                                <tr>
                                                    <td colspan="6">  
                                                        <div class="no-data-found">
                                                            <div class="img">
                                                                <img :src="activeThemeUrl+'/media/retina/empty/no-messages.svg'" width="125px" height="125px" />
                                                            </div>
                                                            <div class="data">
                                                                <h3>{{ $t('LBL_NO_BLOG_COMMENTS_TO_SHOW') }}</h3>
                                                                <router-link :to="{name: 'createBlog'}" class="btn btn-brand btn-wide mt-4" v-bind:class="[(!$canWrite('BLOGS')) ? 'disabledbutton no-drop': '']">{{ $t('BTN_ADD_BLOG') }}</router-link>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>  
                                            <tbody v-else-if="loading==false && records.length == 0 && search != '' && showEmpty == 0 && pageLoaded==1">
                                                <tr>
                                                    <td colspan="6">  
                                                        <noRecordsFound></noRecordsFound> 
                                                    </td>
                                                </tr>
                                            </tbody>  
                                            <tbody v-else>
                                                <tr v-for="(record, index) in records" :key="index">
                                                    <td scope="row">{{ pagination.from + index }}</td>
                                                    <td>{{ record.user_name }}</td>
                                                    <td>{{ record.post_title }}</td>
                                                    <td><label class="switch switch--sm" v-bind:class="[(!$canWrite('BLOGS')) ? 'disabledbutton no-drop': '']" v-b-tooltip.hover :title="record.bpcomment_approved == 1 ? $t('TTL_UNPUBLISH') : $t('TTL_PUBLISH')">
                                                            <input type="checkbox" v-model="record.bpcomment_approved" @change="onSwitchStatus($event, record.bpcomment_id)">
                                                            <span></span></label></td>
                                                    <td>{{ record.bpcomment_added_on | formatDate}}</td>
                                                    <td>
                                                        <div class="actions">
                                                            <router-link :to="{name: 'viewComment', params: {id: record.bpcomment_id}}" v-b-tooltip.hover :title="$t('TTL_VIEW')" class="btn btn-light btn-sm btn-icon" v-bind:class="[(!$canWrite('BLOGS')) ? 'disabledbutton no-drop': '']"><svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#view'" ></use>                               
                                                            </svg></router-link>
                                                            <a v-b-tooltip.hover :title="$t('TTL_DELETE')" class="btn btn-light btn-sm btn-icon" href="javascript:;" @click.capture="confirmDelete($event, record.bpcomment_id)" v-bind:class="[(!$canWrite('BLOGS')) ? 'disabledbutton no-drop': '']">
                                                                <svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'" ></use>                               
                                                            </svg>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <loader v-if="loading"></loader>                             
                            <div class="portlet__foot" v-if="records.length > 0">
                                <pagination :pagination="pagination" @currentPage="currentPage"> </pagination>
                            </div>
                            <!--end::Form-->
                        </div>
                        <!--end::Portlet-->
                    </div>
                </div>
                <DeleteModel :deletePopText="$t('LBL_BLOG_COMMENT_DELETE_TEXT')" :recordId="recordId" @deleted="deleteRecord(recordId)"></DeleteModel>

            </div>
        </div>
    </div>
</template>
<script>
    

    const searchFields = {
        'post_title': ''
    };
    export default {
        data: function() {
            return {
                baseUrl:baseUrl,
                records: [],
                recordId: '',
                search: '',
                pagination: [],
                loading: false,
                modelId: 'deleteModel',
                searchData: searchFields,
                activeThemeUrl: activeThemeUrl,
                pageLoaded: 0,       
                showEmpty: 1 
            }
        },

        methods: {

            searchRecords: function() {
                this.pageRecords(this.pagination.current_page);
            },
            currentPage: function(page) {
                this.pageRecords(page);
            },
            pageRecords: function(pageNo, pageLoad = false) {
                this.loading = pageLoad; 
                let formData = this.$serializeData({
                    'search': this.search
                });
                if (typeof this.pagination.per_page != 'undefined') {
                    formData.append('per_page', this.pagination.per_page);
                }

                this.$http.post(adminBaseUrl + '/blog/comments/list?page=' + pageNo, formData).then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    this.records = response.data.data.comments.data;
                    this.loading = false;
                    this.pagination = response.data.data.comments;
                    this.showEmpty = response.data.data.showEmpty;
                    this.pageLoaded = 1;
                });
            },
            confirmDelete: function(event, dataid) {
                event.stopPropagation();
                this.recordId = dataid;
                this.$bvModal.show(this.modelId);
            },
            deleteRecord: function(recordId) {
                this.$http.delete(adminBaseUrl + '/blog/comments/' + recordId).then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    toastr.success(response.data.message, '', toastr.options);
                    this.pageRecords(this.pagination.current_page);
                    this.$bvModal.hide(this.modelId);
                });
            },

            onSwitchStatus: function(event, id) {
                let formData = this.$serializeData({
                    'status': event.target.checked
                });
                this.$http.post(adminBaseUrl + '/blog/comments/status/' + id, formData).then((response) => {
                    toastr.success(response.data.message, '', toastr.options);
                });
            },

        },
        mounted() {
            this.pageRecords(this.pagination.current_page, true);
        }
    }
</script>