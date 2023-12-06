<?php

namespace Taecontrol\Larvis\Exceptions;

use Exception;

class CpuHealthException extends Exception
{
    public static function make(): self
    {
        return new self("Could not measure the CPU of your system");
    }
}

