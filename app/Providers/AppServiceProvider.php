<?php

namespace App\Providers;

use App\Services\SwApi\SwApiService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // binding SwApiService instance. This is not required by default Laravel can solve this simple
        // dependency injection. But is here to give more clarity about how the service is injected to the Controllers.
        $this->app->bind(SwApiService::class, function ($app) {
            return new SwApiService();
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
