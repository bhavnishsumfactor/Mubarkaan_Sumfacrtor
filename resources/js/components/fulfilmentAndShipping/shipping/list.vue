<template>
    <div id="manage-admins">
       
    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_SHIPPING_PROFILES_ZONES_RATES') }}</h3>               
            </div>
            <div class="subheader__toolbar" >
                <router-link :to="{name: 'createShipping'}" class="btn btn-white" v-bind:class="[(!$canWrite('SHIPPING_ZONE_RATE')) ? 'disabledbutton': '']"><i class="fas fa-plus"></i>{{$t('BTN_ADD')}}
                </router-link>
            </div>
        </div>
    </div>

        <div class="container">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="alert alert-light alert-elevate fade show" role="alert">
                            <div class="alert-icon"><i class="flaticon-signs-2 font-brand"></i></div>
                            <div class="alert-text">{{ $t('LBL_SHIPPING_ZONES_RATES_INFO') }} 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <!--begin::Portlet-->
                        <div class="portlet portlet-no-min-height">
                            <div class="portlet__body">
                            <div class="py-4" v-for="profile in profilesData" :key="'profiles'+profile.sprofile_id">
                                <div class="row justify-content-between">
                                    <div class="col">
                                        <div class="row align-items-center">

                                                <div class="col-auto">
                                                    <h6 class="font-bold">{{profile.sprofile_name}} <span class="badge badge--brand badge--inline badge--pill" v-if="profile.sprofile_default == 1 ">{{$t('LBL_DEFAULT')}}</span></h6>
                                                    <p class="mb-0" v-if="profile.sprofile_default != 1">{{profile.products.length}} {{$t('LBL_PRODUCT_S')}} </p>
                                                    <p class="mb-0" v-if="profile.sprofile_default == 1">{{unAssignedProducts}} {{$t('LBL_PRODUCT_S')}} </p>
                                                </div>

                                                <div class="col-auto"></div>
                                            </div>
                                        </div>

                                        <div class="col-auto">
                                            <div class="actions">
                                                <a class="btn btn-sm btn-light btn-icon" @click="editShippingProfile(profile.sprofile_id)"  href="javascript:;" v-b-tooltip.hover :title="$t('TTL_MANAGE_PROFILE')" v-bind:class="[(!$canWrite('SHIPPING_ZONE_RATE')) ? 'disabledbutton': '']">
                                                    <svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                                                            </svg>
                                                </a>
                                                <a class="btn btn-light btn-sm btn-icon" href="javascript:;" v-b-tooltip.hover :title="$t('TTL_DEFAULT_PROFILE')" v-bind:class="[ profile.sprofile_default == 1 ? 'disabled' : '', (!$canWrite('SHIPPING_ZONE_RATE')) ? 'disabledbutton': '']"  v-if="profile.sprofile_default == 1 " >
                                                    <svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'" ></use>                               
                                                            </svg>
                                                </a>
                                                <a class="btn btn-light btn-sm btn-icon" href="javascript:;" @click="confirmDelete(profile.sprofile_id,'profile')" v-b-tooltip.hover :title="$t('TTL_DELETE')"  v-if="profile.sprofile_default != 1 " v-bind:class="[(!$canWrite('SHIPPING_ZONE_RATE')) ? 'disabledbutton': '']">
                                                    <svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#delete-icon'" ></use>                               
                                                            </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" v-if="profile.zones.length>0">
                                        <div class="col">
                                            <label class="font-bold mt-4">{{$t('LBL_RATES_FOR')}}</label>
                                            <ul class="list list-countries">                                                
                                                <li v-for="(zone, key) in profile.zones" :key="'zone'+key">{{zone.spzone_name}}</li><li class="d-none"></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Portlet-->
                        <!-- <div class="portlet portlet-no-min-height">
                                <div class="portlet__head">
                                    <div class="portlet__head-label">
                                        <h3 class="portlet__head-title">{{$t('Shipping Packages')}}</h3>
                                    </div>
                                    <div class="portlet__head-toolbar">
                                        <div class="portlet__head-actions">
                                            <a class="link font-bolder" href="javascript:;" @click="openPopup()"> {{$t('Add package')}}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="portlet__body">
                                    <div class="py-4 border-bottom" v-for="packageRecord in packageRecords" :key="'package'+key">
                                        <div class="row justify-content-between">
                                            <div class="col">
                                                <div class="row align-items-center">
                                                    <div class="col-auto pr-0">
                                                        <button class="btn btn-secondary btn-elevate btn-icon">
                                                            <i class="fas fa-box-open"></i></button>
                                                    </div>
                                                    <div class="col-auto">
                                                        <h6 class="font-bold">{{packageRecord.sbpkg_name}}</h6>
                                                        <p class="mb-0">{{packageRecord.sbpkg_length}} × {{packageRecord.sbpkg_width}} × {{packageRecord.sbpkg_heigth}} {{dimensionsData[packageRecord.sbpkg_dimension_type]}}</p>
                                                    </div>
                                                    <div class="col-auto" v-if="packageRecord.sbpkg_default == 1"><span class="badge badge--unified-brand badge--inline badge--pill">{{$t('Default')}}</span></div>
                                                </div>
                                            </div>
                                            <div class="col-auto" v-if="$canWrite('SHIPPING_MANAGEMENT')">

                                                <a class="btn btn-sm btn-light btn-icon" href="javascript:;" @click.prevent="editPackageRecord(packageRecord)" title="Edit details"><svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                                                            </svg></a>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div> -->
                        <!--begin::Portlet-->
                        <div class="portlet portlet-no-min-height">
                            <div class="portlet__head">
                                <div class="portlet__head-label">
                                    <h3 class="portlet__head-title">{{$t('LBL_PACKING_SLIP')}}
                                    </h3>
                                </div>
                                <div class="portlet__head-toolbar">
                                    <div class="portlet__head-actions">
                                        <div class="actions">
                                        <router-link :to="{name: 'editPackingSlip' }" class="btn btn-light btn-sm btn-icon " v-bind:class="[(!$canWrite('SHIPPING_ZONE_RATE')) ? 'disabledbutton': '']"><svg>   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                                                            </svg></router-link>
                                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="portlet__body">
                                {{$t('LBL_PACKING_SLIP_INFO')}}.
                            </div>
                        </div>
                        <!--end::Portlet-->
                    </div>
                </div>
            </div>
            <shipping :recordData="recordData" :dimensionsData="dimensionsData" @closePopup="closePopup" @pageRecords="pageRecords"></shipping>
            <DeleteModel :deletePopText="$t('LBL_SHIPPING_DELETE_TEXT')" :subText="$t('LBL_SHIPPING_DELETE_SUBTEXT')" :recordId="recordId" @deleted="deleteRecord(recordId)"></DeleteModel>
        </div>
    </div>
