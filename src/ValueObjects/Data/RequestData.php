<?php

namespace Taecontrol\Larvis\ValueObjects\Data;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Arrayable;

class RequestData implements Arrayable
{
    public function __construct(
        public readonly array $attributes,
        public readonly mixed $request_body,
        public readonly mixed $files,
        public readonly array $headers,
        public readonly string $content,
        public readonly array $server,
        public readonly string $request_uri,
        public readonly string $base_url,
        public readonly string $method,
        public readonly mixed $session,
        public readonly string $format,
        public readonly string $locale,
    ) {
    }

    public static function from(Request $r): RequestData
    {
        /* dd($r); */
        return new self(
            attributes: $r['attributes'] ?? [],
            request_body: $r->requestBody,
            files: $r->files,
            headers: $r['headers'] ?? [],
            content: $r->content ?? '',
            server: $r['server'] ?? [],
            request_uri: $r->requestUri ?? '',
            base_url: $r->baseUrl ?? '',
            method: $r->method ?? '',
            session: $r->session,
            format: $r->format ?? '',
            locale: $r->locale ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'attributes' => $this->attributes,
            'request_body' => $this->request_body,
            'files' => $this->files,
            'headers' => $this->headers,
            'content' => $this->content,
            'server' => $this->server,
            'request_uri' => $this->request_uri,
            'base_url' => $this->base_url,
            'method' => $this->method,
            'session' => $this->session,
            'format' => $this->format,
            'locale' => $this->locale,
        ];
    }

    public static function fromArray(array $args): RequestData
    {
        return new RequestData(
            attributes: $args['attributes'],
            request_body: $args['request_body'],
            files: $args['files'],
            headers: $args['headers'],
            content: $args['content'],
            server: $args['server'],
            request_uri: $args['request_uri'],
            base_url: $args['base_url'],
            method: $args['method'],
            session: $args['session'],
            format: $args['format'],
            locale: $args['locale'],
        );
    }

    public function debugFormat(): array
    {
        return [
            'attributes' => $this->attributes,
            'request_body' => $this->request_body,
            'files' => $this->files,
            'headers' => $this->headers,
            'content' => $this->content,
            'server' => $this->server,
            'request_uri' => $this->request_uri,
            'base_url' => $this->base_url,
            'method' => $this->method,
            'session' => $this->session,
            'format' => $this->format,
            'locale' => $this->locale,
        ];
    }
}
