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
}
