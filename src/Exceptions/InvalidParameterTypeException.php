<?php

declare(strict_types=1);

namespace AlirezaSalehizadeh\Routail\Exceptions;

use InvalidArgumentException;

class InvalidParameterTypeException extends InvalidArgumentException
{
    public function __construct(string $type)
    {
        parent::__construct("'$type' parameter type in not valid.");
    }
}
