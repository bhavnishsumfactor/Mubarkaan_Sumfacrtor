<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::get('/splash-data', 'HomeController@getSplashScreenData');
    Route::get('/countries', 'HomeController@getCountries');
    Route::get('/contact-us', 'HomeController@contactUs');
    Route::get('/states/{country_id}', 'HomeController@getStates');
    Route::get('/reasons', 'HomeController@getCancellationAndReturnReasons');
    Route::get('/home', 'HomeController@index');
    Route::get('/cms', 'HomeController@cmsPages');
    Route::get('/currency', 'HomeController@currencyList');
    Route::get('/cms/page/{id?}', 'HomeController@contentPages');
    Route::get('/blogs/{id?}', 'HomeController@blogs');
    Route::post('/contact', 'HomeController@saveContactEnquiry');
    Route::get('/privacy', 'HomeController@contentPages')->name('privacy');
    Route::get('/terms', 'HomeController@contentPages')->name('terms');
    Route::group(['prefix' => 'faqs'], function () {
        Route::get('/', 'FaqController@index');
        Route::post('/detail', 'FaqController@detail');
    });
    
    Route::group(['prefix' => 'login'], function () {
        Route::post('/email', 'LoginController@loginByEmail');
        Route::post('/email/verify', 'LoginController@loginByEmailVerify');
        Route::post('/email/resend-code', 'LoginController@resendCodeOnEmail');

        Route::post('/phone', 'LoginController@loginByPhone');
        Route::post('/phone/verify', 'LoginController@loginByPhoneVerify');
        Route::post('/phone/resend-code', 'LoginController@resendCodeOnPhone');
    });

    Route::group(['prefix' => 'social-login'], function () {
        Route::post('/instagram', 'LoginController@instagramSocialLogin');
        Route::post('/additional-data', 'LoginController@saveAdditionalDetails');
        Route::post('/resend-code', 'LoginController@resendCodeForSocial');
        Route::post('/verify', 'LoginController@verifyCodeForSocial');
        Route::post('/{service}', 'LoginController@socialLogin');
    });

    Route::group(['prefix' => 'register'], function () {
        Route::post('/verify', 'RegisterController@verifyCode');
        Route::post('/resend-code', 'RegisterController@resendVerificationCode');
        Route::post('/', 'RegisterController@register');
        Route::post('/update-info', 'RegisterController@updateUserInfo');
    });


    Route::group(['prefix' => 'forgot'], function () {
        Route::post('/reset-password', 'ForgotPasswordController@resetPassword');
        Route::post('/resend-code', 'ForgotPasswordController@resendVerificationCode');
        Route::post('/verify-code', 'ForgotPasswordController@verifyCode');
        Route::post('/', 'ForgotPasswordController@forgotPassword');
    });

    Route::group(['prefix' => 'products'], function () {
        Route::post('/search', 'ProductController@autosuggest');
        Route::post('/default/search', 'ProductController@defaultSearchResults');
        Route::post('/list/{page}', 'ProductController@list');
        Route::post('/filters', 'ProductController@filters');
        Route::get('/get/{prod_id}/{pov_code?}', 'ProductController@detail');
        Route::post('/reviews/{prod_id}/{page}', 'ProductController@reviews');
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/list', 'CategoryController@list');
    });
    
    Route::group(['prefix' => 'cart'], function () {
        Route::post('/add', 'CartController@addToCart');
        Route::get('/list/{type?}', 'CartController@list');
        Route::post('/remove', 'CartController@removeFromCart');
        Route::post('/update', 'CartController@updateCart');
        Route::post('/apply-coupon', 'CartController@applyCoupon');
        Route::get('/coupons', 'CartController@getCoupons');
        Route::delete('/remove-coupon', 'CartController@removeCoupon');
        Route::delete('/product/delete', 'CartController@removeProducts');
        Route::post('/apply-giftwrap', 'CartController@updateGiftItem');
        Route::get('/gift-messages', 'CartController@giftItemMessages');
    });

    Route::middleware('auth:api')->group(function () {

        Route::post('/update-device-token', 'UserController@updateDeviceToken');

        Route::group(['prefix' => 'cart'], function () {
            Route::post('/saved-for-later', 'CartController@saveForLater');
            Route::post('/move-to-bag', 'CartController@moveToBag');
        });
        Route::post('/logout', 'LoginController@logout');
        
        Route::group(['prefix' => 'user'], function () {
            Route::get('/profile', 'UserController@profile');
            Route::post('/profile', 'UserController@updateProfile');
            Route::post('/profile/image-update', 'UserController@imageUpdate');
            Route::post('/update-password', 'UserController@updatePassword');
            Route::post('/request-gdpr', 'UserController@requestGdprData');
         
            Route::delete('/delete-account', 'UserController@deleteAccount');
            Route::delete('/delete-account', 'UserController@deleteAccount');
            Route::delete('/remove-image', 'UserController@deleteImage');
            Route::get('/notifications/{pageId}', 'UserController@notifications');
            Route::delete('/notifications/clear/{id?}', 'UserController@deleteNotifications');

            Route::group(['prefix' => 'change-email'], function () {
                Route::post('/send', 'UserController@sendEmailVerificationCode');
                Route::post('/resend', 'UserController@sendEmailVerificationCode');
                Route::post('/verify/{type}', 'UserController@verifyUpdatedEmail');
            });
            Route::group(['prefix' => 'change-phone'], function () {
                Route::post('/send', 'UserController@sendPhoneVerificationCode');
                Route::post('/resend', 'UserController@sendPhoneVerificationCode');
                Route::post('/verify', 'UserController@verifyUpdatedPhone');
                Route::post('/direct', 'UserController@updatePhoneDirectly');
            });
        });

        Route::group(['prefix' => 'favourites'], function () {
            Route::post('/list/{page}', 'FavouriteController@list');
            Route::get('/remove/{ufpId}', 'FavouriteController@remove');
            Route::post('/add', 'FavouriteController@addItem');
        });
        
        Route::group(['prefix' => 'reviews'], function () {
            Route::post('/list/{page}', 'ReviewController@list');
            Route::post('/submit', 'ReviewController@submit');
            Route::get('/get/{op_id}', 'ReviewController@getReview');
            Route::post('images', 'ReviewController@uploadReviewImages');
            Route::delete('images/{image_id}', 'ReviewController@deleteReviewImages');
        });

        Route::group(['prefix' => 'coupons'], function () {
            Route::post('/list/{page}', 'CouponController@list');
            Route::get('/get/{coupon_id}', 'CouponController@detail');
        });

        Route::group(['prefix' => 'cards'], function () {
            Route::get('/list', 'CardController@list');
            Route::post('/update-default', 'CardController@updateDefaultCard');
            Route::delete('/delete/{card_id}', 'CardController@cardDelete');
            Route::post('/add', 'CardController@saveCard');
        });

        Route::group(['prefix' => 'messages'], function () {
            Route::post('/list/{page}', 'MessageController@list');
            Route::get('/get/{thread_id}', 'MessageController@getMessageDetail');
            Route::post('/add', 'MessageController@saveMessage');
        });

        Route::group(['prefix' => 'addresses'], function () {
            Route::get('/list/{page}', 'AddressController@list');
            Route::post('/update-default', 'AddressController@updateDefaultAddress');
            Route::delete('/delete/{addr_id}', 'AddressController@delete');
            Route::post('/add-update', 'AddressController@addOrUpdate');
        });

        Route::group(['prefix' => 'rewards'], function () {
            Route::post('/list/{page}', 'RewardController@list');
        });

        Route::group(['prefix' => 'downloads'], function () {
            Route::post('/list/{page}', 'DownloadController@list');
            Route::post('/files', 'DownloadController@downloadFiles');
        });

        Route::group(['prefix' => 'orders'], function () {
            Route::post('/list/{page?}', 'OrderController@orders');
            Route::post('/payment/update', 'OrderController@updatePaymentDetails');
            Route::get('/invoice/{order_id}', 'OrderController@getInvoice');
            Route::post('/returns/{page?}', 'OrderController@returns');
            Route::post('/get-return-data', 'OrderController@getReturnData');
            Route::get('/comments/{order_id}/{type}/{page}', 'OrderController@loadOrderComments');
            Route::get('/returns/comments/{orrequest_id}/{page}', 'OrderController@loadReturnComments');
            Route::post('/comments/{order_id}', 'OrderController@saveOrderComment');
            Route::post('/returns/comments/{orrequest_id}', 'OrderController@saveReturnComment');
            Route::post('/return', 'OrderController@returnItems');
            Route::post('/buy-again', 'OrderController@buyAgain');
            Route::post('/return/summary', 'OrderController@getReturnSummary');
        });

        Route::group(['prefix' => 'products'], function () {
            Route::post('/review/{preview_id}', 'ProductController@markAsHelpfulOrNot');
            Route::post('/ask-question', 'ProductController@askQuestion');
            Route::post('/remove-search', 'ProductController@removeSearchTerm');
        });
        
        Route::group(['prefix' => 'sharenearn'], function () {
            Route::get('/list/{page}', 'ShareAndEarnController@list');
            Route::get('/summary', 'ShareAndEarnController@summary');
            Route::post('/send', 'ShareAndEarnController@sendInvites');
        });

        Route::group(['prefix' => 'checkout'], function () {
            Route::get('/step1/{type}', 'CheckoutController@getStep1Data');
            Route::post('/address/add-update', 'CheckoutController@addOrUpdateAddress');
            Route::post('/pickup-time-slots', 'CheckoutController@getpickupTimeSlots');
            Route::get('/pickup-days/{saddr_id}', 'CheckoutController@getPickupDays');
            Route::post('/pickup/date-time-slots', 'CheckoutController@pickupRecordsById');
            Route::get('/cart-summary', 'CheckoutController@getCartSummary');
            Route::post('/apply-rewards', 'CheckoutController@applyRewardPoints');
            Route::delete('/remove-rewards', 'CheckoutController@removeRewardPoints');
            Route::post('/payment-step', 'CheckoutController@getPaymentStepData');
            Route::post('/request-code', 'CheckoutController@requestCode');
            Route::post('/resend-code', 'CheckoutController@resendCode');
            Route::post('/verify-code', 'CheckoutController@verifyCode');
            Route::post('/review-order', 'CheckoutController@reviewOrder');
            Route::post('/place-order', 'OrderController@placeOrder');
            Route::post('/save-card', 'CheckoutController@saveCard');
            // Route::post('/cal-shipping-tax', 'CheckoutController@calShippingTax');
            Route::post('/get-shipping-data', 'CheckoutController@getShippingData');
            Route::post('/shipping-update', 'CheckoutController@shippingUpdate');
        });
    });
    Route::get('/{rewrite_url}', 'HomeController@showUrlRewrittenPages')->where('rewrite_url', '.*');

});
