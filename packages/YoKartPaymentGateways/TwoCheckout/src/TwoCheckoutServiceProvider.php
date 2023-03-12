<?php

namespace YoKartPaymentGateways\TwoCheckout;

use Illuminate\Support\ServiceProvider;

class TwoCheckoutServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'TwoCheckout');
    }
}
