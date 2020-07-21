<?php

namespace App\Exceptions;

use Exception;

class WarpgException extends Exception
{
    private $httpCode;

    public function __construct(string $message, int $httpCode)
    {
        $this->httpCode = $httpCode;

        parent::__construct($message);
    }

    public function getHttpCode()
    {
        return $this->httpCode;
    }
}
