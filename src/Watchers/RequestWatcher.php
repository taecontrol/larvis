<?php

namespace Taecontrol\Larvis\Watchers;

use Illuminate\Http\Request;
use Taecontrol\Larvis\Larvis;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpFoundation\Response;
use Taecontrol\Larvis\ValueObjects\Data\RequestData;
use Illuminate\Foundation\Http\Events\RequestHandled;

class RequestWatcher extends Watcher
{
    public function register(): void
    {
        $this->enabled = config('larvis.watchers.requests.enabled');

        Event::listen(RequestHandled::class, function (RequestHandled $event) {
            if (! $this->enabled()) {
                return;
            }
            $this->handleRequest($event->request, $event->response);
        });
    }

    public function handleRequest(Request $request, Response $response): void
    {
        /** @var Larvis */
        $larvis = app(Larvis::class);

        $isLocal = $larvis->isLocalEnvironment();

        if (! $isLocal) {
            return;
        }

        $appData = $larvis->getAppData();
        $requestData = RequestData::from($request, $response);

        $url = config('larvis.krater.url') . config('larvis.krater.api.requests');

        $data = [
            'request' => $requestData->debugFormat(),
            'app' => $appData->toArray(),
        ];

        $larvis->send($url, $data);
    }
}
