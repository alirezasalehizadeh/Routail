<?php

declare(strict_types=1);

use AlirezaSalehizadeh\Routail\Route;
use AlirezaSalehizadeh\Routail\Router;
use AlirezaSalehizadeh\Routail\Request;
use AlirezaSalehizadeh\Routail\Enums\HttpMethod;

beforeEach(function () {
    $this->router = new Router;
});

it('Can add route', function () {
    $this->router->get('/test', function () {
        return 'Hello, World!';
    });

    $routeCollection = $this->router->getRouteCollection();
    [$route, $option] = $routeCollection->getRoutes()[0];

    expect($route)->toBeInstanceOf(Route::class);
    expect($route->method)->toBe(HttpMethod::GET);
    expect($route->getPattern())->toBe('/test');
    expect($route->action)->toBeInstanceOf(Closure::class);
});

it('Can match route', function () {
    Request::create('/test', HttpMethod::GET);

    $this->router->get('/test', function () {
        return 'Hello, World!';
    });

    expect($this->router->run())->toBe('Hello, World!');
});

it('Can find route by name', function () {
    $this->router->get('/test', function () {
        return 'Hello, World!';
    })->name('test_route');

    $route = $this->router->find('test_route');

    expect($route)->toBeInstanceOf(Route::class);
    expect($route->method)->toBe(HttpMethod::GET);
    expect($route->getPattern())->toBe('/test');
    expect($route->action)->toBeInstanceOf(Closure::class);
});

it('Can handle middleware', function () {

    Request::create('/foo', HttpMethod::GET);

    $this->router->get('/foo', [TestController::class, 'index'])->middleware([FooMiddleware::class]);
    expect($this->router->run())->toBe('Hello, World!');

    $this->router->get('/foo', [TestController::class, 'index'])->middleware([FooMiddleware::class, BazMiddleware::class]);
    expect($this->router->run())->toBe('Hello, World!');

    $this->router->get('/foo', [TestController::class, 'index'])->middleware([FooMiddleware::class, BarMiddleware::class]);
    expect(fn () => $this->router->run())->toThrow(MiddlewareException::class);

    $this->router->get('/foo', [TestController::class, 'index'])->middleware([BarMiddleware::class]);
    expect(fn () => $this->router->run())->toThrow(MiddlewareException::class);
});

it('Can group routes', function () {

    Request::create('/', HttpMethod::GET);

    $this->router->get('/', function () {
        return 'Hello, World!';
    });

    $this->router->group(function ($router) {
        $router->get('/users', [TestController::class, 'index']);
        $router->get('/users/{id}', [TestController::class, 'index']);
    }, [BarMiddleware::class]);

    expect($this->router->run())->toBe('Hello, World!');
});

it('Can set prefix on route', function () {

    $this->router->get('/users', function () {
        return 'Hello, World!';
    })->prefix('/api/v1');

    $route = $this->router->getRouteCollection()->getLastRoute();

    expect($route->getPattern())->toBe('/api/v1/users');
});

it('Can set name on route', function () {

    $this->router->get('/users', function () {
        return 'Hello, World!';
    })->name('get_users');

    $route = $this->router->getRouteCollection()->getLastRoute();

    expect($route->getName())->toBe('get_users');
});

it('Can generate url form route', function () {

    $this->router->get('/users/{id}', '')->name('get_user');
    $route = $this->router->getRouteCollection()->getLastRoute();
    $url = $this->router->url($route->getName(), ['id' => '1']);
    expect($url)->toBe('/users/1');

    // without name
    $this->router->get('/posts/{slug}', '');
    $route = $this->router->getRouteCollection()->getLastRoute();
    $url = $this->router->url($route->getName(), ['slug' => 'test']);
    expect($url)->toBe('/posts/test');

    // wrong parameter
    $url = $this->router->url($route->getName(), ['id' => '2']);
    expect($url)->toBe('/posts/{slug}');
});
