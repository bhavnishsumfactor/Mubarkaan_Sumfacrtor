<template>
    <div class="wizard-v2__nav">
        <div class="portlet portlet--height-fluid">
            <div class="portlet__body">
                <div class="wizard-v2__nav-items">
                    <!--doc: Replace A tag with SPAN tag to disable the step link click -->
                    <div class="wizard-v2__nav-item" data-FATbitwizard-type="step" :disabled="true"  @click="swichTab('general')" :class="{ 'current is-active': tab === 'general', 'is-completed': (!$route.params.id && ['price', 'options', 'attributes', 'media', 'digital'].indexOf(tab)!=-1) }">
                        <div class="wizard-v2__nav-body">
                            <div class="wizard-v2__nav-icon">                        
                                <svg width="30px" height="30px">
                                    <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#general-information'" :href="baseUrl+'/admin/images/retina/sprite.svg#general-information'"></use>
                                </svg>                    
                            </div>
                            <div class="wizard-v2__nav-label">
                                <div class="wizard-v2__nav-label-title">
                                    {{$t('LBL_PRODUCT_GENERAL_TITLE')}}
                                </div>
                                <div class="wizard-v2__nav-label-desc">
                                    {{$t('LBL_PRODUCT_GENERAL_CAPTION')}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wizard-v2__nav-item" data-FATbitwizard-type="step"  @click="swichTab('price')" :class="{ 'current is-active': tab === 'price', 'is-completed': (!$route.params.id && ['options', 'attributes', 'media', 'digital'].indexOf(tab)!=-1) }">
                        <div class="wizard-v2__nav-body">
                            <div class="wizard-v2__nav-icon">
                                <svg width="30px" height="30px">
                                    <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#inventory-price-details'" :href="baseUrl+'/admin/images/retina/sprite.svg#inventory-price-details'"></use>
                                </svg>  
                            </div>
                            <div class="wizard-v2__nav-label">
                                <div class="wizard-v2__nav-label-title">
                                    {{$t('LBL_PRODUCT_PRICE_TITLE')}}
                                </div>
                                <div class="wizard-v2__nav-label-desc">
                                    {{$t('LBL_PRODUCT_PRICE_CAPTION')}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wizard-v2__nav-item"  data-FATbitwizard-type="step" @click="swichTab('options')" :class="{'current is-active': tab === 'options', 'is-completed': (!$route.params.id && ['attributes', 'media', 'digital'].indexOf(tab)!=-1) }">
                        <div class="wizard-v2__nav-body">
                            <div class="wizard-v2__nav-icon">
                                <svg width="30px" height="30px">
                                    <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#options-variants'" :href="baseUrl+'/admin/images/retina/sprite.svg#options-variants'"></use>
                                </svg>  
                            </div>
                            <div class="wizard-v2__nav-label">
                                <div class="wizard-v2__nav-label-title">
                                    {{$t('LBL_PRODUCT_OPTION_TITLE')}}
                                </div>
                                <div class="wizard-v2__nav-label-desc">
                                    {{$t('LBL_PRODUCT_OPTION_CAPTION')}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wizard-v2__nav-item" data-FATbitwizard-type="step"  @click="swichTab('attributes')" :class="{'current is-active': tab === 'attributes', 'is-completed': (!$route.params.id && ['media', 'digital'].indexOf(tab)!=-1) }">
                        <div class="wizard-v2__nav-body">
                            <div class="wizard-v2__nav-icon">
                                <svg width="30px" height="30px">
                                    <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#product-attribute'" :href="baseUrl+'/admin/images/retina/sprite.svg#product-attribute'"></use>
                                </svg>  
                            </div>
                            <div class="wizard-v2__nav-label">
                                <div class="wizard-v2__nav-label-title">
                                    {{$t('LBL_PRODUCT_ATTRIBUTE_TITLE')}}
                                </div>
                                <div class="wizard-v2__nav-label-desc">
                                    {{$t('LBL_PRODUCT_ATTRIBUTE_CAPTION')}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wizard-v2__nav-item" data-FATbitwizard-type="step"  @click="swichTab('media')" :class="{ 'current is-active': tab === 'media', 'is-completed': (!$route.params.id && ['digital'].indexOf(tab)!=-1) }">
                        <div class="wizard-v2__nav-body">
                            <div class="wizard-v2__nav-icon">
                                <svg width="30px" height="30px">
                                    <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#product-media'" :href="baseUrl+'/admin/images/retina/sprite.svg#product-media'"></use>
                                </svg>  
                            </div>
                            <div class="wizard-v2__nav-label">
                                <div class="wizard-v2__nav-label-title">
                                    {{$t('LBL_PRODUCT_MEDIA_TITLE')}}
                                </div>
                                <div class="wizard-v2__nav-label-desc">
                                    {{$t('LBL_PRODUCT_MEDIA_CAPTION')}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wizard-v2__nav-item" data-FATbitwizard-type="step"  @click="swichTab('digital')" :class="{ 'current is-active' : tab === 'digital' }" v-if="type == 2">
                        <div class="wizard-v2__nav-body">
                            <div class="wizard-v2__nav-icon">
                                <i class="flaticon-confetti"></i>
                            </div>
                            <div class="wizard-v2__nav-label">
                                <div class="wizard-v2__nav-label-title">
                                    {{$t('LBL_PRODUCT_DIGITAL_TITLE')}}
                                </div>
                                <div class="wizard-v2__nav-label-desc">
                                    {{$t('LBL_PRODUCT_DIGITAL_CAPTION')}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="portlet__foot" v-if="selectedPage == 'editform'">
                <div class="logs__wrapper" >
                    <div class="logs-inner">
                        <span class="lable">{{ $t('LBL_CREATED')}}:</span> 
                        <span class="ml-auto"> {{ logs.created_at ? logs.created_at.admin_username+ ' |' : '' }}  <span class="time">{{ logs.prod_created_on |  formatDateTime}}</span> </span>
                    </div>        
                    <div class="logs-inner">
                        <span class="lable">{{ $t('LBL_LAST_UPDATED')}}:</span>
                        <span class="ml-auto"> {{ logs.updated_at ? logs.updated_at.admin_username+ ' |' : '' }} <span class="time">{{ logs.prod_updated_on | formatDateTime}}</span> </span>           
                    </div>
                </div>
            </div>
        </div>
     
    </div>
</template>
<script>

    export default {
        props:['tab', 'type', 'selectedPage', 'logs'],
        data: function () {
            return {
                baseUrl: baseUrl                
            }
        },
        methods: {
            swichTab:function(tab){
                let id = this.$route.params.id;
                if(id){
                  this.$emit('next',id, tab, this.type);
                }
            }            
        },
    }
</script>
