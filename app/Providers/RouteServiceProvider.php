<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /** @var string $apiNamespace */
    protected $apiNamespace = 'App\Http\Controllers\Api';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        // $requestUri = $_SERVER["REQUEST_URI"];
        // if (strpos($requestUri, "/installer") === false) {
        //     try {
        //         \DB::connection()->table(\DB::raw('DUAL'))->first([\DB::raw(1)]);
        //     } catch (\Exception $e) {
        //         die("Database not connected");
        //     }
        // }
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
       
        Route::prefix('api/' . env('API_VERSION'))
             ->middleware(['api', 'api_version:' . env('API_VERSION')])
             ->namespace($this->apiNamespace)
             ->group(base_path('routes/api.php'));
    }
}
