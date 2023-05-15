<?php

namespace Taecontrol\Larvis\Watchers;


use Illuminate\Support\Facades\DB;
use Illuminate\Database\Events\QueryExecuted;
use Taecontrol\Larvis\ValueObjects\QueryData;
use Illuminate\Support\Facades\Http;
use Taecontrol\Larvis\Larvis;

class QueryWatcher extends Watcher
{
    public function register(): void
    {
        $this->enabled = config('larvis.watchers.queries.enabled');

        DB::listen(function (QueryExecuted $query){
            if (!$this->enabled) {
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

        //dd($url, $endpoint, $data);
        Http::withHeaders(
            ['Content-Type' => 'application/json; charset=utf-8']
        )->post($url . $endpoint, $data)->throw();

    }
}
