<?php

namespace App\Providers;

use App\Models\Order;
use App\Observers\OrderObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // If APP_URL starts with https, force URL generation to use https scheme
        if (str_starts_with(config('app.url'), 'https')) {
            URL::forceScheme('https');
        }

        Order::observe(OrderObserver::class);
    }
}
