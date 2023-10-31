<?php

declare(strict_types=1);

namespace AlirezaSalehizadeh\Routail;

use AlirezaSalehizadeh\Routail\Enums\HttpMethod;

class Request
{

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getUri(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public static function create(string $uri, HttpMethod $method)
    {
        $_SERVER['REQUEST_URI'] = $uri;
        $_SERVER['REQUEST_METHOD'] = $method->value;
        return new static;
    }
}
