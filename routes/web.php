<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/orders', function () {
    return view('orders.index');
});

Route::get('/orders/create', function () {
    return view('orders.create');
});

Route::get('/orders/{id}', function ($id) {
    return view('orders.show', ['id' => $id]);
});

Route::get('/customers', function () {
    return view('customers.index');
});

Route::get('/products', function () {
    return view('products.index');
});

Route::get('/workflow', function () {
    return view('workflow.index');
});
