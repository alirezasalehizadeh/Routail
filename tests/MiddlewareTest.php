<?php

declare(strict_types=1);

use AlirezaSalehizadeh\Routail\Request;
use AlirezaSalehizadeh\Routail\Middleware\Middleware;

beforeEach(function () {
    $this->middleware = new Middleware();
});

test('can add and get middlewares', function () {
    $this->middleware->add('auth', ['authMiddleware1', 'authMiddleware2']);
    $this->middleware->add('admin', ['adminMiddleware1', 'adminMiddleware2']);

    $middlewares = $this->middleware->getMiddlewares();

    expect($middlewares)->toHaveKey('auth');
    expect($middlewares)->toHaveKey('admin');
    expect($middlewares['auth'])->toBe(['authMiddleware1', 'authMiddleware2']);
    expect($middlewares['admin'])->toBe(['adminMiddleware1', 'adminMiddleware2']);
});

test('can check if middleware exists', function () {
    $this->middleware->add('auth', ['authMiddleware1', 'authMiddleware2']);

    expect($this->middleware->has('auth'))->toBeTrue();
    expect($this->middleware->has('admin'))->toBeFalse();
});

test('can get middleware by name', function () {
    $this->middleware->add('auth', ['authMiddleware1', 'authMiddleware2']);

    $middlewares = $this->middleware->get('auth');

    expect($middlewares)->toBe(['authMiddleware1', 'authMiddleware2']);
});

test('can handle request', function () {
    $request = new Request();

    expect($this->middleware->handle($request))->toBeTrue();
});
