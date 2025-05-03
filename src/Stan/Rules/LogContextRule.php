<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Stan\Rules;

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
        if (count($args) < 2) {
            return [];
        }

        // Check if there's a context array
        $contextArg = $args[1]->value;

        // If it's not an array, report an error
        if (! $contextArg instanceof Node\Expr\Array_) {
            return [
                RuleErrorBuilder::message('Log context must be an array')->build(),
            ];
        }

        $errors = [];

        // Check each item in the context array
        foreach ($contextArg->items as $item) {
            if ($item === null) {
                continue;
            }

            // Check if the key is a string
            if ($item->key instanceof Node\Scalar\String_) {
                $key = $item->key->value;

                if ($this->isSensitiveKey($key)) {
                    $errors[] = RuleErrorBuilder::message(
                        sprintf('Context key "%s" appears to contain sensitive information', $key)
                    )->build();
                }

                // Check if the value is a scalar
                if (! $this->isScalarValue($item->value)) {
                    $errors[] = RuleErrorBuilder::message(
                        sprintf('Context key "%s" must have a scalar value', $key)
                    )->build();
                }
            }
        }

        return $errors;
    }

    private function isScalarValue(Node $node): bool
    {
        return $node instanceof Node\Scalar\String_
            || $node instanceof Node\Scalar\LNumber
            || $node instanceof Node\Scalar\DNumber
            || $node instanceof Node\Expr\ConstFetch && in_array($node->name->toString(), ['true', 'false', 'null']);
    }

    private function isSensitiveKey(string $key): bool
    {
        $sensitivePatterns = [
            'password',
            'passwd',
            'secret',
            'credential',
            'token',
            'auth',
            'key',
            'api_key',
            'apikey',
            'access_token',
            'refresh_token',
            'private',
            'ssn',
            'social_security',
            'credit_card',
            'card_number',
            'cvv',
            'pin',
        ];

        $lowercaseKey = strtolower($key);

        foreach ($sensitivePatterns as $pattern) {
            if (str_contains($lowercaseKey, $pattern)) {
                return true;
            }
        }

        return false;
    }
}
