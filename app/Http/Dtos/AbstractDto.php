<?php

namespace App\Http\Dtos;

abstract class AbstractDto
{
    const UNKNOWN = 'unknown';

    /**
     * @return array the DTO array representation
     */
    abstract function toArray(): array;
}
