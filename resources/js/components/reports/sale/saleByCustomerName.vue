<template>
    <div>
        <div class="datatable datatable--default datatable--brand datatable--loaded" id="saleCustomerName" v-if="records.length > 0">
            <table class="datatable__table">
                <thead class="datatable__head datatable__head-fixed">
                    <tr class="datatable__row">
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('user_username')" v-bind:class="[
                            sortingBy == 'user_username' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_CUSTOMER_NAME') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'user_username'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('user_email')" v-bind:class="[
                            sortingBy == 'user_email' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_CUSTOMER_EMAIL') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'user_email'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('order_count')" v-bind:class="[
                            sortingBy == 'order_count' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_ORDERS') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'order_count'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('gross_sale')" v-bind:class="[
                            sortingBy == 'gross_sale' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_GROSS_SALES') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'gross_sale'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('return_amount')" v-bind:class="[
                            sortingBy == 'return_amount' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_RETURNS') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'return_amount'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('net_sale')" v-bind:class="[
                            sortingBy == 'net_sale' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_NET_SALES') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'net_sale'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('total_sale')" v-bind:class="[
                            sortingBy == 'total_sale' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_TOTAL_SALES') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'total_sale'"></i>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="datatable__body">
                    <tr class="datatable__row datatable__row-highlighted">
                        <td class="datatable__cell"><span>{{ $t('LBL_SUMMARY') }}</span></td>
                        <td class="datatable__cell"><span></span></td>
                        <td class="datatable__cell"><span>{{ totalOrderCount }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ Math.round(totalGrossSale) }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ Math.round(totalReturnAmount) }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ Math.round(totalNetSale) }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ Math.round(totalSale) }}</span></td>
                    </tr>

                    <tr class="datatable__row" v-for = "(item, key) in records" :key="key">
                        <td class="datatable__cell"><span>{{ item.user_username ? item.user_username : '' }}</span></td>
                        <td class="datatable__cell"><span>{{ item.user_email ? item.user_email : '' }}</span></td>
                        <td class="datatable__cell"><span>{{ item.order_count ? item.order_count : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ item.gross_sale ? item.gross_sale : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ item.return_amount ? item.return_amount : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ item.net_sale ? item.net_sale : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}  {{ item.total_sale ? item.total_sale : 0 }}</span></td>
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
                saleByCustomerFields: {
                    'Customer Name': 'user.user_name',
                    'Customer Email': 'user.user_email',
                    'Orders': 'order_count',
                    'Gross Sale' :  'gross_sale',
                    'Net Sale' : 'net_sale',
                    'Total' : 'total_sale'
                },
                saleByCustomerData: [],
                summary:{},
                loading: false,
                symbol: '',
                sortingType: '',
                sortingBy: '',
                type: 'saleReportByCustomerName'
            }
        },
        methods: {
            searchRecords: function(date) {
                this.searchData.dateRange = date;
                this.getSaleByCustomerName();
            },
            emitExportFieldsToParent : function(){
                this.$emit('exportExcelFields', this.saleByCustomerFields);
            },
            emitExportDataToParent : function(){
                this.$emit('exportExcelData', this.saleByCustomerData);
            },
            emitDateRange : function(){
                this.$emit('setDate', this.searchData.dateRange);
            },
            getSaleByCustomerName : function() {
                this.loading = true;
                let formData = this.$serializeData(this.searchData);
                formData.append("sorting-by", this.sortingBy);
                formData.append("sorting-type", this.sortingType);
                formData.append('type', this.type);
                this.$http.post(adminBaseUrl + '/reports/get-sale-reports', formData ).then((response) => {
                    if (response.data.status == false) {
                        this.loading = false;
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    this.records = [...Object.values(response.data.data['totalCustomer'])];

                    this.summary = response.data.data['summary'];
                    this.saleByCustomerData = this.records;
                    this.searchData.dateRange = response.data.data['dateRange'];
                    this.loading = false;
                    this.emitExportFieldsToParent();
                    this.emitExportDataToParent();
                    this.emitDateRange();
                });
            },
            printSaleByCustomerName() {
                this.$htmlToPaper('saleCustomerName');
            },
            sortBy: function (type) {                
                if (this.sortingType == "" || this.sortingType == "desc" || this.sortingBy != type) {
                    this.sortingType = "asc";
                } else {
                    this.sortingType = "desc";
                }
                this.sortingBy = type;
                this.getSaleByCustomerName();
            },
        },
        computed: {
            /*filterCustomers: function () {
                return this.records.filter(function (record) {
                    return record.order_user_id
                })
            }*/
            totalOrderCount: function () {
                return this.records.reduce((acc, item) => acc + (item.order_count ? Number(item.order_count) : 0), 0);
            },
            totalGrossSale: function () {
                return this.records.reduce((acc, item) => acc + (item.gross_sale ? Number(item.gross_sale) : 0), 0);
            },
            totalReturnAmount: function () {
                return this.records.reduce((acc, item) => acc + (item.return_amount ? Number(item.return_amount) : 0), 0);
            },
            totalNetSale : function() {
                return this.records.reduce((acc, item) => acc + (item.net_sale ? Number(item.net_sale) : 0), 0);
            },
            totalSale : function() {
                return this.records.reduce((acc, item) => acc + (item.total_sale ? Number(item.total_sale) : 0), 0);
            }

        },
        mounted: function() {
            if(installationDate != '') {
                this.$emit('installationDate', installationDate);
            }
            this.symbol = currencySymbol;
            this.searchData.dateRange = this.dateRange;
            this.getSaleByCustomerName();
        },
    };
</script>