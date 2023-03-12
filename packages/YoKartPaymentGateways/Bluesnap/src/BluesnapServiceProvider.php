<?php

namespace YoKartPaymentGateways\Bluesnap;

use Illuminate\Support\ServiceProvider;

class BluesnapServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Bluesnap');
    }
}
