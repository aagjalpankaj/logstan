<?php

declare(strict_types=1);

arch('Commands')
    ->expect('Aagjalpankaj\LaravelPackageTemplate\Commands')
    ->toHaveSuffix('Command');

arch('Middlewares')
    ->expect('Aagjalpankaj\LaravelPackageTemplate\Middlewares')
    ->toHaveSuffix('Middleware');

arch('Dtos')
    ->expect('Aagjalpankaj\LaravelPackageTemplate\Dtos')
    ->toHaveSuffix('Dto');

arch('Exceptions')
    ->expect('Aagjalpankaj\LaravelPackageTemplate\Exceptions')
    ->toHaveSuffix('Exception');
