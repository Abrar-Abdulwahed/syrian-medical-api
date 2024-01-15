<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Policies\ProviderServicePolicy;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Http\Controllers\Auth\BaseLoginController;
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
        ProviderProfileService::class => ProviderServicePolicy::class,
        // User::class => SuperAdminPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Gate::define('is-super-admin', [SuperAdminPolicy::class, 'isSuperAdmin']);

        // customize email verification
        VerifyEmail::toMailUsing(function (object $notifiable) {
            $code = generateRandomNumber(8);
            DB::table('email_verify_codes')->updateOrInsert([
                'email' => $notifiable->email,
                'code'  => $code,
                'created_at' => now(),
            ]);
            return (new MailMessage)
                ->subject('Verify Email Address')
                ->line('This is your code: '. $code)
                ->line('If you did not create an account, no further action is required');
        });

        // customize reset password
        ResetPassword::toMailUsing(function (object $notifiable) {
            $code = generateRandomNumber(4);
            DB::table('password_resets')->updateOrInsert(
                ['email' => $notifiable->email],
                ['code' => $code, 'created_at' => now()]
            );
            return (new MailMessage)
            ->subject('Reset Password Notification')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->line('This is your code: '. $code)
            ->line('If you did not request a password reset, no further action is required.');
        });
    }
}
