<?php

declare(strict_types=1);

arch('Commands')
    ->expect('Aagjalpankaj\Logstan\Commands')
    ->toHaveSuffix('Command')
    ->toExtend('Symfony\Component\Console\Command\Command');

arch('Concerns')
    ->expect('Aagjalpankaj\Logstan\Concerns')
    ->toBeTraits();

arch('Enums')
    ->expect('Aagjalpankaj\Logstan\Enums')
    ->toBeEnums();

arch('Rules')
    ->expect('Aagjalpankaj\Logstan\Rules')
    ->toHaveSuffix('Rule')
    ->toImplement('PHPStan\Rules\Rule');
