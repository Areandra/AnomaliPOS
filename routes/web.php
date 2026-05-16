<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\MenuItemController;

// ========================================================================
// REGISTER
// ========================================================================

Route::prefix('register')->group(function () {

    Route::view('/', 'auth.register')
        ->name('register-form');

    Route::post('/', [
        AuthController::class,
        'register'
    ]);
});

Route::get('/account-activation', [
    AuthController::class,
    'activationProgres'
])
    ->name('account-activation')
    ->middleware('signed');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index']);

// Redirect legacy URLs
Route::redirect('/menu', '/menu/items');
Route::redirect('/shift', '/shifts');

Route::middleware(['auth', 'auth.restaurant'])->group(function () {
    // ========================================================================
    // CASHIER ROUTES
    // ========================================================================

    Route::get('/cashier', [CashierController::class, 'index'])
        ->name('cashier.index');

    Route::get('/cashier/order/start', [CashierController::class, 'start'])
        ->name('cashier.order.start');

    // ========================================================================
    // SHIFT API
    // ========================================================================

    Route::post('/shifts/open',  [ShiftController::class, 'open'])->name('shifts.open');
    Route::post('/shifts/close', [ShiftController::class, 'close'])->name('shifts.close');

    // ========================================================================
    // ORDER API
    // ========================================================================

    Route::post('/order',                    [OrderController::class, 'store']);
    Route::get('/order/{id}',                [OrderController::class, 'show']);
    Route::post('/order/add-item',           [OrderController::class, 'addItem']);
    Route::post('/order/update-qty',         [OrderController::class, 'updateQty']);
    Route::post('/order/delete-item',        [OrderController::class, 'deleteItem']);
    Route::post('/order/place-order/{id}',   [OrderController::class, 'placeOrder']);
    Route::post('/order/{id}/notes',         [OrderController::class, 'makeNotes']);
    Route::post('/session/{token}/end',      [OrderController::class, 'endSession']);

    // ========================================================================
    // PAYMENT API
    // ========================================================================

    Route::post('/payments', [PaymentController::class, 'store']);
});

// ========================================================================
// LOGIN USER
// ========================================================================

Route::prefix('login')->group(function () {

    Route::view('/', 'auth.login')
        ->name('login');

    Route::post('/', [
        AuthController::class,
        'login'
    ]);

    Route::middleware('auth')->group(function () {

        Route::post('/re-verify-device', [
            AuthController::class,
            'verifyNewDevice'
        ]);

        Route::post('/request-trust', [
            AuthController::class,
            'requestToTrust'
        ]);
    });

    Route::get('/verify-device/{userId}', [
        AuthController::class,
        'verify'
    ])
        ->name('verify_device')
        ->middleware('signed');
});

// ========================================================================
// RESTAURANT PIN
// ========================================================================

Route::prefix('login/restaurant-pin')
    ->middleware([
        'auth',
        'trusted.device'
    ])
    ->group(function () {

        Route::view('/', 'auth.restaurant-pin');

        Route::post('/', [
            AuthController::class,
            'restaurantPin'
        ]);
    });

// ========================================================================
// LOGOUT
// ========================================================================

Route::post('/sign-out', [
    AuthController::class,
    'logout'
]);

// ========================================================================
// CHANGE PIN
// ========================================================================

Route::middleware('auth.restaurant')
    ->group(function () {

        Route::post('/request-change-pin', [
            AuthController::class,
            'requestToChangePin'
        ]);
    });

Route::get('/change-restaurant-pin/{restaurantId}', [
    AuthController::class,
    'showChangePin'
])
    ->name('change-restaurant-pin')
    ->middleware('signed');

Route::post('/change-restaurant-pin/{restaurantId}', [
    AuthController::class,
    'updatePin'
]);

// ========================================================================
// CHANGE PASSWORD
// ========================================================================

Route::prefix('request-change-password')->group(function () {

    Route::get('/', function () {
        return view('auth.request-reset-password-form');
    })->name('request-change-password-form');

    Route::post('/', [
        AuthController::class,
        'requestToChangePassword'
    ])->name('request-change-password');
});

Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('change-password');

// ========================================================================
// USER MANAGEMENT
// ========================================================================

