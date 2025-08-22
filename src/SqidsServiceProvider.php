<?php

namespace LaravelSqids;

use Illuminate\Support\ServiceProvider;

class SqidsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sqids.php', 'laravel-sqids');

        $this->app->singleton(SqidsManager::class, function ($app) {
            return new SqidsManager($app);
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/sqids.php' => config_path('laravel-sqids.php'),
            ], 'laravel-sqids');
        }
    }
}
