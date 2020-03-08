<?php

namespace ersaazis\usermanagement\Providers;

use Illuminate\Support\ServiceProvider;

class UserManagementServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/database/seeds/' => database_path('seeds'),
        ], 'usermanagement');
        $this->loadViewsFrom(__DIR__.'/views', 'usermanagement');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }
}
