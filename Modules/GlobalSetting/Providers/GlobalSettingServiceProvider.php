<?php

namespace Modules\GlobalSetting\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class GlobalSettingServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('GlobalSetting', 'Database/Migrations'));
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
            module_path('GlobalSetting', 'Config/config.php') => config_path('globalsetting.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('GlobalSetting', 'Config/config.php'), 'globalsetting'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/globalsetting');

        $sourcePath = module_path('GlobalSetting', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/globalsetting';
        }, \Config::get('view.paths')), [$sourcePath]), 'globalsetting');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/globalsetting');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'globalsetting');
        } else {
            $this->loadTranslationsFrom(module_path('GlobalSetting', 'Resources/lang'), 'globalsetting');
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
            app(Factory::class)->load(module_path('GlobalSetting', 'Database/factories'));
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
