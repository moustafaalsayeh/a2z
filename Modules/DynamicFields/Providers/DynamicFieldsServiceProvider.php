<?php

namespace Modules\DynamicFields\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class DynamicFieldsServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('DynamicFields', 'Database/Migrations'));
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
            module_path('DynamicFields', 'Config/config.php') => config_path('dynamicfields.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('DynamicFields', 'Config/config.php'), 'dynamicfields'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/dynamicfields');

        $sourcePath = module_path('DynamicFields', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/dynamicfields';
        }, \Config::get('view.paths')), [$sourcePath]), 'dynamicfields');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/dynamicfields');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'dynamicfields');
        } else {
            $this->loadTranslationsFrom(module_path('DynamicFields', 'Resources/lang'), 'dynamicfields');
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
            app(Factory::class)->load(module_path('DynamicFields', 'Database/factories'));
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
