<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Admin routes
Route::get('/.well-known/apple-app-site-association', 'HomeController@iosDeepLink');
Route::get('/product/query/update', 'HomeController@updateVariantRecords');
Route::group(['prefix' => 'admin'], function () {
    Route::get('sub-user/login/{id}', 'Auth\LoginController@impersonateSubAdmin')->name('impersonateSubAdmin');
    Route::get('login', 'Auth\LoginController@showAdminLoginForm')->name('adminLoginForm');
    Route::post('login', 'Auth\LoginController@adminLogin')->name('adminLogin');
    Route::get('password/reset', 'Auth\ForgotPasswordController@adminShowLinkRequestForm')->name('adminResetPassword');
    Route::post('password/email', 'Auth\ForgotPasswordController@adminSendResetLinkEmail')->name('adminResetEmail');
    Route::post('password/phone', 'Auth\ForgotPasswordController@adminSendResetOtpPhone')->name('adminResetPhone');
    Route::post('password/phone/resend', 'Auth\ForgotPasswordController@adminResendOtp')->name('adminResendOtp');
    Route::post('password/phone/verify', 'Auth\ForgotPasswordController@adminVerifyOtp')->name('adminVerifyOtp');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@adminShowResetForm');
    Route::post('password/reset', 'Auth\ResetPasswordController@adminReset')->name('adminPasswordUpdate');
    Route::any('logout', 'Auth\LoginController@adminLogout')->name('adminLogout');
    // Admins & SubAdmin managements
    Route::post('sub-admins/{id}/password-update', 'Admin\AdminController@updateAdminPassword');
    Route::resource('sub-admins', 'Admin\AdminController');
   
    Route::post('sub-admins/list', 'Admin\AdminController@getListing');
    Route::post('global-search', 'Admin\AdminController@globalSearch')->name('globalSearch');

    Route::post('sub-admins/roles', 'Admin\AdminController@getRoles');
    Route::post('sub-admins/status/{admin_id}', 'Admin\AdminController@updateStatus');

    Route::get('/', 'Admin\AdminController@dashboard')->name('adminDashboard');
    Route::get('/change-password', 'Admin\AdminController@changePassword')->name('changePassword');
    Route::post('/change-password', 'Admin\AdminController@updatePassword')->name('updatePassword');
    Route::get('/get-user', 'Admin\AdminController@editUserData')->name('getUserData');
    Route::get('/send-otp', 'Admin\AdminController@sendOTP')->name('sendOTP');
    Route::post('/verify-otp', 'Admin\AdminController@verifyOTP')->name('verifyOTP');
    Route::get('/edit-profile', 'Admin\AdminController@editProfile')->name('editProfile');
    Route::post('/edit-profile/{id}', 'Admin\AdminController@updateProfile')->name('updateProfile');
    Route::get('/edit-email/', 'Admin\AdminController@editEmailAddress')->name('editEmailAddress');
    Route::post('/update-email/', 'Admin\AdminController@updateEmailAddress')->name('updateEmailAddress');
    Route::post('/update-profile-image/{id}', 'Admin\AdminController@updateProfileImage')->name('updateProfileImage');
    Route::get('email/verify/{token}', 'Admin\AdminController@emailTokenVerify')->name('emailTokenVerify');
    Route::post('email-templates/list', 'Admin\EmailTemplateController@getListing');
    Route::get('email-templates/{id}/record', 'Admin\EmailTemplateController@getRecord');
    Route::get('email-templates/{id}/preview', 'Admin\EmailTemplateController@getPreview');
    Route::get('email-templates/open-settings', 'Admin\EmailTemplateController@openSettingsPage');
    Route::get('email-templates/settings', 'Admin\EmailTemplateController@loadSettings');
    Route::post('email-templates/settings', 'Admin\EmailTemplateController@saveSettings');
    Route::resource('email-templates', 'Admin\EmailTemplateController');
    // Admins Role managements
    Route::post('roles/list', 'Admin\AdminRoleController@getListing');
    Route::get('roles/get-role-permission', 'Admin\AdminRoleController@getAdminRolePermission');
    Route::get('roles/get-all-permission-section', 'Admin\AdminRoleController@getAllPermissionSections');

    Route::resource('roles', 'Admin\AdminRoleController');
    Route::get('notifications/status', 'Admin\NotificationController@udpateNotificationStatus')
        ->name('udpateNotificationStatus');
    Route::get('notifications/read-unread/{notify_id?}/{nta_read?}', 'Admin\NotificationController@readUnread');
    Route::get('notifications/redirect/{notify_id?}', 'Admin\NotificationController@redirectNotification')
        ->name('redirectNotification');
    Route::get('notifications/{row?}', 'Admin\NotificationController@listNotifications')->name('listNotifications');
    Route::get('get-notifications/{row?}/{type?}', 'Admin\NotificationController@getNotifications');
    Route::get('get-unread-notifications', 'Admin\NotificationController@getUnreadNotifications');

    // Admin Currencies management
    Route::post('currencies/list', 'Admin\CurrencyController@getListing');
    Route::get('currencies/fetchall', 'Admin\CurrencyController@fetchallCurrencies');
    Route::post('currencies/status/{id}', 'Admin\CurrencyController@updateStatus');
    Route::post('currencies/markasdefault/{id}', 'Admin\CurrencyController@markAsDefault');
    Route::resource('currencies', 'Admin\CurrencyController');


    // App Notification template management
    Route::post('app-notification-templates/status/{package_id}', 'Admin\AppNotificationTemplateController@updateStatus');
    Route::post('app-notification-templates/list', 'Admin\AppNotificationTemplateController@getListing');
    Route::resource('app-notification-templates', 'Admin\AppNotificationTemplateController');

    // Admins Brand managements
    Route::post('brands/import', 'Admin\BrandController@import');
    Route::get('brands/export', 'Admin\BrandController@export');
    Route::post('brand/brands-for-excel', 'Admin\BrandController@getBrandForExcel');
    Route::post('brand/excel-update', 'Admin\BrandController@brandExcelUpdate');
    Route::get('brand/get-last-id', 'Admin\BrandController@getLastId');
    Route::resource('brands', 'Admin\BrandController');
    Route::post('brands/list', 'Admin\BrandController@getListing');
    Route::post('brands/status/{id}', 'Admin\BrandController@updateStatus');
    // Admins Options managements
    Route::post('options/options-for-excel', 'Admin\OptionController@optionForExcel');
    Route::get('options/get-last-id', 'Admin\OptionController@getLastId');
    Route::get('options/export', 'Admin\OptionController@export');
    Route::post('options/import', 'Admin\OptionController@import');
    Route::post('options/excel-update', 'Admin\OptionController@optionExcelUpdate');
    Route::resource('options', 'Admin\OptionController');
    Route::post('options/list', 'Admin\OptionController@getListing');

    // Admins Brand managements
    Route::resource('payment-methods', 'Admin\PaymentGatewayController');
    Route::post('payment-methods/list', 'Admin\PaymentGatewayController@getListing');
    Route::post('payment-methods/status/{id}', 'Admin\PaymentGatewayController@updateStatus');
    // SMS template management
    Route::get('sms-templates/packages', 'Admin\SmsTemplateController@getPackages');
    Route::post('sms-templates/configurations/{package_id}', 'Admin\SmsTemplateController@saveConfigurations');
    Route::get('sms-templates/configurations/{package_id}', 'Admin\SmsTemplateController@getConfigurations');
    Route::post('sms-templates/status/{package_id}', 'Admin\SmsTemplateController@updateStatus');
    Route::post('sms-templates/list', 'Admin\SmsTemplateController@getListing');
    Route::resource('sms-templates', 'Admin\SmsTemplateController');
    // Notification template management
    Route::post('notification-templates/status/{package_id}', 'Admin\NotificationTemplateController@updateStatus');
    Route::post('notification-templates/list', 'Admin\NotificationTemplateController@getListing');
    Route::resource('notification-templates', 'Admin\NotificationTemplateController');
    // Users managements
    Route::post('users/export-users', 'Admin\UserController@exportUsers');
    Route::get('users/exportsubscriptions', 'Admin\UserController@exportSubscriptions');
    Route::any('users/search', 'Admin\UserController@userSearch');
    Route::get('users/login/{id}', 'Admin\UserController@loginUserById');
    Route::resource('users', 'Admin\UserController');
    Route::post('users/list', 'Admin\UserController@getListing');
    Route::post('users/status/{userId}', 'Admin\UserController@updateStatus');
    Route::post('users/sendmail/{userId}', 'Admin\UserController@sendMail');
    Route::post('users/orders/{userId}', 'Admin\UserController@getUserOrders');
    Route::post('users/reviews/{userId}', 'Admin\UserController@getUserReviews');
    Route::post('users/rewards/{userId}', 'Admin\UserController@getUserRewards');
    Route::post('users/coupons/{userId}', 'Admin\UserController@getUserCoupons');
    Route::post('users/addresses/{userId}', 'Admin\UserController@getUserAddress');
    Route::post('users/rewards/update/{userId}', 'Admin\UserController@updateUserRewards');


    // Collections
    Route::post('/savetemplate/{page_id}', 'Admin\CollectionController@saveTemplate');
    Route::get('/loadtemplate/{page_id}', 'Admin\CollectionController@loadTemplate');
    Route::post('widgets/load', 'Admin\CollectionController@loadWidget');
    Route::post('collection/search', 'Admin\CollectionController@searchCollection');
    Route::post('collection/get', 'Admin\CollectionController@getCollection');
    Route::post('collection/save', 'Admin\CollectionController@saveCollection');
    Route::post('collection/delete', 'Admin\CollectionController@deleteCollection');
    Route::post('collection/deleteimage', 'Admin\CollectionController@deleteCollectionImage');
    Route::post('collection/delete_all', 'Admin\CollectionController@deleteAllCollection');
    Route::post('collection/settings', 'Admin\CollectionController@getCollectionSetting');
    Route::post('collection/checkifexists', 'Admin\CollectionController@checkIfExists');
    // Navigation
    Route::post('navigations/save', 'Admin\CollectionController@saveMenu');
    Route::post('navigations/delete', 'Admin\CollectionController@deleteMenu');
    // Banner slider
    Route::post('sliders/save', 'Admin\CollectionController@saveSlide');
    Route::post('sliders/delete', 'Admin\CollectionController@deleteSlide');
    Route::post('sliders/saveslideimage', 'Admin\CollectionController@saveSlideImage');
    Route::post('sliders/deleteslideimage', 'Admin\CollectionController@deleteSlideImage');
    Route::post('sliders/settings', 'Admin\CollectionController@saveSliderSettings');

    // Pages managements
    Route::post('pages/list', 'Admin\PageController@getListing');
    Route::post('pages/status/{page_id}', 'Admin\PageController@updateStatus');
    Route::post('pages/duplicate', 'Admin\PageController@duplicatePage');
    Route::resource('pages', 'Admin\PageController');
    Route::post('pages/skill-level/{id}', 'Admin\PageController@updateSkillLevel');
    Route::post('pages/reset', 'Admin\PageController@resetPage');
    // Admins Shipping managements
    Route::get('shipping-packages/export', 'Admin\ShippingController@packageExport');
    Route::post('shipping-packages/import', 'Admin\ShippingController@packageImport');
    Route::post('shipping-packages/get-data-excel', 'Admin\ShippingController@packageForExcel');
    Route::get('shipping-packages/get-last-id', 'Admin\ShippingController@getLastId');
    Route::post('shipping-packages/excel-update', 'Admin\ShippingController@packageExcelUpdate');
    Route::post('shipping/list', 'Admin\ShippingController@getListing');
    Route::get('shipping/tracking-api', 'Admin\ShippingController@getTrackingApiInformation');
    Route::post('shipping/tracking-api-update', 'Admin\ShippingController@updateTrackingApiInformation');
    Route::resource('shipping', 'Admin\ShippingController');
    Route::post('shipping/page-load-data', 'Admin\ShippingController@pageLoadData')->name('shippingPageLoadData');
    Route::post('shipping/save/{saveType}', 'Admin\ShippingController@save');
    Route::get('shipping/view-more-locations/{spzone_id}', 'Admin\ShippingController@viewMoreLocations');
    Route::post('shipping-zone/get-countries', 'Admin\ShippingController@getCountries');
    Route::post('shipping-zone/get-countries-data', 'Admin\ShippingController@getCountriesData');
    Route::get('shipping-zone/get-regions-data', 'Admin\ShippingController@getRegionsData');
    Route::post('shipping-zone/get-states-data', 'Admin\ShippingController@getStatesData');
    Route::post('shipping-zone/get-states', 'Admin\ShippingController@getStates');
    Route::post('shipping-zone/save-location', 'Admin\ShippingController@saveZoneLocation');
    Route::post('shipping/zone-location/remove', 'Admin\ShippingController@removeZoneLocation');
    Route::post('shipping/products/remove', 'Admin\ShippingController@removeProduct');
    Route::post('shipping/delete/{deleteType}', 'Admin\ShippingController@destroy');
    Route::post('shipping/products', 'Admin\ShippingController@getProducts');
    Route::post('shipping/getSelectedLocations', 'Admin\ShippingController@getSelectedLocations');

    // Admins Products managements

    Route::get('products/export', 'Admin\ProductController@export');
    Route::post('product/import', 'Admin\ProductController@import');
    Route::get('products/export-digital', 'Admin\ProductController@exportDigitalProduct');
    Route::get('products/get-last-id', 'Admin\ProductController@getLastId');
    Route::post('products/physical-product-excel-update', 'Admin\ProductController@physicalProductExcelUpdate');
    Route::post('products/digital-product-excel-update', 'Admin\ProductController@digitalProductExcelUpdate');
    Route::post('products/digital-product-for-excel', 'Admin\ProductController@getDigitalProductForExcel');
    Route::post('products/physical-product-for-excel', 'Admin\ProductController@getPhysicalProductForExcel');
    Route::post('products/import-digital', 'Admin\ProductController@importDigitalProduct');
    Route::post('products/page-load-data', 'Admin\ProductController@pageLoadData')->name('productsPageLoadData');
    Route::get('products/get-brands', 'Admin\ProductController@getBrands');
    Route::get('products/get-categories', 'Admin\ProductController@getCategories');
    Route::get('products/collections', 'Admin\ProductController@collections');
    Route::post('products/search', 'Admin\ProductController@searchProduct');
    Route::post('product/detail', 'Admin\ProductController@productDetail');
    Route::post('products/update_records', 'Admin\ProductController@updateRecords');
    Route::post('products/update_items', 'Admin\ProductController@updateItems');
    Route::post('products/search-brands', 'Admin\ProductController@searchBrands');
    Route::resource('products', 'Admin\ProductController');
    Route::post('products/list', 'Admin\ProductController@getListing');
    Route::post('products/upload-files', 'Admin\ProductController@uploadFiles')->name('uploadFiles');
    Route::post('products/delete-file', 'Admin\ProductController@deleteFile')->name('deleteImage');
    Route::post('products/save/{tab_ID}', 'Admin\ProductController@store')->name('store');
    Route::get('products/{id}/{tab_ID}', 'Admin\ProductController@show')->name('show');
    Route::post('products/status/{productId}', 'Admin\ProductController@updateStatus');
    Route::post('products/upload-product-images/{productId}/{optionId?}', 'Admin\ProductController@uploadProductImages')->name('uploadProductImages');
    Route::post('products/upload-digital-file', 'Admin\ProductController@uploadDigitalFile')->name('uploadDigitalFile');
    Route::post('products/delete/digital-file', 'Admin\ProductController@deleteDigitalFile')->name('deleteDigitalFile');
    Route::post('products/update-file', 'Admin\ProductController@updateFile');


    // Admins Product Category managements
    Route::post('categories/import', 'Admin\ProductCategoryController@import');
    Route::get('categories/export', 'Admin\ProductCategoryController@export');
    Route::post('categories/category-for-excel', 'Admin\ProductCategoryController@categoryForExcel');
    Route::get('categories/get-last-id', 'Admin\ProductCategoryController@getLastId');
    Route::post('categories/excel-update', 'Admin\ProductCategoryController@categoryExcelUpdate');
    Route::resource('product/categories', 'Admin\ProductCategoryController');
    Route::post('product/categories/drag', 'Admin\ProductCategoryController@updateOnDrag')->name('updateOnDrag');
    Route::get('categories/search', 'Admin\ProductCategoryController@searchRecord');
    Route::post('product/categories/list', 'Admin\ProductCategoryController@getListing');
    Route::get('categories/parent/{id?}', 'Admin\ProductCategoryController@getParentList');
    Route::post('categories/status/{id?}', 'Admin\ProductCategoryController@updateStatus');

    // Admins Tax managements
    Route::resource('tax-group', 'Admin\TaxGroupController');
    Route::post('tax-group/page-load-data', 'Admin\TaxGroupController@pageLoadData')->name('taxPageLoadData');
    Route::post('tax-group/get-states', 'Admin\TaxGroupController@getStatesById')->name('getStatesById');
    Route::post('tax-group/list', 'Admin\TaxGroupController@getListing');

    //Blogs Managements
    Route::get('blog/categories/parent/{id?}', 'Admin\BlogPostCategoryController@getParentList');
    Route::resource('blog/categories', 'Admin\BlogPostCategoryController', ['as' => 'blogCategories']);
    Route::post('blog/categories/list', 'Admin\BlogPostCategoryController@getListing');
    Route::post('blog/categories/drag', 'Admin\BlogPostCategoryController@updateOnDrag');
    Route::post('blog/categories/status/{id?}', 'Admin\BlogPostCategoryController@updateStatus');

    Route::get('blogs/settings', 'Admin\BlogPostController@getSettings');
    Route::resource('blogs', 'Admin\BlogPostController');
    Route::post('blogs/list', 'Admin\BlogPostController@getListing');
    Route::post('blogs/settings/save', 'Admin\BlogPostController@saveSettings');
    Route::post('blogs/page-load-data', 'Admin\BlogPostController@pageLoadData');
    Route::post('blogs/status/{id}', 'Admin\BlogPostController@updateStatus');

    Route::post('editor/images', 'Admin\AdminController@uploadEditorImages');

    Route::resource('blog/comments', 'Admin\BlogPostCommentController');
    Route::post('blog/comments/list', 'Admin\BlogPostCommentController@getListing');
    Route::post('blog/comments/status/{id}', 'Admin\BlogPostCommentController@updateStatus');

    Route::get('/setting/get-business-settings', 'Admin\ConfigurationController@getBusinessSettings');
    Route::get('settings/{type}', 'Admin\ConfigurationController@getSettings');
    Route::post('settings/get', 'Admin\ConfigurationController@getSettings');
    Route::post('settings/{type?}', 'Admin\ConfigurationController@updateSettings');
    Route::delete('instagramfeed/remove/{handle}', 'Admin\AdminController@removeInstaHandle');

    // Pickup Management
    Route::resource('store-address', 'Admin\StoreAddressController');
    Route::post('store-address/list', 'Admin\StoreAddressController@getListing');
    Route::post('store-address/page-load-data', 'Admin\StoreAddressController@pageLoadData');
    Route::post('store-address/get-states', 'Admin\StoreAddressController@getStates');
    //Faqs Management
    Route::resource('faqs', 'Admin\FaqController');
    Route::post('faqs/list', 'Admin\FaqController@getListing');
    Route::post('faqs/status/{id}', 'Admin\FaqController@updateStatus');

    //Meta tags Management
    Route::post('metatags/modulelist', 'Admin\MetaTagController@getListing');
    Route::post('metatags/get', 'Admin\MetaTagController@getRecord');
    Route::post('metatags/update', 'Admin\MetaTagController@updateRecord');
    Route::get('metatags/update-meta-tags', 'Admin\MetaTagController@updateMetaTags');

    //Image tags Management
    Route::post('imagetags/modulelist', 'Admin\ImageAttributesController@getListing');
    Route::post('imagetags/get', 'Admin\ImageAttributesController@getRecord');
    Route::post('imagetags/update', 'Admin\ImageAttributesController@updateRecord');

    //Url Rewriting Management
    Route::post('urlrewrites/modulelist', 'Admin\UrlRewriteController@getListing');
    Route::post('urlrewrites/update', 'Admin\UrlRewriteController@updateRecord');
    Route::post('urlrewrites/enable301', 'Admin\UrlRewriteController@enable301');
    Route::get('urlrewrites/update-record-id', 'Admin\UrlRewriteController@updateRecordId');
    Route::get('urlrewrites/update-url-rewrite', 'Admin\UrlRewriteController@updateUrlRewrite');
 
    //Buy together Products Management
    Route::post('buytogether', 'Admin\BuyTogetherProductController@getListing');
    Route::get('buytogether/search', 'Admin\BuyTogetherProductController@getProducts');
    Route::get('buytogether/get', 'Admin\BuyTogetherProductController@getBuyTogetherProducts');
    Route::post('buytogether/update', 'Admin\BuyTogetherProductController@update');
    Route::post('buytogether/delete', 'Admin\BuyTogetherProductController@delete');

    //Related Products Management
    Route::post('relatedproducts', 'Admin\RelatedProductController@getListing');
    Route::get('relatedproducts/search', 'Admin\RelatedProductController@getProducts');
    Route::get('relatedproducts/get', 'Admin\RelatedProductController@getRelatedProducts');
    Route::post('relatedproducts/update', 'Admin\RelatedProductController@update');
    Route::post('relatedproducts/delete', 'Admin\RelatedProductController@delete');

    //Testimonials management
    Route::resource('testimonials', 'Admin\TestimonialController');
    Route::post('testimonials/list', 'Admin\TestimonialController@getListing');
    Route::post('testimonials/status/{id}', 'Admin\TestimonialController@updateStatus');
    Route::post('testimonials/deleteimage/{id}', 'Admin\TestimonialController@deleteImage');


    //Orders managements
    Route::get('orders/export', 'Admin\OrderController@export');
    Route::post('orders/get-states/', 'Admin\OrderController@getStates');
    Route::get('orders/coupons', 'Admin\OrderController@getCoupons');
    Route::get('orders/page-load-data', 'Admin\OrderController@pageLoadData');
    Route::post('orders/coupon/apply', 'Admin\OrderController@applyCoupon');
    Route::post('orders/order-summary', 'Admin\OrderController@orderSummary');
    Route::post('orders/address/save', 'Admin\OrderController@saveAddress');
    Route::post('orders/save', 'Admin\OrderController@saveOrder');
    Route::post('orders/invoice-exist', 'Admin\OrderController@validateInvoiceByNumber');
    Route::get('return-order/{id}/{requestId}', 'Admin\OrderController@ykReturnOrder')->name('userReturnOrder');
    Route::post('users/orders/{type}/{userId}', 'Admin\OrderController@orderRequests')->name('orderRequests');

    Route::post('order/payment-url', 'Admin\OrderController@paymentLink');
    Route::resource('orders', 'Admin\OrderController');

    Route::post('orders/list', 'Admin\OrderController@getListing');
    Route::post('orders/request-details/{id}', 'Admin\OrderController@requestDetails');
    Route::post('orders/create-request', 'Admin\OrderController@generateRequest');
    Route::post('orders/pending-requests', 'Admin\OrderController@pendingRequestCount');
    Route::post('orders/upload-digital-file', 'Admin\OrderController@uploadDigitalFile');

    Route::post('orders/digital-additional-upload', 'Admin\OrderController@digitalAdditionalUpload');
    Route::post('orders/return/list', 'Admin\OrderController@getRetrunListing');
    Route::post('orders/return/status/{id}', 'Admin\OrderController@updateReturnStatus');
    Route::post('orders/complete/{id}', 'Admin\OrderController@orderComplete');
    Route::post('orders/return', 'Admin\OrderController@orderReturn');
    Route::post('orders/comments', 'Admin\OrderController@comments');
    Route::post('orders/update-notes', 'Admin\OrderController@updateNote');
    Route::post('orders/update-courier-info', 'Admin\OrderController@updateCourierInfo');
    Route::post('orders/payment', 'Admin\OrderController@savePaymentDetail');
    Route::post('orders/retun/approved', 'Admin\OrderController@returnApproved');
    Route::post('orders/tags', 'Admin\OrderController@saveTags');
    Route::post('orders/load-comments', 'Admin\OrderController@getComments');
    Route::post('orders/load-more', 'Admin\OrderController@loadMore');
    Route::post('orders/status/{id}', 'Admin\OrderController@updateStatus');
    Route::post('orders-listing', 'Admin\OrderController@getOrdersListing');
    Route::get('orders/get/{id}', 'Admin\OrderController@getOrderDetails');
    Route::get('orders/user/address/{userId}', 'Admin\OrderController@getOrderAddress');
    Route::get('orders/download-packing-slip/{orderId}', 'Admin\OrderController@downloadPackingSlip');
    Route::post('orders/orders-export', 'Admin\OrderController@ordersExport');
    Route::post('orders/invoice-export', 'Admin\OrderController@invoiceExport');

    Route::post('orders/get-tracking-information', 'Admin\OrderController@getTrackingInformation');

    //Product reviews managements
    Route::post('product-reviews/list/{product_id}', 'Admin\ProductReviewController@getListing');
    Route::post('product-reviews/status/{id}', 'Admin\ProductReviewController@updateStatus');
    Route::post('product-reviews/moderate', 'Admin\ProductReviewController@updateReviewStatus');
    Route::post('product-reviews/moderate-edited', 'Admin\ProductReviewController@updateEditedReviewStatus');
    Route::get('/product-reviews/detail/{preview_id}', 'Admin\ProductReviewController@detail');
    Route::post('/product-reviews/reply', 'Admin\ProductReviewController@postReply');

    //Special price management
    Route::post('specialprices/list', 'Admin\SpecialpriceController@getListing');
    Route::post('specialprices/status/{id}', 'Admin\SpecialpriceController@updateStatus');
    Route::get('specialprices/search', 'Admin\SpecialpriceController@getRecords');
    Route::get('specialprices/included', 'Admin\SpecialpriceController@getIncluded');
    Route::resource('specialprices', 'Admin\SpecialpriceController');

    // analytics
    Route::get('analytics', 'Admin\AnalyticsController@getListing');
    Route::post('analytics/store/{type}', 'Admin\AnalyticsController@store');
    Route::get('googleanalytics/callbackUrl', 'Admin\AnalyticsController@handleRedirectUrl')->name('googleCallBackUrl');
    Route::get('googleanalytics/authenticate', 'Admin\AnalyticsController@authenticate');
    Route::post('googleanalytics/upload-service-credentials', 'Admin\AnalyticsController@uploadServiceCredentails');
    Route::delete('googleanalytics/remove-credentails', 'Admin\AnalyticsController@removeCredentailFile');
    Route::get('googleanalytics/view-analytics-credentails', 'Admin\AnalyticsController@viewAnalyticsCredentailFile');

    Route::get('sitemaps/generate', 'Admin\SiteMapsController@generate');
    Route::get('sitemaps/view', 'Admin\SiteMapsController@view');


    Route::post('special-prices/list', 'Admin\SpecialPriceController@getListing');
    Route::post('special-prices/status/{id}', 'Admin\SpecialPriceController@updateStatus');
    Route::post('special-prices/search', 'Admin\SpecialPriceController@getRecords');
    Route::get('special-prices/included', 'Admin\SpecialPriceController@getIncluded');
    Route::resource('special-prices', 'Admin\SpecialPriceController');

    //Discount coupons management
    Route::post('discount-coupons/list', 'Admin\DiscountCouponController@getListing');
    Route::post('discount-coupons/status/{id}', 'Admin\DiscountCouponController@updateStatus');
    Route::get('discount-coupons/search', 'Admin\DiscountCouponController@getRecords');
    Route::post('discount-coupons/check/{id?}', 'Admin\DiscountCouponController@checkDiscountCoupon');
    Route::get('discount-coupons/included', 'Admin\DiscountCouponController@getIncluded');
    Route::resource('discount-coupons', 'Admin\DiscountCouponController');

    //Reward points settings
    Route::get('reward-points/get', 'Admin\RewardPointController@get');
    Route::post('reward-points/update', 'Admin\RewardPointController@update');


    //Language settings
    Route::post('languages/list', 'Admin\LanguageController@getListing');
    Route::get('/js/lang.js', 'LanguageController@adminLangData')->name('assets.lang');


    Route::post('languages/status/{id}', 'Admin\LanguageController@updateStatus');
    Route::post('languages/markasdefault/{id}', 'Admin\LanguageController@markAsDefault');
    Route::delete('languages/{id}', 'Admin\LanguageController@deleteLanguage');
    Route::post('languages/save', 'Admin\LanguageController@saveLanguage');
    Route::get('languages/export', 'Admin\LanguageController@export');
    Route::post('languages/import', 'Admin\LanguageController@import');

    Route::post('languageslabels/list', 'Admin\LanguageController@languageslabels')->name('languageslabels');
    Route::post('languageslabels/get', 'Admin\LanguageController@getLabelDataByKey');
    Route::post('languageslabels/save', 'Admin\LanguageController@saveLabelDataByKey');

    Route::post('languageslabels/mobile/list', 'Admin\LanguageController@languageslabelsMobile')->name('languageslabelsMobile');
    Route::post('languageslabels/mobile/get', 'Admin\LanguageController@getLabelMobileDataByKey');
    Route::post('languageslabels/mobile/all', 'Admin\LanguageController@getAppLanguageLabelData');
    Route::post('languageslabels/mobile/save', 'Admin\LanguageController@saveLabelMobileDataByKey');
    Route::get('languages/mobile/export', 'Admin\LanguageController@exportMobileData');
    Route::post('languages/mobile/import', 'Admin\LanguageController@importMobileData');
    Route::post('app-explore/get-data', 'Admin\AppExploreController@getData');
    Route::post('app-explore/delete', 'Admin\AppExploreController@delete');
    // Chat Threads
    Route::post('messages/chat-threads', 'Admin\AdminMessagesController@getAllthreadsWithLastMessage');
    Route::get('messages/get-thread-messages/{id}', 'Admin\AdminMessagesController@getAllthreadMessage');
    //Route::get('messages/read/{threadId}', 'Admin\AdminMessagesController@readMessages');
    Route::post('messages/send-message', 'Admin\AdminMessagesController@sendMessage');

    // user requests
    Route::post('userRequests/list', 'Admin\UserRequestsController@getListing');

    Route::post('/get-logo-details', 'Admin\AdminController@getLogoDetails');
    Route::post('/update-admin-logo', 'Admin\AdminController@updateAdminLogo');
    Route::post('/update-frontend-logo', 'Admin\AdminController@updateFrontendLogo');
    Route::post('/update-favicon', 'Admin\AdminController@updateFavicon');
    Route::get('/delete-favicon', 'Admin\AdminController@deleteFavicon');

    //social sharing(ShareThis) settings
    Route::get('sharethis', 'Admin\AdminController@getSharethisNetworks');
    Route::post('sharethis', 'Admin\AdminController@updateSharethisNetworks');

    //upload bingwebmaster xml file
    Route::get('bingwebmaster', 'Admin\SeoController@getBingWebmasterXml');
    Route::delete('bingwebmaster', 'Admin\SeoController@deleteBingWebmasterXml');
    Route::post('bingwebmaster', 'Admin\SeoController@updateBingWebmasterXml');

    //upload googlewebmaster xml file
    Route::get('googlewebmaster', 'Admin\SeoController@getGoogleWebmasterHtml');
    Route::delete('googlewebmaster', 'Admin\SeoController@deleteGoogleWebmasterHtml');
    Route::post('googlewebmaster', 'Admin\SeoController@updateGoogleWebmasterHtml');

    //System status for get started module
    Route::get('system/status', 'Admin\AdminController@getSystemStatus');
    Route::get('getstarted/skip', 'Admin\AdminController@skipGetstarted');
    // Dashboard APIs
    Route::get('getTotalSalesByType/{type}', 'Admin\DashboardController@getTotalSalesByType');
    Route::get('getTotalOrdersByType/{type}', 'Admin\DashboardController@getTotalOrdersByType');
    Route::get('getProfitByType/{type}', 'Admin\DashboardController@getProfitByType');
    Route::get('DashboardReportsData/{type}', 'Admin\DashboardController@getDashboardReportsData');
    Route::get('getTopReferrers', 'Admin\DashboardController@getTopReferrers');
    Route::get('getDataByLocation', 'Admin\DashboardController@getDataByLocation');

    Route::get('dashboard-data', 'Admin\DashboardController@getDashboardData');

    Route::get('/clear-cache', 'Admin\DashboardController@clearCache');
    Route::get('email-archieves/details/{id}', 'Admin\EmailArchivesController@getEmailDetails');
    Route::post('email-archieves/list', 'Admin\EmailArchivesController@getListing');

    Route::get('sub-admin-dashboard', 'Admin\AdminController@getSubAdminDashboardData');

    // Reports Section
    Route::group(['prefix' => 'reports'], function () {
        // Sale Report
        Route::post('get-sale-reports', 'Admin\ReportController@getSaleReports');
        // Product Report
        Route::post('/get-profit-by-product-report', 'Admin\ReportController@getProfitByProductReport');
        // Acquisiton Report
        Route::post('/get-acquisiton-report', 'Admin\ReportController@getAcquisitonReport');
        // Behavior Report
        Route::post('/get-behavior-report', 'Admin\ReportController@getBehaviorReport');
        // Customer report
        Route::post('/get-customer-report', 'Admin\ReportController@getCustomerReport');
        Route::post('/get-first-vs-return-customer', 'Admin\ReportController@getFirstVsReturnCustomer');
        Route::post('/get-customer-over-time', 'Admin\ReportController@getCustomerOverTime');
    });
    //Todo module
    Route::get('todos/{row?}', 'Admin\TodoController@getTodos');
    Route::post('todos', 'Admin\TodoController@store');
    Route::post('todos/complete/{id?}', 'Admin\TodoController@updateStatus');
    Route::post('todos/reminder/{id?}', 'Admin\TodoController@setReminder');
    Route::delete('todos/{id?}', 'Admin\TodoController@delete');

    //Admin
    Route::get('get-admin-data', 'Admin\AdminController@getAdminData');
    Route::get('get-timzone', 'Admin\ConfigurationController@getTimeZoneArr');

    //Reasons Management
    Route::resource('reasons', 'Admin\ReasonController');
    Route::post('reasons/list', 'Admin\ReasonController@getListing');
    Route::post('reasons/status/{id}', 'Admin\ReasonController@updateStatus');

    // Import Export Media
    Route::post('importexport/media-zip', 'Admin\ImportExportController@uploadExtractZip');
    Route::get('importexport/download-sheet/{module_type}', 'Admin\ImportExportController@downloadSheet');
    Route::post('importexport/upload-sheet/{module_type?}', 'Admin\ImportExportController@uploadSheet');

    // User Groups managements
    Route::resource('groups', 'Admin\UserGroupController');
    Route::post('groups/list', 'Admin\UserGroupController@getListing');
    Route::post('groups/update-members', 'Admin\UserGroupController@updateMembers');
    Route::post('groups/records/{ugroup_id?}', 'Admin\UserGroupController@getRecords');
    Route::post('groups/sourcelist/{ugroup_id?}', 'Admin\UserGroupController@getSourceListing');
    Route::post('groups/destinationlist/{ugroup_id}', 'Admin\UserGroupController@getDestinationListing');
    Route::post('groups/sendmail/{group_id}', 'Admin\UserGroupController@sendMail');
    Route::post('groups/moveAll-user-todestination/{ugroup_id?}', 'Admin\UserGroupController@moveUserSourceToDestination');
    Route::post('groups/moveAll-user-tosource/{ugroup_id?}', 'Admin\UserGroupController@moveUserDestinationToSource');

    Route::delete('remove-attached-files/{fileId}', 'Admin\AttachedFileController@deleteFile');

    Route::get('api-test', 'Admin\ShippingController@getAfterShippingCouriersData');

    ////
    Route::get('resources', 'Admin\ResourceController@getListing');
    Route::post('resources/list', 'Admin\ResourceController@getListing');
    Route::post('resources/search', 'Admin\ResourceController@search');
    Route::post('resource/details', 'Admin\ResourceController@details');

    Route::group(['prefix' => 'app'], function () {
        Route::post('/recrods', 'Admin\AppCollectionController@index');
        Route::post('/store/records', 'Admin\AppCollectionController@store');
        Route::post('/delete/image', 'Admin\AppCollectionController@deleteImage');
        Route::post('/delete/collection', 'Admin\AppCollectionController@deleteCollection');
        Route::post('/delete/record', 'Admin\AppCollectionController@deleteRecord');
        Route::post('/collection', 'Admin\AppCollectionController@getCollections');
        Route::post('/upload-images', 'Admin\AppCollectionController@uploadImages');
        Route::post('/collection/search-records', 'Admin\AppCollectionController@searchRecords');
        Route::post('/collection/sorting-update', 'Admin\AppCollectionController@updateSorting');
    });

});

