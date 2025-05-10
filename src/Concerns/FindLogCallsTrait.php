<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Concerns;

use Aagjalpankaj\Logstan\Enums\LogLevel;
use Illuminate\Support\Facades\Log;
use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;

trait FindLogCallsTrait
{
    protected function isLogCall(Node $node): bool
    {
        if (! $node instanceof StaticCall) {
            return false;
        }

        return $node->class instanceof Node\Name &&
               $node->class->toString() === Log::class;
    }

    protected function isLogLevel(Node $node): bool
    {
        if (! $node instanceof StaticCall || ! $node->name instanceof Node\Identifier) {
            return false;
        }

        $logLevels = LogLevel::values();

        return in_array($node->name->name, $logLevels);
    }

    protected function getLogMethodName(StaticCall $node): ?string
    {
        return $node->name instanceof Node\Identifier ? $node->name->name : null;
    }

    protected function processLogCall(Node $node): ?Node
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
