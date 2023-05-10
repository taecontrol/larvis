<?php

namespace Taecontrol\Larvis\Watchers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Http\Events\RequestHandled;

class RequestWatcher extends Watcher
{
    public function register(): void
    {
        $this->enabled = config('larvis.watchers.request.enabled');

        Event::listen(RequestHandled::class, function (RequestHandled $event) {
            if (! $this->enabled()) {
                return;
            }
            $this->handleRequest($event->request, $event->response);
        });
    }

    public function handleRequest(Request $request, Response $response): void
    {
        /** Handle request */
        //dd($request);
    }
}
