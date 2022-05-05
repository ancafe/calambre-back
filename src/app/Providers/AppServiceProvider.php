<?php

namespace App\Providers;

use App\Jobs\ReadMeasureFromEDISAndStore;
use App\Services\Edis\EdisService;
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
            if(auth()->user() && auth()->user()->edis()->exists()){
                return new EdisClient(
                    auth()->user()->edis->username,
                    auth()->user()->edis->password,
                );
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
