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

    public function testReportsCamelCaseKeys(): void
    {
        $this->analyse(
            [
                __DIR__.'/../../Dataset/lc-camelcase.php',
            ],
            [
                [
                    'Log context key "orderId" should be in snake_case format.',
                    7,
                ],
            ]
        );
    }

    public function testReportsLogContextExceedsKeyLimit(): void
    {
        $this->analyse(
            [
                __DIR__.'/../../Dataset/lc-exceeds-key-limit.php',
            ],
            [
                [
                    'Log context has too many keys (11). Maximum allowed is 10.',
                    7,
                ],
            ]
        );
    }

    public function testReportsNonScalarContextValues(): void
    {
        $this->analyse(
            [
                __DIR__.'/../../Dataset/lc-has-non-scalar-values.php',
            ],
            [
                [
                    'Log context value of key "settings" must be scalar, null or array of scalar. "object{theme: \'dark\', notifications: true}&stdClass" provided.',
                    7,
                ],
            ]
        );
    }
}
