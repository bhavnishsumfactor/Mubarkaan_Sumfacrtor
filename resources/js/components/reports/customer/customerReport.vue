<template>
    <div>
        <div class="subheader grid__item" id="subheader">
            <div class="container ">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{$t('LBL_CUSTOMER_REPORT') }} </h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">
                            {{ $t('LBL_REPORTS')}}
                        </span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{ $t('LBL_CUSTOMER_REPORT') }}</span>
                    </div>
                </div>
                <div class="subheader__toolbar">
                    <div class="subheader__wrapper">
                        <date-picker class="custom-date-range" v-model="searchData.dateRange" type="date" range value-type="format" format="YYYY-MM-DD" placeholder="Select date" @change="searchCustomerRecords" :disabled-date="disabledDates">
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
                <div class="col-lg-12">
                    <div class="portlet portlet--tabs">
                        <div class="portlet__head">
                            <div class="portlet__head-toolbar">
                                <ul role="tablist" class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold">
                                    <li class="nav-item"><a data-toggle="tab" href="#portlet_tab_content_1" @click="switchTab('customerOverTime')" role="tab" aria-selected="false" class="nav-link active">{{ $t('LNK_CUSTOMER_OVER_TIME') }}</a></li>

                                    <li class="nav-item"><a data-toggle="tab" href="#portlet_tab_content_2" @click="switchTab('firstTimeVsReturn')" role="tab" aria-selected="true" class="nav-link">{{ $t('LNK_FIRST_TIME_VS_RETURN_CUSTOMER') }} </a></li>

                                    <li class="nav-item"><a data-toggle="tab" href="#portlet_tab_content_3" @click="switchTab('locationCustomer')" role="tab" aria-selected="false" class="nav-link"> {{ $t('LNK_LOCATION_CUSTOMER') }}</a></li>

                                    <li class="nav-item"><a data-toggle="tab" href="#portlet_tab_content_4" @click="switchTab('oneTimeCustomer')" role="tab" aria-selected="false"  class="nav-link">{{ $t('LNK_ONE_TIME_CUSTOMER') }}</a></li>

                                    <li class="nav-item"><a data-toggle="tab" href="#portlet_tab_content_5" @click="switchTab('returningCustomer')" role="tab" aria-selected="false" class="nav-link">{{ $t('LNK_RETURNING_CUSTOMER') }}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="portlet__body portlet__body--fit">
                            <div class="tab-content">
                                <div id="portlet_tab_content_1" role="tabpanel" class="tab-pane active">
                                    <customerOverTime :dateRange="searchData.dateRange" v-if="currentActionTab == 'customerOverTime'" v-on:exportExcelFields="setExportFields" v-on:exportExcelData="setExportData" v-on:setDate="setDate" v-on:installationDate="setInstallationDate" ref="customerOverTime"/>
                                </div>
                                <div id="portlet_tab_content_2" role="tabpanel" class="tab-pane">
                                    <firstTimeVsReturnCustomer :dateRange="searchData.dateRange" v-if="currentActionTab == 'firstTimeVsReturn'" v-on:exportExcelFields="setExportFields" v-on:exportExcelData="setExportData" v-on:installationDate="setInstallationDate" v-on:setDate="setDate" ref="firstTimeVsReturn"/>
                                </div>
                                <div id="portlet_tab_content_3" role="tabpanel" class="tab-pane">
                                    <locationCustomer :dateRange="searchData.dateRange" v-if="currentActionTab == 'locationCustomer'" v-on:exportExcelFields="setExportFields" v-on:exportExcelData="setExportData" v-on:setDate="setDate" v-on:installationDate="setInstallationDate" ref="locationCustomer"/>
                                </div>
                                <div id="portlet_tab_content_4" role="tabpanel" class="tab-pane">
                                    <oneTimeCustomer :dateRange="searchData.dateRange" v-if="currentActionTab == 'oneTimeCustomer'" v-on:exportExcelFields="setExportFields" v-on:exportExcelData="setExportData" v-on:setDate="setDate" v-on:installationDate="setInstallationDate" ref="oneTimeCustomer"/>
                                </div>
                                <div id="portlet_tab_content_5" role="tabpanel" class="tab-pane">
                                    <returningCustomer :dateRange="searchData.dateRange" v-if="currentActionTab == 'returningCustomer'" v-on:exportExcelFields="setExportFields" v-on:exportExcelData="setExportData" v-on:setDate="setDate" v-on:installationDate="setInstallationDate" ref="returningCustomer"/>
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
const searchFields = {
    'dateRange': [],
};
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import customerOverTime from './customerOverTime';
import firstTimeVsReturnCustomer from './firstTimeVsReturnCustomer';
import locationCustomer from './locationCustomer';
import oneTimeCustomer from './oneTimeCustomer';
import returningCustomer from './returningCustomer';
export default {
    components: {
        DatePicker,
        customerOverTime,
        firstTimeVsReturnCustomer,
        locationCustomer,
        oneTimeCustomer,
        returningCustomer
    },
    data: function() {
        return {
            exportFields: {},
            exportData: [],
            searchData: searchFields,
            isDisabled:true,
            currentActionTab: 'customerOverTime',
            symbol: '',
            installationDate:''
        }
    },
    methods: {
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
        searchCustomerRecords: function() {
            switch(this.currentActionTab)
            {
                case "customerOverTime":
                    this.$refs.customerOverTime.searchRecords(this.searchData.dateRange);
                break;
                case "firstTimeVsReturn":
                    this.$refs.firstTimeVsReturn.searchRecords(this.searchData.dateRange);
                break;
                case "locationCustomer":
                    this.$refs.locationCustomer.searchRecords(this.searchData.dateRange);
                break;
                case "oneTimeCustomer":
                    this.$refs.oneTimeCustomer.searchRecords(this.searchData.dateRange);
                break;
                case "returningCustomer":
                    this.$refs.returningCustomer.searchRecords(this.searchData.dateRange);
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
                case "customerOverTime":
                    this.$refs.customerOverTime.printCustomerReport();
                break;
                case "firstTimeVsReturn":
                    this.$refs.firstTimeVsReturn.printCustomerReport();
                break;
                case "locationCustomer":
                    this.$refs.locationCustomer.printCustomerData();
                break;
                case "oneTimeCustomer":
                    this.$refs.oneTimeCustomer.printCustomerData();
                break;
                case "returningCustomer":
                    this.$refs.returningCustomer.printCustomerData();
                break;
            }
        },
        switchTab: function(value) {
            this.currentActionTab = value;
        }  
    }
}
</script>