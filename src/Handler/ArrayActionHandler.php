<?php

declare(strict_types=1);

namespace AlirezaSalehizadeh\Routail\Handler;

class ArrayActionHandler
{
    public function handle(array $action)
    {
        $controller = $action[0];
        $method = $action[1];
        $controller = new $controller;
        return $controller->$method();
    }
}
