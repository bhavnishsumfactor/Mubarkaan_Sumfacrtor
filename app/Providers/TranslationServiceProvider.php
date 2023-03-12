<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class TranslationServiceProvider extends ServiceProvider
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
        App::setLocale('fr');
        Cache::forget('translations');
        Cache::rememberForever('translations', function () {
            return collect([
                //'php' => $this->phpTranslations(),
                'json' => $this->jsonTranslations(),
            ]);
        });
    }

    private function phpTranslations()
    {
        $path = resource_path('lang/' . app()->getLocale());
        
        return collect(File::allFiles($path))->flatMap(function ($file) {
            $key = ($translation = $file->getBasename('.php'));

            return [$key => trans($translation)];
        });
    }

    private function jsonTranslations()
    {
        $path = resource_path('lang/' . app()->getLocale() . '.json');
        if (is_string($path) && is_readable($path)) {
            return json_decode(file_get_contents($path), true);
        }

        return [];
    }
}
