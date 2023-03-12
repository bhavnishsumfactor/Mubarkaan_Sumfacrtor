<?php

namespace YoKartInstall\Installer;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use YoKartInstall\Installer\Middleware\canInstall;
use YoKartInstall\Installer\Middleware\canUpdate;

class InstallerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
         $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');
    }

    /**
     * Bootstrap the application events.
     *
     * @param $void
     */
    public function boot(Router $router)
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'installer');
        $router->middlewareGroup('install', [CanInstall::class]);
        $router->middlewareGroup('update', [CanUpdate::class]);
    }

    /**
     * Publish config file for the installer.
     *
     * @return void
     */

}
