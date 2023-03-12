<template>
    <div class="col-xl-6">
        <div class="portlet portlet--height-fluid portlet-no-min-height">
            <div class="portlet__head">
                <div class="portlet__head-label">
                    <h3 class="portlet__head-title">{{$t('LBL_INVENTORY')}}</h3>
                </div>
                <div class="portlet__head-toolbar" v-if="inventory.length > 0">
                    <router-link :to="{path: 'products/alerts' }" class="btn btn-outline-secondary btn-label-brand btn-bold btn-sm">
                        {{ $t("BTN_VIEW_ALL") }}
                    </router-link>
                </div>
            </div>
            <div class="portlet__body">
                <div class="widget11" v-if="inventory.length > 0">
                    <div class="table-responsive">
                        <table class="table js--table-scrollable">
                            <thead>
                                <tr>
                                    <td>{{$t('LBL_PRODUCTS_NAME')}}</td>
                                    <td>{{$t('LBL_QUANTITY')}}</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="record in records" :key="record.user_id">
                                    <td>{{ record.prod_name ? record.prod_name  : ''}}</td>
                                    <td>{{ record.qty  ? record.qty  : ''}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <no-records-found image="admin-low-inventory.svg" maxWidth="150" heading="" subHeading="We're all stocked up!" v-else ></no-records-found>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['inventory'],
    data: function() {
        return {
            records: [],
            type : "inventory",
            total: 0
        }
    },
    methods: {
        getReturnOrders: function() {
           
        },
        setData: function(response) {
            this.records = response;
       
        }
    },
    mounted: function() {
        this.setData(this.inventory);
    },
}
</script>