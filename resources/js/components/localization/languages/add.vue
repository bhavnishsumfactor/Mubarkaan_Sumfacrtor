<template>
    <b-modal id="languagePopup" centered title="BootstrapVue">
        <template v-slot:modal-header>
            <h5 class="modal-title" id="exampleModalLabel">
                <span >{{$t('LBL_ADD_LANGUAGE') }}</span>
            </h5>
            <button type="button" class="close" @click="$bvModal.hide('languagePopup')"></button>
        </template>                
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
					<div class="col-auto">
						<vue-bootstrap-typeahead v-model="adminsData.lang_name" :data="languagesArray" @hit="onSelectRecord" ref="languageTypeahead" :placeholder="$t('LBL_START_TYPING_LANGUAGE_NAME')" :max-matches=5
							:min-matching-chars=1 />
					</div>
                </div>
            </div>
        </div>
        <template v-slot:modal-footer>
            <button type="submit" class="btn btn-brand gb-btn gb-btn-primary" @click="addLanguage()" :disabled='!isComplete || clicked==1' v-bind:class="clicked==1 && 'gb-is-loading'"><span>{{$t('BTN_LANGUAGE_CREATE') }}</span></button>
        </template>
    </b-modal>
    <!--end::Modal-->
</template>
<script>
import VueBootstrapTypeahead from 'vue-bootstrap-typeahead';
export default {
	components: { 'vue-bootstrap-typeahead' : VueBootstrapTypeahead },
    props: ['languages','languagesArray'],
    data: function () {
        return {
            clicked: 0,
            adminsData: {'lang_name':''}
        }
    },
    methods: {
        addLanguage: function() {
            let input = this.adminsData;
            if (typeof input.lang_code == "undefined" || input.lang_code == "") {
                toastr.error(this.$t('NOTI_PLEASE_SELECT_VALID_LANGUAGE'), '', toastr.options);
                return;
            }
            this.clicked = 1;
            let formdata = this.$serializeData(input);
            this.$http.post(adminBaseUrl + '/languages/save', formdata)
                .then((response) => {
                    if (response.data.status == false) {
                        this.clicked = 0;
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    toastr.success(response.data.message, '', toastr.options);
                    this.$refs.languageTypeahead.inputValue = '';
                    this.clicked = 0;
                    setTimeout(() => {
					    window.location.reload();
				    }, 500);
                }, (response) => {
                    this.clicked = 0;
                    this.validateErrors(response);
                });
        },
        onSelectRecord: function(languageName) {
            Object.keys(this.languages).forEach(key => {
                if (this.languages[key].name == languageName) {
                    this.adminsData.lang_name = this.languages[key].name;
                    this.adminsData.lang_code = key;
                    if (typeof this.languages[key].direction != 'undefined') {
                        this.adminsData.lang_direction = this.languages[key].direction;
                    }
                }
            });
        },
        validateErrors: function (response) {
            let jsondata = response.data;
            Object.keys(jsondata.errors).forEach(key => {
                this.errors.add({field: key,msg: jsondata.errors[key][0]  });
            });
        },
    },
    computed: {
        isComplete () {
            return this.adminsData.lang_name;
        }
    },
}
</script>
