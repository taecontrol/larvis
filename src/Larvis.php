<?php

namespace Taecontrol\Larvis;

use Throwable;
use Taecontrol\Larvis\Handlers\ExceptionHandler;

class Larvis
{
    public function captureException(Throwable $exception, array $data = []): void
    {
        $handler = new ExceptionHandler();
        $handler->handle($exception, $data);
    }
}
