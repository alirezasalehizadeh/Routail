<?php

declare(strict_types=1);

namespace AlirezaSalehizadeh\Routail\Handler;

use Closure;
use AlirezaSalehizadeh\Routail\Route;

class ActionManager
{
    public function __invoke(Route $route, array $parameters = [])
    {
        if ($route->action instanceof Closure) {
            return (new ClosureActionHandler)->handle($route->action, $parameters);
        }

        if (is_string($route->action)) {
            return (new StringActionHandler)->handle($route->action);
        }

        if (is_array($route->action)) {
            return (new ArrayActionHandler)->handle($route->action);
        }
    }
}
