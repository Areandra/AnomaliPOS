<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('register')->group(function () {
    Route::view('/', 'auth.register')->name('register-form');
    Route::post('/', [AuthController::class, 'register']);
});

Route::get('/account-activation/{userId}', [AuthController::class, 'register'])
    ->name('account-activation')
    ->middleware('signed');

// ========================================================================
// Login User
// ========================================================================

Route::prefix('login')->group(function () {

    Route::view('/', 'auth.login')->name('login');

    Route::post('/', [AuthController::class, 'login']);

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
    ])->name('verify_device')->middleware('signed');
});

// ========================================================================
// Restaurant PIN
// ========================================================================

Route::prefix('login/restaurant-pin')
    ->middleware(['auth', 'trusted.device'])
    ->group(function () {

        Route::view('/', 'auth.restaurant-pin');

        Route::post('/', [AuthController::class, 'restaurantPin']);
    });

// ========================================================================
// Logout
// ========================================================================

Route::post('/sign-out', [AuthController::class, 'logout'])
    ->middleware(['auth', 'auth.restaurant']);

// ========================================================================
// Change PIN
// ========================================================================

Route::middleware('auth.restaurant')->group(function () {

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
// Change Password
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
