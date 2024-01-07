<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Policies\SuperAdminPolicy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => SuperAdminPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('is-super-admin', [SuperAdminPolicy::class, 'isSuperAdmin']);

        // customize email verification
        VerifyEmail::toMailUsing(function (object $notifiable, $user) {
            $code = generateRandomNumber(8);
            DB::table('email_verify_codes')->insert([
                'email' => $notifiable->email,
                'code'  => $code,
                'created_at' => now(),
            ]);
            return (new MailMessage)
                ->subject('Verify Email Address')
                ->line('This is your code: '. $code)
                ->line('If you did not create an account, no further action is required');
        });
    }
}
