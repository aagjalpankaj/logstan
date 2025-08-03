<?php

declare(strict_types=1);

namespace Tests;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

abstract class UnitTestCase extends RuleTestCase
{
    protected Rule $rule;

    protected function getRule(): Rule
    {
        return $this->rule;
    }
}
