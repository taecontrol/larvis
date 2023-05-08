<?php

namespace Taecontrol\Larvis\Watchers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Events\RequestHandled;

class RequestWatcher extends Watcher
{
    /**
     * Register the watcher.
     */
    public function register(Application $app): void
    {
        $app['events']->listen(RequestHandled::class, [$this, 'recordRequest']);
    }

    /**
     * Record an incoming HTTP request.
     */
    public function recordRequest(RequestHandled $event)
    {
        /** Formatear request para enviarlos al debug client (krater) */
    }
}
