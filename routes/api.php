<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\WorkflowController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\LookupController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        // Profile
        Route::get('/profile', [ProfileController::class, 'me']);
        Route::put('/profile', [ProfileController::class, 'update']);

        // Orders
        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);

        // Customers
        Route::get('/customers', [CustomerController::class, 'index']);
        Route::get('/customers/{id}', [CustomerController::class, 'show']);
        Route::get('/customers/{id}/addresses', [CustomerController::class, 'addresses']);

        // Products
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/{id}', [ProductController::class, 'show']);

        // Companies
        Route::get('/companies', [CompanyController::class, 'index']);

        // Users
        Route::get('/users', [UserController::class, 'index']);

        // Lookups
        Route::get('/lookup/packaging-types', [LookupController::class, 'packagingTypes']);
        Route::get('/lookup/units',           [LookupController::class, 'units']);
        Route::get('/lookup/freight-types',   [LookupController::class, 'freightTypes']);

        // Workflow
        Route::get('/workflow/steps', [WorkflowController::class, 'steps']);
        Route::get('/workflow/orders/{orderId}/approvals', [WorkflowController::class, 'orderApprovals']);
        Route::post('/workflow/approvals/{approvalId}/approve', [WorkflowController::class, 'approve']);
        Route::post('/workflow/approvals/{approvalId}/reject', [WorkflowController::class, 'reject']);

        // Reports
        Route::prefix('reports')->group(function () {
            Route::get('/summary',          [ReportController::class, 'summary']);
            Route::get('/orders-by-status', [ReportController::class, 'ordersByStatus']);
            Route::get('/top-customers',    [ReportController::class, 'topCustomers']);
            Route::get('/top-sales-users',  [ReportController::class, 'topSalesUsers']);
        });
    });

});
