<?php

declare(strict_types=1);

namespace AlirezaSalehizadeh\Routail\Handler;

class StringActionHandler
{
    public function handle(string $action)
    {
        $action = explode('@', $action);
        $controller = basename($action[0]);
        $method = $action[1];
        $controller = new $controller;
        return $controller->$method();
    }
}
