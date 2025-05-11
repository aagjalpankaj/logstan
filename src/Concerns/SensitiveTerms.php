<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Concerns;

trait SensitiveTerms
{
    private const SENSITIVE_TERMS = [
        'password',
        'secret',
        'key',
        'api_key',
        'token',
    ];
}
