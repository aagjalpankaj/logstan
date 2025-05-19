<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Log;

Log::info('Order created', [
    'order_id' => 123,
    'user_id' => 456,
    'product_id' => 789,
    'quantity' => 2,
    'price' => 99.99,
    'currency' => 'USD',
    'payment_method' => 'credit_card',
    'shipping_method' => 'express',
    'status' => 'pending',
    'created_at' => '2023-10-15',
    'updated_at' => '2023-10-15',
]);
