<?php

use App\Http\Controllers\authController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\restautant\itmeController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\users\userController;
use App\Http\Middleware\auth;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ContactController;

Route::middleware([auth::class])->group(function () {
    Route::controller(dashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard.index');
    });
    
    // ============================================
    // SUPER ADMIN ONLY ROUTES
    // ============================================
    Route::middleware('role:super_admin')->group(function () {
        // User Management (Restaurants)
        Route::controller(userController::class)->group(function () {
            Route::get('/users', 'index')->name('users.index');
            Route::get('/users/create', 'create')->name('users.create');
            Route::post('/users', 'store')->name('users.store');
            Route::get('/users/{id}/edit', 'edit')->name('users.edit');
            Route::post('/users/{id}', 'update')->name('users.update');
            Route::delete('/users/{id}', 'destroy')->name('users.destroy');
            Route::get('/users/{id}/edit-password', 'editPassword')->name('users.edit_password');
            Route::post('/users/{id}/update-password', 'updatePassword')->name('users.update_password');
        });

        // Subscription Management (Super Admin)
        Route::controller(SubscriptionController::class)->group(function () {
            Route::get('/users/{userId}/subscriptions', 'index')->name('subscriptions.index');
            Route::get('/users/{userId}/subscriptions/create', 'create')->name('subscriptions.create');
            Route::post('/users/{userId}/subscriptions', 'store')->name('subscriptions.store');
            Route::delete('/subscriptions/{id}', 'destroy')->name('subscriptions.destroy');
        });

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/download', [ReportController::class, 'download'])->name('reports.download');
    });

    // ============================================
    // RESTAURANT ADMIN ROUTES
    // ============================================
    Route::middleware('role:restaurant_admin')->group(function () {
        // Items Routes
        Route::controller(itmeController::class)->group(function () {
            Route::get('/items', 'index')->name('items.index');
            Route::get('/items/create', 'create')->name('items.create');
            Route::post('/items', 'store')->name('items.store');
            Route::get('/items/{id}/edit', 'edit')->name('items.edit');
            Route::post('/items/{id}', 'update')->name('items.update');
            Route::delete('/items/{id}', 'destroy')->name('items.destroy');
        });

        // Tables Routes
        Route::controller(TableController::class)->group(function () {
            Route::get('/tables', 'index')->name('tables.index');
            Route::get('/tables/create', 'create')->name('tables.create');
            Route::post('/tables', 'store')->name('tables.store');
            Route::get('/tables/{id}/edit', 'edit')->name('tables.edit');
            Route::post('/tables/{id}', 'update')->name('tables.update');
            Route::delete('/tables/{id}', 'destroy')->name('tables.destroy');
        });

        // Staff Management
        Route::controller(userController::class)->group(function () {
            Route::get('/staff', 'staffIndex')->name('staff.index');
            Route::get('/staff/create', 'staffCreate')->name('staff.create');
            Route::post('/staff', 'staffStore')->name('staff.store');
            Route::get('/staff/{id}/edit', 'staffEdit')->name('staff.edit');
            Route::post('/staff/{id}', 'staffUpdate')->name('staff.update');
            Route::delete('/staff/{id}', 'staffDestroy')->name('staff.destroy');
        });
    });

    // ============================================
    // ACCESSIBLE BY BOTH ADMIN & RESTAURANT ADMIN
    // ============================================
   /* Route::middleware('role:super_admin,restaurant_admin')->group(function () {
        // Orders Routes
        Route::controller(OrderController::class)->group(function () {
            Route::get('/orders', 'index')->name('orders.index');
            Route::get('/orders/{order}', 'show')->name('orders.show');
            Route::get('orders/{order}/invoice', 'invoice')->name('orders.invoice');
            Route::post('/kitchen/update-status', 'updateStatus')->name('orders.updateStatus');
        });
    }); */

    //////// NEW  CODE ///////////

    // ============================================
    // ACCESSIBLE BY BOTH ADMIN & RESTAURANT ADMIN
    // ============================================
    Route::middleware('role:super_admin,restaurant_admin')->group(function () {
        Route::controller(OrderController::class)->group(function () {
            Route::get('/orders', 'index')->name('orders.index');
        });
    });

    // ============================================
    // SHARED ROUTES (Admins + Kitchen Staff)
    // ============================================
    Route::middleware('role:super_admin,restaurant_admin,restaurant_user')->group(function () {
        Route::controller(OrderController::class)->group(function () {
            // Yahan humne restaurant_user ko permission de di hai
            Route::get('/orders/{order}', 'show')->name('orders.show');
            Route::get('orders/{order}/invoice', 'invoice')->name('orders.invoice');
            Route::post('/kitchen/update-status', 'updateStatus')->name('orders.updateStatus');
        });
    
        // ======== CONTACT & INBOX ROUTES ========
        Route::controller(ContactController::class)->group(function () {
            Route::get('/contact', 'create')->name('contact.create');
            Route::post('/contact', 'store')->name('contact.store');
            Route::get('/inbox', 'index')->name('contact.index');
        });
        // ========================================
    });
    
    /////// END CODE  ///////////

    // ============================================
    // RESTAURANT USER ROUTES (Kitchen Staff)
    // ============================================
    Route::middleware('role:restaurant_user')->group(function () {
        Route::controller(OrderController::class)->group(function () {
            Route::get('/kitchen', 'kitchen')->name('orders.kitchen');
            Route::post('/kitchen/update-status', 'updateStatus')->name('orders.updateStatus');
        });
    });
});

// Auth Routes
Route::controller(authController::class)->group(function () {
    Route::get('/', 'login')->name('login');
    Route::post('/login-store', 'loginStore')->name('login.store');
    Route::get('/logout', 'logout')->name('logout');
});

// User Menu Routes
Route::controller(userController::class)->group(function () {
    Route::get('/admin/menu/{token}', 'menu')->name('users.menu');
    Route::get('/order-status/{token}', 'checkOrderStatus')->name('users.orderStatus');
});

// Order Store Routes
Route::controller(OrderController::class)->group(function () {
    Route::post('/orders', 'store')->name('orders.store');
});

// Guest Routes 

// 1. Shows the form to collect Name and Phone
Route::get('/menu/{qr_token}', [MenuController::class, 'showGuestForm'])->name('menu.guest');

// 2. Saves the details and redirects to the actual menu
Route::post('/menu/{qr_token}/register', [MenuController::class, 'registerGuest'])->name('menu.register');

// 3. The actual menu page
Route::get('/menu/{qr_token}/view', [MenuController::class, 'ShowMenu'])->name('menu.view');

// Review Route
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
