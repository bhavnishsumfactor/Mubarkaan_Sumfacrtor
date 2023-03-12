<template>
    <div>
        <div class="subheader grid__item" id="subheader">
            <div class="container ">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{$t('LBL_SALE_REPORT') }} </h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">
                            {{ $t('LBL_REPORTS')}}
                        </span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{ $t('LBL_SALE_REPORT') }}</span>
                    </div>
                </div>
                <div class="subheader__toolbar">
                    <div class="subheader__wrapper">
                        <date-picker class="custom-date-range" 
                            v-model="searchData.dateRange" type="date" range 
                            value-type="format" format="YYYY-MM-DD" 
                            :placeholder="$t('PLH_SELECT_DATE')" 
                            :disabled-date="disabledDates"
                            @change="searchSaleRecords">
                        </date-picker>
                        <export-excel :data="exportData" :fields="exportFields" :name="currentActionTab">
                            <button type="button" class="btn btn-subheader YK-exportBtn" :disabled="isDisabled || (!$canWrite('SALE_REPORT'))">{{ $t('BTN_EXPORT') }}</button>
                        </export-excel>
                        <button type="button" @click="printSaleOverTimeReport" class="btn btn-subheader YK-printBtn" :disabled="isDisabled || (!$canWrite('SALE_REPORT'))">{{ $t('BTN_PRINT') }}</button>
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
                            <div class="portlet__head-toolbar">
                                <ul role="tablist" class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold">
                                    <li class="nav-item"><a data-toggle="tab" href="#portlet_tab_content_1" @click="switchTab('saleOverTime')" role="tab" aria-selected="false" class="nav-link active">{{ $t('LNK_SALE_OVER_TIME') }}</a></li>

                                    <li class="nav-item"><a data-toggle="tab" href="#portlet_tab_content_2" @click="switchTab('saleByProduct')" role="tab" aria-selected="true" class="nav-link">{{ $t('LNK_SALE_BY_PRODUCT') }} </a></li>

                                    <li class="nav-item"><a data-toggle="tab" href="#portlet_tab_content_3" @click="switchTab('saleByProductVariant')" role="tab" aria-selected="false" class="nav-link"> {{ $t('LNK_SALE_BY_PRODUCT_VARIANT') }}</a></li>

                                    <li class="nav-item"><a data-toggle="tab" href="#portlet_tab_content_4" @click="switchTab('saleByCustomerName')" role="tab" aria-selected="false"  class="nav-link">{{ $t('LNK_SALE_BY_CUSTOMER_NAME') }}</a></li>

                                    <li class="nav-item"><a data-toggle="tab" href="#portlet_tab_content_5" @click="switchTab('saleByAvgOrderOverTime')" role="tab" aria-selected="false" class="nav-link">{{ $t('LNK_AVERAGE_ORDER_VALUE_OVER_TIME') }}</a></li>

                                    <li class="nav-item"><a data-toggle="tab" href="#portlet_tab_content_6" @click="switchTab('saleByTrafficReferrer')" role="tab" aria-selected="false" class="nav-link">{{ $t('LNK_SALES_BY_TRAFFIC_REFERRER') }}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="portlet__body portlet__body--fit">
                            <div class="tab-content">
                                <div id="portlet_tab_content_1" role="tabpanel" class="tab-pane active">
                                    <saleOverTime :dateRange="searchData.dateRange" v-if="currentActionTab == 'saleOverTime'" v-on:exportExcelFields="setExportFields" v-on:exportExcelData="setExportData" v-on:setDate="setDate" v-on:installationDate="setInstallationDate" ref="saleOverTime"/>
                                </div>
                                <div id="portlet_tab_content_2" role="tabpanel" class="tab-pane">
                                    <saleByProduct  :dateRange="searchData.dateRange" v-if="currentActionTab == 'saleByProduct'" v-on:exportExcelFields="setExportFields" v-on:exportExcelData="setExportData" v-on:setDate="setDate" v-on:installationDate="setInstallationDate" ref="saleByProduct"/>
                                </div>
                                <div id="portlet_tab_content_3" role="tabpanel" class="tab-pane">
                                    <saleByProductVariant  :dateRange="searchData.dateRange" v-if="currentActionTab == 'saleByProductVariant'" v-on:exportExcelFields="setExportFields" v-on:exportExcelData="setExportData" v-on:setDate="setDate" v-on:installationDate="setInstallationDate" ref="saleByProductVariant"/>
                                </div>
                                <div id="portlet_tab_content_4" role="tabpanel" class="tab-pane">
                                    <saleByCustomerName  :dateRange="searchData.dateRange" v-if="currentActionTab == 'saleByCustomerName'" v-on:exportExcelFields="setExportFields" v-on:exportExcelData="setExportData" v-on:setDate="setDate" v-on:installationDate="setInstallationDate" ref="saleByCustomerName"/>
                                </div>
                                <div id="portlet_tab_content_5" role="tabpanel" class="tab-pane">
                                    <saleByAverageOrderOverTime  :dateRange="searchData.dateRange" v-if="currentActionTab == 'saleByAvgOrderOverTime'" v-on:exportExcelFields="setExportFields" v-on:exportExcelData="setExportData" v-on:installationDate="setInstallationDate" v-on:setDate="setDate" ref="saleByAvgOrderOverTime"/>
                                </div>
                                <div id="portlet_tab_content_6" role="tabpanel" class="tab-pane">
                                    <saleByTrafficReferrer  :dateRange="searchData.dateRange" v-if="currentActionTab == 'saleByTrafficReferrer'" v-on:exportExcelFields="setExportFields" v-on:exportExcelData="setExportData" v-on:installationDate="setInstallationDate" v-on:setDate="setDate" ref="saleByTrafficReferrer"/>
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
import saleOverTime from './saleOverTime';
import saleByProduct from './saleByProduct';
import saleByCustomerName from './saleByCustomerName';
import saleByProductVariant from './saleByProductVariant';
import saleByTrafficReferrer from './saleByTrafficReferrer';
import saleByAverageOrderOverTime from './saleByAverageOrderOverTime';

