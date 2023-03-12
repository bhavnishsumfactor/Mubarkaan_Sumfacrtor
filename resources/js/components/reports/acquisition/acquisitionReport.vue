<template>
    <div>
        <div class="subheader grid__item" id="subheader">
            <div class="container ">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{$t('LBL_ACQUISITION_REPORT') }} </h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">
                            {{ $t('LBL_REPORTS')}}
                        </span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{ $t('LBL_ACQUISITION_REPORT') }}</span>
                    </div>
                </div>
                <div class="subheader__toolbar" v-bind:class="[!checkServiceCredentialFile ? 'disabledbutton' : '']">
                    <div class="subheader__wrapper">
                        <date-picker class="custom-date-range" v-model="searchData.dateRange" :disabled-date="notAfterToday" type="date" range value-type="format" format="YYYY-MM-DD" placeholder="Select date" @change="searchRecords">
                        </date-picker>
                        <export-excel :data="exportData" :fields="exportFields" :name="currentActionTab">
                            <button type="button" class="btn btn-subheader YK-exportBtn" :disabled="isDisabled || (!$canWrite('CUSTOMER_REPORT'))">{{ $t('BTN_EXPORT') }}</button>
                        </export-excel>
                        <button type="button" @click="printReport" class="btn btn-subheader YK-printBtn" :disabled="isDisabled || (!$canWrite('CUSTOMER_REPORT'))">{{ $t('BTN_PRINT') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="portlet portlet--tabs">
                        <div class="portlet__head">
                            <div class="portlet__head-toolbar" v-bind:class="[!checkServiceCredentialFile ? 'disabledbutton' : '']">
                                <ul role="tablist" class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold">
                                    <li class="nav-item"><a data-toggle="tab" href="#portlet_tab_content_1" @click="switchTab('sessionOverTime')" role="tab" aria-selected="false" class="nav-link active">{{ $t('LNK_SESSION_OVER_TIME') }}</a></li>

                                    <li class="nav-item"><a data-toggle="tab" href="#portlet_tab_content_2" @click="switchTab('sessionByReferrer')" role="tab" aria-selected="true" class="nav-link">{{ $t('LNK_SESSION_BY_REFERRER') }} </a></li>

                                    <li class="nav-item"><a data-toggle="tab" href="#portlet_tab_content_3" @click="switchTab('sessionByLocation')" role="tab" aria-selected="false" class="nav-link"> {{ $t('LNK_SESSION_BY_LOCATION') }}</a></li>

                                </ul>
                            </div>
                        </div>
                        <div class="portlet__body portlet__body--fit">
                            <div class="tab-content">
                                <div id="portlet_tab_content_1" role="tabpanel" class="tab-pane active">
                                    <sessionOverTime :dateRange="searchData.dateRange" v-if="currentActionTab == 'sessionOverTime'" v-on:exportExcelFields="setExportFields" v-on:exportExcelData="setExportData" v-on:setDate="setDate" v-on:checkCredentialFile="checkCredentialFile"  ref="sessionOverTime"/>
                                </div>
                                <div id="portlet_tab_content_2" role="tabpanel" class="tab-pane">
                                    <sessionByReferrer :dateRange="searchData.dateRange" v-if="currentActionTab == 'sessionByReferrer'" v-on:exportExcelFields="setExportFields" v-on:exportExcelData="setExportData" v-on:setDate="setDate" ref="sessionByReferrer"/>
                                </div>
                                <div id="portlet_tab_content_3" role="tabpanel" class="tab-pane">
                                    <sessionByLocation :dateRange="searchData.dateRange" v-if="currentActionTab == 'sessionByLocation'" v-on:exportExcelFields="setExportFields" v-on:exportExcelData="setExportData" v-on:setDate="setDate" ref="sessionByLocation"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
const today = new Date();  
const searchFields = {
    'dateRange': [],
};
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import sessionOverTime from './sessionOverTime';
import sessionByReferrer from './sessionByReferrer';
import sessionByLocation from './sessionByLocation';
export default {
    components: {
        DatePicker,
        sessionOverTime,
        sessionByReferrer,
        sessionByLocation
    },
    data: function() {
        return {
            exportFields: {},
            exportData: [],
            searchData: searchFields,
            isDisabled:true,
            currentActionTab: 'sessionOverTime',
            checkServiceCredentialFile:false,
            installationDate:''
        }
    },
    methods: {
        notAfterToday(date) {
            return date > today;
        },
        setExportFields: function(fields) {
            this.exportFields = fields;
        },
        setExportData: function(data) {
            this.exportData = data;
            this.isDisabled = data.length > 0 ? false : true;
        },
        setDate: function(date) {
            this.searchData.dateRange = date;
        },
        searchRecords: function() {
            switch(this.currentActionTab)
            {
                case "sessionOverTime":
                    this.$refs.sessionOverTime.searchRecords(this.searchData.dateRange);
                break;
                case "sessionByReferrer":
                    this.$refs.sessionByReferrer.searchRecords(this.searchData.dateRange);
                break;
                case "sessionByLocation":
                    this.$refs.sessionByLocation.searchRecords(this.searchData.dateRange);
                break;
            }
        },
        setInstallationDate: function(date) {
            this.installationDate = date;
        },
        disabledDates: function(date) {
            if (this.installationDate != "") {
                let today = new Date(this.installationDate);
                return date < today || date > new Date();
            }
        },
        printReport() {
            switch(this.currentActionTab)
            {
                case "sessionOverTime":
                    this.$refs.sessionOverTime.printSessionOverTimeReport();
                break;
                case "sessionByReferrer":
                    this.$refs.sessionByReferrer.printSessionByReferrer();
                break;
                case "sessionByLocation":
                    this.$refs.sessionByLocation.printSessionByLocation();
                break;
            }
        },
        switchTab: function(value) {
            this.currentActionTab = value;
        },
        checkCredentialFile: function(fileExist) {
            this.checkServiceCredentialFile = fileExist;
        }
    }
}
</script>