Route::get('/queue/start', function () {
    Artisan::call("queue:work", [
        '--queue' => 'high,normal,default,low',
        '--stop-when-empty' => true,
        '--tries' => 3
    ]);
});
Route::get('/cache/clear', function () {
    Artisan::call("cache:clear");
    Artisan::call("config:clear");
    Artisan::call("route:clear");
    Artisan::call("view:clear");
    echo 'all caches cleared';
});
/*
Route::get('todos/cron', function () {
    $exitCode = Artisan::call('todoreminder:send');
});
Route::get('rewardpoints/cron', function () {
    Artisan::call('birthdayrewardpoints:add');
});
Route::get('cron/rewardpoints/expire', function () {
    Artisan::call('rewardpoints:expire');
});
Route::get('phpinfo', function () {
    phpinfo();
});*/

Route::get('/payment/{id}', 'HomeController@createPayment');
Route::post('/make/{payment}/payment/{id}', 'OrderController@makePayment')->name('makePayment');
Route::get('/testEmail', 'HomeController@testEmail');
Route::get('languages', 'LanguageTranslationController@index')->name('languages');
Route::post('translations/update', 'LanguageTranslationController@transUpdate')->name('translation.update.json');
Route::post('translations/updateKey', 'LanguageTranslationController@transUpdateKey')->name('translation.update.json.key');
Route::delete('translations/destroy/{key}', 'LanguageTranslationController@destroy')->name('translations.destroy');
Route::post('translations/create', 'LanguageTranslationController@store')->name('translations.create');
Route::get('lang/{lang}', 'LanguageController@switchLang')->name('lang.switch');

