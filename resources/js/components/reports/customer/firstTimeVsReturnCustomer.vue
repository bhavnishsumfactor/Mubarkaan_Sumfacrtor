<template>
    <div>
        <div class="p-4 YK-firstTimeVsReturnCustomer">
            <vue-chartist ratio="" type="Bar" :data="chartData" :options="options"></vue-chartist>
        </div>
        <hr>
        <div class="datatable datatable--default datatable--brand datatable--loaded" id="firstTimeVsReturnCustomer" v-if="records.length > 0">
            <table class="datatable__table">
                <thead class="datatable__head datatable__head-fixed">
                    <tr class="datatable__row">
                        <th class="datatable__cell datatable__cell--sort"><span>{{ $t('LBL_CUSTOMER_SALE_MONTH') }}</span></th>
                        <th class="datatable__cell datatable__cell--sort" @click="sort('first_time_spent')" v-bind:class="[
                            currentSort == 'first_time_spent' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_FIRST_TIME_SPENT') }}
                                <i class="fas" v-bind:class="[currentSortDir == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'first_time_spent'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sort('returning_spent')" v-bind:class="[
                            currentSort == 'returning_spent' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_RETURNING_SPENT') }}
                                <i class="fas" v-bind:class="[currentSortDir == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'returning_spent'"></i>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="datatable__body">
                    <tr class="datatable__row datatable__row-highlighted">
                        <td class="datatable__cell"><span>{{ $t('LBL_SUMMARY') }}</span></td>
                        <td class="datatable__cell"><span>{{ Math.round(TotalFirstTimeAmount) }} </span></td>
                        <td class="datatable__cell"><span> {{ Math.round(TotalReturningAmount) }} </span></td>
                    </tr>
                    <tr class="datatable__row" v-for = "(item, key) in sortlisting" :key="key">
                        <td class="datatable__cell"><span>{{ item.month ? item.month : '' }}</span></td>
                        <td class="datatable__cell"><span>{{ item.first_time_spent ? item.first_time_spent : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ item.returning_spent ? item.returning_spent : 0 }}</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <loader v-if="loading"></loader>
    </div>
</template>
<script>
 
const searchFields = {
    'dateRange': [],
};
import VueChartist from 'v-chartist';

export default {
    props: ['dateRange'],
    components: {
        'vue-chartist': VueChartist
    },
    data: function() {
        return {
            chartData: {
                labels: [],
                series: []
            },
            options: {
                fullWidth: true,
                showArea: true,
                onlyInteger: true,
                showPoint: false,
                axisY: {
                    showGrid: true,
                },
                axisX: {
                    showGrid: true
                },
            },
            exportFields: {
                'Month': 'month',
                'Customer Type': 'customer_type',
                'Orders' :'order_count',
                'Total spent to date' : 'amount',
            },
            exportData: [],
            baseUrl: baseUrl,
            searchData: searchFields,
            orderDetail: {},
            records: [],
            summary: {},
            loading: false,
            symbol:'',
            currentSort:'',
            currentSortDir:'',
        }
    },
    methods: {
        searchRecords: function(date) {
            this.searchData.dateRange = date;
            this.firstTimeVsReturningCustomerReport();
        },
        emitExportFieldsToParent : function() {
            this.$emit('exportExcelFields', this.exportFields);
        },
        emitExportDataToParent : function(){
            this.$emit('exportExcelData', this.exportData);
        },
        emitDateRange : function() {
            this.$emit('setDate', this.searchData.dateRange);
        },
        printCustomerReport () {
            this.$htmlToPaper('firstTimeVsReturnCustomer');
        },
        firstTimeVsReturningCustomerReport : function() {
            this.loading = true;
            let formData = this.$serializeData(this.searchData);
            this.$http.post(adminBaseUrl + '/reports/get-first-vs-return-customer',formData).then((response) => {
                if (response.data.status == false) {
                    this.loading = false;
                    toastr.error(response.data.message, '', toastr.options);
                    return;
                }
                this.chartData.labels = response.data.data['labels'];
                this.chartData.series = [];
                //this.summary = response.data.data['summary'];
                this.chartData.series.push(response.data.data['series'][0]);
                this.chartData.series.push(response.data.data['series'][1]);
                this.summary = response.data.data['summary'];
                this.records = response.data.data['data'];
                this.exportData  = response.data.data['data'];
                this.searchData.dateRange = response.data.data['dateRange'];
                this.loading = false;
                this.emitExportFieldsToParent();
                this.emitExportDataToParent();
                this.emitDateRange();
            });
        },
        sort:function(field) {
            if (field === this.currentSort) {
                this.currentSortDir = this.currentSortDir === 'asc'  ? 'desc':'asc';
            }else {
                this.currentSortDir = 'desc';
            }
            this.currentSort = field;
        }
    },
    mounted: function() {
        if(installationDate != '') {
            this.$emit('installationDate', installationDate);
        }
        this.symbol = currencySymbol;
        this.searchData.dateRange = this.dateRange;
        this.firstTimeVsReturningCustomerReport();
    },
    computed :{
        TotalFirstTimeAmount: function () {
            return  this.records.reduce((acc, item) => acc + parseInt(item.first_time_spent), 0);
        },
        TotalReturningAmount: function () {
            return  this.records.reduce((acc, item) => acc + parseInt(item.returning_spent), 0);
        },
        sortlisting: function() {
            return this.records.sort((a,b) => {
            let modifier = 1;
                if(this.currentSortDir === 'desc') modifier = -1;
                if(a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
                if(a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
                return 0;
            });
        }
    }
}
</script>