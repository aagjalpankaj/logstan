<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Rules;

use Aagjalpankaj\Logstan\Concerns\IdentifiesLog;
use Aagjalpankaj\Logstan\Concerns\SensitiveTerms;
use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<StaticCall>
 */
class LogMessageRule implements Rule
{
    use IdentifiesLog;
    use SensitiveTerms;

    private const MAX_LENGTH = 120;

    public function getNodeType(): string
    {
        return StaticCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $logCall = $this->processLogCall($node);
        if (! $logCall instanceof StaticCall) {
            return [];
        }

        $args = $logCall->args;

        if (count($args) === 0) {
            return [];
        }

        $messageArg = $args[0]->value;

        if (! $messageArg instanceof Node\Scalar\String_) {
            return [];
        }

        $message = $messageArg->value;
        $errors = [];
        $trimmedMessage = trim($message);

        if ($trimmedMessage === '') {
            $errors[] = RuleErrorBuilder::message(sprintf('Log message "%s" cannot be empty.', $message))->build();
        }

        if (strlen($message) > self::MAX_LENGTH) {
            $errors[] = RuleErrorBuilder::message(
                sprintf('Log message "%s" exceeds maximum length of %d characters.', $message, self::MAX_LENGTH)
            )->build();
        }

        if (preg_match('/^[a-z]/', $trimmedMessage)) {
            $errors[] = RuleErrorBuilder::message(
                sprintf('Log message "%s" should start with an uppercase letter.', $message)
            )->build();
        }

        foreach (self::SENSITIVE_TERMS as $term) {
            if (stripos($trimmedMessage, $term) !== false) {

                $errors[] = RuleErrorBuilder::message(
                    sprintf('Log message "%s" contains sensitive information ("%s").', $message, $term)
                )->build();
            }
        }

        return $errors;
    }
}
