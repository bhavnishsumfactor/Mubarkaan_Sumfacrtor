<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <div class="px-4 py-2">
                    <div class="input-icon input-icon--left"><input type="text" placeholder="Search" id="generalSearch" class="form-control" v-model="searchData.search" @keyup="searchProducts"> <span class="input-icon__icon input-icon__icon--left"><span><i class="la la-search"></i></span></span></div>
                </div>
            </div>
        </div>
        <div class="datatable datatable--default datatable--brand datatable--loaded" id="saleByProductVariant" v-if="records.length > 0">
            <table class="datatable__table">
                <thead class="datatable__head datatable__head-fixed">
                    <tr class="datatable__row">
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('op_product_name')" v-bind:class="[
                            sortingBy == 'op_product_name' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_PRODUCTS_NAME') }} 
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'op_product_name'"></i></span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort"><span>{{ $t('Variant Title') }}</span></th>
                        <th class="datatable__cell datatable__cell--sort"><span>{{ $t('Variant SKU') }} </span></th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('order_net_quantity')" v-bind:class="[
                            sortingBy == 'order_net_quantity' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_NET_QUANTITY') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'order_net_quantity'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('gross_sale')" v-bind:class="[
                            sortingBy == 'gross_sale' ? 'datatable__cell--sorted' : '']">
                            <span>
                                {{ $t('LBL_GROSS_SALES') }} 
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'gross_sale'">
                                </i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('discount_amount')" v-bind:class="[
                            sortingBy == 'discount_amount' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_DISCOUNTS') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'discount_amount'"></i>
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
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('tax_charge')" v-bind:class="[
                            sortingBy == 'tax_charge' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_TAX') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'tax_charge'"></i>
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
                        <td class="datatable__cell"></td>
                        <td class="datatable__cell"></td>
                        <td class="datatable__cell"><span>{{ totalQuantity }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ Math.round(totalGrossSale) }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ Math.round(totalDiscount) }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ Math.round(totalReturnAmount) }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ Math.round(totalNetSale) }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ Math.round(totalTaxCharge) }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ Math.round(totalSale) }}</span></td>
                    </tr>
                    <tr class="datatable__row" v-for = "(item, key) in sortlisting" :key="key">
                        <td class="datatable__cell" >
                            <span>{{ item.op_product_name ? item.op_product_name : '' }}</span>
                        </td>
                        <td class="datatable__cell" v-if="item.op_product_variants != null &&  item.op_product_variants.includes('styles')">
                            <span v-for="(style,key) in JSON.parse(item.op_product_variants).styles" :key="key"> <span class="product-option">{{style}}<br></span>  </span>
                        </td>
                        <td class="datatable__cell" v-else>
                            <span> {{ $t('LBL_N_A') }} </span>
                        </td>
                        <td class="datatable__cell"> <span>{{ item.op_product_sku ? item.op_product_sku : '' }}</span> </td>
                        <td class="datatable__cell"> <span>{{ item.order_net_quantity ? item.order_net_quantity : 0 }}</span></td>

                        <td class="datatable__cell">
                            <span>{{ symbol }} {{ item.gross_sale ? item.gross_sale : 0 }}</span>
                        </td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ item.discount_amount ? item.discount_amount : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ item.return_amount ? item.return_amount : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ item.net_sale ? item.net_sale : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ item.tax_charge ? item.tax_charge : 0 }}</span></td>
                        <td class="datatable__cell">
                            <span>{{ symbol }}{{ item.total_sale ? item.total_sale : 0 }}</span>
                        </td>
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
                    'Product Name': 'op_product_name',
                    'Variant Title':  {
                        field: 'op_product_variants',
                        callback: (value) => {
                            if(JSON.parse(value).styles) {
                                var variantName = '';
                                let val = JSON.parse(value).styles;
                                Object.keys(val).forEach(function(key) {
                                    variantName += `${val[key]} \n`;
                                });
                                return `${variantName}`;
                            }
                        }
                    },
                    'Net Quantity': 'order_net_quantity',
                    'Gross Sale': 'gross_sale',
                    'Discounts': 'discount_amount',
                    'Returns' : 'return_amount',
                    'Net Sales': 'net_sale',
                    'Tax': 'tax_charge',
                    'Total sales': 'total_sale',
                },
                exportData: [],
                summary:{},
                loading: false,
                symbol: '',
                sortingType: 'asc',
                sortingBy: 'op_product_name',
                type: 'saleReportByProductVariant'
            }
        },
        methods: {
            searchRecords: function(date) {
                this.searchData.dateRange = date;
                this.getSaleByProductVariant();
            },
            searchProducts: function() {
                this.getSaleByProductVariant();
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
            getSaleByProductVariant : function() {
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
                    this.records = [...Object.values(response.data.data['totalProducts'])];
                    this.summary = [];
                    this.exportData = this.records;
                    this.searchData.dateRange = response.data.data['dateRange'];
                    this.loading = false;
                    this.emitExportFieldsToParent();
                    this.emitExportDataToParent();
                    this.emitDateRange();
                });
            },
            printSaleByProductVariant() {
                this.$htmlToPaper('saleByProductVariant');
            },
            sortBy: function (type) {
                if (this.customSearch == 1) {
                    this.sortingBy = "";
                    this.sortingType = "";
                    return false;
                }
                if (this.sortingType == "" || this.sortingType == "desc" || this.sortingBy != type) {
                    this.sortingType = "asc";
                } else {
                    this.sortingType = "desc";
                }
                this.sortingBy = type;
                this.getSaleByProductVariant();
            },
        },
        mounted: function() {
            if(installationDate != '') {
                this.$emit('installationDate', installationDate);
            }
            this.symbol = currencySymbol;
            this.searchData.dateRange = this.dateRange;
            this.getSaleByProductVariant();
        },
        computed:{
            sortlisting: function() {
                if (this.sortingBy == "op_product_name") {
                    return this.records.sort((a,b) => {
                    let modifier = 1;
                        if(this.sortingType === 'desc') modifier = -1;
                        if((a[this.sortingBy]).toUpperCase() < (b[this.sortingBy].toUpperCase())) return -1 * modifier;
                        if((a[this.sortingBy].toUpperCase()) > (b[this.sortingBy].toUpperCase())) return 1 * modifier;
                        return 0;
                    });
                } else {
                    return this.records.sort((a,b) => {
                    let modifier = 1;
                        if(this.sortingType === 'desc') modifier = -1;
                        if(a[this.sortingBy] < b[this.sortingBy]) return -1 * modifier;
                        if(a[this.sortingBy] > b[this.sortingBy]) return 1 * modifier;
                        return 0;
                    });
                }
            },
            totalQuantity: function () {
                return this.records.reduce((acc, item) => acc + (item.order_net_quantity ? Number(item.order_net_quantity) : 0), 0);
            },
            totalGrossSale: function () {
                return  this.records.reduce((acc, item) => acc + (item.gross_sale ? Number(item.gross_sale) : 0), 0);
            },
            totalReturnAmount: function () {
                return  this.records.reduce((acc, item) => acc + (item.return_amount ? Number(item.return_amount) : 0), 0);
            },
            totalNetSale: function () {
                return  this.records.reduce((acc, item) => acc + (item.net_sale ? Number(item.net_sale) : 0), 0);
            },
            totalTaxCharge: function () {
                return  this.records.reduce((acc, item) => acc + (item.tax_charge ? Number(item.tax_charge) : 0), 0);
            },
            totalSale: function () {
                return  this.records.reduce((acc, item) => acc + (item.total_sale ? Number(item.total_sale) : 0), 0);
            },
            totalDiscount: function () {
                return  this.records.reduce((acc, item) => acc + (item.discount_amount ? Number(item.discount_amount) : 0), 0);
            }
        }
    };
</script>