<?php

namespace Taecontrol\Larvis\Facades;

use Throwable;
use Illuminate\Support\Facades\Facade;

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
