<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Validators;

class LogContextValidator
{
    private const MAX_KEYS = 10;

    private const MAX_VALUE_LENGTH = 100;

    public function validate(array $context): array
    {
        $errors = [];

        if (count($context) > self::MAX_KEYS) {
            $errors[] = sprintf('Log context has too many keys (%d). Maximum allowed is %d.', count($context), self::MAX_KEYS);
        }

        foreach ($context as $key => $value) {
            if (! is_string($key)) {
                $errors[] = sprintf('Log context key must be a string. Found: %s', gettype($key));

                continue;
            }

            if ($key === '') {
                $errors[] = 'Log context contains an empty key.';

                continue;
            }

            if (in_array(preg_match('/^[a-z][a-z0-9]*(_[a-z0-9]+)*$/', $key), [0, false], true)) {
                $errors[] = sprintf('Log context key "%s" should be in snake_case format.', $key);
            }

            if (is_array($value) || is_object($value)) {
                $errors[] = sprintf('Log context value for key "%s" must be a scalar or null. Found: %s', $key, gettype($value));
            } elseif (is_string($value) && strlen($value) > self::MAX_VALUE_LENGTH) {
                $errors[] = sprintf('Log context value for key "%s" is too long (%d characters). Maximum allowed is %d.', $key, strlen($value), self::MAX_VALUE_LENGTH);
            }

            if (preg_match('/\b(password|secret|key)\b/i', $key)) {
                $errors[] = sprintf('Log context key "%s" contains sensitive information.', $key);
            }
        }

        return $errors;
    }

    public function isValid(array $context): bool
    {
        return $this->validate($context) === [];
    }
}
