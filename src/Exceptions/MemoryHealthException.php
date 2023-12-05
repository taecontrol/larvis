<?php

namespace Taecontrol\Larvis\Exceptions;

use Exception;

class MemoryHealthException extends Exception
{
    /**
   * Create a new exception instance.
   *
   * @param  string  $message
   *
   * @return void
   */
    public function __construct($message = 'Could not get memory usage.')
    {
        parent::__construct($message);
    }

}
