<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Validators;

class LogMessageValidator
{
    private const MAX_LENGTH = 50;

    public function validate(string $message): array
    {
        $errors = [];

        if ($message === '' || $message === '0') {
            $errors[] = sprintf('Log message "%s" cannot be empty.', $message);
        }

        if (strlen($message) > self::MAX_LENGTH) {
            $errors[] = sprintf('Log message "%s" exceeds maximum length of %d characters.', $message, self::MAX_LENGTH);
        }

        if (preg_match('/^[a-z]/', $message)) {
            $errors[] = sprintf('Log message "%s" should start with an uppercase letter.', $message);
        }

        if (in_array(preg_match('/[.!?]$/', $message), [0, false], true)) {
            $errors[] = sprintf('Log message "%s" should end with a punctuation mark (., !, or ?).', $message);
        }

        if (preg_match('/\b(password|secret|key)\b/i', $message)) {
            $errors[] = sprintf('Log message "%s" contains sensitive information (password, secret, or key).', $message);
        }

        return $errors;
    }

    public function isValid(string $message): bool
    {
        return $this->validate($message) === [];
    }
}
