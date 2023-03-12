<template>
<div class="card">
    <div class="card_body">
        <div class="steps-data" id="stepData"> 
            <div class="data active welcome-screen" data-step="0" >                
                    <div class="welcome-logo"> 
                        <img :src="baseUrl+'/installer/images/logo.png'" />
                    </div>
                    <h1>{{ $t('Accelerate Your Shift To') }}  {{ $t('eCommerce') }}</h1>                
            </div>
        </div>
    </div>
    <div class="card_foot text-center">
        <button class="btn btn-brand btn-wide mx-auto" @click="next()">{{ $t('Install') }}</button>
    </div>
</div>
</template>
<script>
    import NProgress from 'nprogress';
    import 'nprogress/nprogress.css';
    export default {     
        props: ["installerData"],
        data: function() {
            return { 
                baseUrl: baseUrl                
            }
        },
        methods: {
            next: function() {
                this.$emit('next', 1, this.installerData);
            }
        }
    }
</script>