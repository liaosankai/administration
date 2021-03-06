<?php

namespace Larapacks\Administration;

use Illuminate\Support\ServiceProvider;
use Laracasts\Flash\FlashServiceProvider;

class AdministrationServiceProvider extends ServiceProvider
{
    /**
     * The administrations dependent service providers.
     *
     * @var array
     */
    protected $dependencies = [
        RouteServiceProvider::class,
        FlashServiceProvider::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        foreach ($this->dependencies as $dependency) {
            $this->app->register($dependency);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Load our views.
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'admin');

        // Load our translations.
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'admin');

        // Our configuration file.
        $config = __DIR__.'/Config/config.php';

        // Our views directory.
        $views = __DIR__.'/../resources/views';

        // Our assets directory.
        $assets = __DIR__.'/../resources/assets/public';

        $this->publishes([
            $config => config_path('admin.php')
        ], 'config');

        $this->publishes([
            $views => resource_path('views/vendor/admin')
        ], 'views');

        $this->publishes([
            $assets => public_path('vendor/administration')
        ], 'public');

        // We'll merge the configuration in case of updates.
        $this->mergeConfigFrom($config, 'admin');
    }
}
