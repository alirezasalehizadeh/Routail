<?php

declare(strict_types=1);

namespace AlirezaSalehizadeh\Routail\Matcher;

use AlirezaSalehizadeh\Routail\Route;
use AlirezaSalehizadeh\Routail\Request;
use AlirezaSalehizadeh\Routail\RouteCollection;
use AlirezaSalehizadeh\Routail\Compiler\RoutePatternCompiler;
use AlirezaSalehizadeh\Routail\Exceptions\RouteNotFoundException;
use AlirezaSalehizadeh\Routail\Exceptions\BadHttpMethodCallException;


class RouteMatcher
{

    public function __construct(
        private Request $request,
        private RouteCollection $collection,
    ) {
        //
    }

    public function match()
    {
        $matchedRoutes = [];

        foreach ($this->collection->getRoutes() as $routeWithOption) {
            [$route, $option] = $routeWithOption;
            if (!empty($this->matchRoutePattern($route))) {
                $matchedRoutes[] = $route;
            }
        }

        if (!empty($matchedRoutes)) {
            foreach ($matchedRoutes as $route) {
                if ($this->matchHttpMethod($route)) {
                    return $route;
                }
            }
            throw new BadHttpMethodCallException($route);
        }

        throw new RouteNotFoundException($this->request->getUri());
    }

    private function matchRoutePattern(Route $route): array|null
    {
        $pattern = (new RoutePatternCompiler($route))->toRegex();
        preg_match($pattern, $this->request->getUri(), $matches);
        return $matches;
    }

    private function matchHttpMethod(Route $route): bool
    {
        return $this->request->getMethod() === $route->method->value;
    }
}
