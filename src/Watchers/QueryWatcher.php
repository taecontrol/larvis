<?php

namespace Taecontrol\Larvis\Watchers;

use Taecontrol\Larvis\Larvis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Events\QueryExecuted;
use Taecontrol\Larvis\ValueObjects\QueryData;

class QueryWatcher extends Watcher
{
    public function register(): void
    {
        $this->enabled = config('larvis.watchers.queries.enabled');

        DB::listen(function (QueryExecuted $query) {
            if (! $this->enabled()) {
                return;
            }

            $this->handleQueries($query);
        });
    }

    public function handleQueries(QueryExecuted $query): void
    {
        /** @var Larvis */
        $larvis = app(Larvis::class);

        $appData = $larvis->getAppData();
        $queryData = QueryData::from($query);

        $url = config('larvis.debug.url');
        $endpoint = config('larvis.debug.api.query');

        $data = [
            'query' => $queryData->toArray(),
            'app' => $appData->toArray(),
        ];

        Http::withHeaders(
            ['Content-Type' => 'application/json; charset=utf-8']
        )->post($url . $endpoint, $data)->throw();
    }
}
