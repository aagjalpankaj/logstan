<?php

declare(strict_types=1);

arch('Commands')
    ->expect('Aagjalpankaj\Logstan\Commands')
    ->toHaveSuffix('Command');

arch('Middlewares')
    ->expect('Aagjalpankaj\Logstan\Middlewares')
    ->toHaveSuffix('Middleware');

arch('Dtos')
    ->expect('Aagjalpankaj\Logstan\Dtos')
    ->toHaveSuffix('Dto');

arch('Exceptions')
    ->expect('Aagjalpankaj\Logstan\Exceptions')
    ->toHaveSuffix('Exception');
