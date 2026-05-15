<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;

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

Route::post('/api/shifts/open',  [ShiftController::class, 'open'])->name('shifts.open');
Route::post('/api/shifts/close', [ShiftController::class, 'close'])->name('shifts.close');

// ========================================================================
// ORDER API
// ========================================================================

Route::post('/api/order',                    [OrderController::class, 'store']);
Route::get('/api/order/{id}',                [OrderController::class, 'show']);
Route::post('/api/order/add-item',           [OrderController::class, 'addItem']);
Route::post('/api/order/update-qty',         [OrderController::class, 'updateQty']);
Route::post('/api/order/delete-item',        [OrderController::class, 'deleteItem']);
Route::post('/api/order/place-order/{id}',   [OrderController::class, 'placeOrder']);
Route::post('/api/order/{id}/notes',         [OrderController::class, 'makeNotes']);
Route::post('/api/session/{token}/end',      [OrderController::class, 'endSession']);

// ========================================================================
// PAYMENT API
// ========================================================================

Route::post('/api/payments', [PaymentController::class, 'store']);

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

Route::get('/users', function () {
    return view('users.index');
})->name('users.index');

Route::get('/users/create', function () {
    return view('users.create');
})->name('users.create');

Route::post('/users', function (Request $request) {
    return redirect()->route('users.create');
})->name('users.store');

Route::put('/users/{id}', function (Request $request, $id) {
    return redirect()->route('users.create');
})->name('users.update');

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
