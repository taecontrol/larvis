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
        public readonly string $connectionName,
    ) {
    }

    public static function from(QueryExecuted $e): QueryData
    {
        return new self(
            sql: $e->sql,
            bindings: $e->bindings,
            time: $e->time,
            connectionName: $e->connection->getName(),
        );
    }

    public function toArray(): array
    {
        return [
            'sql' => $this->sql,
            'bindings' => $this->bindings,
            'time' => $this->time,
            'connectionName' => $this->connectionName,
        ];
    }

    public function debugFormat(): array
    {
        return [
            'sql' => $this->sql,
            'bindings' => $this->bindings,
            'time' => $this->time,
            'connectionName' => $this->connectionName,
        ];
    }
}
