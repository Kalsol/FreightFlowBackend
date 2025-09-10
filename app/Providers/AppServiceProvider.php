<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification; // Import the Notification facade
use App\Notifications\Channels\AfromessageChannel; // Import your custom channel class
use Illuminate\Support\Facades\Schema;

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
        // Register the custom 'afromessage' notification channel
        // When Laravel encounters 'afromessage' in a `via()` method,
        // it will use this extension to resolve the channel.
        Notification::extend('afromessage', function ($app) {
            return new AfromessageChannel();
        });

        Schema::defaultStringLength(191);
    }
}
