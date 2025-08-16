<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Rules;

use Aagjalpankaj\Logstan\Concerns\IdentifiesLog;
use Aagjalpankaj\Logstan\Config;
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

    private Config $config;

    public function __construct()
    {
        $this->config = Config::load();
    }

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

        if (strlen($message) > $this->config->logMessageMaxLength) {
            $errors[] = RuleErrorBuilder::message(
                sprintf('Log message "%s" exceeds maximum length of %d characters.', $message, $this->config->logMessageMaxLength)
            )->build();
        }

        if (preg_match('/^[a-z]/', $trimmedMessage)) {
            $errors[] = RuleErrorBuilder::message(
                sprintf('Log message "%s" should start with an uppercase letter.', $message)
            )->build();
        }

        return $errors;
    }
}
