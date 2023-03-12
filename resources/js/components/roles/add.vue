<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{ $t('LBL_ROLE_SETUP') }}</h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_USERS')}}</span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <router-link :to="{name: 'roles'}" class="subheader__breadcrumbs-link">{{ $t('LBL_ROLES')}}</router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{$t('LBL_ROLE_SETUP')}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="portlet portlet-no-min-height">
                <div class="portlet__body pb-0 bg-gray flex-grow-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                
                                <div class="portlet__head-toolbar" >
                                    <input type="text" :placeholder="$t('PLH_PLEASE_ENTER_ROLE_NAME')" v-model="rolesData.role_name" name="role_name"
                                        v-validate="'required'" :data-vv-as="$t('LBL_ROLE_NAME')"
                                        data-vv-validate-on="none" class="form-control" aria-required="true"
                                        aria-invalid="false">
                                    <span v-if="errors.first('role_name')"
                                        class="error text-danger">{{ errors.first('role_name') }}</span>

                                    <div class="portlet__head-actions" id="tooltip-target-1" v-if="selectedPage == 'editform'">
                                        <i class="fas fa-info-circle px-2"></i>
                                    </div>
                                    <b-popover
                                    target="tooltip-target-1"
                                    triggers="hover"
                                    placement="top"
                                    class="popover-cover" v-if="selectedPage == 'editform'"
                                    >
                                        <div class="list-stats">
                                            <div class="lable">
                                            <span class="stats_title">{{ $t('LBL_CREATED') }}:</span>
                                            <span
                                                class="time"
                                            >{{ createdByUser ? createdByUser+ ' |' : '' }} {{ createdAt | formatDateTime}}</span>
                                            </div>
                                            <div class="lable">
                                            <span class="stats_title">{{ $t('LBL_LAST_UPDATED') }}:</span>
                                            <span
                                                class="time"
                                            >{{ updatedByUser ? updatedByUser+ ' |' : ''}} {{ updatedAt | formatDateTime}}</span>
                                            </div>
                                        </div>
                                    </b-popover>
                                </div> 
                            </div>
                        </div>
               
                    </div>
                   
                </div>
            </div>
            <div class="portlet">
                <div class="portlet__body pb-0 bg-gray">
                    <div class="row">
                        
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="input-icon input-icon--left">
                                    <input type="text" :placeholder="$t('PLH_SEARCH') + '...'" id="generalSearch" class="form-control" v-model="searchData.search" @keyup="searchRecords"> 
                                    <span class="input-icon__icon input-icon__icon--left">  
                                        <span>  <i class="la la-search"></i>    </span>
                                    </span>
                                    <span class="input-icon__icon input-icon__icon--right" v-if="searchData.search!=''" @click="searchData.search=''; pageloadData(true);">
                                        <span><i class="fas fa-times"></i></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    <div class="row" v-bind:class="[(!$canWrite('ADMIN_USERS')) ? 'disabledbutton': '']">
                        <div class="col-xl-6 col-md-12 mb-4" v-for="(record, index) in permissionSections" :key="'rolestab'+index">
                            <div class="portlet portlet-no-min-height portlet--height-fluid">
                                <div class="portlet__body">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-auto col-md-6">
                                                    <div><h5>{{ $t(index) }}</h5></div>
                                                </div>
                                                <div class="col col-md-2">
                                                    <div><label class="checkbox"><input type="radio"  :name="'radiorole-'+index" :value="0" @click="changePermissionType(index, 0)" v-model="rolesData.roleGroup[index]"> {{ $t('LBL_PERMISSION_NONE') }} 
                                                            <span></span></label></div>
                                                </div>
                                                <div class="col col-md-2">
                                                    <div>
                                                        <label class="checkbox">
                                                            <input type="radio" :name="'radiorole-'+index" :value="1" @click="changePermissionType(index, 1)" v-model="rolesData.roleGroup[index]" :disabled="index =='System Settings'"> {{ $t('LBL_PERMISSION_READ') }} 
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            <div class="col col-md-2">
                                                    <div><label class="checkbox"><input type="radio" :name="'radiorole-'+index" :value="2" @click="changePermissionType(index, 2)" v-model="rolesData.roleGroup[index]" :disabled="index == 'Dashboard'"> {{ $t('LBL_PERMISSION_WRITE') }}
                                                    <span></span></label></div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item" v-for="(subModule, roleIndex) in record" :key="'modules'+roleIndex" :id="roleIndex">
                                            <div class="row">
                                                <div class="col-auto col-md-6">
                                                    <div v-html="$options.filters.highlight($t(subModule), searchData.search)"></div>
                                                </div>
                                            <div class="col col-md-2">
                                                    <div><label class="checkbox"><input type="radio"  value="0" v-model="rolesData.role[roleIndex]" @click="unCheckedCheckbox(roleIndex, index)"> <span></span></label></div>
                                                </div>
                                            <div class="col col-md-2"  v-bind:class="[index == 'System Settings' ? 'disabled' : '']">
                                                    <div><label class="checkbox"><input type="radio"  value="1" v-model="rolesData.role[roleIndex]" :disabled="index =='System Settings'" @click="unCheckedCheckbox(roleIndex, index)"> <span></span></label></div>
                                                </div>
                                            <div class="col col-md-2">                                                
                                                    <div><label class="checkbox"><input type="radio"   value="2" v-model="rolesData.role[roleIndex]" :disabled="index == 'Dashboard'" @click="unCheckedCheckbox(roleIndex, index)"> <span></span></label></div>
                                                </div>
                                            </div>
                                        </li>
                                        <p class="text-muted form-text mt-3" v-if="index =='System Settings'"> {{$t('LBL_NOTE') }} {{$t('LBL_PERMISSION_DISCLAIMER')}}</p>
                                    </ul>
                                </div>
                            </div>
                        </div>    
                         <div class="col-xl-12 col-md-12 mb-4" v-if="searchData.search!='' && Object.keys(permissionSections).length == 0">
                            <div class="portlet  portlet--height-fluid">
                                <noRecordsFound></noRecordsFound> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="portlet__foot">
                    <div class="row">
                        <div class="col">
                            <router-link :to="{name: 'roles'}" class="btn btn-secondary  btn-wide">{{ $t('BTN_DISCARD')}}</router-link>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-brand btn-wide gb-btn gb-btn-primary" @click="validateRecord()" :disabled='!isComplete || clicked==1' v-bind:class="[clicked==1 && 'gb-is-loading', (!$canWrite('ADMIN_USERS')) ? 'disabledbutton': '']">{{ (this.selectedPage == 'addform') ? $t('BTN_ROLE_CREATE') : $t('BTN_ROLE_UPDATE') }}</button>
                        </div>
                    </div>
                </div>
            </div>           
        </div>
    </div>
