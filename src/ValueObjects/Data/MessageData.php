<?php

namespace Taecontrol\Larvis\ValueObjects\Data;

use Illuminate\Contracts\Support\Arrayable;
use Taecontrol\Larvis\ValueObjects\Backtrace;

class MessageData implements Arrayable
{
    public function __construct(
        public readonly string $data,
        public readonly string $kind,
        public readonly string $file,
        public readonly int $line,
    ) {
    }

    public static function from(string $formattedData, string $kind, Backtrace $backtrace): self
    {
        return new self($formattedData, $kind, $backtrace->file, $backtrace->line);
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

    public function debugFormat(): array
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
}
