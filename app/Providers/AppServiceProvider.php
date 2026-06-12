<?php

namespace App\Providers;

use App\Notifications\PasswordResetSuccessNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Event;
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
        // Override the password-reset-success notification with our custom premium email.
        // This replaces the visualbuilder database-driven template listener.
        Event::listen(PasswordReset::class, function (PasswordReset $event) {
            $event->user->notify(new PasswordResetSuccessNotification());
        });
    }
}

