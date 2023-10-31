<?php

declare(strict_types=1);

use AlirezaSalehizadeh\Routail\Route;
use AlirezaSalehizadeh\Routail\Enums\HttpMethod;
use AlirezaSalehizadeh\Routail\Compiler\RoutePatternCompiler;

it('converts route without parameter to regex', function () {
    $route = new Route(HttpMethod::GET, '/home', '');
    $compiler = new RoutePatternCompiler($route);
    expect($compiler->toRegex())->toBe('/^\/home$/');
});

it('converts route with parameter without type to regex', function () {
    $route = new Route(HttpMethod::GET, '/users/{id}', '');
    $compiler = new RoutePatternCompiler($route);
    expect($compiler->toRegex())->toBe('/^\/users\/([^\/]+)$/');
});

it('converts route with parameter with type to regex', function () {
    $route = new Route(HttpMethod::GET, '/users/{id:int}', '');
    $compiler = new RoutePatternCompiler($route);
    expect($compiler->toRegex())->toBe('/^\/users\/(\d+)$/');
});

it('throws exception for invalid parameter type', function () {
    $route = new Route(HttpMethod::GET, '/users/{id:invalid}', '');
    $compiler = new RoutePatternCompiler($route);
    expect(function () use ($compiler) {
        $compiler->toRegex();
    })->toThrow(InvalidParameterTypeException::class);
});