Route::get('additional-details/verification/{socialId}', 'Auth\LoginController@additionalDetailsSuccessPage');
Route::get('additional-details/{serviceType}/{socialId}', 'Auth\LoginController@enterAdditionalDetails');
Route::post('additional-details', 'Auth\LoginController@saveAdditionalDetails')->name('saveAdditionalDetails');

Route::post('additional-details/resendcode', 'Auth\LoginController@resendVerificationCode');
Route::post('additional-details/verify', 'Auth\LoginController@verifyCode');

Route::get('authenticate/{userId}', function ($userId) {
    Auth::loginUsingId($userId);
    return redirect('/');
});

Route::get('register/success/{slug}/{type}', 'Auth\RegisterController@registerSuccessPage');
Route::post('account/verification', 'Auth\RegisterController@resendVerificationCode')->name('resendVerificationCode');
Route::post('account/verify', 'Auth\RegisterController@verifyCode')->name('verifyCode');
Route::get('account/verification/{slug}/{type}', 'Auth\RegisterController@accountVerificationPage');


// Installer Routes
Route::get('/installer', 'InstallerController@index');
Route::get('installer-languages', 'InstallerController@getLanguages');
Route::get('installer/countries', 'InstallerController@getCountries');
Route::post('installer/states', 'InstallerController@getStates');
Route::post('installer/update-config', 'InstallerController@updateConfig');
Route::get('installer/check-requirements', 'InstallerController@checkRequirements');
Route::get('installer/check-storage-permission', 'InstallerController@checkStoragePermission');
Route::get('installer/currency-and-languages', 'InstallerController@getCurrencyWithLanguage');
Route::post('installer/save-installer-data', 'InstallerController@saveFileWizard');
Route::post('installer/migration-seeding-data', 'InstallerController@migrationAndSeeding');
Route::get('/js/installer-lang.js', 'InstallerController@installerLanguage')->name('assets.installer-lang');
Route::get('installer/lang/{lang}', 'InstallerController@switchLang')->name('installerlang.switch');

