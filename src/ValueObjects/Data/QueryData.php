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
            sql: QueryData::formatBindingsInSQL($e->sql, $e->bindings),
            bindings: $e->bindings,
            time: $e->time,
            connectionName: $e->connectionName,
            queriedAt: now(),
        );
    }

    public static function formatBindingsInSQL(string $sql, array $bindings): string
    {
        foreach ($bindings as $binding) {
            $position = strpos($sql, '?');
            if ($position !== false) {
                if (is_string($binding)) {
                    $binding = "'" . $binding . "'";
                }
                if (is_null($binding)) {
                    $binding = 'null';
                }
                $sql = substr_replace($sql, $binding, $position, 1);
            }
        }

        return $sql;
    }

    public function toArray(): array
    {
        return [
            'sql' => $this->sql,
            'bindings' => $this->bindings,
            'time' => $this->time,
            'connection_name' => $this->connectionName,
            'queried_at' => $this->queriedAt->utc(),
        ];
    }

    public static function fromArray(array $args): QueryData
    {
        return new QueryData(
            sql: $args['sql'],
            bindings: $args['bindings'],
            time: $args['time'],
            connectionName: $args['connection_name'],
            queriedAt: $args['queried_at'],
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
