<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ─── REDIRECT ROOT ───────────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('login');
});

// ─── LOGIN ───────────────────────────────────────────────────
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $dummy = [
        'email'    => 'admin@anopos.com',
        'password' => 'password123',
    ];

    if (
        $request->email !== $dummy['email'] ||
        $request->password !== $dummy['password']
    ) {
        return response()->json(['code' => 'invalid_credential'], 401);
    }

    // Simpan session login dummy
    session(['dummy_logged_in' => true, 'dummy_email' => $request->email]);

    return response()->json(['status' => 'ok']);
});

// ─── RE-VERIFY DEVICE ────────────────────────────────────────
Route::post('/login/re-verify-device', function (Request $request) {
    // Dummy: selalu lolos
    return response()->json(['status' => 'ok']);
});

// ─── REQUEST TRUST DEVICE ────────────────────────────────────
Route::post('/login/request-trust', function (Request $request) {
    // Dummy: selalu berhasil
    return response()->json(['status' => 'ok']);
});

// ─── RESTAURANT PIN ──────────────────────────────────────────
Route::get('/login/restaurant-pin', function () {
    return view('auth.restaurant-pin');
})->name('restaurant-pin');

Route::post('/login/restaurant-pin', function (Request $request) {
    $dummyPin = '123456';

    if ($request->pin !== $dummyPin) {
        return response()->json([
            'code'    => 'invalid_pin',
            'message' => 'PIN salah, coba lagi',
        ], 401);
    }

    session(['dummy_pin_verified' => true]);

    return response()->json([
        'status'   => 'ok',
        'redirect' => '/dashboard',
    ]);
});

// ─── DASHBOARD (placeholder) ─────────────────────────────────
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// ─── RESET PASSWORD ──────────────────────────────────────────
Route::get('/reset-password/{userId}', function ($userId) {
    return view('auth.reset-password', [
        'userId'   => $userId,
        'userName' => 'Admin Dummy',
    ]);
})->name('reset-password');

Route::post('/reset-password', function (Request $request) {
    $validated = $request->validate([
        'password'              => 'required|min:6|confirmed',
        'password_confirmation' => 'required',
    ]);

    // Dummy: langsung sukses
    return response()->json(['status' => 'ok']);
});

// ─── RESET PIN ───────────────────────────────────────────────
Route::get('/reset-pin/{restaurantId}', function ($restaurantId) {
    return view('auth.reset-pin', [
        'restaurantId'   => $restaurantId,
        'restaurantName' => 'Outlet Dummy',
    ]);
})->name('reset-pin');

Route::post('/reset-pin', function (Request $request) {
    $validated = $request->validate([
        'pin'              => 'required|digits:6|confirmed',
        'pin_confirmation' => 'required',
    ]);

    // Dummy: langsung sukses
    return response()->json(['status' => 'ok']);
});

// ─── ERROR PAGES TEST ─────────────────────────────────────────
Route::get('/bill-closed', function () {
    return view('errors.bill-closed', [
        'sessionToken' => 'dummy-session-abc123',
    ]);
});

Route::get('/forbidden', function () {
    return view('errors.forbidden', [
        'redirectUrl' => '/dashboard',
    ]);
});

Route::get('/server-error', function () {
    return view('errors.server-error');
});

Route::get('/not-found', function () {
    return view('errors.not-found');
});

// ─── DEVICE VERIFICATION STATUS ──────────────────────────────
Route::get('/device-verification', function () {
    return view('auth.device-verification', [
        'status'     => 'success',       // 'success' | 'error'
        'message'    => 'Perangkat Anda telah berhasil diverifikasi oleh Admin.',
        'deviceName' => 'Laptop Kasir Depan',
    ]);
})->name('device-verification');

Route::get('/device-verification-failed', function () {
    return view('auth.device-verification', [
        'status'     => 'error',
        'message'    => 'Permintaan akses perangkat Anda ditolak oleh Admin.',
        'deviceName' => null,
    ]);
});