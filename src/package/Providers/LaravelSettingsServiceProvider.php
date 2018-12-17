<?php

namespace Vkovic\LaravelSettings\Providers;

use Illuminate\Support\ServiceProvider;
use Vkovic\LaravelMeta\MetaHandler;
use Vkovic\LaravelSettings\Models\Meta;

class LaravelSettingsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // This package depends on vkovic/laravel-meta package
        // and it's migration, so we'll load it here.
        // If user already have it installed, this migration will do nothing
        $this->loadMigrationsFrom($this->vendorDir('vkovic/laravel-meta/src/database/migrations'));
    }

    /**
     * Get vendor dir
     *
     * @param $packageDir
     *
     * @return string
     */
    protected function vendorDir($packageDir = '')
    {
        if (strpos(app_path(), 'vendor/orchestra/testbench-core') !== false) {
            // Package context
            return __DIR__ . '/../../../vendor/' . ltrim($packageDir, '/');
        } else {
            // Laravel app context
            return __DIR__ . '/../../../../../../vendor/' . ltrim($packageDir, '/');
        }
    }

    public function register()
    {
        $this->app->singleton('vkovic.laravel-settings', function () {
            return new MetaHandler(new Meta);
        });
    }
}
