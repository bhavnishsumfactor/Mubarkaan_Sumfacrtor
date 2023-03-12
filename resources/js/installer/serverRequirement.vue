<template>
<div class="card">
    <div class="card_body">
        <div class="steps-data" > 
            <div class="data active" id="stepData1">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <h6>{{ $t('Framework & extensions') }}</h6>
                        <ul class="list-permission">
                            <li class="list-permission-item list__title">
                                <span class="lt">
                                    <strong>{{ $t('Php') }}</strong>
                                        <span class="ml-1" v-b-tooltip.hover title="version 7.3 required"><i class="fa fa-info-circle"></i>
                                        </span>                                     
                                </span>
                                <span class="rt">
                                    <strong>
                                        7.3.4
                                    </strong>                                  
                                    <permission :checkPermission="requirements.php"></permission>                                    
                                </span>
                            </li>

                            <li class="list-permission-item list__title">
                                <span class="lt">
                                    <strong>{{ $t('Memory Limit') }}<small> {{ $t('') }} </small></strong>
                                </span>
                                <span class="rt">
                                    <strong>
                                        128M
                                    </strong>
                                    <permission :checkPermission="requirements.memory_limit"></permission>
                                </span>
                            </li>
                            
                            <li class="list-permission-item">
                                <span class="lt"> {{ $t('File Upload') }}</span>
                                <permission :checkPermission="requirements.file_uploads"></permission>
                            </li>

                            <li class="list-permission-item">
                                <span class="lt"> {{ $t('Allow Url fopen') }}</span>
                                <permission :checkPermission="requirements.allow_url_fopen"></permission>
                            </li>

                            <li class="list-permission-item">
                                <span class="lt"> {{ $t('MySQLi') }}</span>
                                <permission :checkPermission="requirements.mysqli"></permission>
                            </li>

                            <li class="list-permission-item">
                                <span class="lt"> {{ $t('cURL') }}</span>
                                <permission :checkPermission="requirements.curl"></permission>
                            </li>
                            <li class="list-permission-item">
                                <span class="lt"> {{ $t('Ctype') }} </span>
                                <permission :checkPermission="requirements.ctype"></permission>
                            </li>
                            <li class="list-permission-item">
                                <span class="lt"> {{ $t('Fileinfo') }} </span>
                                <permission :checkPermission="requirements.fileinfo"></permission>
                            </li>
                            <li class="list-permission-item">
                                <span class="lt"> {{ $t('JSON') }} </span>
                                <permission :checkPermission="requirements.json"></permission>
                            </li>
                            <li class="list-permission-item">
                                <span class="lt"> {{ $t('Mbstring') }} </span>
                                <permission :checkPermission="requirements.mbstring"></permission>
                            </li>
                            <li class="list-permission-item">
                                <span class="lt"> {{ $t('OpenSSL') }} </span>
                                <permission :checkPermission="requirements.openssl"></permission>
                            </li>
                            <li class="list-permission-item">
                                <span class="lt"> {{ $t('Tokenizer') }}</span>
                                <permission :checkPermission="requirements.tokenizer"></permission>
                            </li>
                            <li class="list-permission-item">
                                <span class="lt">{{ $t('XML') }}</span>
                                <permission :checkPermission="requirements.xml"></permission>
                            </li>
                        </ul>
                        <h6>{{ $t('Apache Configration') }}</h6>
                        <ul class="list-permission">
                            <li class="list-permission-item">
                                <span class="lt"> {{ $t('mod_rewrite') }}</span>
                                <permission :checkPermission="requirements.mod_rewrite"></permission>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card_foot">
        <button class="btn btn-outline-secondary btn-wide" @click="previous()">{{ $t('Back') }}</button>
        <button class="btn btn-brand btn-wide" @click="next()" :disabled="checkRequirements">{{ $t('Next') }}</button>
    </div>
</div>

</template>
<script>
    import permission from './permission';
    export default {
        components: {
            permission,
        },
        props: ["installerData"],
        data: function() {
            return { 
                baseUrl: baseUrl,
                requirements: []
            }
        },
        methods: {
            next: function() {
                this.$emit('next', 4, this.installerData);
            },
            previous: function() {
                this.$emit('previous', 4, this.installerData);
            },
            checkingRequirements:function() {
                this.$http.get(baseUrl + "/installer/check-requirements")
                    .then(response => {                
                        this.requirements = response.data.data;
                });
            }
        },
        mounted: function() {
            
            this.checkingRequirements();
            if (localStorage.getItem("lang") !== null) {
                localStorage.removeItem("lang");
            }
        },
        computed: {
            checkRequirements: function () {
                return (!this.requirements.php || !this.requirements.mysqli || !this.requirements.curl || !this.requirements.ctype || !this.requirements.fileinfo || !this.requirements.json || !this.requirements.mbstring || !this.requirements.openssl || !this.requirements.tokenizer || !this.requirements.xml || !this.requirements.file_uploads || !this.requirements.allow_url_fopen || !this.requirements.memory_limit || !this.requirements.mod_rewrite)
            }
        }
    }
</script>