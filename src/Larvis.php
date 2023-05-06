<?php

namespace Taecontrol\Larvis;

use Throwable;
use Taecontrol\Larvis\ValueObjects\AppData;
use Taecontrol\Larvis\Handlers\MessageHandler;
use Taecontrol\Larvis\Handlers\ExceptionHandler;

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

    public function send(mixed $args): void
    {
        if($this->isLocalDebugEnabled()) {
            (new MessageHandler())->handle($args);
        }
    }
}
