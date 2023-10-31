<?php

use AlirezaSalehizadeh\Routail\Route;
use AlirezaSalehizadeh\Routail\RouteCollection;
use AlirezaSalehizadeh\Routail\Enums\HttpMethod;

it('can add a route to the collection', function () {
    $routeCollection = new RouteCollection();
    $route = new Route(HttpMethod::GET, 'home', '/', 'HomeController@index');
    $routeCollection->add($route);

    expect(count($routeCollection->getRoutes()))->toBe(1);
});

it('can find a route in the collection', function () {
    $routeCollection = new RouteCollection();
    $route = new Route(HttpMethod::GET, '/', 'HomeController@index', 'home');
    $routeCollection->add($route);

    expect($routeCollection->find('home'))->toBe($route);
});

it('throws RouteNotFoundException when finding a nonexistent route', function () {
    $routeCollection = new RouteCollection();
    $routeCollection->find('nonexistent');
})->throws(RouteNotFoundException::class);

