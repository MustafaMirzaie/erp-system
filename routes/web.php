<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/login'));
Route::get('/login', fn() => view('auth.login'));

// Dashboard
Route::get('/dashboard', fn() => view('dashboard'));

// Orders
Route::get('/orders', fn() => view('orders.index'));
Route::get('/orders/create', fn() => view('orders.create'));
Route::get('/orders/{id}', fn($id) => view('orders.show', ['id' => $id]));
Route::get('/orders/{id}/approve', fn($id) => view('orders.approve', ['id' => $id]));
Route::get('/orders/{id}/print', fn($id) => view('orders.print', ['id' => $id]));

// Customers
Route::get('/customers', fn() => view('customers.index'));
Route::get('/customers/create', fn() => view('customers.create'));
Route::get('/customers/reports', fn() => view('customers.reports'));
Route::get('/customers/{id}', fn($id) => view('customers.manage', ['customerId' => $id]));
Route::get('/customers/{id}/addresses/create', fn($id) => view('customers.addresses.create', ['customerId' => $id]));
Route::get('/customers/{id}/manage', fn($id) => view('customers.manage', ['customerId' => $id]));

// Products
Route::get('/products', fn() => view('products.index'));
Route::get('/products/create', fn() => view('products.create'));

// Packaging
Route::get('/packaging', fn() => view('packaging.index'));
Route::get('/packaging/create', fn() => view('packaging.create'));

// Users
Route::get('/users', fn() => view('users.index'));
Route::get('/users/create', fn() => view('users.create'));

// Roles
Route::get('/roles', fn() => view('roles.index'));
Route::get('/roles/create', fn() => view('roles.create'));

// Workflow
Route::get('/workflow', fn() => view('workflow.index'));
Route::get('/workflow/create', fn() => view('workflow.create'));

// Profile
Route::get('/profile', fn() => view('profile'));

// Inbox
Route::get('/inbox', fn() => view('inbox.index'));
Route::get('/inbox/order/{id}', fn($id) => view('inbox.show', ['id' => $id]));
