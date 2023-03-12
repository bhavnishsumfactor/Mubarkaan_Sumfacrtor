<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{ $t('LBL_GDPR_REQUESTS') }}</h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_USERS')}}</span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_GDPR_REQUESTS')}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="portlet portlet--height-fluid">
                        <div class="portlet__body pb-0 bg-gray flex-grow-0">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="input-icon input-icon--left">
                                            <input type="text" class="form-control" :placeholder="$t('PLH_SEARCH') + '...'" id="generalSearch" :readonly="records.length == 0 && search === ''" @keyup="searchRecords" v-model="search">
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
                            <div class="no-data-found" v-if="loading==false && records.length == 0 && search == '' && showEmpty == 1 && pageLoaded==1">
                                <div class="img">
                                    <img :src="activeThemeUrl+'/media/retina/empty/no-address.svg'" width="125px" height="125px" />
                                </div>
                                <div class="data">
                                    <h3>{{$t("LBL_NO_REQUESTS_TO_SHOW")}}</h3>
                                </div>
                            </div>
                            <div class="section" v-else>
                                <div class="section__content">
                                    <table class="table table-data">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th width="10%">{{ $t('LBL_USERNAME') }}</th>
                                                <th width="15%">{{ $t('LBL_GDPR_TYPE') }}</th>
                                                <th>{{ $t('LBL_GDPR_PURPOSE') }}</th>
                                                <th width="15%">{{ $t('LBL_GDPR_DATE') }}</th>
                                            </tr>
                                        </thead>  
                                        <tbody v-if="loading==false && records.length == 0 && search != '' && showEmpty == 0 && pageLoaded==1">
                                            <tr>
                                                <td colspan="5">  
                                                    <noRecordsFound></noRecordsFound> 
                                                </td>
                                            </tr>
                                        </tbody>  
                                        <tbody v-else>
                                            <tr v-for="(record, index) in records" :key="index">
                                                <td scope="row">{{ pagination.from + index }}</td>
                                                <td>{{ record.user_name }}</td>
                                                <td v-if="record.ureq_type == 1">{{ $t("LBL_DATA_REQUEST")}}</td>
                                                <td v-if="record.ureq_type == 2">{{ $t("LBL_DELETION_REQUEST")}}</td>
                                                <td>{{ record.ureq_purpose }}</td>
                                                <td>
                                                    <span class="date text-nowrap">
                                                    <span class="form-text text-muted">{{ record.ureq_created_at | formatDateTime }}</span>
                                                    </span>
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
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
<script>
    const tableFields = {
        'ureq_id': '',
        'ureq_user_id': '',
        'ureq_type': '',
        'ureq_purpose': '',
        'ureq_created_at': ''
    };
    export default {
        data: function() {
            return {
                records: [],
                search: '',
                recordId: '',
                pagination: [],
                loading: false,
                adminsData: tableFields,
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
                this.$http.post(adminBaseUrl + '/userRequests/list?page=' + pageNo, formData).then((response) => {
                    this.records = response.data.data.requests.data;
                    this.pagination = response.data.data.requests;
                    this.loading = false;
                    this.showEmpty = response.data.data.showEmpty;
                    this.pageLoaded = 1;
                });
            },
        },
        mounted() {
            let inputval = document.getElementById("id");
            if (inputval == null) {
                this.pageRecords(this.pagination.current_page, true);
                return;
            }
        }
    }
</script>