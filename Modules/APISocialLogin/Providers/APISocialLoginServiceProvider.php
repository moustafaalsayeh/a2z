<?php

namespace Modules\APISocialLogin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class APISocialLoginServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('APISocialLogin', 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('APISocialLogin', 'Config/config.php') => config_path('apisociallogin.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('APISocialLogin', 'Config/config.php'), 'apisociallogin'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/apisociallogin');

        $sourcePath = module_path('APISocialLogin', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/apisociallogin';
        }, \Config::get('view.paths')), [$sourcePath]), 'apisociallogin');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/apisociallogin');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'apisociallogin');
        } else {
            $this->loadTranslationsFrom(module_path('APISocialLogin', 'Resources/lang'), 'apisociallogin');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path('APISocialLogin', 'Database/factories'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
