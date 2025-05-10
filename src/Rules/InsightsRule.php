<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Rules;

use Aagjalpankaj\Logstan\Concerns\FindLogCallsTrait;
use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<StaticCall>
 */
class InsightsRule implements Rule
{
    use FindLogCallsTrait;

    /** @var array<string, array<string, mixed>> */
    private static array $logStats = [];

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

        $file = $scope->getFile();
        $method = $logCall->name->toString();

        if (! isset(self::$logStats[$file])) {
            self::$logStats[$file] = [
                'total' => 0,
                'methods' => [],
                'contextKeys' => [],
            ];
        }

        self::$logStats[$file]['total']++;

        if (! isset(self::$logStats[$file]['methods'][$method])) {
            self::$logStats[$file]['methods'][$method] = 0;
        }
        self::$logStats[$file]['methods'][$method]++;

        // Process context keys
        if (count($args) >= 2 && $args[1]->value instanceof Node\Expr\Array_) {
            foreach ($args[1]->value->items as $item) {
                if ($item !== null && $item->key instanceof Node\Scalar\String_) {
                    $contextKey = $item->key->value;
                    if (! isset(self::$logStats[$file]['contextKeys'][$contextKey])) {
                        self::$logStats[$file]['contextKeys'][$contextKey] = 0;
                    }
                    self::$logStats[$file]['contextKeys'][$contextKey]++;
                }
            }
        }

        $this->saveLogStats();

        return [];
    }

    private function saveLogStats(): void
    {
        $statsDir = sys_get_temp_dir().'/logstan';
        if (! is_dir($statsDir)) {
            mkdir($statsDir, 0755, true);
        }

        file_put_contents(
            $statsDir.'/log_stats.json',
            json_encode(self::$logStats, JSON_PRETTY_PRINT)
        );
    }
}
