<?php

namespace App\Services\SwApi\Exceptions;

class SwApiRequestException extends \Exception
{

    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
