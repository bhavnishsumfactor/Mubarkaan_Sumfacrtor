<template>
    <div>
        <div class="datatable datatable--default datatable--brand datatable--loaded" id="sessionByLandingPage" v-if="records.length > 0">
            <table class="datatable__table">
                <thead class="datatable__head datatable__head-fixed">
                    <tr class="datatable__row">
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('users')" v-bind:class="[
                            sortingBy == 'users' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_LANDING_PAGE_TYPE') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'users'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort"><span>{{ $t('Landing page path') }}</span></th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('users')" v-bind:class="[
                            sortingBy == 'users' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_VISITORS') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'users'"></i>
                            </span>
                        </th>
                        <th class="datatable__cell datatable__cell--sort" @click="sortBy('sessions')" v-bind:class="[
                            sortingBy == 'sessions' ? 'datatable__cell--sorted' : '']">
                            <span>{{ $t('LBL_SESSIONS') }}
                                <i class="fas" v-bind:class="[sortingType == 'asc' ? 'fa-arrow-up' : 'fa-arrow-down']" v-if="sortingBy == 'sessions'"></i>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="datatable__body">
                    <tr class="datatable__row datatable__row-highlighted">
                        <td class="datatable__cell"><span>{{ $t('LBL_SUMMARY') }}</span></td>
                        <td class="datatable__cell"><span></span></td>
                        <td class="datatable__cell"><span>{{ totalSession }}</span></td>
                        <td class="datatable__cell"><span>{{ totalVisitor }}</span></td>
                    </tr>
                    <tr class="datatable__row" v-for = "(item, key) in records" :key="key">
                        <td class="datatable__cell"><span>{{ item.page_type.length > 100 ? item.page_type.substring(0,50)+"...." : item.page_type }}</span></td>
                        <td class="datatable__cell"><span>{{ item.landing_page.length > 100 ? item.landing_page.substring(0,50)+"...." : item.landing_page }}</span></td>
                        <td class="datatable__cell"><span>{{ item.visitors ? item.visitors : 0 }}</span></td>
                        <td class="datatable__cell"><span>{{ item.sessions ? item.sessions : 0 }}</span></td>
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
                    'Landing page type': 'page_type',
                    'Landing page path': 'landing_page',
                    'Visitors' : 'visitors',
                    'Sessions' : 'sessions'
                },
                exportData :[],
                summary:{},
                loading: false,
                type : 'sessionByLandingPage',
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
                formData.append('type', this.type);
                this.$http.post(adminBaseUrl + '/reports/get-behavior-report', formData ).then((response) => {
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
            printSessionByLandingPage() {
                this.$htmlToPaper('sessionByLandingPage');
            },
            sortBy: function (type) {
                if (this.sortingType == "" || this.sortingType == "desc" || this.sortingBy != type) {
                    this.sortingType = "asc";
                } else {
                    this.sortingType = "desc";
                }
                this.sortingBy = type;
                this.getBehaviourReportData();
            },
            
        },
        mounted: function() {
            if(installationDate != '') {
                this.$emit('installationDate', installationDate);
            }
            this.searchData.dateRange = this.dateRange;
            this.getBehaviourReportData();
        },
        computed :{
            totalSession: function () {
               return  this.records.reduce((acc, item) => acc + item.sessions, 0);
            },
            totalVisitor : function () {
               return  this.records.reduce((acc, item) => acc + parseInt(item.visitors), 0);
            },
        }
    };
</script>