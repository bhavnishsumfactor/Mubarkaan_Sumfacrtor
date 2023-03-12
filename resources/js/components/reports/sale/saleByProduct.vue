<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <div class="px-4 py-2">
                    <div class="input-icon input-icon--left"><input type="text" placeholder="Search" id="generalSearch" class="form-control" v-model="searchData.search" @keyup="searchProducts"> <span class="input-icon__icon input-icon__icon--left"><span><i class="la la-search"></i></span></span></div>
                </div>
            </div>
        </div>
        <div class="datatable datatable--default datatable--brand datatable--loaded" id="saleByProduct" v-if="records.length > 0">
            <table class="datatable__table">
                <thead class="datatable__head datatable__head-fixed">
                    <tr class="datatable__row">
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('op_product_name')" v-bind:class="[
                            currentSort == 'op_product_name' ? 'datatable__cell--sorted' : '',
                          ]"><span>{{ $t('LBL_PRODUCTS_NAME') }} <i class="fas" v-bind:class="[currentSortDir == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']"
                              v-if="currentSort == 'op_product_name'"
                            ></i></span></th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('op_product_type')" v-bind:class="[
                            currentSort == 'op_product_type' ? 'datatable__cell--sorted' : '',
                          ]"><span>{{ $t('LBL_PRODUCTS_TYPE') }}
                              <i class="fas" v-bind:class="[currentSortDir == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'op_product_type'"></i>
                            </span></th>
                        
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('order_net_quantity')" v-bind:class="[
                            currentSort == 'order_net_quantity' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_NET_QUANTITY') }} 
                                <i class="fas" v-bind:class="[currentSortDir == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'order_net_quantity'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('gross_sale')" v-bind:class="[
                            currentSort == 'gross_sale' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_GROSS_SALES') }} 
                                <i class="fas" v-bind:class="[currentSortDir == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'gross_sale'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('discount_amount')" v-bind:class="[
                            currentSort == 'discount_amount' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_DISCOUNTS') }}
                                <i class="fas" v-bind:class="[currentSortDir == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'discount_amount'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('return_amount')" v-bind:class="[
                            currentSort == 'return_amount' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_RETURNS') }}
                                <i class="fas" v-bind:class="[currentSortDir == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'return_amount'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('net_sale')" v-bind:class="[
                            currentSort == 'net_sale' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_NET_SALES') }}
                                <i class="fas" v-bind:class="[currentSortDir == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="currentSort == 'net_sale'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('tax_charge')" v-bind:class="[
                            currentSort == 'tax_charge' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_TAX') }}
                                <i class="fas" v-bind:class="[currentSortDir == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']"
                                    v-if="currentSort == 'tax_charge'"
                                ></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('total_sale')" v-bind:class="[
                            currentSort == 'total_sale' ? 'datatable__cell--sorted' : '']">
                            <span>  {{ $t('LBL_TOTAL_SALES') }} 
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']"
                                    v-if="currentSort == 'total_sale'"
                                ></i>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="datatable__body">
                    <tr class="datatable__row datatable__row-highlighted">
                        <td class="datatable__cell"><span>{{ $t('LBL_SUMMARY') }}</span></td>
                        <td class="datatable__cell"> </td>
                        <td class="datatable__cell"><span>{{ countOrders }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ Math.round(totalGrossSale) }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ Math.round(totalDiscount) }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ Math.round(totalReturns) }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ Math.round(totalNetSale) }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ Math.round(totalTaxCharge) }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ Math.round(totalSale) }}</span></td>
                    </tr>
                    <tr class="datatable__row" v-for = "(item, key) in sortlisting" :key="key">
                        <td class="datatable__cell"><span>{{ item.op_product_name ? item.op_product_name : '' }}</span></td>
                        <td class="datatable__cell">
                            <span class="badge  badge--dot" v-bind:class="[item.op_product_type == 1 ? 'badge--danger' : 'badge--success']"></span>&nbsp;
                            <span class="font-bold" v-bind:class="[item.op_product_type == 1 ? 'font-danger' : 'font-success']">{{ productTypes[item.op_product_type] }}</span>
                        </td>
                        <td class="datatable__cell"><span>{{ item.order_net_quantity ? item.order_net_quantity : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ item.gross_sale ? item.gross_sale : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ item.discount_amount ? item.discount_amount : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ item.return_amount ? item.return_amount : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ item.net_sale ? item.net_sale : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ item.tax_charge ? item.tax_charge : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ item.total_sale ? item.total_sale : 0 }}</span></td>
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
                orderDetail: {},
                records: [],
                productTypes: [],
                saleByProductFields: {
                    'Product Title': 'op_product_name',
                    'Product Type': {
                        field: 'op_product_type',
                        callback: (value) => {
                            if(value == 1){
                                return "Physical"
                            }else{
                                return "Digital"
                            }
                        }
                    },
                    'Net Quantity': 'order_net_quantity',
                    'Gross Sale': 'gross_sale',
                    'Discounts': 'discount_amount',
                    'Returns': 'return_amount',
                    'Net Sale': 'net_sale',
                    'Tax': 'tax_charge',
                    'Total': 'total_sale'
                },
                saleByProductData: [],
                summary:{},
                loading: false,
                symbol: '',
                sortingType: '',
                sortingBy: '',
                currentSort:'op_product_name',
                currentSortDir:'asc',
                type: 'saleReportByProduct'
            }
        },
        methods: {
            searchRecords: function(date) {
                this.searchData.dateRange = date;
                this.getSaleByProduct();
            },
            searchProducts: function() {
                this.getSaleByProduct();
            },
            emitExportFieldsToParent : function(){
                this.$emit('exportExcelFields', this.saleByProductFields);
            },
            emitExportDataToParent : function(){
                this.$emit('exportExcelData', this.saleByProductData);
            },
            emitDateRange : function(){
                this.$emit('setDate', this.searchData.dateRange);
            },
            getSaleByProduct: function() {
                this.loading = true;
                let formData = this.$serializeData(this.searchData);
                formData.append("sorting-by", this.sortingBy);
                formData.append("sorting-type", this.sortingType);
                formData.append('type', this.type);
                this.$http.post(adminBaseUrl + '/reports/get-sale-reports', formData).then((response) => {
                    if (response.data.status == false) {
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    this.records = [...Object.values(response.data.data['totalProducts'])];
                    this.saleByProductData = this.records;
                    this.productTypes = response.data.data['types'];
                    this.searchData.dateRange = response.data.data['dateRange'];
                    this.loading = false;
                    this.emitExportFieldsToParent();
                    this.emitExportDataToParent();
                    this.emitDateRange();
                });
            },
            printSaleByProduct() {
                this.$htmlToPaper('saleByProduct');
            },
            sortBy: function (field) {
                if (field === this.currentSort) {
                    this.currentSortDir = this.currentSortDir === 'asc'  ? 'desc':'asc';
                }else {
                    this.currentSortDir = 'desc';
                }
                this.currentSort = field;
            },
        },
        mounted: function() {
            if(installationDate != '') {
                this.$emit('installationDate', installationDate);
            }
            this.symbol = currencySymbol;
            this.searchData.dateRange = this.dateRange;
            this.getSaleByProduct();
        },
        computed: {
            sortlisting: function() {
                if (this.currentSort == "op_product_name") {
                    return this.records.sort((a,b) => {
                    let modifier = 1;
                        if(this.currentSortDir === 'desc') modifier = -1;
                        if((a[this.currentSort]).toUpperCase() < (b[this.currentSort].toUpperCase())) return -1 * modifier;
                        if((a[this.currentSort].toUpperCase()) > (b[this.currentSort].toUpperCase())) return 1 * modifier;
                        return 0;
                    });
                } else {
                    return this.records.sort((a,b) => {
                    let modifier = 1;
                        if(this.currentSortDir === 'desc') modifier = -1;
                        if(a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
                        if(a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
                        return 0;
                    });
                }
            },
            countOrders: function () {
                return this.records.reduce((acc, item) => acc + (item.order_net_quantity ? (Number(item.order_net_quantity)) : 0), 0);
            },
            totalGrossSale: function () {
                return  this.records.reduce((acc, item) => acc + (item.gross_sale ? item.gross_sale : 0), 0);
            },
            totalReturns: function () {
                return  this.records.reduce((acc, item) => acc + (item.return_amount ? item.return_amount : 0), 0);
            },
            totalNetSale: function () {
                return  this.records.reduce((acc, item) => acc + (item.net_sale ? item.net_sale : 0), 0);
            },
            totalTaxCharge : function () {
                return  this.records.reduce((acc, item) => acc + (item.tax_charge ? item.tax_charge : 0), 0);
            },
            totalSale : function () {
                return  this.records.reduce((acc, item) => acc + (item.total_sale ? item.total_sale : 0) , 0);
            },
            totalDiscount: function () {
                return  this.records.reduce((acc, item) => acc + (item.discount_amount ? Number(item.discount_amount) : 0), 0);
            }
        }
    };
</script>