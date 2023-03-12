<template>
    <div>
        <div class="subheader" id="subheader">
            <div class="container ">
                <div class="subheader__main">
                </div>
                <div class="subheader__toolbar">
                    <a @click="skip" v-if="progress<100" class="btn btn-white" href="javascript:;"><i class="fas fa-forward"></i>{{$t('LBL_SKIP')}}</a>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="portlet">
                        <div class="progress" style="height: 1.5rem;">
                                    <div class="progress-bar" role="progressbar" :style="'width: ' + progress + '%;'" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100" v-html="progress + '%'"></div>
                                </div>  
                        <div class="portlet__head align-items-center py-4">
                            <div class="portlet__head-label">
                                <h3 class="">{{$t('LBL_WELCOME')}}
                                    <br> <small>{{$t('LBL_TIPS_TO_HELP_GET_STARTED')}}.</small></h3>
                            </div>
                        </div>
                        <div class="portlet__body p-0">
                            <ul class="list-group list-group-xl list-group-flush list-started">
                                <li class="list-group-item" v-bind:class="[ statuses.localization ==  1 ? activeClass : '']">
                                    <div class="started_icon">
                                        <router-link :to="{name: 'businessManagement'}" target="_blank" class="links">
                                            <svg>
                                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#setup-logo-address'" :href="baseUrl+'/admin/images/retina/sprite.svg#setup-logo-address'"></use>                               
                                            </svg>
                                        </router-link>
                                    </div>
                                    <div class="started_data">
                                        <h5><router-link :to="{name: 'businessManagement'}" target="_blank" class="links">{{ $t('LBL_ADD_LOCALIZATION_SETTINGS')}}</router-link></h5>
                                        <p>{{$t('LBL_LOCALIZATION_CAPTION')}}.</p>
                                    </div>
                                    <div class="started_action">
                                        <img v-if="statuses.localization == 1" :src="baseUrl + '/admin/images/retina/tick-green.svg'" alt="">
                                        <img v-if="statuses.localization == 0" :src="baseUrl + '/admin/images/retina/tick-unfill.svg'" alt="">
                                    </div>
                                </li>
                                <li class="list-group-item" v-bind:class="[ statuses.storeinfo ==  1 ? activeClass : '']">
                                    <div class="started_icon">
                                        <router-link :to="{name: 'settingsLogo'}" target="_blank" class="links">
                                        <svg>   
                                            <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#setup-logo-address'" :href="baseUrl+'/admin/images/retina/sprite.svg#setup-logo-address'"></use>                               
                                        </svg>
                                        </router-link>
                                    </div>
                                    <div class="started_data">
                                        <h5><router-link :to="{name: 'settingsLogo'}" target="_blank" class="links">{{ $t('LBL_CONFIGURE_GENERAL_SETTINGS')}}</router-link></h5>
                                        <p>{{ $t('LBL_GENERAL_SETTINGS_CAPTION')}}.</p>
                                    </div>
                                    <div class="started_action">
                                        <img v-if="statuses.storeinfo == 1" :src="baseUrl + '/admin/images/retina/tick-green.svg'" alt="">
                                        <img v-if="statuses.storeinfo == 0" :src="baseUrl + '/admin/images/retina/tick-unfill.svg'" alt="">
                                    </div>
                                </li>
                                <li class="list-group-item" v-bind:class="[ statuses.homepage ==  1 ? activeClass : '']">
                                    <div class="started_icon">
                                        <a target="_blank" :href="adminBaseUrl + '/pages/1/edit'" class="links">
                                         <svg>   
                                            <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#setup-homepage'" :href="baseUrl+'/admin/images/retina/sprite.svg#setup-homepage'"></use>                               
                                        </svg>
                                        </a>
                                    </div>
                                    <div class="started_data">
                                        <h5><a target="_blank" :href="adminBaseUrl + '/pages/1/edit'" class="links">{{ $t('LBL_CONFIGURE_HOMEPAGE')}}</a></h5>
                                        <p>{{ $t('LBL_CONFIGURE_HOMEPAGE_CAPTION')}}</p>
                                    </div>
                                    <div class="started_action">
                                        <img v-if="statuses.homepage == 1" :src="baseUrl + '/admin/images/retina/tick-green.svg'" alt="">
                                        <img v-if="statuses.homepage == 0" :src="baseUrl + '/admin/images/retina/tick-unfill.svg'" alt="">
                                    </div>
                                </li>
                                <li class="list-group-item" v-bind:class="[ statuses.paymentgateway ==  1 ? activeClass : '']">
                                    <div class="started_icon">
                                        <router-link :to="{name: 'paymentMethods'}" target="_blank" class="links">
                                        <svg>   
                                            <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#setup-payment-gateway'" :href="baseUrl+'/admin/images/retina/sprite.svg#setup-payment-gateway'"></use>                               
                                        </svg>
                                        </router-link>
                                    </div>
                                    <div class="started_data">
                                        <h5><router-link :to="{name: 'paymentMethods'}" target="_blank" class="links">{{ $t('LBL_SETUP_PAYMENT_GATEWAY')}}</router-link></h5>
                                        <p>{{ $t('LBL_PAYMENT_GATEWAY_CAPTION')}}.</p>
                                    </div>
                                    <div class="started_action">
                                        <img v-if="statuses.paymentgateway == 1" :src="baseUrl + '/admin/images/retina/tick-green.svg'" alt="">
                                        <img v-if="statuses.paymentgateway == 0" :src="baseUrl + '/admin/images/retina/tick-unfill.svg'" alt="">
                                    </div>
                                </li>
                                <li class="list-group-item" v-bind:class="[ statuses.shipping ==  1 ? activeClass : '']">
                                    <div class="started_icon">
                                        <router-link :to="{name: 'createShipping'}" target="_blank" class="links">
                                        <svg>   
                                            <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#setup-shipping-charges'" :href="baseUrl+'/admin/images/retina/sprite.svg#setup-shipping-charges'"></use>                               
                                        </svg>
                                        </router-link>
                                    </div>
                                    <div class="started_data">
                                        <h5><router-link :to="{name: 'createShipping'}" target="_blank" class="links">{{ $t('LBL_SETUP_SHIPPING')}}</router-link></h5>
                                        <p>{{ $t('LBL_SETUP_SHIPPING_CAPTION')}}.</p>
                                    </div>
                                    <div class="started_action">
                                        <img v-if="statuses.shipping == 1" :src="baseUrl + '/admin/images/retina/tick-green.svg'" alt="">
                                        <img v-if="statuses.shipping == 0" :src="baseUrl + '/admin/images/retina/tick-unfill.svg'" alt="">
                                    </div>
                                </li>
                                <li class="list-group-item" v-bind:class="[ statuses.tax ==  1 ? activeClass : '']">
                                    <div class="started_icon">
                                        <router-link :to="{name: 'taxCreate'}" target="_blank" class="links">
                                        <svg>   
                                            <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#setup-tax-rates'" :href="baseUrl+'/admin/images/retina/sprite.svg#setup-tax-rates'"></use>                               
                                        </svg>
                                        </router-link>
                                    </div>
                                    <div class="started_data">
                                        <h5><router-link :to="{name: 'taxCreate'}" target="_blank" class="links">{{ $t('LBL_SETUP_TAX')}}</router-link></h5>
                                        <p>{{ $t('LBL_SETUP_TAX_CAPTION')}}.</p>
                                    </div>
                                    <div class="started_action">
                                        <img v-if="statuses.tax == 1" :src="baseUrl + '/admin/images/retina/tick-green.svg'" alt="">
                                        <img v-if="statuses.tax == 0" :src="baseUrl + '/admin/images/retina/tick-unfill.svg'" alt="">
                                    </div>
                                </li>
                                <li class="list-group-item" v-bind:class="[ statuses.brand ==  1 ? activeClass : '']">
                                    <div class="started_icon">
                                        <router-link :to="{name: 'brands'}" target="_blank" class="links">
                                         <svg>   
                                            <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#setup-brand'" :href="baseUrl+'/admin/images/retina/sprite.svg#setup-brand'"></use>                               
                                        </svg>
                                        </router-link>
                                    </div>
                                    <div class="started_data">
                                        <h5><router-link :to="{name: 'brands'}" target="_blank" class="links">{{ $t('LBL_ADD_BRANDS')}}</router-link></h5>
                                        <p>{{ $t('LBL_ADD_BRANDS_CAPTION')}}</p>
                                    </div>
                                    <div class="started_action">
                                        <img v-if="statuses.brand == 1" :src="baseUrl + '/admin/images/retina/tick-green.svg'" alt="">
                                        <img v-if="statuses.brand == 0" :src="baseUrl + '/admin/images/retina/tick-unfill.svg'" alt="">
                                    </div>
                                </li>
                                <li class="list-group-item" v-bind:class="[ statuses.category ==  1 ? activeClass : '']">
                                    <div class="started_icon">
                                        <router-link :to="{name: 'productCategory'}" target="_blank" class="links">
                                         <svg>   
                                            <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#setup-categoriess'" :href="baseUrl+'/admin/images/retina/sprite.svg#setup-categoriess'"></use>                               
                                        </svg>
                                        </router-link>
                                    </div>
                                    <div class="started_data">
                                        <h5><router-link :to="{name: 'productCategory'}" target="_blank" class="links">{{ $t('LBL_ADD_CATEGORIES')}}</router-link></h5>
                                        <p>{{ $t('LBL_ADD_CATEGORIES_CAPTION')}}</p>
                                    </div>
                                    <div class="started_action">
                                        <img v-if="statuses.category == 1" :src="baseUrl + '/admin/images/retina/tick-green.svg'" alt="">
                                        <img v-if="statuses.category == 0" :src="baseUrl + '/admin/images/retina/tick-unfill.svg'" alt="">
                                    </div>
                                </li>
                                <li class="list-group-item" v-bind:class="[ statuses.product ==  1 ? activeClass : '']">
                                    <div class="started_icon">
                                        <router-link :to="{name: 'productCreate'}" target="_blank" class="links">
                                        <svg>   
                                            <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#setup-products'" :href="baseUrl+'/admin/images/retina/sprite.svg#setup-products'"></use>                               
                                        </svg>
                                        </router-link>
                                    </div>
                                    <div class="started_data">
                                        <h5><router-link :to="{name: 'productCreate'}" target="_blank" class="links">{{ $t('LBL_ADD_PRODUCTS')}}</router-link></h5>
                                        <p>{{ $t('LBL_ADD_PRODUCTS_CAPTION')}}.</p>
                                    </div>
                                    <div class="started_action">
                                        <img v-if="statuses.product == 1" :src="baseUrl + '/admin/images/retina/tick-green.svg'" alt="">
                                        <img v-if="statuses.product == 0" :src="baseUrl + '/admin/images/retina/tick-unfill.svg'" alt="">
                                    </div>
                                </li>
                                <li class="list-group-item" v-bind:class="[ statuses.contentpages ==  1 ? activeClass : '']">
                                    <div class="started_icon">
                                        <router-link :to="{name: 'pages'}" target="_blank" class="links">
                                        <svg>   
                                            <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#setup-content-pages'" :href="baseUrl+'/admin/images/retina/sprite.svg#setup-content-pages'"></use>                               
                                        </svg>
                                        </router-link>
                                    </div>
                                    <div class="started_data">
                                        <h5><router-link :to="{name: 'pages'}" target="_blank" class="links">{{ $t('LBL_CONFIGURE_CONTENT_PAGES')}}</router-link></h5>
                                        <p>{{ $t('LBL_CONFIGURE_CONTENT_PAGES_CAPTION')}}?</p>
                                    </div>
                                    <div class="started_action">
                                        <img v-if="statuses.contentpages == 1" :src="baseUrl + '/admin/images/retina/tick-green.svg'" alt="">
                                        <img v-if="statuses.contentpages == 0" :src="baseUrl + '/admin/images/retina/tick-unfill.svg'" alt="">
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <b-modal id="skipModal" centered title="BootstrapVue">
                <template v-slot:modal-header>
                        <h5 class="modal-title" id="exampleModalLabel">{{ $t('LBL_SKIP_THIS_GUIDE')}}</h5>
                        <button type="button" class="close" @click="$bvModal.hide('skipModal')"></button>
                </template>
                <p>{{$t('LBL_SKIP_GET_STARTED_CONFIRMATION')}}.</p>
                <template v-slot:modal-footer >
                    <div class="d-flex justify-content-between w-100">
                        <button type="button" class="btn btn-secondary" @click="skipConfirm(0)">{{ $t('LBL_SKIP_FOR_THIS_SESSION')}}</button>
                        <button type="button" class="btn btn-brand" @click="skipConfirm(1)">{{ $t('LBL_SKIP_FOREVER')}}</button>
                    </div>
                </template>
            </b-modal>
        </div>
    </div>    
</template>
<script>

export default {
    data: function() {
        return {
            baseUrl: baseUrl,
            adminBaseUrl: adminBaseUrl,
            statuses: '',
            progress: 0,
            activeClass : 'completed'
        }
    },
    methods: {
        getSystemStatus: function() {            
            this.$http.get(adminBaseUrl + '/system/status').then((response) => {
                this.statuses = response.data.data.statuses;
                this.progress = response.data.data.statuses.progress;
            });
        },
        skip: function() { 
            this.$bvModal.show('skipModal');  
        },
        skipConfirm: function(type) { 
            this.$bvModal.hide('skipModal');   
            if (type == 1) {
                this.$http.get(adminBaseUrl + '/getstarted/skip').then((response) => {
                    toastr.success(response.data.message, '', toastr.options);
                });
            }        
            this.$router.push({ name: 'dashboard'});
        }
    },
    mounted: function() {
        this.getSystemStatus();
    }
};
</script>