<template>
    <div>
        <div class="subheader grid__item" id="subheader">
            <div class="container ">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{$t('LBL_BEHAVIOUR_REPORT') }} </h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">
                            {{ $t('LBL_REPORTS')}}
                        </span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{ $t('LBL_BEHAVIOUR_REPORT') }}</span>
                    </div>
                </div>
                <div class="subheader__toolbar">
                    <div class="subheader__wrapper">
                        <date-picker class="custom-date-range" v-model="searchData.dateRange" :disabled-date="notAfterToday" type="date" range value-type="format" format="YYYY-MM-DD" placeholder="Select date" @change="searchRecords">
                        </date-picker>
                        <export-excel :data="exportData" :fields="exportFields" :name="currentActionTab">
                            <button type="button" class="btn btn-subheader YK-exportBtn" :disabled="isDisabled || (!$canWrite('BEHAVIOUR_REPORT'))">{{ $t('BTN_EXPORT') }}</button>
                        </export-excel>
                        <button type="button" @click="printReport" class="btn btn-subheader YK-printBtn" :disabled="isDisabled || (!$canWrite('BEHAVIOUR_REPORT'))">{{ $t('BTN_PRINT') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="portlet portlet--tabs">
                        <div class="portlet__head">
                            <div class="portlet__head-toolbar">
                                <ul role="tablist" class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold">
                                    <li class="nav-item"><a data-toggle="tab" href="#portlet_tab_content_1" @click="switchTab('searchWithNoRecords')" role="tab" aria-selected="false" class="nav-link active">{{ $t('LBL_SEARCH_WITH_NO_RECORDS') }}</a></li>

                                    <li class="nav-item"><a data-toggle="tab" href="#portlet_tab_content_2" @click="switchTab('searchWithRecords')" role="tab" aria-selected="true" class="nav-link">{{ $t('LBL_SEARCH_WITH_RECORDS') }} </a></li>

                                    <li class="nav-item"><a data-toggle="tab" href="#portlet_tab_content_3" @click="switchTab('sessionByDevice')" role="tab" aria-selected="false" class="nav-link"> {{ $t('LBL_SESSION_BY_DEVICE') }}</a></li>

                                    <li class="nav-item"><a data-toggle="tab" href="#portlet_tab_content_4" @click="switchTab('sessionByLandingPage')" role="tab" aria-selected="false"  class="nav-link">{{ $t('LBL_SESSION_BY_LANDING_PAGE') }}</a></li>

                                </ul>
                            </div>
                        </div>
                        <div class="portlet__body portlet__body--fit portlet__body--fit">
                            <div class="tab-content">
                                <div id="portlet_tab_content_1" role="tabpanel" class="tab-pane active">
                                    <onlineSearchRecords :dateRange="searchData.dateRange" type="topOnlineStoreWithNoResult" v-if="currentActionTab == 'searchWithNoRecords'" v-on:exportExcelFields="setExportFields" v-on:exportExcelData="setExportData" v-on:setDate="setDate" v-on:installationDate="setInstallationDate" ref="searchWithNoRecords"/>
                                </div>
                                <div id="portlet_tab_content_2" role="tabpanel" class="tab-pane">
                                    <onlineSearchRecords :dateRange="searchData.dateRange" type="topOnlineStoreWithResult" v-if="currentActionTab == 'searchWithRecords'" v-on:exportExcelFields="setExportFields" v-on:exportExcelData="setExportData" v-on:setDate="setDate" v-on:installationDate="setInstallationDate" ref="searchWithRecords"/>
                                </div>
                                <div id="portlet_tab_content_3" role="tabpanel" class="tab-pane">
                                    <sessionByDevice  :dateRange="searchData.dateRange" v-if="currentActionTab == 'sessionByDevice'" v-on:installationDate="setInstallationDate" v-on:exportExcelFields="setExportFields" v-on:exportExcelData="setExportData" v-on:setDate="setDate" ref="sessionByDevice"/>
                                </div>
                                <div id="portlet_tab_content_4" role="tabpanel" class="tab-pane">
                                    <sessionByLandingPage :dateRange="searchData.dateRange" v-if="currentActionTab == 'sessionByLandingPage'" v-on:installationDate="setInstallationDate" v-on:exportExcelFields="setExportFields" v-on:exportExcelData="setExportData" v-on:setDate="setDate" ref="sessionByLandingPage"/>
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
import onlineSearchRecords from './onlineSearchRecords';
import sessionByLandingPage from './sessionByLandingPage';
import sessionByDevice from './sessionByDevice';
export default {
    components: {
        DatePicker,
        onlineSearchRecords,
        sessionByLandingPage,
        sessionByDevice
    },
    data: function() {
        return {
            exportFields: {},
            exportData: [],
            searchData: searchFields,
            isDisabled:true,
            currentActionTab: 'searchWithNoRecords',
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
                case "searchWithNoRecords":
                    this.$refs.searchWithNoRecords.searchRecords(this.searchData.dateRange);
                break;
                case "searchWithRecords":
                    this.$refs.searchWithRecords.searchRecords(this.searchData.dateRange);
                break;
                case "sessionByDevice":
                    this.$refs.sessionByDevice.searchRecords(this.searchData.dateRange);
                break;
                case "sessionByLandingPage":
                    this.$refs.sessionByLandingPage.searchRecords(this.searchData.dateRange);
                break;
            }
        },
        printReport() {
            switch(this.currentActionTab)
            {
                case "searchWithNoRecords":
                    this.$refs.searchWithNoRecords.printSearchRecords();
                break;
                case "searchWithRecords":
                    this.$refs.searchWithRecords.printSearchRecords();
                break;
                case "sessionByDevice":
                    this.$refs.sessionByDevice.printSessionByDevice();
                break;
                case "sessionByLandingPage":
                    this.$refs.sessionByLandingPage.printSessionByLandingPage();
                break;
            }
        },
        switchTab: function(value) {
            this.currentActionTab = value;
        }  
    }
}
</script>