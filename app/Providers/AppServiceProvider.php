<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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
        Validator::extend('school_year_format', function ($attribute, $value, $parameters, $validator) {
            // Validate format YYYY-YYYY (e.g., 2023-2024)
            return preg_match('/^\d{4}-\d{4}$/', $value);
        });

        Validator::replacer('school_year_format', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, ':attribute must be in format YYYY-YYYY (e.g., 2023-2024).');
        });
    }
}
