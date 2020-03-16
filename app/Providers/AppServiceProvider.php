<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::component('components.alert', 'alert');
        Blade::component('components.breadcrumb', 'breadcrumb');
        Blade::component('components.button-link', 'buttonLink');
        Blade::component('components.button', 'button');
        Blade::component('components.card', 'card');
        Blade::component('components.form-field', 'formField');
        Blade::component('components.page-header', 'pageHeader');
        Blade::component('components.status', 'status');
    }
}
