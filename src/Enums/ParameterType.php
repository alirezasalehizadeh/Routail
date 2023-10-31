<?php

namespace AlirezaSalehizadeh\Routail\Enums;

enum ParameterType: string
{
    case ANY = "any";
    case ID = "id";
    case INT = "int";
    case STRING = "string";
    case UUID = "uuid";
    case BOOL = "bool";
    case SLUG = "slug";
    case DATE = "date";
    case INT_NULLABLE = "int?";
    case ANY_NULLABLE = "any?";

    public static function find(string $parameterType)
    {
        return self::tryFrom(strtolower($parameterType));
    }
}
