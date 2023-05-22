<?php

namespace Taecontrol\Larvis\ValueObjects\Data;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Arrayable;

class RequestData implements Arrayable
{
    public function __construct(
        public readonly ?array $attributes,
        public readonly mixed $requestBody,
        public readonly mixed $files,
        public readonly mixed $headers,
        public readonly ?string $content,
        public readonly ?array $server,
        public readonly ?string $requestUri,
        public readonly ?string $baseUrl,
        public readonly ?string $method,
        public readonly mixed $session,
        public readonly ?string $format,
        public readonly ?string $locale,
    ) {
    }

    public static function from(Request $r): RequestData
    {
        /* dd($r); */
        return new self(
            attributes: $r->attributes->all(),
            requestBody: $r->getContent(),
            files: $r->files->all(),
            headers: $r->headers->all(),
            content: $r->getContent(),
            server: $r->server->all(),
            requestUri: $r->getRequestUri(),
            baseUrl: $r->getBaseUrl(),
            method: $r->getMethod(),
            session:$r->getSession(),
            format: $r->getRequestFormat(),
            locale: $r->getLocale(),
        );
    }

    public function toArray(): array
    {
        return [
            'attributes' => $this->attributes,
            'requestBody' => $this->requestBody,
            'files' => $this->files,
            'headers' => $this->headers,
            'content' => $this->content,
            'server' => $this->server,
            'requestUri' => $this->requestUri,
            'baseUrl' => $this->baseUrl,
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
            requestBody: $args['requestBody'],
            files: $args['files'],
            headers: $args['headers'],
            content: $args['content'],
            server: $args['server'],
            requestUri: $args['requestUri'],
            baseUrl: $args['baseUrl'],
            method: $args['method'],
            session: $args['session'],
            format: $args['format'],
            locale: $args['locale'],
        );
    }

    public function debugFormat(): array
    {
        return [
        'attributes' => json_encode($this->attributes),
        'request_body' => json_encode($this->requestBody),
        'files' => json_encode($this->files),
        'headers' => json_encode($this->headers),
        'content' => json_encode($this->content),
        'server' => json_encode($this->server),
        'request_uri' => json_encode($this->requestUri),
        'base_url' => json_encode($this->baseUrl),
        'method' => json_encode($this->method),
        'session' => json_encode($this->session),
        'format' => json_encode($this->format),
        'locale' => json_encode($this->locale),
        ];
    }
}
