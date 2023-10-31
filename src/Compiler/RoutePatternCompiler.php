<?php

declare(strict_types=1);

namespace AlirezaSalehizadeh\Routail\Compiler;

use AlirezaSalehizadeh\Routail\Route;
use AlirezaSalehizadeh\Routail\Enums\ParameterType;
use AlirezaSalehizadeh\Routail\Exceptions\InvalidParameterTypeException;


class RoutePatternCompiler
{

    private string $pattern;

    private array $types = [
        'any' => '([^/]+)',
        'id' => '\d+',
        'int' => '\d+',
        'string' => '[^/]+',
        'uuid' => '([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12})',
        'slug' => '([\w\-_]+)',
        'bool' => '(true|false|1|0)',
        'date' => '([0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]))',
        'int?' => '(?:/([0-9]+))?',
        'any?' => '(?:/([a-zA-Z0-9\.\-_%=]+))?',
    ];

    public function __construct(
        private Route $route
    ) {
        $this->pattern = $this->route->getPattern();
    }

    public function toRegex(): string
    {
        if ($this->hasParameter()) {
            return $this->compilePatternWithParameter();
        }

        return $this->compilePatternWithoutParameter();
    }

    private function compilePatternWithoutParameter(): string
    {
        $this->pattern = str_replace('/', '\/', $this->pattern);
        return '/^' . $this->pattern . '$/';
    }

    private function compilePatternWithParameter(): string
    {
        $parameters = $this->getParameters();

        foreach ($parameters[1] as $parameter) {
            if ($this->parameterHaveType($parameter)) {
                [$key, $type] = $this->getParameterKeyAndType($parameter);
                if ($this->parameterTypeIsValid($type)) {
                    $this->compilePatternWithParameterAndType($key, $type);
                    continue;
                }
            }
            $this->compilePatternWithParameterWithoutType($parameter);
        }
        $this->pattern = str_replace('/', '\/', $this->pattern);
        return '/^' . $this->pattern . '$/';
    }

    public function hasParameter(): bool
    {
        preg_match('/\{(.*?)\}/', $this->pattern, $matches);
        return !empty($matches);
    }

    private function getParameters(): array
    {
        preg_match_all('/\{(.*?)\}/', $this->pattern, $matches);
        return $matches;
    }

    private function parameterHaveType(string $parameter): bool
    {
        return (bool) preg_match('/\w+:\w+/', $parameter, $matches);
    }

    private function getParameterKeyAndType(string $parameter): array
    {
        return explode(':', $parameter);
    }

    private function compilePatternWithParameterWithoutType(string $key)
    {
        $this->pattern = preg_replace("/\{($key)\}/", '([^/]+)', $this->pattern);
    }

    private function compilePatternWithParameterAndType(string $key, string $type)
    {
        if (str_ends_with($type, '?')) {
            $this->pattern = preg_replace("/\{($key):(\w+.)\}/", '.?' . $this->types[$type], $this->pattern);
            return;
        }
        $this->pattern = preg_replace("/\{($key):(\w+)\}/", '(' . $this->types[$type] . ')', $this->pattern);
    }

    private function parameterTypeIsValid(string $type): bool
    {
        return (bool) ParameterType::find($type) ?: throw new InvalidParameterTypeException($type);
    }
}
