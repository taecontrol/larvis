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
        public readonly string $connection_name,
        public readonly Carbon $queried_at,
    ) {
    }

    public static function from(QueryExecuted $e): QueryData
    {
        return new self(
            sql: $e->sql,
            bindings: $e->bindings,
            time: $e->time,
            connection_name: $e->connectionName,
            queried_at: now(),
        );
    }

    public function toArray(): array
    {
        return [
            'sql' => $this->sql,
            'bindings' => $this->bindings,
            'time' => $this->time,
            'connection_name' => $this->connection_name,
            'queried_at' => $this->queried_at->utc(),
        ];
    }

    public static function fromArray(array $args): QueryData
    {
        return new QueryData(
            sql: $args['sql'],
            bindings: $args['bindings'],
            time: $args['time'],
            connection_name: $args['connection_name'],
            queried_at: $args['queried_at'],
        );
    }

    public function debugFormat(): array
    {
        return [
            'sql' => $this->sql,
            'bindings' => $this->bindings,
            'time' => $this->time,
            'connection_name' => $this->connection_name,
            'queried_at' => (string) $this->queried_at->format('Y-m-d H:i:s'),
        ];
    }
}
