<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
])->middleware([
    'auth',
    'auth.restaurant'
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

Route::get('/change-password/{userId}', [
    AuthController::class,
    'showChangePassword'
])
    ->name('change-password')
    ->middleware('signed');

Route::post('/change-password/{userId}', [
    AuthController::class,
    'updatePassword'
]);

// ========================================================================
// USER MANAGEMENT
// ========================================================================

Route::middleware([
    'auth',
    'auth.restaurant',
])
    ->group(function () {

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
