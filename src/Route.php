<?php

declare(strict_types=1);

namespace AlirezaSalehizadeh\Routail;

use Closure;
use AlirezaSalehizadeh\Routail\Enums\HttpMethod;

class Route
{
    public function __construct(
        public readonly HttpMethod $method,
        private string $pattern,
        public readonly string|array|Closure $action,
        private ?string $name = null,
        private ?string $prefix = ''
    ) {
        //  
    }

    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        if ($this->name === null) {
            $this->setName($this->pattern);
        }
        return $this->name;
    }

    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function getPattern()
    {
        if ($this->prefix) {
            return $this->prefix . $this->pattern;
        }
        return $this->pattern;
    }
}