</template>
<script>
    const tableFields = {
        'role_name': '',
        'role': {},
        'roleGroup': {},
        'role_id': '',
    };
    const searchFields = {
        'search': ''
    };
    export default {
        data: function () {
            return {
                permissionSections: [],
                originalPermissionSection: [],
                rolesData: tableFields,
                searchData: searchFields,
                clicked: 0,
                selectedPage:'',
                createdByUser: '',
                updatedByUser: '',
                updatedAt: '',
                createdAt: '',
                showEmpty: 1
            }
        },
        computed: {
            isComplete () {
                return this.rolesData.role_name;
            }
        },
        methods: {
            validateErrors: function (response) {
                let jsondata = response.data;
                Object.keys(jsondata.errors).forEach(key => {
                    this.errors.add({
                        field: key,
                        msg: jsondata.errors[key][0]
                    });
                });
            },
            validateRecord: function () {
                this.$validator.validateAll().then(res => {
                    if (res) {
                        this.clicked = 1;
                        if (this.$route.params.id === undefined) {
                            this.saveRoles();
                        } else {
                            this.updateRoles();
                        }
                    } else {
                        this.clicked = 0;
                    }
                });
            },
            saveRoles: function () {
                let formData = this.$serializeData({
                    role_name: this.rolesData.role_name, 
                    role: JSON.stringify(this.rolesData.role),
                });
                this.$http.post(adminBaseUrl + '/roles', formData)
                    .then((response) => { //success
                        if (response.data.status == false) {
                            this.clicked = 0;
                            toastr.error(response.data.message, '', toastr.options);
                            return;
                        }
                        toastr.success(response.data.message, '', toastr.options);
                        this.clicked = 0;
                        this.$router.push({
                            name: 'roles'
                        })
                    }, (response) => { //error
                        this.clicked = 0;
                        this.validateErrors(response);
                    });
            },
            updateRoles: function() {
                let formData = this.$serializeData({
                    role_id: this.rolesData.role_id, 
                    role_name: this.rolesData.role_name, 
                    role: JSON.stringify(this.rolesData.role),
                    roleGroup: JSON.stringify(this.rolesData.roleGroup)
                });
                formData.append("_method", "PUT");
                this.clicked = 1;
                this.$http.post(adminBaseUrl + '/roles/' + this.rolesData.role_id, formData)
                    .then((response) => { //success
                        if (response.data.status == false) {
                            this.clicked = 0;
                            toastr.error(response.data.message, '', toastr.options);
                            return;
                        }
                        toastr.success(response.data.message, '', toastr.options);
                        this.clicked = 0;
                        this.$router.push({
                            name: 'roles'
                        })
                    }, (response) => { //error
                        this.clicked = 0;
                        this.validateErrors(response);
                    });
            },
            pageloadData: function(clear = false) {
                
                this.$http.get(adminBaseUrl + '/roles/get-all-permission-section').then((response) => {
                    this.permissionSections = response.data.data;
                    this.originalPermissionSection = Object.assign({}, response.data.data);
                    if(clear==false) {
                        this.rolesData.role_name = response.data.data.role_name;
                        this.rolesData.role_id = response.data.data.role_id;
                        this.rolesData.role = {};
                        this.rolesData.roleGroup = [];
                    }
                });
            },
            pageRecord: function() {
                let id = this.$route.params.id;
                this.$http.get(adminBaseUrl + '/roles/' + id).then((response) => {
                    this.permissionSections = response.data.data.permissions;
                    this.originalPermissionSection = Object.assign({}, response.data.data.permissions);
                    this.rolesData.role_name = response.data.data.role_name;
                    this.rolesData.role_id = response.data.data.role_id;
                    if (Object.keys(response.data.data.role).length > 1) {
                        this.rolesData.role = response.data.data.role;
                    }
                    if (this.selectedPage == 'editform') {
                        if (response.data.data.created_at != null && "admin_username" in response.data.data.created_at) {
                            this.createdByUser = response.data.data.created_at.admin_username;
                        }
                        if (response.data.data.updated_at != null && "admin_username" in response.data.data.updated_at) {
                            this.updatedByUser = response.data.data.updated_at.admin_username;
                        }
                        this.updatedAt = response.data.data.role_updated_at ? response.data.data.role_updated_at : '';
                        this.createdAt = response.data.data.role_created_at ? response.data.data.role_created_at : '';
                        this.rolesData.roleGroup = [];
                    }
                });
            },
            changePermissionType : function(type, value) {
                Object.keys(this.permissionSections[type]).forEach(roleKey => {
                    this.rolesData.role[roleKey] = value;
                    this.$forceUpdate(); 
                });
            },
            searchRecords: function() {
                this.rolesData.roleGroup.Products = false;
                this.permissionSections = Object.assign({}, this.originalPermissionSection);
                Object.keys(this.permissionSections).forEach(roleSection => {
                    let checked = 0;
                    if (!roleSection.toLowerCase().includes(this.searchData.search)) {
                        Object.values(this.permissionSections[roleSection]).forEach(role => {
                            if (role.toLowerCase().includes(this.searchData.search)) {
                                checked = 1;
                                return false
                            }
                        });
                        if (checked == 0) {
                            delete this.permissionSections[roleSection];
                        }
                    }
                });
            },
            emptyUpdatedFieldValue : function() {
                this.createdByUser = '';
                this.updatedByUser = '';
                this.updatedAt = '';
                this.createdAt = '';
            },
            unCheckedCheckbox : function(roleIndex, index) {
                delete this.rolesData.role[roleIndex]; 
                delete this.rolesData.roleGroup[index];
                this.$forceUpdate();                
            }
        },
        mounted() {
            this.searchData.search = '';
            if(this.$route.params.id === undefined) {
                this.pageloadData();
                this.selectedPage= 'addform';
            } else {
                this.pageRecord();
                this.selectedPage= 'editform';
            }
        }
    }

</script>