<?php

use App\Http\Controllers\API\AdminAuthController;
use App\Http\Controllers\API\MemberAuthController;
use App\Http\Middleware\AdminRoleMiddleware;
use App\Http\Controllers\API\ResetPasswordController;

// Routes untuk Admin
Route::prefix('admin')->group(function () {
    Route::post('register', [AdminAuthController::class, 'register']);
    Route::post('login', [AdminAuthController::class, 'login']);

    Route::middleware(['auth:admin', 'role:superadmin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout']);
        Route::get('me', [AdminAuthController::class, 'me']);
        Route::apiResource('posts', PostController::class); // Hanya superadmin bisa kelola post
        Route::apiResource('categories', CategoryController::class);
    });

    Route::middleware(['auth:admin', 'role:admin'])->group(function () {
        Route::apiResource('comments', CommentController::class);
        Route::apiResource('ads', AdController::class);
    });

    Route::middleware(['auth:admin', 'role:editor'])->group(function () {
        Route::get('edit-content', [EditorController::class, 'index']); // Contoh hak akses editor
    });
});

// Routes untuk Member
Route::prefix('member')->group(function () {
    Route::post('register', [MemberAuthController::class, 'register']);
    Route::post('login', [MemberAuthController::class, 'login']);

    Route::middleware('auth:member')->group(function () {
        Route::post('logout', [MemberAuthController::class, 'logout']);
        Route::get('me', [MemberAuthController::class, 'me']);
    });
});

//Reset Password
Route::post('password/email', [ResetPasswordController::class, 'sendResetLinkEmail']);
Route::post('password/reset', [ResetPasswordController::class, 'resetPassword']);
Route::get('email/verify/{id}', [VerificationController::class, 'verifyEmail']);

//Subs
Route::middleware('auth:member')->group(function () {
    Route::get('subscription', [SubscriptionController::class, 'index']);
    Route::post('subscribe', [SubscriptionController::class, 'subscribe']);
});

//Statistics
Route::middleware('auth:admin')->get('admin/stats', [AdminDashboardController::class, 'stats']);

//MAPS
Route::get('location', [LocationController::class, 'getLocation']);

