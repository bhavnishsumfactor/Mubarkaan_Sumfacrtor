<template>
    <div class="portlet portlet--height-fluid portlet--tabs portlet-no-min-height">
        <div class="portlet__head">
            <div class="portlet__head-label">
                <h3 class="portlet__head-title">{{$t('LBL_TOTAL_ORDERS')}}</h3>
            </div>
            <div class="portlet__head-toolbar">
                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" @click="getOrderCounts(today)" data-toggle="tab" href="#widget11_tab1_content" role="tab">
                             {{ $t('LNK_TODAY') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" @click="getOrderCounts(weekly)" data-toggle="tab" href="#widget11_tab1_content" role="tab">
                           {{ $t('LNK_WEEK') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" @click="getOrderCounts(currentMonth)" data-toggle="tab" href="#widget11_tab1_content" role="tab">
                            {{ $t('LNK_THIS_MONTH') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" @click="getOrderCounts(lastMonth)" data-toggle="tab" href="#widget11_tab1_content" role="tab">
                            {{ $t('LNK_LAST_MONTH') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" @click="getOrderCounts(currentYear)" data-toggle="tab" href="#widget11_tab2_content" role="tab">
                            {{ $t('LNK_THIS_YEAR') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="portlet__body">
            <div class="tab-content">
                <div class="widget26">
                    <div class="widget26__content">
                        <div class="row align-items-center justify-content-between">
                            <div class="col"><span class="widget26__number">{{ totalOrder ? totalOrder : 0 }} </span></div>
                           
                            <div class="col-auto">
                                <span class="widget26__cents" v-bind:class="[orderPercentage == 0 ? 'font-warning ' : '', orderPercentage > 0 ? 'font-success' : '', orderPercentage < 0 ? 'font-danger':'']">
                                    <i class="la " v-if="orderPercentage != 0" v-bind:class="[orderPercentage > 0 ? 'la-arrow-up' : 'la-arrow-down']"></i> {{ orderPercentage | numberFormat }}%</span>
                            </div>
                        </div>

                        <div class="row align-items-center justify-content-between">
                            <div class="col"><span class="widget26__desc">{{$t('LBL_TOTAL_ORDERS')}} 
                                </span> 
                            </div>
                            <div class="col-auto">
                                <router-link :to="{name: 'saleReport' }" class="link">
                                     {{ $t("BTN_VIEW_REPORT") }}
                                </router-link>
                            </div>
                        </div>
                        <div class="row align-items-center justify-content-between">
                            <div class="col"></div>
                            <div class="col-auto">
                                <p> {{ fromDate }} - {{ toDate }}</p>
                            </div>
                        </div>
                        <div id="chart">
                            <vue-chartist ratio="" type="Line" :data="chartData" :options="options">
                            </vue-chartist>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</template>
<script>
    import VueChartist from 'v-chartist'; 
    export default {
        props: ['orders'],
        components: {
            'vue-chartist': VueChartist
        },
        data: function() {
            return {
                fromDate: '',
                toDate: '',
                today: 'today',
                weekly: 'weekly',
                lastMonth: 'lastMonth',
                currentMonth: 'currentMonth',
                currentYear: 'currentYear',
                totalOrder: 0,
                orderPercentageStatus: 0,
                orderPercentage: 0,
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
                        onlyInteger: true,
                        showGrid: true,
                        offset: 20
                    },
                    axisX: {
                        showGrid: true
                    },
                    plugins: [
                        // this.$chartist.plugins.tooltip()
                    ]
                },
            }
        },
        methods: {
            getOrderCounts: function(type) {
                this.$http.get(adminBaseUrl + '/getTotalOrdersByType/' + type)
                    .then((response) => { //success
                        this.setData(response.data.data);
                    }, (response) => { //error
                });
            },
            setData: function(response) {
                this.chartData.series = [];
                this.chartData.series.push(response.data);
                this.fromDate = response.fromDate;
                this.totalOrder = response.total;
                this.toDate = response.toDate;
                this.chartData.labels = response.labels;
                this.orderPercentage = response.orderPercentage;
                this.orderPercentageStatus = response.orderPercentageStatus;
            }
        },
        mounted: function() {
            this.setData(this.orders);
        },
    }
</script>