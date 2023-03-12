<template>
<div>
    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_PICKUP_ADDRESSES') }}</h3>
            </div>
            <div class="subheader__toolbar">
				<router-link :to="{name: 'createPickup'}" class="btn btn-white" v-bind:class="[(!$canWrite('PICKUP_ADDRESS')) ? 'disabledbutton': '']"><i class="fas fa-plus"></i>{{ $t('BTN_ADD')}}
                </router-link>
			</div>
        </div>
    </div>

  <div class="container">
    <div class="" id="manage-admins">
        <div class="row">
            <div class="col-xl-12">
                <!--begin::Portlet-->
                <div class="portlet portlet--height-fluid">
                    
                    <div class="portlet__body pb-0 bg-gray flex-grow-0" v-if="showEmpty == 0">
                        <div class="row mb-4">
                            <div class="col-lg-12">
                                <div class="input-icon input-icon--left">
                                    <input type="text" class="form-control" :placeholder="$t('PLH_SEARCH')" id="generalSearch" @keyup="searchRecords" v-model="search">
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
                    <div class="portlet__body portlet__body--fit" v-if="showEmpty == 0 && pageLoaded==1">
                        <!--begin::Section-->
                        <div class="section">
                            <div class="section__content">
                                <table class="table table-data table-justified">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ $t('LBL_STORE_NAME') }}</th>
                                            <th>{{ $t('LBL_ADDRESS') }}</th>
                                            <th>{{ $t('LBL_CITY') }}</th>
                                            <th>{{ $t('LBL_STATE') }}</th>
                                            <th>{{ $t('LBL_COUNTRY') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="loading==false && records.length == 0 && showEmpty == 0 && pageLoaded==1">
                                        <tr>
                                            <td colspan="7">  
                                                <noRecordsFound></noRecordsFound> 
                                            </td>
                                        </tr>
                                    </tbody>  
                                    <tbody v-else>
                                        <tr v-for="(record, index) in records" :key="index">
                                            <td scope="row">{{ pagination.from+index }}</td>
                                            <td>
                                                <a href="javascript:;" @click.prevent="openViewMode(record)" v-if="!$canWrite('PICKUP_ADDRESS')">{{ record.saddr_name }}</a>
                                                <div v-else>{{ record.saddr_name }}</div>
                                            </td>
                                            <td>{{ record.saddr_address }}</td>
                                            <td>{{ record.saddr_city }}</td>
                                            <td>{{ record.state_name }}</td>
                                            <td>{{ record.country_name }}</td>
                                            <td>
                                                <div class="actions">
                                                    <router-link :to="{name: 'editPickup', params: {id: record.saddr_id}}" v-b-tooltip.hover :title="$t('TTL_EDIT')" class="btn btn-light btn-sm btn-icon " v-bind:class="[(!$canWrite('PICKUP_ADDRESS')) ? 'disabledbutton': '']"><svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                                                            </svg></router-link>
                                                    <a class="btn btn-light btn-sm btn-icon " href="javascipt:;" @click.capture="confirmDelete($event, record.saddr_id)" v-b-tooltip.hover :title="$t('TTL_DELETE')" v-bind:class="[(!$canWrite('PICKUP_ADDRESS')) ? 'disabledbutton': '']">
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
                        <!--end::Section-->
                    </div>
                    <div class="portlet__body" v-if="showEmpty == 1 && pageLoaded == 1">
                        <div class="get-started ">
                            <div class="get-started-arrow d-flex justify-content-end mb-5">
                                <img :src="baseUrl+'/admin/images/get-started-graphic/get-started-arrow-head.svg'"/>
                            </div>
                            <div class="no-content d-flex text-center flex-column mb-7">
                                <p>{{ $t('LBL_CLICK_THE') }} <router-link :to="{name: 'createPickup'}" class="btn btn-brand btn-small" v-bind:class="[(!$canWrite('PICKUP_ADDRESS')) ? 'disabledbutton': '']"><i class="fas fa-plus"></i>{{ $t('BTN_ADD')}}</router-link> {{ $t('LBL_BUTTON_TO_CREATE_PICKUP') }}</p>
                            </div>
                            <div class="get-started-media">
                                <img :src="baseUrl+'/admin/images/get-started-graphic/get-started-pickup-address.svg'"/>
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
        <DeleteModel :deletePopText="$t('LBL_PICKUP_DELETE_TEXT')" :recordId="recordId" @deleted="deleteRecord(recordId)"></DeleteModel>
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
            records: [],
            recordId: '',
            search: '',
            pagination: [],
            modelId: 'deleteModel',
            productLink: 0,
            searchData: searchFields,
            loading: false,
            baseUrl: baseUrl,
            pageLoaded: 0,          
            showEmpty: 1
        }
    },
    methods: {
        searchRecords: function() {
            this.pageRecords(this.pagination.current_page);
        },
        currentPage: function(page) {
            this.searchRecords();
        },
        pageRecords: function(pageNo, pageLoad = false) {
            this.loading = pageLoad; 
            let formData = this.$serializeData({
                'search': this.search
            });

            this.$http.post(adminBaseUrl + '/store-address/list?page=' + pageNo, formData).then((response) => {
				if (response.data.status == false) {
					toastr.error(response.data.message, '', toastr.options);
					return;
				}
                this.records = response.data.data.addresses.data;
                this.pagination = response.data.data.addresses;
                this.loading = false;
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
            this.$http.delete(adminBaseUrl + '/store-address/' + recordId).then((response) => {
                if (response.data.status == false) {
                    toastr.error(response.data.message, '', toastr.options);
                    return;
                }
                toastr.success(response.data.message, '', toastr.options);
                this.pageRecords(this.pagination.current_page);
                this.$bvModal.hide(this.modelId);
            });
        },
        openViewMode: function(record) {
            this.$router.push({name:'editPickup', params: { id: record.saddr_id } });
        }
    },
    mounted() {
        this.pageRecords(this.pagination.current_page, true);
    }
}
</script>
