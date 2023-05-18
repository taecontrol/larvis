<?php

namespace Taecontrol\Larvis\ValueObjects\Data;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;

class RequestData implements Arrayable
{
    public function __construct(
        public readonly string $url,
        public readonly string $http_method,
        public readonly array $http_headers,
        public readonly object $request_body,
        public readonly string $user_agent,
        public readonly string $ip_address,
        public readonly string $client_port,
        ) {
    }

    public static function from(Request $r): RequestData
    {
        return new self(
            url: $r->url,
            http_method: $r->http_method,
            http_headers:$r->http_headers,
            request_body:$r->request_body,
            user_agent:$r->user_agent,
            ip_address:$r->ip_address,
            client_port:$r->client_port,
        );
    }

    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'http_method' => $this->http_method,
            'http_headers' => $this->http_headers,
            'request_body' => $this->request_body,
            'user_agent' => $this->user_agent,      
            'ip_address' => $this->ip_address,
            'client_port' => $this->client_port,
        ];
    }

    public static function fromArray(array $args): RequestData
    {
        return new RequestData(
            url: $args['url'],
            http_method: $args['http_method'],
            http_headers:$args['http_headers'],
            request_body:$args['request_body'],
            user_agent:$args['user_agent'],
            ip_address:$args['ip_address'],
            client_port:$args['client_port'],
        );
    }

    public function debugFormat(): array
    {
        return [
            'url' => $this->url,
            'http_method' => $this->http_method,
            'http_headers' => $this->http_headers,
            'request_body' => $this->request_body,
            'user_agent' => $this->user_agent,      
            'ip_address' => $this->ip_address,
            'client_port' => $this->client_port,
        ];
    }
}
