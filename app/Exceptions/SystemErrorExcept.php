<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class SystemErrorExcept extends Exception
{
    public function __construct($message = "", $code = 0)
    {
        parent::__construct($message, $code);
    }
}
