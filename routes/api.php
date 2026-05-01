<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\WorkflowController;
use App\Http\Controllers\Api\CompanyController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

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

        // Workflow
        Route::get('/workflow/steps', [WorkflowController::class, 'steps']);
        Route::get('/workflow/orders/{orderId}/approvals', [WorkflowController::class, 'orderApprovals']);
        Route::post('/workflow/approvals/{approvalId}/approve', [WorkflowController::class, 'approve']);
        Route::post('/workflow/approvals/{approvalId}/reject', [WorkflowController::class, 'reject']);
    });

});
