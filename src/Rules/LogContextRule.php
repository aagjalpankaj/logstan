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
use PHPStan\Type\VerbosityLevel;

/**
 * @implements Rule<StaticCall>
 */
class LogContextRule implements Rule
{
    use IdentifiesLog;
    use SensitiveTerms;

    private const MAX_KEYS = 10;

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

        if (count($contextArg->items) > self::MAX_KEYS) {
            $errors[] = RuleErrorBuilder::message(sprintf(
                'Log context has too many keys (%d). Maximum allowed is %d.',
                count($contextArg->items),
                self::MAX_KEYS
            ))->build();
        }

        foreach ($contextArg->items as $item) {

            if (! $item->key instanceof Node\Scalar\String_) {
                continue;
            }

            $key = $item->key->value;
            $valueType = $scope->getType($item->value);

            if (! is_string($key)) {
                $errors[] = RuleErrorBuilder::message(sprintf('Log context key must be a string. Found: %s.', gettype($key)))->build();
            }

            if ($key === '') {
                $errors[] = RuleErrorBuilder::message('Log context contains an empty key.')->build();
            }

            if (in_array(preg_match('/^[a-z][a-z0-9]*(_[a-z0-9]+)*$/', $key), [0, false], true)) {
                $errors[] = RuleErrorBuilder::message(sprintf('Log context key "%s" should be in snake_case format.', $key))->build();
            }

            if (
                ! $valueType->isScalar()->yes() &&
                ! $valueType->isNull()->yes() &&
                ! (
                    $valueType->isArray()->yes() &&
                    $valueType->getIterableValueType()->isScalar()->yes()
                )
            ) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'Log context value of key "%s" must be scalar, null or array of scalar. "%s" provided.',
                        $key,
                        $valueType->describe(VerbosityLevel::value())
                    )
                )->build();
            }

            foreach (self::SENSITIVE_TERMS as $term) {
                if (stripos($key, $term) !== false) {
                    $errors[] = RuleErrorBuilder::message(
                        sprintf('Log context key "%s" contains sensitive information.', $key)
                    )->build();
                    break;
                }
            }
        }

        return $errors;
    }
}
