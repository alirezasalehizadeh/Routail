<?php

declare(strict_types=1);

namespace AlirezaSalehizadeh\Routail;

use AlirezaSalehizadeh\Routail\Exceptions\RouteNotFoundException;

class RouteCollection
{

    private array $routes = [];
    public array $options = [];

    public function add(Route $route): void
    {
        if (array_key_exists('prefix', $this->options)) $route->setPrefix($this->options['prefix']);
        $this->routes[] = [$route, $this->options];
    }

    public function find(string $name): Route
    {
        foreach ($this->routes as $route) {
            if ($route[0]->getName() == $name) {
                return $route[0];
            }
        }
        throw new RouteNotFoundException($name);
    }

    public function findOption(string $name): array
    {
        foreach ($this->routes as $route) {
            if ($route[0]->getName() == $name) {
                return $route[1];
            }
        }
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function getLastRoute(): Route|false
    {
        if (count($this->routes) > 0) {
            return end($this->routes)[0];
        }
        return false;
    }
}
