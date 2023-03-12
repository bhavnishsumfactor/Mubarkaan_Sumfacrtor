import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

const routes = [    
    {
        path: '',
        component: () => import("./components/dashboard/dashboard.vue"),
        name: 'dashboard'
    },
    {
        path: '/get-started',
        component: () => import("./components/dashboard/getstarted.vue"),
        name: 'getStarted'
    },
    {
        path: '/brands',
        component: () => import("./components/brands/index.vue"),
        name: 'brands',
        meta : {
            permissions: 'BRANDS'
        },
    },
    {
        path: '/payment-methods',
        component: () => import("./components/paymentMethods/index.vue"),
        name: 'paymentMethods',
        meta : {
            permissions: 'PAYMENT_METHODS'
        },
    },
    {
        path: '/email-templates',
        component: () => import("./components/emailTemplates/lists.vue"),
        name: 'emailTemplates',
        meta : {
            permissions: 'EMAIL_TEMPLATES'
        },
    },
    {
        path: '/options',
        component: () => import("./components/options/index.vue"),
        name: 'options',
        meta : {
            permissions: 'OPTIONS'
        },
    },
    {
        path: '/sms-templates',
        component: () => import("./components/smsTemplates/index.vue"),
        name: 'smsTemplates',
        meta : {
            permissions: 'SMS_TEMPLATES'
        },
    },
    {
        path: '/notification-templates',
        component: () => import("./components/notificationTemplates/index.vue"),
        name: 'notificationTemplates',
        meta : {
            permissions: 'NOTIFICATION_TEMPLATES'
        },
    },
    {
        path: '/roles',
        component: () => import("./components/roles/list.vue"),
        name: 'roles',
        meta : {
            permissions: 'ADMIN_USERS'
        },
    },
    {
        path: '/roles/create',
        component: () => import("./components/roles/add.vue"),
        name: 'rolesAdd',
        meta : {
            permissions: 'ADMIN_USERS'
        },
    },
    {
        path: '/roles/:id/edit',
        component: () => import("./components/roles/add.vue"),
        name: 'rolesEdit',
        meta : {
            permissions: 'ADMIN_USERS'
        },
    },
    {
        path: '/users/:id?',
        component: () => import("./components/users/list.vue"),
        name: 'users',
        meta : {
            permissions: 'USERS'
        },
    },
    {
        path: '/user/groups',
        component: () => import('./components/users/groups/index.vue'),
        name: 'userGroups',
        meta : {
            permissions: 'USERS'
        },
    },
    {
        path: '/edit-profile',
        component: () => import('./components/settings/editProfile.vue'),
        name: 'editProfile'
    },
    {
        path: '/change-password',
        component: () => import('./components/settings/changePassword.vue'),
        name: 'changePassword'
    },
    {
        path: '/edit-email',
        component: () => import('./components/settings/editEmailAddress.vue'),
        name: 'editEmailAddress'
    },
    {
        path: '/pages',
        component: () => import('./components/pages/list.vue'),
        name: 'pages',
        meta : {
            permissions: 'PAGES'
        },
    },
    {
        path: '/pages/listing',
        component: () => import('./components/pages/listing.vue'),
        name: 'settingsProductListing',
        meta : {
            permissions: 'PAGES'
        },
    },
    {
        path: '/pages/detail',
        component: () => import('./components/pages/detail.vue'),
        name: 'settingsProductDetail',
        meta : {
            permissions: 'PAGES'
        },
    },
    {
        path: '/testimonials',
        component: () => import('./components/testimonials/index.vue'),
        name: 'testimonials',
        meta : {
            permissions: 'TESTIMONIALS'
        },
    },
    {
        path: '/sub-admins',
        component: () => import('./components/admins/list.vue'),
        name: 'subAdmins',
        meta : {
            permissions: 'ADMIN_USERS'
        },
    },
    {
        path: '/sub-admins/create',
        component: () => import('./components/admins/add.vue'),
        name: 'addSubAdmins',
        meta : {
            permissions: 'ADMIN_USERS'
        },
    },
    {
        path: '/sub-admins/:id/edit',
        component: () => import('./components/admins/edit.vue'),
        name: 'editSubAdmin',
        meta : {
            permissions: 'ADMIN_USERS'
        },
    },
    {
        path: '/blog/categories',
        component: () => import('./components/blogs/categories/index.vue'),
        name: 'blogCategory',
        meta : {
            permissions: 'BLOGS'
        },
    },
    {
        path: '/blog/comments',
        component: () => import('./components/blogs/comments/list.vue'),
        name: 'blogComment',
        meta : {
            permissions: 'BLOGS'
        },
    },
    {
        path: '/blog/comments/:id/view',
        component: () => import('./components/blogs/comments/view.vue'),
        name: 'viewComment',
        meta : {
            permissions: 'BLOGS'
        },
    },
    {
        path: '/blogs',
        component: () => import('./components/blogs/list.vue'),
        name: 'blogs',
        meta : {
            permissions: 'BLOGS'
        },
    },
    {
        path: '/blog/create',
        component: () => import('./components/blogs/add.vue'),
        name: 'createBlog',
        meta : {
            permissions: 'BLOGS'
        },
    },
    {
        path: '/blog/:id/edit',
        component: () => import('./components/blogs/edit.vue'),
        name: 'editBlog',
        meta : {
            permissions: 'BLOGS'
        },
    },
    {
        path: '/product/categories',
        component: () => import('./components/products/categories/index.vue'),
        name: 'productCategory',
        meta : {
            permissions: 'CATEGORIES'
        },
    },
    {
        path: '/settings/shipping',
        component: () => import('./components/fulfilmentAndShipping/general.vue'),
        name: 'settingsShipping',
        meta : {
            permissions: 'SHIPPING_FULFILMENT'
        },
    },
    {
        path: '/shipping',
        component: () => import('./components/fulfilmentAndShipping/shipping/list.vue'),
        name: 'shipping',
        meta : {
            permissions: 'SHIPPING_ZONE_RATE'
        },
    },
    {
        path: '/shipping/:id/edit',
        component: () => import('./components/fulfilmentAndShipping/shipping/add.vue'),
        name: 'editShippingProfile',
        meta : {
            permissions: 'SHIPPING_ZONE_RATE'
        },
    },
    {
        path: '/shipping/create',
        component: () => import('./components/fulfilmentAndShipping/shipping/add.vue'),
        name: 'createShipping',
        meta : {
            permissions: 'SHIPPING_ZONE_RATE'
        },
    },
    {
        path: '/tax',
        component: () => import('./components/taxManagement/list.vue'),
        name: 'tax',
        meta : {
            permissions: 'TAX_SETTINGS'
        },
    },
    {
        path: '/tax/create',
        component: () => import('./components/taxManagement/add.vue'),
        name: 'taxCreate',
        meta : {
            permissions: 'TAX_SETTINGS'
        },
    },
    {
        path: '/tax/:id/edit',
        component: () => import('./components/taxManagement/add.vue'),
        name: 'taxEdit',
        meta : {
            permissions: 'TAX_SETTINGS'
        },
    },
    {
        path: '/invoice',
        component: () => import('./components/taxManagement/invoice.vue'),
        name: 'taxInvoice',
        meta : {
            permissions: 'INVOICE_MANAGEMENT'
        },
    },
    {
        path: '/pickups',
        component: () => import('./components/pickups/list.vue'),
        name: 'pickups',
        meta : {
            permissions: 'PICKUP_ADDRESS'
        },
    },
    {
        path: '/pickup/create',
        component: () => import('./components/pickups/add.vue'),
        name: 'createPickup',
        meta : {
            permissions: 'PICKUP_ADDRESS'
        },
    },
    {
        path: '/pickup/:id/edit',
        component: () => import('./components/pickups/edit.vue'),
        name: 'editPickup',
        meta : {
            permissions: 'PICKUP_ADDRESS'
        },
    },
    {
        path: '/product/create',
        component: () => import('./components/products/add.vue'),
        name: 'productCreate',
        meta : {
            permissions: 'PRODUCTS'
        },
    },
    {
        path: '/product/:id/edit',
        component: () => import('./components/products/add.vue'),
        name: 'editProduct',
        meta : {
            permissions: 'PRODUCTS'
        },
    },
    {
        path: '/products/buy-together',
        component: () => import('./components/products/buyTogetherProducts.vue'),
        name: 'buyTogetherProducts',
        meta : {
            permissions: 'BUY_TOGETHER_PRODUCTS'
        },
    },
    {
        path: '/products/related',
        component: () => import('./components/products/relatedProducts.vue'),
        name: 'relatedProducts',
        meta : {
            permissions: 'RELATED_PRODUCTS'
        },
    },
    {
        path: '/products/:tab?',
        component: () => import('./components/products/list.vue'),
        name: 'products',
        meta : {
            permissions: 'PRODUCTS'
        },
    },
    {
        path: '/localization/settings',
        component: () => import('./components/localization/general.vue'),
        name: 'localizationGeneral',
        meta : {
            permissions: 'LOCALIZATION'
        },
    },
    {
        path: '/currencies',
        component: () => import('./components/localization/currencies/list.vue'),
        name: 'currencies',
        meta : {
            permissions: 'LOCALIZATION'
        },
    },
    {
        path: '/currency/create',
        component: () => import('./components/localization/currencies/add.vue'),
        name: 'createCurrency',
        meta : {
            permissions: 'LOCALIZATION'
        },
    },
    {
        path: '/seo/metatags',
        component: () => import('./components/seo/metaTags.vue'),
        name: 'metaTags',
        meta : {
            permissions: 'META_TAGS'
        },
    },
    {
        path: '/seo/image-attributes',
        component: () => import('./components/seo/imageAttributes.vue'),
        name: 'imageAttributes',
        meta : {
            permissions: 'ALT_IMAGES'
        },
    },
    {
        path: '/seo/url-rewrite',
        component: () => import('./components/seo/urlRewrite.vue'),
        name: 'urlRewrite',
        meta : {
            permissions: 'URL_REWRITE'
        },
    },
    {
        path: '/special-prices',
        component: () => import('./components/promotions/specialPrices.vue'),
        name: 'specialPrices',
        meta : {
            permissions: 'SPECIAL_PRICES'
        },
    },
    {
        path: '/discount-coupons',
        component: () => import('./components/promotions/discountCoupons.vue'),
        name: 'discountCoupons',
        meta : {
            permissions: 'DISCOUNT_COUPONS'
        },
    },
    {
        path: '/reward-points',
        component: () => import('./components/promotions/rewardPoints.vue'),
        name: 'rewardPoints',
        meta : {
            permissions: 'REWARDS_POINTS'
        },
    },
    {
        path: '/faqs',
        component: () => import('./components/faqs/list.vue'),
        name: 'faqs',
        meta : {
            permissions: 'FAQ'
        },
    },
    {
        path: '/languages-labels/:type',
        component: () => import('./components/languageLabels/list.vue'),
        name: 'languageLabels',
        meta : {
            permissions: 'LANGUAGE_LABELS'
        },
    },
    {
        path: '/orders',
        component: () => import('./components/orders/list.vue'),
        name: 'orders',
        meta : {
            permissions: 'ORDERS'
        },
    },
    {
        path: '/order/create',
        component: () => import('./components/orders/add.vue'),
        name: 'addOrder',
        meta : {
            permissions: 'ADD_ORDERS'
        },
    },
    {
        path: '/order/:id',
        component: () => import('./components/orders/detail.vue'),
        name: 'orderDetail',
        meta : {
            permissions: 'ORDERS'
        },
    },
    {
        path: '/return-request/:id',
        component: () => import('./components/orders/cancelRequest.vue'),
        name: 'returnRequest',
        meta : {
            permissions: 'ORDERS'
        },
    },
    {
        path: '/order/return/:id/:requestId',
        component: () => import('./components/orders/returnDetail.vue'),
        name: 'orderReturnDetail',
        meta : {
            permissions: 'ORDERS'
        },
    },
    {
        path: '/product-reviews',
        component: () => import('./components/productReviews/list.vue'),
        name: 'productReviewsListing',
        meta : {
            permissions: 'PRODUCT_REVIEWS'
        },
    },
    {
        path: '/product-reviews/:id',
        component: () => import('./components/productReviews/list.vue'),
        name: 'productReviews',
        meta : {
            permissions: 'PRODUCT_REVIEWS'
        },
    },
    {
        path: '/product-reviews/:id/detail',
        component: () => import('./components/productReviews/detail.vue'),
        name: 'productReviewsDetail',
        meta : {
            permissions: 'PRODUCT_REVIEWS'
        },
    },
    {
        path: '/un-authorised',
        component: () => import('./components/permission/index.vue'),
        name: 'unAuthorised'
    },
    {
        path: '/thirdparty/security',
        component: () => import('./components/thirdPartyIntegrations/security.vue'),
        name: 'thirdpartySecurity',
        meta : {
            permissions: 'THIRD_PARTY_INTEGRATION'
        },
    },
    {
        path: '/thirdparty/live-chat',
        component: () => import('./components/thirdPartyIntegrations/liveChat.vue'),
        name: 'thirdpartyLiveChat',
        meta : {
            permissions: 'THIRD_PARTY_INTEGRATION'
        },
    },
    {
        path: '/thirdparty/social-keys',
        component: () => import('./components/thirdPartyIntegrations/socialKeys.vue'),
        name: 'thirdpartySocialKeys',
        meta : {
            permissions: 'THIRD_PARTY_INTEGRATION'
        },
    },
    {
        path: '/thirdparty/analytics',
        component: () => import('./components/thirdPartyIntegrations/analytics.vue'),
        name: 'thirdpartyAnalytics',
        meta : {
            permissions: 'THIRD_PARTY_INTEGRATION'
        },
    },
    {
        path: '/thirdparty/language',
        component: () => import('./components/thirdPartyIntegrations/language.vue'),
        name: 'thirdpartyLanguage',
        meta : {
            permissions: 'THIRD_PARTY_INTEGRATION'
        },
    },
    {
        path: '/thirdparty/marketing-tools',
        component: () => import('./components/thirdPartyIntegrations/marketingTools.vue'),
        name: 'thirdpartyMarketingTools',
        meta : {
            permissions: 'THIRD_PARTY_INTEGRATION'
        },
    },
    {
        path: '/thirdparty/geo-location',
        component: () => import('./components/thirdPartyIntegrations/geoLocation.vue'),
        name: 'thirdpartyGeoLocation',
        meta : {
            permissions: 'THIRD_PARTY_INTEGRATION'
        },
    },
    {
        path: '/thirdparty/sms-tools',
        component: () => import('./components/thirdPartyIntegrations/smsTools.vue'),
        name: 'thirdpartySMSTools',
        meta : {
            permissions: 'THIRD_PARTY_INTEGRATION'
        },
    },
    {
        path: '/thirdparty/currency',
        component: () => import('./components/thirdPartyIntegrations/currency.vue'),
        name: 'thirdpartyCurrency',
        meta : {
            permissions: 'THIRD_PARTY_INTEGRATION'
        },
    },
    {
        path: '/thread/:id/messages',
        component: () => import('./components/messages/messages.vue'),
        name: 'threadMessages',
        meta : {
            permissions: 'MESSAGES'
        },
    },
    {
        path: '/languages',
        component: () => import('./components/localization/languages/list.vue'),
        name: 'languages',
        meta : {
            permissions: 'LOCALIZATION'
        },
    },
    {
        path: '/user/requests',
        component: () => import('./components/userRequests/list.vue'),
        name: 'userRequests',
        meta : {
            permissions: 'GDPR_REQUEST'
        },
    },
    {
        path: '/system-settings/logo',
        component: () => import('./components/systemSettings/logo.vue'),
        name: 'settingsLogo',
        meta : {
            permissions: 'SYSTEM_SETTINGS'
        },
    },
    {
        path: '/system-settings/smtp',
        component: () => import('./components/systemSettings/smtp.vue'),
        name: 'settingsSmtp',
        meta : {
            permissions: 'SYSTEM_SETTINGS'
        },
    },
    {
        path: '/system-settings/messages',
        component: () => import('./components/systemSettings/systemMessage.vue'),
        name: 'settingsSystemMessage',
        meta : {
            permissions: 'SYSTEM_SETTINGS'
        },
    },
    {
        path: '/system-settings/email-templates',
        component: () => import("./components/systemSettings/emailTemplates.vue"),
        name: 'settingsEmailTemplates',
        meta : {
            permissions: 'SYSTEM_SETTINGS'
        },
    },
    {
        path: '/system-settings/sharing',
        component: () => import('./components/systemSettings/sharing.vue'),
        name: 'settingsSharing',
        meta : {
            permissions: 'SYSTEM_SETTINGS'
        },
    },
    {
        path: '/system-settings/cookies',
        component: () => import('./components/systemSettings/cookies.vue'),
        name: 'settingsCookies',
        meta : {
            permissions: 'SYSTEM_SETTINGS'
        },
    },    
    {
        path: '/system-settings/cms/:type?/:message?',
        component: () => import('./components/systemSettings/cms.vue'),
        name: 'settingsCms',
        meta : {
            permissions: 'SYSTEM_SETTINGS'
        },
    },
    {
        path: '/settings/product',
        component: () => import('./components/systemSettings/product.vue'),
        name: 'settingsProduct',
        meta : {
            permissions: 'PRODUCT_SETTINGS'
        },
    },
    {
        path: '/reports/sale-reports',
        component: () => import('./components/reports/sale/saleReport.vue'),
        name: 'saleReport',
        meta : {
            permissions: 'SALE_REPORT'
        },
    },
    {
        path: '/reports/acquisition-reports',
        component: () => import('./components/reports/acquisition/acquisitionReport.vue'),
        name: 'acquisitionReport',
        meta : {
            permissions: 'ACQUISITION_REPORT'
        },
    },
    {
        path: '/reports/profit-reports',
        component: () => import('./components/reports/profit/profitReport.vue'),
        name: 'profitReport',
        meta : {
            permissions: 'PROFIT_MARGIN_REPORT'
        },
    },
    {
        path: '/reports/customer-reports',
        component: () => import('./components/reports/customer/customerReport.vue'),
        name: 'customerReport',
        meta : {
            permissions: 'CUSTOMER_REPORT'
        },
    },
    {
        path: '/reports/behavior-reports',
        component: () => import('./components/reports/behavior/behaviorReport.vue'),
        name: 'behaviorReport',
        meta : {
            permissions: 'BEHAVIOUR_REPORT'
        },
    },
    {
        path: '/localization/bussiness-management',
        component: () => import('./components/localization/businessManagement.vue'),
        name: 'businessManagement',
        meta : {
            permissions: 'LOCALIZATION'
        }
    },
    {
        path: '/shipping/packing-slip',
        component: () => import('./components/fulfilmentAndShipping/shipping/packingSlip.vue'),
        name: 'editPackingSlip',
        meta : {
            permissions: 'SHIPPING_ZONE_RATE'
        }
    },
    {
        path: '/import-export/brands',
        component: () => import('./components/importExport/brands.vue'),
        name: 'importExportBrands',
        meta : {
            permissions: 'IMPORT_EXPORT'
        }
    },
    {
        path: '/import-export/categories',
        component: () => import('./components/importExport/categories.vue'),
        name: 'importExportCategories',
        meta : {
            permissions: 'IMPORT_EXPORT'
        }
    },
    {
        path: '/import-export/products',
        component: () => import('./components/importExport/products.vue'),
        name: 'importExportProducts',
        meta : {
            permissions: 'IMPORT_EXPORT'
        }
    },
    {
        path: '/import-export/option-group',
        component: () => import('./components/importExport/optionGroups.vue'),
        name: 'importExportOptionGroups',
        meta : {
            permissions: 'IMPORT_EXPORT'
        }
    },
    {
        path: '/import-export/shipping-packages',
        component: () => import('./components/importExport/shippingPackages.vue'),
        name: 'importExportShippingPackages',
        meta : {
            permissions: 'IMPORT_EXPORT'
        }
    },
    {
        path: '/import-export/digital-products',
        component: () => import('./components/importExport/digitalProducts.vue'),
        name: 'importExportDigitalProducts',
        meta : {
            permissions: 'IMPORT_EXPORT'
        }
    },
    {
        path: '/return-cancellation-messages',
        component: () => import('./components/fulfilmentAndShipping/returnCancellationMessages.vue'),
        name: 'returnCancellationMessages',
        meta : {
            permissions: 'RETURN_AND_CANCELLATION'
        }
    },
    {
        path: '/import-export/media',
        component: () => import('./components/importExport/media.vue'),
        name: 'importExportMedia',
        meta : {
            permissions: 'IMPORT_EXPORT'
        }
    },
    {
        path: '/seo/miscellaneous',
        component: () => import('./components/seo/miscellaneous.vue'),
        name: 'miscellaneous',
        meta : {
            permissions: 'MISCELLANEOUS'
        }
    },
    {
        path: '/admin/emails',
        component: () => import('./components/emails/list.vue'),
        name: 'emailList'
    },
    {
        path: '/admin/emails/:id',
        component: () => import('./components/emails/show.vue'),
        name: 'emailView'
    },
    {
        path: "*",
        component: () => import('./components/page-not-found.vue')
    },
    {
        path: '/user/view/:id',
        component: () => import("./components/users/viewDetails.vue"),
        name: 'userDetails',
        meta : {
            permissions: 'USERS'
        }
    },
    {
        path: '/subadminDashboard',
        component: () => import("./components/dashboard/subadmin.vue"),
        name: 'subadminDashboard',
        meta : {
            permissions: ''
        }
    },
    {
        path: '/thirdparty/tracking-api',
        component: () => import('./components/thirdPartyIntegrations/trackingApi.vue'),
        name: 'trackingApi',
        meta : {
            permissions: 'THIRD_PARTY_INTEGRATION'
        },
    },
    {
        path: '/plugins',
        component: () => import("./components/plugins/index.vue"),
        name: 'plugins',
        meta : {
            permissions: ''
        }
    }, {
        path: '/mobile-apps/theme',
        component: () =>
            import ('./components/mobileApps/theme.vue'),
        name: 'mobileTheme'
    }, {
        path: '/mobile-apps/home-screen',
        component: () =>
            import ("./components/mobileApps/homeScreen.vue"),
        name: 'mobileHome'
    }, {
        path: '/mobile-apps/content/:id',
        component: () =>
            import ("./components/mobileApps/contentPages.vue"),
        name: 'contentPages'
    }, {
        path: '/mobile-apps/privacy',
        component: () =>
            import ("./components/mobileApps/privacy.vue"),
        name: 'mobilePrivacy'
    }, {
        path: '/mobile-apps/terms',
        component: () =>
            import ("./components/mobileApps/terms.vue"),
        name: 'mobileTerms'
    }, {
        path: '/mobile-apps/settings',
        component: () =>
            import ("./components/mobileApps/settings.vue"),
        name: 'mobileSettings'
    }, {
        path: '/mobile-apps/app-noti-templates',
        component: () =>
            import ("./components/mobileApps/appNotiTemplates.vue"),
        name: 'appNotiTemplates'
    }, {
        path: '/mobile-apps/lang-labels',
        component: () =>
            import ("./components/mobileApps/langLabels.vue"),
        name: 'mobileLangLabels'
    },
    {
        path: '/mobile-apps/explore',
        component: () =>
            import ("./components/mobileApps/explore.vue"),
        name: 'mobileExplore'
    },
    {
        path: '/resources',
        component: () => import("./components/resources/index.vue"),
        name: 'resources',
        meta : {
            permissions: ''
        }
    }
];

const router = new VueRouter({
    routes
})
export default router
