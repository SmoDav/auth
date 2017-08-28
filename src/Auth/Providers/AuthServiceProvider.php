<?php

namespace SmoDav\Auth\Providers;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->setConfigs();
        $this->loadMigrationsFrom(__DIR__.'/../Migrations');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }

    private function setConfigs()
    {
        $this->publishes([
            __DIR__.'/../Config/sauth.php' => config_path('sauth.php'),
        ]);

        $this->mergeConfigFrom(__DIR__.'/../Config/sauth.php', 'sauth');
    }
}
