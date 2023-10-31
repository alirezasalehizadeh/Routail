<?php

namespace AlirezaSalehizadeh\Routail\Middleware;

use AlirezaSalehizadeh\Routail\Request;

interface MiddlewareInterface
{
    public function handle(Request $request): bool;
}
