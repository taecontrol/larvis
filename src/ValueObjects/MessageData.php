<?php

namespace Taecontrol\Larvis\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;

class MessageData implements Arrayable
{
    public function __construct(
        public readonly mixed $data,
        public readonly string $kind,
        public readonly string $file,
        public readonly int $line,
    ) {
    }

    public static function from(mixed $data, Backtrace $backtrace): MessageData
    {
        $type = gettype($data);

        $result = match ($type) {
            'string' => self::formatString($data),
            'boolean' => self::formatBoolean($data),
            'integer' => self::formatInteger($data),
            'double' => self::formatDouble($data),
            'object' => self::formatObject($data),
            'array' => self::formatArray($data),
            'NULL' => self::formatNull(),
            'resource' => self::formatResource($data),
            default => self::formatUnknown($data),
        };

        return new self($result['data'], $result['type'], $backtrace->file, $backtrace->line);
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'kind' => $this->kind,
            'file' => $this->file,
            'line' => $this->line,
        ];
    }

    public static function fromArray(array $args): MessageData
    {
        return new MessageData(
            data: $args['data'],
            kind: $args['kind'],
            file: $args['file'],
            line: $args['line']
        );
    }

    private static function formatString(string $data): array
    {
        return [
            'data' => $data,
            'type' => 'string',
        ];
    }

    private static function formatUnknown(mixed $data): array
    {
        return [
            'data' => json_encode($data),
            'type' => 'unknown',
        ];
    }

    private static function formatDouble(mixed $data): array
    {
        return [
            'data' => strval($data),
            'type' => 'double',
        ];
    }

    private static function formatResource(mixed $data): array
    {
        $type = get_resource_type($data);

        if ($type === 'stream') {
            $data = stream_get_contents($data);
        }

        return [
            'data' => (string) $data,
            'type' => 'resource',
        ];
    }

    private static function formatNull(): array
    {
        return [
            'data' => 'null',
            'type' => 'NULL',
        ];
    }

    private static function formatArray(array $data): array
    {
        return [
            'data' => json_encode($data),
            'type' => 'array',
        ];
    }

    private static function formatInteger(int $data): array
    {
        return [
            'data' => strval($data),
            'type' => 'integer',
        ];
    }

    private static function formatBoolean(bool $data): array
    {
        return [
            'data' => $data ? 'true' : 'false',
            'type' => 'boolean',
        ];
    }

    private static function formatObject(object $data): array
    {
        $objectData = ObjectData::from($data);

        return [
            'data' => $objectData->data,
            'type' => 'object',
        ];
    }
}
