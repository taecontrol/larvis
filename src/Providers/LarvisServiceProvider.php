<?php

namespace Taecontrol\Larvis\Providers;

use Taecontrol\Larvis\Larvis;
use Illuminate\Support\ServiceProvider;

class LarvisServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/larvis.php', 'larvis');

        $this->app->bind('larvis', function () {
            return new Larvis();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/larvis.php' => config_path('larvis.php'),
        ], 'larvis-config');
    }
}
