<?php

declare(strict_types=1);

namespace AlirezaSalehizadeh\PHPRoute\Tests\Fixtures\Middleware;

use AlirezaSalehizadeh\Routail\Request;
use AlirezaSalehizadeh\Routail\Middleware\Middleware;

class BarMiddleware extends Middleware
{

    public function handle(Request $request): bool
    {
        return false;
    }
}
