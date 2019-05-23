<?php

namespace App\Providers;

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
        // 
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('nospace', function($attribute, $value, $parameters, $validator){
            return strpos($value, ' ') === false;
        });

        Validator::replacer('required', function($message, $attribute, $rule, $parameters){
            $message = str_replace(' field', '', $message);
            return str_replace($attribute, ucwords($attribute), $message);
        });

        Validator::replacer('max', function($message, $attribute, $rule, $parameters){
            $message = str_replace([':max'], $parameters, $message);
            return str_replace($attribute, ucwords($attribute), $message);
        });

        Validator::replacer('nospace', function($message, $attribute, $rule, $parameters){
            return 'The ' . ucwords($attribute) . ' may not have spaces.';
        });
    }

}