Auth::routes();

Route::post('change-email/send-code', 'UserController@resendEmailVerificationCode')->name('resendEmailVerificationCode');
Route::post('change-email/verify-code', 'UserController@verifyUpdatedEmail')->name('verifyUpdatedEmail');
Route::post('change-phone/send-code', 'UserController@resendPhoneVerificationCode')->name('resendPhoneVerificationCode');
Route::post('change-phone/verify-code', 'UserController@verifyUpdatedPhone')->name('verifyUpdatedPhone');
Route::post('change-phone/verify-code-directly', 'UserController@updatePhoneDirectly');

//Forgot and reset password
Route::get('forgot-password', 'Auth\ForgotPasswordController@forgotPassword')->name('forgotPassword');
Route::post('forgot-password', 'Auth\ForgotPasswordController@forgotPassword')->name('forgotPasswordPost');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('resetEmail');
Route::post('password/phone-number', 'Auth\ForgotPasswordController@sendResetLinkPhone')->name('resetPhone');
Route::post('password/otp-verify', 'Auth\ForgotPasswordController@userOtpVerify');
Route::get('password/reset/{token}/{via}', 'Auth\ResetPasswordController@showResetForm');
Route::post('reset-password/verification/{via}', 'Auth\ResetPasswordController@resendVerificationCode')->name('resendPasswordVerificationCode');
Route::post('reset-password/verify', 'Auth\ResetPasswordController@verifyCode')->name('verifyPasswordVerificationCode');
Route::post('password/reset', 'Auth\ResetPasswordController@resetPassword')->name('passwordUpdate');
//login
Route::post('login', 'Auth\LoginController@frontendLogin')->name('frontendLogin')->middleware("throttle:1");
Route::any('logout', 'Auth\LoginController@logout')->name('userLogout');
//social login
Route::get('/redirect/{service}', 'Auth\LoginController@redirect');
Route::get('/callback/instagram', 'Auth\LoginController@instagramProviderCallback');
Route::get('/callback/{service}', 'Auth\LoginController@callback');
//other frontend routes

