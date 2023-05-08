<?php
namespace Taecontrol\Larvis\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Application;

trait RegisterWatchers
{
    /**
     * The class names of the registered watchers.
     */
    protected array $watchers = [];

    /**
     * Determine if a given watcher has been registered.
     */
    public function hasWatcher(string $class): bool
    {
        return in_array($class, static::$watchers);
    }

    /**
     * Register the configured Larvis watchers.
     */
    protected public function registerWatchers(Application $app): void
    {
        foreach (config('larvis.watchers') as $key => $watcher) {
            if (is_string($key) && $watcher === false) {
                continue;
            }

            if (is_array($watcher) && ! ($watcher['enabled'] ?? true)) {
                continue;
            }

            $watcher = $app->make(is_string($key) ? $key : $watcher, [
                'options' => is_array($watcher) ? $watcher : [],
            ]);

            static::$watchers[] = get_class($watcher);

            $watcher->register($app);
        }
    }
}