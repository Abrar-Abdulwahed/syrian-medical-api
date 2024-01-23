<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

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
        Relation::morphMap([
            'User'   => 'App\Models\User',
            'Admin'  => 'App\Models\Admin',
            'Service' => 'App\Models\ProviderService',
            'Product' => 'App\Models\Product',
            'Product' => 'App\Models\ProductReservation',
            'Service' => 'App\Models\ServiceReservation',
        ]);
    }
}
