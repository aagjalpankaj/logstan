<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Validators;

use Aagjalpankaj\Logstan\Concerns\SensitiveTerms;

class LogMessageValidator
{
    use SensitiveTerms;

    private const MAX_LENGTH = 120;

    public function validate(string $message): array
    {
        $errors = [];

        $trimmedMessage = trim($message);

        if ($trimmedMessage === '') {
            $errors[] = sprintf('Log message "%s" cannot be empty.', $message);
        }

        if (strlen($message) > self::MAX_LENGTH) {
            $errors[] = sprintf('Log message "%s" exceeds maximum length of %d characters.', $message, self::MAX_LENGTH);
        }

        if (preg_match('/^[a-z]/', $trimmedMessage)) {
            $errors[] = sprintf('Log message "%s" should start with an uppercase letter.', $message);
        }

        foreach (self::SENSITIVE_TERMS as $term) {
            if (stripos($trimmedMessage, $term) !== false) {
                $errors[] = sprintf('Log message "%s" contains sensitive information ("%s").', $message, $term);
            }
        }

        return $errors;
    }

    public function isValid(string $message): bool
    {
        return $this->validate($message) === [];
    }
}
