<template>
<div class="card">
    <div class="card_body">
        <div class="steps-data">
            <div class="data active" id="stepData8" data-step="6">
                <div class="row justify-content-center">
                    <div class="col-md-10"> 
                        <div class="final-review">
                            <!--begin::Section-->
                            <h6 class="">{{ $t('App Information:') }}</h6>
                            <ul class="review-list">
                                <li>{{'App Name : '}} <span class="value"> {{ installerData.app_name ? installerData.app_name : '' }}</span></li>
                                <li>{{'App Url : '}} <span class="value">{{ installerData.app_url  ? installerData.app_url : ''}}</span></li>
                                <li>{{ 'Install Dummy Data : '}} <span class="value">{{ installerData.install_dummy ? 'Yes' : 'No' }}</span></li>
                                <li>{{ 'Database Name : '}} <span class="value">{{ installerData.db_name  ? installerData.db_name : '' }}</span></li>
                                <li>{{ 'Database Host : '}} <span class="value">{{ installerData.db_host ? installerData.db_host : '' }}</span></li>
                                <li>{{ 'Database Port : '}} <span class="value">{{ installerData.db_port  ? installerData.db_port : '' }}</span></li>
                                <li>{{ 'Database Username : '}} <span class="value">{{ installerData.db_user ? installerData.db_user : '' }}</span></li>                                
                            </ul>
                            
                            <!--end::Section-->
                            <!--begin::Section-->
                            <h6 class="">{{ $t('Business Information:') }} </h6>
                            <ul class="review-list">
                                <li>{{'Name : '}} <span class="value">{{ installerData.config.BUSINESS_NAME ? installerData.config.BUSINESS_NAME : ''  }}</span></li>
                                <li>{{'Email : '}} <span class="value">{{ installerData.config.BUSINESS_EMAIL ? installerData.config.BUSINESS_EMAIL : '' }}</span></li>
                                <li>{{'Address : '}} <span class="value">{{ installerData.config.BUSINESS_ADDRESS1  ? installerData.config.BUSINESS_ADDRESS1 : '' }}</span></li>
                                <li>{{'Address2 : '}} <span class="value">{{ installerData.config.BUSINESS_ADDRESS2 ? installerData.config.BUSINESS_ADDRESS2 : '' }}</span></li>
                                <li>{{'Country : '}} <span class="value">{{ installerData.config.BUSINESS_COUNTRY ? installerData.country_name : '' }}</span></li>
                                <li>{{'State : '}} <span class="value">{{ installerData.config.BUSINESS_STATE ? installerData.state_name : '' }}</span></li>
                                <li>{{'City : '}} <span class="value">{{ installerData.config.BUSINESS_CITY ? installerData.config.BUSINESS_CITY : '' }}</span></li>
                                <li>{{'Phone : '}} <span class="value">{{ installerData.config.BUSINESS_PHONE_NUMBER ? installerData.config.BUSINESS_PHONE_NUMBER : '' }}</span></li>
                                <li>{{'Pincode : '}} <span class="value">{{ installerData.config.BUSINESS_PINCODE ? installerData.config.BUSINESS_PINCODE : '' }}</span></li>
                            </ul>
                            
                            <!--end::Section-->
                            <!--begin::Section-->
                            <h6 class="">{{ $t('Admin Information:') }}</h6>
                            <ul class="review-list">
                                <li>{{'Name : '}}<span class="value"> {{ installerData.admin_name ? installerData.admin_name : '' }}</span></li>
                                <li>{{'Email : '}} <span class="value">{{ installerData.admin_email ? installerData.admin_email : '' }}</span></li>                                
                                <li>{{'Default Language : '}} <span class="value">{{ installerData.admin_language ? installerData.admin_language : '' }}</span></li>
                                <li>{{'Default Currency : '}} <span class="value">{{ installerData.admin_currency ? installerData.admin_currency : '' }}</span></li>
                            </ul>
                            <!--end::Section-->                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card_foot">
        <button class="btn btn-outline-secondary btn-wide" @click="previous()">{{ $t('Back') }}</button>
        <button class="btn btn-brand btn-wide" @click="next()">{{ $t('Next') }}</button>
    </div>
</div>
</template>
<script>
    export default {
        props: ["installerData"],
        data: function() {
            return {
                baseUrl: baseUrl,
                height: ''
            }
        },
        methods: {
            next: function() {                
                this.$emit('next', 8, this.installerData);
            },
            previous: function() {
                this.$emit('previous', 8, this.installerData);
            }
        }
    }
</script>