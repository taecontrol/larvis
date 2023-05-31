<?php

namespace Taecontrol\Larvis\Exceptions;

use Exception;

class MissingAppNameException extends Exception
{
    /**
       * Create a new exception instance.
       *
       * @param  string  $message
       *
       * @return void
       */
    public function __construct($message = 'No application name has been specified.')
    {
        parent::__construct($message);
    }
}
