<?php

namespace YoKartTax\SystemTaxManagement;

use Illuminate\Support\ServiceProvider;

class SystemTaxServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/resources/views', 'tax');
    }
}
