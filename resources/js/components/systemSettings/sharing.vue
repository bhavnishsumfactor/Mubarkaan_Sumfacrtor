<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{ $t('LBL_SYSTEM_SETTINGS') }}</h3>
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
                                    <div class="form-group row justify-content-center">
                                        <label class="col-lg-9 col-form-label">
                                            <h3 class="section__title section__title-sm">{{$t('LBL_ENABLE_SOCIAL_NETWORKS')}}</h3>                                                        
                                        </label>
                                        <div class="col-lg-9">
                                            <ul class="list-switch list-switch--three">
                                                <li v-for="(val, key) in networks" v-bind:key="key">                                                                
                                                    <label class="checkbox checkbox--socialBtn" :class="'bg-'+key">                                                                    
                                                            <input @click="updateNetwork($event, key)" value="1" type="checkbox" :checked="val=='1'"> 
                                                            <span>
                                                            <i class="svg--icon">
                                                                <svg class="svg" width="24px" height="24px">
                                                                    <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#'+key" :href="baseUrl+'/admin/images/retina/sprite.svg#'+key"></use>
                                                                </svg>
                                                            </i>
                                                            {{key | camelCase}}
                                                            </span>                                                                                                           
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    export default {
        components: {
            'sidebar': sidebar
        },
        data: function() {
            return {
                networks: [],
                type: 'socialSharing',
                baseUrl: baseUrl
            }
        },
        methods: {
            getSocialNetworks: function() {
                this.$http.get(adminBaseUrl + '/sharethis').then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    this.networks = response.data.data.networks;
                });
            },
            updateNetwork: function(event, network) {
                let formData = this.$serializeData({
                    'network': network,
                    'status': event.target.checked
                });
                this.$http.post(adminBaseUrl + '/sharethis', formData)
                    .then((response) => { //success
                        if (response.data.status == false) {
                            toastr.error(response.data.message, '', toastr.options);
                            return;
                        }
                    
                    });
            }
        },
        mounted: function() {
            this.getSocialNetworks();
        }
    }
</script>