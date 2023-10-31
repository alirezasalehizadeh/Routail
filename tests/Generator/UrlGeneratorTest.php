<?php

declare(strict_types=1);

use AlirezaSalehizadeh\Routail\Route;
use AlirezaSalehizadeh\Routail\Enums\HttpMethod;
use AlirezaSalehizadeh\Routail\Generator\UrlGenerator;

it('Can generate url from route name', function () {
    $routeOne = new Route(HttpMethod::GET, '/posts', '');
    $routeTwo = new Route(HttpMethod::POST, '/posts/{id}/category/{name}', '');
    $routeThree = new Route(HttpMethod::POST, '/posts/{id}/category/{name:string}', '');
    $routeFour = new Route(HttpMethod::GET, '/posts/{id}', '');
    $routeFive = new Route(HttpMethod::PUT, '/posts', '');

    $urlRouteOne = (new UrlGenerator($routeOne))->generate(['id' => '1', 'name' => 'foo']);
    $urlRouteTwo = (new UrlGenerator($routeTwo))->generate(['id' => '1', 'name' => 'foo']);
    $urlRouteThree = (new UrlGenerator($routeThree))->generate(['name' => 'foo', 'id' => '1']);
    $urlRouteFour = (new UrlGenerator($routeFour))->generate(['id' => '1', 'name' => 'foo']);
    $urlRouteFive = (new UrlGenerator($routeFive))->generate([]);

    expect($urlRouteOne)->toBe('/posts?id=1&name=foo');
    expect($urlRouteTwo)->toBe('/posts/1/category/foo');
    expect($urlRouteThree)->toBe('/posts/1/category/foo');
    expect($urlRouteFour)->toBe('/posts/1');
    expect($urlRouteFive)->toBe('/posts');
});
