<template>
    <div v-if="$canRead('VIEW_DASHBOARD')">
        <div class="subheader" id="subheader">
            <div class="container">
                <div class="subheader__main">
                    <h3 class="subheader__title">{{ $t("LBL_DASHBOARD") }}</h3>
                    <div class="subheader__breadcrumbs">
                        <router-link :to="{name: 'dashboard'}" class="subheader__breadcrumbs-home">
                            <i class="flaticon2-shelter"></i>
                        </router-link>
                        <span class="subheader__breadcrumbs-separator"></span>
                        <span class="subheader__breadcrumbs-link">{{
                        $t("LBL_DASHBOARD")
                        }}</span>
                    </div>
                </div>
                <div class="subheader__toolbar">
                <div class="subheader__wrapper">
                    <div
                    v-if="progress < 100"
                    class="header__topbar-item"
                    @click="openGetStarted"
                    >
                    <div class="header__topbar-wrapper">
                        <span class="header__topbar-icon">
                        <i class="fas fa-tasks"></i>
                        </span>
                    </div>
                    </div>
                    <router-link
                    :to="{ name: 'productCreate' }"
                    v-bind:class="[(!$canWrite('ADD_PRODUCTS')) ? 'disabledbutton': '', 'btn btn-white subheader__btn-options']"
                    >
                    {{ $t("BTN_ADD_PRODUCTS") }}
                    </router-link>
                </div>
                </div>
            </div>
        </div>

        <div class="container grid__item grid__item--fluid">
            <div class="row">
                <div class="col-xl-5">
                    <TotalSale v-if="records != ''" :sales="records.todaySaleRecords"/>
                </div>
                <div class="col-xl-7">
                    <TotalOrders v-if="records != ''" :orders="records.todayOrderRecords"/>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-5">
                <Profit v-if="records != ''" :profit="records.todayProfitRecords"/>
                </div>
                <div class="col-xl-7">
                <Customers v-if="records != ''" :customers="records.resentCustomers"/>
                </div>
            </div>
            <div class="row">
                <Orders v-if="records != ''" :orders="records.resentOrders"/>
                 <Inventory v-if="records != ''" :inventory="records.inventory"/>
            </div>
            <div class="row">
                 <ReturnRequest v-if="records != ''" :returnOrders="records.returnOrders" :type="'Return'"/>
                <ReturnRequest v-if="records != ''" :returnOrders="records.cancelOrders" :type="'Cancellation'"/>
            </div>
        </div>
    </div>
    <div v-else>
        <Dashboard />
    </div>
</template>
<script>
import totalSale from "./partials/totalSale";
import totalOrders from "./partials/totalOrders";
import customers from "./partials/customers";
import orders from "./partials/orders";
import returnRequest from "./partials/returnRequests";
import inventory from "./partials/inventory";
import profit from "./partials/profit";
import Dashboard from './subadmin';
export default {
    components: {
        TotalSale: totalSale,
        TotalOrders: totalOrders,
        Customers: customers,
        Orders: orders,
        ReturnRequest: returnRequest,
        Inventory: inventory,
        Profit: profit,
        Dashboard : Dashboard
    },
    data: function () {
        return {
            progress: 100,
            baseUrl: baseUrl,
            step:0,
            records: []
        };
    },
  methods: {
    getSystemStatus: function () {
      this.$http.get(adminBaseUrl + "/system/status").then((response) => {
        this.progress = response.data.data.statuses.progress;
      });
    },
    getDashboardData: function () {
        this.$http.get(adminBaseUrl + "/dashboard-data").then((response) => {
            this.records = response.data.data;
        });
    },
    openGetStarted: function () {
        this.$router.push({ name: "getStarted" });
    },
  },
  mounted: function () {
    this.getSystemStatus();
    this.getDashboardData();
  },
};
</script>