Route::post('/product/favourite', 'UserFavouriteProductController@updateFavourite')->name('updateFavourite');
Route::post('/saved/product/remove', 'UserSavedProductController@destroy')->name('savedItemRemoved');
Route::post('/error/products/remove', 'UserSavedProductController@destroyAll')->name('removedALLProduct');



Route::get('/pwa-manifest', function () {
    return view('pwa-manifest');
})->name('pwaManifest');
Route::get('/offline.html', function () {
    return view('offline');
});
Route::post('/newsletter', 'NewsletterController@subscribe')->name('newsletterPost');
Route::get('newsletter/confirm/{token}', 'NewsletterController@confirmNewsletter');
Route::get('newsletter/unsubscribe/{token}', 'NewsletterController@unsubscribeNewsletter');

Route::get('/yokart/image/{section}/{recordid}/{subrecordid}/{size}/{timestamp?}', 'HomeController@showImage');
Route::get('/yokart/image/{afileId}/{size?}', 'HomeController@showImageById');
Route::get('/yokart/app/product/image/{productId}/{variantCode}/{size?}', 'HomeController@showProductImageForApp');
Route::get('/image/apple-touch-icon/{section}/{subrecordid}/{size}/{timestamp?}', 'HomeController@showImage');


Route::get('/noimages/{path}', 'HomeController@showNoImage')->where('path', '.*');
Route::get('/yokart/product/image/{productId}/{variantCode}/{size}', 'HomeController@showProductImage');
Route::get('/image/{section}/{recordid}/{wSize}/{hSize}', 'HomeController@showImageBySize');
// Route::get('/instagram/authenticate', 'HomeController@instaCallback');
Route::any('/products', 'ProductController@index')->name('productListing');
Route::post('/products/filters/{type}', 'ProductController@loadFilters')->name('loadFilters');

