<?php

namespace Taecontrol\Larvis;

use Throwable;
use Taecontrol\Larvis\ValueObjects\AppData;
use Taecontrol\Larvis\Handlers\MessageHandler;
use Taecontrol\Larvis\Traits\RegistersWatchers;
use Illuminate\Contracts\Foundation\Application;
use Taecontrol\Larvis\Handlers\ExceptionHandler;

class Larvis
{
    use RegistersWatchers;

    protected AppData $app;

    public function __construct()
    {
        $this->app = AppData::generate();
    }

    public static function start(Application $app): void
    {
        static::registerWatchers($app);
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
        if ($this->isLocalDebugEnabled()) {
            (new MessageHandler())->handle($args);
        }
    }
}
