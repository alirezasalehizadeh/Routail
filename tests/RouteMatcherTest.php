<?php

declare(strict_types=1);

use AlirezaSalehizadeh\Routail\Route;
use AlirezaSalehizadeh\Routail\Request;
use AlirezaSalehizadeh\Routail\RouteCollection;
use AlirezaSalehizadeh\Routail\Enums\HttpMethod;
use AlirezaSalehizadeh\Routail\Matcher\RouteMatcher;

beforeEach(function () {
    $this->collection = new RouteCollection;
    $this->collection->add(new Route(HttpMethod::GET, '/posts/{id}/category/{name:string}', ''));
    $this->collection->add(new Route(HttpMethod::POST, '/users', '', 'user_create'));
    $this->collection->add(new Route(HttpMethod::PUT, '/users', '', 'user_update'));
    $this->collection->add(new Route(HttpMethod::DELETE, '/users', '', 'user_delete'));
    $this->collection->add(new Route(HttpMethod::GET, '/', ''));
});

it('returns route if found multiple route ', function () {
    $request = Request::create('/users', HttpMethod::PUT);

    $matcher = new RouteMatcher($request, $this->collection);

    $route = $matcher->match();

    expect($route)->toBeInstanceOf(Route::class);
    expect($route->getPattern())->toBe('/users');
    expect($route->method)->toBe(HttpMethod::PUT);
});

it('returns route if found', function () {
    $request = Request::create('/posts/1/category/travels', HttpMethod::GET);

    $matcher = new RouteMatcher($request, $this->collection);

    $route = $matcher->match();

    expect($route)->toBeInstanceOf(Route::class);
    expect($route->getPattern())->toBe('/posts/{id}/category/{name:string}');
    expect($route->method)->toBe(HttpMethod::GET);
});

it('throws exception if no route found', function () {
    $request = Request::create('/posts', HttpMethod::GET);

    $matcher = new RouteMatcher($request, $this->collection);

    expect(fn () => $matcher->match())->toThrow(RouteNotFoundException::class);
});
