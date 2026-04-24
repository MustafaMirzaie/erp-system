<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Models\Order;

Route::get('/test-order', function () {
    $order = Order::first();

    if (!$order) {
        return 'No orders found in database!';
    }

    return $order->load(['customer','items','approvals']);
});
