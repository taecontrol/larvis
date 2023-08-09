<?php

namespace Taecontrol\Larvis\Support;

use Taecontrol\Larvis\Readers\Reader;

class Formatter
{
    public mixed $data;

    public string $kind;

    public function format(mixed $data): self
    {
        $kind = gettype($data);

        match ($kind) {
            'string' => self::formatString($data),
            'boolean' => self::formatBoolean($data),
            'integer' => self::formatInteger($data),
            'double' => self::formatDouble($data),
            'object' => self::formatObject($data),
            'array' => self::formatArray($data),
            'NULL' => self::formatNull(),
            'resource' => self::formatResource($data),
        };

        return $this;
    }

    public function toJson(): string
    {
        return json_encode($this->data, JSON_UNESCAPED_SLASHES);
    }

    public function formatString(string $data): void
    {
        $this->data = $data;
        $this->kind = 'string';
    }

    public function formatDouble(mixed $data): void
    {
        $this->data = $data;
        $this->kind = 'double';
    }

    public function formatResource(mixed $data): void
    {
        $kind = get_resource_type($data);

        if ($kind === 'stream') {
            $data = stream_get_contents($data);
        }

        $this->data = $data;
        $this->kind = 'resource';
    }

    public function formatNull(): void
    {
        $this->data = null;
        $this->kind = 'NULL';
    }

    public function formatArray(array $data): array
    {
        $formattedArray = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $formattedArray[$key] = $this->formatArray($value);

                continue;
            }

            if (is_object($value)) {
                $formattedArray[$key] = $this->formatObject($value);

                continue;
            }

            $formattedArray[$key] = $value;
        }

        $this->data = $formattedArray;
        $this->kind = 'array';

        return $formattedArray;
    }

    public function formatInteger(int $data): void
    {
        $this->data = $data;
        $this->kind = 'int';
    }

    public function formatBoolean(bool $data): void
    {
        $this->data = $data;
        $this->kind = 'boolean';
    }

    public function formatObject(object $data): array
    {
        $reader = Reader::getReader($data);

        $this->data = $reader->toArray();
        $this->kind = 'object';

        return $this->data;
    }
}
