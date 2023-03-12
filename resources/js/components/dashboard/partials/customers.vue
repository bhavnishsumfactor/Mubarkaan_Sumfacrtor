<template>
    <div class="portlet portlet--height-fluid portlet-no-min-height">
        <div class="portlet__head">
            <div class="portlet__head-label">
                <h3 class="portlet__head-title">{{ $t('LBL_RECENT_CUSTOMERS') }}</h3>
            </div>
            <div class="portlet__head-toolbar"  v-if="customers.length > 0">
                <router-link :to="{name: 'users'  }" class="btn btn-outline-secondary btn-label-brand btn-bold btn-sm">
                    {{ $t("BTN_VIEW_CUSTOMERS") }}
                </router-link>
            </div>
        </div>
        <div class="portlet__body">
            <div class="widget11"  v-if="customers.length > 0">
                <div class="table-responsive">
                    <table class="table js--table-scrollable">
                        <thead>
                            <tr>
                                <td>{{ $t('LBL_CUSTOMER_NAME') }}</td>
                                <td>{{ $t('LBL_CUSTOMER_EMAIL') }}</td>
                                <td>{{ $t('LBL_CUSTOMER_PHONE') }}</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="record in records" :key="record.user_id">
                                <td>{{ record.user_name ? record.user_name : ''}}</td>
                                <td>{{ record.user_email  ? record.user_email  : ''}}</td>
                                <td>{{ record.user_phone ? record.user_phone : ''}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
                <no-records-found image="admin-no-customer.svg" maxWidth="150" heading="" subHeading="Still waiting for our very first customer!" v-else ></no-records-found>

        </div>
    </div>
</template>
<script>
export default {
    props: ['customers'],
    data: function() {
        return {
            records: [],
            type : "resentCustomers"
        }
    },
    methods: {
        getNewCustomers: function() {
            this.$http.get(adminBaseUrl + '/getDashboardReportsData/' + this.type) 
            .then((response) => { 
                this.setData(response.data.data);
            }, (response) => {
                //error
            });
        },
        setData: function(response) {
            this.records = response;
        }
    },
    mounted: function() {
        this.setData(this.customers);
    },
}
</script>