<?php

namespace App\Providers;

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

    public function boot(): void
    {
        // Enforce HTTPS generation for all routes, URLs, assets, and form actions behind proxies and in production
        if (app()->isProduction() || 
            request()->header('X-Forwarded-Proto') === 'https' || 
            request()->header('X-Forwarded-SSL') === 'on' || 
            (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        try {
            $global_settings = \App\Models\Setting::pluck('value', 'key')->toArray();
            \Illuminate\Support\Facades\View::share('global_settings', $global_settings);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\View::share('global_settings', []);
        }
    }
}