Route::middleware([
    'auth',
    'auth.restaurant',
    'plan.acsess'
])->group(function () {

    Route::get('/users', [
        UserController::class,
        'index'
    ])->name('users.index');

    Route::get('/users/create', [
        UserController::class,
        'show'
    ])->defaults('id', 'create')
        ->name('users.create');

    Route::post('/users', [
        UserController::class,
        'store'
    ])->name('users.store');

    Route::get('/users/{id}/edit', [
        UserController::class,
        'show'
    ])->name('users.edit');

    Route::put('/users/{id}', [
        UserController::class,
        'update'
    ])->name('users.update');

    Route::delete('/users/{id}', [
        UserController::class,
        'destroy'
    ])->name('users.destroy');

    Route::post('/users/{id}/toggle-status', [
        UserController::class,
        'toggleStatus'
    ])->name('users.toggle-status');

    Route::put('/users/{id}/password', [
        UserController::class,
        'updatePassword'
    ])->name('users.update-password');
});

// ========================================================================
// TABLE CRUD
// ========================================================================

Route::middleware(['auth', 'auth.restaurant', 'plan.acsess'])->group(function () {
    Route::get('/tables', [TableController::class, 'index'])->name('tables.index');
    Route::get('/tables/create', [TableController::class, 'create'])->name('tables.create');
    Route::post('/tables', [TableController::class, 'store'])->name('tables.store');
    Route::get('/tables/{id}/edit', [TableController::class, 'edit'])->name('tables.edit');
    Route::put('/tables/{id}', [TableController::class, 'update'])->name('tables.update');
    Route::delete('/tables/{id}', [TableController::class, 'destroy'])->name('tables.destroy');
});

// ========================================================================
// MENU CATEGORY CRUD
// ========================================================================

Route::middleware(['auth', 'auth.restaurant', 'plan.acsess'])->group(function () {
    Route::get('/menu/categories', [MenuCategoryController::class, 'index'])->name('menu.categories.index');
    Route::get('/menu/categories/create', [MenuCategoryController::class, 'create'])->name('menu.categories.create');
    Route::post('/menu/categories', [MenuCategoryController::class, 'store'])->name('menu.categories.store');
    Route::get('/menu/categories/{id}/edit', [MenuCategoryController::class, 'edit'])->name('menu.categories.edit');
    Route::put('/menu/categories/{id}', [MenuCategoryController::class, 'update'])->name('menu.categories.update');
    Route::delete('/menu/categories/{id}', [MenuCategoryController::class, 'destroy'])->name('menu.categories.destroy');
});

// ========================================================================
// MENU ITEM CRUD
// ========================================================================

Route::middleware(['auth', 'auth.restaurant', 'plan.acsess'])->group(function () {
    Route::get('/menu/items', [MenuItemController::class, 'index'])->name('menu.items.index');
    Route::get('/menu/items/create', [MenuItemController::class, 'create'])->name('menu.items.create');
    Route::post('/menu/items', [MenuItemController::class, 'store'])->name('menu.items.store');
    Route::get('/menu/items/{id}/edit', [MenuItemController::class, 'edit'])->name('menu.items.edit');
    Route::put('/menu/items/{id}', [MenuItemController::class, 'update'])->name('menu.items.update');
    Route::delete('/menu/items/{id}', [MenuItemController::class, 'destroy'])->name('menu.items.destroy');
    Route::post('/menu/items/{id}/toggle-available', [MenuItemController::class, 'toggleAvailable'])->name('menu.items.toggle-available');
});

// ========================================================================
// SHIFT CRUD
// ========================================================================

Route::middleware(['auth', 'auth.restaurant', 'plan.acsess'])->group(function () {
    Route::get('/shifts', [ShiftController::class, 'history'])->name('shifts.index');
    Route::get('/shifts/{id}', [ShiftController::class, 'show'])->name('shifts.show');
    Route::delete('/shifts/{id}', [ShiftController::class, 'destroy'])->name('shifts.destroy');
    Route::get('/shifts/me', [ShiftController::class, 'historyMe'])->name('shifts.me');
    Route::get('/attendance/me', [ShiftController::class, 'attendenceMe'])->name('attendance.me');
});
