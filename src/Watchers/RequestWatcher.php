<?php

namespace Taecontrol\Larvis\Watchers;

use Illuminate\Http\Request;
use Taecontrol\Larvis\Larvis;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpFoundation\Response;
use Taecontrol\Larvis\ValueObjects\Data\RequestData;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Taecontrol\Larvis\ValueObjects\Data\ResponseData;

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
        /** @var Larvis */
        $larvis = app(Larvis::class);
        $appData = $larvis->getAppData();
        $requestData = RequestData::from($request);
        $responseData = ResponseData::from($response);

        $url = config('larvis.debug.url');
        $endpoint = config('larvis.debug.api.request');

        $data = [
            'request' => $requestData->debugFormat(),
            'app' => $appData->toArray(),
            'response' => json_encode($responseData->toArray()),
        ];

        Http::withHeaders(
            ['Content-Type' => 'application/json; charset=utf-8']
        )->post($url . $endpoint, $data)->throw();
    }
}
