<template>
    <div>
        <div class="datatable datatable--default datatable--brand datatable--loaded" id="onlineSearchRecords" v-if="records.length > 0">
            <table class="datatable__table">
                <thead class="datatable__head datatable__head-fixed">
                    <tr class="datatable__row">
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('search_term')" v-bind:class="[
                            sortingBy == 'search_term' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_ORIGINAL_QUERY') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'search_term'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('total_count')" v-bind:class="[
                            sortingBy == 'total_count' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_TOTAL_SEARCHES') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'total_count'"></i>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="datatable__body">
                    <tr class="datatable__row datatable__row-highlighted">
                        <td class="datatable__cell"><span>{{ $t('LBL_SUMMARY') }}</span></td>
                        <td class="datatable__cell"><span>{{ totalResult }}</span></td>
                    </tr>
                    <tr class="datatable__row" v-for = "(item, key) in records" :key="key">
                        <td class="datatable__cell"><span>{{ item.search_term ? item.search_term : '' }}</span></td>
                        <td class="datatable__cell"><span>{{ item.total_count ? item.total_count : 0 }}</span></td>
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
        props: ['dateRange','type'],
        data: function() {
            return {
                baseUrl: baseUrl,
                searchData: searchFields,
                records: [],
                exportFields: {
                    'Original Query': 'search_term',
                    'Total Searches': 'total_count'
                },
                exportData :[],
                summary:{},
                loading: false,
                reqType : '',
                sortingType: '',
                sortingBy: ''
            }
        },
        methods: {
            searchRecords: function(date) {
                this.searchData.dateRange = date;
                this.getBehaviourReportData();
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
            getBehaviourReportData : function() {
                this.loading = true;
                let formData = this.$serializeData(this.searchData);
                formData.append("sorting-by", this.sortingBy);
                formData.append("sorting-type", this.sortingType);
                formData.append('type', this.reqType);
                this.$http.post(adminBaseUrl + '/reports/get-behavior-report', formData).then((response) => {
                    if (response.data.status == false) {
                        this.loading = false;
                        toastr.error(response.data.message, '', toastr.options);
                        return;
                    }
                    this.records = response.data.data['data'];
                    this.exportData = response.data.data['data'];
                    this.searchData.dateRange = response.data.data['dateRange'];
                    this.loading = false;
                    this.emitExportFieldsToParent();
                    this.emitExportDataToParent();
                    this.emitDateRange();
                });
            },
            printSearchRecords() {
                this.$htmlToPaper("onlineSearchRecords");
            },
            sortBy: function (type) {
                if (this.sortingType == "" || this.sortingType == "desc" || this.sortingBy != type) {
                    this.sortingType = "asc";
                } else {
                    this.sortingType = "desc";
                }
                this.sortingBy = type;
                this.getBehaviourReportData();
            }
        },
        mounted: function() {
            if(installationDate != '') {
                this.$emit('installationDate', installationDate);
            }
            this.reqType = this.type;
            if (this.dateRange != undefined)
            {
                this.searchData.dateRange = this.dateRange;
            }
            this.getBehaviourReportData();
        },
        computed :{
            totalResult: function () {
               return  this.records.reduce((acc, item) => acc + item.total_count, 0);
            }
        }
    };
</script>