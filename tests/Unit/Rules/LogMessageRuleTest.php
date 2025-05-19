<?php

declare(strict_types=1);

namespace Tests\Unit\Rules;

use Aagjalpankaj\Logstan\Rules\LogMessageRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<LogMessageRule>
 */
class LogMessageRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new LogMessageRule;
    }

    public function testReportsEmptyLogMessages(): void
    {
        $this->analyse(
            [
                __DIR__.'/../../Dataset/lm-empty.php',
            ],
            [
                [
                    'Log message "" cannot be empty.',
                    7,
                ],
            ]
        );
    }

    public function testReportsLongLogMessages(): void
    {
        $this->analyse(
            [
                __DIR__.'/../../Dataset/lm-exceeds-limit.php',
            ],
            [
                [
                    'Log message "User authentication failed after multiple attempts. The system has temporarily locked the account and sent a notification email to the registered address for security verification purposes." exceeds maximum length of 120 characters.',
                    7,
                ],
            ]
        );
    }

    public function testReportsNonUppercaseLogMessages(): void
    {
        $this->analyse(
            [
                __DIR__.'/../../Dataset/lm-starts-with-lowercase.php',
            ],
            [
                [
                    'Log message "order created" should start with an uppercase letter.',
                    7,
                ],
            ]
        );
    }
}
