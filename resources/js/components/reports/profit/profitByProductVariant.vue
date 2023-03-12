<template>
    <div>
        <div class="datatable datatable--default datatable--brand datatable--loaded" id="profitByProductVariant" v-if="records.length > 0">
            <table class="datatable__table">
                <thead class="datatable__head datatable__head-fixed">
                    <tr class="datatable__row">
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('op_product_name')" v-bind:class="[
                            sortingBy == 'op_product_name' ? 'datatable__cell--sorted' : '']">
                            <span>
                                {{ $t('LBL_PRODUCTS_NAME') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'op_product_name'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('op_product_type')" v-bind:class="[
                            sortingBy == 'op_product_type' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_PRODUCTS_TYPE') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'op_product_type'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort">
                            <span>
                                {{ $t('LBL_VARIANT_TITLE') }}
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('order_net_quantity')" v-bind:class="[
                            sortingBy == 'order_net_quantity' ? 'datatable__cell--sorted' : '']">
                            <span>
                                {{ $t('LBL_NET_QUANTITY') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'order_net_quantity'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('net_sale')" v-bind:class="[
                            sortingBy == 'net_sale' ? 'datatable__cell--sorted' : '']">
                            <span>
                                {{ $t('LBL_NET_SALES') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'net_sale'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('cost')" v-bind:class="[
                            sortingBy == 'cost' ? 'datatable__cell--sorted' : '']">
                            <span>
                                {{ $t('LBL_COST') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'cost'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('gross_percent')" v-bind:class="[
                            sortingBy == 'gross_percent' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_GROSS_MARGIN')+' %' }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'gross_percent'"></i>
                            </span></th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('gross_profit')" v-bind:class="[
                            sortingBy == 'gross_profit' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_GROSS_PROFIT') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'gross_profit'"></i>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="datatable__body">
                    <tr class="datatable__row datatable__row-highlighted">
                        <td class="datatable__cell"><span>{{ $t('LBL_SUMMARY') }}</span></td>
                        <td class="datatable__cell"><span></span></td>
                        <td class="datatable__cell"><span></span></td>
                        <td class="datatable__cell"><span>{{ totalQuantity }}</span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ Math.round(totalNetSale) }} </span></td>
                        <td class="datatable__cell"><span>{{ symbol }}{{ Math.round(totalCost) }} </span></td>
                        <td class="datatable__cell"></td>
                        <td class="datatable__cell">{{ symbol }}  {{ Math.round(totalGrossProfit) }}</td>
                    </tr>
                    <tr class="datatable__row" v-for = "(item, key) in sortlisting" :key="key">
                        <td class="datatable__cell"><span>{{ item.op_product_name ? item.op_product_name : '' }}</span></td>
                        <td class="datatable__cell">
                            <span>
                                <span class="badge  badge--dot" v-bind:class="[item.op_product_type == 1 ? 'badge--danger' : 'badge--success']"></span>&nbsp;
                                <span class="font-bold" v-bind:class="[item.op_product_type == 1 ? 'font-danger' : 'font-success']">{{ productTypes[item.op_product_type] }}</span>
                            </span>
                        </td>
                        <td class="datatable__cell" v-if="item.op_product_variants != null &&  item.op_product_variants.includes('styles')">
                            <span v-for="(style,key) in JSON.parse(item.op_product_variants).styles" :key="key"> {{style}} <br></span>
                        </td>
                        <td class="datatable__cell" v-else>
                            <span>{{ $t('LBL_N_A') }}</span>
                        </td>
                        <td class="datatable__cell"><span> {{ item.order_net_quantity ? item.order_net_quantity : 0 }}</span></td>
                        <td class="datatable__cell"><span> {{ symbol }}{{ item.net_sale ? item.net_sale : 0 }}</span></td>
                        <td class="datatable__cell"><span> {{ symbol }}{{ item.cost ? item.cost : 0 }}</span></td>
                        <td class="datatable__cell"><span> {{ item.gross_percent ? item.gross_percent : 0 }}</span></td>
                        <td class="datatable__cell"><span> {{ symbol }}{{ item.gross_profit ? item.gross_profit : 0 }}</span></td>
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
                productTypes: [],
                exportFields: {
                    'Product Name': 'op_product_name',
                    'Variant Title':  {
                        field: 'op_product_variants',
                        callback: (value) => {
                            if(JSON.parse(value).styles){
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
                    'Net Sales': 'net_sale',
                    'Cost': 'cost',
                    'Gross Margin': 'gross_percent',
                    'Gross Profit' : 'gross_profit',
                },
                exportData :[],
                summary:{},
                loading: false,
                type : 'profitByProductVariant',
                symbol: '',
                sortingType: '',
                sortingBy: ''
            }
        },
        methods: {
            searchRecords: function(date) {
                this.searchData.dateRange = date;
                this.getProfitData();
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
            getProfitData : function() {
                this.loading = true;
                let formData = this.$serializeData(this.searchData);
                formData.append("sorting-by", this.sortingBy);
                formData.append("sorting-type", this.sortingType);
                formData.append('type', this.type);
                this.$http.post(adminBaseUrl + '/reports/get-profit-by-product-report',formData ).then((response) => {
                    if (response.data.status == false) {
                        this.loading = false;
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    this.records = [...Object.values(response.data.data['totalProducts'])];
                    this.exportData  = this.records;
                    this.productTypes = response.data.data['types'];
                    this.searchData.dateRange = response.data.data['dateRange'];
                    this.loading = false;
                    this.emitExportFieldsToParent();
                    this.emitExportDataToParent();
                    this.emitDateRange();
                });
            },
            printSaleByProductVariant() {
                // Pass the element id here
                this.$htmlToPaper('profitByProductVariant');
            },
            sortBy: function (type) {
                if (this.sortingType == "" || this.sortingType == "desc" || this.sortingBy != type) {
                    this.sortingType = "asc";
                } else {
                    this.sortingType = "desc";
                }
                this.sortingBy = type;
                this.getProfitData();
            }
        },
        mounted: function() {
            if(installationDate != '') {
                this.$emit('installationDate', installationDate);
            }
            this.symbol = currencySymbol;
            this.searchData.dateRange = this.dateRange;
            this.getProfitData();
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
            totalNetSale : function() {
                return  this.records.reduce((acc, item) => acc + (item.net_sale ? Number(item.net_sale) : 0), 0);
            },
            totalCost : function() {
                return  this.records.reduce((acc, item) => acc + (item.cost ? Number(item.cost) : 0), 0);
            },
            totalCost : function() {
                return  this.records.reduce((acc, item) => acc + (item.cost ? Number(item.cost) : 0), 0);
            },
            totalGrossProfit : function() {
                return  this.records.reduce((acc, item) => acc + (item.gross_profit ? Number(item.gross_profit) : 0), 0);
            }
        }
    };
</script>