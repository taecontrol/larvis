<?php

namespace Taecontrol\Larvis\Watchers;

use Taecontrol\Larvis\Larvis;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Events\QueryExecuted;
use Taecontrol\Larvis\ValueObjects\Data\QueryData;

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

        $url = config('larvis.debug.url') . config('larvis.debug.api.query');

        $data = [
            'query' => $queryData->debugFormat(),
            'app' => $appData->toArray(),
        ];

        $larvis->send($url, $data);
    }
}
