<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Rules;

use Aagjalpankaj\Logstan\Concerns\IdentifiesLogCalls;
use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<StaticCall>
 */
class InsightsRule implements Rule
{
    use IdentifiesLogCalls;

    /** @var array<string, array<string, int>> */
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

        // Track log call statistics
        $file = $scope->getFile();
        $node->getLine();
        $method = $logCall->name->toString();

        // Initialize file entry if it doesn't exist
        if (! isset(self::$logStats[$file])) {
            self::$logStats[$file] = [
                'total' => 0,
                'methods' => [],
            ];
        }

        // Increment total count for this file
        self::$logStats[$file]['total']++;

        // Track method usage
        if (! isset(self::$logStats[$file]['methods'][$method])) {
            self::$logStats[$file]['methods'][$method] = 0;
        }
        self::$logStats[$file]['methods'][$method]++;

        // Store this data in a way that can be accessed by InsightsCommand
        $this->saveLogStats();

        return [];
    }

    /**
     * Save log statistics to a file that can be read by InsightsCommand
     */
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

    /**
     * Get the total number of log calls
     */
    public static function getTotalLogCalls(): int
    {
        $total = 0;
        foreach (self::$logStats as $fileStats) {
            $total += $fileStats['total'];
        }

        return $total;
    }

    /**
     * Get all log statistics
     *
     * @return array<string, array<string, int>>
     */
    public static function getLogStats(): array
    {
        return self::$logStats;
    }
}
