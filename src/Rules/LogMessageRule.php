<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Rules;

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
    public function getNodeType(): string
    {
        return StaticCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        // Only handle Log::level() static calls
        if (! $node instanceof StaticCall) {
            return [];
        }

        if (! $node->class instanceof Node\Name || $node->class->toString() !== \Illuminate\Support\Facades\Log::class) {
            return [];
        }

        $logLevels = ['info', 'debug', 'error', 'warning', 'notice', 'alert', 'critical', 'emergency'];
        $methodName = $node->name instanceof Node\Identifier ? $node->name->name : null;

        if (! in_array($methodName, $logLevels)) {
            return [];
        }

        $args = $node->args;
        if (count($args) === 0) {
            return [];
        }

        $messageArg = $args[0]->value;

        if (! $messageArg instanceof Node\Scalar\String_) {
            return [];
        }

        $message = $messageArg->value;
        if (strlen($message) > 5) {
            return [
                RuleErrorBuilder::message(
                    sprintf('Log message "%s" is too long (%d characters)', $message, strlen($message))
                )->build(),
            ];
        }

        return [];
    }
}
