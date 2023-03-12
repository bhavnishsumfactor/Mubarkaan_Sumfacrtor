
<template>
<div class="card">
    <div class="card_body">
        <div class="steps-data">
            <div class="data active welcome-screen" data-step="0">
                <div class="my-5">
                    <div class="form form-floating mt-10">
                        <p class="mb-2 clr-grey">{{ $t('Please select the installer language') }}</p>
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="form-group form-floating__group">
                                    <select class="form-control form-floating__field filled" name="" id="" v-model="languageCode">
                                        <option v-for="(record, index) in languages" :key="index" :value="record.lang_code">{{ record.lang_name }}</option>
                                    </select>
                                    <label class="form-floating__label">{{ $t('Select Language') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card_foot">
        <button class="btn btn-outline-secondary btn-wide" @click="previous()">{{ $t('Back') }}</button>
        <button class="btn btn-brand btn-wide" @click="next()" :disabled="languageCode == ''">{{ $t('Next') }}</button>
    </div>
</div>


</template>
<script>
export default {
    props: ["installerData"],
    data: function() {
        return { 
            baseUrl: baseUrl,
            languages:[],
            height:'',
            checked: '',
            languageCode:'en'
        }
    },
    methods: {
        pageRecords : function() {
            this.$http.get(baseUrl + "/installer-languages")
                .then(response => {
                    this.languages = response.data.data;
            });
        },
        switchLanguage: function(lang) {
            this.$http.get(baseUrl + "/installer/lang/" + lang).then(response => {
                localStorage.setItem("lang", lang);
                window.location.reload();
            });
        },
        next: function() {
            if (this.languageCode != "en") {
                this.switchLanguage(this.languageCode);
            } else {
                this.$emit('next', 3, this.installerData);
            }   
        },
        previous: function() {
            this.$emit('previous', 3, this.installerData);
        }
    },
    mounted: function() {
        this.pageRecords();
    }
    
}
</script>