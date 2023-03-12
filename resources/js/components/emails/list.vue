<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{$t('Sent Emails')}}</h3>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet">
                        <div class="portlet__body pb-0 bg-gray" >
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="input-icon input-icon--left">
                                            <input type="text" class="form-control" placeholder="Search email and subject" id="generalSearch" v-model="searchData.search" @keyup="searchRecords">
                                            <span class="input-icon__icon input-icon__icon--left">
                                                <span><i class="la la-search"></i></span>
                                            </span>
                                            <span class="input-icon__icon input-icon__icon--right" v-if="searchData.search!=''" @click="searchData.search='';pageRecords(1);">
                                                <span><i class="fas fa-times"></i></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="m-0">
                        <div class="portlet__body portlet__body--fit" v-if="pageLoaded==1">
                            <div class="section">
                                <div class="section__content">
                                    <table class="table table-data table-justified">
                                        <thead>
                                            <tr>
                                                <th>{{ '#' }}</th>
                                                <th>{{ $t('Subject') }}</th>
                                                <th>{{ $t('Sent To') }}</th>
                                                <th >{{ $t('Sent On') }}</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            v-if="loading==false && records.length == 0 && searchData.search != '' && pageLoaded==1"
                                            >
                                            <tr>
                                                <td colspan="5">
                                                    <noRecordsFound></noRecordsFound>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr v-for="(record, index) in records" :key="record.emailarchive_id" :data-id="record.emailarchive_id">
                                                <td scope="row">{{ pagination.from + index }}</td>
                                                <td>{{ record.emailarchive_subject }}</td>
                                                <td>{{ record.emailarchive_to_email }}</td>
                                                <td>{{ record.emailarchive_created_at | formatDateTime }}</td>
                                                <td>
                                                    <div class="actions">
                                                     <router-link :to="{name: 'emailView', params: {id: record.emailarchive_id }}" v-b-tooltip.hover title="view email"   class="btn btn-light btn-sm btn-icon"> <svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#view'" ></use>                               
                                                            </svg> </router-link>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>    
                        <div v-if="!(showEmpty == 1 && pageLoaded==1)">
                            <loader v-if="loading"></loader>
                        </div>                    
                        <div class="portlet__foot" v-if="records.length > 0">
                            <pagination :pagination="pagination" @currentPage="currentPage"> </pagination>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
    const searchFields = {
        'search': ''
    };
    export default {
        data: function() {
            return {
                baseUrl: baseUrl,                
                records: [],
                pagination: [],
                search: '',
                searchData: searchFields,
                pageLoaded: 0,      
                loading: false, 
                showEmpty: 1       
            }
        },
        methods: {
            pageRecords: function(pageNo, pageLoad = false) {
                this.loading = pageLoad;
                let formData = this.$serializeData(this.searchData);
                if (typeof this.pagination.per_page != 'undefined') {
                    formData.append('per_page', this.pagination.per_page);
                }
                this.$http.post(adminBaseUrl + '/email-archieves/list'+ '?page=' + pageNo, formData).then((response) => {
                    this.records = response.data.data.data;
                    this.pagination = response.data.data;
                    this.loading = false;
                    this.pageLoaded = 1;
                });
            },
            searchRecords: function() {
                this.pageRecords(this.pagination.current_page);
            },
            onSwitchStatus: function(event, id) {
                let formData = this.$serializeData({
                    'status': event.target.checked
                });
                this.$http.post(adminBaseUrl + '/product-reviews/status/' + id, formData).then((response) => {
                    toastr.success(response.data.message, '', toastr.options);
                });
            },
            currentPage: function(page) {
                this.pageRecords(page);
            }
        },
        mounted: function() {
            this.pageRecords(this.pagination.current_page, true);
        }
    }
</script>