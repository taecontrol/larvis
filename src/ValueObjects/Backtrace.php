<?php

namespace Taecontrol\Larvis\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;

class Backtrace implements Arrayable
{
    public function __construct(
        public readonly string $file,
        public readonly int $line,
        public readonly string $function,
        public readonly array $args,
    ) {
    }

    public static function from(array $data): Backtrace
    {
        return new self(
            file: data_get($data, 'file'),
            line: data_get($data, 'line'),
            function: data_get($data, 'function'),
            args: data_get($data, 'args'),
        );
    }

    public function toArray(): array
    {
        return [
          'file' => $this->file,
          'line' => $this->line,
          'function' => $this->function,
          'args' => $this->args,
        ];
    }
}
