<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NotificationController;

// Redirect root ទៅ admin login
Route::get('/', function () {
    return redirect()->route('admin.login');
});

// ===== ADMIN ROUTES =====
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth routes (no login needed)
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    //notification
    Route::resource('orders', OrderController::class)->only(['index', 'show']);
    Route::post(
        '/orders/{order}/status/{status}',
        [OrderController::class, 'updateStatus']
    )->name('orders.status');

    // Protected routes (login required)
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('orders', OrderController::class)->only(['index', 'show']);
        Route::get('/users/{user}/orders', [OrderController::class, 'userOrders'])
            ->name('users.orders');
        Route::resource('users', UserController::class)->only(['index', 'show']);
        Route::post('/users/{user}/verify', [UserController::class, 'verify'])
            ->name('users.verify');
        Route::get('/notifications', [NotificationController::class, 'index'])
            ->name('notifications.index');
        Route::post('/notifications/read', [NotificationController::class, 'markAllRead'])
            ->name('notifications.read');
        Route::post('/notifications/{order}/read', [NotificationController::class, 'markOrderRead'])
            ->name('notifications.order.read');
        Route::get('/notifications/count', [NotificationController::class, 'unreadCount'])
            ->name('notifications.count');
    });
});
