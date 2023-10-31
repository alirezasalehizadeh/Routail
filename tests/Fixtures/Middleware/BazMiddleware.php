<?php

declare(strict_types=1);

namespace AlirezaSalehizadeh\PHPRoute\Tests\Fixtures\Middleware;

use AlirezaSalehizadeh\Routail\Request;
use AlirezaSalehizadeh\Routail\Middleware\Middleware;

class BazMiddleware extends Middleware
{

    public function handle(Request $request): bool
    {
        return true;
    }
}
