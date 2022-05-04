<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RLSServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton( \App\Services\CreateDatabaseUserWhenUserRegisteredService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
