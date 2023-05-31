<?php

namespace Taecontrol\Larvis\ValueObjects\Data;

use Illuminate\Http\Response;
use Illuminate\Contracts\Support\Arrayable;

class ResponseData implements Arrayable
{
    public function __construct(
        public readonly int $status,
        public readonly string $statusText,
        public readonly array $headers,
        public readonly bool|string $content,
        public readonly string $version,
        public readonly mixed $original,
    ) {
    }

    public static function from(Response $response): ResponseData
    {
        return new self(
            status: $response->getStatusCode(),
            statusText: $response->statusText(),
            headers: $response->headers->all(),
            content: $response->getContent(),
            version: $response->getProtocolVersion(),
            original: $response->getOriginalContent(),
        );
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'statusText' => $this->statusText,
            'headers' => $this->headers,
            'content' => $this->content,
            'version' => $this->version,
            'original' => $this->original,
        ];
    }

    public static function fromArray(array $args): ResponseData
    {
        return new ResponseData(
            status: $args['status'],
            statusText: data_get($args, ['statusText', 'status_text']),
            headers: $args['headers'],
            content: $args['content'],
            version: $args['version'],
            original: $args['original'],
        );
    }

    public function debugFormat(): array
    {
        return [
            'status' => strval($this->status),
            'status_text' => $this->statusText,
            'headers' => json_encode($this->headers),
            'content' => strval($this->content),
            'version' => $this->version,
            'original' => json_encode($this->original),
        ];
    }
}
