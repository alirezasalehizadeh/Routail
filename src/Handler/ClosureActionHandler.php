<?php

declare(strict_types=1);

namespace AlirezaSalehizadeh\Routail\Handler;

use Closure;

class ClosureActionHandler
{
    public function handle(Closure $action, array $parameters = [])
    {
        return call_user_func_array($action, $parameters);
    }
}
