<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
    */
    public function boot()
    {
        // Register components
        Blade::component('components.application-logo', 'application-logo');
        Blade::component('components.nav-link', 'nav-link');
    }
}
