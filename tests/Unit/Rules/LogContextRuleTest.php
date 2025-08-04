<?php

declare(strict_types=1);

use Aagjalpankaj\Logstan\Rules\LogContextRule;

beforeEach(function () {
    $this->rule = new LogContextRule;
});

it('if log context is non-array', function () {
    $this->analyse(
        [
            __DIR__.'/../../Dataset/lc-non-array.php',
        ],
        [
            [
                'Log context must be an array.',
                7,
            ],
        ]
    );
});

it('reports camel case keys', function () {
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
});

it('reports log context exceeds key limit', function () {
    $this->analyse(
        [
            __DIR__.'/../../Dataset/lc-exceeds-key-limit.php',
        ],
        [
            [
                'Log context has too many keys (11). Maximum allowed are 10.',
                7,
            ],
        ]
    );
});

it('reports non scalar context values', function () {
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
});