//Route::get('/productsnew', 'ProductController@indexNew')->name('productListing1');

Route::any('/collection/{pageid}/{cid?}', 'ProductController@collectionListing')->name('collectionListing');

Route::get('blogs/load-sidebar-content', 'BlogController@sidebarContent');
Route::post('blog/comment/save', 'BlogPostCommentController@store')->name('storeComment');
Route::get('blogs/{categoryId?}', 'BlogController@index')->name('blogListing');
Route::get('blog/{id}', 'BlogController@show')->name('blogDetail');

Route::any('/category/{id}', 'CategoryController@index')->name('categoryListing');
Route::get('/brand/{id}', 'BrandController@index')->name('brandListing');
//Route::get('/product/discount/{code}', 'ProductController@discountListing')->name('discountProductsListing');
Route::post('/product/product-by-filter', 'ProductController@filterResult')->name('filterResult');
Route::get('/product/{id}/{code}/ask-questions', 'ProductController@askQuestions')->name('askQuestions');
Route::post('/product/submit-questions', 'ProductController@submitQuestion')->name('submitQuestion');
Route::post('/product/product-varient-price', 'ProductController@varientPrice')->name('varientPrice');
Route::post('/product/validate-shipping', 'ProductController@validateShippingLocation')->name('validateShippingLocation');
Route::get('/product/{id}/{subid?}', 'ProductController@detail')->name('productDetail');
Route::post('/product/filters', 'ProductController@filters')->name('productFilters');
//Route::post('/product/filtersNew', 'ProductController@filtersNew')->name('productfiltersNew');
Route::post('/product/get-images', 'ProductController@getImages')->name('getImages');
Route::post('/product/get-gallery-images', 'ProductController@getGalleryImages');
Route::post('/product/add-to-cart', 'CartController@addToCart')->name('addToCart');
Route::post('/product/move-to-cart', 'CartController@updateSavedProduct')->name('moveToCart');
Route::post('/cart/get-coupons', 'CartController@getCoupons')->name('getCoupons');
Route::post('/product/saved-for-later', 'CartController@savedCartItems')->name('savedForLater');
Route::post('/cart/shippingUpdate/{type}', 'CartController@updateShipping')->name('updateShipping');




