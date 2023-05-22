<?php

namespace Taecontrol\Larvis\ValueObjects\Data;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Arrayable;

class RequestData implements Arrayable
{
    public function __construct(
        public readonly ?array $attributes,
        public readonly mixed $requestBody,
        public readonly array $files,
        public readonly array $headers,
        public readonly ?string $content,
        public readonly array $server,
        public readonly string $requestUri,
        public readonly string $baseUrl,
        public readonly string $method,
        public readonly array $session,
        public readonly ?string $format,
        public readonly string $locale,
    ) {
    }

    public static function from(Request $request): RequestData
    {
        $session = $request->hasSession()
            ? $request->session()->all()
            : [];

        return new self(
            attributes: $request->attributes->all(),
            requestBody: $request->getContent(),
            files: $request->files->all(),
            headers: $request->headers->all(),
            content: $request->getContent(),
            server: $request->server->all(),
            requestUri: $request->getRequestUri(),
            baseUrl: $request->getBaseUrl(),
            method: $request->getMethod(),
            session: $session,
            format: $request->getRequestFormat() ?? 'null',
            locale: $request->getLocale(),
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
            'content' => $this->content,
            'server' => json_encode($this->server),
            'request_uri' => $this->requestUri,
            'base_url' => $this->baseUrl,
            'method' => $this->method,
            'session' => json_encode($this->session),
            'format' => $this->format,
            'locale' => $this->locale,
        ];
    }
}
