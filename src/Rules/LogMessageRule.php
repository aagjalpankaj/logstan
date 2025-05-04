<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Rules;

use Aagjalpankaj\Logstan\Concerns\IdentifiesLogCalls;
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
    use IdentifiesLogCalls;

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
