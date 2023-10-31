<?php

declare(strict_types=1);

namespace AlirezaSalehizadeh\Routail\Exceptions;

use Exception;

class MiddlewareException extends Exception
{
    public function __construct(string $middleware)
    {
        parent::__construct("Request denied by '$middleware'");
    }
}
