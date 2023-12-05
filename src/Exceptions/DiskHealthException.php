<?php

namespace Taecontrol\Larvis\Exceptions;

use Exception;

class DiskHealthException extends Exception
{
    /**
   * Create a new exception instance.
   *
   * @param  string  $message
   *
   * @return void
   */
    public function __construct($message = 'Could not get hard disk usage.')
    {
        parent::__construct($message);
    }

}
