<template>
    <div>
        <div class="p-4 YK-saleOverTime">
            <vue-chartist ratio="" type="Bar" :data="chartData" :options="options"></vue-chartist>
        </div>
        <hr>
        <div id="saleOverTimeReport" class="datatable datatable--default datatable--brand datatable--loaded " v-if="records.length > 0">
            <table class="datatable__table ">
                <thead class="datatable__head datatable__head-fixed">
                    <tr class="datatable__row">
                        <th class="datatable__cell datatable__cell--sort"><span>{{ $t('LBL_DAY') }}</span></th>
                        <th class="datatable__cell datatable__cell--sort" @click="sort('order_count')" v-bind:class="[
                            currentSort == 'order_count' ? 'datatable__cell--sorted' : '']">
                            <span>
                                {{ $t('LBL_ORDERS') }}
                                <i class="fas" v-bind:class="[currentSortDir == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'order_count'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sort('gross_sale')" v-bind:class="[
                            currentSort == 'gross_sale' ? 'datatable__cell--sorted' : '']">
                            <span>
                                {{ $t('LBL_GROSS_SALES') }}
                                <i class="fas" v-bind:class="[currentSortDir == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'gross_sale'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sort('discount_amount')" v-bind:class="[
                            currentSort == 'discount_amount' ? 'datatable__cell--sorted' : '']">
                            <span>
                                {{ $t('LBL_DISCOUNTS') }}
                                <i class="fas" v-bind:class="[currentSortDir == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'discount_amount'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sort('return_amount')" v-bind:class="[
                            currentSort == 'return_amount' ? 'datatable__cell--sorted' : '']">
                            <span>
                                {{ $t('LBL_RETURNS') }}
                                <i class="fas" v-bind:class="[currentSortDir == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'return_amount'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sort('net_sale')" v-bind:class="[
                            currentSort == 'net_sale' ? 'datatable__cell--sorted' : '']">
                            <span>
                                {{ $t('LBL_NET_SALES') }}
                                <i class="fas" v-bind:class="[currentSortDir == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'net_sale'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sort('shipping_charge')" v-bind:class="[
                            currentSort == 'shipping_charge' ? 'datatable__cell--sorted' : '']">
                            <span>
                                {{ $t('LBL_SHIPPING') }}
                                <i class="fas" v-bind:class="[currentSortDir == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'shipping_charge'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sort('tax_charge')" v-bind:class="[
                            currentSort == 'tax_charge' ? 'datatable__cell--sorted' : '']">
                            <span>
                                {{ $t('LBL_TAX') }}
                                <i class="fas" v-bind:class="[currentSortDir == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'tax_charge'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sort('total_sale')" v-bind:class="[
                            currentSort == 'total_sale' ? 'datatable__cell--sorted' : '']">
                            <span>
                                {{ $t('LBL_TOTAL_SALES') }}
                                <i class="fas" v-bind:class="[currentSortDir == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'total_sale'"></i>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="datatable__body">
                    <tr class="datatable__row datatable__row-highlighted">
                        <td class="datatable__cell"><span>{{ $t('LBL_SUMMARY') }} </span></td>
                        <td class="datatable__cell"><span>{{ countOrders }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ Math.round(totalGrossSale) }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ Math.round(totalDiscount) }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ Math.round(totalReturns) }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ Math.round(totalNetSale) }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ Math.round(totalShippingCharge) }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ Math.round(totalTaxCharge) }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ Math.round(totalSale) }}</span></td>
                    </tr>
                    <tr class="datatable__row" v-for="(item, key) in sortlisting" :key="key">
                        <td class="datatable__cell"><span>{{ item.day }}</span></td>
                        <td class="datatable__cell" ><span>{{ item.order_count ? item.order_count : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ item.gross_sale ? item.gross_sale : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ item.discount_amount ? item.discount_amount : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ item.return_amount ? item.return_amount : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ item.net_sale ? item.net_sale : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ item.shipping_charge ? item.shipping_charge : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ item.tax_charge ? item.tax_charge : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ item.total_sale ? item.total_sale : 0 }}</span></td>
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
            SaleOverTimeFields: {
                'Day': 'day',
                'Orders': 'order_count',
                'Gross sales': 'gross_sale',
                'Discounts': 'discount_amount',
                'Returns': 'return_amount',
                'Net sales': 'net_sale',
                'Shipping': 'shipping_charge',
                'Tax': 'tax_charge',
                'Total sales': 'total_sale'
            },
            SaleOverTimeData: [],
            exportFields: {},
            exportData: [],
            baseUrl: baseUrl,
            searchData: searchFields,
            orderDetail: {},
            records: [],
            summary: {},
            loading: false,
            symbol: '',
            currentSort:'',
            currentSortDir:'',
            type : 'saleOverTime'
        }
    },
    methods: {
        searchRecords: function(date) {
            this.searchData.dateRange = date;
            this.getSaleOverTime();
        },
        emitExportFieldsToParent : function(){
            this.$emit('exportExcelFields', this.SaleOverTimeFields);
        },
        emitExportDataToParent : function(){
            this.$emit('exportExcelData', this.SaleOverTimeData);
        },
        emitDateRange : function(){
            this.$emit('setDate', this.searchData.dateRange);
        },
        printSaleOverTimeReport() {
            this.$htmlToPaper('saleOverTimeReport');
        },
        getSaleOverTime: function() {
            this.loading = true;
            let formData = this.$serializeData(this.searchData);
            formData.append('type', this.type);
            this.$http.post(adminBaseUrl + '/reports/get-sale-reports', formData).then((response) => {
                if (response.data.status == false) {
                    toastr.error(response.data.message, '', toastr.options);
                    return;
                }
                this.records = response.data.data['data'];
                this.SaleOverTimeData = response.data.data['data'];
                this.exportData = this.SaleOverTimeData;
                this.chartData.labels = response.data.data['labels'];
                this.chartData.series = [];
                this.summary = response.data.data['summary'];
                this.chartData.series.push(response.data.data['series']);
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
    computed:{
        sortlisting: function() {
            return this.records.sort((a,b) => {
            let modifier = 1;
                if(this.currentSortDir === 'desc') modifier = -1;
                if(a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
                if(a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
                return 0;
            });
        },
        countOrders: function () {
            return this.records.reduce((acc, item) => acc + (item.order_count ? Number(item.order_count) : 0), 0);
        },
        totalGrossSale: function () {
            return  this.records.reduce((acc, item) => acc + (item.gross_sale ? Number(item.gross_sale) : 0), 0);
        },
        totalDiscount: function () {
            return  this.records.reduce((acc, item) => acc + (item.discount_amount ? Number(item.discount_amount) : 0), 0);
        },
        totalReturns: function () {
            return  this.records.reduce((acc, item) => acc + (item.return_amount ? Number(item.return_amount) : 0), 0);
        },
        totalNetSale: function () {
            return  this.records.reduce((acc, item) => acc + (item.net_sale ? Number(item.net_sale) : 0), 0);
        },
        totalShippingCharge : function () {
            return  this.records.reduce((acc, item) => acc + (item.shipping_charge ? Number(item.shipping_charge) : 0), 0);
        },
        totalTaxCharge : function () {
            return  this.records.reduce((acc, item) => acc + (item.tax_charge ? Number(item.tax_charge) : 0), 0);
        },
        totalSale : function () {
            return  this.records.reduce((acc, item) => acc + (item.total_sale ? Number(item.total_sale) : 0) , 0);
        }
    },
    mounted: function() {
        if(installationDate != '') {
            this.$emit('installationDate', installationDate);
        }
        this.symbol = currencySymbol;
        if (this.dateRange != undefined)
        {
            this.searchData.dateRange = this.dateRange;
        }
        this.getSaleOverTime();
    }
}
</script>