<template>
<div>
    <div class="subheader" id="subheader">
        <div class="container">
            <div class="subheader__main">
                <h3 class="subheader__title">{{ $t('LBL_MISCELLANEOUS') }}</h3>
                <div class="subheader__breadcrumbs">
                    <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                        <i class="flaticon2-shelter"></i>
                    </router-link>
                    <span class="subheader__breadcrumbs-separator"></span>
                    <span class="subheader__breadcrumbs-link">{{$t('LBL_SEO')}}</span>
                    <span class="subheader__breadcrumbs-separator"></span>
                    <span class="subheader__breadcrumbs-link">{{$t('LBL_MISCELLANEOUS')}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="portlet">
                    <form class="form form--label-right">
                        <div class="portlet__body" v-bind:class="[(!$canWrite('MISCELLANEOUS')) ? 'disabledbutton': '']">
                            <div class="section">
                                <div class="section__body">
                                    <h3 class="section__title section__title-sm">{{ $t('LBL_ROBOT_TXT') }}</h3>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <div class="radio-inline">
                                                <label class="radio">
                                                    <input
                                                    type="radio"
                                                    @click="selectRobotTxtType('1')"
                                                    checked="checked"
                                                    :value="1"
                                                    v-model="adminsData.ROBOT_TXT_TYPE"
                                                    name="ROBOT_TXT_TYPE"
                                                    />
                                                    {{$t('LBL_ALLOW_ALL')}}
                                                    <span></span>
                                                </label>
                                                <label class="radio">
                                                    <input
                                                    type="radio"
                                                    @click="selectRobotTxtType('2')"
                                                    name="ROBOT_TXT_TYPE"
                                                    :value="2"
                                                    v-model="adminsData.ROBOT_TXT_TYPE"
                                                    />
                                                    {{$t('LBL_DISALLOW_ALL')}}
                                                    <span></span>
                                                </label>
                                                <label class="radio">
                                                    <input
                                                    type="radio"
                                                    @click="selectRobotTxtType('3')"
                                                    name="ROBOT_TXT_TYPE"
                                                    :value="3"
                                                    v-model="adminsData.ROBOT_TXT_TYPE"
                                                    />
                                                    {{$t('LBL_CUSTOM')}}
                                                    <span></span>
                                                </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">                                          
                                            <div class="col-lg-12">
                                                <textarea class="form-control" :disabled="adminsData.ROBOT_TXT_TYPE != '3' ? true : false" v-model="adminsData.ROBOT_TXT" name="ROBOT" rows="5"></textarea>
                                            </div>
                                        </div>
                                        <h3 class="section__title section__title-sm">{{ $t('LBL_SCHEMA_CODE') }}</h3>
                                            <div class="form-group row">
                                                <div class="col-lg-12">
                                                    <textarea class="form-control" rows="10" v-model="adminsData.SCHEMA_CODES_DEFAULT_SCRIPT"  name="SCHEMA_CODES_DEFAULT_SCRIPT"></textarea>
                                                    <span v-if="errors.first('SCHEMA_CODES_DEFAULT_SCRIPT')" class="error text-danger">{{ errors.first('SCHEMA_CODES_DEFAULT_SCRIPT') }}</span>
                                                </div>
                                            </div>
                                            <h3 class="section__title section__title-sm">{{ $t('LBL_SITEMAP') }}</h3>
                                            <div class="bg-gray p-4 rounded text-center">
                                                <button type="button" @click="updateSiteMap" class="btn btn-sm btn-secondary " :disabled="siteMapclicked==1 || (!$canWrite('MISCELLANEOUS'))" v-bind:class="siteMapclicked==1 && 'gb-is-loading'">{{ $t('BTN_GENERATE_SITEMAP') }}</button>
                                                <button type="button" @click="viewXmlSiteMap" class="btn btn-sm btn-outline-secondary gb-btn gb-btn-primary" :disabled="xmlSiteMapclicked==1 || (!$canWrite('MISCELLANEOUS'))" v-bind:class="xmlSiteMapclicked==1 && 'gb-is-loading'">
                                                    {{ $t('BTN_VIEW_XML_SITEMAP') }}
                                                </button>
                                                <button type="button" @click="viewHtmlSitemap" class="btn btn-sm btn-outline-secondary gb-btn gb-btn-primary" :disabled="htmlSiteMapclicked==1 || (!$canWrite('MISCELLANEOUS'))" v-bind:class="htmlSiteMapclicked==1 && 'gb-is-loading'">
                                                    {{ $t('BTN_VIEW_HTML_SITEMAP') }}
                                                </button>
                                            </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="portlet__foot  text-center">                            
                        <div class="form__actions">
                            <button type="button" class="btn btn-brand btn-wide gb-btn gb-btn-primary" @click="updateSettings" :disabled="clicked==1 || (!$canWrite('MISCELLANEOUS'))" v-bind:class="clicked==1 && 'gb-is-loading'">{{ $t('BTN_MISCELLANEOUS_UPDATE') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>

const tableFields = {
    'ROBOT_TXT': '',
    'ROBOT_TXT_TYPE': '',
    'SCHEMA_CODES_DEFAULT_SCRIPT': ''
};
export default {
    data: function() {
        return {
            adminsData: tableFields,
            clicked: 0,
            siteMapclicked:0,
            xmlSiteMapclicked:0,
            htmlSiteMapclicked:0
        }
    },
    methods: {
        getSettings: function() {
            let formData = this.$serializeData({'keys':['ROBOT_TXT', 'ROBOT_TXT_TYPE', 'ROBOT_TXT_ALLOW_TEXT', 'ROBOT_TXT_DISALLOW_TEXT','SCHEMA_CODES_DEFAULT_SCRIPT']});
            this.$http.post(adminBaseUrl + '/settings/get', formData).then((response) => {
                if (response.data.status == false) {
                    toastr.error(response.data.message, '', toastr.options);
                    return;
                }
                this.adminsData = response.data.data;
            });
        },
        selectRobotTxtType: function(type) {
           if(type == '1'){
                this.adminsData.ROBOT_TXT = this.adminsData.ROBOT_TXT_ALLOW_TEXT;
            } else if(type == '2'){
                this.adminsData.ROBOT_TXT = this.adminsData.ROBOT_TXT_DISALLOW_TEXT;
            } else {
                this.adminsData.ROBOT_TXT = "";
            }
        },
        updateSettings: function() {
            if(!this.$canWrite('MISCELLANEOUS')) {
                toastr.error('Unauthorized request', "", toastr.options);
                return;
            }
            this.$validator.validateAll().then(res => {
                if (res) {
                    this.clicked = 1;
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
        validateErrors: function(response) {
            let jsondata = response.data;
            Object.keys(jsondata.errors).forEach(key => {
                this.errors.add({
                    field: key,
                    msg: jsondata.errors[key][0]
                });
            });
        },
        updateSiteMap: function() {
            this.siteMapclicked = 1;
            this.$http.get(adminBaseUrl + '/sitemaps/generate')
                .then((response) => {
                    this.siteMapclicked = 0;
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    toastr.success(response.data.message, '', toastr.options);
                });
        },
        viewXmlSiteMap: function() {
            this.xmlSiteMapclicked = 1;
            this.$http.get(adminBaseUrl + '/sitemaps/view')
                .then((response) => {
                    this.xmlSiteMapclicked = 0;
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    } else {
                        window.open(baseUrl + '/storage/sitemap.xml', "_blank");
                    }
                });
        },
        viewHtmlSitemap: function() {
            this.htmlSiteMapclicked = 1;
            this.$http.get(adminBaseUrl + '/sitemaps/view')
                .then((response) => {
                    this.htmlSiteMapclicked = 0;
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    } else {
                        window.open(baseUrl + '/site-map', "_blank");
                    }
                });
            
        }
    },
    mounted: function() {
        this.getSettings();
    }
}
</script>