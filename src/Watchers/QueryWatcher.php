<?php

namespace Taecontrol\Larvis\Watchers;


use Illuminate\Support\Facades\DB;
use Illuminate\Database\Events\QueryExecuted;

class RequestWatcher extends Watcher
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
        /** Handle request */
        dd($query);
    }
}
