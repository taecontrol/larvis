<?php

namespace Taecontrol\Larvis\ValueObjects\Data;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Arrayable;
use Symfony\Component\HttpFoundation\Response;

class RequestData implements Arrayable
{
    public function __construct(
        public readonly ?array $attributes,
        public readonly mixed $body,
        public readonly array $files,
        public readonly array $headers,
        public readonly ?string $content,
        public readonly array $server,
        public readonly array $uri,
        public readonly string $method,
        public readonly array $session,
        public readonly ?string $format,
        public readonly string $locale,
        public readonly ResponseData $response,
    ) {
    }

    public static function from(Request $request, Response $response): RequestData
    {
        $session = $request->hasSession()
            ? $request->session()->all()
            : [];

        $uri = [
            'root' => $request->root(),
            'path' => $request->path(),
            'host' => $request->getHost(),
            'port' => $request->getPort(),
        ];

        return new self(
            attributes: $request->attributes->all(),
            body: $request->getContent(),
            files: $request->files->all(),
            headers: $request->headers->all(),
            content: $request->getContent(),
            server: $request->server->all(),
            uri: $uri,
            method: $request->getMethod(),
            session: $session,
            format: $request->getRequestFormat() ?? 'null',
            locale: $request->getLocale(),
            response: ResponseData::from($response),
        );
    }

    public function toArray(): array
    {
        return [
            'attributes' => $this->attributes,
            'body' => $this->body,
            'files' => $this->files,
            'headers' => $this->headers,
            'content' => $this->content,
            'server' => $this->server,
            'uri' => $this->uri,
            'method' => $this->method,
            'session' => $this->session,
            'format' => $this->format,
            'locale' => $this->locale,
            'response' => $this->response,
        ];
    }

    public static function fromArray(array $args): RequestData
    {
        return new RequestData(
            attributes: $args['attributes'],
            body: $args['body'],
            files: $args['files'],
            headers: $args['headers'],
            content: $args['content'],
            server: $args['server'],
            uri: $args['uri'],
            method: $args['method'],
            session: $args['session'],
            format: $args['format'],
            locale: $args['locale'],
            response: $args['response']
        );
    }

    public function debugFormat(): array
    {
        return [
            'attributes' => json_encode($this->attributes),
            'body' => json_encode($this->body),
            'files' => json_encode($this->files),
            'headers' => json_encode($this->headers),
            'content' => $this->content,
            'server' => json_encode($this->server),
            'uri' => json_encode($this->uri),
            'method' => $this->method,
            'session' => json_encode($this->session),
            'format' => $this->format,
            'locale' => $this->locale,
            'response' => json_encode($this->response->toArray()),
        ];
    }
}
