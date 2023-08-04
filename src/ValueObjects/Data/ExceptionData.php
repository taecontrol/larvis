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
        $request = [
            'url' => $this->request->url(),
            'params' => $this->request->request->all(),
            'query' => $this->request->query->all(),
        ];

        $request['headers'] = $this->request->headers->all();

        return [
            'message' => $this->message,
            'type' => $this->type,
            'file' => $this->file,
            'trace' => $this->trace,
            'line' => $this->line,
            'request' => $request,
            'thrown_at' => $this->thrownAt->utc(),
        ];
    }

    public function debugFormat(): array
    {
        $request = [
            'url' => $this->request->url(),
            'params' => $this->request->request->all(),
            'query' => $this->request->query->all(),
        ];

        $request['headers'] = $this->request->headers->all();

        $request['server'] = $this->request->server->all();

        return [
            'message' => $this->message,
            'kind' => $this->type,
            'file' => $this->file,
            'trace' => json_encode($this->trace),
            'line' => $this->line,
            'request' => json_encode($request),
            'thrown_at' => $this->thrownAt->format('Y-m-d\TH:i:s\Z'),
        ];
    }
}
