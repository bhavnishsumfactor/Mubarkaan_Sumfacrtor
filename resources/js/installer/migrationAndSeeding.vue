<template>
    <div class="card">
        <div class="card_body">
            <div class="steps-data">
                <div class="data active progress-center">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                        <h1 v-if="increasing_pct < 50">{{$t('please wait for few mintues!!') }}</h1>
                        <h1 v-if="increasing_pct > 50  && increasing_pct < 100">{{$t('You are almost there!!') }}</h1>
                        <h1 v-if="increasing_pct == 100">{{ $t('Setup Complete!!') }}</h1>
                        <progress-bar size="medium" bar-color="#5cb85c" :val="increasing_pct" :text="increasing_pct + '%'" :text-position="'inside'" :text-fg-color="'#fff'" :bar-border-radius="20"></progress-bar>
                            <p class="redirecting mt-6" v-if="increasing_pct == 100"> {{ $t('redirecting in') }} {{ redirectCounter }} {{ $t('second(s)') }}</p>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card_foot justify-content-center">   
            <button class="btn btn-brand btn-wide" @click="adminLogin()" :disabled="increasing_pct != 100 || success == false"> {{ $t('Go To Admin') }}</button>
        </div>
    </div>
</template>
<script>
import ProgressBar from 'vue-simple-progress'
export default {
    props: ["installerData"],
    components: {
        ProgressBar
    },
    data: function() {
        return {
            baseUrl: baseUrl,
            height: '',
            increasing_pct: 0,
            redirectCounter:10,
            success:false,
            interval:'',
        }
    },
    methods: {
        previous: function() {
            this.$emit('previous', 9, this.installerData);
        },
        migration:function() {
            this.success = false;
            this.increasing_pct = 0;
            this.updateBar();
            let formData = this.$serializeData(this.installerData);
            formData.append("config", JSON.stringify(this.installerData.config));
            formData.append("actualImage", this.installerData.uploadedFile);
            formData.append("cropImage", this.installerData.cropImage);
            this.$axios
                .post(baseUrl + "/installer/migration-seeding-data", formData)
                .then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, "", toastr.options);
                        return;
                    }
                    this.success = true;
                    this.increasing_pct = 100;
                    this.redirectInit();
                });
        },
        adminLogin: function() {
            window.location = baseUrl+ '/admin/login'; 
        },
        redirectInit: function() {
            let thisObj = this;
            if (this.redirectCounter != 1) {
                this.redirectCounter = this.redirectCounter - 1;
                setTimeout(function(){
                    thisObj.redirectInit();
                }, 1000);
            } else {
                window.location = baseUrl+ '/admin/login';
            }
        },
        updateBar: function() {
            let thisObj = this;
            if (this.increasing_pct < 90) {
                this.increasing_pct = this.increasing_pct + Math.floor(Math.random() * ((90 - this.increasing_pct) - 1 + 1)) + 1;
                setTimeout(function(){
                    thisObj.updateBar();
                }, 2000);
            }
        }
    },
    mounted: function() {
        this.migration();
    }
}
</script>