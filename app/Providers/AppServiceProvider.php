<?php

namespace App\Providers;

use App\{Category, Criteria};
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Relation::morphMap([
            'category' => Category::class,
            'criteria' => Criteria::class,
        ]);

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
