<?php

namespace App\Providers;

use App\Libraries\CI4PhpEngine;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('auth', function ($app) {
            return new \App\Libraries\CI4AuthManager($app);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app['view.engine.resolver']->register('php', function () {
            return new CI4PhpEngine();
        });
    }
}

