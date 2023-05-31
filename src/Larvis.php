<?php

namespace Taecontrol\Larvis;

use Throwable;
use Illuminate\Support\Facades\Http;
use Taecontrol\Larvis\Handlers\MessageHandler;
use Taecontrol\Larvis\Handlers\ExceptionHandler;
use Taecontrol\Larvis\ValueObjects\Data\AppData;

class Larvis
{
    protected AppData $app;

    public function __construct()
    {
        $this->app = AppData::generate();
    }

    public function isLocalDebugEnabled(): bool
    {
        return app()->environment(['local', 'testing']) && config('larvis.debug.enabled');
    }

    public function getAppData(): AppData
    {
        return $this->app;
    }

    public function captureException(Throwable $exception, array $data = []): void
    {
        (new ExceptionHandler())->handle($exception, $data);
    }

    public function send(string $url, mixed $data): void
    {
        try {
            Http::withHeaders(
                ['Content-Type' => 'application/json; charset=utf-8']
            )->post($url, $data);
        } catch (Throwable $th) {
            //throw $th;
        }
    }

    public function sendMessage(mixed $args): void
    {
        if ($this->isLocalDebugEnabled()) {
            (new MessageHandler())->handle($args);
        }
    }
}
