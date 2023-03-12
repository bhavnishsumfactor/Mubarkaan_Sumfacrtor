<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{ $t('LBL_THIRD_PARTY_INTEGRATIONS') }}</h3>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="grid-layout">
                <!--Begin:: App Aside Mobile Toggle-->
                <button class="grid-layout-close" id="user_profile_aside_close">
                    <i class="la la-close"></i>
                </button>
                <!--End:: App Aside Mobile Toggle-->
                <sidebar :tab="type"></sidebar>

                <!--Begin:: App Content-->
                <div class="grid-layout-content">
                    <div class="portlet portlet--height-fluid">
                        <div class="portlet__body">
                            <div class="section">
                                <div class="section__body">
                                    <div class="row">
                                        <div class="col-md-4 mb-5">
                                            <div class="border rounded p-5 h-100 position-relative">
                                                <div class="d-flex justify-content-between text-center">
                                                    <div class="media mb-4">
                                                        <svg v-if="adminsData.FACEBOOK_CLIENT_STATUS == 0" id="regular" enable-background="new 0 0 24 24" height="40" viewBox="0 0 24 24" width="40" xmlns="http://www.w3.org/2000/svg"><path d="m6.812 13.937h2.518v9.312c0 .414.335.75.75.75l4.007.001c.415 0 .75-.337.75-.75v-9.312h2.387c.378 0 .697-.282.744-.657l.498-4c.056-.446-.293-.843-.744-.843h-2.885c.113-2.471-.435-3.202 1.172-3.202 1.088-.13 2.804.421 2.804-.75v-3.577c0-.375-.277-.692-.648-.743-.314-.043-1.555-.166-3.094-.166-7.01 0-5.567 7.772-5.74 8.437h-2.519c-.414 0-.75.336-.75.75v4c0 .414.336.75.75.75zm.75-3.999h2.518c.414 0 .75-.336.75-.75v-3.151c0-2.883 1.545-4.536 4.24-4.536.878 0 1.686.043 2.242.087v2.149c-.402.205-3.976-.884-3.976 2.697v2.755c0 .414.336.75.75.75h2.786l-.312 2.5h-2.474c-.414 0-.75.336-.75.75v9.311h-2.505v-9.312c0-.414-.336-.75-.75-.75h-2.519z"/></svg>

                                                        <svg v-else enable-background="new 0 0 24 24" height="40" viewBox="0 0 24 24"  width="40" xmlns="http://www.w3.org/2000/svg"><path d="m15.997 3.985h2.191v-3.816c-.378-.052-1.678-.169-3.192-.169-6.932 0-5.046 7.85-5.322 9h-3.487v4.266h3.486v10.734h4.274v-10.733h3.345l.531-4.266h-3.877c.188-2.824-.761-5.016 2.051-5.016z" fill="#3b5999"/></svg>
                                                    </div>                                                               
                                                    <span class="switch switch--sm ">
                                                        <label>
                                                            <input type="checkbox" name="" v-model="adminsData.FACEBOOK_CLIENT_STATUS" @change="updateClientStatus($event,'FACEBOOK_CLIENT_STATUS')">
                                                            <span></span>
                                                        </label>
                                                    </span>
                                                </div>
                                                    <div class="">
                                                    <div class="form-group">
                                                        <label class="">{{ $t('LBL_CLIENTID') }} <span class="required" v-if="adminsData.FACEBOOK_CLIENT_STATUS == 1">*</span></label>
                                                        <input type="text" v-model="adminsData.FACEBOOK_CLIENT_ID" name="fb_client_id" 
                                                        v-validate="adminsData.FACEBOOK_CLIENT_STATUS == 1 ? 'required' : '' "
                                                        :data-vv-as="$t('LBL_CLIENTID')"
                                                        data-vv-validate-on="none" class="form-control" :disabled="adminsData.FACEBOOK_CLIENT_STATUS == 0"/>
                                                        <span v-if="errors.first('fb_client_id')" class="error text-danger">{{ errors.first('fb_client_id') }}</span>
                                                    </div>
                                                    <div class="form-group mb-0">
                                                        <label class="">{{ $t('LBL_CLIENTSECRET') }} <span class="required" v-if="adminsData.FACEBOOK_CLIENT_STATUS == 1">*</span></label>
                                                        <input type="text" v-model="adminsData.FACEBOOK_CLIENT_SECRET" name="fb_client_secret" 
                                                        v-validate="adminsData.FACEBOOK_CLIENT_STATUS == 1 ? 'required' : '' "
                                                        :data-vv-as="$t('LBL_CLIENTSECRET')" 
                                                        data-vv-validate-on="none" class="form-control" :disabled="adminsData.FACEBOOK_CLIENT_STATUS == 0"/>
                                                        <span v-if="errors.first('fb_client_secret')" class="error text-danger">{{ errors.first('fb_client_secret') }}</span>
                                                    </div>
                                                </div>
                                            </div>                                                       
                                        </div><!--col-->
                                        <div class="col-md-4 mb-5">
                                            <div class="border rounded p-5 h-100 position-relative">
                                                <div class="d-flex justify-content-between text-center">
                                                    <div class="media mb-4">
                                                        <svg v-if="adminsData.INSTAGRAM_CLIENT_STATUS == 0" height="40" width="40" viewBox="0 0 512.00096 512.00096" xmlns="http://www.w3.org/2000/svg"><path d="m373.40625 0h-234.8125c-76.421875 0-138.59375 62.171875-138.59375 138.59375v234.816406c0 76.417969 62.171875 138.589844 138.59375 138.589844h234.816406c76.417969 0 138.589844-62.171875 138.589844-138.589844v-234.816406c0-76.421875-62.171875-138.59375-138.59375-138.59375zm108.578125 373.410156c0 59.867188-48.707031 108.574219-108.578125 108.574219h-234.8125c-59.871094 0-108.578125-48.707031-108.578125-108.574219v-234.816406c0-59.871094 48.707031-108.578125 108.578125-108.578125h234.816406c59.867188 0 108.574219 48.707031 108.574219 108.578125zm0 0"/><path d="m256 116.003906c-77.195312 0-139.996094 62.800782-139.996094 139.996094s62.800782 139.996094 139.996094 139.996094 139.996094-62.800782 139.996094-139.996094-62.800782-139.996094-139.996094-139.996094zm0 249.976563c-60.640625 0-109.980469-49.335938-109.980469-109.980469 0-60.640625 49.339844-109.980469 109.980469-109.980469 60.644531 0 109.980469 49.339844 109.980469 109.980469 0 60.644531-49.335938 109.980469-109.980469 109.980469zm0 0"/><path d="m399.34375 66.285156c-22.8125 0-41.367188 18.558594-41.367188 41.367188 0 22.8125 18.554688 41.371094 41.367188 41.371094s41.371094-18.558594 41.371094-41.371094-18.558594-41.367188-41.371094-41.367188zm0 52.71875c-6.257812 0-11.351562-5.09375-11.351562-11.351562 0-6.261719 5.09375-11.351563 11.351562-11.351563 6.261719 0 11.355469 5.089844 11.355469 11.351563 0 6.257812-5.09375 11.351562-11.355469 11.351562zm0 0"/></svg>

                                                    

                                                        <svg v-else height="40" viewBox="0 0 512 512" width="40" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><linearGradient id="a" gradientUnits="userSpaceOnUse" x1="42.966156268" x2="469.0337477" y1="469.0296477168" y2="42.9620562848"><stop offset="0" stop-color="#ffd600"/><stop offset=".5" stop-color="#ff0100"/><stop offset="1" stop-color="#d800b9"/></linearGradient><linearGradient id="b" gradientUnits="userSpaceOnUse" x1="163.0429956456" x2="348.9539083464" y1="348.9538083312" y2="163.0428956304"><stop offset="0" stop-color="#ff6400"/><stop offset=".5" stop-color="#ff0100"/><stop offset="1" stop-color="#fd0056"/></linearGradient><linearGradient id="c" gradientUnits="userSpaceOnUse" x1="370.9291325432" x2="414.3727849912" y1="141.0676714336" y2="97.6240189856"><stop offset="0" stop-color="#f30072"/><stop offset="1" stop-color="#e50097"/></linearGradient><path d="m510.460938 150.453125c-1.246094-27.25-5.574219-45.859375-11.902344-62.140625-6.425782-17.082031-16.503906-32.554688-29.527344-45.34375-12.785156-13.023438-28.261719-23.105469-45.34375-29.535156-16.285156-6.324219-34.890625-10.648438-62.140625-11.886719-27.300781-1.25-36.023437-1.546875-105.546875-1.546875s-78.246094.296875-105.546875 1.539062c-27.25 1.246094-45.855469 5.574219-62.140625 11.902344-17.082031 6.425782-32.554688 16.503906-45.34375 29.527344-13.023438 12.785156-23.105469 28.257812-29.535156 45.339844-6.324219 16.285156-10.648438 34.894531-11.886719 62.140625-1.25 27.304687-1.546875 36.023437-1.546875 105.546875 0 69.527344.296875 78.25 1.546875 105.550781 1.242187 27.246094 5.570313 45.855469 11.898437 62.140625 6.425782 17.078125 16.503907 32.554688 29.527344 45.339844 12.785156 13.023437 28.261719 23.101562 45.34375 29.527344 16.28125 6.332031 34.890625 10.65625 62.140625 11.902343 27.304688 1.246094 36.023438 1.539063 105.546875 1.539063 69.523438 0 78.246094-.292969 105.546875-1.539063 27.25-1.246093 45.855469-5.570312 62.140625-11.902343 34.386719-13.296876 61.570313-40.480469 74.867188-74.867188 6.332031-16.285156 10.65625-34.894531 11.902344-62.140625 1.242187-27.304687 1.539062-36.023437 1.539062-105.546875 0-69.527344-.296875-78.246094-1.539062-105.546875zm-46.082032 208.996094c-1.136718 24.960937-5.308594 38.515625-8.8125 47.535156-8.613281 22.328125-26.257812 39.972656-48.585937 48.585937-9.019531 3.503907-22.574219 7.675782-47.535157 8.8125-26.988281 1.234376-35.085937 1.492188-103.445312 1.492188-68.363281 0-76.457031-.257812-103.449219-1.492188-24.957031-1.136718-38.511719-5.308593-47.535156-8.8125-11.117187-4.105468-21.175781-10.648437-29.433594-19.152343-8.503906-8.257813-15.046875-18.3125-19.152343-29.433594-3.503907-9.019531-7.675782-22.574219-8.8125-47.535156-1.230469-26.992188-1.492188-35.089844-1.492188-103.445313 0-68.359375.261719-76.453125 1.492188-103.449218 1.140624-24.960938 5.308593-38.515626 8.8125-47.535157 4.105468-11.121093 10.652343-21.179687 19.152343-29.4375 8.257813-8.503906 18.316407-15.046875 29.4375-19.148437 9.019531-3.507813 22.574219-7.675782 47.535157-8.816406 26.992187-1.230469 35.089843-1.492188 103.445312-1.492188h-.003906c68.355468 0 76.453125.261719 103.449218 1.496094 24.960938 1.136718 38.511719 5.308594 47.535157 8.8125 11.117187 4.105468 21.175781 10.648437 29.433593 19.148437 8.503907 8.257813 15.046876 18.316407 19.148438 29.4375 3.507812 9.019531 7.679688 22.574219 8.816406 47.535157 1.230469 26.992187 1.492188 35.089843 1.492188 103.445312 0 68.359375-.257813 76.453125-1.492188 103.449219zm0 0" fill="url(#a)"/><path d="m255.996094 124.539062c-72.601563 0-131.457032 58.859376-131.457032 131.460938s58.855469 131.457031 131.457032 131.457031c72.605468 0 131.460937-58.855469 131.460937-131.457031s-58.855469-131.460938-131.460937-131.460938zm0 216.792969c-47.125-.003906-85.332032-38.207031-85.328125-85.335937 0-47.125 38.203125-85.332032 85.332031-85.332032 47.128906.003907 85.332031 38.207032 85.332031 85.332032 0 47.128906-38.207031 85.335937-85.335937 85.335937zm0 0" fill="url(#b)"/><path d="m423.371094 119.347656c0 16.964844-13.753906 30.71875-30.71875 30.71875-16.96875 0-30.722656-13.753906-30.722656-30.71875 0-16.96875 13.753906-30.722656 30.722656-30.722656 16.964844 0 30.71875 13.753906 30.71875 30.722656zm0 0" fill="url(#c)"/></svg>        
                                            </div>
                                                    <span class="switch switch--sm">
                                                        <label>
                                                            <input type="checkbox" name="" v-model="adminsData.INSTAGRAM_CLIENT_STATUS" @change="updateClientStatus($event,'INSTAGRAM_CLIENT_STATUS')">
                                                            <span></span>
                                                        </label>
                                                    </span>
                                                </div>                                                    
                                                <div class="">
                                                    <div class="form-group">
                                                        <label class="">{{ $t('LBL_CLIENTID') }} <span class="required" v-if="adminsData.INSTAGRAM_CLIENT_STATUS == 1">*</span></label>
                                                        <input type="text" v-model="adminsData.INSTAGRAM_CLIENT_ID" name="insta_client_id" 
                                                        v-validate="adminsData.INSTAGRAM_CLIENT_STATUS==1 ?'required':'' 
                                                        " :data-vv-as="$t('LBL_CLIENTID')"
                                                        data-vv-validate-on="none" class="form-control" :disabled="adminsData.INSTAGRAM_CLIENT_STATUS == 0"/>
                                                        <span v-if="errors.first('insta_client_id')" class="error text-danger">{{ errors.first('insta_client_id') }}</span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="">{{ $t('LBL_CLIENTSECRET') }} <span class="required" v-if="adminsData.INSTAGRAM_CLIENT_STATUS == 1">*</span></label>
                                                        <input type="text" v-model="adminsData.INSTAGRAM_CLIENT_SECRET" 
                                                        name="insta_client_secret" 
                                                        v-validate="adminsData.INSTAGRAM_CLIENT_STATUS == 1 ? 'required' : '' "
                                                        :data-vv-as="$t('LBL_CLIENTSECRET')" 
                                                        data-vv-validate-on="none" class="form-control" :disabled="adminsData.INSTAGRAM_CLIENT_STATUS == 0"/>
                                                        <span v-if="errors.first('insta_client_secret')" class="error text-danger">{{ errors.first('insta_client_secret') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!--col-->
                                        <div class="col-md-4 mb-5">
                                            <div class="border rounded p-5 h-100 position-relative">
                                                <div class="d-flex justify-content-between text-center">
                                                    <div class="media mb-4">
                                                        <svg v-if="adminsData.GOOGLE_CLIENT_STATUS == 0" height="40" viewBox="0 0 448 448.00087" width="40" xmlns="http://www.w3.org/2000/svg"><path d="m224 448c-123.464844 0-224-100.53125-224-224 0-123.464844 100.535156-224 224-224 59.441406-.164062 116.480469 23.457031 158.402344 65.601562l7.597656 7.597657-75.464844 75.335937-7.601562-7.601562c-35.960938-35.898438-90.941406-44.570313-136.207032-21.484375-45.265624 23.085937-70.527343 72.6875-62.578124 122.875 7.945312 50.1875 47.296874 89.554687 97.480468 97.527343 50.183594 7.972657 99.796875-17.261718 122.90625-62.515624h-115.203125v-106.667969h228.402344l1.734375 8.53125c3.003906 14.746093 4.523438 29.753906 4.53125 44.800781 0 123.46875-100.53125 224-224 224zm0-426.664062c-111.734375 0-202.667969 90.929687-202.667969 202.664062s90.933594 202.667969 202.667969 202.667969 202.667969-90.933594 202.667969-202.667969c-.007813-10.714844-.855469-21.414062-2.535157-32h-189.464843v64h125.066406l-5.066406 14.265625c-20.3125 57.269531-75.546875 94.644531-136.265625 92.199219-60.714844-2.441406-112.765625-44.128906-128.414063-102.847656-15.648437-58.714844 8.757813-120.777344 60.203125-153.109376 51.449219-32.335937 117.957032-27.40625 164.074219 12.160157l45.335937-45.332031c-37.152343-33.613282-85.503906-52.15625-135.601562-52zm0 0"/></svg>


                                                        <svg version="1.1" v-else  height="40" width="40" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                                        <path style="fill:#FBBB00;" d="M113.47,309.408L95.648,375.94l-65.139,1.378C11.042,341.211,0,299.9,0,256
                                                        c0-42.451,10.324-82.483,28.624-117.732h0.014l57.992,10.632l25.404,57.644c-5.317,15.501-8.215,32.141-8.215,49.456
                                                        C103.821,274.792,107.225,292.797,113.47,309.408z"/>
                                                        <path style="fill:#518EF8;" d="M507.527,208.176C510.467,223.662,512,239.655,512,256c0,18.328-1.927,36.206-5.598,53.451
                                                        c-12.462,58.683-45.025,109.925-90.134,146.187l-0.014-0.014l-73.044-3.727l-10.338-64.535
                                                        c29.932-17.554,53.324-45.025,65.646-77.911h-136.89V208.176h138.887L507.527,208.176L507.527,208.176z"/>
                                                        <path style="fill:#28B446;" d="M416.253,455.624l0.014,0.014C372.396,490.901,316.666,512,256,512
                                                        c-97.491,0-182.252-54.491-225.491-134.681l82.961-67.91c21.619,57.698,77.278,98.771,142.53,98.771
                                                        c28.047,0,54.323-7.582,76.87-20.818L416.253,455.624z"/>
                                                        <path style="fill:#F14336;" d="M419.404,58.936l-82.933,67.896c-23.335-14.586-50.919-23.012-80.471-23.012
                                                        c-66.729,0-123.429,42.957-143.965,102.724l-83.397-68.276h-0.014C71.23,56.123,157.06,0,256,0
                                                        C318.115,0,375.068,22.126,419.404,58.936z"/><g></g><g></g><g></g><g></g><g></g><g></g><g>
                                                        </g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                                                    </div>                                                               
                                                    <span class="switch switch--sm ">
                                                        <label>
                                                            <input type="checkbox" name="" v-model="adminsData.GOOGLE_CLIENT_STATUS" @change="updateClientStatus($event,'GOOGLE_CLIENT_STATUS')">
                                                            <span></span>
                                                        </label>
                                                    </span>
                                                </div>                                                    
                                                <div class="">
                                                    <div class="form-group">
                                                        <label class="">{{ $t('LBL_CLIENTID') }} <span class="required" v-if="adminsData.GOOGLE_CLIENT_STATUS == 1">*</span></label>
                                                        <input type="text" v-model="adminsData.GOOGLE_CLIENT_ID" name="g_client_id" 
                                                        v-validate="adminsData.GOOGLE_CLIENT_STATUS==1 ?'required':'' " :data-vv-as="$t('LBL_CLIENTID')"
                                                        data-vv-validate-on="none" class="form-control" :disabled="adminsData.GOOGLE_CLIENT_STATUS == 0"/>
                                                        <span v-if="errors.first('g_client_id')" class="error text-danger">{{ errors.first('g_client_id') }}</span>
                                                    </div>
                                                    <div class="form-group mb-0">
                                                        <label class="">{{ $t('LBL_CLIENTSECRET') }} <span class="required" v-if="adminsData.GOOGLE_CLIENT_STATUS == 1">*</span></label>
                                                        <input type="text" v-model="adminsData.GOOGLE_CLIENT_SECRET"
                                                        v-validate="adminsData.GOOGLE_CLIENT_STATUS==1 ?'required':'' " :data-vv-as="$t('LBL_CLIENTSECRET')"
                                                        name="g_client_secret" data-vv-validate-on="none" class="form-control" :disabled="adminsData.GOOGLE_CLIENT_STATUS == 0"/>
                                                        <span v-if="errors.first('g_client_secret')" class="error text-danger">{{ errors.first('g_client_secret') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!--col-->
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="portlet__foot text-center">
                            <button type="submit" class="btn btn-brand btn-wide gb-btn gb-btn-primary" @click="updateSettings" :disabled="clicked==1" v-bind:class="clicked==1 && 'gb-is-loading'">{{$t('BTN_SETTINGS_UPDATE')}}</button>
                        </div>
                    </div>
                </div>
                <!--End:: App Content-->
            </div>
        </div>
    </div>
</template>
<script>
    import sidebar from './sidebar';
    const tableFields = {
        'FACEBOOK_CLIENT_ID': '',
        'FACEBOOK_CLIENT_SECRET': '',
        'FACEBOOK_CLIENT_STATUS':0,
        'GOOGLE_CLIENT_ID': '',
        'GOOGLE_CLIENT_SECRET': '',
        'GOOGLE_CLIENT_STATUS':0,
        'INSTAGRAM_CLIENT_ID': '',
        'INSTAGRAM_CLIENT_SECRET': '',
        'INSTAGRAM_CLIENT_STATUS':0,
    };
    export default {
        components: {
            'sidebar': sidebar
        },
        data: function() {
            return {
                adminsData: tableFields,
                type: 'socialkeys',
                clicked: 0,
            }
        },
        methods: {
            getSettings: function() {
                let formData = this.$serializeData({
                    'keys': [
                        'FACEBOOK_CLIENT_ID', 'FACEBOOK_CLIENT_SECRET', 'FACEBOOK_CLIENT_STATUS',
                        'GOOGLE_CLIENT_ID', 'GOOGLE_CLIENT_SECRET', 'GOOGLE_CLIENT_STATUS',
                        'INSTAGRAM_CLIENT_ID', 'INSTAGRAM_CLIENT_SECRET', 'INSTAGRAM_CLIENT_STATUS'
                    ]
                });
                this.$http.post(adminBaseUrl + '/settings/get', formData).then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    this.adminsData = response.data.data;
                    this.adminsData.INSTAGRAM_CLIENT_STATUS = parseInt(this.adminsData.INSTAGRAM_CLIENT_STATUS);
                    this.adminsData.FACEBOOK_CLIENT_STATUS = parseInt(this.adminsData.FACEBOOK_CLIENT_STATUS);
                    this.adminsData.GOOGLE_CLIENT_STATUS = parseInt(this.adminsData.GOOGLE_CLIENT_STATUS);
                });
            },
            updateSettings: function() {
                this.$validator.validateAll().then(res => {
                    if (res) {
                        this.clicked = 1;
                        this.adminsData.INSTAGRAM_CLIENT_STATUS = parseInt(this.adminsData.INSTAGRAM_CLIENT_STATUS ? 1 : 0);
                        this.adminsData.FACEBOOK_CLIENT_STATUS = parseInt(this.adminsData.FACEBOOK_CLIENT_STATUS  ? 1 : 0);
                        this.adminsData.GOOGLE_CLIENT_STATUS = parseInt(this.adminsData.GOOGLE_CLIENT_STATUS  ? 1 : 0);
                        let formData = this.$serializeData(this.adminsData);
                        this.$http.post(adminBaseUrl + '/settings/basedonkeys', formData)
                            .then((response) => { //success
                                this.clicked = 0;
                                if (response.data.status == false) {
                                    toastr.error(response.data.message, '', toastr.options);
                                    return;
                                }
                                toastr.success(response.data.message, '', toastr.options);
                            }, (response) => { //error
                                this.clicked = 0;
                                this.validateErrors(response);
                            });
                    } else {
                        this.clicked = 0;
                    }
                });
            },
            updateClientStatus: function(event, type) {
                let status = event.target.checked ? parseInt(1) : parseInt(0);
                if (status == 0) {
                    let formData = this.$serializeData({ [type] : status});
                    this.$http.post(adminBaseUrl + '/settings/basedonkeys', formData)
                    .then((response) => { //success
                        if (response.data.status == false) {
                            toastr.error(response.data.message, '', toastr.options);
                            return;
                        }
                        toastr.success(response.data.message, '', toastr.options);
                    }, (response) => { //error

                    });
                }
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
        },
        mounted: function() {
            this.getSettings();
        }
    }
</script>