<template>
    <div class="col-xl-6">
        <div class="portlet portlet--height-fluid portlet-no-min-height">
            <div class="portlet__head">
                <div class="portlet__head-label">
                    <h3 class="portlet__head-title">{{type}}  {{$t('LBL_RETURN_REQUESTS')}}</h3>
                </div>
                <div class="portlet__head-toolbar" v-if="returnOrders.length > 0">
                    <router-link :to="{name: 'orders'  }" class="btn btn-outline-secondary btn-label-brand btn-bold btn-sm">
                       {{ $t("BTN_VIEW_ORDERS") }}
                    </router-link>
                </div>
            </div>
            <div class="portlet__body">
                <div class="widget11" v-if="returnOrders.length > 0">
                    <div class="table-responsive">
                        <table class="table js--table-scrollable">
                            <thead>
                                <tr>
                                    <td>{{$t('LBL_ORDER_ID')}}</td>
                                    <td>{{$t('LBL_CUSTOMER_NAME')}}</td>
                                    <td>{{type}} {{$t('LBL_RETURN_QUANTITY')}}</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="record in records" :key="record.user_id">
                                    <td>
                                        <router-link :to="{name: 'orderDetail', params: {id: record.orrequest_order_id}}">#{{record.orrequest_order_id}}</router-link>
                                    </td>
                                    <td>{{ (record.user != null && record.user.user_name)  ? record.user.user_name  : ''}}</td>
                                    <td>{{ record.orrequest_qty  ? record.orrequest_qty  : ''}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <no-records-found :image="(type == 'Return') ? 'admin-no-returns.svg' : 'admin-no-order-cancellation.svg'" maxWidth="150" heading="" :subHeading="(type == 'Return') ? returnMsg : cancellationMsg" v-else ></no-records-found>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['returnOrders', 'type'],
    data: function() {
        return {
            type : "returnOrders",
            returnMsg : "No return's, I think we're doing good here!",
            cancellationMsg : "No Cancellation's yet!",
            records:[]
        }
    },
    methods: {   
        setData: function(response) {
            this.records = response;
        }     
    },
    mounted: function() {
        this.setData(this.returnOrders);
    },
}
</script>