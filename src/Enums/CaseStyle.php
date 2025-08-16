<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Enums;

enum CaseStyle: string
{
    case SNAKE_CASE = 'snake_case';
    case CAMEL_CASE = 'camelCase';
    case PASCAL_CASE = 'PascalCase';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
