<template>
    <div>
        <div class="subheader grid__item" id="subheader">
            <div class="container ">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{$t('LBL_PROFIT_REPORT') }} </h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">
                            {{ $t('LBL_REPORTS')}}
                        </span>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{ $t('LBL_PROFIT_REPORT') }}</span>
                    </div>
                </div>
                <div class="subheader__toolbar">
                    <div class="subheader__wrapper">
                        <date-picker class="custom-date-range" v-model="searchData.dateRange" type="date" range value-type="format" format="YYYY-MM-DD" placeholder="Select date" @change="searchRecords" :disabled-date="disabledDates">
                        </date-picker>
                        <export-excel :data="exportData" :fields="exportFields" :name="currentActionTab">
                            <button type="button" class="btn btn-subheader YK-exportBtn" :disabled="isDisabled || (!$canWrite('PROFIT_MARGIN_REPORT'))">{{ $t('BTN_EXPORT') }}</button>
                        </export-excel>
                        <button type="button" @click="printReport" class="btn btn-subheader YK-printBtn" :disabled="isDisabled || (!$canWrite('PROFIT_MARGIN_REPORT'))">{{ $t('BTN_PRINT') }}</button>
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
                                    <li class="nav-item"><a data-toggle="tab" href="#portlet_tab_content_1" @click="switchTab('profitByProduct')" role="tab" aria-selected="false" class="nav-link active">{{ $t('LNK_PROFIT_BY_PRODUCT') }}</a></li>

                                    <li class="nav-item"><a data-toggle="tab" href="#portlet_tab_content_2" @click="switchTab('profitByProductVariant')" role="tab" aria-selected="true" class="nav-link">{{ $t('LNK_PROFIT_BY_PRODUCT_VARIANT') }} </a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="portlet__body portlet__body--fit">
                            <div class="tab-content">
                                <div id="portlet_tab_content_1" role="tabpanel" class="tab-pane active">
                                    <profitByProduct :dateRange="searchData.dateRange" v-if="currentActionTab == 'profitByProduct'" v-on:exportExcelFields="setExportFields" v-on:exportExcelData="setExportData" v-on:setDate="setDate" v-on:installationDate="setInstallationDate" ref="profitByProduct"/>
                                </div>
                                <div id="portlet_tab_content_2" role="tabpanel" class="tab-pane">
                                    <profitByProductVariant :dateRange="searchData.dateRange" v-if="currentActionTab == 'profitByProductVariant'" v-on:exportExcelFields="setExportFields" v-on:exportExcelData="setExportData"  v-on:installationDate="setInstallationDate" v-on:setDate="setDate" ref="profitByProductVariant"/>
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
import profitByProduct from './profitByProduct';
import profitByProductVariant from './profitByProductVariant';
export default {
    components: {
        DatePicker,
        profitByProduct,
        profitByProductVariant
    },
    data: function() {
        return {
            exportFields: {},
            exportData: [],
            searchData: searchFields,
            currentActionTab: 'profitByProduct',
            isDisabled:true,
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
                let today = new Date(this.installationDate);
                return date < today || date > new Date();
            }
        },
        searchRecords: function() {
            switch(this.currentActionTab)
            {
                case "profitByProduct":
                    this.$refs.profitByProduct.searchRecords(this.searchData.dateRange);
                break;
                case "profitByProductVariant":
                    this.$refs.profitByProductVariant.searchRecords(this.searchData.dateRange);
                break;
            }
        },
        printReport() {
            switch(this.currentActionTab)
            {
                case "profitByProduct":
                    this.$refs.profitByProduct.printProfitByProduct();
                break;
                case "profitByProductVariant":
                    this.$refs.profitByProductVariant.printSaleByProductVariant();
                break;
            }
        },
        switchTab: function(value) {
            this.currentActionTab = value;
        }  
    }
}
</script>