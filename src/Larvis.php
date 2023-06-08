<?php

namespace Taecontrol\Larvis;

use Throwable;
use Illuminate\Support\Facades\Http;
use Taecontrol\Larvis\Handlers\MessageHandler;
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
         * This try-catch prevents unwanted exceptions when there's a
         * failure sending data to MoonGuard/Krater
         */
        try {
            Http::withHeaders(
                ['Content-Type' => 'application/json; charset=utf-8']
            )->post($url, $data);
        } catch (Throwable $th) {
            /**
             * This catch block is empty because there is no action plan
             * to catch Larvis's unwanted exceptions.
             *
             * This will be reviewed in an upcoming update.
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
