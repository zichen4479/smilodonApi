<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class ApiRequestExcept extends Exception
{
    protected $statusCode;

    public function __construct($message = "", $code = 0)
    {
        parent::__construct($message,$code);
    }
}
