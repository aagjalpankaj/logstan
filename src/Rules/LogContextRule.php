<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Rules;

use Aagjalpankaj\Logstan\Concerns\FindLogCallsTrait;
use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ArrayType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\VerbosityLevel;

/**
 * @implements Rule<StaticCall>
 */
class LogContextRule implements Rule
{
    use FindLogCallsTrait;

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

        $errors = [];

        foreach ($contextArg->items as $item) {
            if ($item === null) {
                continue;
            }

            if ($item->key instanceof Node\Scalar\String_) {
                $key = $item->key->value;

                if (! $this->isCamelCase($item->key->value)) {
                    $errors[] = RuleErrorBuilder::message(
                        sprintf('Context key "%s" should be in camelCase format', $item->key->value)
                    )->build();
                }

                $valueType = $scope->getType($item->value);

                if ($valueType instanceof ObjectType || $valueType instanceof ArrayType) {
                    $errors[] = RuleErrorBuilder::message(
                        sprintf(
                            'Context key "%s" must have a scalar value, %s provided',
                            $key,
                            $valueType->describe(VerbosityLevel::typeOnly())
                        )
                    )->build();
                }
            }
        }

        return $errors;
    }

    private function isCamelCase(string $string): bool
    {
        return preg_match('/^[a-z][a-zA-Z0-9]*$/', $string) === 1;
    }
}
