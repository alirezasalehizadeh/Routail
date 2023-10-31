<?php

declare(strict_types=1);

namespace AlirezaSalehizadeh\Routail\Exceptions;

use BadMethodCallException;
use AlirezaSalehizadeh\Routail\Route;

class BadHttpMethodCallException extends BadMethodCallException
{
    public function __construct(Route $route)
    {
        parent::__construct("`{$route->method->value}` method is not supported for '{$route->getPattern()}'.");
    }
}
