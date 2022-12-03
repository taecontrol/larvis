<?php

namespace Taecontrol\Larvis;

use Taecontrol\Larvis\Handlers\ExceptionHandler;
use Throwable;

class Larvis
{
    public function captureException(Throwable $exception): void
    {
        $handler = new ExceptionHandler();
        $handler->handle($exception);
    }
}
