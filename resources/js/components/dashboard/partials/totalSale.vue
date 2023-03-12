<template>
    <div class="portlet portlet--height-fluid portlet--tabs portlet-no-min-height">
        <div class="portlet__head">
            <div class="portlet__head-label">
                <h3 class="portlet__head-title">{{$t('LBL_TOTAL_SALES')}}</h3>
            </div>
            <div class="portlet__head-toolbar">
                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" @click="getSalesRecords(today)" data-toggle="tab" href="#widget11_tab1_content" role="tab">
                            {{ $t('LNK_TODAY') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" @click="getSalesRecords(weekly)" data-toggle="tab" href="#widget11_tab1_content" role="tab">
                            {{ $t('LNK_WEEK') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" @click="getSalesRecords(currentMonth)" data-toggle="tab" href="#widget11_tab1_content" role="tab">
                            {{ $t('LNK_THIS_MONTH') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" @click="getSalesRecords(lastMonth)" data-toggle="tab" href="#widget11_tab1_content" role="tab">
                            {{ $t('LNK_LAST_MONTH') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" @click="getSalesRecords(currentYear)" data-toggle="tab" href="#widget11_tab2_content" role="tab">
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
                            <div class="col"><span class="widget26__number">{{currencySymbol}} {{ totalSale | numberFormat }} </span></div>
                            <div class="col-auto">
                                <span class="widget26__cents" v-bind:class="[salePercentage == 0 ? 'font-warning ' : '', salePercentage > 0 ? 'font-success' : '', salePercentage < 0 ? 'font-danger':'']">
                                    <i class="la " v-if="salePercentage != 0" v-bind:class="[salePercentage > 0 ? 'la-arrow-up' : 'la-arrow-down']"></i> {{ salePercentage | numberFormat }}%</span>
                            </div>
                        </div>
                        <div class="row align-items-center justify-content-between">
                            <div class="col"><span class="widget26__desc">{{$t('LBL_TOTAL_SALES')}}  
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
    //import { Cartesian, Line } from 'laue'
    export default {
        props: ['sales'],
        components: {
            'vue-chartist': VueChartist
            //LaCartesian: Cartesian,
            //LaLine: Line
        },
        data: function() {
            return {
                currencySymbol: currencySymbol,
                fromDate: '',
                toDate: '',
                today: 'today',
                weekly: 'weekly',
                lastMonth: 'lastMonth',
                currentMonth: 'currentMonth',
                currentYear: 'currentYear',
                salePercentage: 0,
                salePercentageStatus: 1,
                totalSale: 0,
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
                }
            }
        },
        methods: {
            getSalesRecords: function(type) {
                this.$http.get(adminBaseUrl + '/getTotalSalesByType/' + type)
                    .then((response) => { //success
                        this.setData(response.data.data);
                    }, (response) => { //error
                    });
            },
            setData: function(response) {
                this.chartData.series = [];
                this.chartData.series.push(response.data);
                this.chartData.labels = response.labels;
                this.totalSale = response.total;
                this.salePercentage = response.percentage;
                this.salePercentageStatus = response.salePercentageStatus;
                this.fromDate = response.fromDate;
                this.toDate = response.toDate;
            }
        },
        mounted: function() {
            this.setData(this.sales);
            //this.getSalesRecords(this.today);
            /*$(document).on("mouseover", ".fa-question-circle", function() {
                $(this).tooltip('show');
            });
            $(document).on("mouseleave", ".fa-question-circle", function() {
                $(this).tooltip('hide');
            });*/
        },
    }
</script>