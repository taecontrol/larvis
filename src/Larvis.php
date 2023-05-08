<?php

namespace Taecontrol\Larvis;

use Throwable;
use Taecontrol\Larvis\Handlers\MessageHandler;
use Taecontrol\Larvis\Handlers\ExceptionHandler;

class Larvis
{
    protected bool $localDebugEnabled = false;

    public function __construct()
    {
        if (app()->environment(['local', 'testing'])) {
            $this->localDebugEnabled = true;
        }
    }

    public function captureException(Throwable $exception, array $data = []): void
    {
        (new ExceptionHandler())->handle($exception, $data);
    }

    public function send(mixed $args): void
    {
        if ($this->localDebugEnabled) {
            (new MessageHandler())->handle($args);
        }
    }
}
