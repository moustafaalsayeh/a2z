<?php

namespace Modules\ProductSpecification\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class ProductSpecificationServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('ProductSpecification', 'Database/Migrations'));
        $this->app->register(AuthServiceProvider::class);
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
            module_path('ProductSpecification', 'Config/config.php') => config_path('productspecification.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('ProductSpecification', 'Config/config.php'), 'productspecification'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/productspecification');

        $sourcePath = module_path('ProductSpecification', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/productspecification';
        }, \Config::get('view.paths')), [$sourcePath]), 'productspecification');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/productspecification');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'productspecification');
        } else {
            $this->loadTranslationsFrom(module_path('ProductSpecification', 'Resources/lang'), 'productspecification');
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
            app(Factory::class)->load(module_path('ProductSpecification', 'Database/factories'));
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
