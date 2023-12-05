<?php

namespace Taecontrol\Larvis\Exceptions;

use Exception;

class CpuHealthException extends Exception
{
    /**
   * Create a new exception instance.
   *
   * @param  string  $message
   *
   * @return void
   */
    public function __construct($message = 'Could not get cpu Load.')
    {
        parent::__construct($message);
    }

}
