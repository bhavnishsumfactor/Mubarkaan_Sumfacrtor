<template>
    <div class="col-xl-6">
        <div class="portlet portlet--height-fluid portlet-no-min-height">
            <div class="portlet__head">
                <div class="portlet__head-label">
                    <h3 class="portlet__head-title">{{$t('LBL_RECENT_ORDERS')}}</h3>
                </div>
                <div class="portlet__head-toolbar" v-if="orders.length > 0">
                    <router-link :to="{name: 'orders'  }" class="btn btn-outline-secondary btn-label-brand btn-bold btn-sm">
                        {{ $t("BTN_VIEW_ORDERS") }}
                    </router-link>
                </div>
            </div>
            <div class="portlet__body">
                <div class="widget11" v-if="orders.length > 0">
                    <div class="table-responsive">
                        <table class="table js--table-scrollable">
                            <thead>
                                <tr>
                                    <td>{{$t('LBL_ORDER_ID')}}</td>
                                    <td>{{$t('LBL_CUSTOMER_NAME')}}</td>
                                    <td>{{$t('LBL_ORDER_AMOUNT')}}</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="record in records" :key="record.user_id">
                                    <td>
                                        <router-link :to="{name: 'orderDetail', params: {id: record.order_id}}">#{{record.order_id}}</router-link>
                                    </td>
                                    <td>{{ (record.user != null && record.user.user_name)  ? record.user.user_name  : ''}}</td>
                                    <td>{{ record.order_net_amount ? currencySymbol + record.order_net_amount : ''}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <no-records-found image="admin-no-orders.svg" maxWidth="150" heading="" subHeading="Waiting for the money to roll in!" v-else ></no-records-found>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['orders'],
    data: function() {
        return {
            records: [],
            type : "resentOrders",
            currencySymbol: currencySymbol
        }
    },
    methods: {
        setData: function(response) {
            this.records = response;
        }
    },
    mounted: function() {
        this.setData(this.orders);
    },
}
</script>