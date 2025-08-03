<?php

declare(strict_types=1);

use Aagjalpankaj\Logstan\Rules\LogMessageRule;

beforeEach(function () {
    $this->rule = new LogMessageRule;
});

it('reports empty log messages', function () {
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
});

it('reports long log messages', function () {
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
});

it('reports non uppercase log messages', function () {
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
});

function getRule(): LogMessageRule
{
    return new LogMessageRule;
}
