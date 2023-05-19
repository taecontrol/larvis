<?php

namespace Taecontrol\Larvis\Watchers;

use Illuminate\Http\Request;
use Taecontrol\Larvis\Larvis;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpFoundation\Response;
use Taecontrol\Larvis\ValueObjects\Data\RequestData;
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
         /* dd($request, $response); */
        /** @var Larvis */
        $larvis = app(Larvis::class);
        $appData = $larvis->getAppData();
        $RequestData = RequestData::from($request);

        $url = config('larvis.debug.url');
        $endpoint = config('larvis.debug.api.request');

        $responseData = [
                'status' => $response->getStatusCode(),
                'headers' => $response->headers->all(),
                'content' => $response->getContent(),
        ];

        $data = [
            /* dd($RequestData, $appData, $responseData), */
            'request' => $RequestData->toArray(),
            'app' => $appData->toArray(),
            'response' => $responseData,
        ];

        Http::withHeaders(
            ['Content-Type' => 'application/json; charset=utf-8']
        )->post($url . $endpoint, $data)->throw(); 
        
    }
}
