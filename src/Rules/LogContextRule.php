<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Rules;

use Aagjalpankaj\Logstan\Concerns\IdentifiesLog;
use Aagjalpankaj\Logstan\Validators\LogContextValidator;
use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<StaticCall>
 */
class LogContextRule implements Rule
{
    use IdentifiesLog;

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

        if (count($args) < 2) {
            return [];
        }

        $contextArg = $args[1]->value;

        if (! $contextArg instanceof Node\Expr\Array_) {
            return [
                RuleErrorBuilder::message('Log context must be an array')->build(),
            ];
        }

        $context = [];
        foreach ($contextArg->items as $item) {
            if ($item === null) {
                continue;
            }
            if (! $item->key instanceof Node\Scalar\String_) {
                continue;
            }
            $key = $item->key->value;
            $value = $scope->getType($item->value)->describe(\PHPStan\Type\VerbosityLevel::value());
            $context[$key] = $value;
        }

        $errors = (new LogContextValidator)->validate($context);

        return array_map(fn ($error): \PHPStan\Rules\RuleError => RuleErrorBuilder::message($error)->build(), $errors);
    }
}
