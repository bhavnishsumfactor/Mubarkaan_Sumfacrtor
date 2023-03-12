<template>
  <!-- begin:: Aside  -->
  <div class="aside aside--fixed" id="aside" data-close-on-click-outside="aside">
	<!-- begin:: Aside Menu -->
	<div class="aside-menu-wrapper" id="aside_menu_wrapper">
		<div id="aside_menu" class="aside-menu">
			<perfect-scrollbar :style="'height: 100vh'">
				<ul class="menu__nav" id="accordionMenuLeftSidebar" role="tablist">
					<li class="menu__section" v-if="languages.length > 0">
						<h4 class="menu__section-text">{{$t('LBL_LANGUAGE')}}</h4> </li>
                            <li class="menu__item" v-bind:class="[ admin_lang == lang_code ? activeClass : '']" v-for="(lang_name, lang_code) in languages" :key="lang_code">
                                <a href="javascript:;" class="menu__link" @click="switchLanguage(lang_code)"> <i class="menu__link-icon">
                                <img :src="baseUrl + '/flags/'+ lang_code + '.svg'" alt width="20px" />
                                </i> <span class="menu__link-text">{{lang_name}}</span> </a>
                            </li>
					    <hr v-if="(($canRead('LOCALIZATION') || $canRead('TAX_SETTINGS') || $canRead('INVOICE_MANAGEMENT') || $canRead('SHIPPING_FULFILMENT') || $canRead('SHIPPING_ZONE_RATE') || $canRead('PICKUP_ADDRESS') || $canRead('RETURN_AND_CANCELLATION') || $canRead('PAYMENT_METHODS') || $canRead('PRODUCT_SETTINGS') || $canRead('SYSTEM_SETTINGS') || $canRead('THIRD_PARTY_INTEGRATION') || $canRead('IMPORT_EXPORT')) && (languages.length > 0)) "/>
                            <li class="menu__section mt-3" v-if="$canRead('LOCALIZATION') || $canRead('TAX_SETTINGS') || $canRead('INVOICE_MANAGEMENT') || $canRead('SHIPPING_FULFILMENT') || $canRead('SHIPPING_ZONE_RATE') || $canRead('PICKUP_ADDRESS') || $canRead('RETURN_AND_CANCELLATION') || $canRead('PAYMENT_METHODS') || $canRead('PRODUCT_SETTINGS') || $canRead('SYSTEM_SETTINGS') || $canRead('THIRD_PARTY_INTEGRATION') || $canRead('IMPORT_EXPORT')">
                                <h4 class="menu__section-text">{{$t('LBL_SETTINGS')}}</h4> 
                            </li>
                            
					    <li class="menu__item" v-if="$canRead('LOCALIZATION')">
						    <a class="menu__link" v-b-toggle.collapse-1 data-parent="#accordionMenu" href="javascript:;"> 
                                <i class="menu__link-icon"> 
                                    <svg class="svg-icon">
                                        <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#localozation'" :href="baseUrl+'/admin/images/retina/sprite.svg#localozation'"></use>
                                    </svg>
                                </i> 
                                <span class="menu__link-text">{{$t('LBL_LOCALIZATION_SETTING')}}</span> 
                                    <i class="menu__ver-arrow"></i> 
                            </a>
						<b-collapse id="collapse-1" accordion="my-accordion" class="menu__submenu" :visible="(currentPage.includes('/localization/bussiness-management') || currentPage.includes('/localization/settings') || currentPage.includes('/currencies') || currentPage.includes('/languages'))">
							<ul class="menu__subnav">
                                <li class="menu__item" v-bind:class="[currentPage.includes('/localization/bussiness-management') ? activeClass : '']">
									<router-link :to="{name: 'businessManagement'}" class="menu__link" data-target-close="aside"> <i class="menu__link-bullet menu__link-bullet--dot">
                                    <span></span>
                                    </i> <span class="menu__link-text">{{ $t('LBL_BUSINESS_INFORMATION') }}</span> </router-link>
								</li>
								<li class="menu__item" v-bind:class="[currentPage.includes('/localization/settings') ? activeClass : '']">
									<router-link :to="{name: 'localizationGeneral'}" class="menu__link" data-target-close="aside"> <i class="menu__link-bullet menu__link-bullet--dot">
                                    <span></span>
                                    </i> <span class="menu__link-text">{{ $t('LBL_DATE_TIME_UNITS') }}</span> </router-link>
								</li>
								<li class="menu__item" v-bind:class="[currentPage.includes('/currencies') ? activeClass : '']">
									<router-link :to="{name: 'currencies'}" class="menu__link" data-target-close="aside"> <i class="menu__link-bullet menu__link-bullet--dot">
                                    <span></span>
                                    </i> <span class="menu__link-text">{{ $t('LBL_CURRENCIES') }}</span> </router-link>
								</li>
								<li class="menu__item" v-bind:class="[currentPage.includes('/languages') ? activeClass : '']">
									<router-link :to="{name: 'languages'}" class="menu__link" data-target-close="aside"> <i class="menu__link-bullet menu__link-bullet--dot">
                                    <span></span>
                                    </i> <span class="menu__link-text">{{ $t('LBL_LANGUAGES') }}</span> </router-link>
								</li>
							</ul>
						</b-collapse>
					</li>
					<li class="menu__item" v-if="$canRead('TAX_SETTINGS') || $canRead('INVOICE_MANAGEMENT')">
						<a class="menu__link" v-b-toggle.collapse-tax data-parent="#accordionMenu" href="javascript:;" aria-expanded="true"> <i class="menu__link-icon"> 
                        <svg class="svg-icon">
                            <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#tax'" :href="baseUrl+'/admin/images/retina/sprite.svg#tax'"></use>
                        </svg>
                        </i> <span class="menu__link-text">{{$t('LBL_TAX_SETTING')}}</span> <i class="menu__ver-arrow"></i> </a>
						<b-collapse id="collapse-tax" accordion="my-accordion" class="menu__submenu panel-collapse" :visible="(currentPage.includes('/tax') || currentPage.includes('/invoice'))">
							<ul class="menu__subnav">
								<li class="menu__item" v-bind:class="[currentPage.includes('/tax') ? activeClass : '']"  v-if="$canRead('TAX_SETTINGS')">
									<router-link :to="{name: 'tax'}" class="menu__link" data-target-close="aside"> <i class="menu__link-bullet menu__link-bullet--dot">
                                    <span></span>
                                    </i> <span class="menu__link-text">{{ $t('LBL_TAX_MANAGEMENT') }}</span> </router-link>
								</li>
								<li class="menu__item" v-bind:class="[currentPage.includes('/invoice') ? activeClass : '']" v-if="$canRead('INVOICE_MANAGEMENT')">
									<router-link :to="{name: 'taxInvoice'}" class="menu__link" data-target-close="aside"> <i class="menu__link-bullet menu__link-bullet--dot">
                                    <span></span>
                                    </i> <span class="menu__link-text">{{ $t('LBL_INVOICE_MANAGEMENT') }}</span> </router-link>
								</li>
							</ul>
						</b-collapse>
					</li>
					<li class="menu__item" v-if="$canRead('SHIPPING_FULFILMENT') || $canRead('SHIPPING_ZONE_RATE') || $canRead('PICKUP_ADDRESS') || $canRead('RETURN_AND_CANCELLATION')">
						<a class="menu__link" v-b-toggle.collapse-2 data-parent="#accordionMenu" href="javascript:;" aria-expanded="true"> <i class="menu__link-icon">
                        <svg class="svg-icon">
                            <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#fulfilment-shipping'" :href="baseUrl+'/admin/images/retina/sprite.svg#fulfilment-shipping'"></use>
                        </svg>
                        </i> <span class="menu__link-text">{{$t('LBL_SHIPPING_FULFILMENT')}}</span> <i class="menu__ver-arrow"></i> </a>
						<b-collapse id="collapse-2" accordion="my-accordion" class="menu__submenu panel-collapse" :visible="(currentPage.includes('/settings/shipping') || currentPage.includes('/shipping') || currentPage.includes('/pickups') || currentPage.includes('/return-cancellation-messages') || currentPage.includes('/shipping/packages'))">
							<ul class="menu__subnav">
								<li :class="[currentPage.includes('/settings/shipping') ? activeClass : '', 'menu__item']" v-if="$canRead('SHIPPING_FULFILMENT')">
									<router-link :to="{name: 'settingsShipping'}" class="menu__link" data-target-close="aside"> <i class="menu__link-bullet menu__link-bullet--dot">
                                    <span></span>
                                    </i> <span class="menu__link-text">{{ $t('LBL_SHIPPING_GENERAL') }}</span> </router-link>
								</li>
								<li class="menu__item" v-bind:class="[currentPage.includes('/shipping') ? activeClass : '']" v-if="$canRead('SHIPPING_ZONE_RATE')">
									<router-link :to="{name: 'shipping'}" class="menu__link" data-target-close="aside"> <i class="menu__link-bullet menu__link-bullet--dot">
                                    <span></span>
                                    </i> <span class="menu__link-text">{{ $t('LBL_SHIPPING_ZONES_RATES') }}</span> </router-link>
								</li>
								<li class="menu__item" v-bind:class="[currentPage.includes('/pickups') ? activeClass : '']" v-if="$canRead('PICKUP_ADDRESS')">
									<router-link :to="{name: 'pickups'}" class="menu__link" data-target-close="aside"> <i class="menu__link-bullet menu__link-bullet--dot">
                                    <span></span>
                                    </i> <span class="menu__link-text">{{ $t('LBL_PICKUP_ADDRESSES') }}</span> </router-link>
								</li>
								<li class="menu__item" v-bind:class="[currentPage.includes('/return-cancellation-messages') ? activeClass : '']" v-if="$canRead('RETURN_AND_CANCELLATION')">
									<router-link :to="{name: 'returnCancellationMessages'}" class="menu__link" data-target-close="aside"> <i class="menu__link-bullet menu__link-bullet--dot">
                                    <span></span>
                                    </i> <span class="menu__link-text">{{ $t('LBL_RETURN_CANCELLATION_REASONS') }}</span> </router-link>
								</li>
							</ul>
						</b-collapse>
					</li>
					<li class="menu__item" v-bind:class="[currentPage.includes('/payment-methods') ? activeClass : '']" v-if="$canRead('PAYMENT_METHODS')">
						<router-link :to="{name: 'paymentMethods'}" class="menu__link" data-target-close="aside"> <i class="menu__link-icon">
                            <svg class="svg-icon">
                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#payment-methods'" :href="baseUrl+'/admin/images/retina/sprite.svg#payment-methods'"></use>
                            </svg>
                            </i> <span class="menu__link-text">{{ $t('LBL_PAYMENT_METHODS') }}</span> </router-link>
					</li>
					<li class="menu__item" v-bind:class="[currentPage.includes('/settings/product') ? activeClass : '']" v-if="$canRead('PRODUCT_SETTINGS')">
						<router-link :to="{name: 'settingsProduct'}" class="menu__link" data-target-close="aside"> <i class="menu__link-icon">
                            <svg class="svg-icon">
                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#products'" :href="baseUrl+'/admin/images/retina/sprite.svg#products'"></use>
                            </svg>
                            </i> <span class="menu__link-text">{{ $t('LBL_PRODUCT_SETTINGS') }}</span> </router-link>
					</li>
					<li class="menu__item" v-bind:class="[currentPage.includes('/system-settings/logo') ? activeClass : '']" v-if="$canRead('SYSTEM_SETTINGS')">
						<router-link :to="{name: 'settingsLogo'}" class="menu__link" data-target-close="aside" v-bind:class="[ (currentPage.includes('/system-settings/cms') || currentPage.includes('/system-settings/smtp') || currentPage.includes('system-settings/messages') || currentPage.includes('/system-settings/email-templates') || currentPage.includes('/system-settings/sharing') || currentPage.includes('/system-settings/cookies')) ? 'router-link-exact-active router-link-active' : '']"> <i class="menu__link-icon">
                            <svg class="svg-icon">
                                <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#system-settings'" :href="baseUrl+'/admin/images/retina/sprite.svg#system-settings'"></use>
                            </svg>
                            </i> <span class="menu__link-text">{{ $t('LBL_SYSTEM_SETTINGS') }}</span> </router-link>
					</li>
					<li v-if="$canRead('THIRD_PARTY_INTEGRATION')" class="menu__item" v-bind:class="[ (currentPage.includes('/thirdparty/analytics') || currentPage.includes('/thirdparty/currency') || currentPage.includes('/thirdparty/marketing-tools') || currentPage.includes('/thirdparty/geo-location') || currentPage.includes('/thirdparty/language') || currentPage.includes('/thirdparty/live-chat') || currentPage.includes('/thirdparty/security') || currentPage.includes('/thirdparty/sms-tools') || currentPage.includes('/thirdparty/social-keys')) ? activeClass : '']">
						<router-link :to="{name: 'thirdpartyAnalytics'}" class="menu__link" v-bind:class="[ (currentPage.includes('/thirdparty/analytics') || currentPage.includes('/thirdparty/currency') || currentPage.includes('/thirdparty/marketing-tools') || currentPage.includes('/thirdparty/geo-location') || currentPage.includes('/thirdparty/language') || currentPage.includes('/thirdparty/live-chat') || currentPage.includes('/thirdparty/security') || currentPage.includes('/thirdparty/sms-tools') || currentPage.includes('/thirdparty/social-keys')) ? 'router-link-exact-active router-link-active' : '']" data-target-close="aside"> <i class="menu__link-icon">
                   <svg class="svg-icon">
                      <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#third-party-intigrations-keys'" :href="baseUrl+'/admin/images/retina/sprite.svg#third-party-intigrations-keys'"></use>
                  </svg>
                </i> <span class="menu__link-text">{{ $t('LBL_THIRD_PARTY_INTEGRATIONS') }}</span> </router-link>
					</li>

					<li  v-if="$canRead('IMPORT_EXPORT') && 0" class="menu__item" v-bind:class="[ (currentPage.includes('/import-export/brands') || currentPage.includes('/import-export/categories') || currentPage.includes('/import-export/option-group') || currentPage.includes('/import-export/products') || currentPage.includes('/import-export/digital-products') || currentPage.includes('/import-export/shipping-packages')  || currentPage.includes('/import-export/media')) ? activeClass : '']">
						<router-link :to="{name: 'importExportBrands'}" class="menu__link" v-bind:class="[ (currentPage.includes('/import-export/brands') || currentPage.includes('/import-export/categories') || currentPage.includes('/import-export/option-group') || currentPage.includes('/import-export/products') || currentPage.includes('/import-export/digital-products') || currentPage.includes('/import-export/shipping-packages')  || currentPage.includes('/import-export/media')) ? 'router-link-exact-active router-link-active' : '']" data-target-close="aside"> <i class="menu__link-icon">
                   <svg class="svg-icon">
                      <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#import-export'" :href="baseUrl+'/admin/images/retina/sprite.svg#import-export'"></use>
                  </svg>
                </i> <span class="menu__link-text">{{ $t('LBL_IMPORT_EXPORT') }}</span> </router-link>
					</li>
           <li  v-if="$canRead('MOBILE_APPS')" class="menu__item" v-bind:class="[ (currentPage.includes('/mobile-apps/theme') || currentPage.includes('/mobile-apps/home-screen') || currentPage.includes('/mobile-apps/aboutus') || currentPage.includes('/mobile-apps/privacy') || currentPage.includes('/mobile-apps/terms') || currentPage.includes('/mobile-apps/settings')) ? activeClass : '']">
						<router-link :to="{name: 'mobileHome'}" class="menu__link" v-bind:class="[ (currentPage.includes('/mobile-apps/theme') || currentPage.includes('/mobile-apps/home-screen') || currentPage.includes('/mobile-apps/aboutus') || currentPage.includes('/mobile-apps/privacy') || currentPage.includes('/mobile-apps/terms') || currentPage.includes('/mobile-apps/settings')) ? 'router-link-exact-active router-link-active' : '']" data-target-close="aside"> <i class="menu__link-icon">
                   <svg class="svg-icon">
                      <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#mobile-app'" :href="baseUrl+'/admin/images/retina/sprite.svg#mobile-app'"></use>
                  </svg>
                </i> <span class="menu__link-text">{{ $t('LBL_MOBILE_APPS') }}</span> </router-link>
          </li>
          <li  v-if="$canRead('IMPORT_EXPORT')" class="menu__item" v-bind:class="[ (currentPage.includes('/resources') ) ? activeClass : '']">
						<router-link :to="{name: 'resources'}" class="menu__link" v-bind:class="[ (currentPage.includes('/resources') ) ? 'router-link-exact-active router-link-active' : '']" data-target-close="aside"> <i class="menu__link-icon">
                   <svg class="svg-icon">
                      <use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#knowledge-centre'" :href="baseUrl+'/admin/images/retina/sprite.svg#knowledge-centre'"></use>
                  </svg>
                </i> <span class="menu__link-text">{{ $t('LBL_RESOURCES') }}</span> </router-link>
					</li>
				</ul>
			</perfect-scrollbar>
		</div>
	</div>
	<!-- end:: Aside Menu -->
	<a class="offset-menu" data-trigger="aside" href="javascript:void(0);">
		<svg class="setting__icon">
			<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#setting'" :href="baseUrl+'/admin/images/retina/sprite.svg#setting'"></use>
		</svg>
	</a>
    <img style="display:none" src="https://fatlib.4livedemo.com/img/nav.png">
