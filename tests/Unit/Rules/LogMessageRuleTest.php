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

    public function test_rule(): void
    {
        $this->analyse([__DIR__.'/../data/logs.php'], [
            [
                'Log message "hello world" should start with an uppercase letter.',
                7,
            ],
        ]);
    }
}
