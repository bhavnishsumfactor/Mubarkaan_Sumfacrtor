<template>
    <div>
        <div class="datatable datatable--default datatable--brand datatable--loaded" id="saleByTrafficRefferrer" v-if="records.length > 0">
            <table class="datatable__table">
                <thead class="datatable__head datatable__head-fixed">
                    <tr class="datatable__row">
                        <th class="datatable__cell datatable__cell--sort" @click="sort('name')" v-bind:class="[
                            currentSort == 'name' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_TRAFFIC_REFERRER_SOURCE') }}
                                <i class="fas" v-bind:class="[currentSortDir == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'name'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sort('order_count')" v-bind:class="[
                            currentSort == 'order_count' ? 'datatable__cell--sorted' : '']">
                            <span>
                                {{ $t('LBL_ORDERS') }}
                                <i class="fas" v-bind:class="[currentSortDir == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'order_count'"></i>
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
                        <td class="datatable__cell"><span>{{ $t('LBL_SUMMARY') }}</span></td>
                        <td class="datatable__cell"><span>{{ countOrders  }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ Math.round(totalSale) }}</span></td>
                    </tr>
                    <tr class="datatable__row" v-for="(item, key) in sortlisting" :key="key">
                        <td class="datatable__cell"><span>{{ item.name ? item.name : '' }}</span></td>
                        <td class="datatable__cell"><span>{{ item.order_count ? item.order_count : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ item.total_sale ? Math.round(item.total_sale) : 0 }}</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <loader v-if="loading"></loader>
        <noRecordsFound v-if="loading==false && records.length == 0"></noRecordsFound>
    </div>
</template>
<script>
    const searchFields = {
        'search': '',
        'dateRange': [],
    };
    export default {
        props: ['dateRange'],
        data: function() {
            return {
                baseUrl: baseUrl,
                searchData: searchFields,
                records: [],
                exportFields: {
                    'Traffic referrer source': 'name',
                    'Orders': 'order_count',
                    'Total Sale' : 'total_sale'
                },
                exportData: [],
                summary:{},
                loading: false,
                symbol: '',
                type: 'saleReportTrafficRefferals',
                currentSort:'',
                currentSortDir:''
            }
        },
        methods: {
            searchRecords: function(date) {
                this.searchData.dateRange = date;
                this.getSaleByTrafficReferrer();
            },
            emitExportFieldsToParent : function(){
                this.$emit('exportExcelFields', this.exportFields);
            },
            emitExportDataToParent : function(){
                this.$emit('exportExcelData', this.exportData);
            },
            emitDateRange : function(){
                this.$emit('setDate', this.searchData.dateRange);
            },
            getSaleByTrafficReferrer : function() {
                this.loading = true;
                let formData = this.$serializeData(this.searchData);
                formData.append('type', this.type);
                this.$http.post(adminBaseUrl + '/reports/get-sale-reports', formData ).then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    this.records = response.data.data['data'];
                    this.exportData = response.data.data['data'];
                    this.summary = response.data.data['summary'];
                    this.searchData.dateRange = response.data.data['dateRange'];
                    this.loading = false;
                    this.emitExportFieldsToParent();
                    this.emitExportDataToParent();
                    this.emitDateRange();
                });
            },
            printSaleByTrafficReferrer() {
                this.$htmlToPaper('saleByTrafficRefferrer');
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
            this.getSaleByTrafficReferrer();
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
            totalSale : function () {
                return  this.records.reduce((acc, item) => acc + (item.total_sale ? Number(item.total_sale) : 0) , 0);
            }
        }
    };
</script>