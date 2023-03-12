<template>
    <div>
        <div class="datatable datatable--default datatable--brand datatable--loaded" id="locationByCustomer" v-if="records.length > 0">
            <table class="datatable__table">
                <thead class="datatable__head datatable__head-fixed">
                    <tr class="datatable__row">
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('user_country')" v-bind:class="[
                            sortingBy == 'user_country' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_COUNTRY_REGION') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'user_country'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('order_customer')" v-bind:class="[
                            sortingBy == 'order_customer' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_CUSTOMERS') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'order_customer'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('order_count')" v-bind:class="[
                            sortingBy == 'order_count' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_ORDER_TO_DATE') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'order_count'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('order_amount')" v-bind:class="[
                            sortingBy == 'order_amount' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_TOTAL_SPENT_TO_DATE') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'order_amount'"></i>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="datatable__body">
                    <tr class="datatable__row datatable__row-highlighted">
                        <td class="datatable__cell"><span>{{ $t('LBL_SUMMARY') }}</span></td>
                        <td class="datatable__cell"><span> {{ TotalCustomer }} </span></td>
                        <td class="datatable__cell"><span> {{ TotalOrderToDate }} </span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ Math.round(totalAmount) }} </span></td>
                    </tr>
                    <tr class="datatable__row" v-for= "(item, key) in records" :key="key">
                        <td class="datatable__cell"><span>{{ item.user_country ? item.user_country : '' }}</span></td>
                        <td class="datatable__cell"><span>{{ item.order_customer ? item.order_customer : '' }}</span></td>
                        <td class="datatable__cell"><span>{{ item.order_count ? item.order_count : '' }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }} {{ item.order_amount ? item.order_amount : 0 }}</span></td>
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
                    'Country/Region': 'user_country',
                    'Customers': 'order_customer',
                    'Order to date' : "order_count",
                    'Total spent to date' : 'order_amount'
                },
                exportData :[],
                summary:{},
                loading: false,
                type : "locationCustomer",
                symbol:'',
                sortingType: '',
                sortingBy: ''
            }
        },
        methods: {
            searchRecords: function(date) {
                this.searchData.dateRange = date;
                this.getCustomerData();
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
            getCustomerData : function() {
                this.loading = true;
                let formData = this.$serializeData(this.searchData);
                formData.append("sorting-by", this.sortingBy);
                formData.append("sorting-type", this.sortingType);
                formData.append('type', this.type);
                this.$http.post(adminBaseUrl + '/reports/get-customer-report',formData).then((response) => {
                    if (response.data.status == false) {
                        this.loading = false;
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    this.records = response.data.data['data'];
                    this.exportData  = response.data.data['data'];
                    this.searchData.dateRange = response.data.data['dateRange'];
                    this.loading = false;
                    this.emitExportFieldsToParent();
                    this.emitExportDataToParent();
                    this.emitDateRange();
                });
            },
            sortBy: function (type) {
                if (this.sortingType == "" || this.sortingType == "desc" || this.sortingBy != type) {
                    this.sortingType = "asc";
                } else {
                    this.sortingType = "desc";
                }
                this.sortingBy = type;
                this.getCustomerData();
            },
            printCustomerData() {
                this.$htmlToPaper('locationByCustomer');
            }
        },
        mounted: function() {
            if(installationDate != '') {
                this.$emit('installationDate', installationDate);
            }
            this.symbol = currencySymbol;
            this.searchData.dateRange = this.dateRange;
            this.getCustomerData();
        },
        computed : {
            TotalCustomer: function () {
               return  this.records.reduce((acc, item) => acc + item.order_customer, 0);
            },
            TotalOrderToDate: function () {
               return  this.records.reduce((acc, item) => acc + item.order_count, 0);
            },
            totalAmount : function () {
               return  this.records.reduce((acc, item) => acc + parseInt(item.order_amount), 0);
            }
        }
    };
</script>