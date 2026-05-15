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
// ─── MENU ─────────────────────────────────────────────────────
// Route::group(['prefix' => 'menu'], function () {
//     // Halaman Index (index.tsx)
//     Route::get('/', function () {
//         $categories = collect([
//             ['id' => 1, 'name' => 'Main Course', 'sortOrder' => 1],
//             ['id' => 2, 'name' => 'Beverages', 'sortOrder' => 2],
//             ['id' => 3, 'name' => 'Desserts', 'sortOrder' => 3],
//         ]);

//         $menuItems = collect([
//             ['id' => 1, 'categoryId' => 1, 'name' => 'Signature Wagyu Burger', 'price' => 85000, 'sku' => 'WG-001', 'isAvailable' => true, 'imageUrl' => null],
//             ['id' => 2, 'categoryId' => 2, 'name' => 'Iced Matcha Latte', 'price' => 35000, 'sku' => 'BV-002', 'isAvailable' => true, 'imageUrl' => null],
//             ['id' => 3, 'categoryId' => 1, 'name' => 'Chicken Teriyaki', 'price' => 55000, 'sku' => 'CH-005', 'isAvailable' => false, 'imageUrl' => null],
//         ]);

//         return view('menu.index', compact('categories', 'menuItems'));
//     });

//     // Halaman Create (create.tsx)
//     Route::get('/menu/create', function () {
//         $categories = [
//             ['id' => 1, 'name' => 'Main Course'],
//             ['id' => 2, 'name' => 'Beverages']
//         ];
//         return view('menu.create', ['categoryOptions' => $categories]);
//     });
// });
Route::get('/menu', function () {
    return view('menu.index', [
        'menuItems'  => [],
        'categories' => [],
    ]);
});

Route::get('/menu/create', function () {
    return view('menu.create', [
        'categoryOptions' => [],
        'initialData'     => null,
        'itemId'          => null,
    ]);
});

Route::get('/menu/{id}', function ($id) {
    return view('menu.create');
})->name('menu.edit');

// ─── MENU CATEGORIES ──────────────────────────────────────────
// Route untuk Create Category
Route::get('/menu/categories/create', function () {
    return view('menu.categories.create');
})->name('category.create');

// Route untuk Edit Category (menggunakan file yang sama)
Route::get('/menu/categories/{id}', function ($id) {
    return view('menu.categories.create');
})->name('category.edit');

// ─── SHIFT ────────────────────────────────────────────────────
Route::get('/shift', function () {
    return view('shift.index', [
        'history' => [
            [
                'id'            => 1,
                'user'          => ['name' => 'Budi Santoso', 'avatarUrl' => null],
                'openedAt'      => '2026-05-15T08:00:00',
                'closedAt'      => '2026-05-15T16:00:00',
                'startingCash'  => 500000,
                'cashPhysical'  => 1200000,
                'cashExpected'  => 1250000,
                'cashSystem'    => 750000,
                'selisih'       => -50000,
                'status'        => 'closed',
            ],
            [
                'id'            => 2,
                'user'          => ['name' => 'Sari Dewi', 'avatarUrl' => null],
                'openedAt'      => '2026-05-15T16:00:00',
                'closedAt'      => null,
                'startingCash'  => 500000,
                'cashPhysical'  => null,
                'cashExpected'  => null,
                'cashSystem'    => 300000,
                'selisih'       => null,
                'status'        => 'open',
            ],
        ]
    ]);
});

Route::get('/me/history', function () {
    return view('shift.me', [
        'data' => [
            [
                'id'            => 1,
                'openedAt'      => '2026-05-15T08:00:00',
                'closedAt'      => '2026-05-15T16:00:00',
                'startingCash'  => 500000,
                'cashPhysical'  => 1200000,
                'cashExpected'  => 1250000,
                'selisih'       => -50000,
                'status'        => 'closed',
            ],
        ]
    ]);
});

Route::get('/shift/{id}', function ($id) {
    return view('shift.show', [
        'shift' => [
            'id'              => $id,
            'userId'          => 1,
            'status'          => 'closed',
            'modalAwal'       => 500000,
            'openedAt'        => '2026-05-15T08:00:00',
            'closedAt'        => '2026-05-15T16:00:00',
            'cashSystem'      => 750000,
            'cashPhysical'    => 1200000,
            'cashVariance'    => -50000,
            'qrisSystem'      => 300000,
            'debitSystem'     => 150000,
            'transferSystem'  => 100000,
            'notes'           => null,
            'user'            => ['name' => 'Budi Santoso', 'avatarUrl' => null],
            'restaurant'      => ['name' => 'Outlet Dummy'],
            'payments'        => [],
        ]
    ]);
});

