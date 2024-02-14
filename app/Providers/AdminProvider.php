<?php

namespace App\Providers;

use App\Services\AdminService;
use Illuminate\Support\ServiceProvider;

class AdminProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AdminService::class, function () {
            return new AdminService(config('admins'));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
