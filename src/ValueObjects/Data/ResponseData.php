<?php

namespace Taecontrol\Larvis\ValueObjects\Data;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Response;

class ResponseData implements Arrayable
{
    public function __construct(
        public readonly string $status,
        public readonly array $headers,
        public readonly string $content,
        public readonly string $version,
        public readonly mixed $original,
    ){
    }

    public static function from(Response $r): ResponseData
    {
        return new self(
            status: $r->getStatusCode(),
            headers: $r->headers->all(),
            content: $r->getContent(),
            version: $r->getProtocolVersion(),
            original: $r->getOriginalContent(),

        );
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
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
            headers: $args['headers'],
            content: $args['content'],
            version: $args['version'],
            original: $args['original'],
        );
    }

    public function debugFormat(): array
    {
        return [
            'status' => json_encode($this->status),
            'headers' => json_encode($this->headers),
            'content' => json_encode($this->content),
            'version' => json_encode($this->version),
            'original' => json_encode($this->original),
        ];
    }
}