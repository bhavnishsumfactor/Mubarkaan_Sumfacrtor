<?php

namespace YoKartShipping\SystemShipping;

use Illuminate\Support\ServiceProvider;

class SystemShippingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include_once __DIR__. '/Models/SystemShipping.php';
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'shipping');
        
    }
}
