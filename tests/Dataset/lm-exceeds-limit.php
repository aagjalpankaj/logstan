<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Log;

Log::error('User authentication failed after multiple attempts. The system has temporarily locked the account and sent a notification email to the registered address for security verification purposes.');