</div>
  <!-- end:: Aside  -->
</template>
<script>
export default {
  data: function() {
    return {
      languages: [],
      adminBaseUrl: adminBaseUrl,
      baseUrl: baseUrl,
      admin_id: "",
      admin_name: "",
      admin_image: "",
      admin_name_badge: "",
      admin_lang: "",
      Height: 0,
      activeClass : 'active'
    };
  },
  methods: {
    getAdminData: function() {
      this.$http.get(adminBaseUrl + "/get-admin-data").then(response => {
        this.languages = response.data.data.languages;
        this.admin_lang = response.data.data.admin_lang;
        this.$root.$emit('adminImage', response.data.data.admin_image);
        this.$root.$emit('adminNameBadge', response.data.data.admin_name_badge);
      });
    }, 
    switchLanguage: function(lang) {
      this.$http.get(baseUrl + "/lang/" + lang).then(response => {
        this.$router.go();
      });
    },
    logout: function() {
      this.$http.get(adminBaseUrl + "/logout").then(response => {
        this.$router.go();
      });
    }    
  },
  mounted() {
    let screenHeight = $(window).height();
    this.Height = screenHeight - 220;
    this.getAdminData();
  },
    computed: {
        currentPage() {
            return this.$route.path;
        }        
    }
};
</script>