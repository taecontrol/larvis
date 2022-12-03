<?php

namespace Taecontrol\Larvis\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Throwable;

class ExceptionData implements Arrayable
{
    public function __construct(
        public readonly string $message,
        public readonly string $type,
        public readonly string $file,
        public readonly array $trace,
        public readonly int $line,
        public readonly Request $request,
        public readonly Carbon $thrown_at,
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
            thrown_at: now(),
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
            'request' => [
                'url' => $this->request->url(),
                'params' => $this->request->all(),
                'headers' => $this->request->headers->all(),
            ],
            'thrown_at' => $this->thrown_at->utc(),
        ];
    }
}