Route::get('/cart', 'CartController@index')->name('cartindex');
Route::post('/cart/coupon', 'CartController@applyCoupon')->name('applyCoupon');
Route::post('/cart/update', 'CartController@update')->name('updateCart');
Route::post('/cart/item-remove', 'CartController@removeItem')->name('removeItem');
Route::post('/cart/coupon-remove', 'CartController@removeCoupon')->name('removeCoupon');
Route::post('/cart/quick-view', 'CartController@quickView')->name('quickView');
Route::post('/cart/gift-items/{validate}', 'CartController@updateGiftItem')->name('updateGiftItem');
Route::get('/cart/gift-messages/{id}', 'CartController@giftItemMessages')->name('giftItemMessages');

Route::get('/checkout', 'CheckoutController@index')->name('checkout');
Route::get('/checkout/cart-summary', 'CheckoutController@cartSummary')->name('cartSummary');
Route::post('/checkout/shippingInfo', 'CheckoutController@shippingInfo')->name('shippingInfo');
Route::post('/checkout/cal-shipping-tax', 'CheckoutController@calShippingTax')->name('calShippingTax');
Route::post('/checkout/shipping-update', 'CheckoutController@shippingUpdate')->name('shippingUpdate');
Route::post('/checkout/get-states', 'CheckoutController@getStates')->name('getStates');
Route::post('/checkout/address/save', 'CheckoutController@saveAddress')->name('saveAddress');
Route::post('/checkout/pickup-address', 'CheckoutController@pickupAddress')->name('pickupAddress');
Route::post('/checkout/pickup-time-slots', 'CheckoutController@pickupTimeSlots')->name('pickupTimeSlots');
Route::get('/checkout/pickup-days/{id}', 'CheckoutController@pickupDays');
Route::post('/checkout/apply-reward-points', 'CheckoutController@applyRewardPoints');
Route::post('/checkout/remove-reward-points', 'CheckoutController@removeRewardPoints');
Route::post('/checkout/payment-section', 'CheckoutController@paymentSection')->name('paymentSection');
Route::post('/checkout/set-pickup-address', 'CheckoutController@setPickupAddress')->name('savePickupAddress');
Route::get('order/bank-form/{id}', 'OrderController@getbankDetails');
Route::get('order/download-pdf/{id}', 'OrderController@downloadInvoice')->name('downloadInvoice');
Route::post('order/bank-form', 'OrderController@submitBankInformation');
Route::post('/checkout/request-otp', 'CheckoutController@requestOtp');
Route::post('/checkout/resent-request-otp', 'CheckoutController@resendOtpRequest');
Route::post('/checkout/verify-otp', 'CheckoutController@verifyOtpRequest');
Route::get('/checkout/get-addressform/{addressId?}', 'CheckoutController@getAddressForm');
Route::get('/checkout/get-billing-address', 'CheckoutController@getBillingAddress');
Route::get('/checkout/get', 'CheckoutController@getFirstStep');
Route::post('/checkout/update-tax', 'CheckoutController@updateTaxAmount');
Route::get('/checkout/add-card-popup', 'CheckoutController@addNewCard');
Route::resource('order', 'OrderController');
Route::post('product/download/url', 'OrderController@downloadFiles');
Route::post('order/notes/{id}', 'OrderController@saveNotes');
Route::post('order/received', 'OrderController@orderConfirmation');
Route::get('order/success/{orderId}/{currencyCode?}/{currencyValue?}', 'OrderController@success')->name('successPage');
Route::get('order/error/{orderId}', 'OrderController@success')->name('successError');
Route::get('login/{via}', 'Auth\LoginController@loginViaForm');

