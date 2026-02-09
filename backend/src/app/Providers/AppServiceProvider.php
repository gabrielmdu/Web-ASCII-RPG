<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // generates the URL for the password reset - needs to be overwritten for Fortify
        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            return config('app.frontend_url') . "/reset-password/{$token}?email={$notifiable->getEmailForPasswordReset()}";
        });

        // generates the URL for the email verification - needs to be overwritten for Fortify
        VerifyEmail::createUrlUsing(function ($notifiable) {
            $signedUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(config('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );

            return config('app.frontend_url') . '/verify-email?verify_url=' . urlencode($signedUrl);
        });
    }
}
