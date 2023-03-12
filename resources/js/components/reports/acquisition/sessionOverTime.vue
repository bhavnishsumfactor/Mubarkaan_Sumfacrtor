<template>
    <div>
        <div class="p-4 YK-saleOverTime">
            <vue-chartist ratio="" type="Bar" :data="chartData" :options="options"></vue-chartist>
        </div>
        <hr>
        <div class="datatable datatable--default datatable--brand datatable--loaded" id="sessionOverTimeReport" v-if="records.length > 0">
            <table class="datatable__table">
                <thead class="datatable__head datatable__head-fixed">
                    <tr class="datatable__row">
                        <th class="datatable__cell datatable__cell--sort"><span>{{ $t('LBL_DAY') }}</span></th>
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
                        <td class="datatable__cell"><span>{{ $t('LBL_SUMMARY') }} </span></td>
                        <td class="datatable__cell"><span>{{ countVisitor }}</span></td>
                        <td class="datatable__cell"><span>{{ countSession }}</span></td>
                    </tr>
                    <tr class="datatable__row" v-for="(item, key) in records" :key="key">
                        <td class="datatable__cell"><span>{{ item.date }}</span></td>
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
                'Day': 'date',
                'Visitors': 'visitors',
                'Sessions': 'sessions'
            },
            exportData: [],
            baseUrl: baseUrl,
            searchData: searchFields,
            orderDetail: {},
            records: [],
            summary: {},
            loading: false,
            sortingType: '',
            sortingBy: '',
            type:'sessionOverTime'
        }
    },
    methods: {
        searchRecords: function(date) {
            this.searchData.dateRange = date;
            this.sessionOverTime();
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
        printSessionOverTimeReport() {
            this.$htmlToPaper('sessionOverTimeReport');
        },
        sessionOverTime : function() {
            this.loading = true;
            let formData = this.$serializeData(this.searchData);
            formData.append("sorting-by", this.sortingBy);
            formData.append("sorting-type", this.sortingType);
            formData.append('type', this.type);
            this.$http.post(adminBaseUrl + '/reports/get-acquisiton-report',  formData).then((response) => {
                if (response.data.status == false) {
                    this.loading = false;
                    toastr.error(response.data.message, '', toastr.options);
                    return;
                }
                this.records = response.data.data['data'];
                this.exportData = response.data.data['data'];
                this.chartData.labels = response.data.data['labels'];
                this.chartData.series = [];
                this.summary = response.data.data['summary'];
                this.chartData.series.push(response.data.data['series']);
                this.searchData.dateRange = response.data.data['dateRange'];
                this.searchData.endDate = response.data.data['dateRange'][1];
                this.loading = false;
                this.emitExportFieldsToParent();
                this.emitExportDataToParent();
                this.emitDateRange();
                this.$emit('checkCredentialFile', response.data.data['serviceFileExists']);
            });
        },
        sortBy: function (type) {
            if (this.sortingType == "" || this.sortingType == "desc" || this.sortingBy != type) {
                this.sortingType = "asc";
            } else {
                this.sortingType = "desc";
            }
            this.sortingBy = type;
            this.sessionOverTime();
        }
    },
    mounted: function() {
        if(installationDate != '') {
            this.$emit('installationDate', installationDate);
        }
        if (this.dateRange != undefined)
        {
            this.searchData.dateRange = this.dateRange;
        }
        this.sessionOverTime();
    },
    computed :{
        countSession: function () {
            return  this.records.reduce((acc, item) => acc + item.sessions, 0);
        },
        countVisitor: function () {
            return  this.records.reduce((acc, item) => acc + parseInt(item.visitors), 0);
        }
    }
}
</script>