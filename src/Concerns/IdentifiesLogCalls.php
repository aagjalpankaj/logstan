<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Concerns;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;

trait IdentifiesLogCalls
{
    /**
     * Check if the node is a Laravel Log facade call.
     */
    protected function isLogCall(Node $node): bool
    {
        if (! $node instanceof StaticCall) {
            return false;
        }

        return $node->class instanceof Node\Name &&
               $node->class->toString() === \Illuminate\Support\Facades\Log::class;
    }

    /**
     * Check if the method name is a valid log level.
     */
    protected function isLogLevel(Node $node): bool
    {
        if (! $node instanceof StaticCall || ! $node->name instanceof Node\Identifier) {
            return false;
        }

        $logLevels = ['info', 'debug', 'error', 'warning', 'notice', 'alert', 'critical', 'emergency'];

        return in_array($node->name->name, $logLevels);
    }

    /**
     * Get the method name of the log call.
     */
    protected function getLogMethodName(StaticCall $node): ?string
    {
        return $node->name instanceof Node\Identifier ? $node->name->name : null;
    }

    /**
     * Process a node and return early if it's not a valid log call.
     */
    protected function processLogCall(Node $node, Scope $scope): ?StaticCall
    {
        if (! $this->isLogCall($node)) {
            return null;
        }

        if (! $this->isLogLevel($node)) {
            return null;
        }

        return $node;
    }
}
