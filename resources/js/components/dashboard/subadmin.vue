<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{ $t("LBL_DASHBOARD") }}</h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{ $t("LBL_DASHBOARD") }}</span>
                    </div>
                </div>
                <div class="subheader__toolbar">
                    <div class="subheader__wrapper">
                        <router-link
                            :to="{ name: 'productCreate' }"
                            class="btn btn-white subheader__btn-options"
                            v-bind:class="[ (!$canWrite('PRODUCTS')) ? 'disabledbutton': '']"
                        >{{ $t("BTN_ADD_PRODUCTS") }}</router-link>
                    </div>
                </div>
            </div>
        </div>

        <div class="container grid__item grid__item--fluid">
            <div class="profile-wrapper">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="portlet portlet--height-fluid portlet--tabs portlet-no-min-height">                            
                            <div class="portlet__body">
                                <div class="row">
                                    <div class="col-xl-1">  
                                        
                                        <div class="avatar">
                                            <img data-yk="" alt="Pic" v-if="adminImage != ''" :src="adminImage" />       
				                            <span v-else><b>{{ adminNameBadge }}</b></span> 
                                        </div>
                                    </div>
                                    <div class="col-auto flex-1">
                                        <div class="user__detail">
                                            <div class="widget__head mb-2">
                                                <a class="user_name">{{ adminData.admin_name }}</a>
                                            </div>
                                            <div class="user__subhead mb-3">
                                                <a href="#">
                                                    <svg height="386pt" viewBox="0 -51 386.00003 386" width="386pt" xmlns="http://www.w3.org/2000/svg"><path d="m33 0h320c17.074219.0234375 31.320312 13.050781 32.863281 30.058594l-171.464843 145.421875c-12.289063 10.480469-30.371094 10.488281-42.667969.015625l-171.597657-145.300782c1.46875-17.0625 15.738282-30.1718745 32.867188-30.195312zm0 283.140625c-7.222656.007813-14.246094-2.367187-19.980469-6.757813l95.699219-97.886718c1.773438-1.785156 2.453125-4.382813 1.78125-6.804688-.671875-2.425781-2.589844-4.300781-5.027344-4.917968-2.441406-.617188-5.023437.121093-6.765625 1.9375l-94.808593 96.972656c-2.5625-4.777344-3.90234425-10.117188-3.898438-15.542969v-201.710937l162.683594 137.753906c17.515625 14.910156 43.265625 14.902344 60.769531-.027344l162.546875-137.859375v201.84375c.007812 5.445313-1.34375 10.804687-3.925781 15.59375l-95.375-97.039063c-2.714844-2.734374-7.125-2.761718-9.875-.0625-2.746094 2.703126-2.796875 7.113282-.109375 9.875l96.226562 97.90625c-5.730468 4.371094-12.734375 6.734376-19.941406 6.726563zm0 0"/></svg>
                                                    {{ adminData.admin_email  != "null" ? adminData.admin_email : '' }}
                                                </a>
                                                <a href="#">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                                        <g>
                                                            <g>
                                                                <path d="M448,64h-48V16c0-8.832-7.168-16-16-16H96C69.536,0,48,21.536,48,48v400c0,35.296,28.704,64,64,64h336    c8.832,0,16-7.168,16-16V80C464,71.168,456.832,64,448,64z M368,400c0,8.832-7.168,16-16,16H160c-8.832,0-16-7.168-16-16v-32    c0-44.128,35.904-80,80-80h64c44.128,0,80,35.872,80,80V400z M192,192c0-35.296,28.704-64,64-64s64,28.704,64,64s-28.704,64-64,64    C220.704,256,192,227.296,192,192z M368,64H96c-8.832,0-16-7.168-16-16c0-8.832,7.168-16,16-16h272V64z"/>
                                                            </g>
                                                        </g>
                                                </svg>
                                                    {{ role }}
                                                </a>
                                                <a href="#">
                                                    <svg height="512pt" viewBox="-85 0 512 512" width="512pt" xmlns="http://www.w3.org/2000/svg"><path d="m246.84375 156.808594c0-41.800782-34.007812-75.808594-75.808594-75.808594-41.796875 0-75.804687 34.007812-75.804687 75.808594 0 41.796875 34.003906 75.804687 75.804687 75.804687 41.800782 0 75.808594-34.007812 75.808594-75.804687zm0 0"/><path d="m171.035156 422c11.746094-15.300781 80.613282-109.945312 103.796875-141.566406 27.574219-37.605469 53.011719-75.335938 53.011719-123.625 0-86.601563-70.207031-156.80468775-156.808594-156.80468775l-.019531-.00390625c-86.605469 0-156.785156 70.203125-156.785156 156.804688 0 48.289062 25.433593 86.023437 53.007812 123.628906 23.1875 31.621094 103.796875 141.566406 103.796875 141.566406zm-105.804687-265.191406c0-58.34375 47.464843-105.808594 105.804687-105.808594 58.34375 0 105.808594 47.464844 105.808594 105.808594 0 58.339844-47.464844 105.804687-105.808594 105.804687-58.339844 0-105.804687-47.464843-105.804687-105.804687zm0 0"/><path d="m228.621094 394.203125c-3.105469 4.242187-58.136719 77.78125-58.136719 77.78125l-57.027344-77.78125h-69.042969l-44.414062 117.796875h342.070312l-44.417968-117.796875zm0 0"/></svg>

                                                {{ country }}
                                                </a>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="col-auto flex-1">
                                        <div class="action__wrapper actions text-right">
                                            <router-link :to="{ name: 'editProfile' }" class="btn btn-brand subheader__btn-options">
                                                <svg width="18px" height="18px">   
                                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#edit-icon'" ></use>                               
                                                            </svg> {{ $t('BTN_EDIT') }}
                                            </router-link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6">
                        <!--begin:: Widgets/Notifications-->
                        <div class="portlet">
                            <div class="portlet__head">
                                <div class="portlet__head-label">
                                    <h3 class="portlet__head-title">
                                        {{ $t('LBL_NOTIFICATIONS') }} 
                                    </h3>
                                </div>
                                <div class="portlet__head-toolbar">
                                    <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" @click="getNotification('latest', true)" href="#widget6_tab1_content" role="tab">
                                                {{ $t('LBL_DASHBOARD_FILTER_LATEST') }} 
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" @click="getNotification('week', true)" href="#widget6_tab2_content" role="tab">
                                                {{ $t('LBL_DASHBOARD_FILTER_WEEK') }} 
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" @click="getNotification('month', true)" href="#widget6_tab3_content" role="tab">
                                                {{ $t('LBL_DASHBOARD_FILTER_MONTH') }} 
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="portlet__body">
                                
                                <div class="tab-content" v-if="totalNotification > 0 && pageLoad == false">
                                    <div class="tab-pane active" id="widget6_tab1_content" aria-expanded="true">
                                        <perfect-scrollbar :style="'max-height: 500px'" @scroll="onScrollNotification">
                                            <div class="notification YK-notifications-latest" style="height: 100%;"></div>
                                        </perfect-scrollbar>
                                    </div>

                                    <div class="tab-pane" id="widget6_tab2_content" aria-expanded="false">
                                        <div class="notification">
                                            <perfect-scrollbar :style="'max-height: 500px'" @scroll="onScrollNotification">
                                                <div class="notification YK-notifications-week" style="height: 100%;"></div>
                                            </perfect-scrollbar>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="widget6_tab3_content" aria-expanded="false">
                                        <div class="notification">
                                            <perfect-scrollbar :style="'max-height: 500px'" @scroll="onScrollNotification">
                                                <div class="notification YK-notifications-month" style="height: 100%;"></div>
                                            </perfect-scrollbar>
                                        </div>
                                    </div>
                                </div>
                                <no-records-found :heading="$t('LBL_NO_PERMISSION_FOUND')" :subHeading="''" :searchRecord="'1'" v-else></no-records-found>
                            </div>
                        </div>
                        <!--end:: Widgets/Notifications-->
                    </div>
                    <div class="col-xl-6">
                        <div class="portlet ">
                            <div class="portlet__head">
                                <div class="portlet__head-label">
                                <h3 class="portlet__head-title">{{ $t('LBL_PERMISSIONS') }}</h3>
                                </div> 
                            </div>
                            <div class="portlet__body">
                                <div class="table-responsive" v-if="Object.keys(permissions).length > 0 && pageLoad != false">
                                    <perfect-scrollbar :style="'max-height: 500px'" >
                                        <table class="table js--table-scrollable">
                                            <thead>
                                                <tr>
                                                    <td>{{ $t('LBL_MODULE') }}</td>
                                                    <td>{{ $t('LBL_PERMISSION') }}</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in permissions" :key="index" >
                                                    <td>
                                                        <router-link :to="{ name: permissionSectionUrls[permissionSection[index]] }" class="">
                                                            {{ (permissionSection[index].replace(/_/g, ' ')) }}
                                                        </router-link>
                                                    </td>
                                                    <td>
                                                        <router-link :to="{ name: permissionSectionUrls[permissionSection[index]] }" class="">
                                                            <i :class="(item == 1) ? 'fa fa-eye' : 'fa fa-pencil-alt'" aria-hidden="true"></i>
                                                        </router-link>    
                                                    </td>                               
                                                </tr>
                                            </tbody>
                                        </table>
                                    </perfect-scrollbar>
                                </div>
                                <no-records-found :heading="$t('LBL_NO_PERMISSION_FOUND')" :subHeading="''" :searchRecord="'1'" v-else></no-records-found>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data: function () {
        return {
            progress: 100,
            baseUrl: baseUrl,
            adminData: {},
            role : '',
            country: '',
            notification:'',
            permissions: [],
            permissionSection: [],
            permissionSectionUrls: [],
            notificationType:'latest',
            notificationsRow:0,
            notificationsTotal:0,
            totalNotification:0,
            notificationsPending:0,
            paginateCount: 10,
            adminImage: '',
            adminNameBadge: '',
            loadingNotification: false,
            pageLoad:0
        };
    },
    methods: {
        getAdminData:  function() {
            this.pageLoad = 1;
            this.$http.get(adminBaseUrl + "/sub-admin-dashboard").then(response => {
                this.adminData =  response.data.data.admin;
                this.role = (response.data.data.admin.admin_role.role_name != '') ? response.data.data.admin.admin_role.role_name : '';
                this.country = (response.data.data.admin.country.country_name != '') ? response.data.data.admin.country.country_name : '';
                this.permissions = response.data.data.permission;
                this.permissionSection = response.data.data.sections;
                this.permissionSectionUrls= response.data.data.sectionUrls;
                this.notificationsTotal = this.totalNotification = response.data.data.total_notification;
                this.notificationsRow = Number(this.notificationsRow) + this.paginateCount;
                this.notificationsPending = this.notificationsTotal - this.notificationsRow;
                setTimeout(() => {
                    $('.YK-notifications-latest').append(response.data.data.notifications);  
                }, 100);
                this.pageLoad = 0;
            });
        },
        getNotification: function(type,tab = false) {
            if (tab == true) {
                this.notificationsRow = 0;
                $('.YK-notifications-'+type).html('');
            }
            this.notificationType = type;
            this.loadingNotification = true;
            this.$http.get(adminBaseUrl + '/get-notifications/' + this.notificationsRow+ '/' +  this.notificationType).then((response) => {                
                $('.YK-notifications-'+type).append(response.data.data.notifications);
                this.notificationsTotal = response.data.data.total;
                this.notificationsRow = Number(this.notificationsRow) + this.paginateCount;
                this.notificationsPending = this.notificationsTotal - this.notificationsRow;
                this.loadingNotification = false;
            });
        },
        onScrollNotification ({ target: { scrollTop, clientHeight, scrollHeight }}) {
            if (this.loadingNotification === false && ((scrollTop + clientHeight + 100) >= scrollHeight) && this.notificationsPending>0) {
                this.getNotification(this.notificationType);
            }
        },
    },
    mounted() {
        this.getAdminData();
        this.$root.$on('adminImage', (data) => {
			this.adminImage = data;
		});
		this.$root.$on('adminNameBadge', (data) => {
			this.adminNameBadge = data;
		});	
    }
};
</script>
