<?php

declare(strict_types=1);

namespace AlirezaSalehizadeh\Routail;

use Closure;
use AlirezaSalehizadeh\Routail\Request;
use AlirezaSalehizadeh\Routail\RouteCollection;
use AlirezaSalehizadeh\Routail\Enums\HttpMethod;
use AlirezaSalehizadeh\Routail\Matcher\RouteMatcher;
use AlirezaSalehizadeh\Routail\Handler\ActionManager;
use AlirezaSalehizadeh\Routail\Middleware\Middleware;
use AlirezaSalehizadeh\Routail\Generator\UrlGenerator;
use AlirezaSalehizadeh\Routail\Exceptions\MiddlewareException;

class Router
{

    private RouteCollection $collection;
    private Request $request;
    private Middleware $middleware;

    public function __construct()
    {
        $this->middleware = new Middleware;
        $this->collection = new RouteCollection;
        $this->request = new Request;
    }

    public function get(string $path, null|string|array|Closure $action)
    {
        $this->add(HttpMethod::GET, $path, $action);
        return $this;
    }

    public function post(string $path, null|string|array|Closure $action)
    {
        $this->add(HttpMethod::POST, $path, $action);
        return $this;
    }

    public function put(string $path, null|string|array|Closure $action)
    {
        $this->add(HttpMethod::PUT, $path, $action);
        return $this;
    }

    public function patch(string $path, null|string|array|Closure $action)
    {
        $this->add(HttpMethod::PATCH, $path, $action);
        return $this;
    }

    public function delete(string $path, null|string|array|Closure $action)
    {
        $this->add(HttpMethod::DELETE, $path, $action);
        return $this;
    }

    public function any(string $path, null|string|array|Closure $action)
    {
        $this
        ->get($path, $action)
        ->post($path, $action)
        ->put($path, $action)
        ->patch($path, $action)
        ->delete($path, $action);
    }

    private function add(HttpMethod $method, string $path, null|string|array|Closure $action)
    {
        $this->collection->add(new Route($method, $path, $action, null, null));
    }

    public function middleware(array $middlewares)
    {
        if ($route = $this->collection->getLastRoute()) {
            $name = $route->getName();
            $this->middleware->add($name, $middlewares);
        }
        return $this;
    }

    public function prefix(string $prefix)
    {
        if ($route = $this->collection->getLastRoute()) {
            $route->setPrefix($prefix);
        }
        return $this;
    }

    public function name(string $name)
    {
        if ($route = $this->collection->getLastRoute()) {
            $route->setName($name);
        }
        return $this;
    }

    public function group(Closure $closure, array $middlewares = [], ?string $prefix = '')
    {
        $this->collection->options = [
            'groupIndex' => $this->middleware->currentGroupIndex,
            'prefix' => $prefix
        ];
        $this->middleware->groupMiddlewares[$this->middleware->currentGroupIndex] = $middlewares;
        $this->middleware->currentGroupIndex++;
        $closure($this);
        $this->collection->options = [];
    }

    public function url(string $name, array $parameters = [])
    {
        $route = $this->collection->find($name);
        return (new UrlGenerator($route))->generate($parameters);
    }

    public function find(string $name)
    {
        return $this->collection->find($name);
    }

    public function match()
    {
        return (new RouteMatcher($this->request, $this->getRouteCollection()))->match();
    }

    public function run()
    {
        $route = $this->match();

        // Route middleware
        if ($this->middleware->has($route->getName())) {
            $middlewares = $this->middleware->get($route->getName());
            foreach ($middlewares as $middleware) {
                if (!((new $middleware) instanceof Middleware) || !(new $middleware)->handle($this->request)) throw new MiddlewareException($middleware);
            }
        }

        // Group middleware
        $option = $this->collection->findOption($route->getName());
        if (array_key_exists('groupIndex', $option)) {
            foreach ($this->middleware->groupMiddlewares[$option['groupIndex']] as $middleware) {
                if (!((new $middleware) instanceof Middleware) || !(new $middleware)->handle($this->request)) {
                    throw new MiddlewareException($middleware);
                }
            }
        }

        return (new ActionManager)($route, $this->getRouteParameters($route));
    }

    public function getRouteCollection()
    {
        return $this->collection;
    }

    public function getRouteParameters(Route $route)
    {
        $parameters = [];
        $routePattern = $route->getPattern();
        $requestUri = $this->request->getUri();
        $routePattern = explode('/', $routePattern);
        $requestUri = explode('/', $requestUri);
        foreach ($routePattern as $key => $value) {
            if (preg_match('/{(.*)}/', $value)) {
                $parameters[] = $requestUri[$key];
            }
        }
        return $parameters;
    }
}
