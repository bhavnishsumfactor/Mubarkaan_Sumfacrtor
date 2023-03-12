<template>
    <b-modal size="lg" id="trackingInformation" centered title="BootstrapVue" hide-footer>
        <template v-slot:modal-header>
            <h5 class="modal-title">{{ $t('LBL_ORDERS_NUMBER') + ': '  }} {{ orderId }}  ({{ informations.tag }})</h5>
            <br>
            <p> {{ $t('LBL_ESTIMATED_DELIVERY') + ': ' }} {{ informations.shipment_delivery_date }} </p>
            <button type="button" class="close" @click="$bvModal.hide('trackingInformation')"></button>
        </template>
        <div v-if="informations.checkpoints && informations.checkpoints.length > 0">
            <perfect-scrollbar :style="'height: 400px'">
                <!--<loader v-if="informations.checkpoints && informations.checkpoints.length == 0"></loader>-->
                <ul class="timeline" id="timeline" >
                    <li :data-date="info.created_at  | formatDateTime" class="event" v-for="(info, key) in informations.checkpoints" :key="key">
                        <span>
                            <a class="" href="javascript:;">
                                {{ info.message }}
                            </a>
                            <p>{{ info.location}} {{ info.city }}  {{ info.state}}</p>
                        </span>
                    </li>  
                </ul>
            </perfect-scrollbar>
        </div>
        <div class="no-data-found no-data-found--sm" v-else>
            <div class="img">
                <img data-yk :src="baseUrl + '/dashboard/media/retina/no-reviews.svg'"/>
            </div>
            <div class="data">
                <h2>{{ $t("LBL_NO_DATA_FOUND") }}</h2>
            </div>
        </div>    
    </b-modal>
</template>
<script>

export default {
    props:['informations', 'orderId'],
    data: function() {
        return {
            clicked: 0,
            baseUrl: baseUrl
        };
    },
    methods: {
    }
};
</script>
