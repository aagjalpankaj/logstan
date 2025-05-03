<?php

declare(strict_types=1);

pest()->extend(Tests\FeatureTestCase::class)->in('Feature');
pest()->extend(Tests\UnitTestCase::class)->in('Unit');