Route::get('/me', function () {
    $user = (object)[
        'id' => 61,
        'name' => 'Adnan Mufti Maulana',
        'email' => 'adnan@example.com',
        'role' => 'admin',
        'avatarUrl' => null,
        'status' => 'active',
        'createdAt' => '2024-09-01'
    ];

    $shifts = collect([
        (object)['cashVariance' => 0],
        (object)['cashVariance' => 1000],
        (object)['cashVariance' => -500],
    ]);

    return view('me', compact('user', 'shifts'));
});


// Route::get('/kitchen/kot', function () {
//     return view('kitchen.index', [
//         'currentShift' => [
//             'id'        => 1,
//             'modalAwal' => 500000,
//             'user'      => ['name' => 'Budi Santoso', 'role' => 'Kasir', 'avatarUrl' => null],
//         ],
//         'kotsData' => [
//             [
//                 'id'        => 101,
//                 'status'    => 'sent',
//                 'createdAt' => now()->toJSON(), // ← fix di sini
//                 'order'     => [
//                     'id'     => 1,
//                     'status' => 'pending',
//                     'table'  => ['tableNumber' => 'A1'],
//                 ],
//                 'orderItem' => [
//                     'id'       => 201,
//                     'status'   => 'cooking',
//                     'quantity' => 2,
//                     'notes'    => 'Tidak pakai bawang. Extra pedas',
//                     'menuItem' => [
//                         'name'     => 'Nasi Goreng Spesial',
//                         'category' => ['id' => 1, 'name' => 'Makanan'],
//                     ],
//                 ],
//             ],
//             [
//                 'id'        => 102,
//                 'status'    => 'done',
//                 'createdAt' => now()->toJSON(), // ← fix di sini
//                 'order'     => [
//                     'id'     => 2,
//                     'status' => 'served',
//                     'table'  => ['tableNumber' => 'B2'],
//                 ],
//                 'orderItem' => [
//                     'id'       => 202,
//                     'status'   => 'delivered',
//                     'quantity' => 1,
//                     'notes'    => null,
//                     'menuItem' => [
//                         'name'     => 'Es Teh Manis',
//                         'category' => ['id' => 2, 'name' => 'Minuman'],
//                     ],
//                 ],
//             ],
//         ],
//         'categoriesData' => [
//             ['id' => 1, 'name' => 'Makanan', 'sortOrder' => 1],
//             ['id' => 2, 'name' => 'Minuman', 'sortOrder' => 2],
//         ],
//     ]);
// });

// Route::post('/kitchen/order-item/{id}/status', function ($id) {
//     // Dummy: langsung ok
//     return response()->json(['status' => 'ok']);
// });

Route::get('/kitchen/kot', function () {
    // Data Dummy sesuai prop React
    $categoriesData = collect([
        ['id' => 1, 'name' => 'Makanan', 'sortOrder' => 1],
        ['id' => 2, 'name' => 'Minuman', 'sortOrder' => 2],
    ]);

    $kotsData = collect([
        [
            'id' => 101,
            'status' => 'viewed',
            'createdAt' => now()->toIso8601String(),
            'order' => [
                'id' => 1,
                'status' => 'pending',
                'table' => ['tableNumber' => 'A1'],
            ],
            'orderItem' => [
                'id' => 201,
                'status' => 'cooking',
                'quantity' => 2,
                'notes' => 'Tidak pakai bawang. Extra pedas',
                'menuItem' => [
                    'name' => 'Nasi Goreng Spesial',
                    'category' => ['id' => 1],
                ],
            ],
        ],
    ]);

    $currentShift = [
        'id' => 42,
        'modalAwal' => 500000,
        'user' => ['name' => 'Adnan Mufti', 'role' => 'Chef', 'avatarUrl' => null]
    ];

    return view('kitchen.index', compact('categoriesData', 'kotsData', 'currentShift'));
});

Route::post('/kitchen/order-item/{id}/status', function (Request $request, $id) {
    // Logika update status di sini
    return response()->json(['status' => 'success', 'new_status' => $request->status]);
});