export default {
    components: {
        DatePicker,
        saleOverTime,
        saleByProduct,
        saleByCustomerName,
        saleByProductVariant,
        saleByTrafficReferrer,
        saleByAverageOrderOverTime
    },
    data: function() {
        return {
            exportFields: {},
            exportData: [],
            searchData: searchFields,
            isDisabled:true,
            currentActionTab: 'saleOverTime',
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
        setInstallationDate: function(date) {
            this.installationDate = date;
        },
        disabledDates: function(date) {
            if (this.installationDate != "") {
                let installationDate = new Date(this.installationDate);
                installationDate.setHours(0,0,0,0);
                return date < installationDate || date > new Date();
            }
        },
        searchSaleRecords: function() {
            switch(this.currentActionTab)
            {
                case "saleOverTime":
                    this.$refs.saleOverTime.searchRecords(this.searchData.dateRange);
                break;
                case "saleByProduct":
                    this.$refs.saleByProduct.searchRecords(this.searchData.dateRange);
                break;
                case "saleByCustomerName":
                    this.$refs.saleByCustomerName.searchRecords(this.searchData.dateRange);
                break;
                case "saleByProductVariant":
                    this.$refs.saleByProductVariant.searchRecords(this.searchData.dateRange);
                break;
                case "saleByTrafficReferrer":
                    this.$refs.saleByTrafficReferrer.searchRecords(this.searchData.dateRange);
                break;
                case "saleByAvgOrderOverTime":
                    this.$refs.saleByAvgOrderOverTime.searchRecords(this.searchData.dateRange);
                break;
            }
        },
        printSaleOverTimeReport() {
            switch(this.currentActionTab)
            {
                case "saleOverTime":
                    this.$refs.saleOverTime.printSaleOverTimeReport();
                break;
                case "saleByProduct":
                    this.$refs.saleByProduct.printSaleByProduct();
                break;
                case "saleByCustomerName":
                    this.$refs.saleByCustomerName.printSaleByCustomerName();
                break;
                case "saleByProductVariant":
                    this.$refs.saleByProductVariant.printSaleByProductVariant();
                break;
                case "saleByTrafficReferrer":
                    this.$refs.saleByTrafficReferrer.printSaleByTrafficReferrer();
                break;
                case "saleByAvgOrderOverTime":
                    this.$refs.saleByAverageOrderOverTime.printSaleAvgOverTimeReport();
                break;
            }
        },
        switchTab: function(value) {
            this.currentActionTab = value;
        },
        mounted: function() {
            $(document).on("mouseover", ".YK-printBtn", function() {
                $(this).tooltip('show');
            });
            $(document).on("mouseleave", ".YK-printBtn", function() {
                $(this).tooltip('hide');
            });
        }
    }
}
</script>