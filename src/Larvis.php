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

    public function send(string $url, mixed $data): void
    {
        /**
         * This try-catch prevents unwanted exceptions when sending
         * data to MoonGuard or Krater
         */
        try {
            Http::withHeaders(
                ['Content-Type' => 'application/json; charset=utf-8']
            )->post($url, $data);
        } catch (Throwable $th) {
            /**
             * Currently there's no action if we catch an unexpected exception
             * sending data. This section will be updated on a update.
             */
        }
    }

    public function sendMessage(mixed $args): void
    {
        if ($this->isLocalDebugEnabled()) {
            (new MessageHandler())->handle($args);
        }
    }
}
