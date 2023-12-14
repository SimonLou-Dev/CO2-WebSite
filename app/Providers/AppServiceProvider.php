<?php

namespace App\Providers;

use App\Facade\ViteAssetLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ViteAssetLoader::class, function (Application $app){
            return new ViteAssetLoader(
                env('FRONT_DEBUG', true),
                public_path('assets/manifest.json'),
                $app->get('cache.store')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
