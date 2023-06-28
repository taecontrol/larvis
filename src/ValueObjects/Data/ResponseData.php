<?php

namespace Taecontrol\Larvis\ValueObjects\Data;

use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Arrayable;

class ResponseData implements Arrayable
{
    public function __construct(
        public readonly int $status,
        public readonly string $statusText,
        public readonly array $headers,
        public readonly array|string $content,
        public readonly string $version,
        public readonly mixed $original,
    ) {
    }

    public static function from(Response $response): ResponseData
    {
        $content = static::formatContent($response);

        return new self(
            status: $response->getStatusCode(),
            statusText: $response->statusText(),
            headers: $response->headers->all(),
            content: $content,
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

    public static function formatContent(Response $response): array|string
    {
        $content = $response->getContent();

        if (is_string($content)) {
            if (is_array(json_decode($content, true)) &&
                json_last_error() === JSON_ERROR_NONE) {
                return json_decode($content, true);
            }

            if (Str::startsWith(strtolower($response->headers->get('Content-Type')), 'text/plain')) {
                return $content;
            }
        }

        if ($response instanceof RedirectResponse) {
            return 'Redirected to ' . $response->getTargetUrl();
        }

        if ($response instanceof Response && $response->getOriginalContent() instanceof View) {
            return [
                'view' => $response->getOriginalContent()->getPath(),
                'data' => static::extractDataFromView($response->getOriginalContent()),
            ];
        }

        return 'HTML Response';
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
        $debugContent = $this->content;

        if (is_array($this->content)) {
            $debugContent = json_encode($this->content);
        }

        return [
            'status' => strval($this->status),
            'status_text' => $this->statusText,
            'headers' => json_encode($this->headers),
            'content' => $debugContent,
            'version' => $this->version,
            'original' => json_encode($this->original),
        ];
    }

    protected static function extractDataFromView(View $view)
    {
        return collect($view->getData())
            ->map(function ($value) {
                if ($value instanceof Model) {
                    return $value->toArray();
                }

                if (is_object($value)) {
                    return [
                        'class' => get_class($value),
                        'properties' => json_decode(json_encode($value), true),
                    ];
                }

                return json_decode(json_encode($value), true);
            })
            ->toArray();
    }
}
