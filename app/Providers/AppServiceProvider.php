<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Laravel\Cashier\Cashier;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Modules\GlobalSetting\Entities\GlobalSetting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Cashier::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Passport::routes();

        try {
            $default_timezone = GlobalSetting::where('name', 'default timezone')->first();
            $default_timezone = $default_timezone ? $default_timezone->value : 'UTC';
    
            $default_locale = GlobalSetting::where('name', 'default locale')->first();
            $default_locale = $default_locale ? $default_locale->value : 'en';
    
    
            config(['app.timezone' => $default_timezone, 'translatable.fallback_locale' => $default_locale]);
            Artisan::call('config:clear');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
