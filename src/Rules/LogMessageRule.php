<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Rules;

use Aagjalpankaj\Logstan\Concerns\LocatesLogCall;
use Aagjalpankaj\Logstan\Validators\LogMessageValidator;
use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<StaticCall>
 */
class LogMessageRule implements Rule
{
    use LocatesLogCall;

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
        $errors = (new LogMessageValidator)->validate($message);

        if ($errors !== []) {
            return array_map(fn ($error): RuleError => RuleErrorBuilder::message($error)->build(), $errors);
        }

        return [];
    }
}
