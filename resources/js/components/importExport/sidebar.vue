<template>
<!--Begin:: App Aside-->
<div class="grid-layout-aside" id="user_profile_aside">
    <!--begin:: Widgets/Applications/User/Profile1-->
    <div class="portlet portlet--height-fluid">
        <div class="portlet__body">
            <!--begin::Widget -->
            <div class="widget widget--user-profile-1">
                <div class="widget__body">
                    <div class="widget__items">
						<router-link :to="{name: 'importExportBrands'}" class="widget__item" v-bind:class="[tab == 'brandImport' ? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#import-export-brands'" :href="baseUrl+'/admin/images/retina/sprite.svg#import-export-brands'"></use>
									</svg> 
								</span>
								<span class="widget__desc">
									{{ $t('LNK_BRANDS') }}
								</span>
							</span>
						</router-link>
						<router-link :to="{name: 'importExportCategories'}" class="widget__item" v-bind:class="[tab == 'categories' ? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#import-export-category'" :href="baseUrl+'/admin/images/retina/sprite.svg#import-export-category'"></use>
									</svg>
								</span>
								<span class="widget__desc">
									{{ $t('LNK_CATEGORIES') }}
								</span>
							</span>
						</router-link>
						<router-link :to="{name: 'importExportOptionGroups'}" class="widget__item" v-bind:class="[tab == 'optionGroups' ? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#import-export-option-group'" :href="baseUrl+'/admin/images/retina/sprite.svg#import-export-option-group'"></use>
									</svg> 
								</span>
								<span class="widget__desc">
									{{ $t('LNK_OPTION_GROUPS') }}
								</span>
							</span>
						</router-link>

						<router-link :to="{name: 'importExportProducts'}" class="widget__item" v-bind:class="[tab == 'products' ? 'widget__item--active': '']" v-if="(productType == 1 || productType == 3)">
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#import-export-physical-products'" :href="baseUrl+'/admin/images/retina/sprite.svg#import-export-physical-products'"></use>
									</svg> 
								</span>
								<span class="widget__desc">
									{{ $t('LNK_PHYSICAL_PRODUCTS') }}
								</span>
							</span>
						</router-link>
						<router-link :to="{name: 'importExportDigitalProducts'}" class="widget__item" v-bind:class="[tab == 'digitalProducts' ? 'widget__item--active': '']" v-if="(productType == 2 || productType == 3)">
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#import-export-digital-products'" :href="baseUrl+'/admin/images/retina/sprite.svg#import-export-digital-products'"></use>
									</svg> 
								</span>
								<span class="widget__desc">
									{{ $t('LNK_DIGITAL_PRODUCTS') }}
								</span>
							</span>
						</router-link>
						<!--<router-link :to="{name: 'importExportShippingPackages'}" class="widget__item" v-bind:class="[tab == 'shippingPackages' ? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#import-export-shipping-packages'" :href="baseUrl+'/admin/images/retina/sprite.svg#import-export-shipping-packages'"></use>
									</svg> 
								</span>
								<span class="widget__desc">
									{{ $t('LNK_SHIPPING_PACKAGES') }}
								</span>
							</span>
						</router-link>-->
						<router-link :to="{name: 'importExportMedia'}" class="widget__item" v-bind:class="[tab == 'media' ? 'widget__item--active': '']" >
							<span class="widget__section">
								<span class="widget__icon">
									<svg>
										<use :xlink:href="baseUrl+'/admin/images/retina/sprite.svg#import-export-upload-media'" :href="baseUrl+'/admin/images/retina/sprite.svg#import-export-upload-media'"></use>
									</svg> 
								</span>
								<span class="widget__desc">
									{{ $t('LNK_MEDIA') }}
								</span>
							</span>
						</router-link>
                    </div>
                </div>
            </div>
            <!--end::Widget -->
        </div>
    </div>

</div>
<!--End:: App Aside-->
</template>
<script>
export default {
    props: ['tab'],
		data: function() {
			return {
				baseUrl: baseUrl,
				productType: productType			
			}
		}
}
</script>
