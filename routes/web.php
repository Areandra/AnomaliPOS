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

    // Dummy: langsung sukses
    return response()->json(['status' => 'ok']);
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

Route::get('/users/{id}/edit', function ($id) {
    //dummy data user
    $user = (object) [
        'id' => $id,
        'name' => 'Dion Anugrah',
        'email' => 'dion@untad.com',
        'role' => 'admin',
        'avatarUrl' => ''
    ];

    return view('users.create', compact('user'));
})->name('users.edit');
