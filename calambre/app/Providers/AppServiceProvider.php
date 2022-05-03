<?php

namespace App\Providers;

use Edistribucion\EdisClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(EdisClient::class, function ($app) {
            return new EdisClient(
                auth()->user()->edisUsername,
                auth()->user()->edisPassword,
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
