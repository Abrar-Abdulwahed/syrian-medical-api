<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\TelescopeServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            'User'   => 'App\Models\User',
            'Admin'  => 'App\Models\Admin',
            'Service' => 'App\Models\ProviderService',
            'Product' => 'App\Models\Product',
            'ProductReservation' => 'App\Models\ProductReservation',
            'ServiceReservation' => 'App\Models\ServiceReservation',
        ]);
    }
}
