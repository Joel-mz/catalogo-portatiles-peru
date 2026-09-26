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
        try {
            $global_settings = \App\Models\Setting::pluck('value', 'key')->toArray();
            \Illuminate\Support\Facades\View::share('global_settings', $global_settings);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\View::share('global_settings', []);
        }
    }
}