</template>
<script>
    import shipping from './packagePopup';
    const tableFields = {
        'sbpkg_id': '',
        'sbpkg_name': '',
        'sbpkg_length': '',
        'sbpkg_width': '',
        'sbpkg_heigth': '',
        'sbpkg_dimension_type': '',
        'sbpkg_default': ''
    };
    export default {
        components: {
            shipping
        },
        data: function() {
            return {
                records: [],
                baseUrl: baseUrl,
                profileZoneTableData: [],
                profilesData: [],
                packageRecords: [],
                dimensionsData: [],
                recordData: tableFields,
                packageModelId: 'packagePopup',
                deleteType: '',
                recordId: '',
                unAssignedProducts: '',
                modelId: 'deleteModel'
            }
        },

        methods: {
            pageRecords: function() {
                this.$http.post(adminBaseUrl + '/shipping/list').then((response) => {
                    this.packageRecords = response.data.data.packageRecords;
                    this.dimensionsData = response.data.data.dimensions;
                    this.profilesData = response.data.data.profilesRecords;
                    this.unAssignedProducts = response.data.data.unAssignedProducts;
                });
            },
            editShippingProfile: function(profileId) {
                 this.$router.push({name: 'editShippingProfile', params: {id: profileId}})
            },
            confirmDelete: function(Id, type) {
                this.$bvModal.show(this.modelId);
                this.recordId = Id;
                this.deleteType = type;
            },
            deleteRecord: function(recordId) {
                let formData = this.$serializeData({
                    id: recordId
                });
                this.$http.post(adminBaseUrl + '/shipping/delete/' + this.deleteType, formData).then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    toastr.success(response.data.message, '', toastr.options);
                    this.pageRecords();
                    this.$bvModal.hide(this.modelId);
                });
            },
            openPopup: function() {
                this.emptyZoneFormValues();
                this.$bvModal.show(this.packageModelId);
            },
            closePopup: function() {
                this.$bvModal.hide(this.packageModelId);
            },
            editPackageRecord: function(record) {
                this.openPopup();
                this.recordData.sbpkg_name = record.sbpkg_name;
                this.recordData.sbpkg_length = record.sbpkg_length;
                this.recordData.sbpkg_width = record.sbpkg_width;
                this.recordData.sbpkg_heigth = record.sbpkg_heigth;
                this.recordData.sbpkg_dimension_type = record.sbpkg_dimension_type;
                this.recordData.sbpkg_default = parseInt(record.sbpkg_default);
                this.recordData.sbpkg_id = record.sbpkg_id;
            },
            emptyZoneFormValues: function() {
                this.profileZoneTableData = {
                    'sprofile_name': this.profileZoneTableData.sprofile_name,
                    'sprofile_id': this.profileZoneTableData.sprofile_id,
                    'spzone_shipping_type': '',
                    'spzone_id': '',
                    'spzone_name': ''
                };
                this.errors.clear();
            },

        },
        mounted() {
            this.pageRecords();
        }
    }
</script>