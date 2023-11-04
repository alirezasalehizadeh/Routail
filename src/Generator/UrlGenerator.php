<?php

declare(strict_types=1);

namespace AlirezaSalehizadeh\Routail\Generator;

use AlirezaSalehizadeh\Routail\Route;
use AlirezaSalehizadeh\Routail\Enums\HttpMethod;
use AlirezaSalehizadeh\Routail\Compiler\RoutePatternCompiler;


class UrlGenerator
{

    private RoutePatternCompiler $pattern;

    public function __construct(
        private Route $route
    ) {
        $this->pattern = new RoutePatternCompiler($this->route);
    }

    public function generate(array $parameters = []): string
    {
        $url = $this->route->getPattern();

        if ($this->pattern->hasParameter()) {
            return $this->replaceParameters($parameters);
        }

        if ($this->route->method === HttpMethod::GET) {
            return $this->urlWithQueryString($url, $parameters);
        }

        return $url;
    }

    private function replaceParameters(array $parameters): string
    {
        $url = '';

        foreach ($parameters as $key => $value) {
            if ($url !== '') {
                $url = preg_replace("/\{($key)(?:\:([a-zA-Z]+))?\}/", (string)$value, $url);
                continue;
            }
            $url = preg_replace("/\{($key)(?:\:([a-zA-Z]+))?\}/", (string)$value, $this->route->getPattern());
        }
        return $url;
    }

    private function urlWithQueryString(string $url, array $parameters): string
    {
        if (count($parameters) === 0) {
            return $url;
        }

        $queryString = http_build_query($parameters);

        return "{$url}?{$queryString}";
    }
}
