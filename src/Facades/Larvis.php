<?php

namespace Taecontrol\Larvis\Facades;

use Illuminate\Support\Facades\Facade;
use Throwable;

/**
 * @method static void captureException(Throwable $exception)
 */
class Larvis extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'larvis';
    }
}
