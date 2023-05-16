<?php

namespace Taecontrol\Larvis\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Events\QueryExecuted;

class QueryData implements Arrayable
{
    public function __construct(
        public readonly string $sql,
        public readonly array $bindings,
        public readonly float $time,
        public readonly string $connection_name,
    ) {
    }

    public static function from(QueryExecuted $e): QueryData
    {
        return new self(
            sql: $e->sql,
            bindings: $e->bindings,
            time: $e->time,
            connection_name: $e->connectionName,
        );
    }

    public function toArray(): array
    {
        return [
            'sql' => $this->sql,
            'bindings' => $this->bindings,
            'time' => $this->time,
            'connection_name' => $this->connection_name,
        ];
    }

    public static function fromArray(array $args): QueryData
    {
        return new QueryData(
            sql: $args['sql'],
            bindings: $args['bindings'],
            time: $args['time'],
            connection_name: $args['connection_name']
        );
    }

    public function debugFormat(): array
    {
        return [
            'sql' => $this->sql,
            'bindings' => $this->bindings,
            'time' => $this->time,
            'connection_name' => $this->connection_name,
        ];
    }
}
