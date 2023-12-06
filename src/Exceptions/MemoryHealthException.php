<?php

namespace Taecontrol\Larvis\Exceptions;

use Exception;

class MemoryHealthException extends Exception
{
    public static function make(): self
    {
        return new self("Could not measure the Memory of your system");
    }
}