//User Dashboard
Route::group(['prefix' => 'user'], function () {
    Route::get('profile', 'UserController@profile')->name('userProfile');
    // Route::get('email/verify/{token}', 'UserController@verifyUpdatedEmail');
    Route::post('profile/updateImage', 'UserController@updateImage')->name('userUpdateImage');
    Route::post('profile', 'UserController@profileUpdate')->name('userProfileUpdate');
    Route::post('change-password', 'UserController@updatePassword')->name('userUpdatePassword');
    Route::get('address', 'UserController@address')->name('userAddress');
    Route::post('address/load-records', 'UserController@loadMoreAddresses');
    Route::get('address/listing', 'UserController@addressListing');
    Route::get('address/add', 'UserController@addAddress')->name('addAddress');
    Route::post('address', 'UserController@addressInfo')->name('updateAddress');
    Route::post('address/updateStatus', 'UserController@updateAddressStatus')->name('updateAddressStatus');
    Route::post('address/status-update', 'UserController@updateAddressDefaultStatus');
    Route::post('address/delete', 'UserController@deleteAddress')->name('deleteAddress');
    Route::get('address/{edit}/edit', 'UserController@editAddress')->name('editAddress');
    Route::get('favorite', 'UserController@favorite')->name('Userfavorite');
    Route::post('favorite/listing', 'UserController@favoriteListing')->name('UserfavoriteListing');
    Route::post('get-orders/{type}/', 'OrderController@getOrders');
    Route::post('get-digtal-orders', 'OrderController@getDigitalOrders');
    Route::get('orders/download/{orderId?}/{productId?}', 'OrderController@digitalProducts')->name('digitalProducts');
    Route::get('orders/{id?}/{type?}', 'OrderController@index')->name('userOrders');
    Route::post('orders/comments', 'OrderController@saveComments')->name('saveComments');
    Route::get('order/cancel/{orderId}', 'OrderController@cancelOrder')->name('cancelOrder');

    Route::get('reorder/{orderId}', 'OrderController@buyAgain')->name('buyAgain');
    Route::get('order/cancel/summary/{orderId}', 'OrderController@returnSummary')->name('returnSummary');
    Route::post('order/return-items', 'OrderController@returnItems')->name('returnItems');
    Route::post('orders/load-comments', 'OrderController@loadComments')->name('loadComments');
    Route::get('order/detail/{orderId}', 'OrderController@orderDetail')->name('orderDetail');

    Route::get('order/download/items/{orderId}/{productId}', 'OrderController@downloadItems')->name('downloadItems');
    Route::post('order/get-tracking-information', 'OrderController@getTrackingInformation');
    Route::get('messages', 'MessagesController@index')->name('messages');
    Route::get('/message/detail/{id}', 'MessagesController@getAllThreadMessages')->name('allMessages');
    Route::post('search-thread', 'MessagesController@searchThread');
    Route::post('/send-message', 'MessagesController@sendMessages')->name('sendMessages');
    Route::post('/upload-image', 'MessagesController@uploadImage')->name('uploadImage');
    Route::get('unread-message-count', 'MessagesController@getUnreadMessageCount')->name('getUnreadMessageCount');

    Route::post('update-gdpr-data', 'UserController@updateRequestGdprData')->name('updateRequestGdprData');
    Route::delete('remove-gdpr-data/{id}', 'UserController@requestRemoveGdprData')->name('requestRemoveGdprData');

    Route::get('checklogin', 'HomeController@checklogin');

    Route::get('card/add', 'UserCardController@createCard')->name('createCard');
    Route::post('savecard', 'UserCardController@saveCard');
    Route::post('card/set-default', 'UserCardController@updateDefaultCard');
    Route::get('cards', 'UserCardController@index')->name('cards');
    Route::get('cards/listing', 'UserCardController@cartListing');
    Route::delete('card/delete/{id}', 'UserCardController@cardDelete');

    Route::get('share-earn', 'RewardPointController@shareAndEarn')->name('shareAndEarn');
    Route::post('shared-invites/listing', 'RewardPointController@sharedListing');
    Route::post('shared-invites/load-records', 'RewardPointController@loadRecords');
    Route::post('send-referral', 'RewardPointController@sendReferralCode')->name('sendReferralCode');
    Route::post('save-referral-code', 'RewardPointController@saveReferralCode');
    Route::get('reward-points', 'RewardPointController@rewardPointsListing')->name('rewardPoints');
    Route::post('reward-points/listing', 'RewardPointController@rewardsListing');
    Route::get('localization', 'UserController@localization')->name('localization');
    Route::post('localization', 'UserController@localizationUpdate');
    Route::get('cookie-preferences', 'UserController@cookiePreferences')->name('cookiePreferences');
    Route::post('cookie-preferences', 'UserController@cookiePreferencesUpdate');

    Route::get('coupons', 'UserController@coupons')->name('discountCoupons');
    Route::get('coupons/listing', 'UserController@couponListing');
    Route::post('coupons/load-records', 'UserController@loadMoreCoupons');
    Route::get('discount-coupons/included', 'UserController@getIncluded');

    Route::get('reviews', 'ProductReviewController@index')->name('reviews');
    Route::post('reviews/load-records', 'ProductReviewController@loadRecords');
    Route::post('review/listing', 'ProductReviewController@listing');
    Route::post('show-review-products', 'ProductReviewController@showReviewProducts');
    Route::get('reviews/{opId}/{prodId?}/{prlId?}', 'ProductReviewController@getReview');
    Route::post('reviews/uploaded/images', 'ProductReviewController@uploadedImages');
    Route::post('reviews', 'ProductReviewController@saveOrUpdateReview');
    Route::post('reviews/images', 'ProductReviewController@uploadReviewImages');
    Route::delete('reviews/images/{imageId}', 'ProductReviewController@deleteReviewImages');
    Route::post('filter-favorite', 'UserController@filterFavourite');
    Route::get('/js/lang.js/{userId}', 'LanguageController@dashboardLangData')->name('userLang');
    Route::post('/switch-language', 'LanguageController@switchDashboardLocale');
});

Route::get('/create-tracking', 'HomeController@createTracking');

Route::post('order/payment-url', 'OrderController@paymentLink');

Route::post('/db-reset', 'RestoreController@reset')->name('restoreDatabase');
Route::get('/mobile', 'RestoreController@mobilePreview')->name('mobileSite');
Route::post('/contact', 'HomeController@saveContactEnquiry')->name('contactPost');
Route::get('/delete-popup/{id}/{type}', 'HomeController@getDeletePopup');

Route::get('/saved-cards', 'HomeController@authorizePaymentCustomer');

Route::post('/product-reviews/get', 'ProductReviewController@getProductReviews');
Route::post('/product-reviews/save', 'ProductReviewController@saveProductReview');
Route::post('/product-reviews/helpful', 'ProductReviewController@helpful');
Route::post('/product-reviews/nothelpful', 'ProductReviewController@nothelpful');


Route::get('/categories', 'CategoryController@displayAllCategories');

Route::get('/faqs', 'FaqController@index')->name('faqs');
Route::post('/faqs', 'FaqController@ajaxSearch');

Route::get('/orderPayment/sucess/paypalExpress/{id}', 'OrderController@paypalSucess')->name('paypalPaymentSuceess');
Route::post('/orderPayment/sucess/cash-free', 'OrderController@cashFreeSucess')->name('cashFreePaymentSuceess');

Route::get('/instagramfeed/auth/request', 'HomeController@instaAuthRequest');
Route::get('/instagramfeed/auth/callback', 'HomeController@instaAuthCallback');
Route::get('/page/{id?}', 'HomeController@pageRender')->name('showPage');
Route::get('/', 'HomeController@pageRender')->name('home');

Route::get('preview/{id?}', 'HomeController@pagePreview');
/**admin preview for pages */
Route::any('/dropzone', 'HomeController@dropzone');
Route::get('/ping', 'YokartController@ping');
Route::get('/site-map', 'SitemapController@index')->name('sitemapindex');
Route::post('/accept-cookies', 'HomeController@acceptCookie')->name('acceptCookies');

Route::get('image-resize', 'HomeController@imageResize');
Route::get('/viewotp', 'HomeController@viewOtp');
/**for testing purpose */

Route::get('downloadItemsByApp/{order_id}/{product_id}', ['as' => 'downloadItemsByApp', 'uses'=>'OrderController@downloadItemsByApp']);




/*route to handle url rewritten routes for products, pages, categories and blogposts*/
Route::get('/{rewrite_url}', 'HomeController@showUrlRewrittenPages')->where('rewrite_url', '.*');
