<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Log;

Log::info('User profile updated', [
    'user_id' => 123,
    'profile' => ['name' => 'John Doe', 'email' => 'john@example.com'],
    'settings' => (object) ['theme' => 'dark', 'notifications' => true],
]);
