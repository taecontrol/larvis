<?php

namespace Taecontrol\Larvis\ValueObjects\Data;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Events\QueryExecuted;

class QueryData implements Arrayable
{
    public function __construct(
        public readonly string $sql,
        public readonly array $bindings,
        public readonly float $time,
        public readonly string $connectionName,
        public readonly Carbon $queriedAt,
    ) {
    }

    public static function from(QueryExecuted $e): QueryData
    {
        return new self(
            sql: $e->sql,
            bindings: $e->bindings,
            time: $e->time,
            connectionName: $e->connectionName,
            queriedAt: now(),
        );
    }

    public function toArray(): array
    {
        return [
            'sql' => $this->sql,
            'bindings' => $this->bindings,
            'time' => $this->time,
            'connectionName' => $this->connectionName,
            'queriedAt' => $this->queriedAt->utc(),
        ];
    }

    public static function fromArray(array $args): QueryData
    {
        return new QueryData(
            sql: $args['sql'],
            bindings: $args['bindings'],
            time: $args['time'],
            connectionName: $args['connectionName'],
            queriedAt: $args['queriedAt'],
        );
    }

    public function debugFormat(): array
    {
        return [
            'sql' => $this->sql,
            'bindings' => json_encode($this->bindings),
            'time' => $this->time,
            'connection_name' => $this->connectionName,
            'queried_at' => $this->queriedAt->format('Y-m-d\TH:i:s\Z'),
        ];
    }
}
