<?php

namespace LaravelSqids;

use Illuminate\Support\ServiceProvider;

class LaravelSqidsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sqids.php', 'sqids');

        $this->app->singleton(SqidsManager::class, function ($app) {
            return new SqidsManager($app);
        });
    }
}
