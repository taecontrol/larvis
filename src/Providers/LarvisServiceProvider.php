<?php

namespace Taecontrol\Larvis\Providers;

use Taecontrol\Larvis\Larvis;
use Illuminate\Support\ServiceProvider;
use Taecontrol\Larvis\Watchers\Watcher;
use Taecontrol\Larvis\Watchers\QueryWatcher;
use Taecontrol\Larvis\Watchers\RequestWatcher;
use Taecontrol\Larvis\Watchers\ExceptionWatcher;
use Taecontrol\Larvis\Commands\CheckHardwareHealthCommand;

class LarvisServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/larvis.php', 'larvis');

        $this->app->bind('larvis', function () {
            return new Larvis();
        });

        $this->registerWatchers();
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/larvis.php' => config_path('larvis.php'),
        ], 'larvis-config');

        $this->bootWatchers();

        $this->commands([
            CheckHardwareHealthCommand::class,
        ]);
    }

    protected function bootWatchers(): self
    {
        $watchers = [
            RequestWatcher::class,
            QueryWatcher::class,
            ExceptionWatcher::class,
        ];

        collect($watchers)
            ->each(function (string $watcherClass) {
                /** @var Watcher $watcher */
                $watcher = app($watcherClass);

                $watcher->register();
            });

        return $this;
    }

    protected function registerWatchers(): self
    {
        $watchers = [
            RequestWatcher::class,
            QueryWatcher::class,
            ExceptionWatcher::class,
        ];

        collect($watchers)
            ->each(function (string $watcherClass) {
                $this->app->singleton($watcherClass);
            });

        return $this;
    }
}
