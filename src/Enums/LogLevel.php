<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Enums;

enum LogLevel: string
{
    case INFO = 'info';
    case DEBUG = 'debug';
    case ERROR = 'error';
    case WARNING = 'warning';
    case NOTICE = 'notice';
    case ALERT = 'alert';
    case CRITICAL = 'critical';
    case EMERGENCY = 'emergency';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
