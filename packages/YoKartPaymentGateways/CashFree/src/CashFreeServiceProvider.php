<?php

namespace YoKartPaymentGateways\CashFree;

use Illuminate\Support\ServiceProvider;

class CashFreeServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/resources/views', 'CashFree');
    }
}
