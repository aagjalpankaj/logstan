<?php

declare(strict_types=1);

namespace Tests\Unit\Rules;

use Aagjalpankaj\Logstan\Rules\LogContextRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<LogContextRule>
 */
class LogContextRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new LogContextRule;
    }

    public function test_log_context(): void
    {
        $this->analyse(
            [
                __DIR__.'/../../Dataset/logs-contexts.php',
            ],
            [
                [
                    'Log context key "orderId" should be in snake_case format.',
                    7,
                ],
            ]
        );

        // TODO: Finish remaining testcases
    }
}
