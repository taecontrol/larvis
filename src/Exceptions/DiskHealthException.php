<?php

namespace Taecontrol\Larvis\Exceptions;

use Exception;

class DiskHealthException extends Exception
{
    public static function make(): self
    {
        return new self('Could not measure the Disk size of your system');
    }
}
