<?php

namespace Taecontrol\Larvis\ValueObjects\Data;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class ExceptionData implements Arrayable
{
    public function __construct(
        public readonly string $message,
        public readonly string $type,
        public readonly string $file,
        public readonly array $trace,
        public readonly int $line,
        public readonly Request $request,
        public readonly Carbon $thrownAt,
    ) {
    }

    public static function from(Throwable $e): ExceptionData
    {
        return new self(
            message: $e->getMessage(),
            type: get_class($e),
            file: $e->getFile(),
            trace: $e->getTrace(),
            line: $e->getLine(),
            request: request(),
            thrownAt: now(),
        );
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'type' => $this->type,
            'file' => $this->file,
            'trace' => $this->trace,
            'line' => $this->line,
            'request' => $this->request,
            'thrownAt' => $this->thrownAt->utc(),
        ];
    }

    public function debugFormat(): array
    {
        return [
            'message' => $this->message,
            'kind' => $this->type,
            'file' => $this->file,
            'trace' => json_encode($this->trace),
            'line' => $this->line,
            'request' => json_encode($this->request),
            'thrown_at' => (string) $this->thrownAt->format('Y-m-d H:i:s'),
        ];
    }
}
