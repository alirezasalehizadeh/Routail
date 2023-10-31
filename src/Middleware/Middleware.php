<?php

declare(strict_types=1);

namespace AlirezaSalehizadeh\Routail\Middleware;

use AlirezaSalehizadeh\Routail\Request;
use AlirezaSalehizadeh\Routail\Middleware\MiddlewareInterface;

class Middleware implements MiddlewareInterface
{

    public array $middlewares = [];
    public array $groupMiddlewares = [];

    public int $currentGroupIndex = 0;

    public function add(string $name, array $middlewares)
    {
        $this->middlewares[$name] = $middlewares;
    }

    public function addGroup(string $name, array $middlewares)
    {
        $this->groupMiddlewares[$name] = $middlewares;
    }

    public function has(string $name): bool
    {
        return isset($this->middlewares[$name]);
    }

    public function groupHas(string $name): bool
    {
        return isset($this->groupMiddlewares[$name]);
    }

    public function get(string $name): array
    {
        return $this->middlewares[$name];
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function handle(Request $request): bool
    {
        return true;
    }
}
