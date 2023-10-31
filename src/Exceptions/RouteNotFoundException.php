<?php

declare(strict_types=1);

namespace AlirezaSalehizadeh\Routail\Exceptions;

use Exception;

class RouteNotFoundException extends Exception
{
    public function __construct(string $path)
    {
        parent::__construct("No routes found for '$path'.");
    }
}
